<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="John Wheal">
    <title>Prism</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite(['resources/js/app.js'])
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ route('netWorth') }}">Prism</a>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'netWorth') active @endif" href="{{ route('netWorth') }}">
                            <x-feathericon-user/>
                            Net Worth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'assets') active @endif" href="{{ route('assets') }}">
                            <x-feathericon-arrow-up/>
                            Assets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'liabilities') active @endif" href="{{ route('liabilities') }}">
                            <x-feathericon-arrow-down/>
                            Liabilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'interestRates') active @endif" href="{{ route('interestRates') }}">
                            <x-feathericon-bar-chart-2/>
                            Interest Rates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'investments') active @endif" href="{{ route('investments') }}">
                            <x-feathericon-bar-chart/>
                            Investments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'charity') active @endif" href="{{ route('charity') }}">
                            <x-feathericon-smile/>
                            Charity
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('title')</h1>
            </div>
            @yield('contents')
        </main>
    </div>
</div>
</body>
</html>
