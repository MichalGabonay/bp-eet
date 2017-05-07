@extends('admin.templates.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-body" style="padding-bottom: 0">
                <p>Vložte .CSV súbor z ktorého chcete importovať tržby. <br></p>
                <p><strong>Formát súboru:</strong><br>
                Celkoá cena;FIK;BKP;Číslo účtenky;Datum uskutečnění tržby;ID provozovny;ID pokladny;Produkty (nepovinné)<br>
                Produkty zadávajte vo forme: "názov produktu||cena;názov druhého produktu||cena" atď</p>
                <p>Stiahnite si <a href="{{route('admin.import.download')}}">ukážkový súbor</a></p>
                 {!! Form::open( ['route' => 'admin.import.submit', 'files' => true, 'target' => 'iframe'] ) !!}
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <div class="form-group">
                     {!! Form::file('import_file', ['class' => 'file-styled fileinput']) !!}
                 </div>
                 {!! Form::button( 'Import tržieb', ['class' => 'btn bg-teal-400', 'type' => 'submit'] ) !!}
                 {!! Form::close() !!}
                <br>
                <div class='row text-center'>
                    <iframe src="" width="100%" height="400" name="iframe"></iframe>
                </div><!-- /.row -->
            </div>
        </div>
    </div>
@endsection