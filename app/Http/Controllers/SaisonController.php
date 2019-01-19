<?php

namespace App\Http\Controllers;

use App\Exports\SaisonsExport;
use App\Saison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class SaisonController extends Controller
{
    public function index(){
        $saison = Saison::with('besoins')->orderBy('id', 'desc')->get();
        $saisonspagination = Saison::orderBy('id', 'desc')->paginate(10);
        $thisaison = Saison::where('active',1)->first();
        return Response()->json(['saison' => $saison ,'saisonspagination' => $saisonspagination ,'thisaison' => $thisaison]);

    }

    public function show($search){
        $saisonspagination = Saison::where('compagne', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['saisonspagination' => $saisonspagination]);
    }

    public function store(Request $request){
        $this->validate($request , [
            'compagne' => 'required|unique:saisons',
            'datedebut' => 'required',
            'datefin' => 'required',
            'prixcoton' => 'required|min:1|numeric',
            'tvacoton' => 'required|min:1|numeric',
        ]);
        $saison = new Saison();
        $saison->compagne =  $request->compagne;
        $saison->datedebut =  $request->datedebut;
        $saison->datefin =  $request->datefin;
        $saison->commentaire =  $request->commentaire;
        $saison->prixcoton =  $request->prixcoton;
        $saison->tvacoton =  $request->tvacoton;
        $saison->save();
        return Response()->json(['etat' => true ] );
    }

    public function update(Request $request,$id){

        $this->validate($request , [
            'compagne' => "required|unique:saisons,compagne,$id",
            'datedebut' => 'required|date|after:tomorrow',
            'datefin' => 'required|date|after:tomorrow',
            'prixcoton' => 'required|min:1|numeric',
            'tvacoton' => 'required|min:1|numeric',

        ]);
        $saison = Saison::find($id);
        $saison->compagne =  $request->compagne;
        $saison->datedebut =  $request->datedebut;
        $saison->datefin =  $request->datefin;
        $saison->commentaire =  $request->commentaire;
        $saison->prixcoton =  $request->prixcoton;
        $saison->tvacoton =  $request->tvacoton;
        $saison->save();
        return Response()->json(['etat' => true] );

    }
    public function updateActive($id){
        Saison::where('active' , 1)->update(array('active' => 0));

        $saison = Saison::find($id);
        $saison->active = 1;
        $saison->save();
        return Response()->json(['etat' => true] );
    }
    public function destroy(Request $request,$id){

        $saison = Saison::find($id);
        $saison->delete();

        return Response()->json(['delete' => true]);

    }

    public function saisonsExport($type){
        return  Excel::download(new SaisonsExport(),'saisons.'.$type);
    }
}
