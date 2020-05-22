<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GroupRole extends Model 
{
    use HasTranslations;
    
    protected $table = "group_roles";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "group_id",
        "name",
        "description",
        "deleteable",
        "default",
    ];
    protected $casts = [
        "deleteable" => "boolean",
        "default" => "boolean",
    ];
    public $translatable = [
        "name",
        "description",
    ];

    //
    // Relationships
    //

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}