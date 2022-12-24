<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Investment extends AbstractAsset
{
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
        return self::getAllData('data/investments.json');
    }
}
