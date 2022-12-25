@extends('layouts.app')

@section('title', 'Day to Day')

@section('contents')
    <div class="row">
        <div class="col-lg-12">
            @foreach($dayToDay as $index=> $category)
                <div class="card">
                    <div class="card-header">
                        <h4>{{ ucfirst($category->name) }}</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-{{ $index }}"></div>
                    </div>
                </div>
                <script>
                    // Area chart
                    var options = {
                        chart: {
                            height: 500,
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
                            @foreach(array_keys($category->values) as $dayCategory)
                            {
                                name: "{{ ucwords(str_replace('_', ' ', $dayCategory)) }}",
                                data: {{ json_encode($category->values[$dayCategory]) }}
                            },
                            @endforeach
                        ],
                        xaxis: {
                            type: "datetime",
                            categories: {{ json_encode($category->dates) }},
                        },
                        yaxis: {
                            min: 0,
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
                        document.querySelector("#chart-{{ $index }}"),
                        options
                    );
                    chart.render();
                </script>
            @endforeach
        </div>
    </div>
@endsection
