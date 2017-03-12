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

        <div class="col-md-3">
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
        <div class="col-md-4">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Logo</h4>
                    @if($company->logo != '')
                    <img src="/uploads/logos/{{$company->logo}}" height="100" ><br>
                    <a class="btn change-logo-btn">Zmeniť</a>
                    <a href="{{route('admin.companies.delete_logo', $company->id)}}" class="btn delete-logo-btn">Odstrániť</a>
                    <div class="change-logo-form" style="display: none">
                    @else
                    <div class="change-logo-form">
                    @endif
                        {!! Form::open( ['route' => ['admin.companies.change_logo', $company->id ], 'id' => 'form_change_logo', 'files' => true] ) !!}
                        <div class="form-level">
                            {!! Form::file('logo', ['class' => 'file-styled fileinput']) !!}
                        </div>
                        {!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-cert'] ) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Certifikát</h4>

                    @if(isset($cert))
                        <div class="col-md-12">
                            <div class="panel panel-flat">

                                <h6 class="panel-title mb-5">Certifikát</h6>

                            </div>
                        </div>
                        <a class="btn change-cert-btn">Zmeniť</a>
                        <div class="change-cert-form" style="display: none">
                    @else
                        {{--TODO: varovna hlaska o pridani certifikatu--}}
                        <div class="change-cert-form">
                    @endif
                        <div class="col-md-12">
                            <p></p>
                            {!! Form::open( ['route' => ['admin.companies.add_cert', $company->id ], 'id' => 'form_add_cert', 'files' => true] ) !!}
                            {{--<p class="mb-15">Uploadujte své soubory. Povolené formáty jsou <kbd>JPEG</kbd>, <kbd>PNG</kbd> a <kbd>GIF</kbd>.</p>--}}
                            {{--<div class="form-level">--}}
                            {{--</div>--}}
                            <div class="form-group">
                                {!! Form::label('name', 'Vložte certifikát vo formáte pkcs12 získanom z webovej aplikácie EET (.p12)') !!}
                                {!! Form::file('cert', ['class' => 'file-styled fileinput']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', 'Heslo k certifikátu') !!}
                                {!! Form::password('password', ['class' => 'form-control maxlength']) !!}
                                {{--{!! Form::text('name', null, ['class' => 'form-control maxlength', 'maxlength' => '100', 'required']) !!}--}}
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
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="pull-left">
                        <h4>Užívatelia</h4>
                    </div>
                    <div class="pull-right">
                        {!! Form::open( ['route' => array('admin.users.add_to_company', $company->id), 'id' => 'form_add_store_to_course', 'files' => true] ) !!}

                                {{--{!! Form::select('user', $all_users, null, ['class' => 'form-control js-example-placeholder-multiple']) !!}--}}
                            <select name="user" class="js-example-placeholder-single">
                                <option></option>
                                @foreach($all_users as $au)
                                    @foreach($users_in as $u)

                                    @endforeach
                                <option value="{{$au->id}}">{{$au->name}} - {{$au->email}}</option>
                                @endforeach
                            </select>
                            {!! Form::button( 'Přidat užívatela', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-add'] ) !!}

                        {!! Form::close() !!}
                    </div>

                    <table class="table datatable-sorting">
                        <thead>
                        <tr>
                            <th>Meno</th>
                            <th>E-mail</th>
                            <th>Užívatelské meno</th>
                            @foreach($roles as $r)
                                <th width="80" class="text-center">{{$r->name}}</th>
                            @endforeach
                            <th width="80" class="text-center">Akcie</th>
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
                                <td width="80" class="text-center">
                                    <a href="{{ route('admin.users.company_state', [$u->user_id, $company->id]) }}">Odobrať</a>
                                </td>
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

        $(".js-example-placeholder-single").select2({
            placeholder: "Vyberte užívatela",
            allowClear: true
        });

        $(".change-logo-btn").click(function(){
            $(".change-logo-btn").hide(1000);
            $(".change-logo-form").show(1000);
        });

        $(".change-cert-btn").click(function(){
            $(".change-cert-btn").hide(1000);
            $(".change-cert-form").show(1000);
        });

@endsection
