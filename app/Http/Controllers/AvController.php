<?php

namespace App\Http\Controllers;

use App\Av;
use App\Exports\AvExport;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Excel;

class AvController extends Controller
{
    public function index(){
        $avs = Av::with('besoins')->get();
        $avspagination = Av::with('cgi')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['avs' => $avs ,'avspagination' => $avspagination] );

    }

    public function show($search){
        $avs = Av::where('cgi_id',$search)->get();
        $avspagination = Av::with('cgi')->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['avs' => $avs ,'avspagination' => $avspagination] );
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:avs',
            'libelle' => 'required',
            'representant' => 'required',
            'adresse' => 'required',
            'email' => 'required|email',
            'numfix' => 'required|digits_between:8,12|numeric',
            'mobile' => 'required|digits_between:8,12|numeric',
            'cgi_id' =>  'required',
        ]);
        $av = new Av();
        $av->code =  $request->code;
        $av->libelle =  ucwords($request->libelle);
        $av->representant =  ucwords($request->representant);
        $av->adresse =  ucwords($request->adresse);
        $av->email =  $request->email;
        $av->numfix =  $request->numfix;
        $av->mobile =  $request->mobile;
        $av->fax =  $request->fax;
        $av->cgi_id =  $request->cgi_id;

        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/av/').$fileDB);

        }else {
            $fileDB = 'av.jpg';
        }

        $av->image = $fileDB;
        $av->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:avs,code,$id",
            'libelle' => 'required',
            'representant' => 'required',
            'adresse' => 'required',
            'email' => 'required|email',
            'numfix' => 'required|digits_between:8,12|numeric',
            'mobile' => 'required|digits_between:8,12|numeric',
            'cgi_id' =>  'required',
        ]);
        $av = Av::find($id);
        $av->code =  $request->code;
        $av->libelle =  ucwords($request->libelle);
        $av->representant =  ucwords($request->representant);
        $av->adresse =  ucwords($request->adresse);
        $av->email =  $request->email;
        $av->numfix =  $request->numfix;
        $av->mobile =  $request->mobile;
        $av->fax =  $request->fax;
        $av->cgi_id =  $request->cgi_id;

        if($request->get('image') == $av->image){
            $fileDB =  $av->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/localisation/av/').$fileDB);

        }else {
            $fileDB = 'av.jpg';
        }

        $av->image = $fileDB;
        $av->save();
        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $av = Av::find($id);
        $av->delete();

        return Response()->json(['delete' => true]);

    }
    public function AvsExport($type){
        return  Excel::download(new AvExport(),'association.'.$type);
    }
}
