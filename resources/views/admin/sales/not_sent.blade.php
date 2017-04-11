@extends('admin.templates.master')

@section('content')
    @if((session('isAdmin') || session('isManager')))
        @if(count($not_sent) > 0)
            <div class="panel panel-flat">
                <div class="panel-body">
                    <span style="color: red;">Niektoré tržby sa nepodarilo správne odoslať do EET (počet: {{count($not_sent)}})</span><a href="{{route('admin.sales.try_sent_again')}}" class="btn">Skúsiť znovu odoslať</a>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting">
                            <thead>
                                <tr>
                                    <th>Tržba</th>
                                    <th>Celková cena</th>
                                    <th>Pridaná</th>

                                    <th width="80" class="text-center">Akcie</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($sales as $s)
                                <tr >
                                    <td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>
                                    <td>{{$s->total_price or '-'}}Kč</td>

                                    @if(!is_null($s->receipt_time))
                                        <td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}} (pred {{$s->hours}} hodinami)
                                        </td>
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
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-th-large" aria-hidden="true"></i>&nbsp;&nbsp; Detail</a></li>
                                                    @if($s->storno == 0)
                                                        <li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>
                                                    @endif
                                                    <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Zobraziť účtenku</a></li>
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Pridať poznámku</a></li>
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
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $(document).ready(function() {
            $("[data-toggle='tooltip']").tooltip();
        });

        var table = $('.datatable-sorting').DataTable({
                order: [2, "desc"],
                columnDefs: [
                    { "type": "de_date", targets: 2 }],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
                }
            });
@endsection