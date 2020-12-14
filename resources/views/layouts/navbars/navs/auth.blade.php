<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#">{{ $titlePage }}</a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
    <span class="sr-only">Toggle navigation</span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      
      <ul class="navbar-nav">
        
        <li class="nav-item dropdown">
          <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
            @if(count(Auth::user()->unreadnotifications()->get()->all()))
            <span class="notification">{{count(Auth::user()->unreadnotifications()->get()->all())}}</span>
            @endif
            <p class="d-lg-none d-md-block">
              {{ __('Actions') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(Auth::check())
            
              @foreach (Auth::user()->unreadnotifications()->get()->all() as $notif)
              
              <a class="dropdown-item" 
              style="max-width: 400px"
              data-toggle="modal" data-target="#notifDisplay"
              onclick="read_observation_notification({{$notif->data['observationId']}},'{{$notif->id}}');"
              >
                <div class="container">
                  
                  <div class="row">
                    <div class="col-sm-0">
                      
                    </div>
                    <div class="col-sm-12">
                      <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-column">
                          <span class="text-dark text-weight-bold">{{$notif->data['title']}}</span>
                          <span class="text-muted px-1"> À {{$notif->data['eleve']}}</span>
                        </div>
                        <div class="text-muted">
                          <span>{{$notif->created_at}}</span>
                        </div>
                        
                      </div>
                      <div class="p-1 pr-3">
                        <p class="text-truncate">{{$notif->data['body']}}</p>
  
                      </div>
                      
                    </div>
                  </div>
                </div>
              </a>
              
              
              @endforeach
            @elseif(Auth::guard('prof')->check())
              @foreach (Auth::guard('prof')->user()->unreadnotifications()->get()->all() as $notif)

              @endforeach
            @endif
            
      
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              {{ __('Compte') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            
            <a class="dropdown-item" href="#">{{ __('Paramètres') }}</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="modal fade" id="notifDisplay" tabindex="-1" role="dialog" aria-labelledby="notifDisplayLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>
@push('js')
<script src="{{ asset('js') }}/services/teacher-services.js" ></script>
<script>
  function read_observation_notification(obsId,notifId){
   
    var url="{{url("/observations")}}"+`/${obsId}`;
    fetchRows(url).then(
      res=>{
        var observations=JSON.parse(res).observation;
        document.querySelector('#notifDisplay .modal-content').innerHTML=populate_notification_modal(observations.Libelle,observations.Corps);

      },
      err=>{
        document.querySelector('#notifDisplay .modal-content').innerHTML=`
        <div class="d-flex flex-row justify-centent center align-items-center">
          <h6 class="text-secondary">Une erreur s'est produite. Impossbile d'afficher le contenu.</h6>
        </div>
        `;

      }
    );
    $.ajax({
        url: "{{url("/observations/read")}}"+`/${obsId}`,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest',
        },
        data:{
          id:notifId,
          // actionner: currentUser.id,
          
        },
        success: res=>{
  
        },
        error: err=>{
          
        }
  
  
      })



  }
  function populate_notification_modal(title,body){
    return `
    <div class="modal-header">
        <h5 class="modal-title" id="notifDisplayLabel">${title}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>${body}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    `;
  }
</script>

@endpush