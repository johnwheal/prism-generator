<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends AbstractAsset
{
    /**
     * Get all the assets
     *
     * @return array
     */
    public static function getAllAssets(): array
    {
        return self::getAllData('data/assets.json');
    }
}
