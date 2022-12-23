<?php

namespace App\Http\Controllers;

use App\Models\DataItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the net worth
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function netWorth()
    {
        $item1 = new DataItem('Test Item 1', [2000, 4000, 3000], [1427760000000,1435622400000,1443571200000]);
        $item2 = new DataItem('Test Item 2,', [4000, 1000, 4000], [1427760000000,1435622400000,1443571200000]);
        $dataItems = [$item1, $item2];
        $totalAssets = 40000;
        $totalLiabilities = -2000;
        $netWorth = $totalAssets + $totalLiabilities;
        return view('net-worth', [
            'dataItems' => $dataItems,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'netWorth' => $netWorth,
        ]);
    }

    /**
     * Get the assets
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function assets()
    {
        return view('assets');
    }

    /**
     * Get the liabilities
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function liabilities()
    {
        return view('liabilities');
    }

    /**
     * Get the interest rates
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function interestRates()
    {
        return view('interest-rates');
    }

    /**
     * Get the investments
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function investments()
    {
        return view('investments');
    }

    /**
     * Get the charity
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function charity()
    {
        return view('charity');
    }
}
