<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone', 'city_of_birth', "date_of_birth", "address_from", "school", "status_id", "total_score"];
}