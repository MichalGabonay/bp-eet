@extends('admin.templates.master')


@section('content')

    {{--<a href="{{ route('admin.sales.test')}}" class="btn bg-teal-400 btn-labeled labeled-margin"><b><i class="icon-arrow-left16"></i></b> test </a>--}}


    @if((session('isAdmin') || session('isManager')))

        <div class="row">
            <div class="col-md-9">
                <div id="dashboard1_div">
                    <div id="daily_container_count_lineChart" style="width: 100%; height: 300px"></div>
                    <div id="control_div" style="width: 100%; height: 50px"></div>
                </div>
                <br>
                <div id="dashboard2_div">
                    <div id="control1"></div>
                    <div id="table_div" style="width: 100%"></div>
                </div>
            </div>

            <div class="col-md-3">
                <div>
                    <input type="button" id="ChangeRangeThisWeek" value="Týždeň" />
                    <input type="button" id="ChangeRangeThisMonth" value="Mesiac" /><br><br>
                    {!! Form::open( ['route' => 'admin.notes.store', 'id' => 'form_add_period_note'] ) !!}
                    <div class="row" >
                        <div class="col-md-6">
                            <strong>Obdobie od:</strong><br>
                            {{--<input type="text" class="date-picker" id="rangeStart">--}}
                            <span id="rangeStart"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Obdobie do:</strong><br>
                            {{--<input type="text" class="date-picker" id="rangeEnd">--}}
                            <span id="rangeEnd"></span>
                        </div>
                    </div>
                    <a class="btn bg-teal-400 add_period_note_btn">Pridať poznámku k obdobiu </a>

                    <div class="add_period_note_form" style="display: none">
                        {!! Form::hidden('from', null, ['id' => 'date_from']) !!}
                        {!! Form::hidden('to', null, ['id' => 'date_to']) !!}
                        {!! Form::textarea('note', null, ['class' => 'form-control note-for-period']) !!}
                        {!! Form::button( 'Zapíš', ['type' => 'submit', 'class' => 'btn bg-teal-400', 'id' => 'btn-add-product'] ) !!}
                    </div>


                    {!! Form::close() !!}
                </div>
                <div class="period-notes-table">

                </div>
            </div>
        </div>

        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
                {{--<div class="panel panel-flat">--}}
                    {{--<div class="panel-body">--}}
                        {{--<table class="table datatable-sorting">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>Tržba</th>--}}
                                {{--<th>Produkty</th>--}}
                                {{--<th>Celková cena</th>--}}
                                {{--<th>Užívatel</th>--}}
                                {{--<th>Pridaná</th>--}}
                                {{--<th>Poznámka</th>--}}

                                {{--<th width="80" class="text-center">Akcie</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--@foreach($sales as $s)--}}
                                {{--<tr >--}}
                                    {{--<td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>--}}
                                    {{--<td>{{$s->products or '-'}}</td>--}}
                                    {{--<td>{{$s->total_price or '-'}}Kč</td>--}}
                                    {{--@if(session('isAdmin'))--}}
                                        {{--<td><a href="{{route('admin.users.detail',$s->user_id )}}">{{$s->user_name or '-'}}</a></td>--}}
                                    {{--@else--}}
                                        {{--<td>{{$s->user_name or '-'}}</td>--}}
                                    {{--@endif--}}

                                    {{--@if(!is_null($s->receipt_time))--}}
                                    {{--<td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}}</td>--}}
                                    {{--@else--}}
                                        {{--<td>-</td>--}}
                                    {{--@endif--}}

                                    {{--<td>{{$s->note or ''}}</td>--}}

                                    {{--<td class="text-center">--}}
                                        {{--<ul class="icons-list">--}}
                                            {{--<li class="dropdown">--}}
                                                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                                                    {{--<i class="fa fa-bars" aria-hidden="true"></i>--}}
                                                {{--</a>--}}

                                                {{--<ul class="dropdown-menu dropdown-menu-right">--}}
                                                    {{--<li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-th-large" aria-hidden="true"></i>&nbsp;&nbsp; Detail</a></li>--}}
                                                    {{--@if($s->storno == 0)--}}
                                                    {{--<li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>--}}
                                                    {{--@endif--}}
                                                    {{--<li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>--}}
                                                    {{--@if($s->note_id == NULL)--}}
                                                        {{--<li><a href="{{ route('admin.notes.create', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Pridať poznámku</a></li>--}}
                                                    {{--@else--}}
                                                        {{--<li><a href="{{ route('admin.notes.edit', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Upraviť poznámku</a></li>--}}
                                                    {{--@endif--}}
                                                    {{--@if(session('isAdmin'))--}}
                                                        {{--<li><a class="sweet_delete" href="{{ route('admin.sales.delete', $s->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp; Smazat</a></li>--}}
                                                    {{--@endif--}}
                                                {{--</ul>--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    @endif


    <div id="create-new">
        @if(!is_null($cert) && $cert->valid == 1)
        @include('admin.sales._form')
        @else
            <h2 class="text-center"> Bohužiaľ nie je možné zadávať nové tržby, obráťte sa na andministrátora systému k tejto firme. </h2>
        @endif
    </div>

    @if(!session('isAdmin') && !session('isManager') && count($sales) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table class="table datatable-sorting">
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
                                                    <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>

                                                    @if(session('isAdmin'))
                                                        <li><a class="sweet_delete" href="{{ route('admin.sales.delete', $s->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp; Smazat</a></li>
                                                    @endif
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
    @endif


@endsection





@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}

    @if((session('isAdmin') || session('isManager')))
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

            var dataOther = google.visualization.arrayToDataTable([
                [{label: 'Dátum pridania', type: 'datetime'},{label: 'ID Tržby', id: 'sales', type: 'string'},{label: 'Užívatel'},{label: 'Poznámka', type: 'string'},{label: 'Celková cena', type: 'number'},{label: 'Akcie', type: 'string'}],
                    @foreach($sales_all as $s)
                [new Date('{{$s->receipt_time}}'),
                    '{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}',
                    @if(session('isAdmin'))
                        "<a href='{{route('admin.users.detail',$s->user_id )}}'>{{$s->user_name or '-'}}</a>",
                    @else
                        "{{$s->user_name or '-'}}",
                    @endif
                    @if(isset($note[$s->id]))
                    "{{$note[$s->id]}}",
                    @else
                        "",
                    @endif
                    {{$s->total_price}},
                    "<ul class='icons-list'><li class='dropdown'>" +
                    "<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-bars' aria-hidden='true'></i></a>" +
                    "<ul class='dropdown-menu dropdown-menu-right'>" +
                    "<li><a href='{{ route('admin.sales.detail', $s->id) }}'><i class='fa fa-th-large' aria-hidden='true'></i> Detail</a></li>" +
                    @if($s->storno == 0)
                        "<li><a href='{{ route('admin.sales.storno', $s->id) }}'><i class='fa fa-ban' aria-hidden='true'></i>&nbsp;&nbsp; Storno</a></li>" +
                    @endif
                        "<li><a href='{{ route('admin.sales.generate_receipt', $s->id) }}' target='_blank'><i class='fa fa-list-alt' aria-hidden='true'></i> Generovať účtenku</a></li>" +
                    "<li><a href='{{ route('admin.sales.detail', $s->id) }}'><i class='fa fa-sticky-note-o' aria-hidden='true'></i> Pridať poznámku</a></li>" +

                            @if(session('isAdmin'))
                        "<li><a class='sweet_delete' href='{{ route('admin.sales.delete', $s->id) }}'><i class='fa fa-trash-o' aria-hidden='true'></i> Smazat</a></li>" +
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
                    sortColumn: 0,
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
                    'filterColumnIndex': 1
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
            });


            google.visualization.events.addListener(control, 'statechange', function () {
                var rangeStart;
                var rangeEnd;
                var state = control.getState();
                var view = new google.visualization.DataView(dataOther);
                view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
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
                table.draw();
            });

            var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard1_div'));
            dashboard.bind(control, chart);
            dashboard.draw(data);

            var dashboard2 = new google.visualization.Dashboard(document.getElementById('dashboard2_div'));
            dashboard2.bind(stringFilter, table);

            dashboard2.draw(dataOther);
            table.draw();

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
                    view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
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
                    var view = new google.visualization.DataView(dataOther);
                    view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
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
                    table.draw();
                }
            });

            dashboard.draw(data);

//            // When the table is selected, update the orgchart.
//            google.visualization.events.addListener(table, 'select', function() {
//                chart.setSelection(table.getSelection());
//            });
//
            // When the orgchart is selected, update the table chart.
            google.visualization.events.addListener(chart, 'select', function() {
                var state = control.getState();
                var view = new google.visualization.DataView(data);
                view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
                var selection = chart.getChart().getSelection();
                if (selection.length) {
                    var date = view.getValue(selection[0].row, 0);
                    date = new Date(date);
                    startDate = date.setHours(0, 0, 0);
                    endDate = date.setHours(23, 59, 59);
                    view = new google.visualization.DataView(dataOther);
                    view.setRows(view.getFilteredRows([{column: 0, minValue: startDate, maxValue: endDate}]));
                    table.setDataTable(view);
                    table.draw();
                }else {
                    var rangeStart;
                    var rangeEnd;
                    state = control.getState();
                    view = new google.visualization.DataView(dataOther);
                    view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));

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
                    table.draw();
                }

            });
        }
    </script>
    @endif
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm



@endsection