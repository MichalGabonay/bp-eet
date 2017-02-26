@extends('admin.templates.master')
{{--@section('breadcrumb-right')--}}
    {{--<li><a href="{{ route('admin.companies.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit spolocnost</a></li>--}}
{{--@endsection--}}

@section('top-buttons')
    <a href="{{ route('admin.users.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Zpět </a>
    <a href="{{ route('admin.users.edit', $users->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Upraviť </a>
    <a href="{{ route('admin.users.delete', $users->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Odstrániť </a>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Základné informácie</h4>
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Meno:</strong></td>
                                <td>{{ $users->name or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>E-mail:</strong></td>
                                <td>{{ $users->email or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Užívatelské meno:</strong></td>
                                <td>{{ $users->username or '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Role a spoločnosti</h4>

                    <div class="tab-pane has-padding" id="tab_roles">
                        @foreach($companies as $c)
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <span><strong>Spoločnosť: </strong>{{$c->name}}</span> <a href="{{ route('admin.users.company_state', [$users->id, $c->id]) }}">{{$c->user_enabled ? 'Deaktivovat':'Aktivovat'}}</a>
                                </div>
                                @if($c->user_enabled)
                                <div class="panel-body">
                                    @foreach($roles as $r)
                                        <div class="row">
                                            <div class="col-md-2">
                                                {{$r->name}}
                                            </div>
                                            <div class="col-md-3">
                                                @if ( $users_role[$users->id][$c->id][$r->id]['enabled'] )
                                                    <a href="{!! route('admin.roles.switch-role', [$users_role[$users->id][$c->id][$r->id]['user_company_id'], $r->id, 'users']) !!}" title="deaktivuj"><i class="fa fa-check enabled" style="color: green"></i></a>
                                                @else
                                                    <a href="{!! route('admin.roles.switch-role', [$users_role[$users->id][$c->id][$r->id]['user_company_id'], $r->id, 'users']) !!}" title="aktivuj"><i class="fa fa-times disabled" style="color: red"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    </div>
                </div>
            </div>
        </div>
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="panel panel-flat">--}}
                {{--<div class="panel-body">--}}
                    {{--<h4>Užívatel</h4>--}}

                    {{--<table class="table datatable-sorting">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Meno</th>--}}
                            {{--<th>E-mail</th>--}}
                            {{--<th>Užívatelské meno</th>--}}
                            {{--@foreach($roles as $r)--}}
                                {{--<th width="80" class="text-center">{{$r->name}}</th>--}}
                            {{--@endforeach--}}
                            {{--<th width="80" class="text-center">Akcie</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($users_in as $u)--}}
                            {{--<tr >--}}
                                {{--<td>{{$u->name or '-'}}</td>--}}
                                {{--<td>{{$u->email or '-'}}</td>--}}
                                {{--<td>{{$u->username or '-'}}</td>--}}
                                {{--@foreach($roles as $r)--}}
                                    {{--<td width="80" class="text-center">--}}
                                        {{--@if ( $users_role[$u->id][$r->id] )--}}
                                            {{--<a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="deaktivuj"><i class="fa fa-check enabled" style="color: green"></i></a>--}}
                                        {{--@else--}}
                                            {{--<a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="aktivuj"><i class="fa fa-times disabled" style="color: red"></i></a>--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                {{--@endforeach--}}
                                {{--<td width="80" class="text-center">--}}
                                    {{--<a href="{{ route('admin.users.edit', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;--}}
                                    {{--<a class="sweet_delete" href="{{ route('admin.users.delete', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
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
