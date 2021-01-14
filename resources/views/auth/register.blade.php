@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'register', 'title' => __('Material Dashboard')])

@section('content')
<div class="container" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      @isset($url)
      <form method="POST" action='{{ url("register/$url") }}' aria-label="{{ __('Register') }}">
      @else
      <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
      @endisset
      {{-- <form class="form" method="POST" action="{{ route('register') }}"> --}}
        @csrf

        <div class="card card-login card-hidden mb-3">
          <div class="card-header card-header-primary text-center">
            @isset($url)
            <h4 class="card-title"><strong>{{ __('Inscription '.($url=='parent'?'':' Enseignant')) }}</strong></h4>
            @else
            <h4 class="card-title"><strong>{{ __('Inscription') }}</strong></h4>
            @endisset
            
          </div>
          <div class="card-body ">
            
            <div class="bmd-form-group{{ $errors->has('nom') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                      <i class="material-icons">face</i>
                  </span>
                </div>
                <input type="text" name="nom" class="form-control" placeholder="{{ __('Nom...') }}" value="{{ old('nom') }}" required>
              </div>
              @if ($errors->has('nom'))
                <div id="nom-error" class="error text-danger pl-3" for="nom" style="display: block;">
                  <strong>{{ $errors->first('nom') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('prenom') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                      <i class="material-icons">face</i>
                  </span>
                </div>
                <input type="text" name="prenom" class="form-control" placeholder="{{ __('Prenom...') }}" value="{{ old('prenom') }}" required>
              </div>
              @if ($errors->has('prenom'))
                <div id="prenom-error" class="error text-danger pl-3" for="prenom" style="display: block;">
                  <strong>{{ $errors->first('nom') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('Email') ? ' has-danger' : '' }} mt-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">email</i>
                  </span>
                </div>
                <input type="email" name="Email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('email') }}" required>
              </div>
              @if ($errors->has('Email'))
                <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                  <strong>{{ $errors->first('Email') }}</strong>
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
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Mot de passe...') }}" required>
              </div>
              @if ($errors->has('password'))
                <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                  <strong>{{ $errors->first('password') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">lock_outline</i>
                  </span>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Retappez le mot de passe...') }}" required>
              </div>
              @if ($errors->has('password_confirmation'))
                <div id="password_confirmation-error" class="error text-danger pl-3" for="password_confirmation" style="display: block;">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('numTel') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                      <i class="material-icons">call</i>
                  </span>
                </div>
                <input type="tel" name="numTel" class="form-control" placeholder="{{ __('Numéro de téléphone...') }}" value="{{ old('numTel') }}" required>
              </div>
              @if ($errors->has('numTel'))
                <div id="numTel-error" class="error text-danger pl-3" for="numTel" style="display: block;">
                  <strong>{{ $errors->first('numTel') }}</strong>
                </div>
              @endif
            </div>


           
          </div>
          <div class="card-footer justify-content-center">
            <button type="submit" class="btn btn-primary btn-link btn-lg">{{ __('Créer un compte') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
