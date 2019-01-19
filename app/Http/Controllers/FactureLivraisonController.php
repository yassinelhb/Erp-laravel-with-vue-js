<?php

namespace App\Http\Controllers;

use App\Detailbesoin;
use App\Facturelivraison;
use App\Mail\Bordereau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class FactureLivraisonController extends Controller
{
    public function index()
    {
        $factures = Facturelivraison::with('bordereaulivraison','factureglobal')->orderBy('id', 'desc')->get();
        $facturespagination = Facturelivraison::with('bordereaulivraison','factureglobal','reglements')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['factures' => $factures ,'facturespagination' => $facturespagination]);
    }

    public function show($search){
        $facturespagination = Facturelivraison::with('bordereaulivraison','factureglobal','reglements')
            ->whereHas('bordereaulivraison', function ($q) use($search){
                $q->where('nbordereau','like', '%'.$search.'%');})
            ->orWhere('nfacture', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['facturespagination' => $facturespagination]);

    }

    public function store(Request $request){
        $saison = $request->besoin['saison_id'];

        $details = Detailbesoin::with(['article' => function($q) use ($saison) {
            $q->with(['saisons' => function ($q) use($saison){
                $q->where('saison_id',$saison);}]);
        }])->where('besoin_id',$request->besoin['id'])->get();

        $facture = new Facturelivraison();
        $facture->bordereaulivraison_id = $request->id;
        $facture->save();
        $facture->nfacture = 'FL'.$facture->id;
        $bordereau = $request->all();
        $path = 'file/'.$facture->nfacture.'.pdf';
        PDF::loadView('page.facturePDF',compact('facture','bordereau','details'))->save($path);
        $facture->file = $path;
        $facture->save();
        Mail::to($request->besoin['av']['email'])->send(new Bordereau($path));
        return Response()->json(['etat' => true]);
    }
}
