@extends('layouts.nav')

@section('content')
<div class="container">
    <div class="row  justify-content-center">
        <div class="form-login">
                <img src="images/logo.png">
                <h3 class="title-log">Se connecter</h3>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group-material">
                            <input  id="email" class="input-material form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email"  name="email" value="{{ old('email') }}" required>
                            <label for="email" class="label-material">E-mail</label>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>


                        <div class="form-group-material">
                            <input  id="password" class="input-material form-control{{  $errors->has('password') ? ' is-invalid' : '' }}"  type="password"  name="password" required>
                            <label for="password" class="label-material">Mot de passe</label>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                        </div>

                        <div class="form-group row">

                                <div class="col-md-6 i-checks">
                                    <input id="checkboxCustom1" type="checkbox" value="" class="form-control-custom">
                                    <label for="checkboxCustom1">Se souvenir de moi</label>
                                </div>
                                <div class="col-md-6 link-pas">
                                  <a class="btn btn-link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                </div>
                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-primary ">
                                    Se connecter
                                </button>
                            </div>
                        </div>
                    </form>
            </div>

    </div>
</div>
@endsection
