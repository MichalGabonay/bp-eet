@extends('admin.templates.master')


@section('content')

    @if($selected_company == null)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading text-center">
                    <h2>Nemáte pridelenú žiadnu spoločnosť!</h2>
                    <h4>Kontaktuje správcu spoločnosti ku ktorej by ste mali byť priradený, alebo pridajte do systému novú spoločnosť.</h4>
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
                <div class="panel-heading">
                    <h3><u>Vybraná firma</u></h3>
                    <h4>{{$selected_company->name}}</h4>
                    {{--TODO: len ak admin alebo manazer--}}
                    <div class="cert_stats">
                        <b>Platnosť certifikátu:</b> áno/nie <br>
                        <b>Vyprší dňa:</b> 01.01.2020
                    </div>

                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h3>Vám priradené firmy</h3>
                </div>
                <div class="panel-body">
                    @foreach($usersCompany as $c)
                        <b>{{$c->name}}</b>
                        @if($c->company_id == $selected_company->id)
                            vybraná
                        @else
                            <a href="{{route('admin.select_company', $c->id)}}">vybrať</a>
                        @endif
                        <br>
                    @endforeach
                    {{--TODO: zoznam firiem, logo-nazov-switch-btn--}}
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-flat">
        <table class="table datatable-sorting table-hover table-hover-hand">
            <thead>
            <tr>
                <th width="140">nieco 1</th>
                <th>nieco 2</th>
                <th>noeco 3</th>
                {{--<th>Uvedeno do provozu / rok výroby</th>--}}
                <th>nieco 4</th>
                <th width="120" class="text-center">Akce</th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($products as $p)--}}
                <tr >
                    <td width="140">dsagsg</td>
                    <td>asdg</td>
                    <td>sadg</td>
                    {{--<th>Uvedeno do provozu / rok výroby</th>--}}
                    <td>sadgsdag</td>
                    <td width="120" class="text-center">E/D</td>
                </tr>
            <tr >
                <td width="140">dsagsg</td>
                <td>asdg</td>
                <td>sadg</td>
                {{--<th>Uvedeno do provozu / rok výroby</th>--}}
                <td>sadgsdag</td>
                <td width="120" class="text-center">E/D</td>
            </tr>
            {{--@endforeach--}}
            </tbody>
        </table>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4>Nadpis</h4>
        </div>
        <div class="panel-body">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec aliquam finibus cursus. Vestibulum hendrerit tincidunt felis nec mollis. Morbi tincidunt eget turpis vitae fermentum. Praesent quam massa, venenatis a eleifend tincidunt, maximus sit amet neque. Aenean vestibulum tincidunt magna, eget maximus tortor facilisis bibendum. Aenean sagittis laoreet gravida. Fusce at dignissim nibh. Nunc non nibh tellus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas rutrum quam sed ullamcorper laoreet. Pellentesque luctus in est nec egestas. Aenean at magna orci. In hac habitasse platea dictumst. Curabitur lobortis ut lorem at aliquam. Aenean at augue sit amet dolor convallis condimentum id at mi. Integer vulputate quam vel purus consequat posuere.
        </div>
    </div>
    @endif

    {{--<a href="{{ route('admin.products.create') }}" class="btn bg-teal-400 btn-labeled"><b><i class="icon-pencil7"></i></b> Vložit zařízení</a>--}}


@endsection





@section('head_js')
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/extensions/date-de.js") ) !!}
    {!! HTML::script( asset("/assets/admin/js/tables/datatables/datatables.min.js") ) !!}

@endsection


@section('jquery_ready')
    //<script> onlyForSyntaxPHPstorm

        $('.datatable-sorting').DataTable({
            order: [0, "asc"]
        });


@endsection