<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;" id="sidebar">
    <div style="position: fixed">

        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="https://images.emojiterra.com/google/noto-emoji/unicode-16.0/color/1024px/1f372.png"
                style="max-width: 40px" alt="" srcset="">
            <span class="fs-4">Menú semanal </span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('index') }}" class="nav-link @if (request()->path() == '/') active @endif text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Semana
                </a>
            </li>
            <li>
                <a href="/recetas" class="nav-link @if (str_contains(request()->path(), 'recetas')) active @endif text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#people-circle"></use>
                    </svg>
                    Recetas
                </a>
            </li>
            <li>
                <a href="/nevera" class="nav-link @if (str_contains(request()->path(), 'nevera')) active @endif text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#speedometer2"></use>
                    </svg>
                    Tu nevera
                </a>
            </li>
            <li>
                <a href="/lista_compra" class="nav-link @if (str_contains(request()->path(), 'lista_compra')) active @endif text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#table"></use>
                    </svg>
                    Lista de la compra
                </a>
            </li>
            <li>
                <a href="/ingredientes" class="nav-link @if (str_contains(request()->path(), 'ingredientes')) active @endif text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Ingrendientes
                </a>
            </li>
        </ul>
    </div>

</div>
<button class="btn btn-dark hideSidebar" style="width: fit-content; height: fit-content; border-radius: inherit; position: fixed; left: 280px; z-index: 1000;">
    <i class="fas fa-arrow-left"></i>
</button>
<button class="btn btn-dark showSidebar" style="width: fit-content; height: fit-content; border-radius: inherit; position: fixed; z-index: 1000;">
    <i class="fas fa-arrow-right"></i>
</button>
