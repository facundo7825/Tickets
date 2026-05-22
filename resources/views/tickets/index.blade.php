@extends('layouts.app')

@section('title', 'Tickets')
@section('page-title', 'Tickets')

@section('content')
<div
    x-data="{
        busqueda: '',
        filtroEstado: '',
        filtroPrioridad: '',
        filtroCategoria: '',

        tickets: [
            { id: 1,  codigo: 'TKT-2026-00023', titulo: 'Error al imprimir desde impresora de sala de audiencias 2',          estado: 'Nuevo',                  prioridad: 'Alta',    categoria: 'Soporte Técnico',        asignado: null,                   actualizado: 'Hace 10 min',  vencido: false },
            { id: 2,  codigo: 'TKT-2026-00022', titulo: 'Solicitud de acceso al sistema IURIX para secretaría civil',         estado: 'En progreso',            prioridad: 'Media',   categoria: 'Soporte Técnico',        asignado: 'Carlos Méndez',        actualizado: 'Hace 1 hora',  vencido: false },
            { id: 3,  codigo: 'TKT-2026-00021', titulo: 'Falla crítica en conexión VPN desde juzgado civil nro. 3',           estado: 'En progreso',            prioridad: 'Crítica', categoria: 'Redes',                  asignado: 'Laura Giménez',        actualizado: 'Hace 2 horas', vencido: true  },
            { id: 4,  codigo: 'TKT-2026-00020', titulo: 'Desarrollo de módulo de exportación a PDF en sistema de actas',      estado: 'Asignado',               prioridad: 'Media',   categoria: 'Desarrollo de Software', asignado: 'Pablo Rodríguez',      actualizado: 'Hace 3 horas', vencido: false },
            { id: 5,  codigo: 'TKT-2026-00019', titulo: 'Actualización de contraseña de dominio expirada',                    estado: 'Resuelto',               prioridad: 'Baja',    categoria: 'Soporte Técnico',        asignado: 'Carlos Méndez',        actualizado: 'Ayer',         vencido: false },
            { id: 6,  codigo: 'TKT-2026-00018', titulo: 'Instalación de Office 365 en equipo nuevo del juzgado laboral',      estado: 'Cerrado',                prioridad: 'Media',   categoria: 'Soporte Técnico',        asignado: 'Ana Torres',           actualizado: 'Hace 2 días',  vencido: false },
            { id: 7,  codigo: 'TKT-2026-00017', titulo: 'Error 500 en módulo de notificaciones del sistema de gestión',       estado: 'Esperando terceros',     prioridad: 'Alta',    categoria: 'Desarrollo de Software', asignado: 'Pablo Rodríguez',      actualizado: 'Hace 2 días',  vencido: true  },
            { id: 8,  codigo: 'TKT-2026-00016', titulo: 'Recursos compartidos no accesibles desde piso 3',                    estado: 'Esperando solicitante',  prioridad: 'Media',   categoria: 'Redes',                  asignado: 'Laura Giménez',        actualizado: 'Hace 3 días',  vencido: false },
            { id: 9,  codigo: 'TKT-2026-00015', titulo: 'Consulta sobre integración con servicio de firma digital',           estado: 'En progreso',            prioridad: 'Baja',    categoria: 'Desarrollo de Software', asignado: 'Pablo Rodríguez',      actualizado: 'Hace 3 días',  vencido: false },
            { id: 10, codigo: 'TKT-2026-00014', titulo: 'Pantalla azul recurrente en PC de secretaría penal',                 estado: 'Reabierto',              prioridad: 'Alta',    categoria: 'Soporte Técnico',        asignado: 'Ana Torres',           actualizado: 'Hace 4 días',  vencido: false },
            { id: 11, codigo: 'TKT-2026-00013', titulo: 'Configuración de telefonía IP para nueva sala de reuniones',         estado: 'Asignado',               prioridad: 'Baja',    categoria: 'Redes',                  asignado: null,                   actualizado: 'Hace 5 días',  vencido: false },
            { id: 12, codigo: 'TKT-2026-00012', titulo: 'Cancelación de solicitud de hardware — presupuesto rechazado',       estado: 'Cancelado',              prioridad: 'Baja',    categoria: 'Soporte Técnico',        asignado: null,                   actualizado: 'Hace 1 semana',vencido: false },
        ],

        get filtrados() {
            return this.tickets.filter(t => {
                const q = this.busqueda.toLowerCase();
                const matchBusqueda = !q || t.codigo.toLowerCase().includes(q) || t.titulo.toLowerCase().includes(q);
                const matchEstado    = !this.filtroEstado    || t.estado    === this.filtroEstado;
                const matchPrioridad = !this.filtroPrioridad || t.prioridad === this.filtroPrioridad;
                const matchCategoria = !this.filtroCategoria || t.categoria === this.filtroCategoria;
                return matchBusqueda && matchEstado && matchPrioridad && matchCategoria;
            });
        },

        get hayFiltrosActivos() {
            return this.busqueda || this.filtroEstado || this.filtroPrioridad || this.filtroCategoria;
        },

        limpiarFiltros() {
            this.busqueda = '';
            this.filtroEstado = '';
            this.filtroPrioridad = '';
            this.filtroCategoria = '';
        },

        estadoClases(estado) {
            const mapa = {
                'Nuevo':                 'bg-blue-100 text-blue-700',
                'Asignado':              'bg-indigo-100 text-indigo-700',
                'En progreso':           'bg-yellow-100 text-yellow-700',
                'Esperando solicitante': 'bg-purple-100 text-purple-700',
                'Esperando terceros':    'bg-orange-100 text-orange-700',
                'Resuelto':              'bg-green-100 text-green-700',
                'Cerrado':               'bg-gray-100 text-gray-600',
                'Reabierto':             'bg-teal-100 text-teal-700',
                'Cancelado':             'bg-red-100 text-red-600',
            };
            return mapa[estado] ?? 'bg-gray-100 text-gray-600';
        },

        prioridadDot(prioridad) {
            const mapa = {
                'Crítica': 'bg-red-500',
                'Alta':    'bg-orange-500',
                'Media':   'bg-blue-500',
                'Baja':    'bg-gray-400',
            };
            return mapa[prioridad] ?? 'bg-gray-400';
        },

        prioridadTexto(prioridad) {
            const mapa = {
                'Crítica': 'text-red-700',
                'Alta':    'text-orange-700',
                'Media':   'text-blue-700',
                'Baja':    'text-gray-600',
            };
            return mapa[prioridad] ?? 'text-gray-600';
        },

        iniciales(nombre) {
            if (!nombre) return '';
            return nombre.split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
        },

        avatarColor(nombre) {
            const colores = ['bg-judicial-600','bg-teal-600','bg-violet-600','bg-rose-600','bg-amber-600'];
            let hash = 0;
            for (let i = 0; i < nombre.length; i++) hash = nombre.charCodeAt(i) + ((hash << 5) - hash);
            return colores[Math.abs(hash) % colores.length];
        },
    }"
>

{{-- ===== BARRA DE FILTROS ===== --}}
<div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-5 p-4">
    <div class="flex flex-col sm:flex-row gap-3">

        {{-- Búsqueda --}}
        <div class="relative flex-1 min-w-0">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                x-model="busqueda"
                placeholder="Buscar por código o título…"
                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg
                       focus:ring-judicial-500 focus:border-judicial-500 bg-gray-50"
            >
        </div>

        {{-- Estado --}}
        <select x-model="filtroEstado"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2
                       focus:ring-judicial-500 focus:border-judicial-500 bg-gray-50 text-gray-700">
            <option value="">Todos los estados</option>
            <option>Nuevo</option>
            <option>Asignado</option>
            <option>En progreso</option>
            <option>Esperando solicitante</option>
            <option>Esperando terceros</option>
            <option>Resuelto</option>
            <option>Cerrado</option>
            <option>Reabierto</option>
            <option>Cancelado</option>
        </select>

        {{-- Prioridad --}}
        <select x-model="filtroPrioridad"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2
                       focus:ring-judicial-500 focus:border-judicial-500 bg-gray-50 text-gray-700">
            <option value="">Todas las prioridades</option>
            <option>Crítica</option>
            <option>Alta</option>
            <option>Media</option>
            <option>Baja</option>
        </select>

        {{-- Categoría --}}
        <select x-model="filtroCategoria"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2
                       focus:ring-judicial-500 focus:border-judicial-500 bg-gray-50 text-gray-700">
            <option value="">Todas las categorías</option>
            <option>Soporte Técnico</option>
            <option>Desarrollo de Software</option>
            <option>Redes</option>
        </select>

        {{-- Limpiar --}}
        <button
            x-show="hayFiltrosActivos"
            x-transition
            @click="limpiarFiltros()"
            class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-600 whitespace-nowrap px-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Limpiar
        </button>
    </div>
</div>

{{-- ===== CABECERA: CONTEO + BOTÓN ===== --}}
<div class="flex items-center justify-between mb-3 px-0.5">
    <p class="text-sm text-gray-500">
        Mostrando
        <span class="font-semibold text-gray-800" x-text="filtrados.length"></span>
        <template x-if="hayFiltrosActivos">
            <span> de <span class="font-semibold text-gray-800" x-text="tickets.length"></span></span>
        </template>
        tickets
    </p>
    <a href="{{ route('tickets.create') }}"
       class="inline-flex items-center gap-2 bg-judicial-700 hover:bg-judicial-800
              text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo ticket
    </a>
</div>

{{-- ===== TABLA ===== --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Código</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Título</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Estado</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Prioridad</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Categoría</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Asignado a</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Actualizado</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center whitespace-nowrap">Ver</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">

                {{-- Filas --}}
                <template x-for="ticket in filtrados" :key="ticket.id">
                    <tr class="hover:bg-gray-50/80 transition-colors group">

                        {{-- Código --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                {{-- Ícono SLA vencido --}}
                                <template x-if="ticket.vencido">
                                    <span title="SLA vencido" class="shrink-0">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                </template>
                                <a :href="'{{ url('tickets') }}/' + ticket.id"
                                   class="font-mono text-xs font-semibold text-judicial-600 hover:text-judicial-800 hover:underline"
                                   x-text="ticket.codigo"></a>
                            </div>
                        </td>

                        {{-- Título --}}
                        <td class="px-4 py-3.5 max-w-xs">
                            <span class="block truncate text-gray-700 group-hover:text-gray-900"
                                  :title="ticket.titulo"
                                  x-text="ticket.titulo"></span>
                        </td>

                        {{-- Estado --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  :class="estadoClases(ticket.estado)"
                                  x-text="ticket.estado"></span>
                        </td>

                        {{-- Prioridad --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold"
                                  :class="prioridadTexto(ticket.prioridad)">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                                      :class="prioridadDot(ticket.prioridad)"></span>
                                <span x-text="ticket.prioridad"></span>
                            </span>
                        </td>

                        {{-- Categoría --}}
                        <td class="px-4 py-3.5 text-gray-600 whitespace-nowrap text-xs" x-text="ticket.categoria"></td>

                        {{-- Asignado a --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <template x-if="ticket.asignado">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold"
                                         :class="avatarColor(ticket.asignado)"
                                         x-text="iniciales(ticket.asignado)"></div>
                                    <span class="text-xs text-gray-700" x-text="ticket.asignado"></span>
                                </div>
                            </template>
                            <template x-if="!ticket.asignado">
                                <span class="text-xs text-gray-400 italic">Sin asignar</span>
                            </template>
                        </td>

                        {{-- Actualizado --}}
                        <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap" x-text="ticket.actualizado"></td>

                        {{-- Acción --}}
                        <td class="px-4 py-3.5 text-center">
                            <a :href="'{{ url('tickets') }}/' + ticket.id"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                      text-gray-400 hover:text-judicial-700 hover:bg-judicial-50
                                      transition-colors"
                               :aria-label="'Ver ticket ' + ticket.codigo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                </template>

            </tbody>
        </table>
    </div>

    {{-- Empty state --}}
    <div x-show="filtrados.length === 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="flex flex-col items-center justify-center py-16 px-4 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <p class="text-gray-700 font-semibold mb-1">Sin resultados</p>
        <p class="text-sm text-gray-400 mb-4">Ningún ticket coincide con los filtros aplicados.</p>
        <button @click="limpiarFiltros()"
                class="text-sm text-judicial-600 hover:underline font-medium">
            Limpiar filtros
        </button>
    </div>
</div>

{{-- ===== PAGINACIÓN ===== --}}
<div x-show="filtrados.length > 0" class="flex items-center justify-between mt-4 px-0.5">
    <p class="text-xs text-gray-500">
        Página <span class="font-semibold text-gray-700">1</span> de <span class="font-semibold text-gray-700">1</span>
    </p>
    <div class="flex items-center gap-1">
        <button disabled
                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-400
                       border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Anterior
        </button>
        <button class="px-3 py-1.5 text-xs font-semibold bg-judicial-700 text-white rounded-lg">1</button>
        <button disabled
                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-400
                       border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50">
            Siguiente
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>

</div>
@endsection
