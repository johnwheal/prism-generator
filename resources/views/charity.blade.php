@extends('layouts.app')

@section('title', 'Charity')

@section('contents')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Total Donations</h4>
                </div>
                <div class="card-body">
                    <div id="chart-total"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Donations per Quarter</h4>
                </div>
                <div class="card-body">
                    <div id="chart-quarter"></div>
                </div>
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
                    name: "Value",
                    data: {{ json_encode($cumulativeDonations->values) }}
                }
            ],
            xaxis: {
                type: "datetime",
                categories: {{ json_encode($cumulativeDonations->dates) }},
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
                '#000'
            ]
        }
        var chart = new ApexCharts(
            document.querySelector("#chart-total"),
            options
        );
        chart.render();

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
                    name: "Value",
                    data: {{ json_encode($donationsPerQuarter->values) }}
                }
            ],
            xaxis: {
                type: "datetime",
                categories: {{ json_encode($donationsPerQuarter->dates) }},
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
                '#000',
            ]
        }
        var chart = new ApexCharts(
            document.querySelector("#chart-quarter"),
            options
        );
        chart.render();
    </script>
@endsection
