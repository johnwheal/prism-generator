@extends('layouts.app')

@section('title')
    @yield('title')
@endsection

@section('contents')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Overall Performance</h4>
                </div>
                <div class="card-body">
                    <div id="chart-overall"></div>
                </div>
            </div>

            @foreach($dataItems as $index => $dataItem)
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $dataItem->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-{{$index}}"></div>
                    </div>
                </div>
            @endforeach

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
            @if ($overallDataItem->hasPaidIn())
        {
            name: "Paid In Capital",
            data: {{ json_encode($overallDataItem->paidIn) }}
        },
            @endif
        {
            name: "Value",
            data: {{ json_encode($overallDataItem->values) }}
        }
            ],
            xaxis: {
            type: "datetime",
            categories: {{ json_encode($overallDataItem->dates) }},
        },
            yaxis: {
            forceNiceScale: true,
            decimalsInFloat: 0,
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

        @foreach($dataItems as $index => $dataItem)
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
                        @if ($dataItem->hasPaidIn())
                    {
                        name: "Paid In Capital",
                        data: {{ json_encode($dataItem->paidIn) }}
                    },
                        @endif
                    {
                        name: "Value",
                        data: {{ json_encode($dataItem->values) }}
                    }
                ],
                xaxis: {
                    type: "datetime",
                    categories: {{ json_encode($dataItem->dates) }},
                },
                yaxis: {
                    forceNiceScale: true,
                    decimalsInFloat: 0,
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
                    '#20c997',
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
        @endforeach
    </script>
@endsection
