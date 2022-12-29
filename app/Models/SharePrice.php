<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePrice extends DateClass
{
    /**
     * The share prices
     *
     * @var array
     */
    public array $values = [];


    /**
     * The constructor
     *
     * @param $sharePricesJson
     */
    public function __construct($sharePricesJson)
    {
        foreach ($sharePricesJson as $sharePriceJson) {
            $this->dates[] = self::convertQuarterToDate($sharePriceJson->date);
            $this->values[] = $sharePriceJson->price;
        }

        $startQuarter = self::convertQuarterToDate($sharePricesJson[0]->date);
        $endQuarter = self::endQuarter();

        self::padToDate($startQuarter, $endQuarter, false, true);
    }
}
