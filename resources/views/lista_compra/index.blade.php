@extends('layout.base')

@section('content')
    <style>
        td {
            text-align: left !important;
        }
        .select2{
            width: 100% !important;
            z-index: 9999 !important;
        }
        .select2-dropdown{
            z-index: 9999 !important;
        }
    </style>
    <h1 class="text-center mt-3 mb-3 d-flex align-items-center justify-content-center">
        Lista de la compra
        <i class="fa-solid fa-shopping-cart ms-2"></i>
    </h1>

    <div class="containerBuscador">
        <input type="search" id="buscador" class="form-control" placeholder="Buscar en la lista...">
    </div>

    <div class="table-container customTable">
        <div class="table-responsive">
            <table class="">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th style="text-align: left">Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaCompra as $value)
                        <tr>
                            <td>{{ $value->ingrediente->nombre }}</td>
                            <td>{{ $value->cantidad }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editButton" data-id="{{ $value->id }}"
                                    data-cantidad="{{ $value->cantidad }}"
                                    data-ingrediente-id="{{ $value->ingrediente->id }}"
                                    
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <button class="btn btn-sm btn-danger removeButton" data-id="{{ $value->id }}">
                                    <i class="fas fa-trash"></i> 
                                </button>
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
                    <h5 class="modal-title" id="createModalLabel">Añadir ingrediente a la lista de la compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lista_compra.create') }}" method="POST">
                        @csrf
                        {{-- select ingrediente e input cantidad --}}
                        <div class="mb-3">
                            <label for="ingrediente" class="form-label">Ingrediente</label>
                            <select class="form-select select2" id="ingrediente" name="ingrediente_id" required>
                                <option value="">Selecciona un ingrediente</option>
                                @foreach ($ingredientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
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
                    <h5 class="modal-title" id="editModalLabel">Editar ingrediente de lista de la compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lista_compra.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="lista_compra_id" name="lista_compra_id">
                        <div class="mb-3">
                            <label for="ingrediente_id" class="form-label">Ingrediente</label>
                            <select class="form-select select2" id="ingrediente_id_edit" name="ingrediente_id" required>
                                <option value="">Selecciona un ingrediente</option>
                                @foreach ($ingredientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="edit_cantidad" name="cantidad" required>
                        </div>
                        <div class="mb-3"></div>
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

            // select2 no funcion en modal de bootstrap
            $('#ingrediente').select2({
                placeholder: 'Selecciona un ingrediente',
                allowClear: true,
                tags: true,
                dropdownParent: $('#createModal'),
                templateResult: function(state) {
                    if (state.id && state.text === state.id) {
                        return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                    }
                    return state.text;
                }
            });

            $('#ingrediente_id').select2({
                placeholder: 'Selecciona un ingrediente',
                allowClear: true,
                tags: true,
                dropdownParent: $('#editModal'),
                templateResult: function(state) {
                    if (state.id && state.text === state.id) {
                        return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                    }
                    return state.text;
                }
            });

            
            $('#buscador').on('keyup', function() {
                // dt-search
                $('.dt-input').val($(this).val()).trigger('keyup');
            });

            // Botón para editar
            $(document).on('click', '.editButton', function() {
                const id = $(this).data('id');
                const ingredienteId = $(this).data('ingrediente-id');
                const cantidad = $(this).data('cantidad');

                $('#lista_compra_id').val(id);
                $('#ingrediente_id_edit').select2().val(ingredienteId).trigger('change');
                $('#edit_cantidad').val(cantidad);
            });

            // Botón para eliminar
            $(document).on('click', '.removeButton', function(e) {
                e.preventDefault();
                const confirmation = confirm("¿Estás seguro de que quieres eliminar este ingrediente?");
                if (!confirmation) return;

                const id = $(this).data('id');
                const url = "{{ route('lista_compra.destroy', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert("Error al eliminar el ingrediente.");
                    }
                });
            });
        });
    </script>
@endsection