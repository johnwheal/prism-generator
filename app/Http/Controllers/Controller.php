<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\DataItem;
use App\Models\Investment;
use App\Models\Liability;
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
        $item1 = new DataItem('Test Item 1', [1427760000000,1435622400000,1443571200000], [2000, 4000, 3000]);
        $item2 = new DataItem('Test Item 2', [1427760000000,1435622400000,1443571200000], [4000, 1000, 4000]);
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
        $assets = Asset::getAllAssets();
        $dataItems = [];

        foreach ($assets as $asset) {
            $dataItems[] = new DataItem(
                $asset->name,
                $asset->getPreciseTimestamps(),
                $asset->values,
                $asset->getCalculatedPaidIn()
            );
        }

        $overallPerformance = Asset::combineData($assets);
        $overallDataItem = new DataItem(
            'Overall Performance',
            $overallPerformance->getPreciseTimestamps(),
            $overallPerformance->values,
            $overallPerformance->getCalculatedPaidIn()
        );

        return view('assets', [
            'overallDataItem' => $overallDataItem,
            'dataItems' => $dataItems,
        ]);
    }

    /**
     * Get the liabilities
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function liabilities()
    {
        $liabilities = Liability::getAllLiabilities();
        $dataItems = [];

        foreach ($liabilities as $liability) {
            $dataItems[] = new DataItem(
                $liability->name,
                $liability->getPreciseTimestamps(),
                $liability->values
            );
        }

        $overallPerformance = Liability::combineData($liabilities);
        $overallDataItem = new DataItem(
            'Overall Performance',
            $overallPerformance->getPreciseTimestamps(),
            $overallPerformance->values
        );

        return view('liabilities', [
            'overallDataItem' => $overallDataItem,
            'dataItems' => $dataItems,
        ]);
    }

    /**
     * Get the interest rates
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function interestRates()
    {
        $item1 = new DataItem('Test Item 1', [1427760000000,1435622400000,1443571200000], [2, 2, 3]);
        $item2 = new DataItem('Test Item 2', [1427760000000,1435622400000,1443571200000], [1, 2.3, 2.2]);
        $dataItems = [$item1, $item2];

        $overallDataItem = $item1;

        $effectiveInterestRate = 1.89;
        $highestInterestRate = 5.4;
        $lowestInterestRate = 1.9;

        return view('interest-rates', [
            'effectiveInterestRate' => $effectiveInterestRate,
            'overallDataItem' => $overallDataItem,
            'dataItems' => $dataItems,
            'highestInterestRate' => $highestInterestRate,
            'lowestInterestRate' => $lowestInterestRate,
        ]);
    }

    /**
     * Get the investments
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function investments()
    {
        $investments = Investment::getAllInvestments();
        $dataItems = [];

        foreach ($investments as $investment) {
            $dataItems[] = new DataItem(
                $investment->name,
                $investment->getPreciseTimestamps(),
                $investment->values,
                $investment->getCalculatedPaidIn()
            );
        }

        $overallPerformance = Asset::combineData($investments);
        $overallDataItem = new DataItem(
            'Overall Performance',
            $overallPerformance->getPreciseTimestamps(),
            $overallPerformance->values,
            $overallPerformance->getCalculatedPaidIn()
        );

        return view('investments', [
            'overallDataItem' => $overallDataItem,
            'dataItems' => $dataItems,
        ]);
    }

    /**
     * Get the charity
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function charity()
    {
        $cumulativeDonations = new DataItem('Test Item 1', [1427760000000,1435622400000,1443571200000], [2000, 4000, 3000]);
        $donationsPerQuarter = new DataItem('Test Item 2', [1427760000000,1435622400000,1443571200000], [4000, 1000, 4000]);

        return view('charity', [
            'cumulativeDonations' => $cumulativeDonations,
            'donationsPerQuarter' => $donationsPerQuarter,
        ]);
    }
}
