<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFormResponses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'type_id', 'notes', 'ip_address', 'user_id', 'user_agent_string', 'session',
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
