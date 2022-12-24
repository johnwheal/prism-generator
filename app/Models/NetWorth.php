<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NetWorth extends AbstractAsset
{
    /**
     * Get the overall net worth
     *
     * @return array
     */
    public static function getNetWorth(): array
    {
        $oldestQuarter = Carbon::now();

        $assets = Asset::getAllAssets();
        $assetOldestQuarter = Asset::getOldestQuarter($assets);
        if ($assetOldestQuarter->isBefore($oldestQuarter)) $oldestQuarter = $assetOldestQuarter;
        $newestQuarter = Asset::getLastQuarter($assets);
        $assets = Asset::combineData($assets, 'Assets');

        $liabilities = Liability::getAllLiabilities();
        $liabilitiesOldestQuarter = Liability::getOldestQuarter($liabilities);
        if ($liabilitiesOldestQuarter->isBefore($oldestQuarter)) $oldestQuarter = $liabilitiesOldestQuarter;
        $liabilities = Liability::combineData($liabilities, 'Liabilities');

        $investments = Investment::getAllInvestments();
        $investmentOldestQuarter = Investment::getOldestQuarter($investments);
        if ($investmentOldestQuarter->isBefore($oldestQuarter)) $oldestQuarter = $investmentOldestQuarter;

        $allAssets = $investments;
        array_unshift($allAssets, $assets);
        array_unshift($allAssets, $liabilities);

        foreach ($allAssets as $asset) {
            $asset->padToDate($oldestQuarter, $newestQuarter);
        }

        return $allAssets;
    }

    /**
     * Returns the total value of all assets
     *
     * @param $netWorth
     * @return int
     */
    public static function getTotalAssets($netWorth): int {
        $totalAssets = 0;

        foreach ($netWorth as $assets) {
            if ($assets->name != 'Liabilities') {
                $lastQuarter = Asset::getLastQuarter([$assets]);

                foreach ($assets->dates as $index => $date) {
                    if ($date->timestamp == $lastQuarter->timestamp) {
                        $totalAssets += $assets->values[$index];
                    }
                }
            }
        }

        return $totalAssets;
    }

    /**
     * Returns the total value of all liabilities
     *
     * @param $netWorth
     * @return int
     */
    public static function getTotalLiabilities($netWorth): int {
        foreach ($netWorth as $assets) {
            if ($assets->name == 'Liabilities') {
                $lastQuarter = Asset::getLastQuarter([$assets]);

                foreach ($assets->dates as $index => $date) {
                    if ($date->timestamp == $lastQuarter->timestamp) {
                        return $assets->values[$index];
                    }
                }
            }
        }
    }
}
