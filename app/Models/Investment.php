<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Investment extends AbstractAsset
{
    /**
     * The low risk value
     *
     * @var int
     */
    public int $lowRiskValue = 0;

    /**
     * The liquid value
     *
     * @var int
     */
    public int $liquidValue = 0;

    /**
     * Flag that the asset has paid in data
     *
     * @var bool
     */
    public bool $hasPaidIn = true;

    /**
     * Get all the investments
     *
     * @return array
     */
    public static function getAllInvestments(): array
    {
        $fileLocation = 'data/investments.json';
        $investments = self::getAllData($fileLocation);

        //Calculate the liquid and low risk value
        $jsonAssets = Storage::get($fileLocation);
        $jsonAssets = json_decode($jsonAssets);

        $investments = self::getInvestmentSpecificValues($jsonAssets, $investments);

        return $investments;
    }

    /**
     * Get investment specific values (risk/liquidity)
     *
     * @param $jsonInvestments
     * @param $investments
     * @return array
     */
    private static function getInvestmentSpecificValues($jsonInvestments, $investments): array
    {
        foreach ($investments as $index => $investment) {
            $investmentValue = $investment->values[count($investment->values) - 1];
            $investment->lowRiskValue = (1 - ($jsonInvestments[$index]->risk)/100) * $investmentValue;

            if ($jsonInvestments[$index]->liquid) {
                $investment->liquidValue = $investmentValue;
            }
        }

        return $investments;
    }

    /**
     * Get the low risk value of an array of investments
     *
     * @param array $investments
     * @return int
     */
    public static function getLowRiskValue(array $investments): int
    {
        $lowRiskValue = 0;

        foreach ($investments as $investment) {
            $lowRiskValue += $investment->lowRiskValue;
        }

        return $lowRiskValue;
    }

    /**
     * Get the liquid value of an array of investments
     *
     * @param array $investments
     * @return int
     */
    public static function getLiquidValue(array $investments): int
    {
        $liquidValue = 0;

        foreach ($investments as $investment) {
            $liquidValue += $investment->liquidValue;
        }

        return $liquidValue;
    }
}
