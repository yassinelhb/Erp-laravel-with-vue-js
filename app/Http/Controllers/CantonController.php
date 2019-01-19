<?php

namespace App\Http\Controllers;

use App\Canton;
use App\Exports\CantonExport;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Excel;

class CantonController extends Controller
{
    public function index(){
        $cantons = Canton::all();
        $cantonspagination = Canton::with('sousprefecture','cgis')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['cantons' => $cantons ,'cantonspagination' => $cantonspagination] );

    }

    public function show($search){
        $cantons = Canton::where('sousprefecture_id',$search)->get();
        $cantonspagination = Canton::with('sousprefecture','cgis')->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['cantons' => $cantons ,'cantonspagination' => $cantonspagination] );
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:cantons',
            'libelle' => 'required',
            'sousprefecture_id' =>  'required',
        ]);
        $canton = new Canton();
        $canton->code =  $request->code;
        $canton->libelle =  ucwords($request->libelle);
        $canton->sousprefecture_id =  $request->sousprefecture_id;

        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/canton/').$fileDB);

        }else {
            $fileDB = 'canton.png';
        }

        $canton->image = $fileDB;
        $canton->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:cantons,code,$id",
            'libelle' => "required",
            'sousprefecture_id' =>  'required',
        ]);
        $canton = Canton::find($id);
        $canton->code =  $request->code;
        $canton->libelle =  ucwords($request->libelle);
        $canton->sousprefecture_id =  $request->sousprefecture_id;

        if($request->get('image') == $canton->image){
            $fileDB =  $canton->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/canton/').$fileDB);

        }else {
            $fileDB = 'canton.png';
        }

        $canton->image = $fileDB;
        $canton->save();
        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $canton = Canton::find($id);
        $canton->delete();
        return Response()->json(['delete' => true]);

    }

    public function cantonsExport($type){
        return  Excel::download(new CantonExport(),'cantons.'.$type);
    }
}
