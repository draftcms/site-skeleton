<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Access all relationships to this User
     *
     */
    public function socialConnection(){
        return $this->hasMany('App\SocialAuth');
    } 
    
    /**
     * Access all relationships to this User
     *
     */
    public function avatar(){
        return $this->hasOne('App\UserAvatar');
    } 
}
