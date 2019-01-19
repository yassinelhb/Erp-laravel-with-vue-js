<?php

namespace App\Http\Controllers;

use App\Cgi;
use App\Exports\CgiExport;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Excel;

class CgiController extends Controller
{
    public function index(){
        $cgis = Cgi::all();
        $cgispagination = Cgi::with('canton','avs')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['cgis' => $cgis ,'cgispagination' => $cgispagination] );

    }

    public function show($search){
        $cgis = Cgi::where('canton_id',$search)->get();;
        $cgispagination = Cgi::with('canton','avs')->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['cgis' => $cgis ,'cgispagination' => $cgispagination] );

    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:cgis',
            'libelle' => 'required',
            'canton_id' =>  'required',
        ]);
        $cgi = new Cgi();
        $cgi->code =  $request->code;
        $cgi->libelle =  ucwords($request->libelle);
        $cgi->canton_id =  $request->canton_id;

        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/cgi/').$fileDB);

        }else {
            $fileDB = 'cgi.png';
        }

        $cgi->image = $fileDB;
        $cgi->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:cgis,code,$id",
            'libelle' => "required",
            'canton_id' =>  'required',

        ]);
        $cgi = Cgi::find($id);
        $cgi->code =  $request->code;
        $cgi->libelle =  ucwords($request->libelle);
        $cgi->canton_id =  $request->canton_id;

        if($request->get('image') == $cgi->image){
            $fileDB =  $cgi->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/cgi/').$fileDB);

        }else {
            $fileDB = 'cgi.png';
        }

        $cgi->image = $fileDB;
        $cgi->save();
        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $cgi = Cgi::find($id);
        $cgi->delete();

        return Response()->json(['delete' => true]);
    }
    public function cgisExport($type){
        return  Excel::download(new CgiExport(),'cgis.'.$type);
    }
}
