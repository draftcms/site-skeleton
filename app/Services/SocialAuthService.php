<?php

namespace App\Services;

use App\User;
use App\SocialAuth;
use App\Services\AvatarService;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;
use Image;

class SocialAuthService {
	
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $social_user data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private static function createSocialUser($user, $social_user, $provider)
    {

        /* set user email to name if no value returned from provider */
        if(!$social_user->email) {
            $social_user->email = $social_user->name;
        }
        
        /* create SocialAuths entry pointing to  already registered user */
        SocialAuth::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $social_user->id,
            'token' => $social_user->token,
            'name' => $social_user->name,
            'email' => $social_user->email,
        ]);
    }

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

        $auth_user = SocialAuthService::findOrCreateUser($user, $driver);

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
     * @param $social_user data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private static function findOrCreateUser($social_user, $provider)
    {

        $user = null;

        /* if user is logged in connect new provider */   
        if($user = Auth::user()){

            /* create social user connection */
            SocialAuthService::createSocialUser($user, $social_user, $provider);

        /* if not logged in but user exists */
        } elseif ($auth_user = SocialAuth::where('provider', $provider)->where('provider_id',$social_user->id)->first()) {

            $user =  Auth::loginUsingId($auth_user->user_id);


        /* if not logged in and no user exists */
        } else {
            
            /* set user email to name if no value returned from provider */
            if(!$social_user->email) {
                $social_user->email = $social_user->name;
            }

            /* create Users entry */
            $user = User::create([
                'name' => $social_user->name,
                'email' => $social_user->email,
                'password' => '',
            ]);

            /* create social user connection */
            SocialAuthService::createSocialUser($user, $social_user, $provider);

            /* create image and store to server */
            $img = Image::make($social_user->avatar);
            $f_name = 'UID' . $user->id . '_' . time() . '.png';
            $rel_path = 'avatar/' . $f_name;
            $path = public_path($rel_path);
            $img->save($path);

            /* add avatar entry*/
            AvatarService::createAvatar($user, $img, $f_name, $rel_path, '');

        }

        return $user;
    }

    

}



