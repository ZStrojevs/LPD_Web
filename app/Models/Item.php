<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'price'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
