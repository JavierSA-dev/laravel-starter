@extends('layout.base')
@section('content')
<style>
    .card{
        width: 18rem;
        margin: 10px;
        display: inline-block;
        box-shadow: 0px 4px 10px 4px rgba(0, 0, 0, 0.1);
        background: #f5b7b2;
        transition: all 0.3s;

    }
    .card-body{
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: white;
    }
    .card-body a{
        color: white;
        text-decoration: none;
    }
    .duracion{
        position: absolute;
        top: 10px;
        right: 10px;
        background: #7e5a57;
    }
    .cocinarButton{
        right: inherit;
        left: 10px;
    }

    .fa-clock{
        margin-right: 5px;
    }
    .categoria{
        background-color: #cb928d !important;
    }
    #containerCards{
        background: #f9f8f8;
        border-radius: 10px;
        display: flex;
        flex-wrap: wrap;
    }


    .card:hover{
        transform: translateY(-10px);
        cursor: pointer;
    }

</style>
<h1 class="text-center mt-3 mb-3">Lista de recetas
    <i class="fas fa-utensils"></i>
</h1>

<div class="containerBuscador">
    <input type="search" id="buscador" class="form-control" placeholder="Buscar receta...">
</div>
<div id="containerCards">
    @foreach ($recetas as $receta)
        <div class="card" data-href="{{ route('recetas.edit', $receta->id) }}">
            @if (!$receta->imagen)
                <img src="{{ '/storage' . asset('Image-not-found.png') }}" class="card-img-top" alt="..." style="max-height: 190px; object-fit: cover; object-position: center {{ $receta->align_selected ?? 'center' }};">
            @else
                <img src="{{ '/storage' . asset($receta->imagen) }}" class="card-img-top" alt="..." style="max-height: 190px; object-fit: cover; object-position: center {{ $receta->align_selected ?? 'center' }};">
            @endif
            <div class="card-body">
                <h5 class="card-title w-100">{{ $receta->nombre }}</h5>
                <span class="badge bg-primary categoria">{{ $receta->tipo }}</span>
                
            </div>
            
            <span data-receta-id="{{ $receta->id }}" class="badge bg-primary duracion cocinarButton @if ($receta->comprobarIngredientes() === 1) todos-ingredientes @elseif ($receta->comprobarIngredientes() === 0) ningun-ingrediente @else algun-ingrediente @endif"><span class="material-symbols-outlined">
                flatware
                </span>
            </span>
            <span class="badge bg-primary duracion" style="background: #7e5a57 !important"><i class="fas fa-clock"></i>{{ $receta->duracion }} min</span>
            
        </div>
        
        <div class="modal fade modalIngrediente{{ $receta->id }}" tabindex="-1" aria-labelledby="modalIngredienteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title modalIngredienteLabel">Revisar ingredientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                           <i>Te faltan...</i> 
                        </p>
                        <div class="ingredientesFaltan customTable">
                            <table class="table notDatatable">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Ingrediente</th>
                                        <th>Añadir a la cesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receta->ingredientesFaltan() as $ingrediente => $cantidad)
                                        <tr>
                                            <td>{{ $cantidad }}</td>
                                            <td>{{ $ingrediente }}</td>
                                            <td>
                                                <button class="btn btn-primary addCart" data-ingrediente="{{ $ingrediente }}" data-cantidad="{{ $cantidad }}" type="button">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>
                            <i>Tienes...</i>
                        </p>
                        <div class="ingredientesTienes customTable">
                            <table class="table notDatatable">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Ingrediente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($receta->ingredientesTengo() as $ingrediente => $cantidad)
                                        <tr>
                                            <td>{{ $cantidad }}</td>
                                            <td>{{ $ingrediente }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No tienes ningún ingrediente 🙃</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    @endforeach

</div>

<div class="text-center mt-3">
    <a href="{{ route('recetas.create') }}" class="btn btn-primary floatButton">
        <i class="fas fa-plus"></i>
    </a>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#buscador').keyup(function(){
                let texto = $(this).val().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                $('.card').hide();
                $('.card').filter(function() {
                    let cardText = $(this).text().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    return cardText.includes(texto);
                }).show();
            });

            $('.card').click(function (e) { 
                e.preventDefault();
                // si pulsas en .ningun-ingrediente o .algun-ingrediente no redirigir
                
                if ($($(e.target).parent()).hasClass('ningun-ingrediente') || $($(e.target).parent()).hasClass('algun-ingrediente') || $(e.target).hasClass('ningun-ingrediente') || $(e.target).hasClass('algun-ingrediente')) {
                    console.log('no redirigir');
                    
                    return;
                }
                
                let href = $(this).data('href');
                window.location.href = href;                
            });



            // al hacer click en .ningun-ingrediente o .algun-ingrediente, abrir el modal
            $(document).on('click', '.ningun-ingrediente, .algun-ingrediente', function (e) { 
                e.preventDefault();
                // .modalIngrediente
                let modal = $('.modalIngrediente' + $(this).data('receta-id'));
                modal.modal('show');
                
            });

            $('.addCart').click(function (e) { 
                e.preventDefault();
                console.log("aaa");
                
                let ingrediente = $(this).data('ingrediente');
                let cantidad = $(this).data('cantidad');

                let data = {
                    ingrediente: ingrediente,
                    cantidad: cantidad,
                    _token: '{{ csrf_token() }}'
                };
                
                $.ajax({
                    type: "POST",
                    url: "/addCart",
                    data: data,
                    success: function (response) {
                        console.log(response);
                        
                        // console.log(response);
                        if (response) {
                            // mostrar mensaje de éxito
                            $.notify("Añadido al carrito", "success");
                        } else {
                            // mostrar mensaje de error
                            $.notify("Ya está en el carrito", "error");
                        }                        
                    }
                });
                
            });

        });
    </script>
@endsection
