<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinistryDepartment extends Model
{
    protected $table = "ministry_departments";
    protected $guarded = [
        "id", "created_at", "updated_at",
    ];
    protected $fillable = [
        "slug",
        "name",
        "description",
    ];
    public $translatable = [
        "description"
    ];

    //
    // Relationships
    //

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}