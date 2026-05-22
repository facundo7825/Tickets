@extends('layouts.app')

@section('title', 'Notificaciones')
@section('page-title', 'Notificaciones')

@section('content')
<div
    x-data="{
        filtro: 'todas',
        marcandoTodas: false,
        notificaciones: [
            {
                id: 1, leida: false,
                tipo: 'asignacion',
                titulo: 'Ticket asignado a tu equipo',
                cuerpo: 'El ticket <strong>TKT-2026-00023</strong> «Error al imprimir desde sala de audiencias» fue asignado al equipo de Soporte Técnico.',
                ticket: 'TKT-2026-00023',
                tiempo: 'Hace 10 minutos',
                timestamp: '22/05/2026 09:04',
            },
            {
                id: 2, leida: false,
                tipo: 'estado',
                titulo: 'Ticket marcado como Resuelto',
                cuerpo: 'El ticket <strong>TKT-2026-00019</strong> «Actualización de contraseña de dominio» fue marcado como <strong>Resuelto</strong> por Carlos Méndez.',
                ticket: 'TKT-2026-00019',
                tiempo: 'Hace 1 hora',
                timestamp: '22/05/2026 08:14',
            },
            {
                id: 3, leida: false,
                tipo: 'sla',
                titulo: 'SLA vencido — requiere atención',
                cuerpo: 'El ticket <strong>TKT-2026-00021</strong> «Falla crítica en conexión VPN desde juzgado civil nro. 3» superó el tiempo de resolución acordado. Prioridad: Crítica.',
                ticket: 'TKT-2026-00021',
                tiempo: 'Hace 2 horas',
                timestamp: '22/05/2026 07:04',
            },
            {
                id: 4, leida: false,
                tipo: 'comentario',
                titulo: 'Nuevo comentario en tu ticket',
                cuerpo: 'Laura Giménez comentó en el ticket <strong>TKT-2026-00021</strong>: «Estamos verificando la configuración del concentrador VPN del juzgado…»',
                ticket: 'TKT-2026-00021',
                tiempo: 'Hace 3 horas',
                timestamp: '22/05/2026 06:14',
            },
            {
                id: 5, leida: true,
                tipo: 'estado',
                titulo: 'Ticket cambiado a En progreso',
                cuerpo: 'El ticket <strong>TKT-2026-00021</strong> cambió de estado a <strong>En progreso</strong>. Asignado a Laura Giménez del equipo de Redes.',
                ticket: 'TKT-2026-00021',
                tiempo: 'Hace 5 horas',
                timestamp: '22/05/2026 04:14',
            },
            {
                id: 6, leida: true,
                tipo: 'asignacion',
                titulo: 'Te fue asignado un ticket',
                cuerpo: 'El coordinador Ing. Sergio Ruiz te asignó el ticket <strong>TKT-2026-00018</strong> «Instalación de Office 365 en equipo nuevo».',
                ticket: 'TKT-2026-00018',
                tiempo: 'Ayer, 16:30',
                timestamp: '21/05/2026 16:30',
            },
            {
                id: 7, leida: true,
                tipo: 'comentario',
                titulo: 'Nuevo comentario en tu ticket',
                cuerpo: 'Dr. Roberto Casas comentó en el ticket <strong>TKT-2026-00015</strong>: «¿Tienen alguna estimación de cuándo estará resuelto? Necesitamos acceder al sistema con urgencia.»',
                ticket: 'TKT-2026-00015',
                tiempo: 'Ayer, 11:20',
                timestamp: '21/05/2026 11:20',
            },
            {
                id: 8, leida: true,
                tipo: 'estado',
                titulo: 'Ticket cerrado automáticamente',
                cuerpo: 'El ticket <strong>TKT-2026-00012</strong> fue cerrado automáticamente por no recibir conformidad del solicitante en los 5 días hábiles establecidos.',
                ticket: 'TKT-2026-00012',
                tiempo: 'Hace 2 días',
                timestamp: '20/05/2026 09:00',
            },
            {
                id: 9, leida: true,
                tipo: 'sla',
                titulo: 'SLA de respuesta inicial vencido',
                cuerpo: 'El ticket <strong>TKT-2026-00011</strong> «Impresora en red sin conexión» superó el plazo de respuesta inicial. Área responsable: Redes.',
                ticket: 'TKT-2026-00011',
                tiempo: 'Hace 3 días',
                timestamp: '19/05/2026 14:00',
            },
            {
                id: 10, leida: true,
                tipo: 'asignacion',
                titulo: 'Ticket derivado a tu área',
                cuerpo: 'El ticket <strong>TKT-2026-00009</strong> fue derivado desde Soporte Técnico hacia el área de Desarrollo. Justificación: «Requiere modificación del módulo de exportación.»',
                ticket: 'TKT-2026-00009',
                tiempo: 'Hace 3 días',
                timestamp: '19/05/2026 10:30',
            },
        ],

        get noLeidas() {
            return this.notificaciones.filter(n => !n.leida).length;
        },

        get filtradas() {
            if (this.filtro === 'noleidas') return this.notificaciones.filter(n => !n.leida);
            if (this.filtro === 'leidas')   return this.notificaciones.filter(n => n.leida);
            if (this.filtro === 'estado')   return this.notificaciones.filter(n => n.tipo === 'estado');
            if (this.filtro === 'comentario') return this.notificaciones.filter(n => n.tipo === 'comentario');
            if (this.filtro === 'sla')      return this.notificaciones.filter(n => n.tipo === 'sla');
            if (this.filtro === 'asignacion') return this.notificaciones.filter(n => n.tipo === 'asignacion');
            return this.notificaciones;
        },

        marcarLeida(id) {
            const n = this.notificaciones.find(n => n.id === id);
            if (n) n.leida = true;
        },

        marcarTodasLeidas() {
            this.marcandoTodas = true;
            setTimeout(() => {
                this.notificaciones.forEach(n => n.leida = true);
                this.marcandoTodas = false;
            }, 600);
        },

        iconoTipo(tipo) {
            const m = {
                'estado':     'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'comentario': 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                'sla':        'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'asignacion': 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            };
            return m[tipo] || m['estado'];
        },

        colorTipo(tipo) {
            const m = {
                'estado':     'bg-blue-100 text-blue-600',
                'comentario': 'bg-violet-100 text-violet-600',
                'sla':        'bg-rose-100 text-rose-600',
                'asignacion': 'bg-emerald-100 text-emerald-600',
            };
            return m[tipo] || 'bg-gray-100 text-gray-600';
        },

        etiquetaTipo(tipo) {
            const m = { 'estado': 'Estado', 'comentario': 'Comentario', 'sla': 'SLA', 'asignacion': 'Asignación' };
            return m[tipo] || tipo;
        },
    }"
>

{{-- ===== ENCABEZADO ===== --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div x-show="noLeidas > 0" class="flex items-center gap-2 px-3 py-1 bg-judicial-100 text-judicial-800 rounded-full text-sm font-semibold">
            <span class="w-2 h-2 rounded-full bg-judicial-600"></span>
            <span x-text="noLeidas + ' sin leer'"></span>
        </div>
        <div x-show="noLeidas === 0" class="flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-medium">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Todo leído
        </div>
    </div>
    <button
        @click="marcarTodasLeidas()"
        :disabled="marcandoTodas || noLeidas === 0"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-judicial-700 border border-judicial-300 rounded-xl hover:bg-judicial-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
    >
        <template x-if="marcandoTodas">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
        </template>
        <template x-if="!marcandoTodas">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </template>
        <span x-text="marcandoTodas ? 'Marcando…' : 'Marcar todo como leído'"></span>
    </button>
</div>

{{-- ===== FILTROS (pills) ===== --}}
<div class="flex flex-wrap gap-2 mb-5">
    @php
    $filtros = [
        ['val' => 'todas',      'label' => 'Todas'],
        ['val' => 'noleidas',   'label' => 'Sin leer'],
        ['val' => 'estado',     'label' => 'Cambios de estado'],
        ['val' => 'comentario', 'label' => 'Comentarios'],
        ['val' => 'sla',        'label' => 'SLA'],
        ['val' => 'asignacion', 'label' => 'Asignaciones'],
    ];
    @endphp
    @foreach ($filtros as $f)
    <button
        @click="filtro = '{{ $f['val'] }}'"
        class="px-3.5 py-1.5 rounded-full text-sm font-medium transition-colors"
        :class="filtro === '{{ $f['val'] }}'
            ? 'bg-judicial-700 text-white shadow-sm'
            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
    >
        {{ $f['label'] }}
        @if ($f['val'] === 'noleidas')
        <span
            x-show="noLeidas > 0"
            x-text="noLeidas"
            class="ml-1 inline-flex items-center justify-center w-4 h-4 text-xs rounded-full"
            :class="filtro === 'noleidas' ? 'bg-white/20 text-white' : 'bg-rose-500 text-white'"
        ></span>
        @endif
    </button>
    @endforeach
</div>

{{-- ===== LISTA DE NOTIFICACIONES ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Estado vacío --}}
    <template x-if="filtradas.length === 0">
        <div class="py-16 text-center">
            <svg class="mx-auto w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-gray-500 font-medium text-sm">No hay notificaciones</p>
            <p class="text-gray-400 text-xs mt-1">Cuando algo relevante ocurra, aparecerá aquí.</p>
        </div>
    </template>

    <div class="divide-y divide-gray-50">
        <template x-for="notif in filtradas" :key="notif.id">
            <div
                class="flex gap-4 px-5 py-4 transition-colors"
                :class="notif.leida ? 'bg-white hover:bg-gray-50' : 'bg-judicial-50/40 hover:bg-judicial-50/70'"
            >
                {{-- Ícono tipo --}}
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                    :class="colorTipo(notif.tipo)"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconoTipo(notif.tipo)"/>
                    </svg>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <span
                                    class="text-sm font-semibold"
                                    :class="notif.leida ? 'text-gray-700' : 'text-gray-900'"
                                    x-text="notif.titulo"
                                ></span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                    :class="colorTipo(notif.tipo)"
                                    x-text="etiquetaTipo(notif.tipo)"
                                ></span>
                                <template x-if="!notif.leida">
                                    <span class="w-2 h-2 rounded-full bg-judicial-500 shrink-0"></span>
                                </template>
                            </div>
                            <p class="text-sm text-gray-500 leading-relaxed" x-html="notif.cuerpo"></p>
                            <div class="flex items-center gap-3 mt-2 flex-wrap">
                                <span class="text-xs text-gray-400" x-text="notif.tiempo"></span>
                                <span class="text-xs text-gray-300">·</span>
                                <span class="text-xs text-gray-400" x-text="notif.timestamp"></span>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center gap-1 shrink-0">
                            {{-- Marcar leída --}}
                            <template x-if="!notif.leida">
                                <button
                                    @click="marcarLeida(notif.id)"
                                    class="p-1.5 rounded-lg text-gray-300 hover:text-judicial-600 hover:bg-judicial-50 transition-colors"
                                    title="Marcar como leída"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </template>
                            {{-- Ver ticket --}}
                            <a
                                href="{{ url('tickets') }}/1"
                                @click="marcarLeida(notif.id)"
                                class="p-1.5 rounded-lg text-gray-300 hover:text-judicial-600 hover:bg-judicial-50 transition-colors"
                                title="Ver ticket"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Footer --}}
    <template x-if="filtradas.length > 0">
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between text-xs text-gray-400">
            <span>
                Mostrando <span class="font-medium text-gray-600" x-text="filtradas.length"></span>
                de <span class="font-medium text-gray-600" x-text="notificaciones.length"></span> notificaciones
            </span>
            <span class="hidden sm:block">Las notificaciones leídas se eliminan automáticamente después de 90 días.</span>
        </div>
    </template>

</div>

</div>
@endsection
