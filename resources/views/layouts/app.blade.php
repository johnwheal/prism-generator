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
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Key Data</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'netWorth') active @endif" href="{{ route('netWorth') }}">
                            <x-feathericon-user/>
                            Net Worth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'investments') active @endif" href="{{ route('investments') }}.html">
                            <x-feathericon-bar-chart/>
                            Investments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'crowdfunding') active @endif" href="{{ route('crowdfunding') }}.html">
                            <x-feathericon-users/>
                            Crowdfunding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'assets') active @endif" href="{{ route('assets') }}.html">
                            <x-feathericon-arrow-up/>
                            Assets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'liabilities') active @endif" href="{{ route('liabilities') }}.html">
                            <x-feathericon-arrow-down/>
                            Liabilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'interestRates') active @endif" href="{{ route('interestRates') }}.html">
                            <x-feathericon-bar-chart-2/>
                            Interest Rates
                        </a>
                    </li>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'charity') active @endif" href="{{ route('charity') }}.html">
                            <x-feathericon-smile/>
                            Charity
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'dayToDay') active @endif" href="{{ route('dayToDay') }}.html">
                            <x-feathericon-credit-card/>
                            Day To Day
                        </a>
                    </li>
                </ul>
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Other</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="https://strategy.wheal.co.uk">
                            <x-feathericon-book-open/>
                            Financial Strategy
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
