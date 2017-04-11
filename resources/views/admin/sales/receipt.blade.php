@extends('admin.templates.empty')

@section('content')
    <div class="receipt-container">
        <div class="text-center">
            @if($company->logo != '')
                <img class="receipt-logo" src="/uploads/logos/{{$company->logo}}" height="90" ><br>
            @endif
            {{$company->name}}<br>
            {{$company->address}}<br>
            IČ: {{$company->ico}} DIČ: {{$company->dic}}<br>
            Tel: {{$company->phone}}<br>
        </div>
        <br>
        <table class="table">
            <tbody>
            @foreach($products as $p)
                <tr>
                    <td width="80%">{{$p['name']}}</td>
                    <td width="20%" style="text-align: right">{{number_format($p['price'], 2, '.', '')}}</td>
                </tr>
            @endforeach
            <tr style="font-size: 200%">
                <td >Total:</td>
                <td style="float: right"><label id="total_price_label">{{number_format($sale->total_price, 2, '.', '')}}</label></td>
            </tr>
            </tbody>
        </table>
        <hr>
        <div class="text-center">
            <p>Čas platby: {{date('d.m.Y H:i:s', strtotime($sale->receipt_time))}}</p><br>
            BKP:<br>
            <p>{{$sale->bkp}}</p>
            FIK:<br>
            <p>{{$sale->fik}}</p><br>
        </div>
    </div>
@endsection