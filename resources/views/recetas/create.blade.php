@extends('layout.base')

@section('content')
    <div class="containerFormReceta mt-5">
        <h1 class="text-center">{{ isset($receta) ? 'Editar receta' : 'Crear receta' }}</h1>
        <form action="{{ isset($receta) ? route('recetas.update', $receta->id) : route('recetas.store') }}" 
              method="POST" 
              class="mt-3 row shadow-sm rounded p-3 m-1" 
              enctype="multipart/form-data">
            @csrf
            <!-- Campo Nombre -->
            <div class="mb-3 col-md-12">
                <label for="nombre" class="form-label labelWithIcon">Nombre
                    <span class="material-symbols-outlined">edit</span>
                </label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="{{ isset($receta) ? $receta->nombre : '' }}" 
                        placeholder="Lentejas con arroz">
            </div>

            <!-- Campo Tipo -->
            <div class="mb-3 col-md-12">
                <label for="tipo" class="form-label labelWithIcon">Tipo
                    <span class="material-symbols-outlined">restaurant_menu</span>
                </label>
                <select class="form-select" id="tipo" name="tipo" >
                    <option value="Desayuno" {{ isset($receta) && $receta->tipo == 'Desayuno' ? 'selected' : '' }}>Desayuno</option>
                    <option value="Almuerzo" {{ isset($receta) && $receta->tipo == 'Almuerzo' ? 'selected' : '' }}>Almuerzo</option>
                    <option value="Merienda" {{ isset($receta) && $receta->tipo == 'Merienda' ? 'selected' : '' }}>Merienda</option>
                    <option value="Cena" {{ isset($receta) && $receta->tipo == 'Cena' ? 'selected' : '' }}>Cena</option>
                </select>
            </div>

            <!-- Campo Duración -->
            <div class="mb-3 col-md-12">
                <label for="duracion" class="form-label labelWithIcon">Duración (min) 
                    <span class="material-symbols-outlined">schedule</span>
                </label>
                <input type="number" class="form-control" id="duracion" name="duracion" 
                       value="{{ isset($receta) ? $receta->duracion : '' }}" 
                       >
            </div>

            <!-- Campo Imagen -->
            <div class="mb-3 col-md-12">
                <label for="imagen" class="form-label labelWithIcon">Imagen
                    <span class="material-symbols-outlined">image</span>
                </label>
                {{-- <input type="file" class="form-control" id="imagen" name="imagen" {{ isset($receta) ? '' : 'required' }}> --}}
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">

         
                    <div class="mt-3" style="justify-content: center; display: flex;flex-wrap: wrap;gap: 10px;" > 
                        {{-- boton para alinear top, center y bottom --}}
                        <div class="containerButtonAlign">

                            <button type="button" class="buttonIcon btn btn-success" id="alignTop">
                                <i class="material-symbols-outlined">vertical_align_top</i>
                            </button>
                            <button type="button" class="buttonIcon btn btn-success" id="alignCenter">
                                <i class="material-symbols-outlined">vertical_align_center</i>
                            </button>
                            <button type="button" class="buttonIcon btn btn-success" id="alignBottom">
                                <i class="material-symbols-outlined">vertical_align_bottom</i>
                            </button>
                        </div>
                        @if (isset($receta) && $receta->imagen)
                            <img id="previewImage" src="{{ '/storage' . asset($receta->imagen) }}" style="object-fit: cover;max-height: 190px;width: 286px; object-position: {{ $receta->align_selected ?? 'center' }};" />
                        @else
                            <img id="previewImage" style="object-fit: cover;max-height: 190px;width: 286px;" />
                        @endif
                    </div>
                    <input type="hidden" name="align_selected" id="align_selected" value="{{ isset($receta) ? $receta->align_selected : 'center' }}">

            </div>

            <!-- Ingredientes -->
            <div class="mb-3 col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Ingredientes
                        <button class="buttonIcon btn btn-success" id="addIngredient">
                            <i class="material-symbols-outlined">add</i>
                        </button>
                    </div>
                    <div class="card-body" id="ingredientesContainer">
                        <div class="table-responsive">
                            <table class="table table-bordered notDatatable" id="ingredientesTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Medida</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="ingredientesList">
                                @if(isset($receta) && $receta->recetaIngredientes->count() > 0)
                                    @foreach($receta->recetaIngredientes as $index => $recetaIngrediente)
                                        
                                        <tr>
                                            <td>
                                                <select class="form-select select2" name="ingredientes[{{ $index }}][nombre]" required>
                                                    <option value="" disabled>Selecciona un ingrediente</option>
                                                    @foreach($ingredientes as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == $recetaIngrediente->ingrediente->id ? 'selected' : '' }}>
                                                            {{ $item->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select selectMedida" name="ingredientes[{{ $index }}][medida]" required>
                                                    <option value="" disabled>Selecciona una medida</option>
                                                    <option value="gramos" {{ $recetaIngrediente->medida == 'gramos' ? 'selected' : '' }}>Gramos</option>
                                                    <option value="mililitros" {{ $recetaIngrediente->medida == 'mililitros' ? 'selected' : '' }}>Mililitros</option>
                                                    <option value="taza" {{ $recetaIngrediente->medida == 'taza' ? 'selected' : '' }}>Taza</option>
                                                    <option value="cucharada" {{ $recetaIngrediente->medida == 'cucharada' ? 'selected' : '' }}>Cucharada</option>
                                                    <option value="cucharadita" {{ $recetaIngrediente->medida == 'cucharadita' ? 'selected' : '' }}>Cucharadita</option>
                                                    <option value="unidad" {{ $recetaIngrediente->medida == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                                    <option value="pizca" {{ $recetaIngrediente->medida == 'pizca' ? 'selected' : '' }}>Pizca</option>
                                                    <option value="puñado" {{ $recetaIngrediente->medida == 'puñado' ? 'selected' : '' }}>Puñado</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="ingredientes[{{ $index }}][cantidad]" 
                                                       value="{{ $recetaIngrediente->cantidad }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger removeIngredientButton buttonIcon">
                                                    <i class="material-symbols-outlined">delete</i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="ingredientRowTemplate">
                                        <td>
                                            <select class="form-select select2" name="ingredientes[0][nombre]" required>
                                                <option value="" disabled selected>Selecciona un ingrediente</option>
                                                @foreach($ingredientes as $ingrediente)
                                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select selectMedida" name="ingredientes[0][medida]" required>
                                                <option value="" disabled selected>Selecciona una medida</option>
                                                <option value="gramos">Gramos</option>
                                                <option value="mililitros">Mililitros</option>
                                                <option value="taza">Taza</option>
                                                <option value="cucharada">Cucharada</option>
                                                <option value="cucharadita">Cucharadita</option>
                                                <option value="unidad">Unidad</option>
                                                <option value="pizca">Pizca</option>
                                                <option value="puñado">Puñado</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" name="ingredientes[0][cantidad]" required></td>
                                        <td>
                                            <button type="button" class="btn btn-danger removeIngredientButton buttonIcon">
                                                <i class="material-symbols-outlined">delete</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Campo Instrucciones -->
            <div class="mb-3 col-md-12 containerInstrucciones">
                <label for="instrucciones" class="form-label labelWithIcon">Instrucciones de preparación
                    <span class="material-symbols-outlined">edit_note</span>
                </label>
                <textarea class="form-control tinymce" id="instrucciones" name="instrucciones" cols="30" rows="10" placeholder="Escribe aquí las instrucciones de preparación">{{ isset($receta) ? $receta->instrucciones : '' }}</textarea>
            </div>

            <!-- Botón Guardar -->
            <button type="submit" class="btn btn-primary floatButton">
                <i class="material-symbols-outlined">save</i>
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let indice = {{ isset($receta) && $receta->ingredientes->count() > 0 ? $receta->ingredientes->count() : 0 }};
            
            $('#addIngredient').click(function (e) {
                e.preventDefault();
                indice++;
                let newRow = `
                    <tr>
                        <td>
                            <select class="form-select select2" name="ingredientes[${indice}][nombre]" required>
                                <option value="" disabled selected>Selecciona un ingrediente</option>
                                @foreach($ingredientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-select selectMedida" name="ingredientes[${indice}][medida]" required>
                                <option value="" disabled selected>Selecciona una medida</option>
                                <option value="gramos">Gramos</option>
                                <option value="mililitros">Mililitros</option>
                                <option value="taza">Taza</option>
                                <option value="cucharada">Cucharada</option>
                                <option value="cucharadita">Cucharadita</option>
                                <option value="unidad">Unidad</option>
                                <option value="pizca">Pizca</option>
                                <option value="puñado">Puñado</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="ingredientes[${indice}][cantidad]" required></td>
                        <td>
                            <button type="button" class="btn btn-danger removeIngredientButton buttonIcon">
                                <i class="material-symbols-outlined">delete</i>
                            </button>
                        </td>
                    </tr>`;
                $('#ingredientesList').append(newRow);
                $('.select2').select2({
                    tags: true,
                    templateResult: function(state) {
                        if (state.id && state.text === state.id) {
                            return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                        }
                        return state.text;
                    },
                });
            });

            $(document).on('click', '.removeIngredientButton', function () {
                $(this).closest('tr').remove();
            });

            $('#imagen').change(function (e) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function (event) {
                    $('#previewImage').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            // quiero hacer una funcion para que si arrastras en el previewImage se cambie el object-position
            $('#alignTop').click(function () {
                $('#previewImage').css('object-position', 'top');
                $('#align_selected').val('top');
            });
            $('#alignCenter').click(function () {
                $('#previewImage').css('object-position', 'center');
                $('#align_selected').val('center');
            });
            $('#alignBottom').click(function () {
                $('#previewImage').css('object-position', 'bottom');
                $('#align_selected').val('bottom');
            });


        });
        setTimeout(() => {
            // tox-notification__dismiss trigger click
            const notification = document.querySelector('.tox-notification__dismiss');
            if (notification) {
                notification.click();
            }
        }, 1000);
    </script>

    
@endsection