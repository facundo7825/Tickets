<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inicio') — Sistema de Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100"
      x-data="{ sidebarOpen: false, notifOpen: false }">

<div class="flex min-h-screen">

{{-- ===================== SIDEBAR ===================== --}}
{{-- Overlay móvil --}}
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-20 bg-black/50 lg:hidden"
     aria-hidden="true"></div>

<aside id="sidebar"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-judicial-900 flex flex-col shrink-0
              transform transition-transform duration-200 ease-in-out
              lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:z-auto"
       aria-label="Navegación principal">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-judicial-700">
        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-judicial-700 shrink-0">
            <svg class="w-5 h-5 text-dorado-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 3L2 9l10 6 10-6-10-6zM2 17l10 6 10-6M2 12l10 6 10-6"/>
            </svg>
        </div>
        <div>
            <p class="text-white text-sm font-semibold leading-tight">Poder Judicial</p>
            <p class="text-judicial-400 text-xs">Mesa de Ayuda</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5" aria-label="Menú principal">

        @php
            $navItem = function(string $route, string $icon, string $label, bool $admin = false): string {
                $active = request()->routeIs($route . '*');
                $base = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150';
                $cls  = $active
                    ? "$base bg-judicial-700 text-white"
                    : "$base text-judicial-300 hover:bg-judicial-800 hover:text-white";
                return $cls;
            };
        @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-judicial-700 text-white' : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-judicial-300 hover:bg-judicial-800 hover:text-white transition-colors' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Inicio
        </a>

        {{-- Separador --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-judicial-500 uppercase tracking-wider">Tickets</p>

        <a href="{{ route('tickets.index') }}"
           class="{{ request()->routeIs('tickets.index') ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-judicial-700 text-white' : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-judicial-300 hover:bg-judicial-800 hover:text-white transition-colors' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Mis tickets
            <span class="ml-auto bg-judicial-600 text-judicial-200 text-xs font-medium px-2 py-0.5 rounded-full">3</span>
        </a>

        <a href="{{ route('tickets.create') }}"
           class="{{ request()->routeIs('tickets.create') ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-judicial-700 text-white' : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-judicial-300 hover:bg-judicial-800 hover:text-white transition-colors' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Crear ticket
        </a>

        <a href="{{ route('tickets.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-judicial-300 hover:bg-judicial-800 hover:text-white transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Todos los tickets
        </a>

        {{-- Separador --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-judicial-500 uppercase tracking-wider">Reportes</p>

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('reports.*') ? 'bg-judicial-800 text-white' : 'text-judicial-300 hover:bg-judicial-800 hover:text-white' }}
                  transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Reportes
        </a>

        {{-- Administración (solo admin) --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-judicial-500 uppercase tracking-wider">Administración</p>

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('users.*') ? 'bg-judicial-800 text-white' : 'text-judicial-300 hover:bg-judicial-800 hover:text-white' }}
                  transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Usuarios
        </a>

        <a href="{{ route('settings.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('settings.*') ? 'bg-judicial-800 text-white' : 'text-judicial-300 hover:bg-judicial-800 hover:text-white' }}
                  transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Configuración
        </a>
    </nav>

    {{-- Usuario en el pie del sidebar --}}
    <div class="border-t border-judicial-700 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-judicial-600 flex items-center justify-center shrink-0">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </span>
            </div>
            <a href="{{ route('profile') }}" class="flex-1 min-w-0 hover:opacity-80 transition-opacity">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                <p class="text-judicial-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        title="Cerrar sesión"
                        class="text-judicial-400 hover:text-white transition-colors p-1 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ===================== CONTENIDO PRINCIPAL ===================== --}}
<div class="flex-1 flex flex-col min-w-0">

    {{-- TOPBAR --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6">

            {{-- Botón hamburguesa (móvil) --}}
            <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    aria-label="Abrir menú">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb / título de página --}}
            <div class="flex-1 lg:flex-none ml-4 lg:ml-0">
                <h1 class="text-gray-800 font-semibold text-base">@yield('page-title', 'Inicio')</h1>
            </div>

            {{-- Acciones topbar --}}
            <div class="flex items-center gap-2">

                {{-- Notificaciones --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            aria-label="Notificaciones">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- Badge --}}
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"
                              aria-label="Tenés notificaciones no leídas"></span>
                    </button>

                    {{-- Panel de notificaciones --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
                         role="dialog" aria-label="Panel de notificaciones">

                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <p class="font-semibold text-gray-800 text-sm">Notificaciones</p>
                            <button class="text-xs text-judicial-600 hover:underline">Marcar todo como leído</button>
                        </div>

                        <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-2 h-2 mt-2 rounded-full bg-judicial-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-gray-800">Tu ticket <strong>TKT-2026-00023</strong> fue asignado al equipo de Soporte.</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Hace 10 minutos</p>
                                </div>
                            </a>
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-2 h-2 mt-2 rounded-full bg-judicial-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-gray-800">El ticket <strong>TKT-2026-00019</strong> fue marcado como Resuelto.</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Hace 1 hora</p>
                                </div>
                            </a>
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-2 h-2 mt-2 rounded-full bg-gray-200 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-gray-500">Nuevo comentario en <strong>TKT-2026-00015</strong>.</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Ayer</p>
                                </div>
                            </a>
                        </div>

                        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-judicial-600 hover:underline">Ver todas las notificaciones</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- CONTENIDO --}}
    <main class="flex-1 p-4 sm:p-6" id="main-content">
        @if (session('success'))
            <div role="alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-5 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</div>{{-- /flex wrapper --}}
</body>
</html>
