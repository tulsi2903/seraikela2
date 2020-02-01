<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use App\User;



class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // protected $table = 'users';
    
    protected $fillable = [
        'name', 'email', 'password', 'userRole',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // public function users()
    // {
    //     return $this->hasMany('App\User');        
    // }
}
