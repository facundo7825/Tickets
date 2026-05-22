@extends('layouts.app')

@section('title', 'Configuración')
@section('page-title', 'Configuración del sistema')

@section('content')
<div
    x-data="{
        tabActiva: 'categorias',

        /* ── Flash ── */
        flash: null,
        mostrarFlash(msg) { this.flash = msg; setTimeout(() => this.flash = null, 3500); },

        /* ══════════════════════════════════════
           TAB 1 — CATEGORÍAS Y SUBCATEGORÍAS
        ══════════════════════════════════════ */
        categorias: [
            {
                id: 1, nombre: 'Soporte Técnico', equipo: 'Equipo de Soporte Técnico',
                icono: '🖥️', expandida: true,
                subcategorias: [
                    { id: 1, nombre: 'Hardware',                 activa: true  },
                    { id: 2, nombre: 'Impresoras y escáneres',   activa: true  },
                    { id: 3, nombre: 'Software de escritorio',   activa: true  },
                    { id: 4, nombre: 'Sesión de usuario',        activa: true  },
                    { id: 5, nombre: 'Telefonía no IP',          activa: true  },
                    { id: 6, nombre: 'Mudanzas / instalaciones', activa: true  },
                    { id: 7, nombre: 'Otros (Soporte)',          activa: true  },
                ],
            },
            {
                id: 2, nombre: 'Desarrollo de Software', equipo: 'Equipo de Desarrollo',
                icono: '💻', expandida: false,
                subcategorias: [
                    { id: 8,  nombre: 'Bug en sistema judicial',         activa: true  },
                    { id: 9,  nombre: 'Solicitud de funcionalidad',      activa: true  },
                    { id: 10, nombre: 'Consulta de uso',                 activa: true  },
                    { id: 11, nombre: 'Datos / migraciones',             activa: true  },
                    { id: 12, nombre: 'Integraciones',                   activa: true  },
                    { id: 13, nombre: 'Errores de impresión documental', activa: false },
                    { id: 14, nombre: 'Otros (Desarrollo)',              activa: true  },
                ],
            },
            {
                id: 3, nombre: 'Redes', equipo: 'Equipo de Redes',
                icono: '🌐', expandida: false,
                subcategorias: [
                    { id: 15, nombre: 'Conectividad',         activa: true  },
                    { id: 16, nombre: 'VPN',                  activa: true  },
                    { id: 17, nombre: 'Telefonía IP',         activa: true  },
                    { id: 18, nombre: 'Recursos compartidos', activa: true  },
                    { id: 19, nombre: 'Acceso remoto',        activa: true  },
                    { id: 20, nombre: 'Wi-Fi institucional',  activa: true  },
                    { id: 21, nombre: 'Otros (Redes)',        activa: true  },
                ],
            },
        ],

        modalSubcat: false,
        subcatCatId: null,
        subcatNombre: '',
        guardandoSubcat: false,

        abrirModalSubcat(catId) {
            this.subcatCatId = catId;
            this.subcatNombre = '';
            this.modalSubcat = true;
            this.$nextTick(() => this.$refs.inputSubcat && this.$refs.inputSubcat.focus());
        },

        guardarSubcat() {
            if (!this.subcatNombre.trim()) return;
            this.guardandoSubcat = true;
            setTimeout(() => {
                const cat = this.categorias.find(c => c.id === this.subcatCatId);
                if (cat) cat.subcategorias.push({ id: Date.now(), nombre: this.subcatNombre.trim(), activa: true });
                this.guardandoSubcat = false;
                this.modalSubcat = false;
                this.mostrarFlash('Subcategoría agregada correctamente.');
            }, 600);
        },

        toggleSubcat(sub) {
            sub.activa = !sub.activa;
            this.mostrarFlash(sub.activa ? `'${sub.nombre}' reactivada.` : `'${sub.nombre}' marcada como obsoleta.`);
        },

        /* ══════════════════════════════════════
           TAB 2 — SLA
        ══════════════════════════════════════ */
        slaRows: [
            { prioridad: 'Crítica', colorDot: 'bg-rose-600',   colorBadge: 'bg-rose-100 text-rose-700',    respLabel: '30 min',     resLabel: '4 horas hábiles',  editando: false, tmpResp: '', tmpRes: '' },
            { prioridad: 'Alta',    colorDot: 'bg-orange-500', colorBadge: 'bg-orange-100 text-orange-700', respLabel: '2 hs háb.',  resLabel: '1 día hábil',      editando: false, tmpResp: '', tmpRes: '' },
            { prioridad: 'Media',   colorDot: 'bg-yellow-400', colorBadge: 'bg-yellow-100 text-yellow-700', respLabel: '1 día háb.', resLabel: '3 días hábiles',   editando: false, tmpResp: '', tmpRes: '' },
            { prioridad: 'Baja',    colorDot: 'bg-blue-400',   colorBadge: 'bg-blue-100 text-blue-700',     respLabel: '2 días háb.',resLabel: '10 días hábiles',  editando: false, tmpResp: '', tmpRes: '' },
        ],
        horarioInicio: '07:30',
        horarioFin: '13:30',
        guardandoHorario: false,
        horarioGuardado: false,

        get horasHabiles() {
            const [hI, mI] = this.horarioInicio.split(':').map(Number);
            const [hF, mF] = this.horarioFin.split(':').map(Number);
            return ((hF + mF / 60) - (hI + mI / 60)).toFixed(1);
        },

        editarSLA(row) { row.tmpResp = row.respLabel; row.tmpRes = row.resLabel; row.editando = true; },
        guardarSLA(row) { row.respLabel = row.tmpResp; row.resLabel = row.tmpRes; row.editando = false; this.mostrarFlash(`SLA de prioridad ${row.prioridad} actualizado.`); },
        cancelarSLA(row) { row.editando = false; },

        guardarHorario() {
            this.guardandoHorario = true;
            setTimeout(() => { this.guardandoHorario = false; this.horarioGuardado = true; setTimeout(() => this.horarioGuardado = false, 3000); }, 700);
        },

        /* ══════════════════════════════════════
           TAB 3 — UNIDADES ORGANIZATIVAS
        ══════════════════════════════════════ */
        unidades: [
            { id: 1,  nombre: 'Sala I – Cámara Civil',          tipo: 'sala',         activa: true  },
            { id: 2,  nombre: 'Sala II – Cámara Civil',         tipo: 'sala',         activa: true  },
            { id: 3,  nombre: 'Juzgado Civil N° 1',             tipo: 'juzgado',      activa: true  },
            { id: 4,  nombre: 'Juzgado Civil N° 2',             tipo: 'juzgado',      activa: true  },
            { id: 5,  nombre: 'Juzgado Civil N° 3',             tipo: 'juzgado',      activa: true  },
            { id: 6,  nombre: 'Juzgado Penal N° 1',             tipo: 'juzgado',      activa: true  },
            { id: 7,  nombre: 'Juzgado Penal N° 2',             tipo: 'juzgado',      activa: true  },
            { id: 8,  nombre: 'Juzgado Laboral N° 1',           tipo: 'juzgado',      activa: true  },
            { id: 9,  nombre: 'Juzgado Laboral N° 2',           tipo: 'juzgado',      activa: false },
            { id: 10, nombre: 'Mesa de Ayuda',                  tipo: 'area_tecnica', activa: true  },
            { id: 11, nombre: 'Área de Desarrollo',             tipo: 'area_tecnica', activa: true  },
            { id: 12, nombre: 'Área de Redes y Conectividad',   tipo: 'area_tecnica', activa: true  },
            { id: 13, nombre: 'Dirección de Sistemas',          tipo: 'area_tecnica', activa: true  },
        ],
        busqUnidad: '',
        filtroTipoUnidad: '',
        modalUnidad: false,
        modoEditarUnidad: false,
        unidadForm: { id: null, nombre: '', tipo: '', activa: true },
        guardandoUnidad: false,

        get unidadesFiltradas() {
            const q = this.busqUnidad.toLowerCase();
            return this.unidades.filter(u =>
                (!q || u.nombre.toLowerCase().includes(q)) &&
                (!this.filtroTipoUnidad || u.tipo === this.filtroTipoUnidad)
            );
        },

        labelTipo(tipo) {
            const m = { juzgado: 'Juzgado', sala: 'Sala', area_tecnica: 'Área técnica', otra: 'Otra' };
            return m[tipo] || tipo;
        },
        colorTipo(tipo) {
            if (tipo === 'juzgado')      return 'bg-judicial-100 text-judicial-700';
            if (tipo === 'sala')         return 'bg-amber-100 text-amber-700';
            if (tipo === 'area_tecnica') return 'bg-emerald-100 text-emerald-700';
            return 'bg-gray-100 text-gray-600';
        },

        abrirCrearUnidad() {
            this.unidadForm = { id: null, nombre: '', tipo: '', activa: true };
            this.modoEditarUnidad = false;
            this.modalUnidad = true;
        },
        abrirEditarUnidad(u) {
            this.unidadForm = { ...u };
            this.modoEditarUnidad = true;
            this.modalUnidad = true;
        },
        guardarUnidad() {
            if (!this.unidadForm.nombre.trim() || !this.unidadForm.tipo) return;
            this.guardandoUnidad = true;
            setTimeout(() => {
                if (this.modoEditarUnidad) {
                    const idx = this.unidades.findIndex(u => u.id === this.unidadForm.id);
                    if (idx !== -1) this.unidades.splice(idx, 1, { ...this.unidadForm });
                    this.mostrarFlash('Unidad actualizada correctamente.');
                } else {
                    this.unidades.push({ ...this.unidadForm, id: Date.now() });
                    this.mostrarFlash('Unidad creada correctamente.');
                }
                this.guardandoUnidad = false;
                this.modalUnidad = false;
            }, 700);
        },
        toggleUnidad(u) {
            u.activa = !u.activa;
            this.mostrarFlash(u.activa ? `'${u.nombre}' activada.` : `'${u.nombre}' desactivada.`);
        },

        /* ══════════════════════════════════════
           TAB 4 — SISTEMA
        ══════════════════════════════════════ */
        sistema: {
            nombreOrganismo: 'Poder Judicial de la Provincia',
            nombreSistema: 'Sistema de Gestión de Tickets',
            emailSoporte: 'sistemas@pjudicial.gob.ar',
            smtpHost: 'mail.pjudicial.gob.ar',
            smtpPuerto: '587',
            smtpUsuario: 'notificaciones@pjudicial.gob.ar',
            timeoutSesion: '60',
            diasAutoClose: '5',
            maxAdjuntosMb: '10',
            maxAdjuntosQty: '20',
            estrategiaAsignacion: 'cola',
        },
        guardandoSistema: false,
        sistemaGuardado: false,
        mostrarSmtp: false,

        guardarSistema() {
            this.guardandoSistema = true;
            setTimeout(() => { this.guardandoSistema = false; this.sistemaGuardado = true; setTimeout(() => this.sistemaGuardado = false, 3000); }, 800);
        },
    }"
    @keydown.escape.window="modalSubcat = false; modalUnidad = false"
>

{{-- Flash --}}
<div
    x-show="flash !== null"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-judicial-800 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium"
    style="display:none"
>
    <svg class="w-4 h-4 text-dorado-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <span x-text="flash"></span>
</div>

{{-- ===== TABS ===== --}}
<div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-6 overflow-x-auto">
    @foreach ([['categorias','Categorías y subcategorías'],['sla','SLA y horarios'],['unidades','Unidades organizativas'],['sistema','Sistema']] as [$val,$label])
    <button
        @click="tabActiva = '{{ $val }}'"
        class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
        :class="tabActiva === '{{ $val }}' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
    >{{ $label }}</button>
    @endforeach
</div>

{{-- ══════════════════════════════════════
     TAB 1 — CATEGORÍAS
══════════════════════════════════════ --}}
<div x-show="tabActiva === 'categorias'">

    <div class="space-y-4">
        <template x-for="cat in categorias" :key="cat.id">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                <button
                    @click="cat.expandida = !cat.expandida"
                    class="w-full flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors text-left"
                >
                    <span class="text-xl shrink-0" x-text="cat.icono"></span>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800" x-text="cat.nombre"></p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="cat.equipo + ' · ' + cat.subcategorias.length + ' subcategorías'"></p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs text-gray-400" x-text="cat.subcategorias.filter(s => s.activa).length + ' activas'"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="cat.expandida ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <div x-show="cat.expandida" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="border-t border-gray-100">
                        <template x-for="sub in cat.subcategorias" :key="sub.id">
                            <div class="flex items-center gap-3 px-5 py-2.5 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 transition-colors">
                                <div class="w-1.5 h-1.5 rounded-full shrink-0" :class="sub.activa ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                <span class="flex-1 text-sm" :class="sub.activa ? 'text-gray-700' : 'text-gray-400 line-through'" x-text="sub.nombre"></span>
                                <span x-show="!sub.activa" class="text-xs text-gray-400 italic px-2">obsoleta</span>
                                <button
                                    @click="toggleSubcat(sub)"
                                    class="text-xs px-2.5 py-1 rounded-lg border transition-colors"
                                    :class="sub.activa
                                        ? 'border-gray-200 text-gray-500 hover:border-rose-300 hover:text-rose-600 hover:bg-rose-50'
                                        : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50'"
                                    x-text="sub.activa ? 'Marcar obsoleta' : 'Reactivar'"
                                ></button>
                            </div>
                        </template>
                    </div>
                    <div class="px-5 py-3 bg-gray-50 flex justify-end">
                        <button
                            @click="abrirModalSubcat(cat.id)"
                            class="inline-flex items-center gap-1.5 text-xs font-medium text-judicial-600 hover:text-judicial-800 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar subcategoría
                        </button>
                    </div>
                </div>

            </div>
        </template>
    </div>

    <p class="text-xs text-gray-400 mt-4 text-center">Las categorías raíz no son editables — están vinculadas a los equipos técnicos del sistema.</p>
</div>

{{-- ══════════════════════════════════════
     TAB 2 — SLA
══════════════════════════════════════ --}}
<div x-show="tabActiva === 'sla'" style="display:none">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Horario hábil judicial</h3>
            <p class="text-xs text-gray-400 mt-0.5">El cómputo de SLA solo cuenta horas dentro de este rango, de lunes a viernes.</p>
        </div>
        <div class="px-5 py-5">
            <div class="flex flex-wrap items-end gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Inicio de jornada</label>
                    <input type="time" x-model="horarioInicio" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Fin de jornada</label>
                    <input type="time" x-model="horarioFin" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                </div>
                <div class="flex items-center gap-3 pb-0.5">
                    <button
                        @click="guardarHorario()"
                        :disabled="guardandoHorario"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
                    >
                        <template x-if="guardandoHorario">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        </template>
                        <span x-text="guardandoHorario ? 'Guardando…' : 'Guardar horario'"></span>
                    </button>
                    <span x-show="horarioGuardado" x-transition class="text-sm text-emerald-600 font-medium flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardado
                    </span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">
                Horario actual: <span class="font-medium text-gray-600" x-text="horarioInicio"></span> a <span class="font-medium text-gray-600" x-text="horarioFin"></span>
                — <span class="font-medium text-gray-600" x-text="horasHabiles"></span> horas hábiles por día.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Objetivos de SLA por prioridad</h3>
            <p class="text-xs text-gray-400 mt-0.5">Tiempo máximo de respuesta inicial y de resolución. Hacé clic en Editar para modificar.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Prioridad</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Respuesta inicial</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Resolución objetivo</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-28"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="row in slaRows" :key="row.prioridad">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold" :class="row.colorBadge">
                                    <span class="w-2 h-2 rounded-full" :class="row.colorDot"></span>
                                    <span x-text="row.prioridad"></span>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <template x-if="!row.editando">
                                    <span class="font-medium text-gray-700" x-text="row.respLabel"></span>
                                </template>
                                <template x-if="row.editando">
                                    <input type="text" x-model="row.tmpResp" class="w-40 px-2.5 py-1.5 text-sm border border-judicial-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500" placeholder="ej: 30 minutos">
                                </template>
                            </td>
                            <td class="px-5 py-3.5">
                                <template x-if="!row.editando">
                                    <span class="font-medium text-gray-700" x-text="row.resLabel"></span>
                                </template>
                                <template x-if="row.editando">
                                    <input type="text" x-model="row.tmpRes" class="w-44 px-2.5 py-1.5 text-sm border border-judicial-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500" placeholder="ej: 4 horas hábiles">
                                </template>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <template x-if="!row.editando">
                                    <button @click="editarSLA(row)" class="text-xs font-medium text-judicial-600 hover:text-judicial-800 hover:underline">Editar</button>
                                </template>
                                <template x-if="row.editando">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="guardarSLA(row)" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Guardar</button>
                                        <button @click="cancelarSLA(row)" class="text-xs text-gray-400 hover:text-gray-600">Cancelar</button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 bg-amber-50 border-t border-amber-100 text-xs text-amber-700 flex items-start gap-2">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Los cambios de SLA aplican a tickets nuevos. Los tickets existentes mantienen el SLA con el que fueron creados.
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════
     TAB 3 — UNIDADES ORGANIZATIVAS
══════════════════════════════════════ --}}
<div x-show="tabActiva === 'unidades'" style="display:none">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Unidades organizativas</h3>
            <button
                @click="abrirCrearUnidad()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-judicial-700 hover:bg-judicial-800 text-white text-xs font-semibold rounded-lg transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva unidad
            </button>
        </div>

        <div class="flex flex-wrap gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50">
            <div class="relative flex-1 min-w-[180px]">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" x-model="busqUnidad" placeholder="Buscar…" class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
            </div>
            <select x-model="filtroTipoUnidad" class="py-1.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 text-gray-700">
                <option value="">Todos los tipos</option>
                <option value="juzgado">Juzgado</option>
                <option value="sala">Sala</option>
                <option value="area_tecnica">Área técnica</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nombre</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-if="unidadesFiltradas.length === 0">
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">No se encontraron unidades.</td>
                        </tr>
                    </template>
                    <template x-for="u in unidadesFiltradas" :key="u.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-800" x-text="u.nombre"></td>
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium" :class="colorTipo(u.tipo)" x-text="labelTipo(u.tipo)"></span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium" :class="u.activa ? 'text-emerald-600' : 'text-gray-400'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="u.activa ? 'bg-emerald-500' : 'bg-gray-300'"></span>
                                    <span x-text="u.activa ? 'Activa' : 'Inactiva'"></span>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="abrirEditarUnidad(u)" class="text-xs font-medium text-judicial-600 hover:text-judicial-800 hover:underline">Editar</button>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        @click="toggleUnidad(u)"
                                        class="text-xs font-medium"
                                        :class="u.activa ? 'text-rose-500 hover:text-rose-700' : 'text-emerald-600 hover:text-emerald-800'"
                                        x-text="u.activa ? 'Desactivar' : 'Activar'"
                                    ></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 text-xs text-gray-400">
            <span x-text="unidadesFiltradas.length + ' de ' + unidades.length + ' unidades'"></span>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     TAB 4 — SISTEMA
══════════════════════════════════════ --}}
<div x-show="tabActiva === 'sistema'" style="display:none">
    <div class="space-y-6 max-w-2xl">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Datos institucionales</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre del organismo</label>
                    <input type="text" x-model="sistema.nombreOrganismo" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre del sistema</label>
                    <input type="text" x-model="sistema.nombreSistema" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email de soporte visible al usuario</label>
                    <input type="email" x-model="sistema.emailSoporte" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <button
                @click="mostrarSmtp = !mostrarSmtp"
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors text-left"
                :class="mostrarSmtp ? 'border-b border-gray-100' : ''"
            >
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Configuración SMTP</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Servidor de correo para notificaciones salientes.</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" :class="mostrarSmtp ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="mostrarSmtp" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Host SMTP</label>
                            <input type="text" x-model="sistema.smtpHost" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Puerto</label>
                            <input type="number" x-model="sistema.smtpPuerto" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Usuario SMTP</label>
                        <input type="email" x-model="sistema.smtpUsuario" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                        La contraseña SMTP se configura directamente en el archivo <code class="font-mono">.env</code> del servidor por razones de seguridad.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Comportamiento del sistema</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Timeout de sesión (minutos)</label>
                        <input type="number" x-model="sistema.timeoutSesion" min="15" max="480" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Días hábiles para auto-cierre</label>
                        <input type="number" x-model="sistema.diasAutoClose" min="1" max="30" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                        <p class="text-xs text-gray-400 mt-1">Días sin conformidad antes del cierre automático.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tamaño máx. por adjunto (MB)</label>
                        <input type="number" x-model="sistema.maxAdjuntosMb" min="1" max="50" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cantidad máx. de adjuntos por ticket</label>
                        <input type="number" x-model="sistema.maxAdjuntosQty" min="1" max="50" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Estrategia de asignación de tickets</label>
                    <select x-model="sistema.estrategiaAsignacion" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 text-gray-700">
                        <option value="cola">Cola compartida — cualquier resolutor del equipo puede tomarlo</option>
                        <option value="roundrobin">Round-robin — rotación automática entre resolutores activos</option>
                        <option value="carga">Por carga — al resolutor con menos tickets activos</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <span x-show="sistemaGuardado" x-transition class="flex items-center gap-2 text-emerald-600 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Configuración guardada.
            </span>
            <button
                @click="guardarSistema()"
                :disabled="guardandoSistema"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors"
            >
                <template x-if="guardandoSistema">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                </template>
                <span x-text="guardandoSistema ? 'Guardando…' : 'Guardar configuración'"></span>
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL — NUEVA SUBCATEGORÍA
══════════════════════════════════════ --}}
<div x-show="modalSubcat"
     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 flex items-center justify-center p-4"
     style="display:none"
>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="modalSubcat = false"></div>
    <div
        x-show="modalSubcat"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10"
        @click.stop
    >
        <h3 class="font-semibold text-gray-800 mb-4">Nueva subcategoría</h3>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre <span class="text-rose-500">*</span></label>
        <input
            type="text"
            x-model="subcatNombre"
            x-ref="inputSubcat"
            @keydown.enter="guardarSubcat()"
            placeholder="Ej: Seguridad informática"
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent mb-5"
        >
        <div class="flex justify-end gap-3">
            <button @click="modalSubcat = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">Cancelar</button>
            <button
                @click="guardarSubcat()"
                :disabled="guardandoSubcat || !subcatNombre.trim()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
            >
                <template x-if="guardandoSubcat">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                </template>
                <span x-text="guardandoSubcat ? 'Guardando…' : 'Agregar'"></span>
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL — CREAR / EDITAR UNIDAD
══════════════════════════════════════ --}}
<div x-show="modalUnidad"
     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 flex items-center justify-center p-4"
     style="display:none"
>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="modalUnidad = false"></div>
    <div
        x-show="modalUnidad"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10"
        @click.stop
    >
        <h3 class="font-semibold text-gray-800 mb-4" x-text="modoEditarUnidad ? 'Editar unidad' : 'Nueva unidad organizativa'"></h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre <span class="text-rose-500">*</span></label>
                <input type="text" x-model="unidadForm.nombre" placeholder="Ej: Juzgado Civil N° 4" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tipo <span class="text-rose-500">*</span></label>
                <select x-model="unidadForm.tipo" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 text-gray-700">
                    <option value="">Seleccioná un tipo</option>
                    <option value="juzgado">Juzgado</option>
                    <option value="sala">Sala</option>
                    <option value="area_tecnica">Área técnica</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button @click="modalUnidad = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">Cancelar</button>
            <button
                @click="guardarUnidad()"
                :disabled="guardandoUnidad || !unidadForm.nombre.trim() || !unidadForm.tipo"
                class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
            >
                <template x-if="guardandoUnidad">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                </template>
                <span x-text="guardandoUnidad ? 'Guardando…' : (modoEditarUnidad ? 'Guardar cambios' : 'Crear unidad')"></span>
            </button>
        </div>
    </div>
</div>

</div>
@endsection
