<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasTranslations;

    protected $table = "skills";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
    ];
    public $translatable = [
        "name"
    ];

    //
    // Relationships
    // 

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function teamRoles()
    {
        return $this->belongsToMany(TeamRole::class);
    }
}