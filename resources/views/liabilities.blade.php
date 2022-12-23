@extends('layouts.app')

@section('title', 'Liabilities')

@section('contents')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Risk Value</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;290</h1>
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
    <script>

    </script>
@endsection
