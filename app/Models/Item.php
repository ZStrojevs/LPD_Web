<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['owner_id', 'name', 'description', 'price_per_day'];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
