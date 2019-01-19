<?php

namespace App\Http\Controllers;

use App\Bordereaureglement;
use Illuminate\Http\Request;
use PDF;

class BordereaureglementController extends Controller
{
    public function index(){
        $bordereaux = Bordereaureglement::with('facturelivraison')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['bordereaux' => $bordereaux]);
    }

    public function show($search){
        $bordereau = Bordereaureglement::with('facturelivraison')->where('facturelivraison_id',$search)->orderBy('id', 'desc')->first();
        $bordereaux = Bordereaureglement::with('facturelivraison')
            ->whereHas('facturelivraison', function ($q) use($search){
                $q->where('nfacture','like', '%'.$search.'%');})
            ->orWhere('reglement', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['bordereau' => $bordereau,'bordereaux' => $bordereaux]);
    }

    public function store(Request $request){
        $this->validate($request , [
            'datereglement' => 'required|date|after:tomorrow',
            'montantP' => 'required|min:10|numeric',
        ]);

        $bordereau = new Bordereaureglement();
        $bordereau->montantreglement = $request->montantreglement;
        $bordereau->montantP = $request->montantP;
        $bordereau->modaliteP = $request->modaliteP;
        $bordereau->datereglement = $request->datereglement;
        $bordereau->facturelivraison_id = $request->facture['id'];
        $bordereau->save();
        $bordereau->reglement = 'BR'.$bordereau->id;
        $path = 'file/'.$bordereau->reglement.'.pdf';
        $facture = $request->facture;
        PDF::loadView('page.reglementPDF',compact('facture','bordereau'))->save($path);
        $bordereau->file = $path;
        $bordereau->save();
        return Response()->json(['etat' => true]);

    }
    public function update(Request $request,$id){
        $this->validate($request , [
            'datereglement' => 'required|date|after:tomorrow',
            'montantP' => 'required|min:10|numeric',
        ]);

        $bordereau =  Bordereaureglement::find($id);
        $bordereau->montantP = $request->montantP;
        $bordereau->modaliteP = $request->modaliteP;
        $bordereau->datereglement = $request->datereglement;
        $path = 'file/'.$bordereau->reglement.'.pdf';
        $facture = $request->facture;
        PDF::loadView('page.reglementPDF',compact('facture','bordereau'))->save($path);
        $bordereau->file = $path;

        $bordereau->save();
        return Response()->json(['etat' => true]);

    }


    public function destroy(Request $request,$id){

        $bordereau = Bordereaureglement::find($id);
        $bordereau->delete();

        return Response()->json(['delete' => true]);

    }
}
