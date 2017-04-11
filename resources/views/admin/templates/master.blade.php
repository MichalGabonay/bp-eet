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
    {!! HTML::style( asset("/assets/admin/css/app.css") ) !!}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    {!! HTML::style( asset("/assets/admin/css/style.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/sweetalert.css") ) !!}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>

    @yield('head_css')

    <!-- Scripts -->
    {!! HTML::script( asset("/assets/admin/js/jquery.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/select2/select2.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/sweetalert.min.js") ) !!}
    @yield('head_js')
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    @yield('head_extra')
</head>

<body class="gray-bg">
<div id="app">

    <nav class="navbar navbar-default navbar-static-top fixed">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <i class="fa fa-bars fa-2x" aria-hidden="true"></i>
                </button>

                <!-- Branding Image -->
                @if($company_logo != '')
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/uploads/logos/{{$company_logo}}" height="50">
                </a>
                @endif

            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @include('admin.partials.menu-items', array('items' => $MyNavBar->roots()))
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Odhlásiť sa
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container first-container">
        <div class="page-header">
            <h3><i class="fa {{ $page_icon or '' }} position-left"></i> <span class="text-semibold">{{ $page_title or '' }}</span> @if(!empty($page_description)) - @endif {{ $page_description or '' }}</h3>

        </div>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home position-left"></i> Home</a></li>
                <li class="active">{{ $page_title or "Page Title" }}</li>
            </ul>

            <ul class="breadcrumb-right">
                @yield('breadcrumb-right')
            </ul>
        <br>
            <hr class="margin-left-right">

        <div class="row">
            <div class="col-md-12">
                @yield('top-buttons')
            </div>
        </div>

        @include('admin.partials._errors')
        @include('flash::message')

        @yield('content')

    </div>
</div>

<!-- Scripts -->

{!! HTML::script( asset("/assets/admin/js/bootstrap.min.js") ) !!}

<script>
    jQuery(document).ready(function() {
        @yield('jquery_ready')
    });
</script>

</body>
</html>