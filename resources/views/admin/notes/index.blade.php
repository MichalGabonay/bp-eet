@extends('admin.templates.master')

@section('breadcrumb-right')
    <li><a href="{{ route('admin.notes.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit poznámku</a></li>
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

    <div id="sales_notes">
        <div class="panel panel-flat">
            <table class="table datatable-sorting table-hover table-hover-hand">
                <thead>
                <tr>
                    <th>Spoločnosť</th>
                    <th>Obdobie</th>
                    <th>Poznámka</th>
                    <th>Vytvorená</th>
                    <th width="120" class="text-center">Akcie</th>
                </tr>
                </thead>
                <tbody>
                {{--@foreach($notes as $n)--}}
                {{--<tr id="{{$c->id}}">--}}
                {{--<td width="140">{{$c->name or '-'}}</td>--}}
                {{--<td>{{$c->users or '-'}}</td>--}}
                {{--@if($c->cert_id != null)--}}
                {{--<td>{{($c->cert_valid == 1 ? 'platný': 'neplatný')}} ({{$c->expiration_date}})</td>--}}
                {{--@else--}}
                {{--<td>nevložený</td>--}}
                {{--@endif--}}
                {{--<td class="text-center">--}}
                {{--<a href="{{ route('admin.companies.edit', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;--}}
                {{--<a class="sweet_delete" href="{{ route('admin.companies.delete', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>--}}
                {{--</td>--}}
                {{--</tr>--}}
                {{--@endforeach--}}
                </tbody>
            </table>
        </div>
    </div>

    <div id="period_notes">
        <div class="panel panel-flat">
            <table class="table datatable-sorting table-hover table-hover-hand">
                <thead>
                <tr>
                    <th>Spoločnosť</th>
                    <th>Tržba</th>
                    <th>Poznámka</th>
                    <th>Vytvorená</th>
                    <th width="120" class="text-center">Akcie</th>
                </tr>
                </thead>
                <tbody>
                {{--@foreach($companies as $c)--}}
                {{--<tr id="{{$c->id}}">--}}
                {{--<td width="140">{{$c->name or '-'}}</td>--}}
                {{--<td>{{$c->users or '-'}}</td>--}}
                {{--@if($c->cert_id != null)--}}
                {{--<td>{{($c->cert_valid == 1 ? 'platný': 'neplatný')}} ({{$c->expiration_date}})</td>--}}
                {{--@else--}}
                {{--<td>nevložený</td>--}}
                {{--@endif--}}
                {{--<td class="text-center">--}}
                {{--<a href="{{ route('admin.companies.edit', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;--}}
                {{--<a class="sweet_delete" href="{{ route('admin.companies.delete', $c->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>--}}
                {{--</td>--}}
                {{--</tr>--}}
                {{--@endforeach--}}
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
            order: [0, "asc"]
        });

        {{--$(document).ready(function(){--}}
            {{--$(".datatable-sorting tbody").delegate("tr", "click", function(){--}}
                {{--var id = $(this).attr('id');--}}
                {{--window.location.href = '{{ url('') }}/companies/' + id + '/detail';--}}
            {{--});--}}
        {{--});--}}

@endsection