@extends('layouts.admin-centro')

@section('title', 'Detalle del Historial Clínico')

@section('content')

<div class="card">
    <h2 class="text-2xl font-bold mb-4">Detalle del Historial Clínico</h2>

    <h3>Paciente: 
        {{ $historial->paciente->primer_nombre }}
        {{ $historial->paciente->primer_apellido }}
        {{ $historial->paciente->segundo_apellido }}
    </h3>
    <p><strong>Fecha de Creación:</strong> {{ $historial->fecha_creacion }}</p>

    <!-- Anamnesis -->
    <div class="section anamnesis-section">
        <h4 class="section-title">1. Anamnesis</h4>
        @if ($historial->anamnesis->isEmpty())
        <p style="color: #718096;">No hay registros de anamnesis.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->anamnesis as $anamnesis)
            <li class="card-item">
                <strong>Fecha de creación:</strong> {{ $anamnesis->fecha_creacion }} <br>
                <strong>Descripción:</strong>
                <ul class="list-disc pl-5">
                    @foreach (explode("\n", $anamnesis->descripcion) as $line)
                    <li>{{ $line }}</li>
                    @endforeach
                </ul>
                <div style="margin-top: 10px;">
                    <a href="{{ route('anamnesis.edit', ['idHistorial' => $historial->id_historial, 'idAnamnesis' => $anamnesis->id_anamnesis]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                    </a>

                    <button class="btn-delete2"
                        data-url="{{ route('anamnesis.destroy', ['idHistorial' => $historial->id_historial, 'idAnamnesis' => $anamnesis->id_anamnesis]) }}"
                        style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>

                </div>
            </li>
            @endforeach
        </ul>
        @endif
        <a href="{{ route('anamnesis.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Agregar Anamnesis
        </a>
    </div>

    <!-- Script para eliminar anamnesis -->
    <script>
        document.querySelectorAll('.btn-delete2').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const listItem = this.closest('li'); // Encuentra el <li> de la anamnesis

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        'La anamnesis ha sido eliminada correctamente.',
                                        'success'
                                    );
                                    listItem.remove(); // Eliminar del DOM
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar la anamnesis.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Error',
                                    'Hubo un problema al eliminar la anamnesis.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    </script>




    <!-- Alergias -->
    <div class="section alergias-section">
        <h4 class="section-title">2. Alergias</h4>
        @if ($historial->alergias->isEmpty())
        <p style="color: #718096;">No hay alergias registradas.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->alergias as $alergia)
            <li class="card-item">
                <strong>Tipo:</strong> {{ $alergia->tipo }} <br>
                <strong>Descripción:</strong> {{ $alergia->descripcion ?? 'Sin descripción' }} <br>
                <strong>Severidad:</strong>
                <span
                    style="
                        padding: 5px 10px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: bold;
                        color: #ffffff;
                        {{ $alergia->severidad === 'Alta'
                            ? 'background-color: #e74c3c;'
                            : ($alergia->severidad === 'Media'
                                ? 'background-color: #f39c12;'
                                : 'background-color: #2ecc71;') }}">
                    {{ $alergia->severidad }}
                </span>
                <div style="margin-top: 10px;">
                    <a href="{{ route('alergias.edit', [$historial->paciente->id_paciente, $alergia->id_alergia]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                    </a>
                    <button class="btn-delete"
                        data-url="{{ route('alergias.destroy', [$historial->paciente->id_paciente, $alergia->id_alergia]) }}"
                        style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
        <a href="{{ route('alergias.create', $historial->paciente->id_paciente) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Alergia
        </a>
    </div>

    <!-- Script para eliminar alergias -->
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.getAttribute('data-url'); // Obtiene la URL de eliminación
                const listItem = this.closest('li'); // Encuentra el <li> correspondiente

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json()) // Convertir respuesta a JSON
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        'La alergia ha sido eliminada correctamente.',
                                        'success'
                                    );
                                    listItem.remove(); // Elimina el elemento del DOM sin recargar
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar la alergia.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Error',
                                    'Hubo un problema al eliminar la alergia.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    </script>


    <!-- Sección de vacunas -->
    <div class="section vacunas-section" style="margin-bottom: 30px;">
        <h4 class="section-title">2. Vacunas</h4>
        @if ($historial->vacunas->isEmpty())
        <p style="color: #718096;">No hay vacunas registradas.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->vacunas as $vacuna)
            <li id="vacuna-{{ $vacuna->id_vacuna }}"
                style="background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 10px;">
                <strong style="color: #4a5568;">Nombre:</strong> {{ $vacuna->nombre_vacuna }} <br>
                <strong style="color: #4a5568;">Fecha de Aplicación:</strong> {{ $vacuna->fecha_aplicacion }}
                <br>
                <strong style="color: #4a5568;">Dosis:</strong> {{ $vacuna->dosis }} <br>
                <strong style="color: #4a5568;">Próxima Dosis:</strong>
                {{ $vacuna->proxima_dosis ?? 'No programada' }} <br>
                @if ($vacuna->observaciones)
                <strong style="color: #4a5568;">Observaciones:</strong> {{ $vacuna->observaciones }} <br>
                @endif
                <div style="margin-top: 10px;">
                    <a href="{{ route('vacunas.edit', [$historial->id_historial, $vacuna->id_vacuna]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">Editar</a>
                    <button
                        onclick="eliminarVacuna('{{ $vacuna->id_vacuna }}', '{{ $historial->id_historial }}')"
                        style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
        <a href="{{ route('vacunas.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Vacuna
        </a>
    </div>

    <!-- Script para la eliminación asíncrona -->

    <script>
        function eliminarVacuna(idVacuna, idHistorial) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener el token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    // Realizar la petición AJAX usando la ruta correcta con el prefijo 'vacunas'
                    fetch(`/vacunas/${idHistorial}/${idVacuna}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'La vacuna ha sido eliminada correctamente.',
                                    'success'
                                );

                                // Eliminar el elemento del DOM
                                const elementoVacuna = document.getElementById(`vacuna-${idVacuna}`);
                                if (elementoVacuna) {
                                    elementoVacuna.remove();
                                }

                                // Verificar si quedan vacunas
                                const listaVacunas = document.querySelector('.vacunas-section ul');
                                if (listaVacunas && listaVacunas.children.length === 0) {
                                    // Si no quedan vacunas, mostrar el mensaje de "No hay vacunas registradas"
                                    const contenedorVacunas = document.querySelector('.vacunas-section');
                                    const mensajeNoVacunas = document.createElement('p');
                                    mensajeNoVacunas.style.color = '#718096';
                                    mensajeNoVacunas.textContent = 'No hay vacunas registradas.';
                                    listaVacunas.replaceWith(mensajeNoVacunas);
                                }
                            } else {
                                Swal.fire(
                                    'Error',
                                    'No se pudo eliminar la vacuna.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error',
                                'Hubo un problema al eliminar la vacuna.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>


    <!-- Consultas FALTA AQUI -->
    <div class="section consultas-section">
        <h4 class="section-title">4. Consultas</h4>
        @if ($historial->consultas->isEmpty())
        <p>No hay consultas registradas.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->consultas as $consulta)
            <li class="card-item">
                <strong>Fecha:</strong> {{ $consulta->fecha_consulta }} <br>
                <strong>Médico:</strong> {{ $consulta->personalMedico->usuario->nombre ?? 'No registrado' }}
                <br>
                <strong>Motivo:</strong> {{ $consulta->motivo_consulta }} <br>
                <strong>Síntomas:</strong> {{ $consulta->sintomas ?? 'Sin síntomas registrados' }} <br>
                <strong>Observaciones:</strong> {{ $consulta->observaciones ?? 'Sin observaciones' }} <br>
                <div style="margin-top: 10px;">
                    <!-- Botón Editar -->
                    <a href="{{ route('consultas.edit', [$historial->id_historial, $consulta->id_consulta]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                    </a>
                    <!-- Botón Añadir Archivo -->
                    <a href="{{ route('archivos.create', ['idHistorial' => $historial->id_historial, 'idConsulta' => $consulta->id_consulta]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-left: 5px;">
                        Añadir Archivo
                    </a>
                    <!-- Botón Ver Archivos -->
                    <button class="btn btn-view mt-2 toggle-files"
                        data-target="consulta-files-{{ $consulta->id_consulta }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #16a34a; color: #ffffff; border: none; border-radius: 4px; margin-left: 5px;">
                        Ver Archivos
                    </button>
                </div>

                <!-- Archivos Adjuntos 
                <div id="consulta-files-{{ $consulta->id_consulta }}" class="files-section hidden">
                    <h5 style="font-size: 16px; margin-top: 10px; color: #2d3748;">Archivos Adjuntos</h5>
                    @if ($consulta->archivos->isEmpty())
                    <p style="color: #718096;">No hay archivos adjuntos.</p>
                    @else
                    <ul>
                        @foreach ($consulta->archivos as $archivo)
                        <li style="margin-bottom: 10px;">
                            <strong>Nombre:</strong> {{ $archivo->nombre_archivo }} <br>
                            <div class="file-container">
                                @if (Str::endsWith($archivo->ruta_archivo, ['.jpg', '.jpeg', '.png', '.gif']))
                                <img src="{{ asset('storage/' . $archivo->ruta_archivo) }}"
                                    alt="Archivo" class="responsive-img">
                                @elseif (Str::endsWith($archivo->ruta_archivo, ['.pdf']))
                                <embed src="{{ asset('storage/' . $archivo->ruta_archivo) }}"
                                    type="application/pdf" width="100%" height="500px">
                                <br>
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}"
                                    target="_blank"
                                    style="display: inline-block; padding: 6px 12px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 10px;">
                                    Abrir PDF
                                </a>
                                @else
                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}"
                                    target="_blank"
                                    style="display: inline-block; padding: 6px 12px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 10px;">
                                    Descargar Archivo
                                </a>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div> -->

            </li>
            @endforeach
        </ul>
        @endif
        <!-- Botón Añadir Consulta -->
        <a href="{{ route('consultas.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Consulta
        </a>
    </div>



    <!-- Cirugías -->
    <div class="section cirugias-section" style="margin-bottom: 30px;">
        <h4 class="section-title">5. Cirugías</h4>
        @if ($historial->cirugias->isEmpty())
        <p style="color: #718096;">No hay cirugías registradas.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->cirugias as $cirugia)
            <li
                style="background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 10px;">
                <strong style="color: #4a5568;">Tipo de Cirugía:</strong> {{ $cirugia->tipo_cirugia }} <br>
                <strong style="color: #4a5568;">Fecha de Cirugía:</strong> {{ $cirugia->fecha_cirugia }} <br>
                <strong style="color: #4a5568;">Cirujano:</strong>
                {{ $cirugia->cirujano ?? 'No especificado' }} <br>
                <strong style="color: #4a5568;">Descripción:</strong>
                {{ $cirugia->descripcion ?? 'Sin descripción' }} <br>
                <strong style="color: #4a5568;">Complicaciones:</strong>
                {{ $cirugia->complicaciones ?? 'Sin complicaciones' }} <br>
                <strong style="color: #4a5568;">Notas Postoperatorias:</strong>
                {{ $cirugia->notas_postoperatorias ?? 'No hay notas' }} <br>
                <div style="margin-top: 10px;">
                    @if ($cirugia->created_at->diffInHours(now()) <= 24)
                        <a href="{{ route('cirugias.edit', [$historial->id_historial, $cirugia->id_cirugia]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                        </a>
                        @endif
                </div>
            </li>
            @endforeach
        </ul>
        @endif
        <a href="{{ route('cirugias.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Cirugía
        </a>
    </div>



    <!-- Diagnósticos -->
    <div class="section diagnosticos-section">
        <h4 class="section-title">6. Diagnósticos</h4>

        @if ($historial->diagnostico->isEmpty())
        <p style="color: #718096;">No hay diagnósticos registrados.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->diagnostico as $diagnostico)
            <li id="diagnostico-{{ $diagnostico->id_diagnostico }}" class="card-item">
                <div style="margin-bottom: 10px;">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($diagnostico->fecha_creacion)->format('Y-m-d') }} <br>
                    <strong>Grupo:</strong> {{ $diagnostico->cie10->grupo ?? 'N/A' }} <br>
                    <strong>Categoria: </strong>{{ $diagnostico->cie10->categoria ?? 'N/A' }} <br>
                    <strong>SubCategoria: </strong> {{ $diagnostico->cie10->subcategoria ?? 'N/A' }}
                </div>

                @if (now()->diffInHours($diagnostico->created_at) <= 24)
                    <a href="{{ route('diagnosticos.edit', [$diagnostico->id_historial, $diagnostico->id_diagnostico]) }}"
                    style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                    Editar
                    </a>
                    <button onclick="eliminarDiagnostico('{{ $diagnostico->id_diagnostico }}', '{{ $historial->id_historial }}')"
                        style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                        Eliminar
                    </button>
                    @endif
            </li>
            @endforeach
        </ul>
        @endif

        <a href="{{ route('diagnosticos.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #1e40af; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Diagnóstico
        </a>
    </div>

    <!-- Script para eliminar diagnósticos con SweetAlert -->
    <script>
        function eliminarDiagnostico(idDiagnostico, idHistorial) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener el token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(`/diagnosticos/${idHistorial}/${idDiagnostico}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El diagnóstico ha sido eliminado correctamente.',
                                    'success'
                                );

                                // Eliminar el elemento del DOM
                                const elementoDiagnostico = document.getElementById(`diagnostico-${idDiagnostico}`);
                                if (elementoDiagnostico) {
                                    elementoDiagnostico.remove();
                                }

                                // Verificar si quedan diagnósticos
                                const listaDiagnosticos = document.querySelector('.diagnosticos-section ul');
                                if (listaDiagnosticos && listaDiagnosticos.children.length === 0) {
                                    // Si no quedan diagnósticos, mostrar el mensaje "No hay diagnósticos registrados"
                                    const contenedorDiagnosticos = document.querySelector('.diagnosticos-section');
                                    const mensajeNoDiagnosticos = document.createElement('p');
                                    mensajeNoDiagnosticos.style.color = '#718096';
                                    mensajeNoDiagnosticos.textContent = 'No hay diagnósticos registrados.';
                                    listaDiagnosticos.replaceWith(mensajeNoDiagnosticos);
                                }
                            } else {
                                Swal.fire(
                                    'Error',
                                    'No se pudo eliminar el diagnóstico.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error',
                                'Hubo un problema al eliminar el diagnóstico.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>



    <!-- 7. Exámenes Médicos -->
    <div class="section examenes-section">
        <h4 class="section-title">7. Exámenes Médicos</h4>
        @if ($historial->examenesMedicos->isEmpty())
        <p style="color: #718096;">No hay exámenes médicos registrados.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->examenesMedicos as $examen)
            <li id="examen-{{ $examen->id_examen }}" class="card-item"
                style="margin-bottom: 10px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px;">
                <strong>Tipo de Examen:</strong> {{ $examen->tipo_examen }} <br>
                <strong>Descripción:</strong> {{ $examen->descripcion ?? 'No especificada' }} <br>
                <strong>Fecha del Examen:</strong> {{ $examen->fecha_examen }} <br>
                <strong>Resultados:</strong> {{ $examen->resultados ?? 'Sin resultados' }} <br>

                <!-- Botones de acción -->
                <div style="margin-top: 10px;">
                    <a href="{{ route('examenes.edit', [$historial->id_historial, $examen->id_examen]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                    </a>

                    <!-- Botón para eliminar examen -->
                    <button onclick="eliminarExamen('{{ $examen->id_examen }}', '{{ $historial->id_historial }}')"
                        style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
                        Eliminar
                    </button>
                </div>
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Botón para añadir examen médico -->
        <a href="{{ route('examenes.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Examen Médico
        </a>
    </div>

    <script>
        function eliminarExamen(idExamen, idHistorial) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener el token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    // Petición AJAX para eliminar examen
                    fetch(`/historial/${idHistorial}/examenes/${idExamen}`, { // URL CORREGIDA
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Eliminar el examen del DOM
                                document.getElementById(`examen-${idExamen}`).remove();

                                // Verificar si quedan exámenes
                                const listaExamenes = document.querySelector('.examenes-section ul');
                                if (listaExamenes && listaExamenes.children.length === 0) {
                                    const contenedorExamenes = document.querySelector('.examenes-section');
                                    const mensajeNoExamenes = document.createElement('p');
                                    mensajeNoExamenes.style.color = '#718096';
                                    mensajeNoExamenes.textContent = 'No hay exámenes médicos registrados.';
                                    listaExamenes.replaceWith(mensajeNoExamenes);
                                }

                                // Mostrar mensaje de éxito con SweetAlert
                                Swal.fire({
                                    title: "Eliminado",
                                    text: "El examen ha sido eliminado exitosamente",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                throw new Error("Error en la respuesta del servidor.");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: "Error",
                                text: "Hubo un problema al eliminar el examen",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>



    <!-- 8. Recetas -->
    <div class="section recetas-section">
        <h4 class="section-title">8. Recetas</h4>

        @if ($historial->recetas->isEmpty())
        <p style="color: #718096;">No hay recetas registradas.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->recetas as $receta)
            <li id="receta-{{ $receta->id_receta }}" class="card-item">
                <strong>Fecha de la Receta:</strong> {{ $receta->fecha_receta }} <br>
                <strong>Médico:</strong> {{ $receta->personalMedico->usuario->nombre ?? 'No registrado' }} <br>

                <!-- Botones de acción -->
                <div style="margin-top: 10px;">
                    @if (now()->diffInHours($receta->created_at) <= 24)
                        <a href="{{ route('recetas.edit', [$receta->id_historial, $receta->id_receta]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                        </a>
                        <button onclick="eliminarReceta('{{ $receta->id_receta }}', '{{ $historial->id_historial }}')"
                            style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                            Eliminar
                        </button>
                        @endif
                        <button class="toggle-medicamentos" data-target="medicamentos-{{ $receta->id_receta }}"
                            style="display: inline-block; padding: 6px 12px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                            Ver Medicamentos
                        </button>
                        <a href="{{ route('medicamentos.create', $receta->id_receta) }}"
                            style="display: inline-block; padding: 6px 12px; background-color: #22c55e; color: #ffffff; text-decoration: none; border-radius: 4px;">
                            Añadir Medicamento
                        </a>
                </div>

                <!-- Sección de Medicamentos -->
                <div id="medicamentos-{{ $receta->id_receta }}" class="medicamentos-section hidden"
                    style="margin-top: 10px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px;">
                    @if ($receta->medicamentos->isEmpty())
                    <p style="color: #718096;">No hay medicamentos registrados en esta receta.</p>
                    @else
                    <ul style="list-style-type: none; padding: 0;">
                        @foreach ($receta->medicamentos as $medicamento)
                        <li style="margin-bottom: 10px; border: 1px solid #d1d5db; border-radius: 4px; padding: 10px; background-color: #f9fafb;">
                            <strong>Medicamento:</strong> {{ $medicamento->medicamento }} <br>
                            <strong>Dosis:</strong> {{ $medicamento->dosis }} <br>
                            <strong>Frecuencia:</strong> {{ $medicamento->frecuencia }} <br>
                            <strong>Duración:</strong> {{ $medicamento->duracion }} <br>
                            <strong>Instrucciones:</strong> {{ $medicamento->instrucciones ?? 'Sin instrucciones' }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Botón para añadir receta -->
        <a href="{{ route('recetas.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #e11d48; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Receta
        </a>
    </div>

    <!-- Script para eliminar recetas junto con sus medicamentos -->
    <script>
        function eliminarReceta(idReceta, idHistorial) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará la receta y todos los medicamentos asociados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(`/recetas/${idHistorial}/${idReceta}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'La receta y sus medicamentos han sido eliminados correctamente.',
                                    'success'
                                );

                                // Eliminar la receta del DOM
                                const elementoReceta = document.getElementById(`receta-${idReceta}`);
                                if (elementoReceta) {
                                    elementoReceta.remove();
                                }

                                // Verificar si quedan recetas
                                const listaRecetas = document.querySelector('.recetas-section ul');
                                if (listaRecetas && listaRecetas.children.length === 0) {
                                    const contenedorRecetas = document.querySelector('.recetas-section');
                                    const mensajeNoRecetas = document.createElement('p');
                                    mensajeNoRecetas.style.color = '#718096';
                                    mensajeNoRecetas.textContent = 'No hay recetas registradas.';
                                    listaRecetas.replaceWith(mensajeNoRecetas);
                                }
                            } else {
                                Swal.fire(
                                    'Error',
                                    'No se pudo eliminar la receta y sus medicamentos.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error',
                                'Hubo un problema al eliminar la receta y sus medicamentos.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>

    <!-- Script para mostrar/ocultar medicamentos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-medicamentos').forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.getElementById(this.getAttribute('data-target'));
                    if (target) {
                        target.classList.toggle('hidden');
                    }
                });
            });
        });
    </script>

    <style>
        .hidden {
            display: none;
        }
    </style>

    <!-- 9. Tratamientos -->
    <div class="section tratamientos-section">
        <h4 class="section-title">9. Tratamientos</h4>
        @if ($historial->tratamientos->isEmpty())
        <p style="color: #718096;">No hay tratamientos registrados.</p>
        @else
        <ul style="list-style-type: none; padding: 0;">
            @foreach ($historial->tratamientos as $tratamiento)
            <li id="tratamiento-{{ $tratamiento->id_tratamiento }}" class="card-item"
                style="margin-bottom: 10px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px;">
                <strong>Descripción:</strong> {{ $tratamiento->descripcion }} <br>
                <strong>Fecha:</strong> {{ $tratamiento->fecha_creacion }}

                <!-- Botones de acción -->
                <div style="margin-top: 10px;">
                    @if (now()->diffInHours($tratamiento->created_at) <= 24)
                        <a href="{{ route('tratamientos.edit', [$tratamiento->id_historial, $tratamiento->id_tratamiento]) }}"
                        style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: #ffffff; text-decoration: none; border-radius: 4px; margin-right: 5px;">
                        Editar
                        </a>
                        @endif

                        <!-- Botón de eliminación con SweetAlert -->
                        <button onclick="eliminarTratamiento('{{ $tratamiento->id_tratamiento }}', '{{ $historial->id_historial }}')"
                            style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; cursor: pointer;">
                            Eliminar
                        </button>
                </div>
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Botón para añadir tratamiento -->
        <a href="{{ route('tratamientos.create', $historial->id_historial) }}"
            style="display: inline-block; padding: 10px 20px; background-color: #10b981; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 15px;">
            Añadir Tratamiento
        </a>
    </div>

    <!-- SweetAlert + AJAX para eliminar tratamiento -->
    <script>
        function eliminarTratamiento(idTratamiento, idHistorial) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener el token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    // Petición AJAX para eliminar tratamiento
                    fetch(`/tratamientos/${idHistorial}/${idTratamiento}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Eliminar el tratamiento del DOM
                                document.getElementById(`tratamiento-${idTratamiento}`).remove();

                                // Verificar si quedan tratamientos
                                const listaTratamientos = document.querySelector('.tratamientos-section ul');
                                if (listaTratamientos && listaTratamientos.children.length === 0) {
                                    const contenedorTratamientos = document.querySelector('.tratamientos-section');
                                    const mensajeNoTratamientos = document.createElement('p');
                                    mensajeNoTratamientos.style.color = '#718096';
                                    mensajeNoTratamientos.textContent = 'No hay tratamientos registrados.';
                                    listaTratamientos.replaceWith(mensajeNoTratamientos);
                                }

                                // Mostrar mensaje de éxito con SweetAlert
                                Swal.fire({
                                    title: "Eliminado",
                                    text: "El tratamiento ha sido eliminado exitosamente",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: "Error",
                                text: "Hubo un problema al eliminar el tratamiento",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>


    <!-- Triajes 
    <div class="section triaje-section">
        <h4 class="section-title">10. Triajes</h4>
        @if ($historial->triaje->isEmpty())
        <p style="color: #6b7280;">No hay registros de triaje.</p>
        @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Fecha</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Peso</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Talla</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">IMC</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Temperatura
                        </th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Presión
                            Arterial</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Frecuencia
                            Cardíaca</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e5e7eb; text-align: left;">Frecuencia
                            Respiratoria</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historial->triaje as $triaje)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $triaje->fecha_triaje }}
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $triaje->peso }} kg
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $triaje->talla }} m
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $triaje->imc }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $triaje->temperatura }} °C
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $triaje->presion_arterial }}
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $triaje->frecuencia_cardiaca }} LPM
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                            {{ $triaje->frecuencia_respiratoria }} RPM
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div> -->

    <!-- Archivos Adjuntos 
    <div class="section archivos-section">
        <h4 class="section-title">11. Archivos Adjuntos</h4>
        @if ($historial->archivosAdjuntos->isEmpty())
        <p>No hay archivos adjuntos registrados.</p>
        @else
        <button class="btn btn-primary toggle-files" data-target="all-files-section">Ver Todos los
            Archivos</button>
        <div id="all-files-section" class="files-section hidden">
            <ul>
                @foreach ($historial->archivosAdjuntos as $archivo)
                <li>
                    <strong>Fecha:</strong> {{ $archivo->created_at }} <br>
                    <strong>Origen:</strong>
                    @if ($archivo->id_consulta)
                    Consulta
                    @elseif ($archivo->id_examen)
                    Examen Médico
                    @else
                    Otro
                    @endif <br>
                    <strong>Nombre:</strong> {{ $archivo->nombre_archivo }} <br>
                    <div class="file-container">
                        @if (Str::endsWith($archivo->ruta_archivo, ['.jpg', '.jpeg', '.png', '.gif']))
                        <img src="{{ asset('storage/' . $archivo->ruta_archivo) }}" alt="Archivo"
                            class="responsive-img">
                        @elseif (Str::endsWith($archivo->ruta_archivo, ['.pdf']))
                        <embed src="{{ asset('storage/' . $archivo->ruta_archivo) }}"
                            type="application/pdf" width="100%" height="500px">
                        <br>
                        <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank"
                            class="btn btn-primary">Abrir PDF</a>
                        @else
                        <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank"
                            class="btn btn-primary">Descargar Archivo</a>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div> -->

</div>
<!-- Botón de regreso -->
<a href="{{ route('historial.index') }}" class="btn btn-secondary">Regresar</a>
</div>


<!-- JavaScript -->
<script>
    document.querySelectorAll('.toggle-files').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const target = document.getElementById(targetId);

            if (target) {
                target.classList.toggle('hidden');
                target.style.display = target.classList.contains('hidden') ? 'none' : 'block';
            }
        });
    });
</script>
@endsection

<style>
    /* General Styles */
    .card-item {
        background-color: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .card {
        padding: 1.5rem;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-top: 1.5rem;
    }

    .btn {
        margin-top: 1rem;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-edit {
        background-color: #ffc107;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-view {
        background-color: #17a2b8;
        color: white;
    }

    /* Section Styles */
    .section {
        margin-bottom: 2rem;
        padding: 1rem;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .anamnesis-section {
        border-left: 4px solid #007bff;
    }

    .alergias-section {
        border-left: 4px solid #28a745;
    }

    .vacunas-section {
        border-left: 4px solid #ffc107;
    }

    .consultas-section {
        border-left: 4px solid #dc3545;
    }

    .cirugias-section {
        border-left: 4px solid #6610f2;
    }

    .diagnosticos-section {
        border-left: 4px solid #fd7e14;
    }

    .examenes-section {
        border-left: 4px solid #20c997;
    }

    .recetas-section {
        border-left: 4px solid #e83e8c;
    }

    .tratamientos-section {
        border-left: 4px solid #6f42c1;
    }

    .triaje-section {
        border-left: 4px solid #17a2b8;
    }

    .archivos-section {
        border-left: 4px solid #6c757d;
    }

    /* Styles for Files Section */
    .files-section {
        margin-top: 1rem;
        display: none;
        transition: opacity 0.2s ease-in-out;
    }

    .files-section.hidden {
        display: none;
    }

    .files-section img.responsive-img {
        max-width: 100%;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        margin-top: 1rem;
    }

    .image-container {
        max-width: 100%;
        overflow: hidden;
        text-align: center;
        margin-top: 1rem;
    }

    .image-container img {
        max-width: 100%;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    /* Additional Button Styles */
    .toggle-files {
        background-color: #17a2b8;
        color: white;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        transition: background-color 0.3s ease;
    }

    .toggle-files:hover {
        background-color: #138496;
    }
</style>