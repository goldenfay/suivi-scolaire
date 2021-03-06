<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
    <div class="container">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="{{ url('/') }}">Gestion Scolarite</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      @if(Request::url() != route('loginProf') && Request::url() != route('registerProf'))

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item{{ $activePage == 'register' ? ' active' : '' }}">
            <a href="{{ url('register') }}" class="nav-link">
              <i class="material-icons">person_add</i> {{ __('Inscription') }}
            </a>
          </li>
          <li class="nav-item{{ $activePage == 'login' ? ' active' : '' }}">
            <a href="{{ url('login') }}" class="nav-link">
              <i class="material-icons">fingerprint</i> {{ __('Authentification') }}
            </a>
          </li>
         
        </ul>
      </div>
      @endif
    </div>
  </nav>
  <!-- End Navbar -->