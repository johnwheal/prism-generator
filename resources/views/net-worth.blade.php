@extends('layouts.app')

@section('title', 'Net Worth')

@section('contents')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Net Worth</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($totalNetWorth, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Total Assets</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($totalAssets, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Total Liabilities</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($totalLiabilities, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Net Worth</h4>
                </div>
                <div class="card-body">
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>
    @if (count($netWorth) > 0)
        <script>
            // Area chart
            var options = {
                chart: {
                    height: 700,
                    type: "area",
                    stacked: true,
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: "straight"
                },
                series: [
                    @foreach($netWorth as $dataItem)
                    {
                        name: "{{ $dataItem->name }}",
                        data: {{ json_encode($dataItem->values) }}
                    },
                    @endforeach
                ],
                xaxis: {
                    type: "datetime",
                    categories: {{ json_encode($netWorth[0]->getPreciseTimestamps()) }},
                },
                yaxis: {
                    min: 0,
                    max: {{ ceil($totalNetWorth / 100000) * 100000 }},
                    forceNiceScale: true,
                    decimalsInFloat: 0,
                    tickAmount: 25,
                    labels: {
                        formatter: (value) => { return "£" + value.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                    }
                },
                tooltip: {
                    enabled: true,
                    x: {
                        format: 'MMM yyyy'
                    }
                },
                colors: [
                    '#3B7DDD',
                    '#fd7e14',
                    '#669ae5',
                    '#20c997',
                    '#6f42c1',
                    '#dc3545'
                ]
            }
            var chart = new ApexCharts(
                document.querySelector("#chart"),
                options
            );
            chart.render();
        </script>
    @endif
@endsection
