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
     * Dates
     *
     * @var array
     */
    public array $dates;

    public function __construct(string $name, array $values, array $dates)
    {
        $this->name = $name;
        $this->values = $values;
        $this->dates = $dates;
    }
}
