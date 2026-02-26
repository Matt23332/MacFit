<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = ['name', 'start_time', 'duration','description', 'value', 'category_id'];
}
