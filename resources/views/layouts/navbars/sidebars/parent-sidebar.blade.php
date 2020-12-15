
<ul class="nav">
  <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="material-icons">dashboard</i>
        <p>{{ __('Tableau De Bord') }}</p>
    </a>
  </li>
  
  <li class="nav-item{{ $activePage == 'enfants' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('enfants') }}">
      <i class="material-icons">content_paste</i>
        <p>{{ __('Suivi Des Enfants') }}</p>
    </a>
  </li>
  {{-- <li class="nav-item{{ $activePage == 'reports' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('reports') }}">
      <i class="material-icons">leaderboard</i>
      <p>{{ __('Rapports') }}</p>
    </a>
  </li> --}}
  <li class="nav-item{{ $activePage == 'compte' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('compte') }}">
      <i class="material-icons">person</i>
      <p>{{ __('Compte') }}</p>
    </a>
  </li>
 
</ul>