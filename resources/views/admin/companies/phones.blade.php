@extends('admin.templates.master')

@section('top-buttons')
    <a href="{{ route('admin.companies.detail', $company_id)}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></b> Zpět </a>
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h4>Telefóny k spoločnosti</h4>
                    <p>Z týchto čísel sa bude dať evidovať nové tržby pomocou SMS</p>
                    <table class="table">
                        <tbody>
                        @foreach($phones as $p)
                            <tr>
                                <td>{{$p->phone}}</td>
                                <td><a href='{{ route('admin.companies.phones_delete', $p->id) }}'><i class='fa fa-trash-o' aria-hidden='true'></i>&nbsp;&nbsp; Odstrániť</a></td>
                            </tr>
                        @endforeach
                        {!! Form::open( ['route' => ['admin.companies.phones_store', $company_id], 'id' => 'form_add_category'] ) !!}
                        <tr>
                            <td>{!! Form::text('phone', null, ['class' => 'form-control']) !!}</td>
                            <td>{!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-edit'] ) !!}</td>
                        </tr>
                        {!! Form::close() !!}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head_js')

@endsection


@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

@endsection
