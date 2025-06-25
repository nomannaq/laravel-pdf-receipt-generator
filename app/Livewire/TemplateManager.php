<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Template;
use App\Models\Assignment;
use Barryvdh\DomPDF\Facade\Pdf;

class TemplateManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = 'all';
    public $showModal = false;
    public $showPreviewModal = false;
    public $showEmailPreviewModal = false;
    public $editingTemplate = null;
    
    // Form fields
    public $name = '';
    public $description = '';
    public $type = 'pdf';
    public $content = '';
    public $emailSubject = '';
    public $selectedPdfAttachments = [];
    
    // Preview data
    public $previewContent = '';
    public $previewSubject = '';
    public $emailPreviewHtml = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'type' => 'required|in:pdf,email,sms',
        'content' => 'required|string',
        'emailSubject' => 'nullable|string|max:255',
        'selectedPdfAttachments' => 'array'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function openModal($templateId = null)
    {
        if ($templateId) {
            $this->editTemplate($templateId);
        } else {
            $this->resetForm();
            $this->editingTemplate = null;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->type = 'pdf';
        $this->content = '';
        $this->emailSubject = '';
        $this->selectedPdfAttachments = [];
        $this->resetValidation();
    }

    public function editTemplate($templateId)
    {
        $template = Template::findOrFail($templateId);
        $this->editingTemplate = $template;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->type = $template->type;
        $this->content = $template->content;
        $this->emailSubject = $template->email_subject ?? '';
        $this->selectedPdfAttachments = $template->pdf_attachments ?? [];
    }

    public function saveTemplate()
    {
        $this->validate();

        $templateData = [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'content' => $this->content,
            'email_subject' => $this->type === 'email' ? $this->emailSubject : null,
            'pdf_attachments' => $this->type === 'email' ? $this->selectedPdfAttachments : null,
            'is_default' => false
        ];

        if ($this->editingTemplate) {
            $this->editingTemplate->update($templateData);
            session()->flash('success', 'Template updated successfully!');
        } else {
            Template::create($templateData);
            session()->flash('success', 'Template created successfully!');
        }

        $this->closeModal();
    }

    public function deleteTemplate($templateId)
    {
        $template = Template::findOrFail($templateId);
        
        if ($template->is_default) {
            session()->flash('error', 'Cannot delete default templates.');
            return;
        }

        if ($template->isLinkedToProcedure()) {
            $procedures = $template->getLinkedProcedures();
            $procedureNames = $procedures->pluck('name')->join(', ');
            session()->flash('error', "Template is linked to Procedure \"{$procedureNames}\". Remove template from procedure before deleting this Template.");
            return;
        }

        $template->delete();
        session()->flash('success', 'Template deleted successfully!');
    }

    public function previewTemplate($templateId)
    {
        $template = Template::findOrFail($templateId);
        
        // Create sample assignment data
        $sampleAssignment = new Assignment([
            'creditor_name' => 'Sample Creditor Ltd.',
            'creditor_contact_person_name' => 'John Smith',
            'creditor_contact_person_email' => 'john.smith@creditor.com',
            'creditor_contact_person_phone' => '+1-555-0123',
            'debtor_name' => 'Sample Debtor Inc.',
            'debtor_contact_person_name' => 'Jane Doe',
            'balance_capital' => 5000.00,
            'balance_penalty_interest' => 250.00,
            'balance_debt_collection_fee' => 150.00,
            'balance_overpayment' => 0.00,
            'penalty_interest_rate' => 8.5000,
            'total_for_due_date' => 5400.00,
            'due_date' => now()->addDays(30)
        ]);

        $rendered = $template->renderWithVariables($sampleAssignment);
        $this->previewContent = $rendered['content'];
        $this->previewSubject = $rendered['subject'] ?? '';
        
        if ($template->type === 'email') {
            $this->showEmailPreview($template, $rendered);
        } else {
            $this->showPreviewModal = true;
        }
    }

    public function showEmailPreview($template, $rendered)
    {
        $this->emailPreviewHtml = view('emails.template-preview', [
            'subject' => $rendered['subject'],
            'content' => $rendered['content'],
            'template' => $template
        ])->render();
        
        $this->showEmailPreviewModal = true;
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
        $this->showEmailPreviewModal = false;
        $this->previewContent = '';
        $this->previewSubject = '';
        $this->emailPreviewHtml = '';
    }

    public function generatePdfPreview($templateId)
    {
        $template = Template::findOrFail($templateId);
        
        if ($template->type !== 'pdf') {
            return;
        }

        $sampleAssignment = new Assignment([
            'creditor_name' => 'Sample Creditor Ltd.',
            'debtor_name' => 'Sample Debtor Inc.',
            'balance_capital' => 5000.00,
            'total_for_due_date' => 5400.00,
        ]);

        $rendered = $template->renderWithVariables($sampleAssignment);
        
        try {
            $pdf = Pdf::loadHTML($this->wrapHtmlWithStyling($rendered['content']));
            $filename = 'preview_' . $template->id . '_' . time() . '.pdf';
            $path = storage_path('app/public/' . $filename);
            $pdf->save($path);
            
            return response()->download($path);
        } catch (\Exception $e) {
            session()->flash('error', 'Error generating PDF preview: ' . $e->getMessage());
        }
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
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .font-bold { font-weight: bold; }
            </style>
        </head>
        <body>
            ' . $content . '
        </body>
        </html>';
    }

    public function insertVariable($variable)
    {
        $this->content .= '{{' . $variable . '}}';
        $this->dispatch('variable-inserted');
    }

    public function getAvailableVariables()
    {
        return [
            'Creditor' => [
                'creditor_name' => 'Creditor Name',
                'creditor_contact_person_name' => 'Contact Person Name',
                'creditor_contact_person_email' => 'Contact Person Email',
                'creditor_contact_person_phone' => 'Contact Person Phone',
            ],
            'Debtor' => [
                'debtor_name' => 'Debtor Name',
                'debtor_contact_person_name' => 'Contact Person Name',
            ],
            'Overview' => [
                'balance_capital' => 'Capital',
                'balance_penalty_interest' => 'Penalty Interest',
                'balance_debt_collection_fee' => 'Debt Collection Fee / Perintäkulu',
                'balance_overpayment' => 'Overpayment',
                'penalty_interest_rate' => 'Penalty Interest Rate',
                'total_for_due_date' => 'Total (For specific due date)',
                'due_date' => 'Due Date',
            ]
        ];
    }

    public function render()
    {
        $query = Template::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(12);
        $pdfTemplates = Template::where('type', 'pdf')->get();
        $availableVariables = $this->getAvailableVariables();

        return view('livewire.template-manager', compact('templates', 'pdfTemplates', 'availableVariables'));
    }
}
