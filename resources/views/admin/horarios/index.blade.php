@extends('layouts.admin')

@section('title', 'Editar Franjas Horarias')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 px-4 py-2">
    <h1 class="text-2xl font-bold mb-4">Editar Franjas Horarias</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Diseño tipo tarjeta en móvil --}}
    <div class="space-y-4 md:hidden">
        @foreach($horarios as $index => $horario)
            <form action="{{ route('admin.horarios.update', $horario) }}" method="POST"
                  class="bg-base-200 p-4 rounded-lg shadow space-y-2">
                @csrf
                <div class="font-semibold">#{{ $index + 1 }}</div>
                <div>
                    <label class="label">Inicio</label>
                    <input type="time" name="hora_inicio" value="{{ substr($horario->hora_inicio, 0, 5) }}"
                           class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label">Fin</label>
                    <input type="time" name="hora_fin" value="{{ substr($horario->hora_fin, 0, 5) }}"
                           class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label">Turno</label>
                    <select name="turno" class="select select-bordered w-full" required>
                        <option value="mañana" @selected($horario->turno == 'mañana')>Mañana</option>
                        <option value="tarde" @selected($horario->turno == 'tarde')>Tarde</option>
                        <option value="ambos" @selected($horario->turno == 'ambos')>Ambos</option>
                    </select>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        @endforeach
    </div>

    {{-- Tabla en pantallas md+ --}}
    <div class="hidden md:block">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Turno</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $index => $horario)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <form action="{{ route('admin.horarios.update', $horario) }}" method="POST">
                            @csrf
                            <td>
                                <input type="time" name="hora_inicio"
                                       value="{{ substr($horario->hora_inicio, 0, 5) }}"
                                       class="input input-bordered w-full" required>
                            </td>
                            <td>
                                <input type="time" name="hora_fin"
                                       value="{{ substr($horario->hora_fin, 0, 5) }}"
                                       class="input input-bordered w-full" required>
                            </td>
                            <td>
                                <select name="turno" class="select select-bordered w-full" required>
                                    <option value="mañana" @selected($horario->turno == 'mañana')>Mañana</option>
                                    <option value="tarde" @selected($horario->turno == 'tarde')>Tarde</option>
                                    <option value="ambos" @selected($horario->turno == 'ambos')>Ambos</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm w-full">Guardar</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
