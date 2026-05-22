@extends('layouts.app')

@section('title', 'Mi perfil')
@section('page-title', 'Mi perfil')

@section('content')
<div
    x-data="{
        /* ── Tabs ── */
        tabActiva: 'datos',

        /* ── Datos personales ── */
        nombre: '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
        guardandoDatos: false,
        datosGuardados: false,

        /* ── Cambio de contraseña ── */
        passwordActual: '',
        passwordNuevo: '',
        passwordConfirm: '',
        mostrarActual: false,
        mostrarNuevo: false,
        mostrarConfirm: false,
        guardandoPassword: false,
        passwordGuardado: false,
        errorPassword: '',

        get passwordSeguridad() {
            const p = this.passwordNuevo;
            if (!p) return 0;
            let score = 0;
            if (p.length >= 10) score++;
            if (/[A-Z]/.test(p)) score++;
            if (/[a-z]/.test(p)) score++;
            if (/[0-9]/.test(p)) score++;
            if (/[^A-Za-z0-9]/.test(p)) score++;
            return score;
        },
        get passwordSeguridadLabel() {
            const s = this.passwordSeguridad;
            if (s <= 1) return 'Muy débil';
            if (s === 2) return 'Débil';
            if (s === 3) return 'Aceptable';
            if (s === 4) return 'Buena';
            return 'Muy segura';
        },
        get passwordSeguridadColor() {
            const s = this.passwordSeguridad;
            if (s <= 1) return 'bg-rose-500';
            if (s === 2) return 'bg-orange-400';
            if (s === 3) return 'bg-yellow-400';
            if (s === 4) return 'bg-emerald-400';
            return 'bg-emerald-600';
        },
        get passwordSeguridadTexto() {
            const s = this.passwordSeguridad;
            if (s <= 1) return 'text-rose-600';
            if (s === 2) return 'text-orange-600';
            if (s === 3) return 'text-yellow-600';
            return 'text-emerald-600';
        },

        /* ── Notificaciones ── */
        notif: {
            emailCambioEstado: true,
            emailComentarioPublico: true,
            emailAsignacion: true,
            emailSlaVencido: false,
            inAppCambioEstado: true,
            inAppComentarioPublico: true,
            inAppAsignacion: true,
            inAppSlaVencido: true,
        },
        guardandoNotif: false,
        notifGuardadas: false,

        /* ── Sesiones ── */
        sesiones: [
            { dispositivo: 'Chrome 124 — Windows 11',   ubicacion: 'Buenos Aires, Argentina', ip: '192.168.1.42',  activa: true,  ultima: 'Ahora' },
            { dispositivo: 'Firefox 125 — Windows 10',  ubicacion: 'Buenos Aires, Argentina', ip: '192.168.1.38',  activa: false, ultima: 'Ayer, 18:30' },
            { dispositivo: 'Edge 123 — Windows 11',     ubicacion: 'Buenos Aires, Argentina', ip: '192.168.1.55',  activa: false, ultima: 'Hace 5 días' },
        ],
        cerrandoSesiones: false,

        /* ── Helpers ── */
        iniciales(name) {
            return name.split(' ').filter(p => p.length > 0).slice(0,2).map(p => p[0].toUpperCase()).join('');
        },

        guardarDatos() {
            if (!this.nombre.trim() || !this.email.trim()) return;
            this.guardandoDatos = true;
            setTimeout(() => {
                this.guardandoDatos = false;
                this.datosGuardados = true;
                setTimeout(() => this.datosGuardados = false, 3000);
            }, 800);
        },

        guardarPassword() {
            this.errorPassword = '';
            if (!this.passwordActual) { this.errorPassword = 'Ingresá tu contraseña actual.'; return; }
            if (this.passwordNuevo.length < 10) { this.errorPassword = 'La nueva contraseña debe tener al menos 10 caracteres.'; return; }
            if (this.passwordSeguridad < 4) { this.errorPassword = 'La contraseña no cumple los requisitos de seguridad.'; return; }
            if (this.passwordNuevo !== this.passwordConfirm) { this.errorPassword = 'Las contraseñas no coinciden.'; return; }
            this.guardandoPassword = true;
            setTimeout(() => {
                this.guardandoPassword = false;
                this.passwordGuardado = true;
                this.passwordActual = ''; this.passwordNuevo = ''; this.passwordConfirm = '';
                setTimeout(() => this.passwordGuardado = false, 4000);
            }, 900);
        },

        guardarNotif() {
            this.guardandoNotif = true;
            setTimeout(() => {
                this.guardandoNotif = false;
                this.notifGuardadas = true;
                setTimeout(() => this.notifGuardadas = false, 3000);
            }, 700);
        },

        cerrarOtrasSesiones() {
            this.cerrandoSesiones = true;
            setTimeout(() => {
                this.sesiones = this.sesiones.filter(s => s.activa);
                this.cerrandoSesiones = false;
            }, 800);
        },
    }"
>

<div class="max-w-4xl mx-auto">

    {{-- ===== ENCABEZADO DE PERFIL ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        {{-- Banner --}}
        <div class="h-24 bg-gradient-to-r from-judicial-800 via-judicial-700 to-judicial-600 relative">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.1) 10px, rgba(255,255,255,.1) 11px)">
            </div>
        </div>
        {{-- Avatar + datos --}}
        <div class="px-6 pb-5">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-8">
                <div class="flex items-end gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-judicial-800 border-4 border-white shadow-lg flex items-center justify-center text-xl font-bold text-white shrink-0"
                         x-text="iniciales(nombre)">
                    </div>
                    <div class="pb-1">
                        <h2 class="text-lg font-bold text-gray-800" x-text="nombre"></h2>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-judicial-100 text-judicial-800 ring-1 ring-judicial-300">
                                Administrador
                            </span>
                            <span class="text-xs text-gray-400">·</span>
                            <span class="text-xs text-gray-500">Dirección de Sistemas</span>
                        </div>
                    </div>
                </div>
                <div class="pb-1 flex items-center gap-4 text-xs text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Último acceso: hoy, 09:14
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Cuenta activa
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABS ===== --}}
    <div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-6 overflow-x-auto">
        <button @click="tabActiva = 'datos'"
            class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
            :class="tabActiva === 'datos' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            Datos personales
        </button>
        <button @click="tabActiva = 'password'"
            class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
            :class="tabActiva === 'password' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            Contraseña
        </button>
        <button @click="tabActiva = 'notificaciones'"
            class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
            :class="tabActiva === 'notificaciones' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            Notificaciones
        </button>
        <button @click="tabActiva = 'sesiones'"
            class="flex-1 min-w-max px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
            :class="tabActiva === 'sesiones' ? 'bg-white text-judicial-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            Sesiones activas
        </button>
    </div>

    {{-- ===== TAB: DATOS PERSONALES ===== --}}
    <div x-show="tabActiva === 'datos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Información personal</h3>
                <p class="text-xs text-gray-400 mt-0.5">Actualizá tu nombre y correo electrónico institucional.</p>
            </div>
            <div class="px-6 py-5 space-y-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre completo</label>
                        <input
                            type="text"
                            x-model="nombre"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Correo electrónico</label>
                        <input
                            type="email"
                            x-model="email"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                        >
                    </div>
                </div>

                {{-- Campos de solo lectura --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rol en el sistema</label>
                        <div class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                            <span class="text-sm text-gray-500">Administrador</span>
                            <span class="ml-auto text-xs text-gray-400 italic">Solo el admin puede modificar este campo</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Unidad organizativa</label>
                        <div class="flex items-center px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                            <span class="text-sm text-gray-500">Dirección de Sistemas</span>
                        </div>
                    </div>
                </div>

                {{-- Info adicional --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div class="bg-gray-50 rounded-lg px-4 py-3">
                        <p class="text-xs font-medium text-gray-500 mb-0.5">Tickets creados</p>
                        <p class="text-xl font-bold text-gray-800">0</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg px-4 py-3">
                        <p class="text-xs font-medium text-gray-500 mb-0.5">Tickets resueltos</p>
                        <p class="text-xl font-bold text-gray-800">0</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg px-4 py-3">
                        <p class="text-xs font-medium text-gray-500 mb-0.5">Miembro desde</p>
                        <p class="text-sm font-bold text-gray-800">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <div x-show="datosGuardados" x-transition class="flex items-center gap-2 text-emerald-600 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Datos actualizados correctamente.
                </div>
                <div x-show="!datosGuardados"></div>
                <button
                    @click="guardarDatos()"
                    :disabled="guardandoDatos"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
                >
                    <template x-if="guardandoDatos">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </template>
                    <span x-text="guardandoDatos ? 'Guardando…' : 'Guardar cambios'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TAB: CONTRASEÑA ===== --}}
    <div x-show="tabActiva === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Cambiar contraseña</h3>
                <p class="text-xs text-gray-400 mt-0.5">La nueva contraseña debe tener al menos 10 caracteres con mayúsculas, minúsculas, números y símbolos.</p>
            </div>
            <div class="px-6 py-5 space-y-4 max-w-md">

                {{-- Contraseña actual --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Contraseña actual <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input
                            :type="mostrarActual ? 'text' : 'password'"
                            x-model="passwordActual"
                            placeholder="Tu contraseña actual"
                            class="w-full pr-10 px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                        >
                        <button type="button" @click="mostrarActual = !mostrarActual" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!mostrarActual" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="mostrarActual" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Nueva contraseña --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nueva contraseña <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input
                            :type="mostrarNuevo ? 'text' : 'password'"
                            x-model="passwordNuevo"
                            placeholder="Mínimo 10 caracteres"
                            class="w-full pr-10 px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                        >
                        <button type="button" @click="mostrarNuevo = !mostrarNuevo" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!mostrarNuevo" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="mostrarNuevo" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>

                    {{-- Barra de seguridad --}}
                    <div x-show="passwordNuevo.length > 0" x-transition class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <template x-for="i in 5" :key="i">
                                <div class="flex-1 h-1.5 rounded-full transition-colors duration-300"
                                     :class="i <= passwordSeguridad ? passwordSeguridadColor : 'bg-gray-200'"></div>
                            </template>
                        </div>
                        <p class="text-xs font-medium" :class="passwordSeguridadTexto" x-text="passwordSeguridadLabel"></p>
                    </div>

                    {{-- Requisitos --}}
                    <ul class="mt-2 space-y-1" x-show="passwordNuevo.length > 0" x-transition>
                        <li class="flex items-center gap-1.5 text-xs" :class="passwordNuevo.length >= 10 ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Mínimo 10 caracteres
                        </li>
                        <li class="flex items-center gap-1.5 text-xs" :class="/[A-Z]/.test(passwordNuevo) ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Al menos una mayúscula
                        </li>
                        <li class="flex items-center gap-1.5 text-xs" :class="/[0-9]/.test(passwordNuevo) ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Al menos un número
                        </li>
                        <li class="flex items-center gap-1.5 text-xs" :class="/[^A-Za-z0-9]/.test(passwordNuevo) ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Al menos un símbolo
                        </li>
                    </ul>
                </div>

                {{-- Confirmar --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar nueva contraseña <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input
                            :type="mostrarConfirm ? 'text' : 'password'"
                            x-model="passwordConfirm"
                            placeholder="Repetí la nueva contraseña"
                            class="w-full pr-10 px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:border-transparent"
                            :class="passwordConfirm && passwordNuevo !== passwordConfirm ? 'border-rose-400 ring-1 ring-rose-400' : ''"
                        >
                        <button type="button" @click="mostrarConfirm = !mostrarConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!mostrarConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="mostrarConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <p x-show="passwordConfirm && passwordNuevo !== passwordConfirm" class="text-xs text-rose-600 mt-1">Las contraseñas no coinciden.</p>
                    <p x-show="passwordConfirm && passwordNuevo === passwordConfirm && passwordConfirm.length > 0" class="text-xs text-emerald-600 mt-1">Las contraseñas coinciden.</p>
                </div>

                {{-- Error --}}
                <div x-show="errorPassword" x-transition class="flex items-start gap-2.5 p-3 bg-rose-50 border border-rose-200 rounded-lg text-sm text-rose-700">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="errorPassword"></span>
                </div>

                {{-- Éxito --}}
                <div x-show="passwordGuardado" x-transition class="flex items-center gap-2.5 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Contraseña actualizada correctamente.
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button
                    @click="guardarPassword()"
                    :disabled="guardandoPassword"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
                >
                    <template x-if="guardandoPassword">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </template>
                    <span x-text="guardandoPassword ? 'Actualizando…' : 'Actualizar contraseña'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TAB: NOTIFICACIONES ===== --}}
    <div x-show="tabActiva === 'notificaciones'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Preferencias de notificaciones</h3>
                <p class="text-xs text-gray-400 mt-0.5">Elegí cómo y cuándo querés ser notificado.</p>
            </div>

            <div class="divide-y divide-gray-50">

                {{-- Encabezados de columna --}}
                <div class="grid grid-cols-[1fr_auto_auto] items-center gap-4 px-6 py-3 bg-gray-50">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Evento</span>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide w-16 text-center">Email</span>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide w-16 text-center">In-app</span>
                </div>

                @php
                $notifRows = [
                    ['key' => 'CambioEstado',      'label' => 'Cambio de estado en mis tickets',         'desc' => 'Cuando un ticket tuyo avanza o retrocede en el flujo.'],
                    ['key' => 'ComentarioPublico', 'label' => 'Nuevo comentario público en mis tickets',  'desc' => 'Cuando el área técnica responde con un mensaje visible.'],
                    ['key' => 'Asignacion',        'label' => 'Ticket asignado a mí',                    'desc' => 'Cuando un coordinador te asigna un ticket.'],
                    ['key' => 'SlaVencido',        'label' => 'Alerta de SLA vencido',                   'desc' => 'Cuando un ticket bajo tu responsabilidad supera el SLA.'],
                ];
                @endphp

                @foreach ($notifRows as $row)
                <div class="grid grid-cols-[1fr_auto_auto] items-center gap-4 px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $row['label'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $row['desc'] }}</p>
                    </div>
                    {{-- Toggle email --}}
                    <div class="w-16 flex justify-center">
                        <button
                            type="button"
                            @click="notif.email{{ $row['key'] }} = !notif.email{{ $row['key'] }}"
                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none"
                            :class="notif.email{{ $row['key'] }} ? 'bg-judicial-600' : 'bg-gray-200'"
                            role="switch"
                            :aria-checked="notif.email{{ $row['key'] }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200"
                                  :class="notif.email{{ $row['key'] }} ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>
                    {{-- Toggle in-app --}}
                    <div class="w-16 flex justify-center">
                        <button
                            type="button"
                            @click="notif.inApp{{ $row['key'] }} = !notif.inApp{{ $row['key'] }}"
                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none"
                            :class="notif.inApp{{ $row['key'] }} ? 'bg-judicial-600' : 'bg-gray-200'"
                            role="switch"
                            :aria-checked="notif.inApp{{ $row['key'] }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200"
                                  :class="notif.inApp{{ $row['key'] }} ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>
                @endforeach

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <div x-show="notifGuardadas" x-transition class="flex items-center gap-2 text-emerald-600 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Preferencias guardadas.
                </div>
                <div x-show="!notifGuardadas"></div>
                <button
                    @click="guardarNotif()"
                    :disabled="guardandoNotif"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-judicial-700 hover:bg-judicial-800 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors"
                >
                    <template x-if="guardandoNotif">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </template>
                    <span x-text="guardandoNotif ? 'Guardando…' : 'Guardar preferencias'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TAB: SESIONES ===== --}}
    <div x-show="tabActiva === 'sesiones'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Sesiones activas</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Dispositivos desde los que iniciaste sesión recientemente.</p>
                </div>
                <button
                    x-show="sesiones.length > 1"
                    @click="cerrarOtrasSesiones()"
                    :disabled="cerrandoSesiones"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 disabled:opacity-60 transition-colors"
                >
                    <template x-if="cerrandoSesiones">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </template>
                    <span x-text="cerrandoSesiones ? 'Cerrando…' : 'Cerrar otras sesiones'"></span>
                </button>
            </div>

            <div class="divide-y divide-gray-50">
                <template x-for="(s, i) in sesiones" :key="i">
                    <div class="px-6 py-4 flex items-center gap-4">
                        {{-- Ícono dispositivo --}}
                        <div class="w-10 h-10 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-medium text-gray-800" x-text="s.dispositivo"></p>
                                <template x-if="s.activa">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sesión actual
                                    </span>
                                </template>
                            </div>
                            <div class="flex items-center gap-3 mt-0.5 text-xs text-gray-400 flex-wrap">
                                <span x-text="s.ubicacion"></span>
                                <span>·</span>
                                <span x-text="s.ip" class="font-mono"></span>
                                <span>·</span>
                                <span x-text="s.ultima"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="sesiones.length <= 1">
                    <div class="px-6 py-6 text-center text-xs text-gray-400">
                        Solo tenés una sesión activa (la actual).
                    </div>
                </template>
            </div>

            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50">
                <p class="text-xs text-gray-400">Si reconocés actividad sospechosa, cerrá las otras sesiones y cambiá tu contraseña de inmediato.</p>
            </div>
        </div>
    </div>

</div>
</div>
@endsection
