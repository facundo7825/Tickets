@extends('layouts.app')

@section('title', 'TKT-2026-00021')
@section('page-title', 'Detalle del ticket')

@section('content')
<div x-data="{
    tipoComentario: 'publico',
    comentario: '',
    enviandoComentario: false,

    mostrarDerivacion: false,
    areaDerivacion: '',
    motivoDerivacion: '',
    enviandoDerivacion: false,

    confirmarResolucion: false,
    confirmarEspera: false,

    scrollAComentario(tipo) {
        this.tipoComentario = tipo;
        this.$nextTick(() => {
            document.getElementById('form-comentario').scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.getElementById('textarea-comentario').focus();
        });
    },
}">

{{-- Breadcrumb --}}
<div class="mb-5">
    <a href="{{ route('tickets.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-judicial-700 transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a tickets
    </a>
</div>

{{-- Título del ticket --}}
<div class="mb-5 flex items-start justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="font-mono text-xs font-semibold text-judicial-600 bg-judicial-50 border border-judicial-200 px-2 py-0.5 rounded">TKT-2026-00021</span>
            {{-- SLA vencido --}}
            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                SLA vencido
            </span>
        </div>
        <h2 class="text-lg font-bold text-gray-900 leading-snug">
            Falla crítica en conexión VPN desde juzgado civil nro. 3
        </h2>
    </div>
</div>

{{-- Grid principal --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ========== COLUMNA IZQUIERDA (2/3) ========== --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Descripción --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descripción
            </h3>
            <p class="text-sm text-gray-700 leading-relaxed">
                Desde las 08:15 de hoy, todos los usuarios del juzgado civil nro. 3 (piso 4, ala norte) no pueden conectarse a la VPN institucional. El error que aparece en pantalla es "Authentication failed: peer not found". Se intentó reiniciar los equipos y el router local sin resultado. La falla impide el acceso al sistema IURIX y a los expedientes digitales, bloqueando completamente la actividad jurisdiccional del juzgado. Afecta a 8 puestos de trabajo.
            </p>

            {{-- Adjuntos --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Adjuntos (2)</p>
                <div class="space-y-1.5">
                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-gray-50 hover:bg-judicial-50 border border-gray-200 hover:border-judicial-200 transition-colors group">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-gray-700 flex-1">captura_error_vpn.png</span>
                        <span class="text-xs text-gray-400">342 KB</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-judicial-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-gray-50 hover:bg-judicial-50 border border-gray-200 hover:border-judicial-200 transition-colors group">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-gray-700 flex-1">log_router_20260522.txt</span>
                        <span class="text-xs text-gray-400">18 KB</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-judicial-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Comentarios --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-700">Comentarios (3)</h3>
            </div>

            <div class="divide-y divide-gray-50">

                {{-- Comentario 1: público, solicitante --}}
                <div class="p-5">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-judicial-600 flex items-center justify-center shrink-0 text-white text-xs font-bold">RC</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-sm font-semibold text-gray-800">Dr. Roberto Casas</span>
                                <span class="text-xs bg-judicial-100 text-judicial-700 px-2 py-0.5 rounded-full font-medium">Juez</span>
                                <span class="text-xs text-gray-400 ml-auto">Hace 2 horas</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Confirmo que el problema persiste. Ya son las 10:30 y seguimos sin acceso. Tenemos una audiencia virtual a las 11:00 que depende de este acceso. Por favor prioricen la solución.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Comentario 2: interno, resolutor --}}
                <div class="p-5 bg-amber-50/60">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center shrink-0 text-white text-xs font-bold">LG</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-sm font-semibold text-gray-800">Laura Giménez</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">Redes</span>
                                <span class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Interno
                                </span>
                                <span class="text-xs text-gray-400 ml-auto">Hace 1 hora</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Revisé el concentrador VPN. El certificado del servidor venció ayer a las 23:59. Necesito acceso al panel de administración para renovarlo — ya le mandé el pedido a infra. ETA estimado: 45 minutos si confirman acceso.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Comentario 3: público, resolutor --}}
                <div class="p-5">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center shrink-0 text-white text-xs font-bold">LG</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-sm font-semibold text-gray-800">Laura Giménez</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">Redes</span>
                                <span class="text-xs text-gray-400 ml-auto">Hace 30 min</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Dr. Casas, el problema fue identificado: el certificado del servidor VPN expiró. Ya tenemos acceso al panel de administración y estamos renovándolo. En aproximadamente 20 minutos debería estar solucionado.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Agregar comentario --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5" id="form-comentario">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar comentario
            </h3>

            {{-- Toggle público / interno --}}
            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1 w-fit mb-4" role="group" aria-label="Tipo de comentario">
                <button type="button"
                        @click="tipoComentario = 'publico'"
                        :class="tipoComentario === 'publico'
                            ? 'bg-white shadow-sm text-gray-800 font-semibold'
                            : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs transition-all duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                    Público
                </button>
                <button type="button"
                        @click="tipoComentario = 'interno'"
                        :class="tipoComentario === 'interno'
                            ? 'bg-amber-100 shadow-sm text-amber-800 font-semibold'
                            : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs transition-all duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Interno
                </button>
            </div>

            <div x-show="tipoComentario === 'interno'"
                 x-transition
                 class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3 text-xs text-amber-700">
                <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Este comentario solo será visible para el equipo técnico, no para el solicitante.
            </div>

            <form @submit.prevent="enviandoComentario = true">
                <textarea
                    id="textarea-comentario"
                    x-model="comentario"
                    rows="4"
                    :placeholder="tipoComentario === 'interno' ? 'Escribí una nota interna para el equipo…' : 'Escribí una respuesta para el solicitante…'"
                    :class="tipoComentario === 'interno' ? 'border-amber-300 bg-amber-50/30 focus:ring-amber-400 focus:border-amber-400' : 'border-gray-300 focus:ring-judicial-500 focus:border-judicial-500'"
                    class="w-full rounded-lg shadow-sm text-sm resize-none transition-colors"
                ></textarea>

                <div class="flex items-center justify-end mt-3">
                    <button
                        type="submit"
                        :disabled="enviandoComentario || !comentario.trim()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                               text-white transition-all duration-150
                               disabled:opacity-50 disabled:cursor-not-allowed
                               focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="tipoComentario === 'interno'
                            ? 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400'
                            : 'bg-judicial-700 hover:bg-judicial-800 focus:ring-judicial-500'"
                    >
                        <svg x-show="enviandoComentario" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="enviandoComentario ? 'Enviando…' : 'Agregar comentario'"></span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- ========== COLUMNA DERECHA (1/3) ========== --}}
    <div class="space-y-5">

        {{-- Panel de información --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Información</p>
            </div>
            <div class="divide-y divide-gray-50">

                @php
                $rows = [];
                @endphp

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Código</span>
                    <span class="font-mono text-xs font-semibold text-judicial-700">TKT-2026-00021</span>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Estado</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">En progreso</span>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Prioridad</span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                        Crítica
                    </span>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Categoría</span>
                    <div>
                        <p class="text-xs text-gray-700 font-medium">Redes</p>
                        <p class="text-xs text-gray-400">Conectividad / VPN</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Solicitante</span>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-judicial-600 flex items-center justify-center shrink-0 text-white text-xs font-bold leading-none">RC</div>
                        <div>
                            <p class="text-xs text-gray-700 font-medium leading-tight">Dr. Roberto Casas</p>
                            <p class="text-xs text-gray-400">Juez</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Asignado a</span>
                    <div class="flex items-start gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="w-5 h-5 rounded-full bg-teal-600 flex items-center justify-center shrink-0 text-white text-xs font-bold leading-none">LG</div>
                            <p class="text-xs text-gray-700 font-medium">Laura Giménez</p>
                        </div>
                        <button class="text-xs text-judicial-600 hover:underline shrink-0">Reasignar</button>
                    </div>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Creado</span>
                    <span class="text-xs text-gray-700">20/05/2026 08:32</span>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">Actualizado</span>
                    <span class="text-xs text-gray-700">22/05/2026 09:51</span>
                </div>

                <div class="flex items-start gap-2 px-4 py-2.5">
                    <span class="text-xs text-gray-400 w-28 shrink-0 pt-0.5">SLA</span>
                    <div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Vencido
                        </span>
                        <p class="text-xs text-gray-400 mt-0.5">+1h 28min sobre el límite</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Acciones --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Acciones</p>
            </div>
            <div class="p-4 space-y-2">

                {{-- Marcar como resuelto --}}
                <div>
                    <button x-show="!confirmarResolucion"
                            @click="confirmarResolucion = true"
                            class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold
                                   bg-green-600 hover:bg-green-700 text-white transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Marcar como resuelto
                    </button>
                    <div x-show="confirmarResolucion"
                         x-transition
                         class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <p class="text-xs text-green-800 font-medium mb-2">¿Confirmar resolución? Se notificará al solicitante.</p>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-1.5 rounded-lg transition-colors">
                                Confirmar
                            </button>
                            <button @click="confirmarResolucion = false"
                                    class="flex-1 bg-white border border-gray-300 text-gray-600 text-xs font-medium py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Pedir información --}}
                <div>
                    <button x-show="!confirmarEspera"
                            @click="confirmarEspera = true"
                            class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium
                                   border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 shrink-0 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pedir info al solicitante
                    </button>
                    <div x-show="confirmarEspera"
                         x-transition
                         class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                        <p class="text-xs text-purple-800 font-medium mb-2">El ticket pasará a "Esperando solicitante" y se pausará el SLA.</p>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold py-1.5 rounded-lg transition-colors">
                                Confirmar
                            </button>
                            <button @click="confirmarEspera = false"
                                    class="flex-1 bg-white border border-gray-300 text-gray-600 text-xs font-medium py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Derivar a otra área --}}
                <div>
                    <button @click="mostrarDerivacion = !mostrarDerivacion"
                            x-show="!mostrarDerivacion"
                            class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium
                                   border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 shrink-0 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Derivar a otra área
                    </button>

                    <div x-show="mostrarDerivacion"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="bg-orange-50 border border-orange-200 rounded-lg p-3 space-y-2">
                        <p class="text-xs font-semibold text-orange-800">Derivar ticket</p>
                        <select x-model="areaDerivacion"
                                class="w-full text-xs rounded-lg border-orange-300 focus:ring-orange-400 focus:border-orange-400 bg-white">
                            <option value="">Seleccioná el área…</option>
                            <option>Soporte Técnico</option>
                            <option>Desarrollo de Software</option>
                        </select>
                        <textarea x-model="motivoDerivacion"
                                  rows="2"
                                  placeholder="Motivo de la derivación (obligatorio)…"
                                  class="w-full text-xs rounded-lg border-orange-300 focus:ring-orange-400 focus:border-orange-400 resize-none bg-white"></textarea>
                        <div class="flex gap-2">
                            <button :disabled="!areaDerivacion || !motivoDerivacion.trim()"
                                    class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed
                                           text-white text-xs font-semibold py-1.5 rounded-lg transition-colors">
                                Derivar
                            </button>
                            <button @click="mostrarDerivacion = false; areaDerivacion = ''; motivoDerivacion = ''"
                                    class="flex-1 bg-white border border-gray-300 text-gray-600 text-xs font-medium py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Nota interna --}}
                <button @click="scrollAComentario('interno')"
                        class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium
                               border border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Agregar nota interna
                </button>

            </div>
        </div>

        {{-- Línea de tiempo --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Línea de tiempo</p>
            </div>
            <div class="p-4">
                <ol class="space-y-0">

                    @php
                    $eventos = [
                        ['color' => 'bg-blue-500',   'bold' => false, 'desc' => 'Ticket creado por Dr. Roberto Casas',          'time' => '20/05 08:32'],
                        ['color' => 'bg-indigo-500',  'bold' => false, 'desc' => 'Asignado al equipo de Redes',                  'time' => '20/05 08:45'],
                        ['color' => 'bg-yellow-500',  'bold' => false, 'desc' => 'Estado cambiado a En progreso por Laura G.',   'time' => '20/05 09:10'],
                        ['color' => 'bg-red-500',     'bold' => true,  'desc' => 'SLA de respuesta inicial vencido',             'time' => '20/05 09:02'],
                        ['color' => 'bg-gray-400',    'bold' => false, 'desc' => 'Comentario público agregado por Laura G.',     'time' => '22/05 09:51'],
                    ];
                    @endphp

                    @foreach ($eventos as $i => $evento)
                    <li class="flex gap-3 {{ !$loop->last ? 'pb-4' : '' }}">
                        <div class="flex flex-col items-center shrink-0">
                            <div class="w-2.5 h-2.5 rounded-full {{ $evento['color'] }} shrink-0 mt-0.5 ring-2 ring-white"></div>
                            @if (!$loop->last)
                                <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 pb-0.5">
                            <p class="text-xs leading-snug {{ $evento['bold'] ? 'font-semibold text-red-700' : 'text-gray-700' }}">
                                {{ $evento['desc'] }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $evento['time'] }}</p>
                        </div>
                    </li>
                    @endforeach

                </ol>
            </div>
        </div>

    </div>
</div>

</div>
@endsection
