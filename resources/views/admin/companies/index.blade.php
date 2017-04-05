@extends('admin.templates.master')
@section('breadcrumb-right')
    <li><a href="{{ route('admin.companies.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit spolocnost</a></li>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <table class="table datatable-sorting table-hover table-hover-hand">
                <thead>
                <tr>
                    <th>Názov</th>
                    <th>Adresa</th>
                    <th>Telefón</th>
                    <th>Užívatelia</th>
                    <th>Certifikát</th>
                    <th width="120" class="text-center">Akcie</th>
                </tr>
                </thead>
                <tbody>
                @foreach($companies as $c)
                <tr id="{{$c->id}}">
                    <td width="140">{{$c->name or '-'}}</td>
                    <td>{{$c->address or '-'}}</td>
                    <td>{{$c->phone or '-'}}</td>
                    <td>{{$c->users or '-'}}</td>
                    @if($c->cert_id != null)
                    <td>{{($c->cert_valid == 1 ? 'platný': 'neplatný')}} ({{$c->expiration_date}})</td>
                    @else
                        <td>nevložený</td>
                    @endif
                    <td class="text-center">
                        <a href="{{ route('admin.companies.edit', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;
                        <a class="sweet_delete" href="{{ route('admin.companies.delete', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>
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
                window.location.href = '{{ url('') }}/companies/' + id + '/detail';
            });
        });

        // PopUP for question confirm delete
        $('.sweet_delete').on('click', function() {
            swal({
                    title: "Odstránenie spoločnosti",
                    text: "Naozaj chcete odstrániť spoločnosť?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: "Áno, odstrániť",
                    cancelButtonText: "Nie",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },

                function(isConfirm){
                    if (isConfirm) {
                        window.location.href = globalVar;
                    }
                });

            window.globalVar = $(this).attr('href');
            return false;
        });

@endsection
