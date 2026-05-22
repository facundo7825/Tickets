@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')

    <h2 class="text-judicial-800 text-2xl font-bold mb-1">Iniciar sesión</h2>
    <p class="text-gray-500 text-sm mb-6">Ingresá con tu cuenta institucional</p>

    {{-- Mensajes de error generales --}}
    @if (session('error'))
        <div role="alert" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.75a.75.75 0 001.5 0v-4.5a.75.75 0 00-1.5 0v4.5zm.75-7a.75.75 0 110 1.5.75.75 0 010-1.5z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate x-data="{ showPass: false }">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:ring-judicial-500 focus:border-judicial-500
                       @error('email') border-red-400 bg-red-50 @enderror"
                placeholder="usuario@pjudicial.gob.ar"
                aria-describedby="@error('email') email-error @enderror"
            >
            @error('email')
                <p id="email-error" class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="mb-4">
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Contraseña
                </label>
                <a href="{{ route('password.request') }}"
                   class="text-xs text-judicial-600 hover:text-judicial-800 hover:underline">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
            <div class="relative">
                <input
                    :type="showPass ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm pr-10
                           focus:ring-judicial-500 focus:border-judicial-500
                           @error('password') border-red-400 bg-red-50 @enderror"
                    placeholder="••••••••••"
                    aria-describedby="@error('password') password-error @enderror"
                >
                <button
                    type="button"
                    @click="showPass = !showPass"
                    :aria-label="showPass ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600"
                >
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p id="password-error" class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recordarme --}}
        <div class="mb-6 flex items-center gap-2">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="rounded border-gray-300 text-judicial-600 focus:ring-judicial-500"
                {{ old('remember') ? 'checked' : '' }}
            >
            <label for="remember" class="text-sm text-gray-600 select-none">
                Mantener sesión iniciada
            </label>
        </div>

        {{-- Botón --}}
        <button
            type="submit"
            class="w-full bg-judicial-700 hover:bg-judicial-800 active:bg-judicial-900
                   text-white font-semibold py-2.5 rounded-lg text-sm
                   transition-colors duration-150
                   focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:ring-offset-2"
        >
            Ingresar
        </button>
    </form>

@endsection
