<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class FaqCategory extends Model
{
    use Sluggable;
    use HasTranslations;
    
    protected $table = "faq_categories";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "slug",
        "description",
    ];
    public $translatable = [
        "name",
        "description",
    ];

    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "name"]];
    }

    //
    // Relationships
    //

    public function faqs()
    {
        return $this->hasMany(Faq::class, "faq_category_id", "id");
    }
}