@extends('admin.templates.master')
{{--@section('breadcrumb-right')--}}
    {{--<li><a href="{{ route('admin.companies.create') }}"><i class="fa fa-plus-square-o position-left"></i> Vložit spolocnost</a></li>--}}
{{--@endsection--}}

@section('top-buttons')
    <a href="{{ route('admin.sales.index')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Spät </a>
    @if($sales->storno == 0)
    <a href="{{ route('admin.sales.storno', $sales->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> Storno </a>
    @endif
    <a href="{{ route('admin.sales.generate_receipt', $sales->id)}}" class="btn bg-teal-400 btn-labeled labeled-margin" target="_blank"><b><i class="icon-arrow-left16"></i></b> Generovať účtenku </a>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-7">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Informácie o zadanej tržbe</h4>
                    <table>
                        <tbody>
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

        $('.datatable-sorting').DataTable({
            order: [3, "asc"]
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
