
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
  
  <li class="nav-item{{ $activePage == 'reports' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('reports') }}">
      <i class="material-icons">leaderboard</i>
      <p>{{ __('Rapports') }}</p>
    </a>
  </li>
  
</ul>
