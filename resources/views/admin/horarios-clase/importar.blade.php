@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-8 space-y-6">
    <h2 class="text-2xl font-bold">📥 Importar horarios de clase</h2>

    @if(session('mensaje'))
        <div class="alert alert-success mt-4">
            {{ session('mensaje') }}
        </div>
    @endif
    
    <form action="{{ route('admin.horarios-clase.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-base-200 p-6 rounded-xl shadow">
        @csrf
        <div>
            <label class="block mb-1 font-semibold">Archivo Excel (.xls):</label>
            <input type="file" name="archivo" accept=".xls" class="file-input file-input-bordered w-full" required>
        </div>

        <button type="submit" class="btn btn-primary">Importar</button>
    </form>


    @if(session('errores'))
        <div class="alert alert-warning mt-4">
            <h3 class="font-bold mb-2">Filas ignoradas:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach (session('errores') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
