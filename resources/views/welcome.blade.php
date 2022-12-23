<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="John Wheal">
    <title>Prism</title>

    @vite(['resources/js/app.js'])
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Prism</a>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <x-feathericon-user/>
                            Net Worth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <x-feathericon-arrow-up/>
                            Assets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <x-feathericon-arrow-down/>
                            Liabilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <x-feathericon-bar-chart-2/>
                            Interest Rates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <x-feathericon-bar-chart/>
                            Investments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <x-feathericon-smile/>
                            Charity
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Net Worth</h1>
            </div>
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
        </main>
    </div>
</div>
</body>
</html>
