<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'file_path', 'file_path_sm', 'file_path_md', 'original_name', 'extension', 'size', 'height', 'width'
    ];

    /**
     * Access inverse relationship to user
     *
     */
    public function avatar()
    {
        return $this->belongsTo('App\Models\User');
    }
}
