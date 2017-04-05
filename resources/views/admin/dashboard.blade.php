@extends('admin.templates.master')


@section('content')

    @if($selected_company == null)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading text-center">
                    <h2>Nemáte pridelenú žiadnu spoločnosť!</h2>
                    <h4>Kontaktuje správcu spoločnosti ku ktorej by ste mali byť priradený, alebo <a href="{{route('admin.companies.create')}}">pridajte do systému novú spoločnosť.</a></h4>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h3><u>Vybraná firma</u></h3>
                    <h4>{{$selected_company->name}}</h4>
                    @if(session('isAdmin'))
                        {{--TODO: len ak admin alebo manazer--}}
                        <div class="cert_stats">
                            @if($cert != null)
                                <b>Platnosť certifikátu:</b> {{($cert->valid == 1 ? 'certifikát je platný': 'neplatný')}} <br>
                                <b>Vyprší dňa:</b> {{date('d.m.Y', strtotime($cert->expiration_date))}}
                            @else
                                Prosím vložte validný certifikát k <a href="{{route('admin.companies.detail', $selected_company)}}">spoločnosti</a> .
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(count($usersCompany) != 1)
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <h3><u>Vám priradené firmy</u></h3>
                    <table class="table">
                        <tbody>
                        @foreach($usersCompany as $c)
                            <tr>
                                <td><b>{{$c->name}}</b></td>
                                <td>
                            @if($c->company_id == $selected_company->id)
                                vybraná
                            @else
                                <a href="{{route('admin.select_company', $c->company_id)}}">vybrať</a>
                            @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{--TODO: zoznam firiem, logo-nazov-switch-btn--}}
                </div>
            </div>
        </div>
        @endif
    </div>


    @if((session('isAdmin') || session('isManager')) && count($last_sales) > 0)
        <div class="panel panel-flat">
            <div class="row">
                <div class="col-md-5">
                    <div id="piechart" style="width: 100%; height: 350px;"></div>
                </div>
                <div class="col-md-7">
                    <div id="linechart" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <h4>Posledné pridané tržby</h4>
                        <table class="table datatable-sorting">
                            <thead>
                            <tr>
                                <th>Tržba</th>
                                {{--<th>Produkty</th>--}}
                                <th>Celková cena</th>
                                <th>Užívatel</th>
                                <th>Pridaná</th>
                                <th>Poznámka</th>

                                <th width="80" class="text-center">Akcie</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($last_sales as $s)
                                <tr >
                                    <td>{{$s->receiptNumber }} {{($s->storno == 1 ? ' - stornované' : '')}}</td>
                                    {{--<td>{{$s->products or '-'}}</td>--}}
                                    <td>{{$s->total_price or '-'}}Kč</td>
                                    @if(session('isAdmin'))
                                        <td><a href="{{route('admin.users.detail',$s->user_id )}}">{{$s->user_name or '-'}}</a></td>
                                    @else
                                        <td>{{$s->user_name or '-'}}</td>
                                    @endif

                                    @if(!is_null($s->receipt_time))
                                        <td>{{date('d.m.Y H:i', strtotime($s->receipt_time))}}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    <td>{{$s->note or ''}}</td>

                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-th-large" aria-hidden="true"></i>&nbsp;&nbsp; Detail</a></li>
                                                    @if($s->storno == 0)
                                                        <li><a href="{{ route('admin.sales.storno', $s->id) }}"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp; Storno</a></li>
                                                    @endif
                                                    <li><a href="{{ route('admin.sales.generate_receipt', $s->id) }}" target="_blank"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp; Generovať účtenku</a></li>
                                                    <li><a href="{{ route('admin.sales.detail', $s->id) }}"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;&nbsp; Pridať poznámku</a></li>
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

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <h4>Predaje zamestnancov</h4>
                        <table class="table datatable-sorting2">
                            <thead>
                            <tr>
                                <th>Meno</th>
                                <th>Predaj</th>

                                {{--<th>Užívatel</th>--}}
                                {{--<th>Pridaná</th>--}}
                                {{--<th>Poznámka</th>--}}

                                {{--<th width="80" class="text-center">Akcie</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users_sales as $us)
                                <tr >
                                    <td>{{$us->name or '-'}}</td>
                                    <td>{{$us->sales or ''}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif

    {{--<a href="{{ route('admin.products.create') }}" class="btn bg-teal-400 btn-labeled"><b><i class="icon-pencil7"></i></b> Vložit zařízení</a>--}}


@endsection





@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawChart2);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Deň', 'Tržba'],
                ['Pondelok',    {{$sales_by_day_week[1]}}],
                ['Utorok',      {{$sales_by_day_week[2]}}],
                ['Streda',      {{$sales_by_day_week[3]}}],
                ['Štvrtok',     {{$sales_by_day_week[4]}}],
                ['Piatok',      {{$sales_by_day_week[5]}}],
                ['Sobota',      {{$sales_by_day_week[6]}}],
                ['Nedela',      {{$sales_by_day_week[0]}}]
            ]);

            var options = {
                title: 'Tržby podľa dní v týždni'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        function drawChart2() {
            var data = google.visualization.arrayToDataTable([
                [{label: 'Deň', type: 'date'}, 'Tržba'],
                @foreach($all_sales as $s)
                    [new Date('{{$s->date}}'), {{$s->total_price}}],
                @endforeach
            ]);

            var options = {
                title: 'Celkový priebeh tržieb',
                legend: { position: "none" }
            };

            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }


    </script>

@endsection


@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $('.datatable-sorting').DataTable({
            order: [3, "desc"],
            bPaginate: false,
            "bInfo" : false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
            }
        });

        $('.datatable-sorting2').DataTable({
            order: [1, "desc"],
            bPaginate: false,
            "bInfo" : false,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Slovak.json"
            }
        });


@endsection