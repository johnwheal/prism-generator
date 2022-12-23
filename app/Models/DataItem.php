<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataItem
{
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
    public array $values;

    /**
     * The paid in values
     *
     * @var array
     */
    public array $paidIn;

    /**
     * Dates
     *
     * @var array
     */
    public array $dates;

    /**
     * The constructor
     *
     * @param string $name
     * @param array $dates
     * @param array $values
     * @param array $paidIn
     */
    public function __construct(string $name, array $dates, array $values, array $paidIn = [])
    {
        $this->name = $name;
        $this->dates = $dates;
        $this->values = $values;
        $this->paidIn = $paidIn;
    }

    /**
     * Returns whether the item has paid in data
     *
     * @return bool
     */
    public function hasPaidIn(): bool
    {
        return (count($this->paidIn) != 0);
    }
}
