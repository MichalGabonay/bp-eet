<!-- Custom Tabs -->
<div class="tabbable tab-content-bordered">
    <ul class="nav nav-tabs nav-tabs-highlight">
        <li class="active"><a href="#tab_info" data-toggle="tab" aria-expanded="true">Základní informace</a></li>
        @if(isset($users))
        <li><a href="#tab_roles" data-toggle="tab" aria-expanded="false">Role a spoločnosti</a></li>
        @endif
        {{--<li><a href="#tab_prices" data-toggle="tab" aria-expanded="false">Ceny</a></li>--}}
    </ul>

    <div class="tab-content">
        <div class="tab-pane has-padding active" id="tab_info">
            <div class="form-group">
                {!! Form::label('name', 'Meno') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email', 'E-mail') !!}
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('username', 'Užívatelské meno') !!}
                {!! Form::text('username', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password', 'Heslo') !!}
                {!! Form::text('password', '', ['class' => 'form-control']) !!}
            </div>
        </div><!-- /.tab-pane -->


        @if(isset($users))
        <div class="tab-pane has-padding" id="tab_roles">
            @foreach($companies as $c)
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <span><strong>Spoločnosť: </strong>{{$c->name}}</span> <a href="{{ route('admin.users.company_state', [$users->id, $c->id]) }}">{{$c->user_enabled ? 'Deaktivovat':'Aktivovat'}}</a>
                    </div>
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
                </div>
            @endforeach
        </div>
        @endif

        {{--<div class="tab-pane has-padding" id="tab_prices">--}}

        {{--</div><!-- /.tab-pane -->--}}
    </div><!-- /.tab-content -->
</div>

@section('head_js')

@endsection

@section('jquery_ready')

@endsection
