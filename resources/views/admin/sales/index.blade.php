@extends('admin.templates.master')


@section('content')

    @if((session('isAdmin') || session('isManager')))

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
                                <th>Poznámka</th>

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

                                    @if(!is_null($s->receipt_time))
                                    <td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    <td>{{$s->note or ''}}</td>

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
                                                    <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>
                                                    @if($s->note_id == NULL)
                                                        <li><a href="{{ route('admin.notes.create', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Pridať poznámku</a></li>
                                                    @else
                                                        <li><a href="{{ route('admin.notes.edit', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Upraviť poznámku</a></li>
                                                    @endif
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
        @if(!is_null($cert) && $cert->valid == 1)
        {{--TODO: možnosť zadat produkt s cenou 0--}}
        @include('admin.sales._form')
        @else
            <h2 class="text-center"> Bohužiaľ nie je možné zadávať nové tržby, obráťte sa na andministrátora systému k tejto firme. </h2>
        @endif
    </div>

    @if(!session('isAdmin') && !session('isManager') && count($sales) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting">
                            <thead>
                            <tr>
                                <th>Tržba</th>
                                <th>Produkty</th>
                                <th>Celková cena</th>
                                <th>Pridaná</th>

                                <th width="80" class="text-center">Akcie</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sales as $s)
                                <tr >
                                    <td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>
                                    <td>{{$s->products or '-'}}</td>
                                    <td>{{$s->total_price or '-'}}Kč</td>

                                    @if(!is_null($s->receipt_time))
                                        <td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @if($s->storno == 0 && session('haveStorno'))
                                                        <li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>
                                                    @endif
                                                    <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>

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


@endsection





@section('head_js')

@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm



@endsection