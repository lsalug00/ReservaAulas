@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
    <h1 class="text-3xl font-bold">Panel de Administración</h1>
    <p class="text-base text-base-content/80">
        Bienvenido, {{ Auth::user()->name }}. Desde aquí puedes gestionar todos los aspectos del sistema:
    </p>

    <ul class="list-disc list-inside text-sm text-base-content/80 space-y-1">
        <li>Editar el codigo, activar, cambiar el rol o eliminar <strong>usuarios</strong>.</li>
        <li>Crear o importar <strong>usuarios</strong> desde Excel.</li>
        <li>Gestionar <strong>aulas</strong>, sus categorías, ubicaciones y capacidades. (sin hacer)</li>
        <li>Crear <strong>aulas</strong>y categorías.</li>
        <li>Importar <strong>horarios de clase</strong> desde Excel.</li>
        <li>Gestionar <strong>franjas horarias</strong> para las reservas.</li>
        <li>Importar <strong>días no lectivos</strong> desde un PDF.</li>
    </ul>

    {{-- Tabla de usuarios invitados --}}
    <div class="overflow-x-auto bg-base-200 p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Usuarios con rol de "invitado"</h2>

        @if($invitados->isEmpty())
            <p class="text-sm">No hay usuarios con rol "invitado" actualmente.</p>
        @else
            {{-- Escritorio y tablet --}}
            <div class="hidden sm:block">
                <table class="table table-zebra w-full text-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invitados as $user)
                            <tr class="{{ !$user->active ? 'opacity-50' : '' }}">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->active ? 'Sí' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Móvil --}}
            <div class="sm:hidden space-y-2">
                @foreach ($invitados as $user)
                    <div class="bg-base-100 p-3 rounded border border-base-300 shadow-sm {{ !$user->active ? 'opacity-50' : '' }}">
                        <p class="font-semibold">{{ $user->name }} {{ $user->surname }}</p>
                        <p class="text-sm text-base-content/70">{{ $user->email }}</p>
                        <p class="text-sm mt-1">Activo: {{ $user->active ? 'Sí' : 'No' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
