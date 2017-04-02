@extends('admin.templates.master')


@section('content')
    <div class="row">
        <div id="dashboard_div" class="col-md-10">
            <div id="daily_container_count_lineChart" style="width: 100%; height: 300px"></div>
            <div id="control_div" style="width: 100%; height: 50px"></div>
        </div>

    </div>
    <br>
    <div id="table_div" style="width: 100%"></div>

@endsection





@section('head_js')

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
                [{label: 'Datum', id: 'date', type: 'date'},{label: 'Sales', id: 'Sales', type: 'number'}],
                    @foreach($sales as $s)
                [new Date('{{$s->date}}'), {{$s->total_price}}],
                @endforeach
            ]);

            var dataOther = google.visualization.arrayToDataTable([
                [{label: 'Dátum pridania', type: 'date'},{label: 'Tržba', type: 'string'},{label: 'Užívatel'},{label: 'Poznámka', type: 'string'},{label: 'Celková cena', type: 'number'},{label: 'Akcie', type: 'string'}],
                @foreach($sales_all as $s)
                    [new Date('{{$s->receipt_time}}'),
                    {{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}},
                    @if(session('isAdmin'))
                    "<a href='{{route('admin.users.detail',$s->user_id )}}'>{{$s->user_name or '-'}}</a>",
                    @else
                        "{{$s->user_name or '-'}}",
                    @endif
                    @if($s->note != '')
                    {{$s->note or ''}},
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
                    @if($s->note_id == NULL)
                        "<li><a href='{{ route('admin.notes.create', $s->id) }}'><i class='fa fa-sticky-note-o' aria-hidden='true'></i> Pridať poznámku</a></li>" +
                    @else
                        "<li><a href='{{ route('admin.notes.edit', $s->id) }}'><i class='fa fa-sticky-note-o' aria-hidden='true'></i> Upraviť poznámku</a></li>" +
                    @endif
                            @if(session('isAdmin'))
                        "<li><a class='sweet_delete' href='{{ route('admin.sales.delete', $s->id) }}'><i class='fa fa-trash-o' aria-hidden='true'></i> Smazat</a></li>" +
                    @endif
                        "</ul></li></ul>"],
                @endforeach
            ]);

            var chart = new google.visualization.ChartWrapper({
                chartType: 'ColumnChart',
                containerId: 'daily_container_count_lineChart',
                options: {
                    title: 'Predaje',
                    hAxis: {
                        format: 'dd.MM.yyyy'
                    }
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
                    page: 'enable',
                    allowHtml: true,
                    width: '100%'
                }
            });


            google.visualization.events.addListener(control, 'statechange', function () {
                var state = control.getState();
                var view = new google.visualization.DataView(dataOther);
                view.setRows(view.getFilteredRows([{column: 0, minValue: state.range.start, maxValue: state.range.end}]));
                table.setDataTable(view);
                table.draw();
            });

            var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));
            dashboard.bind(control, chart);
            dashboard.draw(data);
            table.draw();
        }
    </script>
@endsection

@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm



@endsection