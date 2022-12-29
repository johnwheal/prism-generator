<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company
{

    /**
     * The company name
     *
     * @var string
     */
    public string $name;

    /**
     * The Crowdfunding Platform
     *
     * @var string
     */
    public string $platform;

    /**
     * The status of the company
     *
     * @var string
     */
    public string $status;

    /**
     * The share prices
     *
     * @var SharePrice
     */
    public SharePrice $sharePrice;

    /**
     * Get all the companies
     *
     * @return array
     */
    public static function getCompanies()
    {
        $jsonCompanies = Storage::get('data/crowdfunding.json');
        $jsonCompanies = json_decode($jsonCompanies);

        //This is the final list of companies that get returned
        $companies = [];

        foreach ($jsonCompanies as $jsonCompany) {
            $company = new self();
            $company->name = $jsonCompany->name;
            $company->platform = $jsonCompany->platform;
            $company->status = $jsonCompany->status;

            $company->sharePrice = new SharePrice($jsonCompany->share_price);

            $companies[] = $company;
        }

        return $companies;
    }

    /**
     * Get Platform data
     *
     * @param array $companies
     * @return array
     */
    public static function getPlatformData(array $companies)
    {
        $platforms = [];

        foreach ($companies as $company) {
            if (!isset($platforms[$company->platform])) {
                $platforms[$company->platform] = 0;
            }

            $platforms[$company->platform]++;
        }

        return $platforms;
    }

    /**
     * Get Company Status data
     *
     * @param array $companies
     * @return array
     */
    public static function getCompanyStatusData(array $companies)
    {
        $statues = [];

        foreach ($companies as $company) {
            if (!isset($statues[$company->status])) {
                $statues[$company->status] = 0;
            }

            $statues[$company->status]++;
        }

        return $statues;
    }

}
