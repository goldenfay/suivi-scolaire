@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Paramètres')])
<?php
$email_prefs = explode(',', $events_prefs->events_via_email ?? '');
$sms_prefs = explode(',', $events_prefs->events_via_sms ?? '');
?>
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-end">
                <a class="nav-link" href="{{ route('admin.sysConfig') }}">
                    <p>{{ __('Configuration du système') }} <i class="material-icons">arrow_up_right_from_square</i></p>

                </a>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('settings.updateNotifPref') }}" autocomplete="off"
                        class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="card ">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">{{ __('Configuration des notifications') }}</h4>
                                <p class="card-category">
                                    {{ __("Sélectionnez les types d'évènements pour les notifications") }}</p>
                            </div>
                            <div class="card-body ">
                                @if (session('flag-events'))
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div
                                                class="alert {{ session('flag-events') == 'success' ? 'alert-success' : 'alert-danger' }} w-60">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <span>{{ session('message-events') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <label
                                        class="col-sm-12 col-form-label font-weight-bold">{{ __('Notification par E-mail') }}</label>
                                    <div class="col-sm-7">
                                        @foreach ($types as $key => $type)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="email-{{ $type }}"
                                                        {{ in_array($type, $email_prefs) ? 'checked' : '' }}>
                                                    {{ $type }}
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <label
                                        class="col-sm-12 col-form-label font-weight-bold">{{ __('Notification par SMS') }}</label>
                                    <div class="col-sm-7">
                                        @foreach ($types as $key => $type)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="sms-{{ $type }}"
                                                        {{ in_array($type, $sms_prefs) ? 'checked' : '' }}>
                                                    {{ $type }}
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('admin.updatePassword') }}" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="card ">
                            <div class="card-header card-header-warning">
                                <h4 class="card-title">{{ __('Informations Du compte') }}</h4>
                                <p class="card-category">{{ __('Modifiez Votre Mot De Passe') }}</p>
                            </div>
                            <div class="card-body ">
                                @if (session('flag-password'))
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div
                                                class="alert {{ session('flag-password') == 'success' ? 'alert-success' : 'alert-danger' }} w-60">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <span>{{ session('message-password') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <label class="col-sm-2 col-form-label"
                                        for="input-current-password">{{ __('Mot de passe actuel') }}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                            <input
                                                class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                                input type="password" name="old_password" id="input-current-password"
                                                placeholder="{{ __('Votres mot de passe actuel...') }}" value=""
                                                required />
                                            @if ($errors->has('old_password'))
                                                <span id="name-error" class="error text-danger"
                                                    for="input-name">{{ $errors->first('old_password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label"
                                        for="input-password">{{ __('Nouveau mot de passe') }}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                name="password" id="input-password" type="password"
                                                placeholder="{{ __('Nouveau mot de passe...') }}" value=""
                                                required />
                                            @if ($errors->has('password'))
                                                <span id="password-error" class="error text-danger"
                                                    for="input-password">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label"
                                        for="input-password-confirmation">{{ __('Confirmez le nouveau mot de passe') }}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <input class="form-control" name="password_confirmation"
                                                id="input-password-confirmation" type="password"
                                                placeholder="{{ __('Veuillez re-saisir le nouveau mot de passe') }}"
                                                value="" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit"
                                    class="btn btn-warning">{{ __('Modifier le mot de passe') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
