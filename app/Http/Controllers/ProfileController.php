<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SocialAuth;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Services\SocialAuthService;

class ProfileController extends Controller
{
    
	/**
	 * Show the profile edit form.
     *
     * @return \Illuminate\Http\Response
	 */
    public function form(){

    	$user = Auth::user();

        $providers = $user->socialConnection->lists('provider');

    	return view('profile.form')->with('user', $user)->with('providers', $providers);
    }

    /**
     * Update the profile
     *
     * @return \Illuminate\Http\Response
     */
    public function update(){

        $user = Auth::user();
        $user->name = Input::get('name');
        $user->email = Input::get('email');

        if(bcrypt(Input::get('password')) != $user->password){
            $user->password = bcrypt(Input::get('password'));
        }

        $user->save();

    	return redirect('home');
    }

    /**
     * Connect a new Social Media provider to account.
     *
     * @param $id of social media connection from SocialAuths
     * @return 
     */
    public function addProvider($driver)
    {
        return SocialAuthService::redirectToProvider($driver);
       
    }

    public function addProviderCallback($driver)
    {
        return SocialAuthService::handleProviderCallback($driver);
    }

    /**
     * Disconnect a Social Media provider from account.
     *
     * @param $provider string containing social media service
     * @return Response
     */
    public function disconnectProvider($provider)
    {
        return SocialAuthService::disconnectProvider($provider);
    }


}
