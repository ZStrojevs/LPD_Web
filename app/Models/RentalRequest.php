<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalRequest extends Model
{
    protected $fillable = ['item_id', 'renter_id', 'start_date', 'end_date', 'status'];

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
