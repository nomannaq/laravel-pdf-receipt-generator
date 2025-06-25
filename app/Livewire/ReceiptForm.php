<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\Template;

class ReceiptForm extends Component
{
    public $items = [];
    public $customerName = '';
    public $receiptGenerated = false;
    public $receiptUrl = '';
    public $htmlContent = '';
    public $activeTab = 'receipt'; // 'receipt', 'html', or 'templates'
    public $selectedTemplate = null;
    public $selectedTemplateType = 'pdf';
    public $templateName = '';
    public $templateDescription = '';
    public $showTemplateModal = false;
    public $showPreviewModal = false;
    public $previewContent = '';
    public $editingTemplate = null;

    public function mount()
    {
        $this->items = [
            ['name' => '', 'quantity' => 1, 'price' => 0],
        ];
    }

    public function addItem()
    {
        $this->items[] = ['name' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateSubtotal()
    {
        return collect($this->items)->sum(fn($item) => $item['quantity'] * $item['price']);
    }

    public function calculateTax($subtotal)
    {
        return $subtotal * 0.1;
    }

    public function calculateDiscount($subtotal)
    {
        return $subtotal > 100 ? 10 : 0;
    }

    public function calculateTotal()
    {
        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        return $subtotal + $tax - $discount;
    }

    public function generateReceipt()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        $total = $subtotal + $tax - $discount;

        // Save to DB
        $receipt = Receipt::create(compact('subtotal', 'tax', 'discount', 'total'));

        foreach ($this->items as $item) {
            if (!empty($item['name'])) {
                $receipt->items()->create($item);
            }
        }

        $data = [
            'customerName' => $this->customerName,
            'items' => $this->items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('receipt.pdf', $data);
        $filename = 'receipt_' . $receipt->id . '.pdf';
        $path = storage_path('app/public/' . $filename);
        $pdf->save($path);

        $this->receiptGenerated = true;
        $this->receiptUrl = asset('storage/' . $filename);

        session()->flash('success', 'Receipt generated successfully!');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->receiptGenerated = false;
    }

    public function generateHtmlPdf()
    {
        $this->validate([
            'htmlContent' => 'required|string|min:10',
        ]);

        // Wrap the HTML content with our styling
        $styledHtml = $this->wrapHtmlWithStyling($this->htmlContent);

        // Generate PDF
        $pdf = Pdf::loadHTML($styledHtml);
        $filename = 'custom_html_' . time() . '.pdf';
        $path = storage_path('app/public/' . $filename);
        $pdf->save($path);

        $this->receiptGenerated = true;
        $this->receiptUrl = asset('storage/' . $filename);

        session()->flash('success', 'PDF generated successfully from HTML content!');
    }

    private function wrapHtmlWithStyling($content)
    {
        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <style>
                body { 
                    font-family: sans-serif; 
                    margin: 20px; 
                    line-height: 1.6;
                    color: #333;
                }
                .header { text-align: center; margin-bottom: 30px; }
                .customer-info { margin-bottom: 20px; }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 20px 0; 
                }
                th, td { 
                    border: 1px solid #e2e8f0; 
                    padding: 8px; 
                    text-align: left; 
                }
                th { 
                    background-color: #f1f5f9; 
                    font-weight: bold;
                }
                .summary { 
                    margin-top: 20px; 
                    text-align: right; 
                }
                .total { 
                    font-weight: bold; 
                    font-size: 18px; 
                }
                h1, h2, h3, h4, h5, h6 {
                    color: #2d3748;
                    margin-top: 0;
                }
                p {
                    margin: 10px 0;
                }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .text-left { text-align: left; }
                .font-bold { font-weight: bold; }
                .mt-4 { margin-top: 16px; }
                .mb-4 { margin-bottom: 16px; }
                .p-4 { padding: 16px; }
                .border { border: 1px solid #e2e8f0; }
                .rounded { border-radius: 4px; }
                .bg-gray-100 { background-color: #f7fafc; }
                .bg-blue-100 { background-color: #ebf8ff; }
                .bg-green-100 { background-color: #f0fff4; }
                .bg-yellow-100 { background-color: #fffff0; }
                .bg-red-100 { background-color: #fff5f5; }
                .text-blue-800 { color: #2c5282; }
                .text-green-800 { color: #276749; }
                .text-yellow-800 { color: #744210; }
                .text-red-800 { color: #742a2a; }
            </style>
        </head>
        <body>
            ' . $content . '
        </body>
        </html>';
    }

    public function loadTemplate($templateName)
    {
        $templates = [
            'invoice' => '
<div class="text-center">
    <h1>INVOICE</h1>
    <p>Invoice #: INV-2025-001</p>
    <p>Date: ' . date('F j, Y') . '</p>
</div>

<div class="mt-4">
    <p><strong>Bill To:</strong></p>
    <p>Customer Name<br>
    123 Customer Street<br>
    City, State 12345</p>
</div>

<table class="mt-4">
    <thead>
        <tr>
            <th>Description</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Consulting Services</td>
            <td class="text-center">10</td>
            <td class="text-right">$150.00</td>
            <td class="text-right">$1,500.00</td>
        </tr>
        <tr>
            <td>Development Work</td>
            <td class="text-center">5</td>
            <td class="text-right">$200.00</td>
            <td class="text-right">$1,000.00</td>
        </tr>
    </tbody>
</table>

<div class="text-right mt-4">
    <p>Subtotal: $2,500.00</p>
    <p>Tax (8%): $200.00</p>
    <p class="font-bold">Total: $2,700.00</p>
</div>',
            
            'report' => '
<div class="text-center">
    <h1>Monthly Report</h1>
    <p class="mb-4">Report for ' . date('F Y') . '</p>
</div>

<div class="bg-blue-100 p-4 rounded mb-4">
    <h2>Executive Summary</h2>
    <p>This report provides an overview of key metrics and performance indicators for the current month.</p>
</div>

<h3>Key Metrics</h3>
<table>
    <thead>
        <tr>
            <th>Metric</th>
            <th>Current Month</th>
            <th>Previous Month</th>
            <th>Change</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Revenue</td>
            <td class="text-right">$125,000</td>
            <td class="text-right">$118,000</td>
            <td class="text-right text-green-800">+5.9%</td>
        </tr>
        <tr>
            <td>Customers</td>
            <td class="text-right">1,250</td>
            <td class="text-right">1,180</td>
            <td class="text-right text-green-800">+5.9%</td>
        </tr>
        <tr>
            <td>Orders</td>
            <td class="text-right">450</td>
            <td class="text-right">420</td>
            <td class="text-right text-green-800">+7.1%</td>
        </tr>
    </tbody>
</table>

<div class="bg-green-100 p-4 rounded mt-4">
    <h3>Conclusion</h3>
    <p>Performance has improved across all key metrics this month, indicating positive growth trends.</p>
</div>',
            
            'certificate' => '
<div class="text-center">
    <h1 style="font-size: 24px; margin-bottom: 20px;">CERTIFICATE OF COMPLETION</h1>
</div>

<div class="text-center mt-4 mb-4">
    <p style="font-size: 18px;">This is to certify that</p>
    <h2 style="font-size: 22px; margin: 20px 0; text-decoration: underline;">John Doe</h2>
    <p style="font-size: 18px;">has successfully completed the course</p>
    <h3 style="font-size: 20px; margin: 20px 0;">"Advanced Web Development"</h3>
</div>

<div class="text-center mt-4">
    <p>Date of Completion: ' . date('F j, Y') . '</p>
    <p class="mt-4">Duration: 40 Hours</p>
    <p>Grade: A</p>
</div>

<div style="margin-top: 60px;">
    <table style="width: 100%; border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: center; width: 50%;">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;">
                    <p class="mt-2">Instructor Signature</p>
                </div>
            </td>
            <td style="border: none; text-align: center; width: 50%;">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;">
                    <p class="mt-2">Date</p>
                </div>
            </td>
        </tr>
    </table>
</div>'
        ];

        $this->htmlContent = $templates[$templateName] ?? '';
    }

    public function getTemplatesByType()
    {
        return Template::byType($this->selectedTemplateType)->get();
    }

    public function selectTemplate($templateId)
    {
        $template = Template::find($templateId);
        if ($template) {
            $this->selectedTemplate = $template;
            $this->htmlContent = $template->content;
        }
    }

    public function openTemplateModal()
    {
        $this->showTemplateModal = true;
        $this->resetTemplateForm();
    }

    public function closeTemplateModal()
    {
        $this->showTemplateModal = false;
        $this->resetTemplateForm();
    }

    public function resetTemplateForm()
    {
        $this->templateName = '';
        $this->templateDescription = '';
        $this->editingTemplate = null;
    }

    public function saveTemplate()
    {
        $this->validate([
            'templateName' => 'required|string|max:255',
            'templateDescription' => 'nullable|string|max:500',
            'htmlContent' => 'required|string|min:10',
            'selectedTemplateType' => 'required|in:pdf,email,sms'
        ]);

        if ($this->editingTemplate) {
            // Update existing template
            $this->editingTemplate->update([
                'name' => $this->templateName,
                'description' => $this->templateDescription,
                'content' => $this->htmlContent,
                'type' => $this->selectedTemplateType
            ]);
            session()->flash('success', 'Template updated successfully!');
        } else {
            // Create new template
            Template::create([
                'name' => $this->templateName,
                'description' => $this->templateDescription,
                'content' => $this->htmlContent,
                'type' => $this->selectedTemplateType,
                'is_default' => false
            ]);
            session()->flash('success', 'Template saved successfully!');
        }

        $this->closeTemplateModal();
    }

    public function editTemplate($templateId)
    {
        $template = Template::find($templateId);
        if ($template) {
            $this->editingTemplate = $template;
            $this->templateName = $template->name;
            $this->templateDescription = $template->description;
            $this->htmlContent = $template->content;
            $this->selectedTemplateType = $template->type;
            $this->showTemplateModal = true;
        }
    }

    public function deleteTemplate($templateId)
    {
        $template = Template::find($templateId);
        if ($template && !$template->is_default) {
            $template->delete();
            session()->flash('success', 'Template deleted successfully!');
        } else {
            session()->flash('error', 'Cannot delete default templates.');
        }
    }

    public function previewTemplate($templateId)
    {
        $template = Template::find($templateId);
        if ($template) {
            $this->previewContent = $this->renderTemplatePreview($template);
            $this->showPreviewModal = true;
        }
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
        $this->previewContent = '';
    }

    private function renderTemplatePreview($template)
    {
        $sampleData = [
            'customer_name' => 'John Doe',
            'company_name' => 'Your Company',
            'date' => date('F j, Y'),
            'invoice_number' => 'INV-2025-001',
            'receipt_number' => 'REC-2025-001',
            'total' => '150.00',
            'subtotal' => '135.00',
            'tax' => '15.00',
            'discount' => '0.00'
        ];

        $content = $template->content;
        
        // Simple template variable replacement
        foreach ($sampleData as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        // Handle simple loops for items (basic implementation)
        if (strpos($content, '{{#items}}') !== false) {
            $itemsSection = preg_match('/{{#items}}(.*?){{\/items}}/s', $content, $matches);
            if ($itemsSection) {
                $itemTemplate = $matches[1];
                $sampleItems = [
                    ['name' => 'Sample Item 1', 'quantity' => '2', 'price' => '50.00', 'total' => '100.00'],
                    ['name' => 'Sample Item 2', 'quantity' => '1', 'price' => '50.00', 'total' => '50.00']
                ];
                
                $itemsHtml = '';
                foreach ($sampleItems as $index => $item) {
                    $itemHtml = $itemTemplate;
                    $itemHtml = str_replace('{{index}}', $index + 1, $itemHtml);
                    foreach ($item as $key => $value) {
                        $itemHtml = str_replace('{{' . $key . '}}', $value, $itemHtml);
                    }
                    $itemsHtml .= $itemHtml;
                }
                
                $content = preg_replace('/{{#items}}.*?{{\/items}}/s', $itemsHtml, $content);
            }
        }

        return $content;
    }

    public function render()
    {
        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        $total = $this->calculateTotal();
        $templates = $this->getTemplatesByType();

        return view('livewire.receipt-form', compact('subtotal', 'tax', 'discount', 'total', 'templates'));
    }
}
