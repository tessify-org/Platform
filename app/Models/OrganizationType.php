<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrganizationType extends Model
{
    use HasTranslations;

    protected $table = "organization_types";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "label",
        "description",
    ];
    public $translatable = [
        "name",
        "label",
        "description",
    ];

    //
    // Relationships
    //

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}