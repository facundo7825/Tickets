@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes')

@section('content')
<div
    x-data="{
        /* ── Filtros ── */
        periodo: '30',
        filtroArea: '',
        filtroCategoria: '',

        /* ── Tab activo ── */
        tabActiva: 'estados',

        /* ── Exportando ── */
        exportando: false,

        /* ── Datos mock ── */

        resumenGeneral: {
            total: 147, nuevos: 12, enProgreso: 34, resueltos: 58, cerrados: 38, cancelados: 5,
            slaVencidos: 9, tasaResolucion: 87, tiempoPromedioHs: 14.3,
        },

        ticketsPorEstado: [
            { estado: 'Nuevo',                color: 'bg-blue-500',    porcentaje: 8,  cantidad: 12 },
            { estado: 'Asignado',             color: 'bg-indigo-500',  porcentaje: 11, cantidad: 16 },
            { estado: 'En progreso',          color: 'bg-yellow-500',  porcentaje: 23, cantidad: 34 },
            { estado: 'Esperando solicitante',color: 'bg-orange-400',  porcentaje: 5,  cantidad: 7  },
            { estado: 'Esperando terceros',   color: 'bg-amber-400',   porcentaje: 3,  cantidad: 5  },
            { estado: 'Resuelto',             color: 'bg-emerald-500', porcentaje: 39, cantidad: 58 },
            { estado: 'Cerrado',              color: 'bg-gray-400',    porcentaje: 26, cantidad: 38 },
            { estado: 'Cancelado',            color: 'bg-rose-400',    porcentaje: 3,  cantidad: 5  },
        ],

        ticketsPorPrioridad: [
            { prioridad: 'Crítica', color: 'bg-rose-600',   dot: 'bg-rose-600',   porcentaje: 6,  cantidad: 9  },
            { prioridad: 'Alta',    color: 'bg-orange-500', dot: 'bg-orange-500', porcentaje: 22, cantidad: 32 },
            { prioridad: 'Media',   color: 'bg-yellow-500', dot: 'bg-yellow-400', porcentaje: 47, cantidad: 69 },
            { prioridad: 'Baja',    color: 'bg-blue-400',   dot: 'bg-blue-400',   porcentaje: 25, cantidad: 37 },
        ],

        ticketsPorCategoria: [
            { categoria: 'Soporte Técnico',      icono: '🖥️',  cantidad: 72, porcentaje: 49, vencidos: 3 },
            { categoria: 'Redes y Conectividad', icono: '🌐',  cantidad: 43, porcentaje: 29, vencidos: 4 },
            { categoria: 'Desarrollo de Software',icono: '💻', cantidad: 32, porcentaje: 22, vencidos: 2 },
        ],

        slaEquipos: [
            { equipo: 'Soporte Técnico',       resolutor: 'Carlos Méndez',    asignados: 28, resueltos: 24, vencidos: 3, cumplimiento: 89 },
            { equipo: 'Soporte Técnico',       resolutor: 'Valeria Sosa',     asignados: 14, resueltos: 11, vencidos: 0, cumplimiento: 100 },
            { equipo: 'Redes y Conectividad',  resolutor: 'Laura Giménez',    asignados: 21, resueltos: 17, vencidos: 4, cumplimiento: 81 },
            { equipo: 'Redes y Conectividad',  resolutor: 'Ing. Sergio Ruiz', asignados: 15, resueltos: 13, vencidos: 0, cumplimiento: 100 },
            { equipo: 'Desarrollo',            resolutor: 'Pablo Rodríguez',  asignados: 19, resueltos: 15, vencidos: 2, cumplimiento: 89 },
            { equipo: 'Desarrollo',            resolutor: 'Lic. Claudia Vega',asignados: 13, resueltos: 10, vencidos: 0, cumplimiento: 100 },
        ],

        ticketsPorUnidad: [
            { unidad: 'Juzgado Civil N° 3',         tipo: 'Juzgado',   cantidad: 31, porcentaje: 21 },
            { unidad: 'Juzgado Penal N° 1',         tipo: 'Juzgado',   cantidad: 24, porcentaje: 16 },
            { unidad: 'Sala I – Cámara Civil',       tipo: 'Sala',      cantidad: 19, porcentaje: 13 },
            { unidad: 'Juzgado Laboral N° 2',        tipo: 'Juzgado',   cantidad: 18, porcentaje: 12 },
            { unidad: 'Juzgado Civil N° 1',          tipo: 'Juzgado',   cantidad: 15, porcentaje: 10 },
            { unidad: 'Juzgado Penal N° 2',          tipo: 'Juzgado',   cantidad: 14, porcentaje: 10 },
            { unidad: 'Sala II – Cámara Civil',      tipo: 'Sala',      cantidad: 13, porcentaje: 9  },
            { unidad: 'Juzgado Civil N° 2',          tipo: 'Juzgado',   cantidad: 13, porcentaje: 9  },
        ],

        slaVencidosDetalle: [
            { codigo: 'TKT-2026-00021', titulo: 'Falla crítica en conexión VPN desde juzgado civil nro. 3',    prioridad: 'Crítica', area: 'Redes',           vencidoHace: '18 hs', asignado: 'Laura Giménez' },
            { codigo: 'TKT-2026-00015', titulo: 'Sistema IURIX inaccesible desde secretaría penal',            prioridad: 'Alta',    area: 'Soporte Técnico', vencidoHace: '31 hs', asignado: 'Carlos Méndez' },
            { codigo: 'TKT-2026-00011', titulo: 'Impresora en red sin conexión – sala de audiencias',          prioridad: 'Media',   area: 'Redes',           vencidoHace: '2 días',asignado: 'Laura Giménez' },
            { codigo: 'TKT-2026-00009', titulo: 'Exportación a PDF genera archivo corrupto en módulo actas',   prioridad: 'Alta',    area: 'Desarrollo',      vencidoHace: '3 días',asignado: 'Pablo Rodríguez' },
            { codigo: 'TKT-2026-00007', titulo: 'Correo institucional sin acceso por error de certificado',    prioridad: 'Media',   area: 'Redes',           vencidoHace: '3 días',asignado: 'Sin asignar' },
        ],

        colorPrioridad(p) {
            const m = { 'Crítica': 'bg-rose-100 text-rose-700', 'Alta': 'bg-orange-100 text-orange-700', 'Media': 'bg-yellow-100 text-yellow-700', 'Baja': 'bg-blue-100 text-blue-700' };
            return m[p] || 'bg-gray-100 text-gray-600';
        },

        dotPrioridad(p) {
            const m = { 'Crítica': 'bg-rose-500', 'Alta': 'bg-orange-500', 'Media': 'bg-yellow-500', 'Baja': 'bg-blue-400' };
            return m[p] || 'bg-gray-400';
        },

        colorCumplimiento(v) {
            if (v >= 95) return 'text-emerald-600';
            if (v >= 80) return 'text-yellow-600';
            return 'text-rose-600';
        },

        barCumplimiento(v) {
            if (v >= 95) return 'bg-emerald-500';
            if (v >= 80) return 'bg-yellow-400';
            return 'bg-rose-500';
        },

        exportarCSV() {
            this.exportando = true;
            setTimeout(() => { this.exportando = false; }, 1200);
        },
    }"
>

{{-- ===== FILTROS ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
    <div class="flex flex-wrap items-end gap-3">

        <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Período</label>
            <select x-model="periodo" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700">
                <option value="7">Últimos 7 días</option>
                <option value="30">Últimos 30 días</option>
                <option value="90">Últimos 90 días</option>
                <option value="365">Último año</option>
            </select>
        </div>

        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Área técnica</label>
            <select x-model="filtroArea" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700">
                <option value="">Todas las áreas</option>
                <option>Soporte Técnico</option>
                <option>Redes y Conectividad</option>
                <option>Desarrollo de Software</option>
            </select>
        </div>

        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Categoría</label>
            <select x-model="filtroCategoria" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700">
                <option value="">Todas las categorías</option>
                <option>Hardware y periféricos</option>
                <option>Software y aplicaciones</option>
                <option>Conectividad y redes</option>
                <option>Sistemas judiciales</option>
                <option>Desarrollo a medida</option>
            </select>
        </div>

        <div class="flex gap-2 shrink-0">
            <button
                @click="periodo='30'; filtroArea=''; filtroCategoria=''"
                class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Limpiar
            </button>
            <button
                @click="exportarCSV()"
                :disabled="exportando"
                class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap"
            >
                <template x-if="!exportando">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </template>
                <template x-if="exportando">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                </template>
                <span x-text="exportando ? 'Exportando…' : 'Exportar CSV'"></span>
            </button>
        </div>

    </div>
</div>

{{-- ===== MÉTRICAS RESUMEN ===== --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4 col-span-2 sm:col-span-1">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total tickets</p>
        <p class="text-3xl font-bold text-gray-800 mt-1" x-text="resumenGeneral.total"></p>
        <p class="text-xs text-gray-400 mt-1">en el período</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">En progreso</p>
        <p class="text-3xl font-bold text-yellow-500 mt-1" x-text="resumenGeneral.enProgreso"></p>
        <p class="text-xs text-gray-400 mt-1">activos ahora</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Resueltos</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1" x-text="resumenGeneral.resueltos"></p>
        <p class="text-xs text-gray-400 mt-1">en el período</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">SLA vencidos</p>
        <p class="text-3xl font-bold text-rose-600 mt-1" x-text="resumenGeneral.slaVencidos"></p>
        <p class="text-xs text-rose-400 mt-1">requieren atención</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tasa resolución</p>
        <p class="text-3xl font-bold text-judicial-700 mt-1"><span x-text="resumenGeneral.tasaResolucion"></span>%</p>
        <p class="text-xs text-gray-400 mt-1">sobre cerrados</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tiempo promedio</p>
        <p class="text-3xl font-bold text-gray-700 mt-1"><span x-text="resumenGeneral.tiempoPromedioHs"></span><span class="text-lg font-medium">hs</span></p>
        <p class="text-xs text-gray-400 mt-1">hasta resolución</p>
    </div>

</div>

{{-- ===== TABS ===== --}}
<div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-6 overflow-x-auto">
    <button @click="tabActiva = 'estados'"
        class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
        :class="tabActiva === 'estados' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
        Por estado y prioridad
    </button>
    <button @click="tabActiva = 'sla'"
        class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
        :class="tabActiva === 'sla' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
        SLA vencidos
    </button>
    <button @click="tabActiva = 'carga'"
        class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
        :class="tabActiva === 'carga' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
        Carga por resolutor
    </button>
    <button @click="tabActiva = 'unidades'"
        class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
        :class="tabActiva === 'unidades' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
        Por unidad solicitante
    </button>
</div>

{{-- ===== TAB: ESTADOS Y PRIORIDAD ===== --}}
<div x-show="tabActiva === 'estados'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Tickets por estado --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Distribución por estado</h3>
                <p class="text-xs text-gray-400 mt-0.5">Total de tickets en el período seleccionado</p>
            </div>
            <div class="px-5 py-4 space-y-3.5">
                <template x-for="item in ticketsPorEstado" :key="item.estado">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-gray-600" x-text="item.estado"></span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-700" x-text="item.cantidad"></span>
                                <span class="text-xs text-gray-400" x-text="'(' + item.porcentaje + '%)'"></span>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="item.color"
                                :style="'width: ' + item.porcentaje + '%'"
                            ></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Panel derecho: Prioridad + Categoría --}}
        <div class="space-y-6">

            {{-- Tickets por prioridad --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Distribución por prioridad</h3>
                </div>
                <div class="px-5 py-4">
                    {{-- Barra apilada --}}
                    <div class="flex h-5 rounded-full overflow-hidden gap-0.5 mb-4">
                        <template x-for="item in ticketsPorPrioridad" :key="item.prioridad">
                            <div
                                class="h-full first:rounded-l-full last:rounded-r-full transition-all duration-500"
                                :class="item.color"
                                :style="'width: ' + item.porcentaje + '%'"
                                :title="item.prioridad + ': ' + item.cantidad"
                            ></div>
                        </template>
                    </div>
                    {{-- Leyenda --}}
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="item in ticketsPorPrioridad" :key="item.prioridad">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" :class="item.dot"></span>
                                <span class="text-xs text-gray-600" x-text="item.prioridad"></span>
                                <span class="text-xs font-bold text-gray-800 ml-auto" x-text="item.cantidad"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Tickets por categoría --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Por categoría</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    <template x-for="item in ticketsPorCategoria" :key="item.categoria">
                        <div class="px-5 py-3.5 flex items-center gap-4">
                            <span class="text-xl shrink-0" x-text="item.icono"></span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-700 truncate" x-text="item.categoria"></span>
                                    <span class="text-xs font-bold text-gray-800 ml-2 shrink-0" x-text="item.cantidad"></span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-judicial-500 rounded-full" :style="'width: ' + item.porcentaje + '%'"></div>
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <template x-if="item.vencidos > 0">
                                    <span class="inline-flex items-center gap-1 text-xs text-rose-600 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span x-text="item.vencidos + ' vencidos'"></span>
                                    </span>
                                </template>
                                <template x-if="item.vencidos === 0">
                                    <span class="text-xs text-emerald-500 font-medium">OK</span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===== TAB: SLA VENCIDOS ===== --}}
<div x-show="tabActiva === 'sla'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Tickets con SLA vencido</h3>
                <p class="text-xs text-gray-400 mt-0.5">Requieren atención inmediata del coordinador.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span x-text="slaVencidosDetalle.length + ' pendientes'"></span>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left border-b border-gray-100">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Código</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Título</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Prioridad</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Área</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Vencido hace</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Asignado a</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="t in slaVencidosDetalle" :key="t.codigo">
                        <tr class="hover:bg-rose-50/40 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-judicial-600 whitespace-nowrap" x-text="t.codigo"></td>
                            <td class="px-5 py-3.5 text-gray-700 max-w-xs">
                                <span class="line-clamp-1 text-xs" x-text="t.titulo"></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium" :class="colorPrioridad(t.prioridad)">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="dotPrioridad(t.prioridad)"></span>
                                    <span x-text="t.prioridad"></span>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 hidden md:table-cell" x-text="t.area"></td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="t.vencidoHace"></span>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 hidden lg:table-cell" x-text="t.asignado"></td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ url('tickets') }}/1" class="text-xs font-medium text-judicial-600 hover:underline whitespace-nowrap">Ver ticket →</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- SLA por área (resumen) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-base">🖥️</div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">Soporte Técnico</p>
                    <p class="text-xs text-gray-400">3 tickets vencidos</p>
                </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-400 rounded-full" style="width: 89%"></div>
            </div>
            <p class="text-right text-xs font-bold text-yellow-600 mt-1">89% cumplimiento</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-base">🌐</div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">Redes y Conectividad</p>
                    <p class="text-xs text-gray-400">4 tickets vencidos</p>
                </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: 81%"></div>
            </div>
            <p class="text-right text-xs font-bold text-rose-600 mt-1">81% cumplimiento</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-base">💻</div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">Desarrollo de Software</p>
                    <p class="text-xs text-gray-400">2 tickets vencidos</p>
                </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-400 rounded-full" style="width: 89%"></div>
            </div>
            <p class="text-right text-xs font-bold text-yellow-600 mt-1">89% cumplimiento</p>
        </div>
    </div>

</div>

{{-- ===== TAB: CARGA POR RESOLUTOR ===== --}}
<div x-show="tabActiva === 'carga'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Carga de trabajo por resolutor</h3>
            <p class="text-xs text-gray-400 mt-0.5">Tickets asignados, resueltos y SLA en el período.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left border-b border-gray-100">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Resolutor</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Equipo</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">Asignados</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">Resueltos</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">SLA vencidos</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Cumplimiento SLA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="r in slaEquipos" :key="r.resolutor">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-judicial-700 flex items-center justify-center text-xs font-bold text-white shrink-0"
                                         x-text="r.resolutor.split(' ').filter(p => p.length > 0).slice(0,2).map(p => p[0]).join('')">
                                    </div>
                                    <span class="text-xs font-medium text-gray-800" x-text="r.resolutor"></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 hidden sm:table-cell" x-text="r.equipo"></td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-bold text-gray-700" x-text="r.asignados"></span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-bold text-emerald-600" x-text="r.resueltos"></span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-bold" :class="r.vencidos > 0 ? 'text-rose-600' : 'text-gray-300'" x-text="r.vencidos"></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5 min-w-[120px]">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div
                                            class="h-full rounded-full transition-all duration-500"
                                            :class="barCumplimiento(r.cumplimiento)"
                                            :style="'width: ' + r.cumplimiento + '%'"
                                        ></div>
                                    </div>
                                    <span class="text-xs font-bold shrink-0" :class="colorCumplimiento(r.cumplimiento)" x-text="r.cumplimiento + '%'"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot class="border-t-2 border-gray-100 bg-gray-50">
                    <tr>
                        <td class="px-5 py-3 text-xs font-semibold text-gray-600" colspan="2">Totales del período</td>
                        <td class="px-5 py-3 text-center text-sm font-bold text-gray-700">110</td>
                        <td class="px-5 py-3 text-center text-sm font-bold text-emerald-600">90</td>
                        <td class="px-5 py-3 text-center text-sm font-bold text-rose-600">9</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-bold text-yellow-600">92% promedio</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

{{-- ===== TAB: POR UNIDAD SOLICITANTE ===== --}}
<div x-show="tabActiva === 'unidades'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Tickets por unidad organizativa solicitante</h3>
            <p class="text-xs text-gray-400 mt-0.5">Qué juzgados y salas generan más solicitudes.</p>
        </div>

        <div class="px-5 py-5 space-y-4">
            <template x-for="u in ticketsPorUnidad" :key="u.unidad">
                <div class="flex items-center gap-4">
                    {{-- Tipo badge --}}
                    <div class="shrink-0 w-16 text-right">
                        <span
                            class="inline-block px-2 py-0.5 rounded text-xs font-medium"
                            :class="u.tipo === 'Juzgado' ? 'bg-judicial-100 text-judicial-700' : 'bg-dorado-500/10 text-dorado-600'"
                            x-text="u.tipo"
                        ></span>
                    </div>
                    {{-- Nombre --}}
                    <div class="w-52 shrink-0">
                        <span class="text-xs font-medium text-gray-700" x-text="u.unidad"></span>
                    </div>
                    {{-- Barra --}}
                    <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-judicial-500 rounded-full transition-all duration-500"
                            :style="'width: ' + u.porcentaje + '%'"
                        ></div>
                    </div>
                    {{-- Número --}}
                    <div class="shrink-0 w-16 flex items-center justify-end gap-1.5">
                        <span class="text-sm font-bold text-gray-800" x-text="u.cantidad"></span>
                        <span class="text-xs text-gray-400" x-text="'(' + u.porcentaje + '%)'"></span>
                    </div>
                </div>
            </template>
        </div>

        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between text-xs text-gray-400">
            <span>Total: <span class="font-semibold text-gray-600">147 tickets</span> en el período</span>
            <span class="hidden sm:block">Los datos se actualizan al cambiar el período en los filtros.</span>
        </div>
    </div>

</div>

</div>
@endsection
