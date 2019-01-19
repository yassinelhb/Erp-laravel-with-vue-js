<?php

namespace App\Http\Controllers;


use App\Events\ExempleEvent;
use App\Exports\UsersExport;
use App\Mail\Password;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Excel;
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(){
        $users = User::all();
        $userspagination = User::where('role' ,'!=','Administrateur')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['users' => $users ,'userspagination' => $userspagination] );
    }
    public function show($search){
        $users = User::where('nom', 'like', '%'.$search.'%')->get();
        $userspagination = User::where([['nom', 'like', '%'.$search.'%'],['role' ,'!=','Administrateur']])->orWhere([['email', 'like', '%'.$search.'%'],['role' ,'!=','Administrateur']])->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['users' => $users ,'userspagination' => $userspagination] );

    }
    public function store(Request $request){
        $this->validate($request , [
            'nom' => 'required|min:7|max:20|unique:users',
            'role' => 'required',
            'matricule' => 'required|unique:users|min:5|max:15',
            'email' => 'required|email|unique:users',
            'sexe' => 'required',
            'rue' => 'required',
            'ville' => 'required',
            'etat' => 'required',
            'telephone' => 'required|digits_between:8,12|numeric',
        ]);
        $password = str_random(6);
        $user = new User();
        $user->matricule =  $request->matricule;
        $user->nom =  ucwords($request->nom);
        $user->email =  $request->email;
        $user->password =   Hash::make($password);
        $user->role =  $request->role;
        $user->sexe =  $request->sexe;
        $user->rue =  ucwords($request->rue);
        $user->ville =  ucwords($request->ville);
        $user->etat =  ucwords($request->etat);
        $user->telephone = $request->telephone;
        $user->codetel =  $request->codetel;

        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/').$fileDB);

        }else {
            $fileDB = 'logo-user.jpg';
        }

        $user->image = $fileDB;
        Mail::to($user->email)->send(new Password($user,$password));

        if(count(Mail::failures()) == 0 ){
            $user->save();
            return Response()->json(['etat' => true ] );
        }


    }


    public function update(Request $request,$id){

      $this->validate($request , [
            'nom' => "required|min:7|max:20|unique:users,nom,$id",
            'role' => 'required',
            'matricule' => "required|min:5|max:15|unique:users,matricule,$id",
            'email' => "required|email|unique:users,email,$id",
            'sexe' => 'required',
            'rue' => 'required',
            'ville' => 'required',
            'etat' => 'required',
            'telephone' => 'required|digits_between:8,12|numeric',

        ]);
      $user = User::find($id);

      $user->matricule =  $request->matricule;
      $user->nom =  ucwords($request->nom);
      $user->email =  $request->email;
      $user->password =  $user->password ;
      $user->role =  $request->role;
      $user->sexe =  $request->sexe;
      $user->rue =  ucwords($request->rue);
      $user->ville =  ucwords($request->ville);
      $user->etat =  ucwords($request->etat);
      $user->telephone = $request->telephone;
      $user->codetel =  $request->codetel;

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

    public function destroy(Request $request,$id){

        $user = User::find($id);
        $user->delete();

        return Response()->json(['delete' => true]);

    }


    public function usersExport($type){
        return   Excel::download(new UsersExport(),'users.'.$type);
    }



}
