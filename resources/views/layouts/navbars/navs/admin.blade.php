<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#">{{ $titlePage }}</a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">

      <ul class="navbar-nav">

        <li class="nav-item dropdown">
          <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
           
            <p class="d-lg-none d-md-block">
              {{ __('Actions') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            


          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              {{ __('Compte') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Déconnexion') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>


@push('js')
<script>
 
</script>

@endpush