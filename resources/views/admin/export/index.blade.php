@extends('admin.templates.master')


@section('content')
    {!! Form::open(['route' => 'admin.export.submit', 'method' => 'POST']) !!}

    <h3>Vyberte stĺpce, ktoré chcete vo výslednom exporte.</h3>
    <!-- Default ordering -->
    <div class="panel panel-flat">
        <table class="table datatable-basic table-bordered">
            <thead>
            <tr>
                <th>Celkoá cena</th>
                <th>FIK</th>
                <th>BKP</th>
                <th>Číslo účtenky</th>
                <th>Datum uskutečnění tržby</th>
                <th>ID provozovny</th>
                <th>ID pokladny</th>
                <th>Produkty</th>
            </tr>

            <tr>
                <th> {!! Form::checkbox('total_price', 1,              true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('fik', 1,            true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('bkp', 1,            true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('receipt_number', 1,      true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('receipt_time', 1,         true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('premise_id', 1,         true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('cash_register', 1,         true, ['class' => 'switchery']) !!}</th>
                <th> {!! Form::checkbox('products', 1,         true, ['class' => 'switchery']) !!}</th>
            </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>

    <div class="form-group mt15">
        {!! Form::button('Potvrdiť export', ['class' => 'btn bg-teal-400', 'id' => 'btn-submit-edit', 'type' => 'submit'] ) !!}
    </div>

    {!! Form::close() !!}

@endsection




@section('head_js')

@endsection
