<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Tickets') — Poder Judicial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-judicial-800 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Encabezado institucional --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-lg mb-4">
                <svg class="w-12 h-12 text-judicial-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 3L2 9l10 6 10-6-10-6zM2 17l10 6 10-6M2 12l10 6 10-6"/>
                </svg>
            </div>
            <h1 class="text-white text-xl font-semibold tracking-wide">Poder Judicial</h1>
            <p class="text-judicial-300 text-sm mt-1">Sistema de Gestión de Tickets</p>
        </div>

        {{-- Tarjeta principal --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            {{-- Banda superior dorada --}}
            <div class="h-1 bg-gradient-to-r from-dorado-500 to-dorado-400"></div>

            <div class="px-8 py-8">
                @yield('content')
            </div>
        </div>

        {{-- Pie --}}
        <p class="text-center text-judicial-400 text-xs mt-6">
            &copy; {{ date('Y') }} Poder Judicial &mdash; Uso exclusivo del personal del organismo
        </p>
    </div>

</body>
</html>
