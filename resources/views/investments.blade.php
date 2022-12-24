@extends('layouts.standard-performance')

@section('title', 'Investments')

@section('cards')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="card-title">Low Risk Value</h5>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">&pound;{{ number_format($lowRiskValue, 0, '.', ',' ) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
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
@endsection
