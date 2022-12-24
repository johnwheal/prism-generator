<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InterestRate extends AbstractAsset
{
    /**
     * Flag that the asset has paid in data - used to store the value
     *
     * @var bool
     */
    public bool $hasPaidIn = true;

    /**
     * Get all the assets
     *
     * @return array
     */
    public static function getAllInterestRates(): array
    {
        $jsonLiabilities = Storage::get('data/liabilities.json');
        $jsonLiabilities = json_decode($jsonLiabilities);

        //This is the final list of interest rates that get returned
        $interestRates = [];

        foreach ($jsonLiabilities as $jsonLiability) {
            $interestRate = new InterestRate();
            $interestRate->name = $jsonLiability->name;

            foreach ($jsonLiability->data as $data) {
                $interestRate->values[] = $data->interest_rate;
                $interestRate->paidIn[] = $data->value;
                $interestRate->dates[] = self::convertQuarterToDate($data->date);
            }

            $interestRates[] = $interestRate;
        }

        //Pad the dates
        $oldestQuarter = self::getOldestQuarter($interestRates);
        $newestQuarter = self::getLastQuarter($interestRates);

        //Pad the dates to make them the same
        foreach ($interestRates as $interestRate) {
            $interestRate->padToDate($oldestQuarter, $newestQuarter, true);
        }

        return $interestRates;
    }

    /**
     * Get the overall interest rate
     *
     * @param $interestRates
     * @return InterestRate
     */
    public static function getOverallInterestRate($interestRates)
    {
        $overallInterestRate = new self();
        $overallInterestRate->name = 'Overall Interest Rate';
        $overallInterestRate->dates = $interestRates[0]->dates;

        //This is safe because the dates have been padded
        for ($index = 0; $index < count($interestRates[0]->dates); $index++) {
            $totalLiability = 0;
            $interestRateValue = 0;

            //Get the total liability at this date
            foreach ($interestRates as $interestRate) {
                $totalLiability += $interestRate->paidIn[$index];
            }

            foreach ($interestRates as $interestRate) {
                $relative = $interestRate->paidIn[$index] / $totalLiability;
                $interestRateValue += $interestRate->values[$index] * $relative;
            }

            $overallInterestRate->values[] = (float) number_format($interestRateValue, 2);
        }

        return $overallInterestRate;
    }

    /**
     * Get the highest interest rate
     *
     * @param array $interestRates
     * @return float
     */
    public static function getHighestInterestRate(array $interestRates): float
    {
        $lastQuarter = self::getLastQuarter($interestRates);
        $highestRate = 0;

        foreach ($interestRates as $interestRate) {
            $index = count($interestRate->dates) - 1;

            //If the interest rate is still valid
            if ($interestRate->dates[$index]->timestamp == $lastQuarter->timestamp) {
                if ($highestRate < $interestRate->values[$index]) {
                    $highestRate = $interestRate->values[$index];
                }
            }
        }

        return $highestRate;
    }

    /**
     * Get the lowest interest rate
     *
     * @param array $interestRates
     * @return float
     */
    public static function getLowestInterestRate(array $interestRates): float
    {
        $lastQuarter = self::getLastQuarter($interestRates);
        $lowestRate = 999999999;

        foreach ($interestRates as $interestRate) {
            $index = count($interestRate->dates) - 1;

            //If the interest rate is still valid
            if ($interestRate->dates[$index]->timestamp == $lastQuarter->timestamp) {
                //Ignore if the interest is null
                if ($interestRate->values[$index] !== null) {
                    if ($lowestRate > $interestRate->values[$index]) {
                        $lowestRate = $interestRate->values[$index];
                    }
                }
            }
        }

        return $lowestRate;
    }

    /**
     * Get the effective interest rate
     *
     * @return float
     */
    public function getEffectiveInterestRate(): float
    {
        return $this->values[count($this->values) - 1];
    }
}
