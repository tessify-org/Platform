<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AssignmentType extends Model
{
    use HasTranslations;

    protected $table = "assignment_types";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "label",
    ];
    public $translatable = [
        "label",
    ];

    //
    // Relationships
    //

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}