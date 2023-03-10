<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends AbstractAsset
{
    /**
     * Flag that the asset has paid in data
     *
     * @var bool
     */
    protected bool $hasPaidIn = true;

    /**
     * Get all the assets
     *
     * @return array
     */
    public static function getAllAssets(): array
    {
        $assets = self::getAllData('data/assets.json');
        foreach ($assets as $asset) {
            $asset->paidIn = $asset->getCalculatedPaidIn();
        }
        return $assets;
    }
}
