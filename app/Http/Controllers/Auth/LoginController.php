<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
     *  protected function authenticated ushbu funksiyada
     *  agar foydalanuvchi login qilsa va uning user_type admin bolsa admin sahifasiga
     *  agar user_type user bolsa user sahifasiga yo'naltiriladi
     */
    protected function authenticated()
    {
        if (Auth::user()->user_type == 1) {
            return redirect('/admin')->with('success','welocome admin');
        }elseif(Auth::user()->user_type == 0){
            // return redirect('/user')->with('success','logged in successfully');
            return redirect('/')->with('success','logged in successfully');
        }
        
    }
    
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ]);
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    
    // protected function redirectTo()
    // {    
        // if (Auth::user()->user_type == 1) {
        //     return redirect('/admin')->with('success','welocome admin');
        // }elseif(Auth::user()->user_type == 0){
        //     return redirect('/user')->with('success','logged in successfully');
        // }
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
