@extends('admin.templates.master')

@section('top-buttons')
    <a href="{{ route('admin.users.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></b> Zpět </a>
    <a href="{{ route('admin.users.edit', $users->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></b> Upraviť </a>
    {{--<a href="{{ route('admin.users.delete', $users->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-trash fa-lg" aria-hidden="true"></i></b> Odstrániť </a>--}}
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
@endsection