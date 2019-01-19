<?php

namespace App\Http\Controllers;

use App\Factureglobal;
use Illuminate\Http\Request;
use PDF;

class FactureglobalController extends Controller
{
    public function index(){
        $factures = Factureglobal::with('facturelivraison')->orderBy('id','desc')->paginate(10);
        return Response()->json(['factures' => $factures]);
    }

    public function store(Request $request){
        $facture = new Factureglobal();
        $facture->facturelivraison_id = $request->id;
        $facture->save();
        $facture->nglobal = 'FG'.$facture->id;
        $detail = $request->all();
        $path = 'file/'.$facture->nglobal.'.pdf';
        PDF::loadView('page.facturesglobalPDF',compact('detail','facture'))->save($path);
        $facture->file = $path;
        $facture->save();
        return Response()->json(['etat' => true]);

    }

    public function destroy($id){

        $facture = Factureglobal::find($id);
        $facture->delete();

        return Response()->json(['delete' => true]);

    }
}

