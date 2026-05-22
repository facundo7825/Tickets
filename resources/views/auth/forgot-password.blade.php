@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')

    <div class="mb-6">
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1 text-sm text-judicial-600 hover:text-judicial-800 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al inicio de sesión
        </a>
    </div>

    <h2 class="text-judicial-800 text-2xl font-bold mb-1">Recuperar contraseña</h2>
    <p class="text-gray-500 text-sm mb-6">
        Ingresá tu correo institucional y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    {{-- Confirmación de envío --}}
    @if (session('status'))
        <div role="alert" class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-5 text-sm">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico institucional
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

        <button
            type="submit"
            class="w-full bg-judicial-700 hover:bg-judicial-800 active:bg-judicial-900
                   text-white font-semibold py-2.5 rounded-lg text-sm
                   transition-colors duration-150
                   focus:outline-none focus:ring-2 focus:ring-judicial-500 focus:ring-offset-2"
        >
            Enviar enlace de recuperación
        </button>
    </form>

@endsection
