@extends('layouts.admin')

@section('title', 'Importar horarios de clase')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-0 space-y-6">
    <h2 class="text-2xl font-bold">Importar horarios de clase</h2>

    @if(session('mensaje'))
        <div class="alert alert-success mt-4">
            {{ session('mensaje') }}
        </div>
    @endif

    <form 
        action="{{ route('admin.horarios-clase.import') }}" 
        method="POST"
        enctype="multipart/form-data"
        class="space-y-4 bg-base-200 p-4 sm:p-6 rounded-xl shadow"
    >
        @csrf

        {{-- Campo y botón alineados horizontalmente desde md --}}
        <div class="flex flex-col md:flex-row md:items-end md:gap-4">
            <div class="w-full md:flex-1">
                <label class="block mb-1 font-semibold text-sm sm:text-base">Archivo Excel (.xls o .xlsx):</label>
                <input type="file" name="archivo" accept=".xls" class="file-input file-input-bordered w-full" required>
            </div>

            <div class="mt-4 md:mt-0">
                <button type="submit" class="btn btn-primary w-full md:w-auto">Importar</button>
            </div>
        </div>
    </form>

    @if(session('errores'))
        <div class="alert alert-warning mt-4">
            <h3 class="font-bold mb-2">Filas ignoradas:</h3>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach (session('errores') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection