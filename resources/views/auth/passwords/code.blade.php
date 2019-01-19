@extends('layouts.nav')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="form-login">
            <img src="/images/logo.png">
            <h4 class="title-log">Entre le code de sécurité</h4>
            <form  method="POST" action="{{route('update')}}" >
                {{ csrf_field() }}
                        <div class="form-group-material">
                            <input  id="code" class="input-material form-control{{  $errors->has('code') ? ' is-invalid' : '' }}"  type="text"  name="code" required>
                            <label for="code" class="label-material">Code</label>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('code') }}</strong>
                                            </span>
                            @endif

                        </div>
                        <input type="text" name="email" value="{{ $email }}" hidden>

                        <div class="form-group">

                                <a href="/login" class="btn btn-default text-dark">
                                     Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Continuer
                                </button>
                            </div>
                    </form>
        </div>
    </div>
</div>
@endsection
