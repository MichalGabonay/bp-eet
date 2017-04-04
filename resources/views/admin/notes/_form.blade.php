<div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('from', 'Obdobie od') !!}
        {!! Form::text('from', null, ['class' => 'form-control date-picker', 'id' => 'date_from']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('to', 'Obdobie do') !!}
        {!! Form::text('to', null, ['class' => 'form-control date-picker', 'id' => 'date_to']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('note', 'Poznámka') !!}
    {!! Form::textarea('note', null, ['class' => 'form-control note-for-period']) !!}
</div>

@section('head_js')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $( "#date_from" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
        $( "#date_to" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
@endsection