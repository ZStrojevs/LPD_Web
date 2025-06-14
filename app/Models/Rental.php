<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['renter_id', 'item_id', 'rental_start', 'rental_end', 'total_price'];
    public function renter()
    {
        return $this->belongsTo(Renter::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
