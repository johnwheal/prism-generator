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

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Overall Performance</h4>
                </div>
                <div class="card-body">
                    <div id="chart-overall"></div>
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
                            name: "Paid In Capital",
                            data: {{ json_encode($overallDataItem->paidIn) }}
                        },
                        {
                            name: "Value",
                            data: {{ json_encode($overallDataItem->values) }}
                        }
                    ],
                    xaxis: {
                        type: "datetime",
                        categories: {{ json_encode($overallDataItem->getPreciseTimestamps()) }},
                    },
                    yaxis: {
                        forceNiceScale: true,
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
                        '#fd7e14',
                        '#3B7DDD',
                        '#669ae5',
                        '#20c997',
                        '#6f42c1',
                        '#dc3545'
                    ]
                }
                var chart = new ApexCharts(
                    document.querySelector("#chart-overall"),
                    options
                );
                chart.render();
            </script>

        </div>

        @foreach($companies as $index => $company)
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $company->name }} - {{ $company->status }}</h4>
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
                            type: 'line',
                            data: {{ json_encode($company->sharePrice->values) }}
                        },
                        {
                            name: "Paid In",
                            type: 'area',
                            data: {{ json_encode($company->investments->paidIn) }}
                        },
                        {
                            name: "Value",
                            type: 'area',
                            data: {{ json_encode($company->investments->values) }}
                        }
                    ],
                    xaxis: {
                        type: "datetime",
                        categories: {{ json_encode($company->sharePrice->getPreciseTimestamps()) }},
                    },
                    yaxis: [
                        {
                            seriesName: "Share Price",
                            title: {
                                text: "Share Price"
                            },
                            forceNiceScale: true,
                            min: 0,
                            opposite: true,
                            labels: {
                                formatter: (value) => { return "£" + value.toFixed(4); },
                            }
                        },
                        {
                            seriesName: "Paid In",
                            forceNiceScale: true,
                            min: 0,
                            max: {{ max($company->investments->values) }},
                            labels: {
                                show: false,
                                formatter: (value) => { return "£" + value.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                            }
                        },
                        {
                            seriesName: "Value",
                            title: {
                                text: "Value"
                            },
                            forceNiceScale: true,
                            max: {{ max($company->investments->values) }},
                            min: 0,
                            labels: {
                                formatter: (value) => { return "£" + value.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                            }
                        }
                    ],
                    horizontalAlign: "left",
                    offsetX: 40,
                    tooltip: {
                        enabled: true,
                        x: {
                            format: 'MMM yyyy'
                        }
                    },
                    colors: [
                        '#000',
                        '#fd7e14',
                        '#3B7DDD',
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
