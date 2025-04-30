<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>

<body>
    <div class="containerMain">
        @include('layout.sidebar')
        <div class="container">
            @yield('content')
        </div>

    </div>
    <script src="https://cdn.tiny.cloud/1/zw04q9hk3cme9yg3dr5h23p8jfkfk8pvwhq63zs60rahm4ie/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://kit.fontawesome.com/12166b6364.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.tinymce',
                language: 'es',
                mobile: {
                    menubar: true
                },
                plugins: [
                    // Core editing features
                    'anchor', 'emoticons', 'link', 'lists', 'wordcount',
                    // Your account includes a free trial of TinyMCE premium features
             
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link typography align lineheight | checklist numlist bullist indent outdent |',
                height: 1000,
            });

            $('.tinymce').each(function(index, element) {
                let inputHidden = $(element).attr('id') + '_hidden';
                let inputHiddenElement = $('<input type="hidden" name="' + inputHidden + '" id="' +
                    inputHidden + '">');
                $(element).after(inputHiddenElement);
                $(element).on('input', function() {
                    let content = tinymce.get(element.id).getContent();
                    $('#' + inputHidden).val(content);
                });

                tinymce.get(element.id).on('input', function() {
                    let content = tinymce.get(element.id).getContent();
                    $('#' + inputHidden).val(content);
                });
            });

            $.fn.dataTable.ext.type.order['date-dd-mm-yyyy-pre'] = function(d) {
                if (!d) {
                    return 0;
                }
                const parts = d.split('/');
                return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
            };

            $('table').not('.notDatatable').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    // cuando no haya datos
                    "emptyTable": "No hay datos disponibles en la tabla",
                },
                "searching": true,
                "pagingType": "simple",
                "columnDefs": [{
                    "targets": '.tipo_fecha',
                    "type": 'date-dd-mm-yyyy'
                }],
                
                order: [
                    [0, 'desc']
                ],
            });
        });

        $('.select2').select2({
            tags: true,
            templateResult: function(state) {
                if (state.id && state.text === state.id) {
                    return $('<span>' + state.text + ' <em>(Nuevo)</em></span>');
                }
                return state.text;
            },
        });

        $('.showSidebar').hide();

        $('.hideSidebar').click(function() {
            $('#sidebar').attr('style', 'display: none !important; width: 280px;');
            $(this).hide();
            $('.showSidebar').show();
        });

        $('.showSidebar').click(function() {
            $('#sidebar').attr('style', 'display: flex !important; width: 280px;');
            $(this).hide();
            $('.hideSidebar').show();
        });
    </script>

    @yield('scripts')
    @if (session('success'))
        <script>
            $.notify("{{ session('success') }}", "success");
        </script>
    @endif
    @if (session('error'))
        <script>
            $.notify("{{ session('error') }}", "error");
        </script>
    @endif
    @if (session('warning'))
        <script>
            $.notify("{{ session('warning') }}", "warning");
        </script>
    @endif
    <script>
        console.log("hola");
        if ($(window).width() < 768) {
            $('.hideSidebar').trigger('click');

        }
            </script>
</body>

</html>
