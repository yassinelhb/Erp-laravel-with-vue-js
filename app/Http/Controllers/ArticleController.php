<?php

namespace App\Http\Controllers;

use App\Article;
use App\Exports\ArticlesExport;
use App\Prixvente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Excel;

class ArticleController extends Controller
{
  public function index(){
        $articles = Article::all();
        return Response()->json(['articles' => $articles ]);
    }
    public function show($saison){
        $articles = Article::with(['saisons' => function($q) use ($saison){
            $q->where('saison_id', $saison);
        }])->get();
        if(Auth::user()->role == 'Cozoc') {
            $articlespagination = Article::with(['saisons' => function($q) use ($saison){
                $q->where('saison_id', $saison);
            }])->orderBy('qtedispo', 'asc')->paginate(10);
        }else{
            $articlespagination = Article::with(['saisons' => function($q) use ($saison){
                $q->where('saison_id', $saison);
            }])->orderBy('id', 'desc')->paginate(10);
        }
        return Response()->json(['articles' => $articles ,'articlespagination' => $articlespagination] );
    }

    public function searcharticle($saison ,$search){
        if(Auth::user()->role == 'Cozoc') {
            $articlespagination = Article::with(['saisons' => function($q) use ($saison){
                $q->where('saison_id', $saison);
            }])->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('qtedispo', 'asc')->paginate(10);
        }else{
            $articlespagination = Article::with(['saisons' => function($q) use ($saison){
                $q->where('saison_id', $saison);
            }])->where('libelle', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        }
        return Response()->json(['articlespagination' => $articlespagination] );
    }

    public function store(Request $request){
        $this->validate($request , [
            'code' => 'required|min:7|max:20|unique:articles',
            'libelle' => 'required|min:7|max:20|unique:articles',
            'quantite' => 'required|min:0|numeric',
            'unitevente' => 'required',
        ]);
        $article = new Article();
        $article->code =  $request->code;
        $article->libelle =  ucwords($request->libelle);
        $article->qtedispo =  $request->quantite;
        $article->unitevente =  $request->unitevente;
        if($request->get('image')){
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/product/').$fileDB);

        }else {
            $fileDB = 'product.png';
        }

        $article->image = $fileDB;
        $article->save();
        return Response()->json(['etat' => true ] );
    }


    public function update(Request $request,$id){

        $this->validate($request , [
            'code' => "required|min:7|max:20|unique:articles,code,$id",
            'libelle' => "required|min:7|max:20|unique:articles,libelle,$id",
            'quantite' => 'required|min:0|numeric',
            'unitevente' => 'required',
            'prixvente' => 'required|min:0|numeric',
            'tva' => 'required|min:0|numeric',

        ]);


        $article = Article::find($id);
        $article->code =  $request->code;
        $article->libelle =  ucwords($request->libelle);
        $article->qtedispo =  $request->quantite;
        $article->unitevente =  $request->unitevente;

        if($request->get('image') == $article->image){
            $fileDB =  $article->image ;
        }
        else if($request->get('image')) {
            $file = $request->get('image');
            $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                    strpos($file, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/product/').$fileDB);

        }else {
            $fileDB = 'product.png';
        }

        $article->image = $fileDB;
        $article->save();
        if ($request->articlesaisonid != "") {
            $article_saison = Prixvente::find($request->articlesaisonid);
            $article_saison->prixvente = $request->prixvente;
            $article_saison->tva = $request->tva;
        }else {
            $article_saison = new Prixvente();
            $article_saison->prixvente = $request->prixvente;
            $article_saison->tva = $request->tva;
            $article_saison->article_id = $article->id;
            $article_saison->saison_id = $request->saison;
        }

        $article_saison->save();

        return Response()->json(['etat' => true] );

    }

    public function destroy($id){

        $article = Article::find($id);
        $article->delete();

        return Response()->json(['delete' => true]);

    }
    public function articlesExport($type){
        return  Excel::download(new ArticlesExport(),'articles.'.$type);
    }



}
