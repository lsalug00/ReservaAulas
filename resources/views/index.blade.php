@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="max-w-8xl mx-auto px-4 md:px-6 lg:px-8 py-6 space-y-6">
        <h1 class="text-2xl font-bold mb-4">Buscar aula</h1>

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
        
        <form method="POST" id="form-busqueda-aula" action="{{ route('index') }}" class="space-y-6">
            @csrf
        
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                {{-- Categoría --}}
                <div class="form-control">
                    <label class="label">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="select select-bordered w-full">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $categoriaOld) == $categoria->id ? 'selected' : '' }}>
                                {{ ucfirst($categoria->nombre) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Capacidad mínima --}}
                <div class="form-control">
                    <label class="label">Capacidad mínima</label>
                    <input type="number" id="capacidad" name="capacidad" class="input input-bordered w-full"
                        value="{{ old('capacidad', $capacidadOld) }}" placeholder="Ej: 20" min="1">
                </div>
            
                {{-- Edificio --}}
                <div class="form-control">
                    <label class="label">Edificio</label>
                    <select name="edificio" id="edificio" class="select select-bordered w-full">
                        <option value="">Todos</option>
                        @foreach ($edificios as $edificio)
                            <option value="{{ $edificio }}" {{ old('edificio',$edificioOld) == $edificio ? 'selected' : '' }}>
                                {{ $edificio }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                {{-- Planta --}}
                <div class="form-control">
                    <label class="label">Planta</label>
                    <select name="planta" id="planta" class="select select-bordered w-full">
                        <option value="">Todas</option>
                        @foreach ($plantas as $planta)
                            <option value="{{ $planta }}" {{ old('planta',$plantaOld) == $planta ? 'selected' : '' }}>
                                {{ $planta }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                {{-- Aula --}}
                <div class="form-control">
                    <label class="label">Aula</label>
                    <select name="aula_id" id="aula_id" class="select select-bordered w-full" required>
                        <option value="">Selecciona un aula</option>
                        @foreach ($aulas as $aula)
                            <option
                                value="{{ $aula->id }}"
                                data-categoria="{{ $aula->categoria_id }}"
                                data-edificio="{{ $aula->edificio }}"
                                data-planta="{{ $aula->planta }}"
                                data-capacidad="{{ $aula->capacidad }}"
                                {{ old('aula_id', $aulaOld) == $aula->id ? 'selected' : '' }}
                            >
                                {{ $aula->codigo }} - {{ $aula->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <p id="mensaje-sin-aulas" class="text-sm text-error mt-1 hidden">
                    </p>
                </div>        
            
                {{-- Turno --}}
                <div class="form-control">
                    <label class="label">Turno</label>
                    <select name="turno" class="select select-bordered w-full">
                        <option value="">Todos</option>
                        <option value="mañana" {{ old('turno',$turnoOld) == 'mañana' ? 'selected' : '' }}>Mañana</option>
                        <option value="tarde" {{ old('turno',$turnoOld) == 'tarde' ? 'selected' : '' }}>Tarde</option>
                    </select>
                </div>
            
                {{-- Incluir franjas "ambos" --}}
                <div class="flex flex-col justify-end h-full">
                    <label class="cursor-pointer flex items-center gap-2 ">
                        <input type="checkbox" name="incluir_ambos" class="checkbox" {{ old('incluir_ambos', $incluirAmbosOld) ? 'checked' : '' }}>
                        <span class="text-base-content/60">Mostrar franjas de ambos turnos</span>
                    </label>
                </div>

                {{-- Botón --}}
                <div class="flex items-end h-full">
                    <button class="btn btn-primary w-full" id="boton-buscar">Buscar</button>
                </div>
            </div>
        </form>

    @isset($aulaSeleccionada)
        <h2 class="text-xl font-semibold mt-6 mb-2">Horario semanal de {{ $aulaSeleccionada->nombre }}</h2>

        <p class="mb-4 text-sm text-gray-600">
            <span class="font-medium">Tipo:</span> {{ ucfirst($aulaSeleccionada->categoria->nombre ?? 'Sin categoría') }} —
            <span class="font-medium">Capacidad:</span> {{ $aulaSeleccionada->capacidad }} personas —
            <span class="font-medium">Edificio:</span> {{ $aulaSeleccionada->edificio }} — 
            <span class="font-medium">Planta:</span> {{ $aulaSeleccionada->planta }}
        </p>

        <div class="hidden md:block mt-4">
            @php
                $semanas = [];
                $diasES = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
                $inicioSemana = strtotime($inicio_semana);

                for ($i = 0; $i < 3; $i++) {
                    $inicio = strtotime("+$i week", $inicioSemana);
                    $dias = [];

                    for ($d = 0; $d < 5; $d++) {
                        $fecha = date('Y-m-d', strtotime("+$d day", $inicio));
                        $nombre = $diasES[date('w', strtotime($fecha))];
                        $dias[] = [
                            'nombre' => $nombre,
                            'fecha' => $fecha,
                            'hoy' => $fecha === $hoy,
                        ];
                    }

                    $semanas[] = $dias;
                }

                $horariosAgrupados = $horarios->groupBy('turno');
            @endphp
            @foreach ($semanas as $semanaIndex => $dias)
                <div class="collapse collapse-arrow bg-base-100 border-base-300 border">
                    <input type="radio" name="horario" id="semana-{{ $semanaIndex }}" {{ $semanaIndex === 0 ? 'checked' : '' }} />
                    <div class="collapse-title text-lg font-semibold">
                        Semana del {{ date('d/m', strtotime($dias[0]['fecha'])) }} al {{ date('d/m', strtotime(end($dias)['fecha'])) }}
                    </div>

                    @php
                        $diasNoLectivosSemana = collect($dias)->filter(function ($dia) use ($diasNoLectivos) {
                            return $diasNoLectivos->has($dia['fecha']);
                        });
                        $rowspans = [];
                        $diasNoLectivosPintados = [];

                        foreach ($dias as $dia) {
                            $rowspans[$dia['nombre']] = 0;
                            $rowspans[$dia['nombre']] += $horarios->count();
                        }
                    @endphp

                    <div class="collapse-content">
                        <div class="overflow-x-auto">
                            <table class="table table-bordered w-full mt-2">
                                <thead>
                                    <tr>
                                        <th class="bg-base-300 text-base-content/80 text-center w-24">Turno</th>
                                        <th class="bg-base-300 text-base-content/80 text-center">Hora</th>
                                        @foreach ($dias as $dia)
                                            <th class="text-center text-sm w-[16%] font-bold {{ $dia['hoy'] ? 'bg-accent/80 text-accent-content' : 'bg-base-300 text-base-content/80' }}">
                                                {{ date('d/m', strtotime($dia['fecha'])) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ocupadas = [];
                                    @endphp
                                    @foreach (['mañana', 'ambos', 'tarde'] as $turno)
                                        @if ($horariosAgrupados->has($turno))
                                            @php
                                                $filas = $horariosAgrupados[$turno];
                                                $mostrarTurno = $turno !== 'ambos';
                                            @endphp

                                            @foreach ($filas as $index => $franja)
                                                @php
                                                    $alto = 'h-16'; // altura estándar

                                                    if ($turno === 'ambos') {
                                                        $alto = 'h-25'; // más alto para "ambos"
                                                    } elseif (isset($recreos[$turno]) && $franja->id === $recreos[$turno]->id) {
                                                        $alto = 'h-10'; // más bajo si es el recreo
                                                    }
                                                    $esRecreo = isset($recreos[$turno]) && $franja->id === $recreos[$turno]->id;
                                                @endphp
                                                <tr class="{{ $alto }}">
                                                    {{-- Columna de Turno (solo en la primera fila de cada grupo y si no es "ambos") --}}
                                                    @if ($mostrarTurno && $index === 0)
                                                        <td class="bg-base-100 text-center font-semibold text-sm text-base-content/80 uppercase align-middle" rowspan="{{ $filas->count() }}">
                                                            {{ ucfirst($turno) }}
                                                        </td>
                                                    @elseif ($mostrarTurno === false)
                                                        <td class="bg-base-100" rowspan="{{ $filas->count() }}">
                                                            
                                                        </td>
                                                    @endif

                                                    {{-- Columna de hora --}}
                                                    <th class="w-32 text-sm text-center align-middle {{ $esRecreo ? 'bg-base-300 text-base-content/60' : 'bg-base-100 text-base-content/80' }}">
                                                        {{ substr($franja->hora_inicio, 0, 5) }} - {{ substr($franja->hora_fin, 0, 5) }}
                                                    </th>

                                                    {{-- Celdas de días --}}
                                                    @foreach ($dias as $dia)
                                                        @php
                                                            $horaInicioFranja = substr($franja->hora_inicio, 0, 5);
                                                            $key = $dia['fecha'] . '_' . $horaInicioFranja;

                                                            // Saltar si ya fue ocupada por rowspan anterior
                                                            if (in_array($key, $ocupadas)) {
                                                                continue;
                                                            }
                                                            
                                                            $keyClase = $dia['nombre'] . '_' . $horaInicioFranja;
                                                            $keyReserva = $dia['fecha'] . '_' . $horaInicioFranja;

                                                            $clase = $horariosClase[$keyClase] ?? null;
                                                            $reservasDelDia = $reservasSemana[$dia['fecha']] ?? collect();
                                                            $reserva = $reservasDelDia->first(function ($res) use ($franja) {
                                                                return $res->hora_inicio <= $franja->hora_inicio && $res->hora_fin >= $franja->hora_fin;
                                                            });
                                                            $noLectivo = $diasNoLectivos[$dia['fecha']] ?? null;
                                                            $diaYaPintado = in_array($dia['fecha'], $diasNoLectivosPintados ?? []);

                                                            // ROWSPAN para clases
                                                            $rowspanClase = 0;
                                                            if ($clase && substr($clase->hora_inicio, 0, 5) === $horaInicioFranja) {
                                                                $rowspanClase = $horarios->filter(function ($h) use ($clase) {
                                                                    return $h->hora_inicio >= $clase->hora_inicio && $h->hora_fin <= $clase->hora_fin;
                                                                })->count();

                                                                // Marcar como ocupadas
                                                                foreach ($horarios as $h) {
                                                                    if ($h->hora_inicio >= $clase->hora_inicio && $h->hora_fin <= $clase->hora_fin) {
                                                                        $ocupadas[] = $dia['fecha'] . '_' . substr($h->hora_inicio, 0, 5);
                                                                    }
                                                                }
                                                            }

                                                            // ROWSPAN para reservas
                                                            $rowspanReserva = 0;
                                                            if ($reserva && substr($reserva->hora_inicio, 0, 5) === $horaInicioFranja) {
                                                                $rowspanReserva = $horarios->filter(function ($h) use ($reserva) {
                                                                    return $h->hora_inicio >= $reserva->hora_inicio && $h->hora_fin <= $reserva->hora_fin;
                                                                })->count();

                                                                // Marcar como ocupadas
                                                                foreach ($horarios as $h) {
                                                                    if ($h->hora_inicio >= $reserva->hora_inicio && $h->hora_fin <= $reserva->hora_fin) {
                                                                        $ocupadas[] = $dia['fecha'] . '_' . substr($h->hora_inicio, 0, 5);
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @if ($noLectivo && !$diaYaPintado && $index === 0 && $loop->parent->first)
                                                            <td rowspan="{{ $rowspans[$dia['nombre']] }}"
                                                                class="bg-warning/80 text-warning-content font-semibold text-xs text-center align-middle w-[16%]">
                                                                {{ $noLectivo->descripcion }}
                                                            </td>
                                                            @php $diasNoLectivosPintados[] = $dia['fecha']; @endphp
                                                        @elseif (!$noLectivo)
                                                        {{-- Mostrar contenido normal --}}
                                                            @if ($clase)
                                                                <td class="text-center align-middle bg-primary text-primary-content text-sm font-medium w-[16%]">
                                                                    {{ \App\Models\User::find($clase->user_id)?->codigo ?? '—' }}
                                                                </td>
                                                            @elseif ($reserva && $rowspanReserva > 0)
                                                                <td rowspan="{{ $rowspanReserva }}" class="text-center align-middle bg-secondary text-secondary-content text-sm font-medium w-[16%]">
                                                                    {{ \App\Models\User::find($reserva->user_id)?->codigo ?? '—' }}
                                                                </td>
                                                            @else
                                                                {{-- Celda sin clase --}}
                                                                <td class="text-center align-middle w-[16%]
                                                                    {{ $esRecreo && $dia['hoy'] ? 'bg-accent/50' :
                                                                    ($dia['hoy'] ? 'bg-accent' :
                                                                    ($esRecreo ? 'bg-base-300' : 'bg-base-100')) }}">
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Horario en versión móvil con acordeones (solo visible en pantallas pequeñas) --}}
        <div class="block md:hidden mt-6 space-y-4">
            @foreach ($semanas as $semanaIndex => $dias)
                <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-lg">
                    <input type="radio" name="semana" id="semana-{{ $semanaIndex }}" />
                    <div class="collapse-title font-semibold text-base">
                        Semana del {{ date('d/m', strtotime($dias[0]['fecha'])) }} al {{ date('d/m', strtotime(end($dias)['fecha'])) }}
                    </div>

                    <div class="collapse-content space-y-2">
                        @foreach ($dias as $dia)
                            <div class="collapse collapse-arrow bg-base-200 border border-base-300 rounded-lg">
                                <input type="radio" name="dia" id="dia-{{ $dia['fecha'] }}" />
                                <div class="collapse-title flex justify-between items-center text-sm font-medium">
                                    <span>{{ ucfirst($dia['nombre']) }} {{ date('d/m', strtotime($dia['fecha'])) }}</span>
                                    @if ($dia['hoy'])
                                        <span class="badge badge-accent text-xs">Hoy</span>
                                    @endif
                                </div>

                                <div class="collapse-content">
                                    @php
                                        $noLectivo = $diasNoLectivos[$dia['fecha']] ?? null;
                                    @endphp

                                    @if ($noLectivo)
                                        <div class="text-warning-content bg-warning p-2 rounded text-sm font-medium">
                                            {{ $noLectivo->descripcion }}
                                        </div>
                                    @else
                                        @foreach (['mañana', 'ambos', 'tarde'] as $turno)
                                            @php
                                                $franjas = $horariosAgrupados[$turno] ?? [];
                                            @endphp

                                            @if ($franjas->isNotEmpty())
                                                <div class="mt-3">
                                                    <div class="text-xs text-base-content/70 font-semibold uppercase mb-1">
                                                        {{ ucfirst($turno) }}
                                                    </div>

                                                    @foreach ($franjas as $franja)
                                                        @php
                                                            $horaInicio = substr($franja->hora_inicio, 0, 5);
                                                            $horaFin = substr($franja->hora_fin, 0, 5);
                                                            $esRecreo = isset($recreos[$turno]) && $franja->id === $recreos[$turno]->id;

                                                            $keyClase = $dia['nombre'] . '_' . $horaInicio;
                                                            $clase = $horariosClase[$keyClase] ?? null;

                                                            $reservasDelDia = $reservasSemana[$dia['fecha']] ?? collect();
                                                            $reserva = $reservasDelDia->first(function ($res) use ($franja) {
                                                                return $res->hora_inicio <= $franja->hora_inicio && $res->hora_fin >= $franja->hora_fin;
                                                            });

                                                            $bgClass = 'bg-base-100';
                                                            if ($clase) {
                                                                $bgClass = 'bg-primary text-primary-content';
                                                            } elseif ($reserva) {
                                                                $bgClass = 'bg-secondary text-secondary-content';
                                                            } elseif ($esRecreo) {
                                                                $bgClass = 'bg-base-300';
                                                            }

                                                            $userClase = $clase ? \App\Models\User::find($clase->user_id)?->codigo : null;
                                                            $userReserva = $reserva ? \App\Models\User::find($reserva->user_id)?->codigo : null;
                                                        @endphp

                                                        <div class="border rounded p-2 mb-2 text-sm {{ $bgClass }}">
                                                            <div class="flex justify-between">
                                                                <span class="font-medium">{{ $horaInicio }} - {{ $horaFin }}</span>
                                                                @if ($esRecreo)
                                                                    <span class="italic text-xs">Recreo</span>
                                                                @endif
                                                            </div>
                                                            @if ($userClase)
                                                                <div class="mt-1">{{ $userClase }}</div>
                                                            @elseif ($userReserva)
                                                                <div class="mt-1">Reserva: {{ $userReserva }}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endisset

    @auth
        @if(auth()->user()->rol === 'profesor' && isset($aulaSeleccionada))
            <h2 class="text-xl font-semibold mt-10 mb-2">Realizar una reserva en esta aula</h2>

            <form method="POST" action="{{ route('reservas.store') }}">
                @csrf

                {{-- Oculto el aula_id ya seleccionada y los datos de la busqueda --}}
                <input type="hidden" name="aula_id" value="{{ $aulaOld }}">
                <input type="hidden" name="categoria_id" value="{{ $categoriaOld }}">
                <input type="hidden" name="turno" value="{{ $turnoOld }}">
                <input type="hidden" name="incluir_ambos" value="{{ $incluirAmbosOld }}">
                <input type="hidden" name="capacidad" value="{{ $capacidadOld }}">
                <input type="hidden" name="edificio" value="{{ $edificioOld }}">
                <input type="hidden" name="planta" value="{{ $plantaOld }}">

                {{-- Mensaje error horas --}}
                <div class="form-control col-span-full">
                    <p id="mensaje-hora-error" class="text-sm text-error mt-1 hidden"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    {{-- Fecha --}}
                    <div class="form-control">
                        <label class="label">Fecha</label>
                        @php
                            $minFecha = $hoy;
                            $maxFecha = date('Y-m-d', strtotime('friday this week', strtotime($fin_semana_2)));
                        @endphp
                        <input
                            type="date"
                            name="fecha"
                            class="input input-bordered w-full"
                            min="{{ $minFecha }}"
                            max="{{ $maxFecha }}"
                            required
                            value="{{ old('fecha') }}"
                        >
                    </div>

                    {{-- Uso --}}
                    <div class="form-control">
                        <label class="label">Uso</label>
                        <select name="uso" class="select select-bordered w-full" required>
                            @foreach (['clase', 'examen', 'charla', 'taller', 'otro'] as $opcion)
                                <option value="{{ $opcion }}" {{ old('uso') == $opcion ? 'selected' : '' }}>
                                    {{ ucfirst($opcion) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hora inicio --}}
                    <div class="form-control">
                        <label class="label">Hora de inicio</label>
                        <select id="hora_inicio" name="hora_inicio" class="select select-bordered w-full" required>
                            @foreach ($horarios as $h)
                                @php $hora = date('H:i', strtotime($h->hora_inicio)); @endphp
                                <option value="{{ $hora }}" {{ old('hora_inicio') == $hora ? 'selected' : '' }}>
                                    {{ $hora }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hora fin --}}
                    <div class="form-control">
                        <label class="label">Hora de fin</label>
                        <select id="hora_fin" name="hora_fin" class="select select-bordered w-full" required>
                            @foreach ($horarios as $h)
                                @php $hora = date('H:i', strtotime($h->hora_fin)); @endphp
                                <option value="{{ $hora }}" {{ old('hora_fin') == $hora ? 'selected' : '' }}>
                                    {{ $hora }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botón --}}
                    <div class="flex items-end h-full">
                        <button id="boton-reservar" class="btn btn-primary w-full">Reservar aula</button>
                    </div>
                </div>
            </form>
            @endif
        @endauth
    </div>
            

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const todasLasAulas = @json($todasLasAulas);
            const categoriaSelect = document.getElementById('categoria_id');
            const aulaSelect = document.getElementById('aula_id');
            const edificioInput = document.getElementById('edificio');
            const plantaInput = document.getElementById('planta');
            const capacidadInput = document.getElementById('capacidad');
            const mensajeSinAulas = document.getElementById('mensaje-sin-aulas');
            const botonBuscar = document.getElementById('boton-buscar');
        
            const horaInicioInput = document.getElementById('hora_inicio');
            const horaFinInput = document.getElementById('hora_fin');
            const mensajeHoraError = document.getElementById('mensaje-hora-error');  // Aquí se muestra el mensaje de error de horas
            const botonReservar = document.getElementById('boton-reservar');

            const aulaSeleccionada = "{{ old('aula_id', $aulaOld) }}";
        
            function filtrarAulas() {
                const categoria = categoriaSelect.value;
                const edificio = edificioInput?.value;
                const planta = plantaInput?.value;
                const capacidadMin = parseInt(capacidadInput?.value);

                // Limpiar el select de aulas
                while (aulaSelect.firstChild) {
                    aulaSelect.removeChild(aulaSelect.firstChild);
                }

                // Agregar opción por defecto manualmente
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Selecciona un aula';
                aulaSelect.appendChild(defaultOption);
                mensajeSinAulas?.classList.add('hidden');
        
                const opcionesFiltradas = todasLasAulas.filter(aula => {
                    const coincideCategoria = !categoria || aula.categoria_id == categoria;
                    const coincideEdificio = !edificio || aula.edificio === edificio;
                    const coincidePlanta = !planta || aula.planta === planta;
                    const coincideCapacidad = !capacidadMin || aula.capacidad >= capacidadMin;
                    return coincideCategoria && coincideEdificio && coincidePlanta && coincideCapacidad;
                });
        
                opcionesFiltradas.forEach(aula => {
                    const option = document.createElement('option');
                    option.value = aula.id;
                    option.textContent = `${aula.codigo} - ${aula.nombre}`;
                    if (aula.id == aulaSeleccionada) option.selected = true;
                    aulaSelect.appendChild(option);
                });
        
                if (opcionesFiltradas.length === 0) {
                    mensajeSinAulas?.classList.remove('hidden');
                    mensajeSinAulas.textContent = 'No hay aulas que coincidan con los filtros';
                    botonBuscar?.setAttribute('disabled', 'disabled');
                } else {
                    mensajeSinAulas?.classList.add('hidden');
                    botonBuscar?.removeAttribute('disabled');
                }
            }

            function validarHoras() {
                const horaInicio = horaInicioInput.value;
                const horaFin = horaFinInput.value;

                // Convertimos las horas a objetos Date para poder compararlas
                const startTime = new Date(`1970-01-01T${horaInicio}:00`);
                const endTime = new Date(`1970-01-01T${horaFin}:00`);

                // Si la hora de fin es anterior o igual a la de inicio
                if (endTime <= startTime) {
                    // Mostrar mensaje de error
                    mensajeHoraError?.classList.remove('hidden');
                    mensajeHoraError.textContent = 'La hora de fin debe ser posterior a la hora de inicio.';
                    botonReservar?.setAttribute('disabled', 'disabled');
                } else {
                    // Si las horas son válidas, ocultar el mensaje de error
                    mensajeHoraError?.classList.add('hidden');
                    botonReservar?.removeAttribute('disabled');
                }
            }
            
            capacidadInput?.addEventListener('input', filtrarAulas);
        
            [categoriaSelect, edificioInput, plantaInput, capacidadInput].forEach(input => {
                input?.addEventListener('change', filtrarAulas);
            });

            horaInicioInput?.addEventListener('change', validarHoras);
            horaFinInput?.addEventListener('change', validarHoras);
        
            filtrarAulas(); // Ejecutar al cargar

            document.querySelectorAll('.close-alert').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.alert').remove();
                });
            });
        });
    </script>
@endsection
