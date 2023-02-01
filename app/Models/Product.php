<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property double price
 * @property double total_value_number
 * @property \DateTime submitted_at
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'submitted_date_human_readable',
        'submitted_date_formatted',
    ];

    public function getSubmittedDateHumanReadableAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['submitted_at'])->diffForHumans();
    }

    public function getSubmittedDateFormattedAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['submitted_at'])->format('Y-m-d h:m A');
    }

}
