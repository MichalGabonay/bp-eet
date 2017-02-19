<!-- Custom Tabs -->
<div class="tabbable tab-content-bordered">
    <ul class="nav nav-tabs nav-tabs-highlight">
        <li class="active"><a href="#tab_details" data-toggle="tab" aria-expanded="true">Základní informace</a></li>
        {{--<li><a href="#tab_contact" data-toggle="tab" aria-expanded="false">Kontakty</a></li>--}}
        {{--<li><a href="#tab_prices" data-toggle="tab" aria-expanded="false">Ceny</a></li>--}}
    </ul>

    <div class="tab-content">
        <div class="tab-pane has-padding active" id="tab_details">
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
        </div><!-- /.tab-pane -->

        <div class="tab-pane has-padding" id="tab_contact">

        </div>

        <div class="tab-pane has-padding" id="tab_prices">

        </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
</div>

@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/plugins/forms/tags/tagsinput.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/plugins/forms/tags/tokenfield.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/plugins/forms/inputs/maxlength.min.js") ) !!}

    {!! HTML::script( asset("/assets/admin/js/core/libraries/jquery_ui/core.min.js") ) !!}
@endsection

@section('jquery_ready')
    $('.maxlength').maxlength({
    alwaysShow: true
    });
@endsection
