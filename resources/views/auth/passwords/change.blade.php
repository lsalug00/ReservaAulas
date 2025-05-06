@extends('layouts.app')

@section('title', 'Cambiar contraseña')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">🔒 Cambiar contraseña</h1>

        @if (session('error'))
            <div class="alert alert-error shadow mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error shadow mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pass.update') }}" class="max-w-md space-y-4">
            @csrf

            <div class="form-control relative">
                <label class="label">Contraseña actual</label>
                <input id="current_password" type="password" name="current_password" class="input input-bordered w-full" required>
                <button type="button" id="togglePasswordCurrent" class="absolute right-3 top-10 transform -translate-y-1/4 text-gray-500">
                    <svg id="eyeClosedCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg id="eyeOpenCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </button>
            </div>

            <div class="form-control relative">
                <label class="label">Nueva contraseña</label>
                <input id="password" type="password" name="password" class="input input-bordered w-full" required>
                <button type="button" id="togglePassword" class="absolute right-3 top-10 transform -translate-y-1/4 text-gray-500">
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </button>
            </div>

            <div class="form-control relative">
                <label class="label">Repetir nueva contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input input-bordered w-full" required>
                <button type="button" id="togglePasswordConfirm" class="absolute right-3 top-10 transform -translate-y-1/4 text-gray-500">
                    <svg id="eyeClosedConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                        <path stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn btn-primary">Guardar nueva contraseña</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function togglePasswordVisibility(toggleId, inputId, openId, closedId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                const eyeOpen = document.getElementById(openId);
                const eyeClosed = document.getElementById(closedId);
                if (toggle && input) {
                    toggle.addEventListener('click', () => {
                        const isHidden = input.type === 'password';
                        input.type = isHidden ? 'text' : 'password';
                        eyeOpen.classList.toggle('hidden', isHidden);
                        eyeClosed.classList.toggle('hidden', !isHidden);
                    });
                }
            }
    
            togglePasswordVisibility('togglePasswordCurrent', 'current_password', 'eyeOpenCurrent', 'eyeClosedCurrent');
            togglePasswordVisibility('togglePassword', 'password', 'eyeOpen', 'eyeClosed');
            togglePasswordVisibility('togglePasswordConfirm', 'password_confirmation', 'eyeOpenConfirm', 'eyeClosedConfirm');
        });
    </script>
@endsection
