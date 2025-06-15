<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'category', 'price', 'image'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function rentalRequests()
    {
        return $this->hasMany(RentalRequest::class);
    }
}
