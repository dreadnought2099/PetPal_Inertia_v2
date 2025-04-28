<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" href="icon/pawprint.png">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>PetPal</title>
    @vite(['resources/js/app.js', 'resources/css/app.css']) 
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>