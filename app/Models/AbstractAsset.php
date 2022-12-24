<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Storage;

abstract class AbstractAsset
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
     * Dates
     *
     * @var array
     */
    public array $dates = [];

    /**
     * Flag whether the item has paid in data
     *
     * @var bool
     */
    public bool $hasPaidIn = false;

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
     * Add an inital quarter
     *
     * @param $assets
     * @return mixed
     */
    public static function addInitialQuarter($assets)
    {
        foreach ($assets as $asset) {
            if ($asset->dates[0]->year != Env::get('START_YEAR')) {
                $date = $asset->dates[0]->clone();
                $date->addDay()->subQuarter()->subDay();

                array_unshift($asset->dates, $date);
                array_unshift($asset->values, 0);
                if ($asset->hasPaidIn) {
                    array_unshift($asset->paidIn, 0);
                }
            }
        }

        return $assets;
    }

    /**
     * Converts a quarter to a Carbon date
     *
     * @param $quarter
     * @return Carbon|false
     */
    public static function convertQuarterToDate($quarter)
    {
        $quarterNum = substr(strtok($quarter,  ' '), 1, 1);
        $year = substr($quarter, strpos($quarter, " ") + 1, 4);

        $date = Carbon::create($year);
        $date->addQuarters($quarterNum)->subDay();

        return $date;
    }

    /**
     * Get the oldest quarter
     *
     * @param $assets
     * @return Carbon|mixed
     */
    public static function getOldestQuarter($assets)
    {
        $earliestDate = Carbon::now();

        foreach ($assets as $asset) {
            foreach ($asset->dates as $date) {
                if ($date->isBefore($earliestDate)) {
                    $earliestDate = $date;
                }
            }
        }

        return $earliestDate;
    }

    /**
     * Pad the data to a date (i.e. add earlier quarters)
     *
     * @param Carbon $dateToPad
     * @return void
     */
    public function padToDate(Carbon $dateToPad)
    {
        $lastDate = end($this->dates)->clone();
        $lastDate->addDay();

        $newDates = [];
        $newValues = [];
        $newPaidIn = [];

        //Loop through all the quarters
        while ($dateToPad->isBefore($lastDate)) {

            $dateIndex = -1;

            //Loop through all the data
            foreach ($this->dates as $index => $date) {
                if ($dateToPad->timestamp == $date->timestamp) {
                    $dateIndex = $index;
                    break;
                }
            }

            $newDates[] = $dateToPad->clone();

            //If data has been found for the date
            if ($dateIndex >= 0) {
                $newValues[] = $this->values[$dateIndex];
                if ($this->hasPaidIn) $newPaidIn[] = $this->paidIn[$dateIndex];
            } else {
                $newValues[] = 0;
                $newPaidIn[] = 0;
            }

            //Add a quarter
            $dateToPad->addDay()->addQuarter()->subDay();
        }

        $this->dates = $newDates;
        $this->values = $newValues;
        $this->paidIn = $newPaidIn;
    }

    /**
     * Combine all the data
     *
     * @param array $assets
     * @return Asset
     */
    public static function combineData(array $assets)
    {
        $class = get_called_class();
        $overallAssets = new $class();
        $overallAssets->name = 'Overall Performance';

        $oldestQuarter = self::getOldestQuarter($assets);

        //Pad the dates to make them the same
        foreach ($assets as $asset) {
            $asset->padToDate($oldestQuarter->clone());
        }

        //They will all have the same dates because they've been padded
        $overallAssets->dates = $assets[0]->dates;

        //Loop through all the quarters
        $numQuarters = count($assets[0]->dates);
        for ($index = 0; $index < $numQuarters; $index++) {
            $value = 0;
            $paidIn = 0;

            foreach ($assets as $asset) {
                if (isset($asset->values[$index])) $value += $asset->values[$index];
                if($asset->hasPaidIn) $paidIn += $asset->paidIn[$index];
            }

            $overallAssets->values[] = $value;
            $overallAssets->paidIn[] = $paidIn;
        }

        return $overallAssets;
    }

    /**
     * Returns the dates as a precise timestamp
     *
     * @return array
     */
    public function getPreciseTimestamps()
    {
        $dates = [];

        foreach ($this->dates as $date) {
            $dates[] = $date->getPreciseTimestamp(3);
        }

        return $dates;
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
}
