<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Donation extends AbstractAsset
{
    /**
     * Get all the donations
     *
     * @return self
     */
    public static function getAllDonations(): self
    {
        return self::getAllData('data/charity.json')[0];
    }

    /**
     * Convert the donation to cumulative
     *
     * @return void
     */
    public function convertToCumulative()
    {
        $cumulativeValue = 0;

        foreach ($this->values as $index => $value) {
            $cumulativeValue += $value;

            $this->values[$index] = $cumulativeValue;
        }
    }
}
