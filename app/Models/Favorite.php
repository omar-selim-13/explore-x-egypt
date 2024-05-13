<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'user_id',
    ];
  
    public function locations()
{
    return $this->belongsTo(Location::class, 'location_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    
}
