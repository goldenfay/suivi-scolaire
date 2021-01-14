
<ul class="nav">
  <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
      <i class="material-icons">dashboard</i>
        <p>{{ __('Tableau De Bord') }}</p>
    </a>
  </li>
  
  <li class="nav-item{{ $activePage == 'classes' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('admin.classes') }}">
      <i class="material-icons">home_work</i>
      {{-- <i class="fa fa-school"></i> --}}
        <p>{{ __('Classes') }}</p>
    </a>
  </li>
  <li class="nav-item{{ $activePage == 'enseignants' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('admin.enseignants') }}">
      <i class="material-icons">school</i>
      {{-- <i class="fas fa-school"></i> --}}
        <p>{{ __('Enseignants') }}</p>
    </a>
  </li>
  <li class="nav-item{{ $activePage == 'parents' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('admin.parents') }}">
      <i class="material-icons">escalator_warning</i>
      {{-- <i class="fas fa-school"></i> --}}
        <p>{{ __('Parents') }}</p>
    </a>
  </li>
  <li class="nav-item{{ $activePage == 'eleves' ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('admin.eleves') }}">
      <i class="material-icons">people</i>
      {{-- <i class="fas fa-school"></i> --}}
        <p>{{ __('Elèves') }}</p>
    </a>
  </li>
  
  
  
</ul>
