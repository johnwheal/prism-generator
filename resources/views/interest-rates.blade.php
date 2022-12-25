@extends('layouts.app')

@section('title', 'Interest Rates')

@section('contents')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Effective Savings Interest Rate</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ $effectiveInvestmentInterestRate }}&percnt;</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Effective Borrowing Interest Rate</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ $effectiveLiabilityInterestRate }}&percnt;</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Highest Borrowing Interest Rate</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ $highestInterestRate }}&percnt;</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Lowest Borrowing Interest Rate</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ $lowestInterestRate }}&percnt;</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Overall Interest Rate</h4>
                </div>
                <div class="card-body">
                    <div id="chart-overall"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Borrowing Interest Rates</h4>
                </div>
                <div class="card-body">
                    <div id="chart-liability"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Savings Interest Rates</h4>
                </div>
                <div class="card-body">
                    <div id="chart-investments"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Area chart
        var options = {
            chart: {
                height: 500,
                type: "line",
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
                    name: "Borrowing Interest Rate",
                    data: {{ json_encode($overallLiabilityDataItem->values) }}
                },
                {
                    name: "Investment Interest Rate",
                    data: {{ json_encode($overallInvestmentDataItem->values) }}
                },
                {
                    name: "Bank of England Interest Rate",
                    data: {{ json_encode($boeDataItem->values) }}
                }
            ],
            xaxis: {
                type: "datetime",
                categories: {{ json_encode($overallLiabilityDataItem->dates) }},
            },
            yaxis: {
                min: 0,
                max: {{ ceil($highestInterestRate) }},
                forceNiceScale: true,
                decimalsInFloat: 0,
                labels: {
                    formatter: (value) => {
                        if (value == null) return "N/A"
                        else return value + "%"
                    }
                }
            },
            tooltip: {
                enabled: true,
                x: {
                    format: 'MMM yyyy'
                }
            },
            colors: [
                "#F00",
                "#00F",
                "#000"
            ]
        }
        var chart = new ApexCharts(
            document.querySelector("#chart-overall"),
            options
        );
        chart.render();

        // Liability chart
        var options = {
            chart: {
                height: 500,
                type: "line",
                stacked: false,
                forceNiceScale: true
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: "straight"
            },
            series: [
                @foreach($liabilityDataItems as $dataItem)
                {
                    name:  "{{ $dataItem->name }}",
                    data: {!! json_encode($dataItem->values)  !!}
                },
                @endforeach
            ],
            xaxis: {
                type: "datetime",
                categories: {{ json_encode($dataItem->dates) }},
            },
            yaxis: {
                min: 0,
                max: {{ ceil($highestInterestRate) }},
                forceNiceScale: true,
                decimalsInFloat: 0,
                labels: {
                    formatter: (value) => {
                        if (value == null) return "N/A"
                        else return value + "%"
                    }
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
            document.querySelector("#chart-liability"),
            options
        );
        chart.render();

        // Investment Chart
        var options = {
            chart: {
                height: 500,
                type: "line",
                stacked: false,
                forceNiceScale: true
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: "straight"
            },
            series: [
                    @foreach($investmentDataItems as $dataItem)
                {
                    name:  "{{ $dataItem->name }}",
                    data: {!! json_encode($dataItem->values)  !!}
                },
                @endforeach
            ],
            xaxis: {
                type: "datetime",
                categories: {{ json_encode($dataItem->dates) }},
            },
            yaxis: {
                min: 0,
                max: {{ ceil($highestInterestRate) }},
                forceNiceScale: true,
                decimalsInFloat: 0,
                labels: {
                    formatter: (value) => {
                        if (value == null) return "N/A"
                        else return value + "%"
                    }
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
            document.querySelector("#chart-investments"),
            options
        );
        chart.render();
    </script>
@endsection
