@extends('admin.templates.master')


@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">

                {!! Form::model( $companies, ['route' => ['admin.companies.update', $companies->id], 'method' => 'PATCH', 'id' => 'form_edit_categories', 'files' => true]) !!}

                @include('admin.companies._form')

                <div class="form-group mt15">
                    {!! Form::button('Upraviť', ['class' => 'btn bg-teal-400', 'id' => 'btn-submit-edit', 'type' => 'submit'] ) !!}
                    <a href="{{ URL::previous() }}" title="Zrušiť" class='btn btn-default'>Zrušiť</a>
                </div>

                {!! Form::close() !!}

            </div><!-- /.box-body -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection





@section('head_js')

@endsection
