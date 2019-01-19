@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i>
                @if(Auth::user()->role == "Administrateur")
                    Gestion Des Articles
                @else
                    Consulter Les Articles
                @endif
            </p>
        </div>
    </section>


    <section class="section-body">
        <div class="container-fluid">
            <div class="row">
               <h3 class="section-title">Consulter Les Articles</h3>

               <gestionarticle :user="{{Auth::user()}}"></gestionarticle>


            </div>
        </div>
    </section>



@endsection

