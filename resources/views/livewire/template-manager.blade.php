<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-500 mb-4">
            <a href="/" class="hover:text-gray-700">Home</a>
            <svg class="mx-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900">Templates</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Templates</h1>
                <p class="mt-1 text-gray-600">Manage your SMS, Email, and PDF templates</p>
            </div>
            
            <!-- Create Button -->
            <div class="relative">
                <button wire:click="openModal" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Template
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live="search" placeholder="Search templates..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                
                <select wire:model.live="filterType" 
                        class="block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All</option>
                    <option value="pdf">PDF</option>
                    <option value="email">Email</option>
                    <option value="sms">SMS</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Templates Section -->
    <div class="bg-white rounded-lg border border-gray-200">
        <!-- Section Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-lg font-medium text-gray-900">Templates ({{ $templates->total() }})</h2>
            </div>
        </div>

        <!-- Templates Table -->
        @if($templates->count() > 0)
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Modified</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($templates as $template)
                    <tr class="hover:bg-gray-50">
                        <!-- Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($template->type === 'sms')
                                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                @elseif($template->type === 'email')
                                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                                
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                                    @if($template->is_default)
                                        <div class="text-xs text-gray-500">Default template</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Description -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $template->description ?? 'No description' }}</div>
                        </td>
                        
                        <!-- Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $template->type === 'pdf' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $template->type === 'email' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $template->type === 'sms' ? 'bg-green-100 text-green-800' : '' }}">
                                <svg class="w-2 h-2 mr-1 fill-current" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"></circle>
                                </svg>
                                {{ strtoupper($template->type) }}
                            </span>
                        </td>
                        
                        <!-- Last Modified -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $template->updated_at->format('Y-m-d') }}
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative inline-block text-left">
                                <button class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600" 
                                        onclick="toggleDropdown('dropdown-{{ $template->id }}')">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                
                                <div id="dropdown-{{ $template->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1" role="menu">
                                        <button wire:click="previewTemplate({{ $template->id }})" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Preview
                                        </button>
                                        
                                        @if($template->type === 'pdf')
                                        <button wire:click="generatePdfPreview({{ $template->id }})" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Download PDF
                                        </button>
                                        @endif
                                        
                                        <button wire:click="openModal({{ $template->id }})" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        @if(!$template->is_default)
                                        <button wire:click="deleteTemplate({{ $template->id }})" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50" role="menuitem"
                                                onclick="return confirm('Are you sure you want to delete this template?')">
                                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No templates found</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($search || $filterType !== 'all')
                    Try adjusting your search or filter criteria.
                @else
                    Get started by creating your first template.
                @endif
            </p>
            @if(!$search && $filterType === 'all')
            <div class="mt-6">
                <button wire:click="openModal" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create New Template
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
    <div class="mt-6">
        {{ $templates->links() }}
    </div>
    @endif

    <!-- Create/Edit Template Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit="saveTemplate">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    {{ $editingTemplate ? 'Edit Template' : 'Create New Template' }}
                                </h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Main Form -->
                                    <div class="lg:col-span-2 space-y-4">
                                        <!-- Template Type -->
                                        <div>
                                            <label for="type" class="block text-sm font-medium text-gray-700">Template Type</label>
                                            <select wire:model.live="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="pdf">PDF Template</option>
                                                <option value="email">Email Template</option>
                                                <option value="sms">SMS Template</option>
                                            </select>
                                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Template Name -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Template Name</label>
                                            <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter template name">
                                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Template Description -->
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter template description (optional)"></textarea>
                                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Email Subject (Email templates only) -->
                                        @if($type === 'email')
                                        <div>
                                            <label for="emailSubject" class="block text-sm font-medium text-gray-700">Email Subject</label>
                                            <input type="text" wire:model="emailSubject" id="emailSubject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter email subject">
                                            @error('emailSubject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        @endif

                                        <!-- Content -->
                                        <div>
                                            <label for="content" class="block text-sm font-medium text-gray-700">
                                                @if($type === 'pdf') HTML/CSS Content
                                                @elseif($type === 'email') Email Body
                                                @else SMS Message
                                                @endif
                                            </label>
                                            <textarea wire:model="content" id="content" 
                                                     rows="{{ $type === 'sms' ? '4' : '12' }}" 
                                                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono" 
                                                     placeholder="Enter template content..."></textarea>
                                            @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- PDF Attachments (Email templates only) -->
                                        @if($type === 'email' && $pdfTemplates->count() > 0)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">PDF Attachments</label>
                                            <div class="mt-2 space-y-2">
                                                @foreach($pdfTemplates as $pdfTemplate)
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" wire:model="selectedPdfAttachments" value="{{ $pdfTemplate->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-700">{{ $pdfTemplate->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Variables Panel -->
                                    <div class="lg:col-span-1">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Available Variables</h4>
                                            <div class="space-y-3">
                                                @foreach($availableVariables as $category => $variables)
                                                <div>
                                                    <h5 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">{{ $category }}</h5>
                                                    <div class="space-y-1">
                                                        @foreach($variables as $variable => $label)
                                                        <button type="button" 
                                                                wire:click="insertVariable('{{ $variable }}')"
                                                                class="block w-full text-left px-2 py-1 text-xs text-indigo-600 hover:bg-indigo-50 rounded transition-colors">
                                                            {{ $label }}
                                                            <span class="text-gray-400">({{{{ $variable }}}})</span>
                                                        </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $editingTemplate ? 'Update Template' : 'Create Template' }}
                        </button>
                        <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePreviewModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Template Preview</h3>
                            
                            @if($previewSubject)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700">Subject:</h4>
                                <p class="text-sm text-gray-900 mt-1">{{ $previewSubject }}</p>
                            </div>
                            @endif
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($previewContent)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closePreviewModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Email Preview Modal -->
    @if($showEmailPreviewModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePreviewModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Email Preview</h3>
                            
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <iframe srcdoc="{{ htmlspecialchars($emailPreviewHtml) }}" 
                                        class="w-full h-96" 
                                        frameborder="0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closePreviewModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                    </svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Toggle dropdown functionality
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const isHidden = dropdown.classList.contains('hidden');
        
        // Close all dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
        
        // Toggle current dropdown
        if (isHidden) {
            dropdown.classList.remove('hidden');
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('button') && !event.target.closest('[id^="dropdown-"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        const messages = document.querySelectorAll('[role="alert"]');
        messages.forEach(message => {
            message.style.transition = 'opacity 0.3s';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        });
    }, 5000);
</script>
