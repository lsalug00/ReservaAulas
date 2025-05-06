@if (session('success'))
    <div class="alert alert-success mb-4 shadow">
        <span>{{ session('success') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('perfil') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @csrf

    {{-- Fecha desde --}}
    <div class="form-control">
        <label class="label">Fecha desde</label>
        <input type="date" name="fecha_inicio" class="input input-bordered w-full"
            value="{{ old('fecha_inicio', $request->input('fecha_inicio')) }}">
    </div>

    {{-- Fecha hasta --}}
    <div class="form-control">
        <label class="label">Fecha hasta</label>
        <input type="date" name="fecha_fin" class="input input-bordered w-full"
            value="{{ old('fecha_fin', $request->input('fecha_fin')) }}">
    </div>

    {{-- Uso --}}
    <div class="form-control">
        <label class="label">Uso</label>
        <select name="uso" class="select select-bordered w-full">
            <option value="">Todos</option>
            @foreach(['clase', 'examen', 'charla', 'taller', 'otro'] as $opcion)
                <option value="{{ $opcion }}" {{ $request->input('uso') == $opcion ? 'selected' : '' }}>
                    {{ ucfirst($opcion) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Estado --}}
    <div class="form-control">
        <label class="label">Estado</label>
        <select name="estado" class="select select-bordered w-full">
            <option value="">Todos</option>
            <option value="futuras" {{ $request->input('estado') === 'futuras' ? 'selected' : '' }}>Futuras</option>
            <option value="pasadas" {{ $request->input('estado') === 'pasadas' ? 'selected' : '' }}>Pasadas</option>
        </select>
    </div>

    {{-- Tipo de aula --}}
    <div class="form-control">
        <label class="label">Tipo de aula</label>
        <select name="categoria_id" class="select select-bordered w-full">
            <option value="">Todas</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ $request->input('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{ ucfirst($categoria->nombre) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Edificio --}}
    <div class="form-control">
        <label class="label">Edificio</label>
        <select name="edificio" class="select select-bordered w-full">
            <option value="">Todos</option>
            @foreach ($edificios as $edificio)
                <option value="{{ $edificio }}" {{ $request->input('edificio') == $edificio ? 'selected' : '' }}>
                    {{ ucfirst($edificio) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Planta --}}
    <div class="form-control">
        <label class="label">Planta</label>
        <select name="planta" class="select select-bordered w-full">
            <option value="">Todas</option>
            @foreach ($plantas as $planta)
                <option value="{{ $planta }}" {{ $request->input('planta') == $planta ? 'selected' : '' }}>
                    {{ $planta }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Botón --}}
    <div class="flex justify-end items-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>

@if ($reservas->isEmpty())
    <p class="text-gray-600">No tienes reservas registradas.</p>
@else
    <div class="overflow-x-auto">
        @php
            $ordenActual = request('orden');
            $direccionActual = request('direccion', 'asc');

            function ordenarPor($columna) {
                $direccion = request('orden') === $columna && request('direccion') === 'asc' ? 'desc' : 'asc';
                return array_merge(request()->all(), ['orden' => $columna, 'direccion' => $direccion]);
            }

            function iconoOrdenSvg($columna) {
                if (request('orden') !== $columna) return '';

                return request('direccion') === 'asc'
                    ? '<svg class="inline w-6 h-6 mb-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 6l-5 6h10l-5-6z" /></svg>'
                    : '<svg class="inline w-6 h-6 mb-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 14l5-6H5l5 6z" /></svg>';
            }
        @endphp

        <table class="table w-full table-zebra">
            <thead>
                <tr>
                    <th><a href="{{ route('perfil', ordenarPor('fecha')) }}">Fecha {!! iconoOrdenSvg('fecha') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('hora_inicio')) }}">Inicio {!! iconoOrdenSvg('hora_inicio') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('hora_fin')) }}">Fin {!! iconoOrdenSvg('hora_fin') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('uso')) }}">Uso {!! iconoOrdenSvg('uso') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('aula')) }}">Aula {!! iconoOrdenSvg('aula') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('edificio')) }}">Edificio {!! iconoOrdenSvg('edificio') !!}</a></th>
                    <th><a href="{{ route('perfil', ordenarPor('planta')) }}">Planta {!! iconoOrdenSvg('planta') !!}</a></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservas as $reserva)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($reserva->fecha)) }}</td>
                        <td>{{ substr($reserva->hora_inicio, 0, 5) }}</td>
                        <td>{{ substr($reserva->hora_fin, 0, 5) }}</td>
                        <td>{{ ucfirst($reserva->uso) }}</td>
                        <td>{{ $reserva->aula->codigo }} - {{ $reserva->aula->nombre }}</td>
                        <td>{{ $reserva->aula->edificio }}</td>
                        <td>{{ $reserva->aula->planta }}</td>
                        <td>
                            @php
                                $esFutura = $reserva->fecha > $hoy || ($reserva->fecha === $hoy && $reserva->hora_inicio > $ahora);
                                $enCurso = $reserva->fecha === $hoy && $reserva->hora_inicio <= $ahora && $reserva->hora_fin > $ahora;
                                $finalizada = $reserva->fecha < $hoy || ($reserva->fecha === $hoy && $reserva->hora_fin <= $ahora);
                            @endphp

                            @if ($esFutura)
                                <form id="form-cancelar-{{ $reserva->id }}" method="POST" action="{{ route('mis-reservas.destroy', $reserva) }}">
                                    @csrf
                                    @method('DELETE')
                                    <label for="cancelar-modal" class="btn btn-sm btn-error abrir-modal" data-id="{{ $reserva->id }}">
                                        Cancelar
                                    </label>
                                </form>
                            @elseif ($enCurso)
                                <span class="text-warning font-semibold">En curso</span>
                            @elseif ($finalizada)
                                <span class="text-success font-semibold">Finalizada</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            {{ $reservas->links('vendor.pagination.daisy') }}
        </div>
    </div>
@endif

{{-- MODAL GLOBAL --}}
<input type="checkbox" id="cancelar-modal" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">¿Cancelar reserva?</h3>
        <p class="py-4">¿Estás seguro de que deseas cancelar esta reserva?</p>
        <div class="modal-action">
            <label for="cancelar-modal" class="btn">No</label>
            <button id="confirmar-cancelacion" class="btn btn-error">Sí, cancelar</button>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    let reservaId = null;

    document.querySelectorAll('.abrir-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            reservaId = this.getAttribute('data-id');
        });
    });

    document.getElementById('confirmar-cancelacion').addEventListener('click', function () {
        if (reservaId) {
            document.getElementById('form-cancelar-' + reservaId).submit();
        }
    });
</script>
