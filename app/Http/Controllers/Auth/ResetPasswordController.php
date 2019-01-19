<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showResetForm($tosken){
       return view('auth.passwords.code')->with(['email' => $tosken]);
    }

    public function loginReset(Request $request)
    {

        $user = User::where('email',$request->email)->get();

        if ($user[0]->resetcode==$request->code)
        {
            $user[0]->password=Hash::make($request->code);
            $user[0]->resetcode="";
            $user[0]->save();
            Auth::loginUsingId($user[0]->id);
            return redirect()->intended('/');
        }
        else{
            return Redirect::back()->withErrors(['code'=>'Code invalide']);

        }
    }

    public function __construct()
    {
        $this->middleware('guest');
    }


}
