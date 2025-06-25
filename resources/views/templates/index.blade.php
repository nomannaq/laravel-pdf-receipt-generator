<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Manager - Paychase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">Paychase</h1>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="/receipt" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Receipt Generator</a>
                        <a href="/templates" class="text-gray-900 bg-gray-100 px-3 py-2 rounded-md text-sm font-medium">Templates</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @livewire('template-manager')
    </main>

    @livewireScripts
</body>
</html>
