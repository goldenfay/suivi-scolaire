@extends('layouts.app', ['activePage' => 'enfants', 'titlePage' => __('Suivi Des Enfants')])
<?php
setlocale(LC_TIME, "fr_FR");
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
// $key = config('requests.SECRET_KEY');
// $enc_parent=SymmetricCrypto::encrypt(new HiddenString(($user->id).""), $key);
// $enc_eleve=SymmetricCrypto::encrypt(new HiddenString($eleve->eleve->id.""
//     ), $key)
    // KeyFactory::save($key, '/secret.key');
$enc_parent=$user->id;
$enc_eleve=$eleve->eleve->id;

$days=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];
$hours=[
  "08:00-09:00","09:00-10:00","10:00-11:00","11:00-12:00",
  "13:30-14:30","14:30-15:30","15:30-16:30"
];

function badge_class($etat){

switch($etat){
  case "NV": return "badge-danger";
  case "V": return "badge-dark";
  case "ATV": return "badge-warning";
  case "VAL": return "badge-success";
  default: return "";

}
}

?>
@section('content')
@push('styles')
<link href="{{ asset('css')."/calendar.css" }}" rel="stylesheet">
@endpush
<div class="content">
  <div class="container-fluid">
    <div class="row my-4">
      <div class="col-sm-4 d-flex align-items-center">
        <h5> Elève en cours de consultation :</h5>
      </div>
      <div class="col-sm-8">
        <form id="change_eleve_form" method="GET" class="form-row">
          <div class="form-row my-3">

            <div class="form-group col-md-4">
              <label for="eleveSelect">Veuillez choisir un élève à consulter</label>
              <select class="form-control" data-style="btn btn-link" id="eleveSelect">
                @foreach ($children as $child)
                <option value="{{$child->eleve->id}}" {{$child->eleve->id==$eleve->eleve->id?"selected='selected'":""}}>
                  <a href="{{route('enfants')}}/{{$child->eleve->id}}">{{$child->eleve->Prenom}}
                  </a>
                </option>
                @endforeach


              </select>

            </div>
            <div class="form-group col-md-4">
              <label for="classeSelect">Veuillez choisir la classe</label>
              <select class="form-control" data-style="btn btn-link" id="classeSelect">
                @foreach ($children[$eleve->eleve->id]->classes as $classe)
                <option value="{{$classe->id}}" {{$classe->id==$currentClasse->id?"selected='selected'":""}}>
                  <a href="#">{{$classe->Des}}
                  </a>
                </option>
                @endforeach


              </select>

            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center">
              <button type="submit" class="btn btn-primary">Consulter</button>

            </div>
          </div>

        </form>
      </div>
    </div>

    {{-- Ecrire un message pour l'observation --}}
    @foreach ($observations as $idx => $observation)
    <div class="modal fade" id="write-message-modal-{{$observation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Réponse sur l'observation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url("observations/add")}}" id="addObservation-form">
              @csrf
            
              <div class="form-group my-3">
                <label for="contenu-input">Votre réponse</label>
                <textarea type="text" class="form-control" name="corps" 
                  placeholder="Ecrivez en bref votre réponse"></textarea>
                @if ($errors->has('corps'))
                <div id="corps-error" class="error text-danger pl-3" for="corps" style="display: block;">
                  <strong>{{ $errors->first('corps') }}</strong>
                </div>
                @endif
              </div>


              {{-- <button type="submit" class="btn btn-primary">Envoyer</button> --}}
            </form>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button id="{{$observation->id}}"
            type="submit" class="btn btn-primary" 
            onclick="send_observation_feedback(event)">
            Envoyer
            </button>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    <h4> Cahier de correspondance</h4>
    <div class="row mb-3">
      <div class="col-sm-12">
        <div style="background-image: linear-gradient(to top left, rgba(255,0,0,0), rgb(0, 102, 255,0.3)">
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Date</th>
                <th>Type</th>
                <th>Rédigée par</th>
                <th>Titre</th>
                <th class="text-center">Contenu</th>
                <th class="text-center">Etat</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(!reset($observations))
              <tr>
                <td colspan="8">
                  <h4 class="text-secondary text-center"> Aucune observation</h4>
                </td>
              </tr>
              @endif
              @foreach ($observations as $idx => $observation)
              {{-- @if(strcasecmp($observation->Type,"Information")) --}}
              <tr>
                <td class="text-center">{{$idx+1}}</td>
                <td>{{$observation->Date}}</td>
                <td>{{$observation->Type}}</td>
                <td>{{$observation->NomProfesseur}} {{$observation->PrenomProfesseur}}</td>
                <td>{{$observation->Libelle}}</td>
                <td>{{$observation->Corps}}
                  @if($observation->ReponseParent!=NULL)
                  <div>
                    <i rel="tooltip" 
                    data-placement="top" title="{{$observation->ReponseParent}}"
                    data-toggle="tooltip" 
                    class="material-icons">comment</i>
                  </div>
                  @endif
                </td>
                <td class="d-flex justify-content-center badge badge-pill {{badge_class($observation->Etat)}}">{{
                ($observation->Etat=="NV"? "Non consultée":
                ($observation->Etat=="V"? "Consultée":
                ($observation->Etat=="ATV"? "En attente de validation":
                ($observation->Etat=="VAL"? "Validée":$observation->Etat))) )
                }}</td>

                @if($observation->Etat!="VAL")
                <td class="td-actions text-center">
                  @if($observation->Etat!="ATV")
                  <button type="button" id="{{$observation->id}}" rel="tooltip" data-toggle="tooltip"
                    data-placement="top" title="Marquer en attente de validation" class="my-3 btn btn-round btn-warning"
                    onclick="set_observation_pending(event)">
                    <i class="material-icons">pending_actions</i>
                  </button>
                  @endif
                  @if($observation->ReponseParent==NULL)
                  <button type="button" id="reply-{{$observation->id}}" rel="tooltip" 
                    data-placement="top" title="Répondre par un message" class="my-3 btn btn-round btn-secondary"
                    data-toggle="modal" data-target="#write-message-modal-{{$observation->id}}"
                    >
                    <i class="material-icons">comment</i>
                  </button>
                  @endif
                  <button type="button" id="{{$observation->id}}" rel="tooltip" data-toggle="tooltip"
                    data-placement="top" title="Valider" class="my-3 btn btn-round btn-success"
                    onclick="set_observation_validated(event)">
                    <i class="material-icons">done</i>
                  </button>

                </td>
                @endif

              </tr>
              {{-- @endif --}}
              @endforeach

            </tbody>
          </table>

        </div>
      </div>




    </div>

    <div class="row mb-3">
      <div class="col-sm-8">
        <h4> Evaluations récentes</h4>
        <div class="card">
          <div class="card-body">

            <table class="table">
              <thead>
                <tr>
                  <th>Matière</th>
                  <th>Date</th>
                  <th>Evalué par</th>
                  <th>Note</th>
                </tr>
              </thead>
              <tbody>
                @if(!reset($evaluations))
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune évaluation dans cette tranche d'année</h4>
                  </td>
                </tr>
                @endif

                @foreach ($evaluations as $idx => $evaluation)

                <tr>
                  <td>{{$evaluation->Matiere}}</td>
                  <td>{{isset($evaluation->Date)?$evaluation->Date:"Non spécifiée" }}</td>
                  <td>{{"Non spécifié"}} {{""}}</td>
                  <td class="{{$evaluation->Resultat<5?"text-danger":""}}">{{$evaluation->Resultat}}</td>

                </tr>
                @endforeach

              </tbody>
            </table>
          </div>

        </div>

      </div>
      <div class="col-sm-4">
        <h4>Calendrier des épreuves</h4>
        <div class="calendar-box jzdbasf calendar-container dark-green-bg mt-2" id="up-events-calendar">

          <div class="jzdcalt">{{date('F, Y')}} </div>
          <span>Ven</span>
          <span>Sam</span>
          @foreach ($days as $day)
          <span>{{substr($day,0,3)}}</span>

          @endforeach

        </div>





      </div>



    </div>

    {{-- Observations Summary section --}}
    <h4>Récapitulatif</h4>
    <div class="row">
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Evènnements:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#diciplines" data-toggle="tab">
                      <i class="material-icons">emoji_people</i> Dicipline
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#appreciations" data-toggle="tab">
                      <i class="material-icons">emoji_events</i> Appréciations
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#informations" data-toggle="tab">
                      <i class="material-icons">info</i> Informations
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#autres" data-toggle="tab">
                      <i class="material-icons">description</i> Autres
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="diciplines">
                <table class="table">
                  <tbody>
                    @if(!($observations->where('UPPER(Type)','DISCIPLINE')->count()))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune observation sur la dscipline</h4>
                      </td>
                    </tr>
                    @endif
                    @foreach ($observations as $idx => $observation)
                    @if(strcasecmp($observation->Type,"Discipline")==0)
                    <tr>
                      <td>{{$observation->Date}}</td>
                      <td>{{$observation->Corps}}</td>
                      <td>{{$observation->NomProfesseur}} {{$observation->PrenomProfesseur}}</td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="appreciations">
                <table class="table">
                  <tbody>
                    @if(!($observations->where('UPPER(Type)','APPRECIATION')->count()))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune appréciation</h4>
                      </td>
                    </tr>
                    @endif
                    @foreach ($observations as $idx => $observation)
                    @if(strcasecmp($observation->Type,"Appréciation")==0)
                    <tr>
                      <td>{{$observation->Date}}</td>
                      <td>{{$observation->Corps}}</td>
                      <td>{{$observation->NomProfesseur}} {{$observation->PrenomProfesseur}}</td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="informations">
                <table class="table">
                  <tbody>
                    @if(!($observations->where('UPPER(Type)','INFORMATION')->count()))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune communication informative</h4>
                      </td>
                    </tr>
                    @endif
                    @foreach ($observations as $idx => $observation)
                    @if(strcasecmp($observation->Type,"Information")==0)
                    <tr>
                      <td>{{$observation->Date}}</td>
                      <td>{{$observation->Corps}}</td>
                      <td>{{$observation->NomProfesseur}} {{$observation->PrenomProfesseur}}</td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="autres">
                <table class="table">
                  <tbody>
                    @if(!($observations->whereNotIn('UPPER(Type)',['APPRECIATION','DICIPLINE','INFORMATION'])->count()))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune observation</h4>
                      </td>
                    </tr>
                    @endif
                    @foreach ($observations->whereNotIn('UPPER(Type)',['APPRECIATION','DICIPLINE','INFORMATION']) as
                    $idx => $observation)
                    {{-- @if(strcasecmp($observation->Type,"Autre")==0)    --}}
                    <tr>
                      <td>{{$observation->Date}}</td>
                      <td>{{$observation->Corps}}</td>
                      <td>{{$observation->NomProfesseur}} {{$observation->PrenomProfesseur}}</td>
                    </tr>
                    {{-- @endif --}}
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header card-header-warning">
            <h4 class="card-title">Historique</h4>
            <p class="card-category"></p>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>Date</th>
                <th>Type</th>
                <th>Contenu</th>
              </thead>
              <tbody>
                @if(!reset($observations))
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune observation</h4>
                  </td>
                </tr>
                @endif
                @foreach ($observations as $idx => $observation)
                <tr>
                  <td>{{$observation->Date}}</td>
                  <td>{{$observation->Type}} {{$observation->PrenomProfesseur}}</td>
                  <td>{{$observation->Corps}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Children's schedule section --}}
    <h4>Emploi Du Temps</h4>
    <div class="row my-3">
      <div class="col-sm-12 p-3">
        <table class="table  table-striped table-light table-hover">
          <thead>
            <tr>
              <td scope="col">Jour</td>
              @foreach ($hours as $hour)
              <td scope="col" class="text-centerx">{{$hour}}</td>

              @endforeach
            </tr>

          </thead>
          <tbody>
            @foreach ($days as $day)
            <tr>
              <td scope="row">{{$day}}</td>
              @foreach ($schedule->where('Jour',$day) as $daySchedule)
              <td class="text-center">{{ array_search($daySchedule->Heure,$hours).""!="false"?$daySchedule->DesM:"/" }}
              </td>

              @endforeach


            </tr>

            @endforeach
            <tr>
              <td></td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <div class="row">
      <div id="calendar">

      </div>
    </div>

    <div style="position: sticky; bottom: 5%; left: 0;right: 0; width: 100% ;min-height:50px">
      <div class="d-flex flex-row justify-content-center" id="observations-actions-results">

      </div>

    </div>


  </div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('js') }}/calendar.js"></script>
<script src="{{ asset('js') }}/services/teacher-services.js"></script>

<script>
  var evals_plans_url="{{url("/evaluations/planning/classe")}}";
      var classe=@json($currentClasse);
      
      fetchRows(`${evals_plans_url}/${classe.id}`).then(
        res=>{
          var result=JSON.parse(res);

          var calendarEvents=result.evaluations
          .map(eval=>({
            day: new Date(eval.Date).getDate(),
            title:  `${eval.Type || "Examen"} en ${eval.Matiere}`

          })).concat(result.events
          .map(event=>({
            day: new Date(event.Date).getDate(),
            title: `${event.Titre}`

          })))
          displayEvents('up-events-calendar',calendarEvents);


        },
        err=>{

        }
      );
</script>


<script>
  document.getElementById('eleveSelect').onchange=function(e){
      var children= @json($children);
      console.log(children[e.target.value].classes);
      document.getElementById('classeSelect').innerHTML=`
      ${children[e.target.value].classes.map(classe=>`
        <option  value="${classe.id}">
                <a href="#">${classe.Des}
                </a>
              </option>`)}         
      `;
    }
    document.getElementById('change_eleve_form').addEventListener('submit', (e) =>{
      e.preventDefault();
      
      var eleve=document.getElementById('eleveSelect').value,
      classe=document.getElementById('classeSelect').value
      const destination=`${@json(route('enfants'))}/${eleve}/${classe}`;
      // const destination=window.location="{{route('enfants',['eleveId'=>2,'classeId'=>1])}}/";
      // //  + encodeURIComponent(document.getElementById('eleveSelect').value);
    if(window.location.href===destination) return
      window.location=destination;
      
    });
    function set_observation_pending(e){
      update_observation(e,"ATV");
    }
    function set_observation_validated(e){
      update_observation(e,"VAL");
    }
    function send_observation_feedback(e){
    
      update_observation(e,"ATV",document.getElementsByName("corps")[0].value);
    }
    function update_observation(e,etat,reponse=null){
      var target=e.currentTarget;
      var id=target.id;

    $.ajax({
      url: "{{url("/observations")}}"+"/"+id,
      type: 'PUT',
      data: {
        Etat: etat,
        ReponseParent: reponse,
        actionner: "{{$enc_parent}}",
        eleve: "{{$enc_eleve}}",
        } ,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest',

      },
      

      success: res=>{
        console.log(JSON.parse(res));
        $(e.currentTarget).remove();
        var resultDiv=document.getElementById('observations-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-success alert-with-icon w-60" data-notify="container">
        <i class="material-icons" data-notify="icon">check</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
      <span>Mise à jours réussie</span>
      </div>
        `
        if(etat==="VAL")
          $(`button[id=${id}]`).remove()
          else   
          $(target).remove()
        
        $(resultDiv).show();

        // Hide response modal
        $(`#write-message-modal-${id}`).modal('hide')
        setTimeout(function () {
      	$(resultDiv).slideUp(500);
        if(reponse) window.location.reload()
        }, 2000);

      },
      error: err=>{
        console.log(err);
        var resultDiv=document.getElementById('observations-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
        <i class="material-icons" data-notify="icon">error</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
      <span> Une erreur s'est produite. ${err.message || (err.responseText && JSON.parse(err.responseText).message) || "Impossible de mettre à jours l'état"}</span>
      </div>
        `
        $(resultDiv).show();
        setTimeout(function () {
      	$(resultDiv).slideUp(500);
      }, 2000);
      }


    });}
    
</script>
@endpush