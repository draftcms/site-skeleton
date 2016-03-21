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

class SocialAuthService {
	
    /**
     * Redirect the user to the Social Media authentication page.
     * 
     * @param $driver string containing proper social media driver
     * @return Redirect to home page
     */
    public static function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from Social Media.
     *
     * @param $driver string containing proper social media driver
     * @return Response
     */
    public static function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return Redirect::to('auth/'.$driver);
        }

        $authUser = SocialAuthService::findOrCreateUser($user, $driver);

        return redirect('home');
    }

    /**
     * Obtain the user information from Social Media.
     *
     * @param $driver string containing proper social media driver
     * @return Response
     */
    public static function disconnectProvider($driver)
    {
        $connection = Auth::user()->socialConnection()->where('provider',$driver);
        
        $connection->delete();

        return redirect('home');
    }



    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialUser data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private static function findOrCreateUser($socialUser, $provider)
    {

        if($user = Auth::user()){

            // create SocialAuths entry pointing to registered user
            SocialAuth::create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'token' => $socialUser->token,
                'name' => $socialUser->name,
                'email' => 'test',
            ]);

            return redirect('profile');
        }
        // get user if already registered and return
        if ($authUser = SocialAuth::where('token', $socialUser->token)->first()) {
            return Auth::loginUsingId($authUser->user_id);
        }

        // set user email to name if no value returned from provider
        if(!$socialUser->email){
            $socialUser->email = $socialUser->name;
        }

        // create Users entry then create Social_Auths entry
        $user = User::create([
            'name' => $socialUser->name,
            'email' => $socialUser->email,
            'password' => '',
        ]);

        // create SocialAuths entry pointing to registered user
        SocialAuth::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->id,
            'token' => $socialUser->token,
            'name' => $socialUser->name,
            'email' => $socialUser->email,
        ]);

        // create instance of avatar image
        $img = Image::make($socialUser->avatar);
        $f_name = 'UID' . $user->id . '_' . time() . '.png';
        $rel_path = 'avatar/' . $f_name;
        $path = public_path($rel_path);
        
        $img->save($path);

        // create UserAvatar entry to associate with registered user
        UserAvatar::create([
            'user_id' => $user->id,
            'file_path' => $rel_path,
            'original_name' => 'test',
            'extension' => '.png',
            'size' => $img->filesize(),
            'height' => $img->height(),
            'width' => $img->width(),
        ]);

        return Auth::loginUsingId($user->id);
    }

}



