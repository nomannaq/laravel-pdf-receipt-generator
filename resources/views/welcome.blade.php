<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Receipt Generator</title>
    @vite('resources/css/app.css') <!-- Tailwind CSS -->
    @livewireStyles              <!-- Livewire CSS -->
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-semibold mb-4 text-center">Receipt Generator</h1>

        <!-- Livewire receipt form component -->
        <livewire:receipt-form />
    </div>

    @livewireScripts             <!-- Livewire JS -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Confirmation alert --}}
</body>
</html>
