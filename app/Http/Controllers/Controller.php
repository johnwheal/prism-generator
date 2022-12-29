<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Company;
use App\Models\CrowdfundingInvestment;
use App\Models\DayToDay;
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

        $totalAssets = NetWorth::getTotalAssets($netWorth);
        $totalLiabilities = NetWorth::getTotalLiabilities($netWorth);
        $totalNetWorth = $totalAssets + $totalLiabilities;

        return view('net-worth', [
            'netWorth' => $netWorth,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalNetWorth' => $totalNetWorth,
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
        $overallPerformance = Asset::combineData($assets);

        return view('assets', [
            'overallDataItem' => $overallPerformance,
            'dataItems' => $assets,
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

        $overallPerformance = Liability::combineData($liabilities);

        return view('liabilities', [
            'overallDataItem' => $overallPerformance,
            'dataItems' => $liabilities,
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

        $overallLiabilityInterestRates = InterestRate::getOverallInterestRate($liabilityInterestRates);

        $effectiveLiabilityInterestRate = $overallLiabilityInterestRates->getEffectiveInterestRate();
        $highestInterestRate = InterestRate::getHighestInterestRate($liabilityInterestRates);
        $lowestInterestRate = InterestRate::getLowestInterestRate($liabilityInterestRates);

        //Get the investment data
        $investmentInterestRates = InterestRate::getAllInvestmentInterestRates();
        $overallInvestmentInterestRates = InterestRate::getOverallInterestRate($investmentInterestRates);

        $effectiveInvestmentInterestRate = $overallInvestmentInterestRates->getEffectiveInterestRate();

        $boeInterestRates = InterestRate::getBankOfEndEnglandInterestRates();

        return view('interest-rates', [
            'effectiveLiabilityInterestRate' => $effectiveLiabilityInterestRate,
            'effectiveInvestmentInterestRate' => $effectiveInvestmentInterestRate,
            'overallLiabilityDataItem' => $overallLiabilityInterestRates,
            'overallInvestmentDataItem' => $overallInvestmentInterestRates,
            'boeDataItem' => $boeInterestRates,
            'liabilityDataItems' => $liabilityInterestRates,
            'investmentDataItems' => $investmentInterestRates,
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

        $overallPerformance = Asset::combineData($investments);

        $lowRiskValue = Investment::getLowRiskValue($investments);
        $liquidValue = Investment::getLiquidValue($investments);

        $allocation = Investment::getAllocations($investments);

        return view('investments', [
            'overallDataItem' => $overallPerformance,
            'dataItems' => $investments,
            'lowRiskValue' => $lowRiskValue,
            'liquidValue' => $liquidValue,
            'allocation' => $allocation,
        ]);
    }

    /**
     * Get the charity
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function charity()
    {
        $donationsPerQuarter = Donation::getAllDonations();

        $cumulativeDonations = clone $donationsPerQuarter;
        $cumulativeDonations->convertToCumulative();


        return view('charity', [
            'cumulativeDonations' => $cumulativeDonations,
            'donationsPerQuarter' => $donationsPerQuarter,
        ]);
    }

    /**
     * Get the day to day
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dayToDay()
    {
        $categories = DayToDay::getAllCategoryTypes();
        $dayToDay = [];

        foreach ($categories as $category) {
            $dayToDay[] = new DayToDay($category);
        }

        //Put the income at the end
        $overall = DayToDay::getOverallDayToDay($dayToDay);
        array_unshift($dayToDay, $overall);

        return view('day-to-day', [
            'dayToDay' => $dayToDay,
        ]);
    }

    /**
     * Get the crowdfunding
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function crowdfunding()
    {
        $companies = Company::getCompanies();
        $platformData = Company::getPlatformData($companies);
        $statusData = Company::getCompanyStatusData($companies);
        $overallData = Company::getOverall($companies);

        return view('crowdfunding', [
            'platforms' => $platformData,
            'status' => $statusData,
            'companies' => $companies,
            'overallDataItem' => $overallData,
        ]);
    }
}
