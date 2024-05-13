<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
    use HasFactory;

    protected $fillable = [
        'artifact_name',
        'date',
        'image',
        'description',
        'location_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    
   /*  protected static function boot()
    {
        parent::boot();

        // Update total_artifacts when an artifact is saved or deleted
        static::saved(function ($artifact) {
            $artifact->location->updateTotalArtifacts();
        });

        static::deleted(function ($artifact) {
            $artifact->location->updateTotalArtifacts();
        });
    } */
}
