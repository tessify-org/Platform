<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use HasTranslations;

    protected $table = "notifications";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "uuid",
        "user_id",
        "title",
        "description",
        "read",
        "read_on",
    ];
    protected $casts = [
        "read" => "boolean"
    ];
    protected $dates = [
        "read_on",
    ];
    public $translatable = [
        "title",
        "description",
    ];

    //
    // Uuid
    //
    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    //
    // Relationships
    // 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}