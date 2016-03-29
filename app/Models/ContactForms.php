<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactForms extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'type_id', 'notes'
    ];

    /**
     * Access inverse relationship to user
     *
     */
    public function type()
    {
        return $this->belongsTo('App\Models\ContactFormTypes');
    }
}
