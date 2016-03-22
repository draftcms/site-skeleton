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


    public static function addUserAvatar($id) {
        
        $user = Auth::user();

        if($user->avatar){
            $avatar = UserAvatar::find($id);
            $user->avatar()->delete();
        }

        $image = Input::file('file');
        $extension = $image->getClientOriginalExtension();
        $orig_name = $image->getClientOriginalName(); 
        $img = Image::make($image);
        $f_name = 'UID' . $user->id . '_' . time() . '.png';
        $rel_path = 'avatar/' . $f_name;
        $path = public_path($rel_path);
        $img->save($path);

        // create UserAvatar entry to associate with registered user
        UserAvatar::create([
            'user_id' => Auth::user()->id,
            'file_path' => $rel_path,
            'original_name' => $orig_name,
            'extension' => $extension,
            'size' => $img->filesize(),
            'height' => $img->height(),
            'width' => $img->width(),
        ]);

        return redirect('home');
    }

    public static function addSocialAvatar() {

    }

    public static function createAvatar($user, $img, $f_name, $path, $orig_name) {
        

        // create UserAvatar entry to associate with registered user
        UserAvatar::create([
            'user_id' => $user->id,
            'file_path' => $path,
            'original_name' => 'test',
            'extension' => '.png',
            'size' => $img->filesize(),
            'height' => $img->height(),
            'width' => $img->width(),
        ]);
    }
	


}



