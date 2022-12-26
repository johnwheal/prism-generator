<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\exactly;

class DayToDay extends AbstractAsset
{
    /**
     * The constructor
     *
     * @param $type
     */
    public function __construct($type)
    {
        $jsonCategories = Storage::get('data/categories.json');
        $jsonCategories = json_decode($jsonCategories, true);
        $categoriesInterested = [];

        foreach (array_keys($jsonCategories) as $category) {
            if ($category == $type) {
                $categoriesInterested = $jsonCategories[$category];
            }
        }

        $this->name = $type;

        $jsonDayToDay = Storage::get('data/day-to-day.json');
        $jsonDayToDay = json_decode($jsonDayToDay, true);

        foreach ($jsonDayToDay as $quarter) {
            $this->dates[] = self::convertQuarterToDate($quarter['date']);
            foreach (array_keys($quarter['data']) as $category) {
                if (in_array($category, $categoriesInterested)) {
                    $this->values[$category][] = $quarter['data'][$category];
                }
            }
        }

        $this->dates = $this->getPreciseTimestamps();
    }

    /**
     * Get all category types
     *
     * @return array
     */
    public static function getAllCategoryTypes()
    {
        $jsonCategories = Storage::get('data/categories.json');
        $jsonCategories = json_decode($jsonCategories, true);
        $categories = [];

        foreach (array_keys($jsonCategories) as $category) {
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Get the overall day to day by combining data
     *
     * @param array $dayToDays
     * @return mixed
     */
    public static function getOverallDayToDay(array $dayToDays)
    {
        $overallDayToDay = new self('');
        $overallDayToDay->name = 'Overall';

        foreach ($dayToDays as $dayToDay) {
            $arrayKey = array_keys($dayToDay->values)[0];

            for ($index = 0; $index < count($dayToDay->values[$arrayKey]); $index++) {
                $overallValue = 0;

                foreach ($dayToDay->values as $value) {
                    $overallValue += $value[$index];
                }

                $overallDayToDay->values[$dayToDay->name][] = $overallValue;
            }
        }

        //Move the income to the end of the chart
        $income = reset($overallDayToDay->values);
        array_shift($overallDayToDay->values);
        $overallDayToDay->values['income'] = $income;

        return $overallDayToDay;
    }
}
