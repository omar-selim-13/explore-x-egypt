<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function admin(){
        return $this->hasMany(Admin::class);
    }
}
