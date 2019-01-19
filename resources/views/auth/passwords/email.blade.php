@extends('layouts.nav')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="form-login">

                   <img src="/images/logo.png">
                    <h3 class="title-log">Réinitialiser le mot de passe</h3>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group-material">
                            <input  id="email"  class="input-material form-control{{  $errors->has('email') ? ' is-invalid' : '' }}"  type="email"  name="email" value="{{ old('email') }}" required >
                            <label for="email" class="label-material">E-mail</label>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif

                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Continuer
                                </button>
                        </div>
                    </form>
            </div>
        </div>
</div>
@endsection
