<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected function scopePublished($query){
        $query->where("published_at","<=" , Carbon::now());
    }
    protected function scopeFeatured($query){
        $query->where("featured",true);
    }
}
