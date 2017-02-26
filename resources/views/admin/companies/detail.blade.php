@extends('admin.templates.master')
{{--@section('breadcrumb-right')--}}
    {{--<li><a href="{{ route('admin.companies.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit spolocnost</a></li>--}}
{{--@endsection--}}

@section('top-buttons')
    <a href="{{ route('admin.companies.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Zpět </a>
    <a href="{{ route('admin.companies.edit', $company->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Upraviť </a>
    <a href="{{ route('admin.companies.delete', $company->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Odstrániť </a>
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
                                <td><strong>Název:</strong></td>
                                <td>{{ $company->name or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>IČO:</strong></td>
                                <td>{{ $company->ico or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>DIČ:</strong></td>
                                <td>{{ $company->dic or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Užívatelia:</strong></td>
                                <td>{{ $company->users or '-'  }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Certifikát</h4>

                    @if(isset($cert))
                        <div class="col-md-12">
                            <div class="panel panel-flat">

                                <h6 class="panel-title mb-5">Certifikát</h6>

                            </div>
                        </div>
                    @else
                        {{--TODO: varovna hlaska o pridani certifikatu--}}
                    @endif
                    <div class="col-md-12">
                        {!! Form::open( ['route' => ['admin.companies.add_cert', $company->id ], 'id' => 'form_add_cert', 'files' => true] ) !!}
                        {{--<p class="mb-15">Uploadujte své soubory. Povolené formáty jsou <kbd>JPEG</kbd>, <kbd>PNG</kbd> a <kbd>GIF</kbd>.</p>--}}
                        <div class="form-level">
                            {!! Form::file('cert', ['class' => 'file-styled fileinput']) !!}
                        </div>
                        {{--<div class="form-group mt15">--}}
                            {!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-cert'] ) !!}
                            {{--<a href="{!! route('admin.companies.index') !!}" title="Zrušit" class='btn btn-default'>Zrušit</a>--}}
                        {{--</div>--}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Užívatelia</h4>

                    <table class="table datatable-sorting">
                        <thead>
                        <tr>
                            <th>Meno</th>
                            <th>E-mail</th>
                            <th>Užívatelské meno</th>
                            @foreach($roles as $r)
                                <th width="80" class="text-center">{{$r->name}}</th>
                            @endforeach
                            {{--<th width="80" class="text-center">Akcie</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users_in as $u)
                            <tr >
                                <td>{{$u->name or '-'}}</td>
                                <td>{{$u->email or '-'}}</td>
                                <td>{{$u->username or '-'}}</td>
                                @foreach($roles as $r)
                                    <td width="80" class="text-center">
                                        @if ( $users_role[$u->id][$r->id] )
                                            <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="deaktivuj"><i class="fa fa-check enabled" style="color: green"></i></a>
                                        @else
                                            <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="aktivuj"><i class="fa fa-times disabled" style="color: red"></i></a>
                                        @endif
                                    </td>
                                @endforeach
                                {{--<td width="80" class="text-center">--}}
                                    {{--<a href="{{ route('admin.users.edit', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;--}}
                                    {{--<a class="sweet_delete" href="{{ route('admin.users.delete', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
            order: [0, "asc"]
        });

@endsection
