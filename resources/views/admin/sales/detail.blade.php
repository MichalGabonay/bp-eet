@extends('admin.templates.master')

@section('top-buttons')
    <a href="{{ route('admin.sales.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></b> Spät </a>
    @if($sales->storno == 0)
    <a href="{{ route('admin.sales.storno', $sales->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class='fa fa-ban fa-lg' aria-hidden='true'></i></b> Storno </a>
    @endif
    <a href="{{ route('admin.sales.generate_receipt', $sales->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin" target="_blank"><b><i class='fa fa-list-alt fa-lg' aria-hidden='true'></i></b> Zobraziť účtenku </a>
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Informácie o zadanej tržbe</h4>
                    <table class="table">
                        <tbody >
                            <tr>
                                <td><strong>Číslo účtenky: </strong></td>
                                <td> {{ $sales->receiptNumber or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Užívatel: </strong></td>
                                @if(session('isAdmin'))
                                    <td> <a href="{{route('admin.users.detail', $user->id)}}">{{ $user->name }}</a></td>
                                @else
                                    <td> {{ $user->name or '-' }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td><strong>FIK: </strong></td>
                                <td> {{ $sales->fik or '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>BKP: </strong> </td>
                                <td> {{ $sales->bkp or '-'  }}</td>
                            </tr>
                            <tr>
                                <td><strong>Celková cena: </strong> </td>
                                <td> {{ $sales->total_price or '-'  }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4 class="pull-left">Poznámky</h4>
                    {{--<a class="btn new-note-btn pull-right">Nová</a>--}}
                    <div class="clearfix"></div>
                    <div class="new-note-form">
                        {!! Form::open( ['route' => ['admin.notes.store', $sales->id], 'class' => 'form-inline', 'id' => 'form_add_note'] ) !!}
                        {!! Form::text('note', null, ['class' => 'form-control new-note-input', 'required']) !!}
                        {!! Form::button( 'Zapíš novú', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-edit new-note-btn'] ) !!}
                        {!! Form::close() !!}
                    </div>
                    <table class="table datatable-sorting2">
                        <thead>
                        <tr>
                            <th>Poznámka</th>
                            <th>Pridaná</th>
                            <th width="30" class="text-center">Akcie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notes as $n)
                            <tr >
                                <td>{{$n->note or '-'}}</td>
                                <td>{{date('d.m.Y H:i', strtotime($n->created_at))}}</td>
                                <td class="text-center"><a class="sweet_delete" href="{{ route('admin.sales.delete', $n->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(!empty($products))
        <div class='col-md-5'>
            <table class="table">
                <thead id="products-list">
                <tr>
                    <td width="350"><strong>Produkt</strong></td>
                    <td><strong>Cena</strong></td>
                </tr>

                </thead>
                <tbody>
                @foreach($products as $p)
                    <tr>
                        <td>{{$p['name']}}</td>
                        <td>{{$p['price']}}</td>
                    </tr>
                @endforeach



                <tr>
                    <td></td>
                    <td ><label id="total_price_label">{{$sales->total_price}} Kč</label></td>
                </tr>
                </tbody>
            </table>
            <div class="form-group submit-btn">
                {{--{!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'id' => 'btn-add-product'] ) !!}--}}
            </div>
        </div><!-- /.col -->
        @endif
    </div>
@endsection

@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
@endsection


@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $(".new-note-btn").click(function(){
//            $(".new-note-btn").hide(1000);
            $(".new-note-form").show(300);
        });

        $('.datatable-sorting2').DataTable({
            order: [1, "asc"],
            bPaginate: false,
            "bInfo" : false,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
            }
        });


@endsection
