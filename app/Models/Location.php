<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name' => 'omarato',
        'type',
        'image',
        'description',
        'total_artifacts',
        'city_id'
    ];

    // protected $guarded = ['total_artifacts'];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function artifact(){
        return $this->hasMany(Artifact::class);
    }

    public function favorite()
    {
        return $this->belongsToMany(Favorite::class);
    }
    

    // Update total_artifacts
   /*  public function updateTotalArtifacts()
    {
        $this->total_artifacts = $this->artifacts()->count();
        $this->save();
    } */

}

