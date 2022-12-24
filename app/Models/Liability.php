<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Liability extends AbstractAsset
{
    /**
     * Get all the liabilities
     *
     * @return array
     */
    public static function getAllLiabilities(): array
    {
        return self::getAllData('data/liabilities.json');
    }
}
