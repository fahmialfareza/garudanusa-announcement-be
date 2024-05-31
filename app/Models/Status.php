<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ["status", "message", "color"];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}