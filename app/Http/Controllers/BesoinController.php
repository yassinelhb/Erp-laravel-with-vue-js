<?php

namespace App\Http\Controllers;

use App\Besoin;
use App\Events\BesoinEvent;
use Illuminate\Http\Request;

class BesoinController extends Controller
{

    public function index(){
        $besoins = Besoin::with('articles','av','bordereaux')->get();
        $besoinspagination = Besoin::with('saison','av','bordereaux')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['besoins' => $besoins , 'besoinspagination' => $besoinspagination] );

    }

    public function show($search){
        $besoin = Besoin::with('saison','av','bordereaux')->where('id',$search)->first();
        $besoinspagination = Besoin::with('saison','av','bordereaux')->where('code', 'like', '%'.$search.'%')->orWhere('libelle', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['besoin' => $besoin , 'besoinspagination' => $besoinspagination]);
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|unique:besoins',
            'libelle' => 'required|unique:besoins',
            'date' => 'required|date|after:tomorrow',
            'av' => 'required',
        ]);
        $besoin = new Besoin();
        $besoin->code =  $request->code;
        $besoin->libelle =  ucwords($request->libelle);
        $besoin->date =  $request->date;
        $besoin->saison_id =  $request->saison;
        $besoin->av_id =  $request->av;
        $besoin->save();
        event(new BesoinEvent($besoin));
        return Response()->json(['etat' => true ] );
    }
    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|unique:besoins,code,$id",
            'libelle' => "required|unique:besoins,libelle,$id",
            'date' => 'required',
            'av' => 'required',

        ]);


        $besoin = Besoin::find($id);
        $besoin->code =  $request->code;
        $besoin->libelle =  ucwords($request->libelle);
        $besoin->date =  $request->date;
        $besoin->saison_id =  $request->saison;
        $besoin->av_id =  $request->av;
        $besoin->save();
        return Response()->json(['etat' => true] );

    }
    public function destroy($id){

        $besoin = Besoin::find($id);
        $besoin->delete();

        return Response()->json(['delete' => true]);

    }
}
