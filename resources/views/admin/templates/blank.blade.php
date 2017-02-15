<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('admin.title') }} {{ $page_title or '' }}</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    {!! HTML::style( asset("/assets/admin/css/icons/icomoon/styles.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/bootstrap.min.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/core.min.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/components.min.css") ) !!}
    {!! HTML::style( asset("/assets/admin/css/colors.css") ) !!}
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    {!! HTML::script( asset("/assets/admin/js/plugins/loaders/pace.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/core/libraries/jquery.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/core/libraries/bootstrap.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/plugins/notifications/jgrowl.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/plugins/loaders/blockui.min.js") ) !!}
    <!-- /core JS files -->

    <!-- Theme JS files -->
    {!! HTML::script( asset("/assets/admin/js/plugins/forms/styling/uniform.min.js") ) !!}

    {!! HTML::script( asset("/assets/admin/js/core/app.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/pages/login.js") ) !!}
    <!-- /theme JS files -->
</head>
<body class="bg-slate-800">

<div class="box-body">
    @include('flash::message', ['flash_type' => 'frontend'])
    @include('admin.partials._errors')
</div>

<!-- Page container -->
<div class="page-container login-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

                @yield('content')

                <!-- Footer -->
                <div class="footer text-white">
                    {!! config('admin.copyright') !!}
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
