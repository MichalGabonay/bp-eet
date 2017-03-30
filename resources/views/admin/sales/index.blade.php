@extends('admin.templates.master')


@section('content')

    @if(session('isAdmin') || session('isManager'))

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting">
                            <thead>
                            <tr>
                                <th>Tržba</th>
                                {{--<th>Produkty</th>--}}
                                <th>Celková cena</th>
                                <th>Užívatel</th>
                                <th>Pridaná</th>

                                <th width="80" class="text-center">Akcie</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sales as $s)
                                <tr >
                                    <td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>
                                    {{--<td>{{$s->products or '-'}}</td>--}}
                                    <td>{{$s->total_price or '-'}}Kč</td>
                                    @if(session('isAdmin'))
                                        <td><a href="{{route('admin.users.detail',$s->user_id )}}">{{$s->user_name or '-'}}</a></td>
                                    @else
                                        <td>{{$s->user_name or '-'}}</td>
                                    @endif
                                        <td>{{date('d.m.Y H:i', strtotime($s->created_at))}}</td>

                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-th-large" aria-hidden="true"></i>&nbsp;&nbsp; Detail</a></li>
                                                    @if($s->storno == 0)
                                                    <li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>
                                                    @endif
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>

                                                    @if(session('isAdmin'))
                                                        <li><a class="sweet_delete" href="{{ route('admin.sales.delete', $s->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp; Smazat</a></li>
                                                    @endif
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div id="create-new">
        @if($cert->valid == 1)
        {{--TODO: možnosť zadat produkt s cenou 0--}}
        @include('admin.sales._form')
        @else
            <h2 class="text-center"> Bohužiaľ nie je možné zadávať nové tržby, obráťte sa na andministrátora systému k tejto firme. </h2>
        @endif
    </div>

    @if(!session('isAdmin') && !session('isManager'))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="pull-left">
                        <h4>Posledné zadané tržby</h4>
                    </div>

                    <table class="table datatable-sorting">
                        <thead>
                        <tr>
                            <th>Tržba</th>
                            {{--<th>Produkty</th>--}}
                            <th>Celková cena</th>
                            <th>Celková cena</th>

                            <th width="80" class="text-center">Akcie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sales as $s)
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
                                    {{--<a href="{{ route('admin.users.company_state', [$u->user_id, $company->id]) }}">Odobrať</a>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif


@endsection





@section('head_js')

@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm


@endsection