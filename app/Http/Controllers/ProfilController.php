<?php

namespace App\Http\Controllers;

use App\Rules\CurrentPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class ProfilController extends Controller
{

    public function index(){
        $user = User::find(Auth::user()->id);
        return $user;
    }

    public function update($id,Request $request){
        $this->validate($request , [
            'nom' => "required|min:7|max:20|unique:users,nom,$id",
            'rue' => 'required',
            'ville' => 'required',
            'etat' => 'required',
            'telephone' => 'required|digits_between:8,12|numeric',

        ]);
        $user = User::find($id);
        $user->nom = $request->nom;
        $user->telephone = $request->telephone;
        $user->codetel = $request->codetel;
        $user->rue = $request->rue;
        $user->ville = $request->ville;
        $user->etat = $request->etat;
        $user->password = $user->password;

       if($request->get('image') == $user->image){
            $fileDB =  $user->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/').$fileDB);
        }
        else {
            $fileDB = 'logo-user.jpg';
        }

        $user->image = $fileDB;
        $user->save();
        return Response()->json(['etat' => true] );

    }

    public function editPassword($id, Request $request){
        $user = User::find($id);
        $this->validate($request , [
            'current_password' => [new CurrentPassword()],
            'password' => 'required|min:6|max:15|confirmed',
        ]);
        $user->password =   Hash::make($request->password);
        if($request->get('image') == $user->image){
            $fileDB =  $user->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/').$fileDB);
        }
        else {
            $fileDB = 'logo-user.jpg';
        }

        $user->image = $fileDB;
        $user->save();
        return Response()->json(['etat' => true] );


    }

}
