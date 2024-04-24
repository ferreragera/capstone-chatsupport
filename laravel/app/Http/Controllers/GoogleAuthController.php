<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\GoogleUserInfo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    { 
        return Socialite::driver('google')->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try { 
            $socialiteUser = Socialite::driver('google')->user();

            // Insert google user info 
            $googleUserInfo = GoogleUserInfo::updateOrCreate([
                'email'   => $socialiteUser->email,
            ],[
                'gid'           => $socialiteUser->user['id'],
                'email'         => $socialiteUser->user['email'],
                'givenName'     => $socialiteUser->user['given_name'],
                'familyName'    => $socialiteUser->user['family_name'],
                'name'          => $socialiteUser->user['name'],
                'picture'       => $socialiteUser->user['picture'],
                'verifiedEmail' => $socialiteUser->user['verified_email'],
                'hd'            => $socialiteUser->user['hd'] ?? NULL
            ]);

            $user = User::where('email', $googleUserInfo->email)->first();
            if(!$user) {
                dd('user had no access');
                return;
            }
            
            Auth::login($user);
            return redirect()->intended('/dashboard');
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}