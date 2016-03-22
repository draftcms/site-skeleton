<?php

namespace App\Services;

use App\User;
use App\SocialAuth;
use App\UserAvatar;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;
use Image;
use Illuminate\Support\Facades\Input;


class AvatarService {


    /**
     * Add an avatar for user
     *
     */
    public static function addUserAvatar($user, $img, $orig_name) {

        /* remove existing avatar entry in UserAvatar table and 
         * delete image from server
         */
        if($user->avatar){
            $user->avatar()->delete();
        }

        /* find extension and name image file */
        $ext = AvatarService::getExtFromMimeType($img->mime());
        $f_name = 'UID' . $user->id . '_' . time() . '.' . $ext;

        /* gather image details */
        $details = [
            'extension' => $ext,
            'orig_name' => $orig_name,
            'path' => 'avatar/' . $f_name,
        ];

        /* store file on server */
        $path = public_path($details['path']);
        $img->save($path);

        /* create entry */
        AvatarService::createAvatars($user, $img, $details);
    }

    /**
     * Create avatar entry in UserAvatar table
     *
     */
    private static function createAvatars($user, $img, $details) {
        
        // create UserAvatar entry to associate with registered user
        UserAvatar::create([
            'user_id' => $user->id,
            'file_path' => $details['path'],
            'original_name' => $details['orig_name'],
            'extension' => $details['extension'],
            'size' => $img->filesize(),
            'height' => $img->height(),
            'width' => $img->width(),
        ]);
    }

    private static function getExtFromMimeType($type) {

        $mime_types = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
        ];

        return $mime_types[$type];

    }
}
