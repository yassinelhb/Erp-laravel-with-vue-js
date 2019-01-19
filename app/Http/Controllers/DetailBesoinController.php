<?php

namespace App\Http\Controllers;

use App\Besoin;
use App\Detailbesoin;
use Illuminate\Http\Request;

class DetailBesoinController extends Controller
{
    public function show($besoin){
        $detailbesoin = Detailbesoin::with('article','besoin')->where('besoin_id',$besoin)->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['detailbesoin' => $detailbesoin]);
    }
    public function search($besoin,$search){
        $detailbesoin = Detailbesoin::with('article','besoin')->where('besoin_id',$besoin)
            ->whereHas('article', function ($q) use($search){
                $q->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%');})
            ->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['detailbesoin' => $detailbesoin]);
    }
    public function store(Request $request){
        $this->validate($request , [
            'article' => 'required',
            'quantite' => 'required|min:1|numeric',
        ]);

        $detailbesoin = new Detailbesoin();
        $detailbesoin->quantite = $request->quantite;
        $detailbesoin->besoin_id = $request->besoin;
        $detailbesoin->article_id = $request->article;
        $detailbesoin->save();
        return Response()->json(['etat' => true]);
    }
    public function update(Request $request,$id){

        $this->validate($request , [
            'article' => 'required',
            'quantite' => 'required|min:1|numeric',

        ]);

        $detailbesoin = Detailbesoin::find($id);

        $detailbesoin->quantite = $request->quantite;
        $detailbesoin->besoin_id = $request->besoin;
        $detailbesoin->article_id = $request->article;
        $detailbesoin->save();
        return Response()->json(['etat' => true]);

    }
    public function destroy(Request $request,$id){

        $detailbesoin = Detailbesoin::find($id);
        $detailbesoin->delete();

        return Response()->json(['delete' => true]);

    }
}
