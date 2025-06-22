<!DOCTYPE html>
<html>
<head>
    <title>Receipt Generator</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-start py-10">
    <div class="bg-white shadow-md rounded p-6 w-full max-w-4xl">
        @livewire('receipt-form')
    </div>
    @livewireScripts
</body>
</html>
