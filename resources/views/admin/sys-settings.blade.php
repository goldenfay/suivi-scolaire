@extends('layouts.app', ['activePage' => 'sys-settings', 'titlePage' => __('Configurations')])

@section('content')
<div class="content">
  <div class="container-fluid">
    @if (session('access-granted'))
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="{{ route('settings.updateSMSInfos') }}" autocomplete="off" class="form-horizontal">
          @csrf
          @method('put')

          <div class="card ">
            <div class="card-header card-header-info">
              <h4 class="card-title">{{ __('Informations de messagerie par SMS (ICOSNET)') }}</h4>
              <p class="card-category">{{ __('Soyez vigilants à props de ces informations sensibles') }}</p>
            </div>
            <div class="card-body ">
              @if (session('sms-flag'))
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert {{session('sms-flag')=='success'?"alert-success": "alert-danger"}} w-60">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                    <span>{{ session('sms-message') }}</span>
                  </div>
                </div>
              </div>
              @endif
              @if (session('sms-test-flag'))
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert {{session('sms-test-flag')=='success'?"alert-success": "alert-danger"}} w-60">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                    <span>{{ session('sms-test-message') }}</span>
                  </div>
                </div>
              </div>
              @endif
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __("SenderId") }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('sms-senderId') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('sms-senderId') ? ' is-invalid' : '' }}" name="sms-senderId"
                      id="input-sms-senderId" type="text" placeholder="{{ __('SenderId ') }}"
                      value="{{ old('sms-senderId', $sms_settings->senderId??'') }}" required="true" aria-required="true" />
                    @if ($errors->has('sms-senderId'))
                    <span id="sms-senderId-error" class="error text-danger"
                      for="input-sms-senderId">{{ $errors->first('sms-senderId') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row align-items-center">
                <label class="col-sm-2 col-form-label">{{ __('Adress Hôte') }}</label>
                <div class="col-sm-5">
                  <div class="form-group{{ $errors->has('sms-host') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('sms-host') ? ' is-invalid' : '' }}" name="sms-host"
                      id="input-sms-host" type="text" placeholder="{{ __('Adress Hôte') }}"
                      value="{{ old('sms-host', $sms_settings->host??'') }}" required="true" aria-required="true" />
                    @if ($errors->has('sms-host'))
                    <span id="sms-host-error" class="error text-danger"
                      for="input-sms-host">{{ $errors->first('sms-host') }}</span>
                    @endif
                  </div>
                </div>
                <label class="col-sm-1 col-form-label">{{ __('Port') }}</label>
                <div class="col-sm-2">
                  <div class="form-group{{ $errors->has('sms-port') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('sms-port') ? ' is-invalid' : '' }}" name="sms-port"
                      id="sms-port" type="text" placeholder="{{ __('Port') }}"
                      value="{{ old('sms-port', $sms_settings->port??'') }}" required="true" aria-required="true" />
                    @if ($errors->has('sms-port'))
                    <span id="sms-port-error" class="error text-danger"
                      for="sms-port">{{ $errors->first('sms-port') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row align-items-center">
                <label class="col-sm-2 col-form-label">{{ __('Username') }}</label>
                <div class="col-sm-4">
                  <div class="form-group{{ $errors->has('sms-username') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('sms-username') ? ' is-invalid' : '' }}" name="sms-username"
                      id="input-sms-username" type="text" placeholder="{{ __('Nom d\'utilisateur du compte ') }}"
                      value="{{ old('sms-username', $sms_settings->username??'') }}" required="true" aria-required="true" />
                    @if ($errors->has('sms-username'))
                    <span id="sms-username-error" class="error text-danger"
                      for="input-sms-username">{{ $errors->first('sms-username') }}</span>
                    @endif
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">{{ __('Mot de passe') }}</label>
                <div class="col-sm-4">
                  <div class="form-group{{ $errors->has('sms-password') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('sms-password') ? ' is-invalid' : '' }}" name="sms-password"
                      id="input-sms-password" type="password" placeholder="{{ __('Mot de passe') }}"
                      value="{{ old('sms-password', $sms_settings->password??'') }}" required="true" aria-required="true" />
                    @if ($errors->has('sms-password'))
                    <span id="sms-password-error" class="error text-danger"
                      for="input-sms-password">{{ $errors->first('sms-password') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row justify-content-center align-items-center">
                <div class="col-sm-3">
                    <div class="form-check">
                      <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" name="test" >
                          Tester la configuration
                          <span class="form-check-sign">
                              <span class="check"></span>
                          </span>
                      </label>
                    </div>
                      
                </div>
                <div class="col-sm-8 row align-items-center">
                  <label class="col-sm-5 col-form-label">{{ __('Numéro de téléphone de Test') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('test-phone') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('test-phone') ? ' is-invalid' : '' }}" name="test-phone"
                        id="input-test-phone" type="tel" placeholder="{{ __('Exemple: 0555101010') }}"
                        value="{{ old('test-phone', null) }}"  aria-required="true" />
                      @if ($errors->has('test-phone'))
                      <span id="test-phone-error" class="error text-danger"
                        for="input-test-phone">{{ $errors->first('test-phone') }}</span>
                      @endif
                    </div>
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
    @else
    <div class="row justify-content-center">
      <div class="col-md-12 text-center">
        <div>
          <i class="material-icon h2">lock</i>

        </div>
        <div>
          <h3>Page protégée!</h3>
        </div>
      </div>
    </div>
   
    @endif
  </div>
</div>
@endsection