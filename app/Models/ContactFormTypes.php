<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFormTypes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'friendly_name', 'recipients',
    ];

    /**
     * Access all relationships to this User
     *
     */
    public function messages(){
        return $this->hasMany('App\Models\ContactForm');
    } 
}
