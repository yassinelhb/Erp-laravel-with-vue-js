<?php

namespace App\Http\Controllers;

use App\Exports\PrefectureExport;
use App\Prefecture;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Excel;

class PrefectureController extends Controller
{
    public function index(){
        $prefecture = Prefecture::all();
        $prefecturepagination = Prefecture::with('sousprefectures')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['prefecture' => $prefecture ,'prefecturepagination' => $prefecturepagination] );

    }

    public function show($search){
        $prefecture = Prefecture::find($search);
        $prefecturepagination = Prefecture::with('sousprefectures')->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['prefecture' => $prefecture ,'prefecturepagination' => $prefecturepagination]);
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:prefectures',
            'libelle' => 'required|unique:prefectures',
        ]);
        $prefecture = new Prefecture();
        $prefecture->code =  $request->code;
        $prefecture->libelle =  ucwords($request->libelle);
        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/prefecture/').$fileDB);

        }else {
            $fileDB = 'prefecture.png';
        }

        $prefecture->image = $fileDB;
        $prefecture->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:prefectures,code,$id",
            'libelle' => "required|unique:prefectures,libelle,$id",

        ]);
        $prefecture = Prefecture::find($id);
        $prefecture->code =  $request->code;
        $prefecture->libelle =  ucwords($request->libelle);

        if($request->get('image') == $prefecture->image){
            $fileDB =  $prefecture->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/prefecture/').$fileDB);

        }else {
            $fileDB = 'prefecture.png';
        }

        $prefecture->image = $fileDB;
        $prefecture->save();
        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $prefecture = Prefecture::find($id);
        $prefecture->delete();

        return Response()->json(['delete' => true]);

    }

    public function prefecturesExport($type){
        return  Excel::download(new PrefectureExport(),'prefectures.'.$type);
    }
}
