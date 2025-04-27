<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" href="icon/pawprint.png">
    <title>PetPal</title>
    @vite(['resources/js/app.js', 'resources/css/app.css']) 
    @inertiaHead
</head>
<body>
    @inertia
    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>