@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i> Gestion Des Association Villegoise </p>
        </div>
    </section>

    <section class="section-body">
        <div class="container-fluid">
            <div class="row">
               <h3 class="section-title">Consulter Les Association Villegoise</h3>

               <gestionav></gestionav>


            </div>
        </div>
    </section>



@endsection

