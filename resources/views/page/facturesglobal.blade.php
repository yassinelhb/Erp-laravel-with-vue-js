@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i> Consulter Les Factures Global</p>
        </div>
    </section>

    <section class="section-body">
        <div class="container-fluid">
            <div class="row">
                <h3 class="section-title">Consulter Les Factures Global</h3>
                <facturesglobal></facturesglobal>
            </div>
        </div>
    </section>



@endsection

