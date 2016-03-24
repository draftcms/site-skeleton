<?php

namespace App\Services;

use App\Models\User;
use App\Models\SocialAuth;
use App\Models\UserAvatar;
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
        $f_name = 'UID' . $user->id . '_' . time();

        /* gather image details */
        $details = [
            'extension' => $ext,
            'orig_name' => $orig_name,
            'path' => 'avatar/' . $f_name . '.' . $ext,
            'path_sm' => 'avatar/' . $f_name . '_sm.' . $ext,
            'path_md' => 'avatar/' . $f_name . '_md.' . $ext,
        ];

        /* store file on server */
        $path = public_path($details['path']);
        $img->save($path);

        /* create instances for small and medium images */
        $img_md = Image::make($path);
        $img_sm = Image::make($path);

        /* resize images for thumbnails preventing upscaling */
        $img_md->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img_sm->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        /* crop thumbnails to ensure proper sizing */
        $img_md->crop(250, 250);
        $img_sm->crop(100, 100);

        /* save edited images to server */
        $img_md->save(public_path($details['path_md']));
        $img_sm->save(public_path($details['path_sm']));


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
            'file_path_sm' => $details['path_sm'],
            'file_path_md' => $details['path_md'],
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
