<div class='row'>
    {!! Form::open( ['route' => 'admin.companies.store', 'id' => 'form_add_category', 'files' => true] ) !!}
    {{ Form::hidden('products', null, ['id' => 'products_id']) }}
    {{ Form::hidden('total_price', null, ['id' => 'total_price_id']) }}
    <div class='col-md-5'>
        <div class="box-body">
            <div class="tab-pane has-padding active" id="tab_details">
                <div class="form-group">
                    {!! Form::label('product', 'Produkt') !!}
                    {!! Form::text('product', null, ['class' => 'form-control maxlength', 'id' => 'product_name_id', 'maxlength' => '100', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('price', 'Cena') !!}
                    {!! Form::text('price', null, ['class' => 'form-control maxlength', 'id' => 'price_id', 'maxlength' => '15']) !!}
                </div>
            </div><!-- /.tab-pane -->

            <div class="form-group mt15">
                {!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'id' => 'btn-add-product'] ) !!}
            </div>



        </div><!-- /.box-body -->
    </div><!-- /.col -->
    <div class='col-md-5'>
        <table class="table">
            <thead>
                <tr>
                    <td width="350"><strong>Produkt</strong></td>
                    <td><strong>Cena</strong></td>
                </tr>

            </thead>
            <tbody>



                <tr>
                    <td></td>
                    <td ><label id="total_price_label">0 Kč</label></td>
                </tr>
            </tbody>
        </table>
        <div class="form-group submit-btn">
            {{--{!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'id' => 'btn-add-product'] ) !!}--}}
        </div>
    </div><!-- /.col -->
    {!! Form::close() !!}
</div><!-- /.row -->


@section('head_js')

@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $(document).ready(function() {
            $("#btn-add-product").click(function() {
                var product = jQuery('#product_name_id').val();
                var price = jQuery('#price_id').val();
                var products = jQuery('#products_id').val();
                var total_price = jQuery('#total_price_id').val();

                price = price.replace(',', '.');
                price = parseFloat(price);
                if (total_price == ''){
                    new_total_price = price;
                }else {
                    new_total_price = parseFloat(total_price)+price;
                }

//                alert(isNaN(new_total_price));
//                alert(new_total_price);

                if (product != '' && price != '' && !(isNaN(new_total_price)) ){
                    if (products == ''){
                        $('#products_id').val(product+','+price);
                        $( ".submit-btn" ).append( "<button class='btn' type='submit'>Potvrdiť</button>" );
                        $('#total_price_id').val(price)
                    }else {
                        $('#products_id').val(products+';'+product+','+price);
                        $('#total_price_id').val(new_total_price)


                    }
                    //                products = jQuery('#products_id').val();

                    $( "thead" ).append( "<tr><td>"+product+"</td><td>"+price+"</td></tr>" );

                    total_price = $('#total_price_id').val();
                    $('#total_price_label').text(total_price+' Kč');
                    $('#product_name_id').val('');
                    $('#price_id').val('');
                }

//                alert(products);
            });
        });
@endsection