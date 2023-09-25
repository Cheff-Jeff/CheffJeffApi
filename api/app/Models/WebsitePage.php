<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'title',
        'meta_title',
        'meta_description',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function menuItem()
    {
        return $this->hasOne(MenuItem::class);
    }
}
