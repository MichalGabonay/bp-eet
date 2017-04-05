<div class="form-group">
    {!! Form::label('name', 'Názov firmy') !!}
    {!! Form::text('name', null, ['class' => 'form-control maxlength', 'maxlength' => '100', 'required']) !!}
</div>
<div class="form-group">
    {!! Form::label('ico', 'IČO') !!}
    {!! Form::text('ico', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
</div>
<div class="form-group">
    {!! Form::label('dic', 'DIČ') !!}
    {!! Form::text('dic', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
</div>
<div class="form-group">
    {!! Form::label('address', 'Adresa') !!}
    {!! Form::text('address', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
</div>
<div class="form-group">
    {!! Form::label('phone', 'Telefónne číslo') !!}
    {!! Form::text('phone', null, ['class' => 'form-control maxlength', 'maxlength' => '15']) !!}
</div>
