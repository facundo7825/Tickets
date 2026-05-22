@extends('layouts.app')

@section('title', 'Gestión de usuarios')
@section('page-title', 'Gestión de usuarios')

@section('content')
<div
    x-data="{
        /* ── Filtros ── */
        busqueda: '',
        filtroRol: '',
        filtroEstado: '',
        filtroUnidad: '',

        /* ── Modal crear/editar ── */
        modalAbierto: false,
        modoEdicion: false,
        enviando: false,
        usuario: {
            id: null,
            name: '',
            email: '',
            password: '',
            rol: '',
            unidad: '',
            activo: true,
        },

        /* ── Confirmación desactivar ── */
        confirmandoToggle: null,   // id del usuario o null
        procesandoToggle: false,

        /* ── Notificación flash ── */
        flash: null,

        /* ── Datos mock ── */
        usuarios: [
            { id: 1,  name: 'Administrador del Sistema',  email: 'admin@pjudicial.gob.ar',        rol: 'Administrador',       unidad: 'Dirección de Sistemas',      activo: true,  ultimoLogin: 'Hoy, 09:14' },
            { id: 2,  name: 'Dr. Roberto Casas',           email: 'rcasas@pjudicial.gob.ar',        rol: 'Juez',                unidad: 'Juzgado Civil N° 3',         activo: true,  ultimoLogin: 'Ayer, 16:42' },
            { id: 3,  name: 'Laura Giménez',               email: 'lgimenez@pjudicial.gob.ar',      rol: 'Redes',               unidad: 'Área de Redes y Conectividad',activo: true,  ultimoLogin: 'Hoy, 08:55' },
            { id: 4,  name: 'Carlos Méndez',               email: 'cmendez@pjudicial.gob.ar',       rol: 'Soporte Técnico',     unidad: 'Mesa de Ayuda',              activo: true,  ultimoLogin: 'Hoy, 10:03' },
            { id: 5,  name: 'Dra. María Fernández',        email: 'mfernandez@pjudicial.gob.ar',    rol: 'Ministra',            unidad: 'Sala I – Cámara Civil',      activo: true,  ultimoLogin: 'Hace 3 días' },
            { id: 6,  name: 'Pablo Rodríguez',             email: 'prodriguez@pjudicial.gob.ar',    rol: 'Desarrollador',       unidad: 'Área de Desarrollo',         activo: true,  ultimoLogin: 'Ayer, 11:20' },
            { id: 7,  name: 'Secretaria Ana Torres',       email: 'atorres@pjudicial.gob.ar',       rol: 'Secretario',          unidad: 'Juzgado Civil N° 3',         activo: true,  ultimoLogin: 'Hace 5 horas' },
            { id: 8,  name: 'Ing. Sergio Ruiz',            email: 'sruiz@pjudicial.gob.ar',         rol: 'Coordinador',         unidad: 'Dirección de Sistemas',      activo: true,  ultimoLogin: 'Hoy, 07:48' },
            { id: 9,  name: 'Dr. Jorge Medina',            email: 'jmedina@pjudicial.gob.ar',       rol: 'Juez',                unidad: 'Juzgado Laboral N° 2',       activo: false, ultimoLogin: 'Hace 2 meses' },
            { id: 10, name: 'Valeria Sosa',                email: 'vsosa@pjudicial.gob.ar',         rol: 'Soporte Técnico',     unidad: 'Mesa de Ayuda',              activo: false, ultimoLogin: 'Hace 1 mes' },
            { id: 11, name: 'Juan Pérez',                  email: 'jperez@pjudicial.gob.ar',        rol: 'Secretario',          unidad: 'Juzgado Penal N° 1',         activo: true,  ultimoLogin: 'Nunca' },
            { id: 12, name: 'Lic. Claudia Vega',           email: 'cvega@pjudicial.gob.ar',         rol: 'Desarrollador',       unidad: 'Área de Desarrollo',         activo: true,  ultimoLogin: 'Ayer, 14:00' },
        ],

        get usuariosFiltrados() {
            return this.usuarios.filter(u => {
                const q = this.busqueda.toLowerCase();
                const matchBusq = !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
                const matchRol   = !this.filtroRol    || u.rol === this.filtroRol;
                const matchEst   = !this.filtroEstado || (this.filtroEstado === 'activo' ? u.activo : !u.activo);
                const matchUn    = !this.filtroUnidad || u.unidad.includes(this.filtroUnidad);
                return matchBusq && matchRol && matchEst && matchUn;
            });
        },

        get totalActivos()   { return this.usuarios.filter(u => u.activo).length; },
        get totalInactivos() { return this.usuarios.filter(u => !u.activo).length; },

        iniciales(name) {
            return name.split(' ').filter(p => p.length > 0).slice(0, 2).map(p => p[0].toUpperCase()).join('');
        },

        colorAvatar(name) {
            const paleta = [
                'bg-judicial-700 text-white',
                'bg-dorado-500 text-white',
                'bg-emerald-600 text-white',
                'bg-violet-600 text-white',
                'bg-rose-600 text-white',
                'bg-sky-600 text-white',
                'bg-orange-600 text-white',
                'bg-teal-600 text-white',
            ];
            let hash = 0;
            for (let i = 0; i < name.length; i++) hash += name.charCodeAt(i);
            return paleta[hash % paleta.length];
        },

        colorRol(rol) {
            const map = {
                'Administrador': 'bg-judicial-100 text-judicial-800 ring-judicial-300',
                'Coordinador':   'bg-violet-100 text-violet-800 ring-violet-300',
                'Ministra':      'bg-dorado-500 text-white ring-dorado-400',
                'Ministro':      'bg-dorado-500 text-white ring-dorado-400',
                'Juez':          'bg-blue-100 text-blue-800 ring-blue-300',
                'Secretario':    'bg-sky-100 text-sky-800 ring-sky-300',
                'Soporte Técnico': 'bg-emerald-100 text-emerald-800 ring-emerald-300',
                'Desarrollador': 'bg-purple-100 text-purple-800 ring-purple-300',
                'Redes':         'bg-orange-100 text-orange-800 ring-orange-300',
            };
            return map[rol] || 'bg-gray-100 text-gray-700 ring-gray-300';
        },

        abrirCrear() {
            this.usuario = { id: null, name: '', email: '', password: '', rol: '', unidad: '', activo: true };
            this.modoEdicion = false;
            this.modalAbierto = true;
        },

        abrirEditar(u) {
            this.usuario = { ...u, password: '' };
            this.modoEdicion = true;
            this.modalAbierto = true;
        },

        guardarUsuario() {
            if (!this.usuario.name || !this.usuario.email || !this.usuario.rol || !this.usuario.unidad) return;
            if (!this.modoEdicion && !this.usuario.password) return;
            this.enviando = true;
            setTimeout(() => {
                if (this.modoEdicion) {
                    const idx = this.usuarios.findIndex(u => u.id === this.usuario.id);
                    if (idx !== -1) this.usuarios[idx] = { ...this.usuarios[idx], ...this.usuario };
                    this.mostrarFlash('Usuario actualizado correctamente.');
                } else {
                    const nuevo = { ...this.usuario, id: Date.now(), ultimoLogin: 'Nunca' };
                    this.usuarios.unshift(nuevo);
                    this.mostrarFlash('Usuario creado correctamente.');
                }
                this.enviando = false;
                this.modalAbierto = false;
            }, 800);
        },

        pedirToggle(u) {
            this.confirmandoToggle = u.id;
        },

        confirmarToggle() {
            this.procesandoToggle = true;
            setTimeout(() => {
                const u = this.usuarios.find(u => u.id === this.confirmandoToggle);
                if (u) {
                    u.activo = !u.activo;
                    this.mostrarFlash(u.activo ? `${u.name} fue activado.` : `${u.name} fue desactivado.`);
                }
                this.procesandoToggle = false;
                this.confirmandoToggle = null;
            }, 600);
        },

        mostrarFlash(msg) {
            this.flash = msg;
            setTimeout(() => this.flash = null, 4000);
        },
    }"
    @keydown.escape.window="modalAbierto = false; confirmandoToggle = null"
>

{{-- ===== NOTIFICACIÓN FLASH ===== --}}
<div
    x-show="flash !== null"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
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

{{-- ===== ENCABEZADO + MÉTRICAS ===== --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500 mt-0.5">Administrá los usuarios del sistema, sus roles y unidades organizativas.</p>
    </div>
    <button
        @click="abrirCrear()"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-judicial-700 hover:bg-judicial-800 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors whitespace-nowrap"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo usuario
    </button>
</div>

{{-- Métricas rápidas --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total usuarios</p>
        <p class="text-2xl font-bold text-gray-800 mt-1" x-text="usuarios.length"></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Activos</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1" x-text="totalActivos"></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inactivos</p>
        <p class="text-2xl font-bold text-gray-400 mt-1" x-text="totalInactivos"></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Resultado de búsqueda</p>
        <p class="text-2xl font-bold text-judicial-700 mt-1" x-text="usuariosFiltrados.length"></p>
    </div>
</div>

{{-- ===== FILTROS ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

        {{-- Búsqueda --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input
                type="text"
                x-model="busqueda"
                placeholder="Buscar por nombre o email…"
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
            >
        </div>

        {{-- Rol --}}
        <select x-model="filtroRol" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700">
            <option value="">Todos los roles</option>
            <option>Administrador</option>
            <option>Coordinador</option>
            <option>Ministro</option>
            <option>Ministra</option>
            <option>Juez</option>
            <option>Secretario</option>
            <option>Soporte Técnico</option>
            <option>Desarrollador</option>
            <option>Redes</option>
        </select>

        {{-- Estado --}}
        <select x-model="filtroEstado" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700">
            <option value="">Todos los estados</option>
            <option value="activo">Activos</option>
            <option value="inactivo">Inactivos</option>
        </select>

        {{-- Limpiar --}}
        <button
            @click="busqueda=''; filtroRol=''; filtroEstado=''; filtroUnidad=''"
            class="flex items-center justify-center gap-1.5 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Limpiar filtros
        </button>
    </div>
</div>

{{-- ===== TABLA DE USUARIOS ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Encabezado tabla --}}
    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            <span x-text="usuariosFiltrados.length"></span> usuario<span x-show="usuariosFiltrados.length !== 1">s</span>
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left border-b border-gray-100">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Usuario</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Rol</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Unidad organizativa</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden xl:table-cell">Último acceso</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">

                {{-- Estado vacío --}}
                <template x-if="usuariosFiltrados.length === 0">
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <svg class="mx-auto w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m0 0a4 4 0 118 0m-8 0a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No se encontraron usuarios</p>
                            <p class="text-gray-400 text-xs mt-1">Probá cambiando los filtros de búsqueda.</p>
                        </td>
                    </tr>
                </template>

                <template x-for="u in usuariosFiltrados" :key="u.id">
                    <tr class="hover:bg-gray-50 transition-colors group">

                        {{-- Avatar + nombre + email --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold" :class="colorAvatar(u.name)">
                                    <span x-text="iniciales(u.name)"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate" x-text="u.name"></p>
                                    <p class="text-xs text-gray-400 truncate" x-text="u.email"></p>
                                </div>
                            </div>
                        </td>

                        {{-- Rol --}}
                        <td class="px-5 py-3.5 hidden md:table-cell">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset"
                                :class="colorRol(u.rol)"
                                x-text="u.rol"
                            ></span>
                        </td>

                        {{-- Unidad --}}
                        <td class="px-5 py-3.5 text-gray-600 hidden lg:table-cell">
                            <span class="truncate block max-w-[200px]" x-text="u.unidad"></span>
                        </td>

                        {{-- Último acceso --}}
                        <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap hidden xl:table-cell" x-text="u.ultimoLogin"></td>

                        {{-- Estado --}}
                        <td class="px-5 py-3.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="u.activo ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                            >
                                <span class="w-1.5 h-1.5 rounded-full" :class="u.activo ? 'bg-emerald-500' : 'bg-gray-400'"></span>
                                <span x-text="u.activo ? 'Activo' : 'Inactivo'"></span>
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">

                                {{-- Editar --}}
                                <button
                                    @click="abrirEditar(u)"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-judicial-700 hover:bg-judicial-50 transition-colors"
                                    title="Editar usuario"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Activar/Desactivar --}}
                                <button
                                    @click="pedirToggle(u)"
                                    class="p-1.5 rounded-lg transition-colors"
                                    :class="u.activo
                                        ? 'text-gray-400 hover:text-rose-600 hover:bg-rose-50'
                                        : 'text-gray-400 hover:text-emerald-600 hover:bg-emerald-50'"
                                    :title="u.activo ? 'Desactivar usuario' : 'Activar usuario'"
                                >
                                    <template x-if="u.activo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </template>
                                    <template x-if="!u.activo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </template>
                                </button>

                            </div>
                        </td>

                    </tr>
                </template>

            </tbody>
        </table>
    </div>

    {{-- Footer tabla --}}
    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
        <span>Mostrando <span class="font-medium text-gray-600" x-text="usuariosFiltrados.length"></span> de <span class="font-medium text-gray-600" x-text="usuarios.length"></span> usuarios</span>
        <span class="hidden sm:block">Los cambios de estado quedan registrados en la auditoría del sistema.</span>
    </div>
</div>

{{-- ===== MODAL CREAR / EDITAR ===== --}}
<div
    x-show="modalAbierto"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 flex items-center justify-center p-4"
    style="display:none"
>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="modalAbierto = false"></div>

    {{-- Panel --}}
    <div
        x-show="modalAbierto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden z-10"
        @click.stop
    >
        {{-- Header modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-judicial-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-judicial-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-800 text-sm" x-text="modoEdicion ? 'Editar usuario' : 'Nuevo usuario'"></h2>
            </div>
            <button @click="modalAbierto = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Cuerpo modal --}}
        <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">

            {{-- Nombre completo --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre completo <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    x-model="usuario.name"
                    placeholder="Ej: Dr. Roberto Casas"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                >
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Correo electrónico institucional <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    x-model="usuario.email"
                    placeholder="usuario@pjudicial.gob.ar"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                >
            </div>

            {{-- Contraseña --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Contraseña
                    <span x-show="!modoEdicion" class="text-rose-500">*</span>
                    <span x-show="modoEdicion" class="text-gray-400 font-normal">(dejar en blanco para mantener)</span>
                </label>
                <input
                    type="password"
                    x-model="usuario.password"
                    placeholder="Mínimo 10 caracteres"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                    autocomplete="new-password"
                >
                <p class="text-xs text-gray-400 mt-1">Debe incluir mayúsculas, minúsculas, números y símbolos.</p>
            </div>

            {{-- Rol --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rol <span class="text-rose-500">*</span></label>
                <select
                    x-model="usuario.rol"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700"
                >
                    <option value="">Seleccioná un rol</option>
                    <optgroup label="Solicitantes">
                        <option>Ministro</option>
                        <option>Ministra</option>
                        <option>Juez</option>
                        <option>Secretario</option>
                    </optgroup>
                    <optgroup label="Resolutores">
                        <option>Soporte Técnico</option>
                        <option>Desarrollador</option>
                        <option>Redes</option>
                    </optgroup>
                    <optgroup label="Administrativos">
                        <option>Coordinador</option>
                        <option>Administrador</option>
                    </optgroup>
                </select>
            </div>

            {{-- Unidad organizativa --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Unidad organizativa <span class="text-rose-500">*</span></label>
                <select
                    x-model="usuario.unidad"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent text-gray-700"
                >
                    <option value="">Seleccioná una unidad</option>
                    <optgroup label="Juzgados">
                        <option>Juzgado Civil N° 1</option>
                        <option>Juzgado Civil N° 2</option>
                        <option>Juzgado Civil N° 3</option>
                        <option>Juzgado Penal N° 1</option>
                        <option>Juzgado Penal N° 2</option>
                        <option>Juzgado Laboral N° 1</option>
                        <option>Juzgado Laboral N° 2</option>
                        <option>Sala I – Cámara Civil</option>
                        <option>Sala II – Cámara Civil</option>
                    </optgroup>
                    <optgroup label="Área técnica">
                        <option>Mesa de Ayuda</option>
                        <option>Área de Desarrollo</option>
                        <option>Área de Redes y Conectividad</option>
                        <option>Dirección de Sistemas</option>
                    </optgroup>
                </select>
            </div>

            {{-- Activo (solo en edición) --}}
            <div x-show="modoEdicion" class="flex items-center justify-between py-1">
                <div>
                    <p class="text-xs font-semibold text-gray-600">Estado de la cuenta</p>
                    <p class="text-xs text-gray-400 mt-0.5">Los usuarios inactivos no pueden iniciar sesión.</p>
                </div>
                <button
                    type="button"
                    @click="usuario.activo = !usuario.activo"
                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                    :class="usuario.activo ? 'bg-emerald-500' : 'bg-gray-200'"
                    role="switch"
                    :aria-checked="usuario.activo"
                >
                    <span
                        class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        :class="usuario.activo ? 'translate-x-4' : 'translate-x-0'"
                    ></span>
                </button>
            </div>

        </div>

        {{-- Footer modal --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
            <button
                @click="modalAbierto = false"
                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
            >
                Cancelar
            </button>
            <button
                @click="guardarUsuario()"
                :disabled="enviando"
                class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
            >
                <template x-if="enviando">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                </template>
                <span x-text="enviando ? 'Guardando…' : (modoEdicion ? 'Guardar cambios' : 'Crear usuario')"></span>
            </button>
        </div>

    </div>
</div>

{{-- ===== MODAL CONFIRMAR TOGGLE ===== --}}
<div
    x-show="confirmandoToggle !== null"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none"
>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="confirmandoToggle = null"></div>

    <div
        x-show="confirmandoToggle !== null"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full z-10"
        @click.stop
    >
        <template x-if="confirmandoToggle !== null">
            <div>
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                        :class="usuarios.find(u => u.id === confirmandoToggle)?.activo ? 'bg-rose-100' : 'bg-emerald-100'"
                    >
                        <template x-if="usuarios.find(u => u.id === confirmandoToggle)?.activo">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </template>
                        <template x-if="!usuarios.find(u => u.id === confirmandoToggle)?.activo">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm" x-text="usuarios.find(u => u.id === confirmandoToggle)?.activo ? 'Desactivar usuario' : 'Activar usuario'"></h3>
                        <p class="text-sm text-gray-500 mt-1">
                            ¿Confirmás que querés
                            <span x-text="usuarios.find(u => u.id === confirmandoToggle)?.activo ? 'desactivar' : 'activar'"></span>
                            a <strong x-text="usuarios.find(u => u.id === confirmandoToggle)?.name"></strong>?
                        </p>
                        <p x-show="usuarios.find(u => u.id === confirmandoToggle)?.activo" class="text-xs text-rose-600 mt-2">
                            El usuario no podrá iniciar sesión hasta que sea reactivado.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button
                        @click="confirmandoToggle = null"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="confirmarToggle()"
                        :disabled="procesandoToggle"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg text-white transition-colors disabled:opacity-60"
                        :class="usuarios.find(u => u.id === confirmandoToggle)?.activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                    >
                        <template x-if="procesandoToggle">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                        </template>
                        <span x-text="procesandoToggle ? 'Procesando…' : (usuarios.find(u => u.id === confirmandoToggle)?.activo ? 'Sí, desactivar' : 'Sí, activar')"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

</div>
@endsection
