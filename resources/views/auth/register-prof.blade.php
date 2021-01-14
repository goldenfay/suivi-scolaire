@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'register', 'title' => __('Material Dashboard')])

@section('content')
@push('styles')
<style>
body{
  background-image: url('{{ asset('material') }}/img/classe.jpg'); 
  background-size: cover; 
  background-position: top center;
  align-items: center;
}
</style>
@endpush
<div class="container" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-sm-12">
      <div class="d-flex flex-row justify-content-center">
        @if(session('flag'))
              @if(session('flag')=='fail')
              <div class="col-md-4">
                <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
                  <i class="material-icons" data-notify="icon">error</i>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>Une erreur s'est produite. Impossible de transmettre votre demande.</span>
                </div>
              </div>
              @else
              @if(session('flag')=='success')
              <div class="col-md-4">
                <div class="alert alert-success alert-with-icon w-60" data-notify="container">
                  <i class="material-icons" data-notify="icon">check_circle</i>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>Inscription réussie! Votre demande est en cours de validation. Vous receverez un email de validation dès son approuvement.</span>
                </div>
              </div>
              @endif

              @endif

              @endif
        
      </div>
      
    </div>
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      
      <form method="POST" action="{{ route('registerProf') }}" aria-label="{{ __('Register') }}">
     
      {{-- <form class="form" method="POST" action="{{ route('register') }}"> --}}
        @csrf

        <div class="card card-login card-hidden mb-3">
          <div class="card-header card-header-primary text-center">
            <h4 class="card-title"><strong>{{ __('Inscription Enseignant') }}</strong></h4>
           
            
          </div>
          <div class="card-body ">
            
            <div class="bmd-form-group{{ $errors->has('nom') ? ' has-danger' : '' }}">
              <div class="input-group">
                
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
                
                <input type="email" name="Email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('Email') }}" required>
              </div>
              @if ($errors->has('Email'))
              <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                <strong>{{ $errors->first('Email') }}</strong>
              </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('adresse') ? ' has-danger' : '' }}">
              <div class="input-group">
                
                <input type="text" name="adresse" class="form-control" placeholder="{{ __('Adresse...') }}" value="{{ old('adresse') }}" required>
              </div>
              @if ($errors->has('adresse'))
                <div id="adresse-error" class="error text-danger pl-3" for="prenom" style="display: block;">
                  <strong>{{ $errors->first('nom') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('age') ? ' has-danger' : '' }}">
              <div class="input-group">
                
                <input type="text" name="age" class="form-control" placeholder="{{ __('Age...') }}" value="{{ old('age') }}" required>
              </div>
              @if ($errors->has('age'))
                <div id="age-error" class="error text-danger pl-3" for="prenom" style="display: block;">
                  <strong>{{ $errors->first('nom') }}</strong>
                </div>
              @endif
            </div>
            {{-- <div class="bmd-form-group{{ $errors->has('numTel') ? ' has-danger' : '' }}">
              <div class="input-group">
                
                <input type="tel" name="numTel" class="form-control" placeholder="{{ __('Numéro de téléphone...') }}" value="{{ old('numTel') }}" required>
              </div>
              @if ($errors->has('numTel'))
                <div id="numTel-error" class="error text-danger pl-3" for="numTel" style="display: block;">
                  <strong>{{ $errors->first('numTel') }}</strong>
                </div>
              @endif
            </div> --}}
            <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
              <div class="input-group">
                
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Mot de passe...') }}" required>
              </div>
              @if ($errors->has('password'))
                <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                  <strong>{{ $errors->first('password') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
              <div class="input-group">
                
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Retappez le mot de passe...') }}" required>
              </div>
              @if ($errors->has('password_confirmation'))
                <div id="password_confirmation-error" class="error text-danger pl-3" for="password_confirmation" style="display: block;">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
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
