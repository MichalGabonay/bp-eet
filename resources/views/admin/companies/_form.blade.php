<!-- Custom Tabs -->
<div class="tabbable tab-content-bordered">
    <ul class="nav nav-tabs nav-tabs-highlight">
        <li class="active"><a href="#tab_details" data-toggle="tab" aria-expanded="true">Základní informace</a></li>
        @if(isset($users_in))
        <li><a href="#tab_users" data-toggle="tab" aria-expanded="false">Zamestnanci</a></li>
        <li><a href="#tab_cert" data-toggle="tab" aria-expanded="false">Certifikát</a></li>
        @endif
    </ul>

    <div class="tab-content">
        <div class="tab-pane has-padding active" id="tab_details">
            <div class="form-group">
                {!! Form::label('name', 'Názov firmy') !!}
                {!! Form::text('name', null, ['class' => 'form-control maxlength', 'maxlength' => '100', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('ico', 'IČO') !!}
                {!! Form::text('ico', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('dic', 'DIČ') !!}
                {!! Form::text('dic', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
            </div>
        </div><!-- /.tab-pane -->

        @if(isset($users_in))
        <div class="tab-pane has-padding" id="tab_users">
            <div class="panel panel-flat">
                <table class="table datatable-sorting table-hover table-hover-hand">
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
                                    <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'company']) !!}" title="deaktivuj"><i class="fa fa-check enabled" style="color: green"></i></a>
                                @else
                                    <a href="{!! route('admin.roles.switch-role', [$u->id, $r->id, 'company']) !!}" title="aktivuj"><i class="fa fa-times disabled" style="color: red"></i></a>
                                @endif
                                </td>
                            @endforeach
                            <td width="80" class="text-center">
                                {{--<a href="{{ route('admin.users.edit', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fa fa-pencil"></i></a> &nbsp;--}}
                                <a class="sweet_delete" href="{{ route('admin.users.delete', $u->id) }}" data-toggle="tooltip" data-placement="top" title="Smazat"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- /.tab-pane -->


        <div class="tab-pane has-padding" id="tab_cert">

        </div><!-- /.tab-pane -->
        @endif
    </div><!-- /.tab-content -->
</div>

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
