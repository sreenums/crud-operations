<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function addresses()
    // {
    //     return $this->hasMany(Address::class)->latest();
    // }

    public function address()
    {
        return $this->hasOne(Address::class)->latest();
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    public static function getUsersExcludingAdminWithAddress()
    {
        return static::latest()->where('role', '<>', 'admin')->with('address')->get();
    }

    /**
     * Authors list
     */
    public static function getPostsUserNames()
    {
        return static::select('id', 'name')
        ->whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('posts');
            })
        ->distinct()
        ->orderBy('name')
        ->get();
    }

    public static function getList()
    {
        return static::where('role', '<>', 'admin')->orderBy('name')->get();
    }

}
