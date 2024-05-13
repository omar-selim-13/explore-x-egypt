<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(){
        return $this->hasMany(User::class);
    }

    public function admin(){
        return $this->hasOne(Admin::class);
    }
}
