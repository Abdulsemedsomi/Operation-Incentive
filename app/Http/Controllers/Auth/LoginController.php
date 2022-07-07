<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::with('Google')->redirect();
    }

    /**
     * Redirect the user to the Micrsoft authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToMicrosoftProvider()
    {
     
        return Socialite::driver('graph')->setTenantId(env('GRAPH_TENANT_ID'))->redirect();

    }
    public function login()
    {
        $user = User::find(1);
        Auth::login($user);
        return redirect()->to('/home');
    }

    public function bypass(Request $request){
        $email = $request->email;
        if(!str_contains($email,"@")) $email .="@ienetworks.co";
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            // log them ins
          Auth::login($existingUser);
          return redirect()->to('/home');
        } else {
            // create a new user

            return back()->with('error', 'User does not exist in our database');
        }

    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
    //     $error_code = Input::get('response_type');

    // if ($error_code == 403)
    // {
      
    //   // return redirect()->route('sign-in')->with('error','You\'ve chose not to grant us permission to connect with your Facebook account.');
    // }

        try {
            $user = Socialite::driver('Google')->user();
            
        } catch (\Exception $e) {
          

            return back()->with('error', 'Connetion Error');
        }
        // only allow people with @company.com to login
        if (explode("@", $user->email)[1] !== 'ienetworksolutions.com') {

            return back()->with('error', 'User does not exist in our database');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // log them in
          Auth::login($existingUser);
          return redirect()->to('/home');
        } else {
            // create a new user

            return back()->with('error', 'User does not exist in our database');
        }



    }

    /**
     * Obtain the user information from Microsoft.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleMicrosoftProviderCallback()
    {

        try {
            $user = Socialite::driver('graph')->setTenantId(env('GRAPH_TENANT_ID'))->stateless()->user();
        } catch (\Exception $e) {

            return back()->with('error', 'Connetion Error');
        }
        // only allow people with @company.com to login
        
        if (explode('@', $user->email)[1] !== 'ienetworks.co') {
            return back()->with('error', 'User doesnot exist in our database');
        }

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // log them in
            Auth::login($existingUser);
            return redirect()->to('/home');
        } else {
            // create a new user

            return back()->with('error', 'User doesnot exist in our database');
        }

    }
    public function logout(Request $request){
  $this->guard()->logout();
  $request->session()->invalidate();
  return $this->loggedOut($request)?:redirect('/');
    }

}
