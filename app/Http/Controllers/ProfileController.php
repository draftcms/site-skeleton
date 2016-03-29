<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SocialAuth;
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

        /* if password differs from what is stored */
        if(bcrypt(Input::get('password')) != $user->password){
            
            
            if(Input::get('password')){
                $user->password = bcrypt(Input::get('password'));
            } 
        }
        
        $user->save();

    	return redirect('profile');
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

    public function addProviderCallback(Request $request, $driver)
    {
        $go_to = SocialAuthService::handleProviderCallback($request, $driver);


        return redirect($go_to);
    }

    /**
     * Disconnect a Social Media provider from account.
     *
     * @param $provider string containing social media service
     * @return Response
     */
    public function disconnectProvider(Request $request, $provider)
    {
        SocialAuthService::disconnectProvider($request, $provider);

        return redirect('profile');
    }

}
