<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{    
    protected $table = "group_members";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "user_id",
        "group_id",
        "group_role_id",
    ];

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

    public function groupRole()
    {
        return $this->belongsTo(GroupRole::class);
    }
}