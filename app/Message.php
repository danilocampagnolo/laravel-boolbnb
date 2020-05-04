<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'apartment_id',
        'email',
        'content',
        'created_at'
    ];

    public function apartment()
    {
        return $this->belongsTo('App\Apartment');
    }
}
