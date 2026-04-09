<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IRIS EXAM') - Plateforme d\'évaluation</title>
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'iris-yellow': {
                            DEFAULT: '#FDB913',
                            600: '#E49D0A',
                        },
                        'iris-blue': {
                            DEFAULT: '#0066CC',
                            600: '#0052A3',
                        },
                        'iris-black': {
                            DEFAULT: '#1A1A1A',
                            900: '#1A1A1A',
                        },
                    }
                }
            }
        }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white">
    @yield('content')
    
    @stack('scripts')
</body>
</html>