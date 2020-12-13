<div class="wrapper ">
  @auth  
  @include('layouts.navbars.sidebar')
  @endauth
  @auth('prof')
    @include('layouts.navbars.prof-sidebar')
  @endauth
  <div class="main-panel">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>