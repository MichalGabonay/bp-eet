<!DOCTYPE html>
<html lang="cz">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EET') }} {{ $page_title or '' }}</title>

    <!-- Styles -->

    {!! HTML::style( asset("/assets/admin/css/bootstrap/bootstrap.min.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/select2.min.css") ) !!}
    {{--{!! HTML::style( asset("/assets/admin/css/font-awesome.min.css") ) !!}--}}
    {!! HTML::style( asset("/assets/admin/css/app.css") ) !!}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    {!! HTML::style( asset("/assets/admin/css/style.css") ) !!}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>


    @yield('head_css')

    <!-- Scripts -->
    {!! HTML::script( asset("/assets/admin/js/jquery.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/select2/select2.js") ) !!}
    @yield('head_js')
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    @yield('head_extra')
</head>

<body>


@yield('content')

<!-- Scripts -->

{{--{!! HTML::script( asset("/assets/admin/js/jquery-3.1.1.min.js") ) !!}--}}
{!! HTML::script( asset("/assets/admin/js/bootstrap.min.js") ) !!}
{{--{!! HTML::script( asset("/assets/admin/js/app.js") ) !!}--}}

<script>
    jQuery(document).ready(function() {
//        $(window).scroll(function(){
//            var sticky = $('.sticky'),
//                    scroll = $(window).scrollTop();
//
//            if (scroll >= 100) sticky.addClass('fixed');
//            else sticky.removeClass('fixed');
//        });


        @yield('jquery_ready')
    });
</script>

</body>
</html>