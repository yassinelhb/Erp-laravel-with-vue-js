<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class ForgotPasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function showLinkRequestForm(){
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request){
        $user = User::where('email',$request->email)->get();
        if ($user->count()>0) {
            $coderandom = str_random(6);
            Mail::to($user[0]->email)->send(new ResetPassword($user[0],$coderandom));
            User::where('email',$request->email)->update(array('resetcode' => $coderandom));
            return Redirect('password/reset/'.$user[0]->email);
        }else{
            return Redirect::back()->withErrors(['email'=>'This Email not exsit']);
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
