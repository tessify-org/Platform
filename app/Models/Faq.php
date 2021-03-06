<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Faq extends Model
{
    use Sluggable;

    protected $table = "faqs";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "user_id",
        "faq_category_id",
        "slug",
        "question",
        "answer",
    ];
    public $translatable = [
        "question",
        "answer",
    ];

    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "question"]];
    }

    //
    // Relationships
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, "faq_category_id", "id");
    }
}