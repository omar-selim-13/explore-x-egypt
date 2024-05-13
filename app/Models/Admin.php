<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Admin extends Model implements Authenticatable
{
    use HasFactory , HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password', 'remember_token',];


    public function getAuthIdentifierName()
    {
        return 'email'; 
    }

    public function getAuthIdentifier()
    {
        return $this->email; 
    }

    public function getAuthPassword()
    {
        return Hash::make($this->password);
    }

    public function getRememberToken()
    {
        return null; 
    }

    public function setRememberToken($value)
    {
        return null;
    }

    public function getRememberTokenName()
    {
        return null; 
    }
    
}
