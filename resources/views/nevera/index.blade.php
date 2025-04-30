@extends('layout.base')
@section('content')
    <style>
        td {
            text-align: left !important;
        }
    </style>
    <h1 class="text-center mt-3 mb-3 d-flex align-items-center justify-content-center">
        Tu nevera
        <img src="{{ asset('icons/nevera.png') }}" alt="Nevera" style="object-fit: contain;  width: 37px;">
    </h1>

    <div class="containerBuscador">
        <input type="search" id="buscador" class="form-control" placeholder="Buscar en la nevera...">
    </div>
    <div class="table-container customTable">
        <div class="table-responsive">

            <table class="table" style="width: 100%">
                <thead>
                    <tr>
                        <th class="d-none">ID</th>
                        <th></th>
                        <th>Nombre</th>
                        <th>Medida</th>
                        <th style="text-align: left">Cantidad</th>
                        <th class="tipo_fecha">Caducidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredientes_nevera as $ingrediente_nevera)
                        <tr class="editButton" style="cursor: pointer" data-id="{{ $ingrediente_nevera->id }}"
                            data-ingrediente="{{ $ingrediente_nevera->ingrediente->id }}"
                            data-medida="{{ $ingrediente_nevera->medida }}" data-cantidad="{{ $ingrediente_nevera->cantidad }}"
                            data-caducidad="{{ $ingrediente_nevera->fecha_caducidad }}">
                            <td class="d-none">{{ $ingrediente_nevera->id }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm removeNevera" data-id="{{ $ingrediente_nevera->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            <td>{{ $ingrediente_nevera->ingrediente->nombre }}</td>
                            <td>{{ $ingrediente_nevera->medida }}</td>
                            <td>{{ $ingrediente_nevera->cantidad }}</td>
                            <td>{{ $ingrediente_nevera->fecha_caducidad ? \Carbon\Carbon::parse($ingrediente_nevera->fecha_caducidad)->format('d/m/Y') : 'No especificada' }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="button" class="btn btn-primary floatButton" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- Modal para crear -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">¡Añade algo a tu nevera!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('nevera.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ingrediente_id" class="form-label">Nombre</label>
                            <select class="form-select select2" id="ingrediente_id" name="ingrediente_id" required>
                                <option value="" disabled selected>Selecciona un ingrediente</option>
                                @foreach ($ingrendientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medida" class="form-label">Medida</label>
                            <select class="form-select" id="medida" name="medida" required>
                                <option value="unidad">Unidad</option>
                                <option value="gramos">Gramos</option>
                                <option value="mililitros">Mililitros</option>
                                <option value="taza">Taza</option>
                                <option value="cucharada">Cucharada</option>
                                <option value="cucharadita">Cucharadita</option>
                                <option value="pizca">Pizca</option>
                                <option value="puñado">Puñado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="caducidad" class="form-label">Caducidad
                                <i>
                                    <small>(Opcional)</small>
                                </i>
                            </label>
                            <input type="date" class="form-control" id="caducidad" name="caducidad">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Añadir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar ingrediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('nevera.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="ingrediente_nevera_id" name="ingrediente_nevera_id">
                        <div class="mb-3">
                            <label for="edit_ingrediente_id" class="form-label">Nombre</label>
                            <select class="form-select select2" id="edit_ingrediente_id" name="ingrediente_id" required>
                                <option value="" disabled selected>Selecciona un ingrediente</option>
                                @foreach ($ingrendientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_medida" class="form-label">Medida</label>
                            <select class="form-select" id="edit_medida" name="medida" required>
                                <option value="unidad">Unidad</option>
                                <option value="gramos">Gramos</option>
                                <option value="mililitros">Mililitros</option>
                                <option value="taza">Taza</option>
                                <option value="cucharada">Cucharada</option>
                                <option value="cucharadita">Cucharadita</option>
                                <option value="pizca">Pizca</option>
                                <option value="puñado">Puñado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="edit_cantidad" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_caducidad" class="form-label">Caducidad
                                <i>
                                    <small>(Opcional)</small>
                                </i>
                            </label>
                            <input type="date" class="form-control" id="edit_caducidad" name="caducidad">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#buscador').on('keyup', function() {
                $('.dt-input').val($(this).val()).trigger('keyup');
            });

            $('#ingrediente_id').select2({
                dropdownParent: $('#createModal'),
                tags: true,
                templateResult: function(state) {
                    if (state.id && state.text === state.id) {
                        return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                    }
                    return state.text;
                },
                width: '100%',
            });

            $('#edit_ingrediente_id').select2({
                dropdownParent: $('#editModal'),
                tags: true,
                templateResult: function(state) {
                    if (state.id && state.text === state.id) {
                        return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                    }
                    return state.text;
                },
                width: '100%',
            });

            $('.editButton').on('click', function(e) {

                console.log(e.target.tagName);

                if (e.target.tagName == 'BUTTON' || e.target.tagName == 'I') {
                    return;
                }
                const id = $(this).data('id');
                const ingrediente = $(this).data('ingrediente');
                const medida = $(this).data('medida');
                const cantidad = $(this).data('cantidad');
                const caducidad = $(this).data('caducidad');

                $('#ingrediente_nevera_id').val(id);
                $('#edit_ingrediente_id').val(ingrediente).trigger('change');
                $('#edit_medida').val(medida);
                $('#edit_cantidad').val(cantidad);
                $('#edit_caducidad').val(caducidad);

                $('#editModal').modal('show');
            });

            $('.removeNevera').click(function(e) {
                let confirmation = confirm(
                    "¿Estás seguro de que quieres eliminar este ingrediente de tu nevera?");
                if (!confirmation) {
                    e.preventDefault();
                    return;
                }
                const id = $(this).data('id');
                const url = "{{ route('nevera.delete', ':id') }}".replace(':id', id);
                console.log(url);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        $.notify("El ingrediente ha sido eliminado de la nevera.", "success");

                        // remove tr
                        $('tr[data-id="' + id + '"]').remove();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $.notify("Error al eliminar el ingrediente de la nevera.", "error");

                        alert('Error al eliminar el ingrediente de la nevera.');
                    }
                });
            });
        });
    </script>
@endsection
