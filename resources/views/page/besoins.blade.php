@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i>
                @if(Auth::user()->role == "Cozoc")
                    Gestion Des Besoins
                @else
                    Consulter Des Besoins
                @endif
            </p>
        </div>
    </section>

    <section class="section-body">
          <gestionbesoin :user="{{Auth::user()}}"></gestionbesoin>
    </section>



@endsection

