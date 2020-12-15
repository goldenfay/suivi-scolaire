@extends('layouts.app', ['activePage' => 'compte', 'titlePage' => __('Compte')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('compte.updateInfo') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-info">
                <h4 class="card-title">{{ __('Modifiez Vos Informations') }}</h4>
                <p class="card-category">{{ __('Informations Personnelles') }}</p>
              </div>
              <div class="card-body ">
                @if (session('flag'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert {{session('flag')=='success'?"alert-success": "alert-danger"}} w-60">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session('message') }}</span>
                      </div>
                    </div>
                </div>
                @endif
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('E-mail') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Votre E-mail') }}" value="{{ old('email', auth()->user()->Email) }}" required="true" aria-required="true"/>
                      @if ($errors->has('email'))
                        <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Adresse') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('adress') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('adress') ? ' is-invalid' : '' }}" name="adress" id="input-adress" type="text" placeholder="{{ __('Votre adresse...') }}" value="{{ old('adress', auth()->user()->Adresse) }}" required="true" aria-required="true"/>
                      @if ($errors->has('adress'))
                        <span id="adress-error" class="error text-danger" for="input-adress">{{ $errors->first('adress') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-info">{{ __('Enregistrer') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('compte.updatePassword') }}" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-info">
                <h4 class="card-title">{{ __('Informations Du compte') }}</h4>
                <p class="card-category">{{ __('Modifiez Votre Mot De Passe') }}</p>
              </div>
              <div class="card-body ">
                @if (session('flag_password'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert {{session('flag_password')=='success'?"alert-success": "alert-danger"}} w-60">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session('message_password') }}</span>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="row">
                  <label class="col-sm-2 col-form-label" for="input-current-password">{{ __('Mot de passe actuel') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" input type="password" name="old_password" id="input-current-password" placeholder="{{ __('Votres mot de passe actuel...') }}" value="" required />
                      @if ($errors->has('old_password'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('old_password') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label" for="input-password">{{ __('Nouveau mot de passe') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="input-password" type="password" placeholder="{{ __('Nouveau mot de passe...') }}" value="" required />
                      @if ($errors->has('password'))
                        <span id="password-error" class="error text-danger" for="input-password">{{ $errors->first('password') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label" for="input-password-confirmation">{{ __('Confirmez le nouveau mot de passe') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group">
                      <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Veuillez re-saisir le nouveau mot de passe') }}" value="" required />
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-info">{{ __('Modifier le mot de passe') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection