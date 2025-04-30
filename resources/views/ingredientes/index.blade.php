@extends('layout.base')

@section('content')
    <style>
        td {
            text-align: left !important;
        }
    </style>
    <h1 class="text-center mt-3 mb-3 d-flex align-items-center justify-content-center">
        Ingredientes
        <i class="fa-solid fa-carrot ms-2"></i>
    </h1>

    <div class="containerBuscador">
        <input type="search" id="buscador" class="form-control" placeholder="Buscar ingrediente...">
    </div>

    <div class="table-container customTable">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredientes as $ingrediente)
                        <tr>
                            <td>{{ $ingrediente->nombre }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editButton" data-id="{{ $ingrediente->id }}"
                                    data-nombre="{{ $ingrediente->nombre }}" data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <button class="btn btn-sm btn-danger removeButton" data-id="{{ $ingrediente->id }}">
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
                    <h5 class="modal-title" id="createModalLabel">Añadir ingrediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ingredientes.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ingrediente_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="ingrediente_nombre" name="nombre" required>
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
                    <form action="{{ route('ingredientes.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="ingrediente_id" name="ingrediente_id">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
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
            // Filtro de búsqueda
            $('#buscador').on('keyup', function() {
                // usa el buscador del datatable
                $('.table').DataTable().search($(this).val()).draw();
            });

            // Botón para editar
            $(document).on('click', '.editButton', function() {
                console.log("editButton clicked");
                
                $('#ingrediente_id').val($(this).data('id'));
                $('#edit_nombre').val($(this).data('nombre'));
            });

            

            // Botón para eliminar
            $(document).on('click', '.removeButton', function(e) {
                console.log("removeButton clicked");
                
                e.preventDefault();
                let confirmation = confirm("¿Estás seguro de que quieres eliminar este ingrediente?");
                if (!confirmation) return;

                const id = $(this).data('id');
                const url = "{{ route('ingredientes.destroy', ':id') }}".replace(':id', id);
                console.log(url);
                

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $.notify({
                            message: "Ingrediente eliminado correctamente.",
                            type: 'success',
                            delay: 2000,
                        });
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