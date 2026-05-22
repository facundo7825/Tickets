@extends('layouts.auth')

@section('title', 'Cambiar contraseña')

@section('content')

    {{-- Aviso de primer ingreso --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-6 text-sm text-amber-800">
        <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span>Es tu primer ingreso al sistema. Por seguridad, debés establecer una nueva contraseña antes de continuar.</span>
    </div>

    <h2 class="text-judicial-800 text-2xl font-bold mb-1">Cambiar contraseña</h2>
    <p class="text-gray-500 text-sm mb-6">
        Hola, <strong class="text-gray-700">{{ auth()->user()->name }}</strong>. Elegí una contraseña segura.
    </p>

    <form method="POST" action="{{ route('password.change.update') }}" novalidate
          x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        @csrf

        <div class="mb-4">
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña actual (provista por el administrador)
            </label>
            <div class="relative">
                <input
                    :type="showCurrent ? 'text' : 'password'"
                    id="current_password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm pr-10
                           focus:ring-judicial-500 focus:border-judicial-500
                           @error('current_password') border-red-400 bg-red-50 @enderror"
                    placeholder="••••••••••"
                    aria-describedby="@error('current_password') current-password-error @enderror"
                >
                <button type="button" @click="showCurrent = !showCurrent"
                        :aria-label="showCurrent ? 'Ocultar' : 'Mostrar'"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            @error('current_password')
                <p id="current-password-error" class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Nueva contraseña
            </label>
            <div class="relative">
                <input
                    :type="showNew ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm pr-10
                           focus:ring-judicial-500 focus:border-judicial-500
                           @error('password') border-red-400 bg-red-50 @enderror"
                    placeholder="••••••••••"
                    aria-describedby="password-hint @error('password') password-error @enderror"
                >
                <button type="button" @click="showNew = !showNew"
                        :aria-label="showNew ? 'Ocultar' : 'Mostrar'"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <p id="password-hint" class="mt-1 text-xs text-gray-500">
                Mínimo 10 caracteres, al menos una mayúscula, una minúscula, un número y un símbolo.
            </p>
            @error('password')
                <p id="password-error" class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmar nueva contraseña
            </label>
            <div class="relative">
                <input
                    :type="showConfirm ? 'text' : 'password'"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm pr-10
                           focus:ring-judicial-500 focus:border-judicial-500"
                    placeholder="••••••••••"
                >
                <button type="button" @click="showConfirm = !showConfirm"
                        :aria-label="showConfirm ? 'Ocultar' : 'Mostrar'"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <button
            type="submit"
            class="w-full bg-judicial-700 hover:bg-judicial-800 active:bg-judicial-900
                   text-white font-semibold py-2.5 rounded-lg text-sm
                   transition-colors duration-150
                   focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:ring-offset-2"
        >
            Establecer nueva contraseña
        </button>
    </form>

@endsection
