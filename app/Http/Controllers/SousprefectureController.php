<?php

namespace App\Http\Controllers;

use App\Exports\SousPrefectureExport;
use App\Sousprefecture;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Excel;

class SousprefectureController extends Controller
{
    public function index(){
        $sousprefectures = Sousprefecture::all();
        $sousprefecturespagination = Sousprefecture::with('prefecture','cantons')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['sousprefecture' => $sousprefectures ,'sousprefecturespagination' => $sousprefecturespagination] );

    }

    public function show($search){
        $sousprefecture = Sousprefecture::where('prefecture_id',$search)->get();
        $sousprefecturespagination = Sousprefecture::with('prefecture','cantons')->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['sousprefecture' => $sousprefecture ,'sousprefecturespagination' => $sousprefecturespagination] );
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:sousprefectures',
            'libelle' => 'required',
            'prefecture_id' =>  'required',
        ]);
        $sousprefecture = new Sousprefecture();
        $sousprefecture->code =  $request->code;
        $sousprefecture->libelle =  ucwords($request->libelle);
        $sousprefecture->prefecture_id = $request->prefecture_id ;

        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/sousprefecture/').$fileDB);

        }else {
            $fileDB = 'sousprefecture.png';
        }

        $sousprefecture->image = $fileDB;
        $sousprefecture->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:sousprefectures,code,$id",
            'libelle' => "required",
            'prefecture_id' =>  'required',

        ]);
        $sousprefecture = Sousprefecture::find($id);
        $sousprefecture->code =  $request->code;
        $sousprefecture->libelle =  ucwords($request->libelle);
        $sousprefecture->prefecture_id =  $request->prefecture_id;

        if($request->get('image') == $sousprefecture->image){
            $fileDB =  $sousprefecture->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/sousprefecture/').$fileDB);

        }else {
            $fileDB = 'sousprefecture.png';
        }

        $sousprefecture->image = $fileDB;
        $sousprefecture->save();
        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $sousprefecture = Sousprefecture::find($id);
        $sousprefecture->delete();

        return Response()->json(['delete' => true]);

    }
    public function sousprefecturesExport($type){
        return  Excel::download(new SousPrefectureExport(),'sousprefectures.'.$type);
    }

}
