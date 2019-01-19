@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i> Gestion Sousprefectures </p>
        </div>
    </section>

    <section class="section-body">
        <div class="container-fluid">
            <div class="row">
               <h3 class="section-title">Consulter Les Sousprefectures</h3>

               <gestionsousprefectures></gestionsousprefectures>


            </div>
        </div>
    </section>



@endsection

