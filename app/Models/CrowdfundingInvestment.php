<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrowdfundingInvestment extends Investment
{
    /**
     * The number of shares held per quarter
     *
     * @var array
     */
    public array $numShares = [];

    /**
     * Get investments
     *
     * @param array $investmentsJson
     * @param $sharePrices
     * @return CrowdfundingInvestment
     */
    public static function getInvestments(array $investmentsJson, $sharePrices, array|null $withdrawalsJson): CrowdfundingInvestment
    {
        if ($withdrawalsJson == null) $withdrawalsJson = [];

        $investment = new self();
        $investment->dates = $sharePrices->dates;
        $investment->name = ''; //The name is not important

        //Loop through all the quarters and calculate the number of shares held
        foreach ($investment->dates as $index => $date) {
            if ($index == 0) {
                $numSharesHeld = 0;
                $paidIn = 0;
            } else {
                $numSharesHeld = $investment->numShares[count($investment->numShares)-1];
                $paidIn = $investment->paidIn[count($investment->paidIn)-1];
            }

            foreach ($investmentsJson as $investmentJson) {
                $investmentDate = self::convertQuarterToDate($investmentJson->date);

                if ($date->timestamp == $investmentDate->timestamp) {
                    $numSharesHeld += $investmentJson->num_shares;
                    $paidIn += $investmentJson->num_shares * $sharePrices->values[$index];
                }
            }

            foreach ($withdrawalsJson as $withdrawalJson) {
                $withdrawalDate = self::convertQuarterToDate($withdrawalJson->date);

                if ($date->timestamp == $withdrawalDate->timestamp) {
                    $numSharesHeld -= $withdrawalJson->num_shares;
                    $paidIn -= $withdrawalJson->amount;
                }
            }

            $investment->numShares[] = $numSharesHeld;
            $investment->paidIn[] = $paidIn;

            //Calculate the value of a share on a given quarter
            $investment->values[] = $sharePrices->values[$index] * $numSharesHeld;
        }

        return $investment;
    }

    /**
     * Get the total portfolio size
     *
     * @return mixed
     */
    public function getTotalPortfolioSize()
    {
        return $this->values[count($this->values)-1];
    }

    /**
     * Get the total paid in
     *
     * @return mixed
     */
    public function getTotalPaidIn()
    {
        return $this->paidIn[count($this->paidIn)-1];
    }

    /**
     * Get the exit money
     *
     * @return void
     */
    public function getExitMoney()
    {
        $companies = Company::getCompanies();
        $exitMoney = 0;

        foreach ($companies as $company) {
            foreach ($company->withdrawals as $withdrawal) {
                $exitMoney += $withdrawal;
            }
        }

        return $exitMoney;
    }

}
