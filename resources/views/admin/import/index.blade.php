@extends('admin.templates.master')


@section('content')

    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-body" style="padding-bottom: 0">
                <p>Vložte .CSV súbor z ktorého chcete importovať tržby. <br></p>
                <p><strong>Formát súboru:</strong><br>
                Celkoá cena;FIK;BKP;Číslo účtenky;Datum uskutečnění tržby;ID provozovny;ID pokladny;Produkty (nepovinné)<br>
                Produkty zadávajte vo forme: "názov produktu||cena;názov druhého produktu||cena" atď</p>
                <p>Stiahnite si <a href="{{public_path('media\import.csv')}}" download>ukážkový súbor</a></p>
                 {!! Form::open( ['route' => 'admin.import.submit', 'id' => 'form_add_cert', 'files' => true, 'target' => 'iframe'] ) !!}
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
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
