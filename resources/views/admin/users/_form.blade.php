<div class="form-group">
    {!! Form::label('name', 'Meno') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group">
    {!! Form::label('email', 'E-mail') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('phone_number', 'Tel. číslo') !!}
    {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('username', 'Užívatelské meno') !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('password', 'Heslo') !!}
    {!! Form::text('password', '', ['class' => 'form-control']) !!}
</div>
