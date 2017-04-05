<div class='row'>
    {!! Form::open( ['route' => 'admin.sales.store', 'id' => 'form_add_sales', 'files' => true] ) !!}
    {{ Form::hidden('products', null, ['id' => 'products_id']) }}
    {{ Form::hidden('total_price', null, ['id' => 'total_price_id']) }}
    <div class='col-md-5'>
        <div class="box-body">
            <div class="tab-pane has-padding active" id="tab_details">
                <div class="form-group">
                    {!! Form::label('product', 'Produkt') !!}
                    {!! Form::text('product', null, ['class' => 'form-control', 'id' => 'product_name_id']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('price', 'Cena') !!}
                    {!! Form::text('price', null, ['class' => 'form-control', 'id' => 'price_id']) !!}
                </div>
            </div><!-- /.tab-pane -->

            <div class="form-group mt15">
                {!! Form::button( 'Pridať', ['class' => 'btn bg-teal-400', 'id' => 'btn-add-product'] ) !!}
            </div>

        </div><!-- /.box-body -->
    </div><!-- /.col -->
    <div class='col-md-5'>
        <table class="table">
            <thead id="products-list">
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


