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
            <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Templates</h4>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="loadTemplate('invoice')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Invoice Template
                </button>
                <button type="button" wire:click="loadTemplate('report')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Report Template
                </button>
                <button type="button" wire:click="loadTemplate('certificate')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Certificate Template
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
        <div class="text-center">
            <button type="submit"
                    class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md
                           text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate PDF from HTML
            </button>
        </div>
    </form>
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
