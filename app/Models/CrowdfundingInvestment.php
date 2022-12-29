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
     * The constructor
     *
     * @param array $investmentsJson
     * @param $sharePrices
     */
    public function __construct(array $investmentsJson, $sharePrices)
    {
        $this->dates = $sharePrices->dates;

        //Loop through all the quarters and calculate the number of shares held
        foreach ($this->dates as $index => $date) {
            if ($index == 0) {
                $numSharesHeld = 0;
                $paidIn = 0;
            } else {
                $numSharesHeld = $this->numShares[count($this->numShares)-1];
                $paidIn = $this->paidIn[count($this->paidIn)-1];
            }

            foreach ($investmentsJson as $investmentJson) {
                $investmentDate = self::convertQuarterToDate($investmentJson->date);

                if ($date->timestamp == $investmentDate->timestamp) {
                    $numSharesHeld += $investmentJson->num_shares;
                    $paidIn += $numSharesHeld * $sharePrices->values[$index];
                }
            }

            $this->numShares[] = $numSharesHeld;
            $this->paidIn[] = $paidIn;

            //Calculate the value of a share on a given quarter
            $this->values[] = $sharePrices->values[$index] * $numSharesHeld;
        }

    }

}
