<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SocialAuth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Redirect the user to the Social Media authentication page.
     * 
     * @param $driver string containing proper social media driver
     * @return Redirect to home page
     */
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from Social Media.
     *
     * @param $driver string containing proper social media driver
     * @return Response
     */
    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return Redirect::to('auth/'.$driver);
        }

        $authUser = $this->findOrCreateUser($user, $driver);

        return redirect('home');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialUser data object from social media provider
     * @param $provider string containing driver name
     * @return Auth logged in user
     */
    private function findOrCreateUser($socialUser, $provider)
    {
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

        return Auth::loginUsingId($user->id);

    }
}
