@extends('layouts.app')

@section('title', 'Inicio')
@section('page-title', 'Inicio')

@section('content')

{{-- ===== TARJETAS DE MÉTRICAS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Abiertos --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Abiertos</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">12</p>
                <p class="text-xs text-gray-400 mt-1">+2 desde ayer</p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- En progreso --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">En progreso</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">7</p>
                <p class="text-xs text-gray-400 mt-1">Sin cambios hoy</p>
            </div>
            <div class="p-2 bg-yellow-50 rounded-lg">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Vencidos (SLA) --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-red-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-red-500 uppercase tracking-wide">Vencidos SLA</p>
                <p class="text-3xl font-bold text-red-600 mt-1">3</p>
                <p class="text-xs text-red-400 mt-1">Requieren atención</p>
            </div>
            <div class="p-2 bg-red-50 rounded-lg">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Cerrados este mes --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cerrados (mes)</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">34</p>
                <p class="text-xs text-green-500 mt-1">↑ 8% vs mes anterior</p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- ===== FILA PRINCIPAL ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- TABLA DE TICKETS RECIENTES (2/3) --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Tickets recientes</h2>
            <a href="{{ route('tickets.index') }}" class="text-sm text-judicial-600 hover:underline">Ver todos →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Código</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Asunto</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Prioridad</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actualizado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    @php
                    $tickets = [
                        ['code' => 'TKT-2026-00023', 'subject' => 'Error al imprimir desde impresora de sala de audiencias', 'status' => 'Nuevo',       'status_color' => 'blue',   'priority' => 'Alta',     'priority_color' => 'orange', 'updated' => 'Hace 10 min'],
                        ['code' => 'TKT-2026-00022', 'subject' => 'Solicitud de acceso a sistema IURIX',                   'status' => 'En progreso',  'status_color' => 'yellow', 'priority' => 'Media',    'priority_color' => 'blue',   'updated' => 'Hace 1 hora'],
                        ['code' => 'TKT-2026-00021', 'subject' => 'Falla en conexión VPN desde juzgado civil nro. 3',      'status' => 'En progreso',  'status_color' => 'yellow', 'priority' => 'Crítica',  'priority_color' => 'red',    'updated' => 'Hace 2 horas'],
                        ['code' => 'TKT-2026-00019', 'subject' => 'Actualización de contraseña de dominio',                'status' => 'Resuelto',     'status_color' => 'green',  'priority' => 'Baja',     'priority_color' => 'gray',   'updated' => 'Ayer'],
                        ['code' => 'TKT-2026-00018', 'subject' => 'Instalación de Office 365 en equipo nuevo',             'status' => 'Cerrado',      'status_color' => 'gray',   'priority' => 'Media',    'priority_color' => 'blue',   'updated' => 'Hace 2 días'],
                    ];

                    $statusClasses = [
                        'blue'   => 'bg-blue-100 text-blue-700',
                        'yellow' => 'bg-yellow-100 text-yellow-700',
                        'green'  => 'bg-green-100 text-green-700',
                        'gray'   => 'bg-gray-100 text-gray-600',
                        'red'    => 'bg-red-100 text-red-700',
                    ];
                    $priorityClasses = [
                        'red'    => 'bg-red-100 text-red-700',
                        'orange' => 'bg-orange-100 text-orange-700',
                        'blue'   => 'bg-blue-100 text-blue-700',
                        'gray'   => 'bg-gray-100 text-gray-600',
                    ];
                    @endphp

                    @foreach ($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-judicial-600 font-medium whitespace-nowrap">
                            <a href="{{ route('tickets.show', $ticket['code']) }}" class="hover:underline">{{ $ticket['code'] }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 max-w-xs">
                            <span class="line-clamp-1">{{ $ticket['subject'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$ticket['status_color']] }}">
                                {{ $ticket['status'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClasses[$ticket['priority_color']] }}">
                                {{ $ticket['priority'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 whitespace-nowrap">{{ $ticket['updated'] }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    {{-- COLUMNA DERECHA (1/3) --}}
    <div class="space-y-5">

        {{-- Acción rápida --}}
        <div class="bg-judicial-800 rounded-xl p-5 text-white">
            <h3 class="font-semibold mb-1">¿Necesitás ayuda?</h3>
            <p class="text-judicial-300 text-sm mb-4">Abrí un nuevo ticket y el equipo técnico lo recibirá de inmediato.</p>
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center gap-2 bg-dorado-500 hover:bg-dorado-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear ticket
            </a>
        </div>

        {{-- Distribución por estado --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Estado actual</h3>
            <div class="space-y-3">
                @php
                $states = [
                    ['label' => 'Nuevos',              'count' => 12, 'pct' => 35, 'color' => 'bg-blue-400'],
                    ['label' => 'En progreso',          'count' => 7,  'pct' => 21, 'color' => 'bg-yellow-400'],
                    ['label' => 'Esperando respuesta',  'count' => 4,  'pct' => 12, 'color' => 'bg-purple-400'],
                    ['label' => 'Resueltos',            'count' => 9,  'pct' => 26, 'color' => 'bg-green-400'],
                    ['label' => 'Vencidos',             'count' => 3,  'pct' => 9,  'color' => 'bg-red-400'],
                ];
                @endphp

                @foreach ($states as $state)
                <div>
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>{{ $state['label'] }}</span>
                        <span class="font-medium">{{ $state['count'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="{{ $state['color'] }} h-2 rounded-full transition-all duration-300"
                             style="width: {{ $state['pct'] }}%"
                             role="progressbar"
                             aria-valuenow="{{ $state['pct'] }}"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             aria-label="{{ $state['label'] }}: {{ $state['count'] }} tickets"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Actividad reciente --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Actividad reciente</h3>
            <div class="space-y-3">
                @php
                $activity = [
                    ['icon' => '🟢', 'text' => 'TKT-2026-00019 cerrado con conformidad',       'time' => 'Hace 1 h'],
                    ['icon' => '💬', 'text' => 'Nuevo comentario en TKT-2026-00022',            'time' => 'Hace 2 h'],
                    ['icon' => '🔴', 'text' => 'TKT-2026-00021 superó el SLA de resolución',   'time' => 'Hace 3 h'],
                    ['icon' => '📋', 'text' => 'TKT-2026-00023 asignado a Soporte Técnico',    'time' => 'Hace 4 h'],
                ];
                @endphp

                @foreach ($activity as $item)
                <div class="flex items-start gap-3">
                    <span class="text-base leading-none mt-0.5" aria-hidden="true">{{ $item['icon'] }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-snug">{{ $item['text'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $item['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection
