<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\BoeInterestRate;
use App\Models\DataItem;
use App\Models\Donation;
use App\Models\InterestRate;
use App\Models\Investment;
use App\Models\Liability;
use App\Models\NetWorth;
use Dflydev\DotAccessData\Data;
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
        $netWorth = NetWorth::getNetWorth();
        $dataItems = [];
        foreach ($netWorth as $asset) {
            $dataItems[] = new DataItem(
                $asset->name,
                $asset->getPreciseTimestamps(),
                $asset->values,
            );
        }

        $totalAssets = NetWorth::getTotalAssets($netWorth);
        $totalLiabilities = NetWorth::getTotalLiabilities($netWorth);
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
        $liabilityInterestRates = InterestRate::getAllLiabilitiesInterestRates();

        $liabilityDataItems = [];

        foreach ($liabilityInterestRates as $interestRate) {
            $liabilityDataItems[] = new DataItem(
                $interestRate->name,
                $interestRate->getPreciseTimestamps(),
                $interestRate->values
            );
        }

        $overallLiabilityInterestRates = InterestRate::getOverallInterestRate($liabilityInterestRates);
        $overallLiabilityDataItem = new DataItem(
            'Overall Liability Interest Rates',
            $overallLiabilityInterestRates->getPreciseTimestamps(),
            $overallLiabilityInterestRates->values
        );

        $effectiveLiabilityInterestRate = $overallLiabilityInterestRates->getEffectiveInterestRate();
        $highestInterestRate = InterestRate::getHighestInterestRate($liabilityInterestRates);
        $lowestInterestRate = InterestRate::getLowestInterestRate($liabilityInterestRates);

        //Get the investment data
        $investmentDataItems = [];
        $investmentInterestRates = InterestRate::getAllInvestmentInterestRates();

        foreach ($investmentInterestRates as $interestRate) {
            $investmentDataItems[] = new DataItem(
                $interestRate->name,
                $interestRate->getPreciseTimestamps(),
                $interestRate->values
            );
        }

        $overallInvestmentInterestRates = InterestRate::getOverallInterestRate($investmentInterestRates);
        $overallInvestmentDataItem = new DataItem(
            'Overall Investment Interest Rates',
            $overallInvestmentInterestRates->getPreciseTimestamps(),
            $overallInvestmentInterestRates->values
        );

        $effectiveInvestmentInterestRate = $overallInvestmentInterestRates->getEffectiveInterestRate();

        $boeInterestRates = InterestRate::getBankOfEndEnglandInterestRates();
        $boeDataItem = new DataItem(
            'Bank of England Interest Rate',
            $boeInterestRates->getPreciseTimestamps(),
            $boeInterestRates->values
        );

        return view('interest-rates', [
            'effectiveLiabilityInterestRate' => $effectiveLiabilityInterestRate,
            'effectiveInvestmentInterestRate' => $effectiveInvestmentInterestRate,
            'overallLiabilityDataItem' => $overallLiabilityDataItem,
            'overallInvestmentDataItem' => $overallInvestmentDataItem,
            'boeDataItem' => $boeDataItem,
            'liabilityDataItems' => $liabilityDataItems,
            'investmentDataItems' => $investmentDataItems,
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

        $lowRiskValue = Investment::getLowRiskValue($investments);
        $liquidValue = Investment::getLiquidValue($investments);

        return view('investments', [
            'overallDataItem' => $overallDataItem,
            'dataItems' => $dataItems,
            'lowRiskValue' => $lowRiskValue,
            'liquidValue' => $liquidValue,
        ]);
    }

    /**
     * Get the charity
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function charity()
    {
        $donations = Donation::getAllDonations();

        $donationsPerQuarter = new DataItem(
            'Donations',
            $donations->getPreciseTimestamps(),
            $donations->values
        );

        $donations->convertToCumulative();

        $cumulativeDonations = new DataItem(
            'Cumulative Donations',
            $donations->getPreciseTimestamps(),
            $donations->values
        );

        return view('charity', [
            'cumulativeDonations' => $cumulativeDonations,
            'donationsPerQuarter' => $donationsPerQuarter,
        ]);
    }
}
