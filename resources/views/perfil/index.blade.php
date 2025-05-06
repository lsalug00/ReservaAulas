@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
    <h1 class="text-2xl font-bold mb-6">👤 Mi perfil</h1>

    <div class="card bg-base-100 shadow-md max-w-xl mb-8">
        <div class="card-body space-y-2">
            <p><span class="font-semibold">Nombre:</span> {{ $usuario->name }}</p>
            <p><span class="font-semibold">Apellidos:</span> {{ $usuario->surname }}</p>
            <p>
                <span class="font-semibold">Correo:</span>
                <span id="correo-actual">{{ $usuario->email }}</span>
                <button id="btn-editar-correo" class="btn btn-sm btn-outline ml-2">Editar</button>
            </p>
            
            <div id="correo-edicion" class="mt-2 hidden">
                <input type="email" id="nuevo-correo" class="input input-bordered input-sm w-full max-w-xs" value="{{ $usuario->email }}">
                <div class="mt-2">
                    <button id="btn-guardar-correo" class="btn btn-sm btn-primary">Guardar</button>
                    <button id="btn-cancelar-correo" class="btn btn-sm btn-ghost">Cancelar</button>
                </div>
                <p id="correo-error" class="text-error mt-2 hidden"></p>
            </div>            
            <p><span class="font-semibold">Rol:</span> {{ ucfirst($usuario->rol) }}</p>
            <a href="{{ route('password.edit') }}" class="btn btn-outline btn-sm mb-4">Cambiar contraseña</a>
        </div>
    </div>

    @auth
    @if(auth()->user()->rol === 'profesor')
            <h2 class="text-xl font-semibold mb-4">📅 Mis reservas</h2>
            @include('mis-reservas.mostrar')
        @endif
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const editarBtn = document.getElementById('btn-editar-correo');
        const guardarBtn = document.getElementById('btn-guardar-correo');
        const cancelarBtn = document.getElementById('btn-cancelar-correo');
        const emailActual = document.getElementById('correo-actual');
        const emailEdicion = document.getElementById('correo-edicion');
        const emailInput = document.getElementById('nuevo-correo');
        const errorMsg = document.getElementById('correo-error');

        editarBtn.addEventListener('click', () => {
            emailActual.style.display = 'none';
            emailEdicion.classList.remove('hidden');
        });

        cancelarBtn.addEventListener('click', () => {
            emailEdicion.classList.add('hidden');
            emailActual.style.display = 'inline';
            errorMsg.classList.add('hidden');
        });

        guardarBtn.addEventListener('click', () => {
            fetch('/perfil/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ email: emailInput.value })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    emailActual.textContent = emailInput.value;
                    cancelarBtn.click();
                } else {
                    errorMsg.textContent = data.message || 'Error al actualizar.';
                    errorMsg.classList.remove('hidden');
                }
            })
            .catch(() => {
                errorMsg.textContent = 'Error inesperado.';
                errorMsg.classList.remove('hidden');
            });
        });
    });
    </script>
@endsection
