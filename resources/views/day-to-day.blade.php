@extends('layouts.app')

@section('title', 'Day to Day')

@section('contents')
    <div class="row">
        <div class="col-lg-12">
            @foreach($dayToDay as $index=> $category)
                <div class="card">
                    <div class="card-header">
                        <h4>{{ ucwords(str_replace('_', ' ', $category->name)) }}</h4>
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
                            stacked: true
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
                                @if ($dayCategory == 'income')
                                type: "line",
                                color: "#000",
                                @else
                                type: "area",
                                @endif
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
                            @if ($index == 0)
                            max: {{ max(end($category->values)) * 1.25 }},
                            @endif
                            labels: {
                                formatter: (value) => { return "??" + value.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
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
