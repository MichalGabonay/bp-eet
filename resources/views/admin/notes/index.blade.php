@extends('admin.templates.master')

@section('breadcrumb-right')
    <li><a href="{{ route('admin.notes.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit poznámku pre obdobie</a></li>
@endsection

@section('content')
    <label class="switch">
        <input type="checkbox" checked>
        <div class="slider round"></div>

    </label><label class="switcher-label">Poznámky k obdobiam</label><br>

    <label class="switch">
        <input type="checkbox" checked>
        <div class="slider round"></div>

    </label><label class="switcher-label">Poznámky k tržbám</label><br>

    <div id="period_notes">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting table-hover table-hover-hand">
                            <thead>
                            <tr>
                                <th>Obdobie</th>
                                <th>Poznámka</th>
                                <th>Vytvorená</th>
                                <th width="120" class="text-center">Akcie</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($period_notes as $n)
                                <tr id="{{$n->id}}">
                                    <td>{{date('d.m.Y', strtotime($n->from))}} - {{date('d.m.Y', strtotime($n->to))}}</td>
                                    <td>{{$n->note}}</td>
                                    <td>{{date('d.m.Y', strtotime($n->created_at))}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.notes.edit', $n->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;
                                        <a class="sweet_delete" href="{{ route('admin.notes.delete', $n->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sales_notes">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting table-hover table-hover-hand">
                            <thead>
                            <tr>
                                <th>Tržba</th>
                                <th>Poznámka</th>
                                <th>Vytvorená</th>
                                <th width="120" class="text-center">Akcie</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sales_notes as $n)
                                <tr id="{{$n->id}}">
                                    <td><a href="{{route('admin.sales.detail', $n->sale_id)}}">{{$n->receiptNumber}}</a></td>
                                    <td>{{$n->note or '-'}}</td>
                                    <td>{{date('d.m.Y', strtotime($n->created_at))}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.notes.edit', $n->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;
                                        <a class="sweet_delete" href="{{ route('admin.notes.delete', $n->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
            order: [2, "asc"]
        });
@endsection