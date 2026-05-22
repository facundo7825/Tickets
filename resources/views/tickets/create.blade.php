@extends('layouts.app')

@section('title', 'Nuevo ticket')
@section('page-title', 'Nuevo ticket')

@section('content')
<div
    x-data="{
        titulo: '',
        categoria: '',
        subcategoria: '',
        prioridad: 'media',
        justificacion: '',
        descripcion: '',
        archivos: [],
        arrastrandoArchivo: false,
        enviando: false,

        subcategorias: {
            soporte:     ['Hardware', 'Software de escritorio', 'Periféricos', 'Cuentas y accesos', 'Otro'],
            desarrollo:  ['Error en sistema', 'Nueva funcionalidad', 'Consulta técnica', 'Datos / reportes', 'Otro'],
            redes:       ['Conectividad', 'VPN', 'Telefonía IP', 'Recursos compartidos', 'Acceso remoto', 'Otro'],
        },

        get opcionesSubcategoria() {
            return this.subcategorias[this.categoria] ?? [];
        },

        cambiarCategoria() {
            this.subcategoria = '';
        },

        prioridades: [
            {
                id: 'baja',
                label: 'Baja',
                desc: 'Consulta o mejora sin urgencia',
                color: 'gray',
                ring: 'ring-gray-400',
                bg: 'bg-gray-50',
                badge: 'bg-gray-100 text-gray-600',
                icon: `<path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M19 9l-7 7-7-7'/>`
            },
            {
                id: 'media',
                label: 'Media',
                desc: 'Afecta a un usuario sin urgencia',
                color: 'blue',
                ring: 'ring-blue-400',
                bg: 'bg-blue-50',
                badge: 'bg-blue-100 text-blue-700',
                icon: `<path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M5 12h14'/>`
            },
            {
                id: 'alta',
                label: 'Alta',
                desc: 'Afecta a varios usuarios o tareas clave',
                color: 'orange',
                ring: 'ring-orange-400',
                bg: 'bg-orange-50',
                badge: 'bg-orange-100 text-orange-700',
                icon: `<path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M5 15l7-7 7 7'/>`
            },
            {
                id: 'critica',
                label: 'Crítica',
                desc: 'Bloquea actividad jurisdiccional',
                color: 'red',
                ring: 'ring-red-400',
                bg: 'bg-red-50',
                badge: 'bg-red-100 text-red-700',
                icon: `<path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/>`
            },
        ],

        formatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },

        validarArchivo(file) {
            const maxSize = 10 * 1024 * 1024;
            const tiposPermitidos = ['application/pdf','image/jpeg','image/png','image/gif','image/webp',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip','text/plain','text/csv'];
            const extPermitidas = ['pdf','jpg','jpeg','png','gif','webp','docx','xlsx','zip','txt','csv'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!extPermitidas.includes(ext)) return 'Tipo de archivo no permitido.';
            if (!tiposPermitidos.includes(file.type) && file.type !== '') return 'Tipo MIME no permitido.';
            if (file.size > maxSize) return 'El archivo supera los 10 MB.';
            return null;
        },

        agregarArchivos(fileList) {
            Array.from(fileList).forEach(file => {
                if (this.archivos.length >= 20) return;
                const error = this.validarArchivo(file);
                const existe = this.archivos.some(a => a.name === file.name && a.size === file.size);
                if (!existe) {
                    this.archivos.push({ file, name: file.name, size: file.size, error });
                }
            });
        },

        quitarArchivo(index) {
            this.archivos.splice(index, 1);
        },

        onDrop(e) {
            this.arrastrandoArchivo = false;
            this.agregarArchivos(e.dataTransfer.files);
        },

        get hayErroresArchivos() {
            return this.archivos.some(a => a.error);
        },

        submit(e) {
            if (this.hayErroresArchivos) { e.preventDefault(); return; }
            this.enviando = true;
        }
    }"
    @submit.prevent="submit($event); if (!hayErroresArchivos) $el.submit()"
>

<form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data"
      x-ref="form" @submit="submit($event); if (!hayErroresArchivos) { enviando=true; $el.submit() }">
    @csrf

    {{-- Errores globales --}}
    @if ($errors->any())
        <div role="alert" class="bg-red-50 border border-red-200 rounded-xl px-5 py-4 mb-6">
            <p class="text-sm font-semibold text-red-700 mb-1">Por favor corregí los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        {{-- ===== COLUMNA IZQUIERDA (3/5) ===== --}}
        <div class="xl:col-span-3 space-y-5">

            {{-- Título --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Título <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-400 mb-2">Describí el problema en una línea clara y concisa.</p>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    x-model="titulo"
                    maxlength="150"
                    required
                    placeholder="Ej: No puedo imprimir desde la sala de audiencias 2"
                    value="{{ old('titulo') }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                           focus:ring-judicial-500 focus:border-judicial-500
                           @error('titulo') border-red-400 bg-red-50 @enderror"
                >
                <div class="flex justify-between mt-1.5">
                    @error('titulo')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @else
                        <span></span>
                    @enderror
                    <p class="text-xs ml-auto"
                       :class="titulo.length > 130 ? 'text-orange-500 font-medium' : 'text-gray-400'">
                        <span x-text="titulo.length"></span>/150
                    </p>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Descripción detallada <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-400 mb-2">
                    Explicá con detalle: ¿qué pasó?, ¿cuándo?, ¿en qué equipo o sistema?, ¿qué pasos seguiste?
                </p>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    x-model="descripcion"
                    rows="6"
                    required
                    minlength="20"
                    placeholder="Describí el problema con el mayor detalle posible…"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm resize-none
                           focus:ring-judicial-500 focus:border-judicial-500
                           @error('descripcion') border-red-400 bg-red-50 @enderror"
                >{{ old('descripcion') }}</textarea>
                <div class="flex justify-between mt-1.5">
                    @error('descripcion')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400"
                           x-show="descripcion.length > 0 && descripcion.length < 20">
                            Mínimo 20 caracteres
                        </p>
                    @enderror
                    <p class="text-xs ml-auto text-gray-400">
                        <span x-text="descripcion.length"></span> caracteres
                    </p>
                </div>
            </div>

            {{-- Adjuntos --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-700 mb-1.5">Adjuntos</p>
                <p class="text-xs text-gray-400 mb-3">
                    PDF, imágenes, DOCX, XLSX, ZIP · Máx. 10 MB por archivo · Hasta 20 archivos
                </p>

                {{-- Zona drag & drop --}}
                <div
                    @dragover.prevent="arrastrandoArchivo = true"
                    @dragleave.prevent="arrastrandoArchivo = false"
                    @drop.prevent="onDrop($event)"
                    @click="$refs.fileInput.click()"
                    :class="arrastrandoArchivo
                        ? 'border-judicial-400 bg-judicial-50 scale-[1.01]'
                        : 'border-gray-300 bg-gray-50 hover:border-judicial-400 hover:bg-judicial-50'"
                    class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer
                           transition-all duration-150 select-none"
                >
                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-600 font-medium">Arrastrá archivos aquí</p>
                    <p class="text-xs text-gray-400 mt-0.5">o hacé clic para seleccionar</p>
                </div>

                <input
                    type="file"
                    x-ref="fileInput"
                    name="adjuntos[]"
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.docx,.xlsx,.zip,.txt,.csv"
                    @change="agregarArchivos($event.target.files); $event.target.value = ''"
                    class="hidden"
                >

                {{-- Lista de archivos --}}
                <div x-show="archivos.length > 0" class="mt-3 space-y-2">
                    <template x-for="(archivo, index) in archivos" :key="index">
                        <div :class="archivo.error ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200'"
                             class="flex items-center gap-3 px-3 py-2.5 rounded-lg border text-sm">
                            <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="truncate font-medium text-gray-700" x-text="archivo.name"></p>
                                <p x-show="!archivo.error" class="text-xs text-gray-400" x-text="formatBytes(archivo.size)"></p>
                                <p x-show="archivo.error" class="text-xs text-red-600" x-text="archivo.error"></p>
                            </div>
                            <button type="button" @click="quitarArchivo(index)"
                                    class="text-gray-400 hover:text-red-500 transition-colors p-0.5 rounded shrink-0"
                                    :aria-label="'Quitar ' + archivo.name">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        {{-- ===== COLUMNA DERECHA (2/5) ===== --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Categoría + Subcategoría --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">

                <div>
                    <label for="categoria" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="categoria"
                        name="categoria"
                        x-model="categoria"
                        @change="cambiarCategoria()"
                        required
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                               focus:ring-judicial-500 focus:border-judicial-500
                               @error('categoria') border-red-400 bg-red-50 @enderror"
                    >
                        <option value="">Seleccioná una categoría…</option>
                        <option value="soporte"    {{ old('categoria') === 'soporte'    ? 'selected' : '' }}>Soporte Técnico</option>
                        <option value="desarrollo" {{ old('categoria') === 'desarrollo' ? 'selected' : '' }}>Desarrollo de Software</option>
                        <option value="redes"      {{ old('categoria') === 'redes'      ? 'selected' : '' }}>Redes</option>
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subcategoria" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Subcategoría <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="subcategoria"
                        name="subcategoria"
                        x-model="subcategoria"
                        required
                        :disabled="!categoria"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                               focus:ring-judicial-500 focus:border-judicial-500
                               disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed
                               @error('subcategoria') border-red-400 bg-red-50 @enderror"
                    >
                        <option value="">
                            <span x-text="categoria ? 'Seleccioná una subcategoría…' : 'Primero elegí una categoría'"></span>
                        </option>
                        <template x-for="opcion in opcionesSubcategoria" :key="opcion">
                            <option :value="opcion" x-text="opcion"></option>
                        </template>
                    </select>
                    @error('subcategoria')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Prioridad --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-sm font-semibold text-gray-700 mb-3">
                    Prioridad <span class="text-red-500">*</span>
                </p>

                <div class="grid grid-cols-2 gap-2">
                    <template x-for="p in prioridades" :key="p.id">
                        <label
                            :for="'prioridad_' + p.id"
                            :class="{
                                'ring-2 shadow-sm': prioridad === p.id,
                                [p.ring]: prioridad === p.id,
                                [p.bg]: prioridad === p.id,
                                'bg-white ring-1 ring-gray-200': prioridad !== p.id
                            }"
                            class="relative flex flex-col gap-1.5 p-3 rounded-xl cursor-pointer
                                   transition-all duration-150 hover:shadow-sm"
                        >
                            <input
                                type="radio"
                                :id="'prioridad_' + p.id"
                                name="prioridad"
                                :value="p.id"
                                x-model="prioridad"
                                class="sr-only"
                            >

                            <div class="flex items-center justify-between">
                                <svg class="w-4 h-4 shrink-0"
                                     :class="{
                                         'text-gray-400': p.id === 'baja',
                                         'text-blue-500': p.id === 'media',
                                         'text-orange-500': p.id === 'alta',
                                         'text-red-500': p.id === 'critica',
                                     }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <g x-html="p.icon"></g>
                                </svg>
                                {{-- Check activo --}}
                                <svg x-show="prioridad === p.id"
                                     class="w-3.5 h-3.5 text-judicial-600"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <p class="text-xs font-semibold text-gray-800" x-text="p.label"></p>
                            <p class="text-xs text-gray-500 leading-tight" x-text="p.desc"></p>
                        </label>
                    </template>
                </div>

                {{-- Justificación para crítica --}}
                <div x-show="prioridad === 'critica'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-3">
                    <div class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 mb-2">
                        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-xs text-red-700">La prioridad <strong>crítica</strong> requiere justificación y está sujeta a revisión del coordinador.</p>
                    </div>
                    <label for="justificacion" class="block text-xs font-semibold text-gray-700 mb-1">
                        Justificación <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="justificacion"
                        name="justificacion"
                        x-model="justificacion"
                        rows="3"
                        :required="prioridad === 'critica'"
                        minlength="20"
                        placeholder="Explicá por qué este ticket requiere atención crítica…"
                        class="w-full rounded-lg border-red-300 shadow-sm text-sm resize-none
                               focus:ring-red-400 focus:border-red-400 text-gray-700"
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1 text-right">
                        <span x-text="justificacion.length"></span> / mín. 20 caracteres
                    </p>
                </div>
            </div>

            {{-- Botones --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                <button
                    type="submit"
                    :disabled="enviando"
                    class="w-full flex items-center justify-center gap-2
                           bg-judicial-700 hover:bg-judicial-800 active:bg-judicial-900
                           disabled:opacity-60 disabled:cursor-not-allowed
                           text-white font-semibold py-2.5 rounded-lg text-sm
                           transition-colors duration-150
                           focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:ring-offset-2"
                >
                    <svg x-show="enviando" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <svg x-show="!enviando" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span x-text="enviando ? 'Enviando…' : 'Enviar ticket'"></span>
                </button>

                <a href="{{ route('dashboard') }}"
                   class="w-full flex items-center justify-center gap-2
                          border border-gray-300 text-gray-600 hover:bg-gray-50
                          font-medium py-2.5 rounded-lg text-sm transition-colors duration-150">
                    Cancelar
                </a>

                <p class="text-xs text-gray-400 text-center pt-1">
                    Tu ticket recibirá un código único <span class="font-mono">TKT-{{ date('Y') }}-XXXXX</span>
                </p>
            </div>

        </div>
    </div>

</form>
</div>
@endsection
