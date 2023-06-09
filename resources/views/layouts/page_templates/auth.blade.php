<div class="wrapper ">
    @if (Auth::guard('web')->check() || Auth::guard('prof')->check())
        @include('layouts.navbars.sidebar')
    @elseif(Auth::guard('admin')->check())
        @include('layouts.navbars.sidebar')
    @endif

    <div class="main-panel">
        @if (Auth::guard('web')->check() || Auth::guard('prof')->check())
            @include('layouts.navbars.navs.auth')
        @elseif(Auth::guard('admin')->check())
            @include('layouts.navbars.navs.admin')
        @endif
        @yield('content')
        @include('layouts.footers.auth')
    </div>
</div>
