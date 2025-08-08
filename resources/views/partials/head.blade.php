<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<!-- Script de inicialização do tema para evitar flash -->
<script>
    (function() {
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Figma Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
