<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">

  <div class="logo my-5">
    <a href="https://creative-tim.com/" class="simple-text logo-normal">
      {{ __('Bonjour ') }}
    </a>
  </div>
  <div class="sidebar-wrapper mt-5">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('prof.dashboard') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Tableau De Bord') }}</p>
        </a>
      </li>
      
      <li class="nav-item{{ $activePage == 'enseignement' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('prof.enseignement') }}">
          <i class="material-icons">business_center</i>
            <p>{{ __('Enseignement') }}</p>
        </a>
      </li>
      {{-- <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('typography') }}">
          <i class="material-icons">library_books</i>
            <p>{{ __('Typography') }}</p>
        </a>
      </li> --}}
      <li class="nav-item{{ $activePage == 'reports' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('reports') }}">
          <i class="material-icons">leaderboard</i>
          <p>{{ __('Rapports') }}</p>
        </a>
      </li>
      {{-- <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('map') }}">
          <i class="material-icons">location_ons</i>
            <p>{{ __('Maps') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'language' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('language') }}">
          <i class="material-icons">language</i>
          <p>{{ __('RTL Support') }}</p>
        </a>
      </li> --}}
     
    </ul>
  </div>
</div>
