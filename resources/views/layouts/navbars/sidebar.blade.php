<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">

  <div class="logo my-5">
    <a href="#" class="simple-text logo-normal">
      {{-- {{ __('Bonjour') }} --}}
      @if(Auth::check())
      
      {{Auth::user()->Nom}} {{Auth::user()->Prenom}}
      @else
      {{ __('Bonjour') }}
      @endif
    </a>
  </div>
  <div class="sidebar-wrapper mt-5">
    @if(Auth::guard('prof')->check())
      @include('layouts.navbars.sidebars.prof-sidebar')
    @elseif(Auth::guard('admin')->check())
      @include('layouts.navbars.sidebars.admin-sidebar')
    @elseif(Auth::check())
      @include('layouts.navbars.sidebars.parent-sidebar')
    @endif
    
    <div class="mt-5 ps-container ps-theme-default">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link" 
          href="{{ route('logout') }}"
          onclick="event.preventDefault();document.getElementById('logout-form').submit();"
          >
            <i class="material-icons">power_settings_new</i>
            <p>{{ __('Déconnexion') }}</p>
          </a>
        </li>
  
      </ul>
      
    </div>



  </div>
</div>
