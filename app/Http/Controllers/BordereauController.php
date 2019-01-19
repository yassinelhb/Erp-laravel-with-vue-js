<?php

namespace App\Http\Controllers;

use App\Article;
use App\Besoin;
use App\Bordereaulivraison;
use App\Detailbesoin;
use App\Mail\Bordereau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class BordereauController extends Controller
{
    public function index(){
        $bordereaux = Bordereaulivraison::with('besoin','factures')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['bordereaux' => $bordereaux]);
    }

    public function show($search){
        $bordereaux = Bordereaulivraison::with('besoin')->where('nbordereau', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['bordereaux' => $bordereaux]);
    }

    public function details($saison,$besoin){
     $details = Detailbesoin::with(['article' => function($q) use ($saison) {
         $q->with(['saisons' => function ($q) use($saison){
             $q->where('saison_id',$saison);}]);
     }])->where('besoin_id',$besoin)->get();
    return Response()->json(['details' => $details]);
    }

    public function store(Request $request){
        $this->validate($request , [
            'datePaiement' => 'required|date|after:tomorrow',
            'besoin' => 'required'
        ]);

        $bordereau = new Bordereaulivraison();
        $bordereau->modaliteP = $request->modaliteP;
        $bordereau->datePaiement = $request->datePaiement;
        $bordereau->observation = $request->observation;
        $bordereau->besoin_id = $request->besoin['id'];
        $bordereau->total = $request->total;
        $bordereau->save();
        $bordereau->nbordereau = 'BL'.$bordereau->id;
        $path = 'file/'.$bordereau->nbordereau.'.pdf';
        $details = $request->detailbesoin;
        PDF::loadView('page.bordereauPDF',compact('bordereau','details'))->save($path);
        $bordereau->file = $path;
        $bordereau->save();
        Mail::to($request->besoin['av']['email'])->send(new Bordereau($path));

        return Response()->json(['etat' => true]);

    }
    public function update(Request $request,$id){
        $this->validate($request , [
            'datePaiement' => 'required|date|after:tomorrow',
            'besoin' => 'required'
        ]);

        $bordereau =  Bordereaulivraison::find($id);
        $bordereau->modaliteP = $request->modaliteP;
        $bordereau->datePaiement = $request->datePaiement;
        $bordereau->observation = $request->observation;
        $bordereau->besoin_id = $request->besoin['id'];
        $bordereau->total = $request->total;
        $bordereau->save();

         $path = 'file/'.$bordereau->nbordereau.'.pdf';
         $details = $request->detailbesoin;
         PDF::loadView('page.bordereauPDF',compact('bordereau','details'))->save($path);
         $bordereau->file = $path;
         $bordereau->save();
         Mail::to($request->besoin['av']['email'])->send(new Bordereau($path));
        return Response()->json(['etat' => true]);

    }


}
