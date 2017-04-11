@extends('admin.templates.master')

@section('breadcrumb-right')
    <li><a href="{{ route('admin.users.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit užívatela</a></li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <table class="table datatable-sorting table-hover table-hover-hand">
                <thead>
                    <tr>
                        <th>Meno</th>
                        <th>E-mail</th>
                        <th>Tel. číslo</th>
                        <th>Užívatelské meno</th>
                        <th width="120" class="text-center">Akcie</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $u)
                    <tr id="{{$u->id}}">
                        <td>{{$u->name or '-'}}</td>
                        <td>{{$u->email or '-'}}</td>
                        <td>{{$u->phone_number or '-'}}</td>
                        <td>{{$u->username or '-'}}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
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
            order: [0, "asc"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
            }
        });

        $(document).ready(function(){
            $(".datatable-sorting tbody").delegate("tr", "click", function(){
                var id = $(this).attr('id');
                window.location.href = '{{ url('') }}/users/' + id + '/detail';
            });
        });
@endsection
