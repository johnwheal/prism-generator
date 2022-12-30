@extends('layouts.standard-performance')

@section('title', 'Investments')

@section('cards')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Low Risk Value</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($lowRiskValue, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Liquid Value</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($liquidValue, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Pie Chart</h4>
            </div>
            <div class="card-body">
                <div id="pie-chart"></div>
            </div>
        </div>
    </div>
    <script>

        var options = {
            series: {!! json_encode($allocation->data) !!} ,
            chart: {
                height: 300,
                type: 'pie',
            },
            labels: {!!  json_encode($allocation->items) !!},
            tooltip: {
                y: {
                    formatter: (value) => { return "£" + value.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors: [
                '#fd7e14',
                '#3B7DDD',
                '#669ae5',
                '#20c997',
                '#6f42c1',
                '#dc3545'
            ]
        };

        var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
        chart.render();
    </script>
@endsection
