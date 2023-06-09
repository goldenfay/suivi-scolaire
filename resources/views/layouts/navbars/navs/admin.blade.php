<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="#">{{ $titlePage ?? 'Tablea de bord' }}</a>
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

                        @php
                            $notifications = Auth::guard('admin')
                                ->user()
                                ->unreadnotifications()
                                ->get()
                                ->all();
                        @endphp

                        @if (count($notifications) == 0)

                            <p class="text-muted text-center my-3">Aucune notification</p>
                        @else
                            @foreach (notifications as $notif)
                                <a class="dropdown-item" href="#" style="max-width: 400px" data-toggle="modal"
                                    data-target="#notifDisplay"
                                    onclick="read_observation_notification({{ $notif->data['observationId'] }},'{{ $notif->id }}','parent');">
                                    <div class="container">

                                        <div class="row">
                                            <div class="col-sm-0">

                                            </div>
                                            <div class="col-sm-12">
                                                <div class="d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="text-dark text-weight-bold">{{ $notif->data['title'] }}</span>
                                                        <span class="text-muted px-1"> À
                                                            {{ $notif->data['eleve'] }}</span>
                                                    </div>
                                                    <div class="text-muted">
                                                        <span>{{ $notif->created_at }}</span>
                                                    </div>

                                                </div>
                                                <div class="p-1 pr-3">
                                                    <p class="text-truncate">{{ $notif->data['body'] }}</p>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif

                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownProfile" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
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
    <script></script>
@endpush
