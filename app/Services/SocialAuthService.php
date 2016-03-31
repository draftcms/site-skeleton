<?php

namespace App\Services;

use App\Models\User;
use App\Models\SocialAuth;
use App\Services\AvatarService;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;
use Image;
use Illuminate\Http\Request;

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
    public static function handleProviderCallback($request, $driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return Redirect::to('auth/'.$driver);
        }

        return SocialAuthService::findOrCreateUser($request, $user, $driver);
    }

    /**
     * Obtain the user information from Social Media.
     *
     * @param $driver string containing proper social media driver
     * @return Response
     */
    public static function disconnectProvider($request, $driver)
    {
        try {
            
            $connection = Auth::user()->socialConnection()->where('provider',$driver);
            $connection->delete();
            $request->session()->flash('status', 'Your ' . $driver . ' account was successfully disconnected.');

        } catch (\Exception $e) {
            $request->session()->flash('fail', 'Your ' . $driver . ' account could not be disconnected.');
        }

        return redirect('home');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $social_user data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private static function findOrCreateUser($request, $social_user, $provider)
    {

        $redirect_to = 'profile';

        /* if user is logged in connect new provider */   
        if($user = Auth::user()){

            /* create social user connection */
            SocialAuthService::createSocialUser($request, $user, $social_user, $provider);

        /* if not logged in but user exists, login and get user */
        } elseif ($auth_user = SocialAuth::where('provider', $provider)->where('provider_id',$social_user->id)->first()) {

            $user =  Auth::loginUsingId($auth_user->user_id);


        /* if not logged in and no user exists attempt to create user with details from provider */
        } else {
            
            /* set user email to name if no value returned from provider */
            if(!$social_user->email) {
                $social_user->email = $social_user->name;
            }

            /* set user name & email to nickname if no value returned from provider */
            if(!$social_user->name) {
                $social_user->email = $social_user->nickname;
                $social_user->name = $social_user->nickname;
            }

            /* try to create user entry */
            try {
                
                /* create Users entry */
                $user = User::create([
                    'name'      => $social_user->name,
                    'email'     => $social_user->email,
                    'password'  => '',
                ]);

            } catch (\Exception $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    $request->session()->flash('fail', 'A user is already registered under that email');
                }

                $redirect_to = 'login';
            }

            if($user){

                /* Login user */
                Auth::loginUsingId($user->id);

                /* create social user connection */
                SocialAuthService::createSocialUser($request, $user, $social_user, $provider);

                /* if an avatar is returned by the services create image and store to server */
                if($social_user->avatar) {

                    /* create instance of avatar image */
                    $img = Image::make($social_user->avatar);

                    /* add avatar entry */
                    AvatarService::addUserAvatar($request, $user, $img, $provider);
                } 
            }
                
        }

        return $redirect_to;
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $social_user data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private static function createSocialUser($request, $user, $social_user, $provider)
    {

        /* set user email to name if no value returned from provider */
        if(!$social_user->email) {
            $social_user->email = $social_user->name;
        }

        /* Try to create social user entry */
        try {

            /* create SocialAuths entry pointing to  already registered user */
            SocialAuth::create([
                'user_id'       => $user->id,
                'provider'      => $provider,
                'provider_id'   => $social_user->id,
                'token'         => $social_user->token,
                'name'          => $social_user->name,
                'email'         => $social_user->email,
            ]);

            $request->session()->flash('status', 'You have successfully connected your ' . $provider . ' account.');
            
        } catch (\Exception $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                $request->session()->flash('status', 'A user is already registered under that email');
            }
        }
        
            
    }
}