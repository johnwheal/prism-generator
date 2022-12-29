<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Storage;

abstract class DateClass
{

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
    protected bool $hasPaidIn = false;

    /**
     * Add an initial quarter
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
     * Get the last (most recent) quarter
     *
     * @param $assets
     * @return Carbon|mixed
     */
    public static function getLastQuarter($assets)
    {
        $lastDate = Carbon::now()->subCenturies(1);

        foreach ($assets as $asset) {
            foreach ($asset->dates as $date) {
                if ($date->isAfter($lastDate)) {
                    $lastDate = $date;
                }
            }
        }

        return $lastDate;
    }

    /**
     * Pad the data to a date (i.e. add earlier quarters)
     *
     * @param Carbon $dateToPadFrom
     * @param Carbon $dateToPadTo
     * @param bool $shouldNull
     * @param bool $shouldKeepValue
     * @return void
     */
    public function padToDate(Carbon $dateToPadFrom, Carbon $dateToPadTo, bool $shouldNull = false, bool $shouldKeepValue = false)
    {
        $dateToPadTo = $dateToPadTo->clone();
        $dateToPadTo->addDay();

        $dateToPadFrom = $dateToPadFrom->clone();

        $newDates = [];
        $newValues = [];
        $newPaidIn = [];

        //Loop through all the quarters
        while ($dateToPadFrom->isBefore($dateToPadTo)) {

            $dateIndex = -1;

            //Loop through all the data
            foreach ($this->dates as $index => $date) {
                if ($dateToPadFrom->timestamp == $date->timestamp) {
                    $dateIndex = $index;
                    break;
                }
            }

            $newDates[] = $dateToPadFrom->clone();

            //If data has been found for the date
            if ($dateIndex >= 0) {
                $newValues[] = $this->values[$dateIndex];
                if ($this->hasPaidIn && count ($this->paidIn) > 0) $newPaidIn[] = $this->paidIn[$dateIndex];
            } else {
                if ($shouldNull) {
                    $newValues[] = null;
                } elseif ($shouldKeepValue) {
                    $newValues[] = $newValues[count($newValues)-1];
                } else {
                    $newValues[] = 0;
                }
                $newPaidIn[] = 0;
            }

            //Add a quarter
            $dateToPadFrom->addDay()->addQuarter()->subDay();
        }

        $this->dates = $newDates;
        $this->values = $newValues;
        $this->paidIn = $newPaidIn;
    }

    /**
     * Returns the start quarter
     *
     * @return Carbon|false
     */
    public static function startQuarter()
    {
        return self::convertQuarterToDate(Env::get('START_DATE'));
    }

    /**
     * Returns the end quarter
     *
     * @return Carbon|false
     */
    public static function endQuarter()
    {
        return self::convertQuarterToDate(Env::get('END_DATE'));
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
}
