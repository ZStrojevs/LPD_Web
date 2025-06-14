<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renter extends Model
{
    protected $fillable = ['user_id', 'phone'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
