<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Storage;

abstract class AbstractAsset extends DateClass
{
    /**
     * The file name of the data
     *
     * @var string
     */
    public string $fileName = '';

    /**
     * The name of the item
     *
     * @var string
     */
    public string $name;

    /**
     * The data values
     *
     * @var array
     */
    public array $values = [];

    /**
     * The paid in values
     *
     * @var array
     */
    public array $paidIn = [];

    /**
     * Flag whether the item has paid in data
     *
     * @var bool
     */
    protected bool $hasPaidIn = false;

    /**
     * Get all the data
     *
     * @param string $fileName
     * @return array
     */
    public static function getAllData(string $fileName): array
    {
        $jsonAssets = Storage::get($fileName);
        $jsonAssets = json_decode($jsonAssets);

        //This is the final list of assets that get returned
        $assets = [];

        foreach ($jsonAssets as $jsonAsset) {
            $class = get_called_class();
            $asset = new $class();
            $asset->name = $jsonAsset->name;

            foreach ($jsonAsset->data as $data) {
                $asset->values[] = $data->value;
                if ($asset->hasPaidIn) $asset->paidIn[] = $data->paid_in;
                $asset->dates[] = self::convertQuarterToDate($data->date);
            }

            $assets[] = $asset;
        }

        $assets= self::addInitialQuarter($assets);

        return $assets;
    }

    /**
     * Combine all the data
     *
     * @param array $assets
     * @return Asset
     */
    public static function combineData(array $assets, $name = '')
    {
        $class = get_called_class();
        $overallAssets = new $class();
        $overallAssets->name = $name;

        $oldestQuarter = self::getOldestQuarter($assets);
        $newestQuarter = self::getLastQuarter($assets);

        //Pad the dates to make them the same
        foreach ($assets as $asset) {
            $asset->padToDate($oldestQuarter, $newestQuarter);
        }

        //They will all have the same dates because they've been padded
        $overallAssets->dates = $assets[0]->dates;

        //Loop through all the quarters
        $numQuarters = count($assets[0]->dates);
        for ($index = 0; $index < $numQuarters; $index++) {
            $value = 0;
            $paidIn = 0;

            foreach ($assets as $asset) {
                //Ignore student loan
                if ($asset->name == 'Student Loan') continue;
                if (isset($asset->values[$index])) $value += $asset->values[$index];
                if($asset->hasPaidIn) $paidIn += $asset->paidIn[$index];
            }

            $overallAssets->values[] = $value;
            $overallAssets->paidIn[] = $paidIn;
        }

        return $overallAssets;
    }

    /**
     * Get the calculated paid in value
     *
     * @return array
     */
    public function getCalculatedPaidIn()
    {
        $paidIn = [];

        foreach ($this->paidIn as $index => $item) {
            if ($index > 0) {
                $paidIn[] = $paidIn[$index-1] + $item;
            } else {
                $paidIn[] = $item;
            }
        }

        return $paidIn;
    }

    /**
     * Gets whether the asset has paid in data
     *
     * @return bool
     */
    public function hasPaidIn()
    {
        return $this->hasPaidIn;
    }
}
