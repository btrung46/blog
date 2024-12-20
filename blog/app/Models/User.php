<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const ROLE_ADMIN = "admin";
    const ROLE_EDITOR = "editor";
    const ROLE_USER = "user";
    const ROLES = [
        self::ROLE_ADMIN => 'admin',   
        self::ROLE_EDITOR=> 'editor',  
        self::ROLE_USER => 'user',
    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('viewAdmin',User::class);
    }
    public function isAdmin(): bool{
        return $this->role === self::ROLE_ADMIN;
    }
    public function isEditor(){
        return $this->role === self::ROLE_EDITOR;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    public function likes() {
        return $this->belongsToMany(Post::class,'post_like');   
    }
    public function hasliked(Post $post) {
        return $this->likes()->where('post_id', $post->id)->exists();
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    
}
