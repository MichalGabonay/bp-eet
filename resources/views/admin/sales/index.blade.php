@extends('admin.templates.master')

@section('content')
    @if((session('isAdmin') || session('isManager')))
        @if(count($not_sent) > 0)
            <div class="panel panel-flat">
                <div class="panel-body">
                    <span style="color: red;">Niektoré tržby sa nepodarilo správne odoslať do EET (počet: {{count($not_sent)}})</span><a href="{{route('admin.sales.not_sent')}}" class="btn">Zobraziť</a><a href="{{route('admin.sales.try_sent_again')}}" class="btn">Skúsiť znovu odoslať</a>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-9">
                <div id="dashboard1_div">
                    <div id="daily_container_count_lineChart" style="width: 100%; height: 300px"></div>
                    <div id="control_div" style="width: 100%; height: 50px"></div>
                </div>
                <br>
                <div id="dashboard2_div">
                    <div id="control1"></div>
                    <div id="control2"></div>
                    <div id="table_div" style="width: 100%"></div>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div>
                    <input type="button" class="btn bg-teal-400" id="ChangeRangeThisWeek" value="Týždeň" />
                    <input type="button" class="btn bg-teal-400" id="ChangeRangeThisMonth" value="Mesiac" /><br><br>
                    {!! Form::open( ['route' => 'admin.notes.store', 'id' => 'form_add_period_note'] ) !!}
                    <div class="row" >
                        <div class="col-md-6">
                            <strong>Obdobie od:</strong><br>
                            <span id="rangeStart"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Obdobie do:</strong><br>
                            <span id="rangeEnd"></span>
                        </div>
                    </div>
                    <a class="btn add_period_note_btn">Pridať poznámku k obdobiu </a>

                    <div class="add_period_note_form" style="display: none">
                        {!! Form::hidden('from', null, ['id' => 'date_from']) !!}
                        {!! Form::hidden('to', null, ['id' => 'date_to']) !!}
                        {!! Form::hidden('backto', 1, ['id' => 'backto']) !!}
                        {!! Form::textarea('note', null, ['class' => 'form-control note-for-period']) !!}
                        {!! Form::button( 'Zapíš', ['type' => 'submit', 'class' => 'btn bg-teal-400', 'id' => 'btn-add-period-note'] ) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
                <br>
                <div class="period-notes-table">
                    <h4 class="text-center">Poznámky k obdobiam</h4>
                    @foreach($period_notes as $n)
                        <div class="period-note-period">
                            <span><strong>{{date('d.m.Y', strtotime($n->from))}} - {{date('d.m.Y', strtotime($n->to))}}</strong></span>
                        </div>

                        <div class="period-note-note">
                            <span>{{$n->note}}</span>
                        </div>
                    @endforeach

                    <div class="text-center">
                        <a href="{{ route('admin.notes.index')}}" class="btn bg-teal-400"></b> Zobraziť všetky </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="create-new">
        @if(!is_null($cert) && $cert->valid == 1)
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
                    </div>
                </div><!-- /.col -->
                {!! Form::close() !!}
            </div><!-- /.row -->
        @else
            <h2 class="text-center"> Bohužiaľ nie je možné zadávať nové tržby, obráťte sa na andministrátora systému k tejto firme. </h2>
        @endif
    </div>

    @if(!session('isAdmin') && !session('isManager') && count($sales) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable-sorting table-striped table-hover dt-responsive display nowrap" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tržba</th>
                                        <th>Produkty</th>
                                        <th>Celková cena</th>
                                        <th>Pridaná</th>
                                        <th width="80" class="text-center">Akcie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($sales as $s)
                                    <tr >
                                        <td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>
                                        <td>{{$s->products or '-'}}</td>
                                        <td>{{$s->total_price or '-'}}Kč</td>

                                        @if(!is_null($s->receipt_time))
                                            <td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}}</td>
                                        @else
                                            <td>-</td>
                                        @endif

                                        <td class="text-center">
                                            <ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        @if($s->storno == 0 && session('haveStorno'))
                                                            <li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>
                                                        @endif
                                                        <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Zobraziť účtenku</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}

    @if((session('isAdmin') || session('isManager')))

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['controls', 'corechart', 'table', 'bar'], 'language': 'sk'});
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            //data for chart
            var data = google.visualization.arrayToDataTable([
                [
                    {label: 'Datum', id: 'date', type: 'date'},
                    {label: 'Celková tržba dňa', id: 'Sales', type: 'number'},
                    {label: 'Počet platieb', id: 'Count', type: 'number', role: 'anotation'}
                ],
                    @foreach($sales as $s)
                [new Date('{{$s->date}}'), {{$s->total_price}}, {{$s->count}}],
                @endforeach
            ]);

            //data for table
            var dataOther = google.visualization.arrayToDataTable([
                [{label: 'Stav'},{label: 'Dátum pridania', type: 'datetime'},{label: 'ID Tržby', id: 'sales', type: 'string'},{label: 'Užívatel'},{label: 'Poznámka', type: 'string'},{label: 'Celková cena', type: 'number'},{label: 'Akcie', type: 'string'}],
                    @foreach($sales_all as $s)
                [@if($s->storno == 1)
                "<a href='#' data-toggle='tooltip' data-placement='top' title='stornovaná' class='tooltip-storno'><i class='fa fa-times-circle' aria-hidden='true'></i></a>",
                        @elseif($s->not_sent == 1 || $s->not_sent == 2)
                            "<a href='#' data-toggle='tooltip' data-placement='top' title='neodoslaná na EET' class='tooltip-not-sent'><i class='fa fa-exclamation-circle' aria-hidden='true'></i></i></a>",
                        @else
                            "<a href='#' data-toggle='tooltip' data-placement='top' title='úspešne odoslaná na EET' class='tooltip-okay'><i class='fa fa-check-circle' aria-hidden='true'></i></a>",
                        @endif
                    new Date('{{$s->receipt_time}}'),
                    '{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}',
                    {{--@if(session('isAdmin'))--}}
                        {{--"<a href='{{route('admin.users.detail',$s->user_id )}}'>{{$s->user_name or '-'}}</a>",--}}
                    {{--@else--}}
                        "{{$s->user_name or '-'}}",
                    {{--@endif--}}
                    @if(isset($note[$s->id]))
                    "{{$note[$s->id]}}",
                    @else
                        "",
                    @endif
                    {{$s->total_price}},
                    "<ul class='icons-list'><li class='dropdown'>" +
                    "<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-bars' aria-hidden='true'></i></a>" +
                    "<ul class='dropdown-menu dropdown-menu-right'>" +
                    "<li><a href='{{ route('admin.sales.detail', $s->id) }}'><i class='fa fa-th-large' aria-hidden='true'></i>&nbsp;&nbsp; Detail</a></li>" +
                    @if($s->storno == 0)
                        "<li><a href='{{ route('admin.sales.storno', $s->id) }}'><i class='fa fa-ban' aria-hidden='true'></i>&nbsp;&nbsp; Storno</a></li>" +
                    @endif
                        "<li><a href='{{ route('admin.sales.generate_receipt', $s->id) }}' target='_blank'><i class='fa fa-list-alt' aria-hidden='true'></i>&nbsp;&nbsp; Zobraziť účtenku</a></li>" +
                    "<li><a href='{{ route('admin.sales.detail', $s->id) }}'><i class='fa fa-sticky-note-o' aria-hidden='true'></i>&nbsp;&nbsp; Pridať poznámku</a></li>" +

                            @if(session('isAdmin'))
                        "<li><a class='sweet_delete' href='{{ route('admin.sales.delete', $s->id) }}'><i class='fa fa-trash-o' aria-hidden='true'></i>&nbsp;&nbsp; Smazat</a></li>" +
                    @endif
                        "</ul></li></ul>"],
                @endforeach
            ]);

            var chart = new google.visualization.ChartWrapper({
                chartType: 'Bar',
                containerId: 'daily_container_count_lineChart',
                options: {
                    bars: 'vertical',
                    title: 'Predaje',
                    hAxis: {
                        format: 'dd.MM.yyyy'
                    },
                    legend: { position: "none" }
                }
            });

            var control = new google.visualization.ControlWrapper({
                controlType: 'ChartRangeFilter',
                containerId: 'control_div',
                options: {
                    filterColumnIndex: 0
                }
            });

            var table = new google.visualization.ChartWrapper({
                chartType: 'Table',
                containerId: 'table_div',
                dataTable: dataOther,
                options: {
                    sortColumn: 1,
                    'sortAscending': false,
                    page: 'enable',
                    allowHtml: true,
                    width: '100%'
                }
            });

            // Define a StringFilter control for the 'Name' column
            var stringFilter = new google.visualization.ControlWrapper({
                'controlType': 'StringFilter',
                'containerId': 'control1',
                dataTable: dataOther,
                'options': {
                    'filterColumnIndex': 2
                }
            });

            // Define a StringFilter control for the 'Name' column
            var stringFilter2 = new google.visualization.ControlWrapper({
                'controlType': 'StringFilter',
                'containerId': 'control2',
                dataTable: dataOther,
                'options': {
                    'filterColumnIndex': 3
                }
            });

            //write filter range at beggining
            google.visualization.events.addListener(control, 'ready', function () {
                var rangeStart;
                var rangeEnd;
                var state = control.getState();
                rangeStart = new Date(state.range.start);
                rangeEnd = new Date(state.range.end);

                var dd = rangeStart.getDate();
                var mm = rangeStart.getMonth()+1;
                var yyyy = rangeStart.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                }
                if(mm<10){
                    mm='0'+mm;
                }
                var dateStart = dd+'.'+mm+'.'+yyyy;
                var dateStart2 = yyyy+'-'+mm+'-'+dd;
                document.getElementById('date_from').value = dateStart2;
                document.getElementById('rangeStart').innerHTML = dateStart;

                dd = rangeEnd.getDate();
                mm = rangeEnd.getMonth()+1;
                yyyy = rangeEnd.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                }
                if(mm<10){
                    mm='0'+mm;
                }
                var dateEnd = dd+'.'+mm+'.'+yyyy;
                var dateEnd2 = yyyy+'-'+mm+'-'+dd;
                document.getElementById('date_to').value = dateEnd2;
                document.getElementById('rangeEnd').innerHTML = dateEnd;
            });

            //event for control date from-to
            google.visualization.events.addListener(control, 'statechange', function () {
                var rangeStart;
                var rangeEnd;
                var state = control.getState();
                var view = new google.visualization.DataView(dataOther);
                state.range.start.setHours(0, 0, 1);
                state.range.end.setHours(23, 59, 59);
                view.setRows(view.getFilteredRows([{column: 1, minValue: state.range.start, maxValue: state.range.end}]));
                rangeStart = new Date(state.range.start);
                rangeEnd = new Date(state.range.end);

                var dd = rangeStart.getDate();
                var mm = rangeStart.getMonth()+1;
                var yyyy = rangeStart.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                }
                if(mm<10){
                    mm='0'+mm;
                }
                var dateStart = dd+'.'+mm+'.'+yyyy;
                var dateStart2 = yyyy+'-'+mm+'-'+dd;
                document.getElementById('date_from').value = dateStart2;
                document.getElementById('rangeStart').innerHTML = dateStart;

                dd = rangeEnd.getDate();
                mm = rangeEnd.getMonth()+1;
                yyyy = rangeEnd.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                }
                if(mm<10){
                    mm='0'+mm;
                }
                var dateEnd = dd+'.'+mm+'.'+yyyy;
                var dateEnd2 = yyyy+'-'+mm+'-'+dd;
                document.getElementById('date_to').value = dateEnd2;
                document.getElementById('rangeEnd').innerHTML = dateEnd;
                table.setDataTable(view);
                stringFilter.setDataTable(view);
                stringFilter2.setDataTable(view);
                table.draw();
            });

            var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard1_div'));
            dashboard.bind(control, chart);
            dashboard.draw(data);

            var dashboard2 = new google.visualization.Dashboard(document.getElementById('dashboard2_div'));
            dashboard2.bind([stringFilter, stringFilter2], table);

            dashboard2.draw(dataOther);
            table.draw();
//            table.draw(dataOther, {width: '100%', height: '100%'});

            //event for btn this week
            google.visualization.events.addListener(dashboard, 'ready', function () {
                document.getElementById('ChangeRangeThisWeek').onclick = function () {
                    var rangeStart;
                    var rangeEnd;
                    var today = Date.now();
                    today = new Date(today);
                    var weekBefore = new Date(today.getTime() - 7*24*60*60*1000);
                    control.setState({
                        range: {
                            start: weekBefore,
                            end: new Date(today)
                        }
                    });
                    control.draw();
                    var state = control.getState();
                    var view = new google.visualization.DataView(dataOther);
                    state.range.start.setHours(0, 0, 1);
                    state.range.end.setHours(23, 59, 59);
                    view.setRows(view.getFilteredRows([{column: 1, minValue: state.range.start, maxValue: state.range.end}]));
                    rangeStart = new Date(state.range.start);
                    rangeEnd = new Date(state.range.end);

                    var dd = rangeStart.getDate();
                    var mm = rangeStart.getMonth()+1;
                    var yyyy = rangeStart.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateStart = dd+'.'+mm+'.'+yyyy;
                    var dateStart2 = yyyy+'-'+mm+'-'+dd;
                    document.getElementById('date_from').value = dateStart2;
                    document.getElementById('rangeStart').innerHTML = dateStart;

                    dd = rangeEnd.getDate();
                    mm = rangeEnd.getMonth()+1;
                    yyyy = rangeEnd.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateEnd = dd+'.'+mm+'.'+yyyy;
                    var dateEnd2 = yyyy+'-'+mm+'-'+dd;
                    document.getElementById('date_to').value = dateEnd2;
                    document.getElementById('rangeEnd').innerHTML = dateEnd;
                    table.setDataTable(view);
                    table.draw();
                }
            });

            //event for btn this month
            google.visualization.events.addListener(dashboard, 'ready', function () {
                document.getElementById('ChangeRangeThisMonth').onclick = function () {
                    var rangeStart;
                    var rangeEnd;
                    var today = Date.now();
                    today = new Date(today);

                    var weekBefore = new Date(today.getTime() - 31*24*60*60*1000);
                    control.setState({
                        range: {
                            start: weekBefore,
                            end: new Date(today)
                        }
                    });
                    control.draw();
                    var state = control.getState();
                    state.range.start.setHours(0, 0, 1);
                    state.range.end.setHours(23, 59, 59);
                    var view = new google.visualization.DataView(dataOther);
                    state.range.start.setHours(0, 0, 1);
                    state.range.end.setHours(23, 59, 59);
                    view.setRows(view.getFilteredRows([{column: 1, minValue: state.range.start, maxValue: state.range.end}]));
                    rangeStart = new Date(state.range.start);
                    rangeEnd = new Date(state.range.end);

                    var dd = rangeStart.getDate();
                    var mm = rangeStart.getMonth()+1;
                    var yyyy = rangeStart.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateStart = dd+'.'+mm+'.'+yyyy;
                    var dateStart2 = yyyy+'-'+mm+'-'+dd;
                    document.getElementById('date_from').value = dateStart2;
                    document.getElementById('rangeStart').innerHTML = dateStart;

                    dd = rangeEnd.getDate();
                    mm = rangeEnd.getMonth()+1;
                    yyyy = rangeEnd.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateEnd = dd+'.'+mm+'.'+yyyy;
                  var dateEnd2 = yyyy+'-'+mm+'-'+dd;
                    document.getElementById('date_to').value = dateEnd2;
                    document.getElementById('rangeEnd').innerHTML = dateEnd;
                    table.setDataTable(view);
                    table.draw();
                }
            });

            dashboard.draw(data);

            // When the orgchart is selected, update the table chart.
            google.visualization.events.addListener(chart, 'select', function() {
                var state = control.getState();
                var view = new google.visualization.DataView(data);
                state.range.start.setHours(0, 0, 1);
                state.range.end.setHours(23, 59, 59);
                view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
                var selection = chart.getChart().getSelection();
                if (selection.length) {
                    var date = view.getValue(selection[0].row, 0);
                    date = new Date(date);
                    startDate = date.setHours(0, 0, 0);
                    endDate = date.setHours(23, 59, 59);
                    view = new google.visualization.DataView(dataOther);
                    state.range.start.setHours(0, 0, 1);
                    state.range.end.setHours(23, 59, 59);
                    view.setRows(view.getFilteredRows([{column: 1, minValue: startDate, maxValue: endDate}]));
                    table.setDataTable(view);
                    table.draw();
                }else {
                    var rangeStart;
                    var rangeEnd;
                    state = control.getState();
                    view = new google.visualization.DataView(dataOther);
                    state.range.start.setHours(0, 0, 1);
                    state.range.end.setHours(23, 59, 59);
                    view.setRows(view.getFilteredRows([{column: 1, minValue: state.range.start, maxValue: state.range.end}]));

                    rangeStart = new Date(state.range.start);
                    rangeEnd = new Date(state.range.end);

                    var dd = rangeStart.getDate();
                    var mm = rangeStart.getMonth()+1;
                    var yyyy = rangeStart.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateStart = dd+'.'+mm+'.'+yyyy;
                    var dateStart2 = yyyy+'-'+mm+'-'+dd;
//                document.getElementById('rangeStart').value = dateStart;
                    document.getElementById('date_from').value = dateStart2;
                    document.getElementById('rangeStart').innerHTML = dateStart;

                    dd = rangeEnd.getDate();
                    mm = rangeEnd.getMonth()+1;
                    yyyy = rangeEnd.getFullYear();
                    if(dd<10){
                        dd='0'+dd;
                    }
                    if(mm<10){
                        mm='0'+mm;
                    }
                    var dateEnd = dd+'.'+mm+'.'+yyyy;
                    var dateEnd2 = yyyy+'-'+mm+'-'+dd;
//                    document.getElementById('rangeEnd').value = dateEnd;
                    document.getElementById('date_to').value = dateEnd2;
                    document.getElementById('rangeEnd').innerHTML = dateEnd;
                    table.setDataTable(view);
                    stringFilter.setDataTable(view);
                    stringFilter2.setDataTable(view);
                    table.draw();
                }
            });
        }//end drawChart()
    </script>
    @endif
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $(document).ready(function() {

            $("[data-toggle='tooltip']").tooltip();

            jQuery('#products_id').val('');
            jQuery('#total_price_id').val('');

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

                if (product != '' && price != '' && !(isNaN(new_total_price)) ){
                    if (products == ''){
                        $('#products_id').val(product+'||'+price);
                        $( ".submit-btn" ).append( "<button class='btn' type='submit'>Potvrdiť</button>&nbsp&nbsp" );
                        $( ".submit-btn" ).append( "<button class='btn' name='with_receipt' type='submit'>Potvrdiť s účtenkou</button>" );
                        $('#total_price_id').val(price)
                    }else {
                        $('#products_id').val(products+';'+product+'||'+price);
                        $('#total_price_id').val(new_total_price)
                    }

                    $( "#products-list" ).append( "<tr><td>"+product+"</td><td>"+price+"</td></tr>" );

                    total_price = $('#total_price_id').val();
                    $('#total_price_label').text(total_price+' Kč');
                    $('#product_name_id').val('');
                    $('#price_id').val('');
                }
            });
        });

        @if(!session('isAdmin') && !session('isManager') && count($sales) > 0)
            var table = $('.datatable-sorting').DataTable({
                order: [3, "desc"],
                columnDefs: [
                    { "type": "de_date", targets: 3 }],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
                }
            });
        @endif

    $(".add_period_note_btn").click(function(){
            $(".add_period_note_btn").hide(800);
            $(".add_period_note_form").show(800);
        });

@endsection