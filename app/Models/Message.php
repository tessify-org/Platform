<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $guarded = ["id", "created_id", "updated_id"];
    protected $fillable = [
        "uuid",
        "type",
        "sender_id",
        "receiver_id",
        "reply_to_id",
        "subject",
        "message",
        "read",
        "read_on",
        "data",
    ];
    protected $casts = [
        "read" => "boolean",
    ];
    protected $dates = [
        "read_on",
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

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id", "id");
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id", "id");
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, "reply_to_id", "id");
    }

    //
    // Mutators
    //

    public function setDataAttribute($value)
    {
        $this->attributes["data"] = serialize($value);
    }

    public function getDataAttribute($value)
    {
        return unserialize($value);
    }
}