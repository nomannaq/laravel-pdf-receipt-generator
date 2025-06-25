<div class="space-y-6">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="switchTab('receipt')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'receipt' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Receipt Generator
            </button>
            <button wire:click="switchTab('html')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'html' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                HTML to PDF
            </button>
            <button wire:click="switchTab('templates')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'templates' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Template Manager
            </button>
        </nav>
    </div>

    <!-- Receipt Generator Tab -->
    @if($activeTab === 'receipt')
    <form wire:submit.prevent="generateReceipt" class="space-y-6">
        <!-- Customer Name -->
        <div>
            <label for="customerName" class="block text-sm font-medium text-gray-700">Customer Name</label>
            <input type="text" id="customerName" wire:model="customerName" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            @error('customerName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Items Section -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Items</h3>
                <button type="button" wire:click="addItem" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Item
                </button>
            </div>

            @foreach($items as $index => $item)
                <div class="border border-gray-200 rounded-md p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" wire:model="items.{{ $index }}.name" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            @error("items.{$index}.name") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" wire:model="items.{{ $index }}.quantity" min="1" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            @error("items.{$index}.quantity") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <div class="flex">
                                <input type="number" wire:model="items.{{ $index }}.price" step="0.01" min="0" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                @if(count($items) > 1)
                                    <button type="button" wire:click="removeItem({{ $index }})" 
                                            class="ml-2 mt-1 inline-flex items-center px-2 py-1 border border-transparent text-sm font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                                        Remove
                                    </button>
                                @endif
                            </div>
                            @error("items.{$index}.price") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    @if(isset($item['name']) && isset($item['quantity']) && isset($item['price']) && $item['name'] && $item['quantity'] && $item['price'])
                        <div class="mt-2 text-right text-sm text-gray-600">
                            Line Total: ${{ number_format($item['quantity'] * $item['price'], 2) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="bg-gray-50 p-4 rounded-md">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (10%):</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Discount:</span>
                    <span>-${{ number_format($discount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-2">
                    <span>Total:</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Generate Button -->
        <div class="text-center">
            <button type="submit"
                    class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md
                           text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Receipt PDF
            </button>
        </div>
    </form>
    @endif

    <!-- HTML to PDF Tab -->
    @if($activeTab === 'html')
    <form wire:submit.prevent="generateHtmlPdf" class="space-y-6">
        <!-- Template Selection -->
        <div class="bg-gray-50 p-4 rounded-md">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Template Options</h4>
            
            <!-- Template Type Selector -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Template Type</label>
                <select wire:model="selectedTemplateType" wire:change="$refresh" 
                        class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="pdf">PDF Templates</option>
                    <option value="email">Email Templates</option>
                    <option value="sms">SMS Templates</option>
                </select>
            </div>
            
            <!-- Available Templates -->
            @if($templates->count() > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Template</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($templates as $template)
                    <button type="button" wire:click="selectTemplate({{ $template->id }})" 
                            class="text-left p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors {{ $selectedTemplate && $selectedTemplate->id === $template->id ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <div class="font-medium text-sm">{{ $template->name }}</div>
                        @if($template->description)
                        <div class="text-xs text-gray-600 mt-1">{{ $template->description }}</div>
                        @endif
                        @if($template->is_default)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            Default
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="loadTemplate('invoice')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quick Invoice
                </button>
                <button type="button" wire:click="loadTemplate('report')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quick Report
                </button>
                <button type="button" wire:click="loadTemplate('certificate')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quick Certificate
                </button>
            </div>
        </div>

        <div>
            <label for="htmlContent" class="block text-sm font-medium text-gray-700 mb-2">
                HTML Content
            </label>
            <p class="text-sm text-gray-600 mb-4">
                Enter your HTML content below. You can use basic HTML tags and the following CSS classes:
                <code>text-center</code>, <code>text-right</code>, <code>font-bold</code>, <code>mt-4</code>, <code>mb-4</code>, 
                <code>p-4</code>, <code>border</code>, <code>rounded</code>, <code>bg-gray-100</code>, <code>bg-blue-100</code>, etc.
            </p>
            
            <textarea 
                id="htmlContent" 
                wire:model="htmlContent" 
                rows="15" 
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono"
                placeholder="Example:
<div class='text-center'>
    <h1>My Document</h1>
    <p class='mb-4'>This is a sample document</p>
</div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Product 1</td>
            <td>Description here</td>
            <td class='text-right'>$100.00</td>
        </tr>
    </tbody>
</table>

<div class='text-right mt-4'>
    <p class='font-bold'>Total: $100.00</p>
</div>"></textarea>
            @error('htmlContent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Generate Button -->
        <div class="flex justify-center space-x-4">
            <button type="submit"
                    class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md
                           text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate PDF from HTML
            </button>
            
            @if($htmlContent)
            <button type="button" wire:click="openTemplateModal"
                    class="inline-flex justify-center py-3 px-6 border border-indigo-600 shadow-sm text-base font-medium rounded-md
                           text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save as Template
            </button>
            @endif
        </div>
    </form>
    @endif

    <!-- Template Manager Tab -->
    @if($activeTab === 'templates')
    <div class="space-y-6">
        <!-- Template Type Selector and Actions -->
        <div class="flex justify-between items-center">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Template Type</label>
                <select wire:model="selectedTemplateType" wire:change="$refresh" 
                        class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="pdf">PDF Templates</option>
                    <option value="email">Email Templates</option>
                    <option value="sms">SMS Templates</option>
                </select>
            </div>
            <div>
                <button wire:click="openTemplateModal" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create New Template
                </button>
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $template->name }}</h3>
                        @if($template->is_default)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Default
                            </span>
                        @endif
                    </div>
                    <div class="flex space-x-1">
                        <button wire:click="previewTemplate({{ $template->id }})" 
                                class="text-gray-400 hover:text-gray-600" title="Preview">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button wire:click="selectTemplate({{ $template->id }})" 
                                class="text-green-400 hover:text-green-600" title="Use Template">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <button wire:click="editTemplate({{ $template->id }})" 
                                class="text-indigo-400 hover:text-indigo-600" title="Edit">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        @if(!$template->is_default)
                        <button wire:click="deleteTemplate({{ $template->id }})" 
                                class="text-red-400 hover:text-red-600" title="Delete"
                                onclick="return confirm('Are you sure you want to delete this template?')">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                
                @if($template->description)
                <p class="text-sm text-gray-600 mb-3">{{ $template->description }}</p>
                @endif
                
                <div class="text-xs text-gray-500">
                    Type: {{ ucfirst($template->type) }}
                </div>
            </div>
            @endforeach
        </div>

        @if($templates->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No templates</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new {{ $selectedTemplateType }} template.</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Template Modal -->
    @if($showTemplateModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">{{ $editingTemplate ? 'Edit' : 'Create' }} Template</h3>
                <button wire:click="closeTemplateModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form wire:submit.prevent="saveTemplate" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Template Name</label>
                        <input type="text" wire:model="templateName" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('templateName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Template Type</label>
                        <select wire:model="selectedTemplateType" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="pdf">PDF</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                        @error('selectedTemplateType') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" wire:model="templateDescription" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('templateDescription') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Template Content</label>
                    <textarea wire:model="htmlContent" rows="12" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono"
                              placeholder="Enter your template content..."></textarea>
                    @error('htmlContent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeTemplateModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        {{ $editingTemplate ? 'Update' : 'Save' }} Template
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Template Preview</h3>
                <button wire:click="closePreviewModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="border rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto">
                <div class="bg-white p-4 rounded shadow-inner">
                    {!! $previewContent !!}
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button wire:click="closePreviewModal"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Message -->
    @if ($receiptGenerated)
        <div class="mt-6 p-4 border border-green-500 bg-green-100 rounded text-green-700">
            Receipt generated successfully! 
            <a href="{{ $receiptUrl }}" target="_blank" class="underline text-indigo-700 font-medium">Download PDF</a>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mt-6 p-4 border border-green-500 bg-green-100 rounded text-green-700">
            {{ session('success') }}
        </div>
    @endif
</div>
