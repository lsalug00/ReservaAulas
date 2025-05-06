@extends('layouts.admin')

@section('title', 'Usuario creado')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">✅ Usuario creado</h1>

    <div class="card bg-base-100 shadow p-4 space-y-2">
        <p><strong>Nombre:</strong> {{ $userData[0] }}</p>
        <p><strong>Apellidos:</strong> {{ $userData[1] }}</p>
        <p><strong>Email:</strong> {{ $userData[2] }}</p>
        <p><strong>Contraseña generada:</strong> <code>{{ $userData[3] }}</code></p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">Volver a la lista</a>
        <a href="{{ route('admin.users.create.download') }}" class="btn btn-primary">⬇️ Descargar CSV</a>
    </div>
</div>
@endsection
