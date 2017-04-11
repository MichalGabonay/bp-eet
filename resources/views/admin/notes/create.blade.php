@extends('admin.templates.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::open( ['route' => 'admin.notes.store'] ) !!}

                @include('admin.notes._form')

                <div class="form-group mt15">
                    {!! Form::button( 'Vytvorit', ['class' => 'btn bg-teal-400', 'type' => 'submit'] ) !!}
                    <a href="{{ url()->previous() }}" title="Zrušit" class='btn btn-default'>Zrušit</a>
                </div>

                {!! Form::close() !!}
            </div><!-- /.box-body -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
