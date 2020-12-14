<div class="wrapper ">
  @if(Auth::check() || Auth::guard('prof')->check())
  @include('layouts.navbars.sidebar')
  @endif
  
  <div class="main-panel">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>