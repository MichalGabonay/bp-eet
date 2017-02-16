@extends('admin.templates.master')


@section('content')

    <div class="panel panel-flat">
        <table class="table datatable-sorting table-hover table-hover-hand">
            <thead>
            <tr>
                <th width="140">Kód</th>
                <th>Typ zařízení</th>
                <th>Firma</th>
                {{--<th>Uvedeno do provozu / rok výroby</th>--}}
                <th>Simpati</th>
                <th width="120" class="text-center">Akce</th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($products as $p)--}}
                <tr >
                    <td width="140">Kód</td>
                    <td>Typ zařízení</td>
                    <td>Firma</td>
                    {{--<th>Uvedeno do provozu / rok výroby</th>--}}
                    <td>Simpati</td>
                    <td width="120" class="text-center">Akce</td>
                </tr>
            {{--@endforeach--}}
            </tbody>
        </table>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4>Nadpis</h4>
        </div>
        <div class="panel-body">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec aliquam finibus cursus. Vestibulum hendrerit tincidunt felis nec mollis. Morbi tincidunt eget turpis vitae fermentum. Praesent quam massa, venenatis a eleifend tincidunt, maximus sit amet neque. Aenean vestibulum tincidunt magna, eget maximus tortor facilisis bibendum. Aenean sagittis laoreet gravida. Fusce at dignissim nibh. Nunc non nibh tellus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas rutrum quam sed ullamcorper laoreet. Pellentesque luctus in est nec egestas. Aenean at magna orci. In hac habitasse platea dictumst. Curabitur lobortis ut lorem at aliquam. Aenean at augue sit amet dolor convallis condimentum id at mi. Integer vulputate quam vel purus consequat posuere.
        </div>


    </div>

    {{--<a href="{{ route('admin.products.create') }}" class="btn bg-teal-400 btn-labeled"><b><i class="icon-pencil7"></i></b> Vložit zařízení</a>--}}


@endsection





@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}

@endsection


@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $('.datatable-sorting').DataTable({
            order: [0, "asc"]
        });


@endsection