@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Mi perfil</h1>

    <div class="card bg-base-100 shadow-md w-full max-w-8xl px-4 sm:px-6 md:px-8 mb-8">
        <div class="card-body space-y-6">
            <div class="flex flex-col md:flex-row md:flex-wrap md:gap-x-8 md:gap-y-6">
                <!-- Nombre -->
                <div class="flex flex-col flex-1 min-w-[280px] max-w-md">
                    <p class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <span class="font-semibold min-w-[90px]">Nombre:</span> 
                        <span id="nombre-actual" class="flex-1">{{ $usuario->name }}</span>
                        <button id="btn-editar-nombre" class="btn btn-sm btn-outline mt-2 sm:mt-0 sm:ml-2 self-start sm:self-auto">Editar</button>
                    </p>
                    <div id="nombre-edicion" class="mt-2 hidden w-full max-w-xs">
                        <input type="text" id="nuevo-nombre" class="input input-bordered input-sm w-full" value="{{ $usuario->name }}">
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button id="btn-guardar-nombre" class="btn btn-sm btn-primary flex-grow sm:flex-grow-0">Guardar</button>
                            <button id="btn-cancelar-nombre" class="btn btn-sm btn-ghost flex-grow sm:flex-grow-0">Cancelar</button>
                        </div>
                        <p id="nombre-error" class="text-error mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="flex flex-col flex-1 min-w-[280px] max-w-md">
                    <p class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <span class="font-semibold min-w-[90px]">Apellidos:</span> 
                        <span id="apellido-actual" class="flex-1">{{ $usuario->surname }}</span>
                        <button id="btn-editar-apellido" class="btn btn-sm btn-outline mt-2 sm:mt-0 sm:ml-2 self-start sm:self-auto">Editar</button>
                    </p>
                    <div id="apellido-edicion" class="mt-2 hidden w-full max-w-xs">
                        <input type="text" id="nuevo-apellido" class="input input-bordered input-sm w-full" value="{{ $usuario->surname }}">
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button id="btn-guardar-apellido" class="btn btn-sm btn-primary flex-grow sm:flex-grow-0">Guardar</button>
                            <button id="btn-cancelar-apellido" class="btn btn-sm btn-ghost flex-grow sm:flex-grow-0">Cancelar</button>
                        </div>
                        <p id="apellido-error" class="text-error mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Correo -->
                <div class="flex flex-col flex-1 min-w-[280px] max-w-md">
                    <p class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <span class="font-semibold min-w-[90px]">Correo:</span>
                        <span id="correo-actual" class="flex-1">{{ $usuario->email }}</span>
                        <button id="btn-editar-correo" class="btn btn-sm btn-outline mt-2 sm:mt-0 sm:ml-2 self-start sm:self-auto">Editar</button>
                    </p>
                    <div id="correo-edicion" class="mt-2 hidden w-full max-w-xs">
                        <input type="email" id="nuevo-correo" class="input input-bordered input-sm w-full" value="{{ $usuario->email }}">
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button id="btn-guardar-correo" class="btn btn-sm btn-primary flex-grow sm:flex-grow-0">Guardar</button>
                            <button id="btn-cancelar-correo" class="btn btn-sm btn-ghost flex-grow sm:flex-grow-0">Cancelar</button>
                        </div>
                        <p id="correo-error" class="text-error mt-2 hidden"></p>
                    </div>
                </div>
                
            </div>
            <!-- Rol y cambiar contraseña abajo, full width -->
            <p><span class="font-semibold">Rol:</span> {{ ucfirst($usuario->rol) }}</p>
            <a href="{{ route('pass.edit') }}" class="btn btn-outline btn-sm mb-4">Cambiar contraseña</a>
        </div>
    </div>

    <div class="w-full max-w-7xl">
    @auth
    @if(auth()->user()->rol === 'profesor')
        <h2 class="text-xl font-semibold mb-4">Mis reservas</h2>
        @include('mis-reservas.mostrar')
    @endif
    @endauth
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Email ---
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

        // --- Nombre y Apellido ---
        const regexNombre = /^(Mª|[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s?(del|de|la|los)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/;
        const regexApellido = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(?:\s(de((\s)(la|los))?|del\s)?[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*(?:\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?(?:-[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/;

        function setupEdicion(campo) {
            const btnEditar = document.getElementById(`btn-editar-${campo}`);
            const btnGuardar = document.getElementById(`btn-guardar-${campo}`);
            const btnCancelar = document.getElementById(`btn-cancelar-${campo}`);
            const spanActual = document.getElementById(`${campo}-actual`);
            const divEdicion = document.getElementById(`${campo}-edicion`);
            const input = document.getElementById(`nuevo-${campo}`);
            const error = document.getElementById(`${campo}-error`);

            btnEditar.addEventListener('click', () => {
                spanActual.style.display = 'none';
                btnEditar.style.display = 'none';
                divEdicion.classList.remove('hidden');
                error.classList.add('hidden');
            });

            btnCancelar.addEventListener('click', () => {
                divEdicion.classList.add('hidden');
                spanActual.style.display = 'inline';
                btnEditar.style.display = 'inline-block';
                error.classList.add('hidden');
                input.value = spanActual.textContent.trim();
            });

            btnGuardar.addEventListener('click', () => {
                const valor = input.value.trim();
                const esValido = campo === 'nombre' ? regexNombre.test(valor) : regexApellido.test(valor);

                if (!esValido) {
                    error.textContent = `Formato de ${campo} inválido.`;
                    error.classList.remove('hidden');
                    return;
                }

                fetch(`/perfil/${campo}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ [campo]: valor })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        spanActual.textContent = valor;
                        btnCancelar.click();
                    } else {
                        error.textContent = data.message || 'Error al actualizar.';
                        error.classList.remove('hidden');
                    }
                })
                .catch(() => {
                    error.textContent = 'Error inesperado.';
                    error.classList.remove('hidden');
                });
            });
        }

        setupEdicion('nombre');
        setupEdicion('apellido');
    });
    </script>
@endsection
