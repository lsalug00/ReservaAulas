@extends('layouts.admin')

@section('title', 'Gestión de usuarios')

@section('content')
<div class="mx-auto lg:px-6">
    <h1 class="text-2xl font-bold ml-1 mb-3 lg:mb-4">Gestión de Usuarios</h1>

    @if (session('success'))
        <div class="alert alert-success mb-6 shadow-lg relative">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 close-alert">✕</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mb-6 shadow-lg relative">
            <div>
                <p class="font-bold">Se encontraron errores:</p>
                <ul class="text-sm mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 close-alert">✕</button>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.users.index') }}" class="mb-6">
        @csrf
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:gap-4 gap-4">
            <!-- Input texto -->
            <div class="w-full lg:w-auto lg:flex-grow lg:max-w-xl">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ old('search',$searchOld) }}" 
                    placeholder="Buscar por nombre o email" 
                    class="input input-bordered w-full"
                >
            </div>

            <!-- Selects -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full lg:w-auto">
                <select name="rol" class="select select-bordered w-full">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ old('rol',$rolOld) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="profesor" {{ old('rol',$rolOld) == 'profesor' ? 'selected' : '' }}>Profesor</option>
                    <option value="invitado" {{ old('rol',$rolOld) == 'invitado' ? 'selected' : '' }}>Invitado</option>
                </select>
                
                <select name="active" class="select select-bordered w-full">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ old('active',$activeOld) == '1' ? 'selected' : '' }}>Activado</option>
                    <option value="0" {{ old('active',$activeOld) == '0' ? 'selected' : '' }}>Desactivado</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full lg:flex lg:w-auto">
                <button type="submit" class="btn btn-primary w-full sm:col-span-2 lg:w-auto">Filtrar</button>

                <a href="{{ route('admin.users.index', ['orden' => request('orden'), 'direccion' => request('direccion')]) }}"
                    class="btn btn-outline w-full sm:col-span-1 lg:w-auto">Limpiar filtros</a>

                <a href="{{ route('admin.users.index', request()->except(['orden', 'direccion'])) }}"
                    class="btn btn-outline w-full sm:col-span-1 lg:w-auto">Limpiar orden</a>
            </div>
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="alert alert-info my-4 text-center">
            No se encontraron profesores que coincidan con el filtro.
        </div>
    @else
        @php
            $direccionActual = request('direccion', 'asc');

            function ordenarPor($columna) {
                $direccion = request('orden') === $columna && request('direccion') === 'asc' ? 'desc' : 'asc';
                return array_merge(request()->all(), ['orden' => $columna, 'direccion' => $direccion]);
            }

            function iconoOrdenSvg($columna) {
                if (request('orden') !== $columna) return '';

                return request('direccion') === 'asc'
                    ? '<svg class="inline w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 6l-5 6h10l-5-6z" /></svg>'
                    : '<svg class="inline w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 14l5-6H5l5 6z" /></svg>';
            }
        @endphp

        {{-- Tabla en pantallas grandes --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th><a href="{{ route('admin.users.index', ordenarPor('id')) }}">ID {!! iconoOrdenSvg('id') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('name')) }}">Nombre {!! iconoOrdenSvg('name') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('surname')) }}">Apellidos {!! iconoOrdenSvg('surname') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('email')) }}">Email {!! iconoOrdenSvg('email') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('codigo')) }}">Código {!! iconoOrdenSvg('codigo') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('rol')) }}">Rol {!! iconoOrdenSvg('rol') !!}</a></th>
                        <th><a href="{{ route('admin.users.index', ordenarPor('active')) }}">Activo {!! iconoOrdenSvg('active') !!}</a></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="{{ !$user->active ? 'opacity-60' : '' }}">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="codigo-container" data-user-id="{{ $user->id }}">
                                    <span class="codigo-text">{{ $user->codigo }}</span>
                                    <form action="{{ route('admin.users.updateCode', $user) }}" method="POST" class="hidden codigo-form items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="codigo" class="input input-sm input-bordered uppercase w-20 codigo-input" value="{{ $user->codigo }}" pattern="[A-Z]{2}[0-9]{2}" required>
                                        <button type="button" class="btn btn-sm btn-success guardar-btn" disabled>Guardar</button>
                                        <button type="button" class="btn btn-sm btn-ghost cancelar-btn">Cancelar</button>
                                    </form>
                                    <button type="button" class="btn btn-xs btn-outline editar-btn">Editar</button>
                                </div>
                            </td>
                            <td>
                                <div class="rol-container" data-user-id="{{ $user->id }}">
                                    <span class="rol-text">{{ ucfirst($user->rol) }}</span>
                                    <form action="{{ route('admin.users.changeRole', $user) }}" method="POST" class="hidden rol-form items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="rol" class="select select-sm select-bordered w-28">
                                            <option value="admin" {{ $user->rol === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="profesor" {{ $user->rol === 'profesor' ? 'selected' : '' }}>Profesor</option>
                                            <option value="invitado" {{ $user->rol === 'invitado' ? 'selected' : '' }}>Invitado</option>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-success guardar-rol-btn" disabled>Guardar</button>
                                        <button type="button" class="btn btn-sm btn-ghost cancelar-rol-btn">Cancelar</button>
                                    </form>
                                    <button type="button" class="btn btn-xs btn-outline editar-rol-btn">Editar</button>
                                </div>
                            </td>
                            <td>
                                <div class="activo-container" data-user-id="{{ $user->id }}">
                                    <form action="{{ route('admin.users.toggleActive', $user) }}" method="POST" class="toggle-form">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }} toggle-btn">
                                            {{ $user->active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="eliminar-container" data-user-id="{{ $user->id }}">
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-error eliminar-btn">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Versión responsive para móvil (tarjetas por usuario) --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:hidden">
            @foreach ($users as $user)
                <div class="card bg-base-100 shadow border border-base-300 {{ !$user->active ? 'opacity-60' : '' }}">
                    <div class="card-body space-y-2 text-sm">
                        <div><strong>ID:</strong> {{ $user->id }}</div>
                        <div><strong>Nombre:</strong> {{ $user->name }}</div>
                        <div><strong>Apellidos:</strong> {{ $user->surname }}</div>
                        <div><strong>Email:</strong> {{ $user->email }}</div>
                        <div><strong>Código:</strong>
                            <div class="codigo-container" data-user-id="{{ $user->id }}">
                                <span class="codigo-text">{{ $user->codigo }}</span>
                                <form action="{{ route('admin.users.updateCode', $user) }}" method="POST" class="hidden codigo-form items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="codigo" class="input input-sm input-bordered uppercase w-20 codigo-input" value="{{ $user->codigo }}" pattern="[A-Z]{2}[0-9]{2}" required>
                                    <button type="button" class="btn btn-sm btn-success guardar-btn" disabled>Guardar</button>
                                    <button type="button" class="btn btn-sm btn-ghost cancelar-btn">Cancelar</button>
                                </form>
                                <button type="button" class="btn btn-xs btn-outline editar-btn">Editar</button>
                            </div>
                        </div>
                        <div><strong>Rol:</strong>
                            <div class="rol-container" data-user-id="{{ $user->id }}">
                                <span class="rol-text">{{ ucfirst($user->rol) }}</span>
                                <form action="{{ route('admin.users.changeRole', $user) }}" method="POST" class="hidden rol-form items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="rol" class="select select-sm select-bordered w-28">
                                        <option value="admin" {{ $user->rol === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="profesor" {{ $user->rol === 'profesor' ? 'selected' : '' }}>Profesor</option>
                                        <option value="invitado" {{ $user->rol === 'invitado' ? 'selected' : '' }}>Invitado</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-success guardar-rol-btn" disabled>Guardar</button>
                                    <button type="button" class="btn btn-sm btn-ghost cancelar-rol-btn">Cancelar</button>
                                </form>
                                <button type="button" class="btn btn-xs btn-outline editar-rol-btn">Editar</button>
                            </div>
                        </div>
                        <div>
                            <div class="activo-container" data-user-id="{{ $user->id }}">
                            <form action="{{ route('admin.users.toggleActive', $user) }}" method="POST" class="toggle-form">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }} toggle-btn">
                                    {{ $user->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                        </div>
                        <div class="mt-2">
                            <div class="eliminar-container" data-user-id="{{ $user->id }}">
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-error eliminar-btn">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $users->links('vendor.pagination.daisy') }}
        </div>
    @endif
    {{-- Modal único para confirmar cambio de código --}}
    <div id="modalCodigo" class="modal">
        <div class="modal-box max-w-lg w-11/12 sm:w-96">
            <h3 class="font-bold text-lg mb-2">Confirmar cambio de código</h3>
            <p>¿Está seguro que desea guardar el nuevo código para <span id="modalCodigoNombre" class="font-semibold"></span>?</p>
            <div class="modal-action">
                <button class="btn btn-ghost" id="cancelarCodigo">Cancelar</button>
                <button class="btn btn-primary" id="confirmarCodigo">Confirmar</button>
            </div>
        </div>
    </div>

    {{-- Modal único para confirmar cambio de rol --}}
    <div id="modalRol" class="modal">
        <div class="modal-box max-w-lg sm:w-96">
            <h3 class="font-bold text-lg mb-2">Confirmar cambio de rol</h3>
            <p>¿Está seguro que desea guardar el nuevo rol para <span id="modalRolNombre" class="font-semibold"></span>?</p>
            <div class="modal-action">
                <button class="btn btn-ghost" id="cancelarRol">Cancelar</button>
                <button class="btn btn-primary" id="confirmarRol">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Modal Activar/Desactivar -->
    <div id="modalActivo" class="modal">
        <div class="modal-box">
            <h3 class="text-lg">
                Quieres <span id="accionActivo" class="font-semibold"></span> del usuario <span id="modalActivoNombre" class="font-semibold"></span>?
            </h3>
            <div class="modal-action">
                <button id="confirmarActivo" class="btn btn-primary">Confirmar</button>
                <button id="cancelarActivo" class="btn btn-ghost">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div id="modalEliminar" class="modal">
    <div class="modal-box">
        <h3 class="text-lg">
        ¿Confirmar eliminación del usuario <span id="modalEliminarNombre" class="font-semibold"></span>?
        </h3>
        <div class="modal-action">
        <button id="confirmarEliminar" class="btn btn-error">Eliminar</button>
        <button id="cancelarEliminar" class="btn btn-ghost">Cancelar</button>
        </div>
    </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Función para obtener nombre desde el contenedor, tabla o móvil
        function obtenerNombreUsuario(container) {
            if (!container) return '(usuario)';

            // Tabla
            const fila = container.closest('tr');
            if (fila) {
                const tdNombre = fila.querySelector('td:nth-child(2)');
                const tdApellidos = fila.querySelector('td:nth-child(3)');
                const nombre = tdNombre?.textContent.trim() ?? '';
                const apellidos = tdApellidos?.textContent.trim() ?? '';
                return `${nombre} ${apellidos}`.trim() || '(usuario)';
            }

            // Móvil (card-body)
            const cardBody = container.closest('.card-body');
            if (cardBody) {
                let nombre = '', apellidos = '';
                const divs = cardBody.querySelectorAll('div');
                divs.forEach(div => {
                    const strong = div.querySelector('strong');
                    if (!strong) return;
                    const label = strong.textContent.trim();
                    if (label === 'Nombre:') {
                        nombre = div.textContent.replace('Nombre:', '').trim();
                    } else if (label === 'Apellidos:') {
                        apellidos = div.textContent.replace('Apellidos:', '').trim();
                    }
                });
                return `${nombre} ${apellidos}`.trim() || '(usuario)';
            }

            return '(usuario)';
        }


        // Funcionalidad cerrar alertas
        document.querySelectorAll('.close-alert').forEach(btn => {
            btn.addEventListener('click', () => btn.closest('.alert').remove());
        });

        // Código
        document.querySelectorAll('.codigo-container').forEach(container => {
            const editarBtn = container.querySelector('.editar-btn');
            const form = container.querySelector('.codigo-form');
            const cancelarBtn = container.querySelector('.cancelar-btn');
            const guardarBtn = container.querySelector('.guardar-btn');
            const input = container.querySelector('.codigo-input');
            const textoSpan = container.querySelector('.codigo-text');

            editarBtn.addEventListener('click', () => {
                form.classList.remove('hidden');
                editarBtn.classList.add('hidden');
                textoSpan.classList.add('hidden');
            });

            cancelarBtn.addEventListener('click', () => {
                form.classList.add('hidden');
                editarBtn.classList.remove('hidden');
                textoSpan.classList.remove('hidden');
                input.value = textoSpan.textContent.trim();
                guardarBtn.disabled = true;
            });

            input.addEventListener('input', () => {
                const valido = /^[A-Z]{2}[0-9]{2}$/.test(input.value);
                guardarBtn.disabled = !valido || input.value === textoSpan.textContent.trim();
            });

            guardarBtn.addEventListener('click', () => {
                const modal = document.getElementById('modalCodigo');
                // Poner el nombre en el modal
                const nombreSpan = modal.querySelector('#modalCodigoNombre');
                nombreSpan.textContent = obtenerNombreUsuario(container);
                modal.classList.add('modal-open');

                const confirmarBtn = document.getElementById('confirmarCodigo');
                const cancelarModalBtn = document.getElementById('cancelarCodigo');

                function confirmarHandler() {
                    modal.classList.remove('modal-open');
                    form.submit();
                    limpiar();
                }
                function cancelarHandler() {
                    modal.classList.remove('modal-open');
                    limpiar();
                }
                function limpiar() {
                    confirmarBtn.removeEventListener('click', confirmarHandler);
                    cancelarModalBtn.removeEventListener('click', cancelarHandler);
                }
                confirmarBtn.addEventListener('click', confirmarHandler);
                cancelarModalBtn.addEventListener('click', cancelarHandler);
            });
        });

        // Rol
        document.querySelectorAll('.rol-container').forEach(container => {
            const editarBtn = container.querySelector('.editar-rol-btn');
            const form = container.querySelector('.rol-form');
            const cancelarBtn = container.querySelector('.cancelar-rol-btn');
            const guardarBtn = container.querySelector('.guardar-rol-btn');
            const select = container.querySelector('select');
            const textoSpan = container.querySelector('.rol-text');

            editarBtn.addEventListener('click', () => {
                form.classList.remove('hidden');
                editarBtn.classList.add('hidden');
                textoSpan.classList.add('hidden');
            });

            cancelarBtn.addEventListener('click', () => {
                form.classList.add('hidden');
                editarBtn.classList.remove('hidden');
                textoSpan.classList.remove('hidden');
                select.value = textoSpan.textContent.trim().toLowerCase();
                guardarBtn.disabled = true;
            });

            select.addEventListener('change', () => {
                guardarBtn.disabled = select.value === textoSpan.textContent.trim().toLowerCase();
            });

            guardarBtn.addEventListener('click', () => {
                const modal = document.getElementById('modalRol');
                // Poner el nombre en el modal
                const nombreSpan = modal.querySelector('#modalRolNombre');
                nombreSpan.textContent = obtenerNombreUsuario(container);
                modal.classList.add('modal-open');

                const confirmarBtn = document.getElementById('confirmarRol');
                const cancelarModalBtn = document.getElementById('cancelarRol');

                function confirmarHandler() {
                    modal.classList.remove('modal-open');
                    form.submit();
                    limpiar();
                }
                function cancelarHandler() {
                    modal.classList.remove('modal-open');
                    limpiar();
                }
                function limpiar() {
                    confirmarBtn.removeEventListener('click', confirmarHandler);
                    cancelarModalBtn.removeEventListener('click', cancelarHandler);
                }
                confirmarBtn.addEventListener('click', confirmarHandler);
                cancelarModalBtn.addEventListener('click', cancelarHandler);
            });
        });

        // Activar / Desactivar
        document.querySelectorAll('.activo-container').forEach(container => {
            const form = container.querySelector('.toggle-form');
            const btn = container.querySelector('.toggle-btn');

            btn.addEventListener('click', () => {
                const modal = document.getElementById('modalActivo');
                modal.classList.add('modal-open');

                // Obtener el nombre completo reutilizando la función
                const nombreCompleto = obtenerNombreUsuario(container);

                // Poner la acción (activar/desactivar) y el nombre en el modal
                modal.querySelector('#accionActivo').textContent = btn.textContent.trim().toLowerCase();
                modal.querySelector('#modalActivoNombre').textContent = nombreCompleto;

                const confirmarBtn = document.getElementById('confirmarActivo');
                const cancelarBtn = document.getElementById('cancelarActivo');

                function confirmarHandler() {
                    modal.classList.remove('modal-open');
                    form.submit();
                    limpiar();
                }

                function cancelarHandler() {
                    modal.classList.remove('modal-open');
                    limpiar();
                }

                function limpiar() {
                    confirmarBtn.removeEventListener('click', confirmarHandler);
                    cancelarBtn.removeEventListener('click', cancelarHandler);
                }

                confirmarBtn.addEventListener('click', confirmarHandler);
                cancelarBtn.addEventListener('click', cancelarHandler);
            });
        });

        // Eliminar
        document.querySelectorAll('.eliminar-container').forEach(container => {
            const form = container.querySelector('form');
            const btn = container.querySelector('.eliminar-btn');

            btn.addEventListener('click', () => {
                const modal = document.getElementById('modalEliminar');
                modal.classList.add('modal-open');

                const nombre = obtenerNombreUsuario(container);
                modal.querySelector('#modalEliminarNombre').textContent = nombre;

                const confirmarBtn = document.getElementById('confirmarEliminar');
                const cancelarBtn = document.getElementById('cancelarEliminar');

                function confirmarHandler() {
                    modal.classList.remove('modal-open');
                    form.submit();
                    limpiar();
                }

                function cancelarHandler() {
                    modal.classList.remove('modal-open');
                    limpiar();
                }

                function limpiar() {
                    confirmarBtn.removeEventListener('click', confirmarHandler);
                    cancelarBtn.removeEventListener('click', cancelarHandler);
                }

                confirmarBtn.addEventListener('click', confirmarHandler);
                cancelarBtn.addEventListener('click', cancelarHandler);
            });
        });
    });
</script>
@endsection
