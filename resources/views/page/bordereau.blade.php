@extends('layouts.nav')


@section('content')
    <section class="row title-page">
        <div class="col-sm-12">
            <p><a href="/">Accueil</a><i class="fa fa-angle-right"></i> Gestion Des Bordereaux </p>
        </div>
    </section>

    <section class="section-body">
          <gestionbordereau></gestionbordereau>
    </section>



@endsection

