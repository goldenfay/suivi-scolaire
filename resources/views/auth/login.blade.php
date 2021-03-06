@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'login', 'title' => __('Authentification')])

@section('content')
<div class="container" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-md-9 ml-auto mr-auto mb-3 text-center">
    </div>
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      @isset($url)
      <form method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
      @else
      <form method="POST" action="{{ url('login') }}" aria-label="{{ __('Login') }}">
      @endisset
        @csrf
        
        <div class="card card-login card-hidden mb-3">
          <div class="card-header card-header-primary text-center">
            @isset($url)
            <h4 class="card-title"><strong>{{ __('Authentification '.($url=='parent'?'':' Enseignant')) }}</strong></h4>
            @else
            <h4 class="card-title"><strong>{{ __('Authentification') }}</strong></h4>
            @endisset
            
          </div>
          <div class="card-body">
            <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">email</i>
                  </span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}"  required>
              </div>
              @if ($errors->has('email'))
                <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                  <strong>{{ $errors->first('email') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">lock_outline</i>
                  </span>
                </div>
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Mot de passe...') }}"  required>
              </div>
              @if ($errors->has('password'))
                <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                  <strong>{{ $errors->first('password') }}</strong>
                </div>
              @endif
            </div>
            <div class="form-check mr-auto ml-3 mt-3">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Garder ma session') }}
                <span class="form-check-sign">
                  <span class="check"></span>
                </span>
              </label>
            </div>
          </div>
          @if ($errors->has('credentials'))
            <div  class="error text-danger d-flex flex-row justify-content-center">
              <strong>{{ $errors->first('credentials') }}</strong>
            </div>
          @endif
          <div class="card-footer justify-content-center">
            <button type="submit" class="btn btn-primary btn-link btn-lg">{{ __('Connexion') }}</button>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-6">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-light">
                    <small>{{ __('Mot de passe oublié ?') }}</small>
                </a>
            @endif
        </div>
        <div class="col-6 text-right">
          @isset($url)
          <a href="{{ url('register/'.$url) }}" class="text-light">
            <small>{{ __('S\'enregistrer') }}</small>
        </a>
          @else
          <a href="{{ url('register') }}" class="text-light">
            <small>{{ __('S\'enregistrer') }}</small>
        </a>
          @endisset  
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
