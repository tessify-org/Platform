<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;

class GroupMemberApplication extends Model
{    
    protected $table = "group_member_applications";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "uuid",
        "user_id",
        "group_id",
        "motivation",
        "processed",
        "accepted",
    ];
    protected $casts = [
        "processed" => "boolean",
        "accepted" => "boolean",
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

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}