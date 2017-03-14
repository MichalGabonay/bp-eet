@extends('admin.templates.master')


@section('content')

    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-body" style="padding-bottom: 0">
                <p>Vložte .CSV súbor z ktorého chcete importovať tržby. <br>
                Dalšie pokyny o formáte atď.<br>
                Stiahnite si <a href="#">ukážkový súbor</a></p>
                 {!! Form::open( ['route' => 'admin.import.submit', 'id' => 'form_add_cert', 'files' => true, 'target' => 'iframe'] ) !!}
                 <div class="form-group">
                     {{--{!! Form::label('import_file', 'Vložte certifikát vo formáte pkcs12 získanom z webovej aplikácie EET (.p12)') !!}--}}
                     {!! Form::file('import_file', ['class' => 'file-styled fileinput']) !!}
                 </div>
                 {!! Form::button( 'Import tržieb', ['class' => 'btn bg-teal-400', 'type' => 'submit', 'id' => 'btn-submit-cert'] ) !!}
                 {!! Form::close() !!}

                <br>
                <div class='row text-center'>
                    <iframe src="" width="100%" height="400" name="iframe"></iframe>
                </div><!-- /.row -->

            </div>
        </div>
    </div>

        {{--<div class='row'>--}}
            {{--<a href="{{ route('admin.import.submit') }}" target="iframe" class="btn btn-default">Import tržieb</a>--}}
        {{--</div><!-- /.row -->--}}



@endsection





@section('head_js')

@endsection
