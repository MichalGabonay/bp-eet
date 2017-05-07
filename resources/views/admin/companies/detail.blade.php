@extends('admin.templates.master')

@section('top-buttons')
    <a href="{{ route('admin.companies.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></b> Zpět </a>
    <a href="{{ route('admin.companies.edit', $company->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></b> Upraviť </a>
    <a href="{{ route('admin.companies.delete', $company->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin sweet_delete"><b><i class="fa fa-trash fa-lg" aria-hidden="true"></i></b> Odstrániť </a>
    <a href="{{ route('admin.companies.phones', $company->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-phone fa-lg" aria-hidden="true"></i></b> Telefóny </a>
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Základné informácie</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>Název: </strong></td>
                                <td> {{ $company->name or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>IČO: </strong></td>
                                <td> {{ $company->ico or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>DIČ: </strong></td>
                                <td> {{ $company->dic or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Adresa: </strong></td>
                                <td> {{ $company->address or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telefón: </strong></td>
                                <td> {{ $company->phone or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Užívatelia: </strong> </td>
                                <td> {{ $company->users or '-'  }}</td>
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
                            {!! Form::file('logo', ['class' => 'form-control file-styled fileinput']) !!}
                        </div>
                        {!! Form::button( 'Pridať logo', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-logo'] ) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Certifikát</h4>

                    @if(isset($cert))
                        @if($cert->valid == 1)
                            <strong>Stav:</strong> certifikát je platný <a href="#" data-toggle="tooltip" data-placement="top" title="je možné zadávať nové tržby"><i class="fa fa-info-circle" aria-hidden="true"></i></a><br>
                            <strong>Datum Expirácie:</strong> {{date('d.m.Y', strtotime($cert->expiration_date))}}<br><br>
                        @else
                            <strong>Stav:</strong> neplatný <a href="#" data-toggle="tooltip" data-placement="top" title="Nie je možné zadávať nové tržby, skúste vložiť ešte raz súbor ktorý ste dostali od finančného úradu so správnym heslo, ak heslo nemáte, nechajte prázdne pole."><i class="fa fa-info-circle" aria-hidden="true"></i></a><br>
                        @endif

                        <a class="btn change-cert-btn">Zmeniť</a>
                        <div class="change-cert-form" style="display: none">
                    @else
                        {{--TODO: varovna hlaska o pridani certifikatu--}}
                        <div class="change-cert-form">
                    @endif
                            {!! Form::open( ['route' => ['admin.companies.add_cert', $company->id ], 'id' => 'form_add_cert', 'files' => true] ) !!}
                            <div class="form-group">
                                {!! Form::label('name', 'Vložte certifikát vo formáte pkcs12 získanom z webovej aplikácie EET (.p12)') !!}
                                {!! Form::file('cert', ['class' => 'file-styled fileinput']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', 'Heslo k certifikátu') !!}
                                {!! Form::password('password', ['class' => 'form-control maxlength']) !!}
                            </div>
                                {!! Form::button( 'Pridať', ['name' => 'Pridať', 'class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-cert'] ) !!}
                            {!! Form::close() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h4>Užívatelia</h4>
                    </div>
                    <div class="pull-right">
                        {!! Form::open( ['route' => array('admin.users.add_to_company', $company->id), 'id' => 'form_add_store_to_course', 'files' => true] ) !!}
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
                </div>
                <div class="clearfix"></div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table datatable-sorting table-striped table-hover dt-responsive display nowrap" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Meno</th>
                                <th>E-mail</th>
                                <th>Užívatelské meno</th>
                                @foreach($roles as $r)
                                    <th class="text-center">{{$r->name}} <a href="#" data-toggle="tooltip" data-placement="top" title="{{$r->description}}"><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                @endforeach
                                <th width="30" class="text-center">Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users_in as $u)
                            <tr >
                                <td>{{$u->name or '-'}}</td>
                                <td>{{$u->email or '-'}}</td>
                                <td>{{$u->username or '-'}}</td>
                                @foreach($roles as $r)
                                    <td class="text-center">
                                        @if ( $users_role[$u->id][$r->id] )
                                            <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="deaktivuj"><i class="fa fa-check enabled" style="color: green"></i></a>
                                        @else
                                            <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'companies']) !!}" title="aktivuj"><i class="fa fa-times disabled" style="color: red"></i></a>
                                        @endif
                                    </td>
                                @endforeach
                                <td width="30" class="text-center">
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
    </div>
@endsection

@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $('.datatable-sorting').DataTable({
            order: [0, "asc"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
            }
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

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
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
