@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i> Gestion Les Saisons </p>
        </div>
    </section>

    <section class="section-body">
        <div class="container-fluid">
            <div class="row">
               <h3 class="section-title">Consulter Les Saisons</h3>

               <gestionsaison></gestionsaison>


            </div>
        </div>
    </section>



@endsection

