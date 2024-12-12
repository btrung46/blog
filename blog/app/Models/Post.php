<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $casts = [
        'published_at' => 'datetime',
    ];
    protected $fillable = [
        'user_id',
        'title',    
        'slug',
        'body',
        'image',
        'published_at',
        'featured',
    ];
    protected function scopePublished($query){
        $query->where("published_at","<=" , Carbon::now());
    }
    protected function scopeFeatured($query){
        $query->where("featured",true);
    }
    public function author() {
        return $this->belongsTo(User::class,"user_id");
    }
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function getExcerpt()
    {
        return str::limit(strip_tags($this->body), 150);
    }

    public function getReadingTime()
    {
        $mins = round(str_word_count($this->body) / 250);

        return ($mins < 1) ? 1 : $mins;
    }
    public function getThumbnailUrl()
    {
        $isUrl = str_contains($this->image, 'http');

        return ($isUrl) ? $this->image : Storage::disk('public')->url(path: $this->image);
    }
    public function scopeWithCategory($query, string $category)
    {
        $query->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });
    }
    public function likes() {
        return $this->belongsToMany(User::class,'post_like');   
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
