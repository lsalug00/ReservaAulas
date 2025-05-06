@extends('layouts.admin')

@section('title', 'Editar Franjas Horarias')

@section('content')
<div class="max-w-3xl mx-auto py-8 space-y-6">
    <h1 class="text-2xl font-bold mb-4">🕒 Editar Franjas Horarias</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

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
                        <td><input type="time" name="hora_inicio" value="{{ substr($horario->hora_inicio, 0, 5) }}" required class="input input-bordered w-full" /></td>
                        <td><input type="time" name="hora_fin" value="{{ substr($horario->hora_fin, 0, 5) }}" required class="input input-bordered w-full" /></td>
                        <td>
                            <select name="turno" class="select select-bordered" required>
                                <option value="mañana" @selected($horario->turno == 'mañana')>Mañana</option>
                                <option value="tarde" @selected($horario->turno == 'tarde')>Tarde</option>
                                <option value="ambos" @selected($horario->turno == 'ambos')>Ambos</option>
                            </select>
                        </td>
                        <td><button class="btn btn-primary btn-sm">Guardar</button></td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
