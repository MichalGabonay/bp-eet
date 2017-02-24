@extends('admin.templates.master')
@section('breadcrumb-right')
    <li><a href="{{ route('admin.users.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit užívatela</a></li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <table class="table datatable-sorting table-hover table-hover-hand">
            <thead>
            <tr>
                <th>Meno</th>
                <th>E-mail</th>
                <th>Užívatelské meno</th>
                <th>Priradených spoločností</th>
                <th width="120" class="text-center">Akcie</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr >
                    <td>{{$u->name or '-'}}</td>
                    <td>{{$u->email or '-'}}</td>
                    <td>{{$u->username or '-'}}</td>
                    <td><a href="{{ route('admin.users.edit', $u->id) }}">{{$u->companies_count or '-'}}</a></td> {{--TODO: edit - #tab_companies--}}
                    <td class="text-center">
                        <a href="{{ route('admin.users.edit', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;
                        <a class="sweet_delete" href="{{ route('admin.users.delete', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
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
