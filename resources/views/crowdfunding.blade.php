@extends('layouts.app')

@section('title', 'Crowdfunding')

@section('contents')

    <div class="row">

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Platforms</h4>
                </div>
                <div class="card-body">
                    <div id="platforms-pie-chart"></div>
                </div>
            </div>
        </div>
        <script>

            var options = {
                series: {!! json_encode(array_values($platforms)) !!} ,
                chart: {
                    height: 300,
                    type: 'pie',
                },
                labels: {!!  json_encode(array_keys($platforms)) !!},
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

            var chart = new ApexCharts(document.querySelector("#platforms-pie-chart"), options);
            chart.render();
        </script>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Company Status</h4>
                </div>
                <div class="card-body">
                    <div id="status-pie-chart"></div>
                </div>
            </div>
        </div>
        <script>

            var options = {
                series: {!! json_encode(array_values($status)) !!} ,
                chart: {
                    height: 300,
                    type: 'pie',
                },
                labels: {!! json_encode(array_keys($status)) !!},
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

            var chart = new ApexCharts(document.querySelector("#status-pie-chart"), options);
            chart.render();
        </script>

        @foreach($companies as $index => $company)
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $company->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-{{$index}}"></div>
                    </div>
                </div>
            </div>

            <script>
                // Area chart
                var options = {
                    chart: {
                        height: 500,
                        type: "area",
                        stacked: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: "straight"
                    },
                    series: [
                        {
                            name: "Share Price",
                            data: {{ json_encode($company->sharePrice->values) }}
                        }
                    ],
                    xaxis: {
                        type: "datetime",
                        categories: {{ json_encode($company->sharePrice->getPreciseTimestamps()) }},
                    },
                    yaxis: {
                        forceNiceScale: true,
                        min: 0,
                        decimalsInFloat: 0,
                        @yield('max-y')
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
                        '#6f42c1',
                        '#669ae5',
                        '#dc3545'
                    ]
                }
                var chart = new ApexCharts(
                    document.querySelector("#chart-{{ $index }}"),
                    options
                );
                chart.render();
            </script>
        @endforeach

    </div>

@endsection
