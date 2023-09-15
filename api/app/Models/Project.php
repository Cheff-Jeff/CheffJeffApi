<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
