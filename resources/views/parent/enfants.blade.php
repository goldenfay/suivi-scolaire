@extends('layouts.app', ['activePage' => 'enfants', 'titlePage' => __('Suivi Des Enfants')])
<?php
setlocale(LC_TIME, "fr_FR");
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto as SymmetricCrypto;
use ParagonIE\HiddenString\HiddenString;
// $key = config('requests.SECRET_KEY');
// $enc_parent=SymmetricCrypto::encrypt(new HiddenString(($user->Id).""), $key);
// $enc_eleve=SymmetricCrypto::encrypt(new HiddenString($eleve->eleve->Id.""
//     ), $key)
    // KeyFactory::save($key, '/secret.key');
$enc_parent=$user->Id;
$enc_eleve=$eleve->eleve->Id;

$days=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];
$hours=[
  "08:00-09:00","09:00-10:00","10:00-11:00","11:00-12:00",
  "13:30-14:30","14:30-15:30","15:30-16:30"
];

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
            <select class="form-control" value="{{$eleve->eleve->Id}}" data-style="btn btn-link" id="eleveSelect">
                @foreach ($children as $child)
              <option  value="{{$child->eleve->Id}}">
                <a href="{{route('enfants')}}/{{$child->eleve->Id}}">{{$child->eleve->Prenom}}
                </a>
              </option>
                @endforeach
                
                
              </select>
  
            </div>
            <div class="form-group col-md-4">
              <label for="classeSelect">Veuillez choisir la classe</label>
            <select class="form-control" value="{{$eleve->eleve->Id}}" data-style="btn btn-link" id="classeSelect">
                @foreach ($children[$eleve->eleve->Id]->classes as $classe)
              <option  value="{{$classe->Id}}">
                <a href="#">{{$classe->Des}}
                </a>
              </option>
                @endforeach
                
                
              </select>
  
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center">
              <button type="submit" class="btn btn-primary" >Consulter</button>
  
            </div>
          </div>

        </form>
      </div>
    </div>
    <h4> Cahier de correspondance</h4>
    <div class="row mb-3">
      <div class="col-sm-12">
        <div style="background-image: linear-gradient(to top left, rgba(255,0,0,0), rgb(0, 102, 255,0.3)">
        <table class="table" >
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
              @if(strcasecmp($observation->Type,"Information"))    
              <tr>
                <td class="text-center">{{$idx+1}}</td>
                <td>{{$observation->Date}}</td>
                <td>{{$observation->Type}}</td>
                <td>{{$observation->NomProfesseur}}  {{$observation->PrenomProfesseur}}</td>
                <td>{{$observation->Libelle}}</td>
                <td>{{$observation->Corps}}</td>
                <td>{{
                ($observation->Etat=="NV"? "Non consultée":
                ($observation->Etat=="V"? "Consultée":
                ($observation->Etat=="ATV"? "En attente de validation":
                ($observation->Etat=="VAL"? "Validée":$observation->Etat))) )
                }}</td>

                @if($observation->Etat!="VAL")
                <td class="td-actions text-center">
                    @if($observation->Etat!="ATV")
                    <button type="button" 
                    id="{{$observation->Id}}"
                    rel="tooltip" data-toggle="tooltip" data-placement="top" title="Marquer en attente de validation" 
                    class="my-3 btn btn-round btn-warning"
                    onclick="set_observation_pending(event)"
                    >
                    <i class="material-icons">pending_actions</i>
                  </button>
                  @endif
                  <button type="button" 
                  id="{{$observation->Id}}"
                  rel="tooltip" data-toggle="tooltip" data-placement="top" title="Valider" 
                  class="my-3 btn btn-round btn-success"
                  onclick="set_observation_validated(event)"
                  >
                        <i class="material-icons">done</i>
                    </button>
                  
                </td>
                @endif
                    
              </tr>
              @endif
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

            <table class="table" >
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
                  <td>{{"Non spécifié"}}  {{""}}</td>
                  <td class="{{$evaluation->Resultat<5?"text-danger":""}}">{{$evaluation->Resultat}}</td>
    
                </tr>
                @endforeach
                  
              </tbody>
            </table>
          </div>
        
        </div>

      </div>
      <div class="col-sm-4">
        <h4>Calendrier des évaluations</h4>
        <div class="jzdbox1 jzdbasf jzdcal" id="up-events-calendar">

          <div class="jzdcalt">{{date('F, Y')}} </div>
          <span>Ven</span>
          <span>Sam</span>
          @foreach ($days as $day)
          <span>{{substr($day,0,3)}}</span>
              
          @endforeach
          
        </div>
            
          
          
        
        
      </div>



    </div>
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
                    @if(!reset($observations))
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
                        <td>{{$observation->NomProfesseur}}  {{$observation->PrenomProfesseur}}</td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="appreciations">
                <table class="table">
                  <tbody>
                    @if(!reset($observations))
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
                        <td>{{$observation->NomProfesseur}}  {{$observation->PrenomProfesseur}}</td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="informations">
                <table class="table">
                  <tbody>
                    @if(!reset($observations))
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
                        <td>{{$observation->NomProfesseur}}  {{$observation->PrenomProfesseur}}</td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="autres">
                <table class="table">
                  <tbody>
                    @if(!reset($observations))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune observation</h4>
                      </td>
                    </tr>
                    @endif
                    @foreach ($observations as $idx => $observation)
                      @if(strcasecmp($observation->Type,"Autre")==0)   
                      <tr>
                        <td>{{$observation->Date}}</td>
                        <td>{{$observation->Corps}}</td>
                        <td>{{$observation->NomProfesseur}}  {{$observation->PrenomProfesseur}}</td>
                      </tr>
                      @endif
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
                    <td>{{$observation->Type}}  {{$observation->PrenomProfesseur}}</td>
                    <td>{{$observation->Corps}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
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
              <td class="text-center">{{ array_search($daySchedule->Heure,$hours).""!="false"?$daySchedule->DesM:"/" }}</td>
    
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
  @push('js')
  <script type="text/javascript" 
  src="{{ asset('js') }}/calendar.js"></script>
  @endpush

  <script>
    document.getElementById('eleveSelect').onchange=function(e){
      console.log(e.target.value);
      var children= @json($children);
      console.log(children[e.target.value].classes);
      document.getElementById('classeSelect').innerHTML=`
      ${children[e.target.value].classes.map(classe=>`
        <option  value="${classe.Id}">
                <a href="#">${classe.Des}
                </a>
              </option>`)}         
      `;
    }
    document.getElementById('change_eleve_form').addEventListener('submit', (e) =>{
      e.preventDefault();
      
      var eleve=document.getElementById('eleveSelect').value,
      classe=document.getElementById('classeSelect').value
      const destination=window.location=`${@json(route('enfants'))}/${eleve}/${classe}`;
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
    function set_observation_pending(e){
      update_observation(e,"ATV");
    }
      function update_observation(e,etat){
      var target=e.currentTarget;
      var id=target.id;
      console.log(id);

    $.ajax({
      url: "{{url("/observations")}}"+"/"+id,
      type: 'PUT',
      data: {
        Etat: etat,
        actionner: "{{$enc_parent}}",
        eleve: "{{$enc_eleve}}",
        } ,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest',

      },
      

      success: res=>{
        console.log(res);
        console.log(JSON.parse(res));
        $(e.currentTarget).remove();
        var resultDiv=document.getElementById('observations-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-success alert-with-icon" data-notify="container">
        <i class="material-icons" data-notify="icon">check</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
      <span>Mise à jours réussie</span>
      </div>
        `
        $(resultDiv).show();
        setTimeout(function () {
      	$(resultDiv).slideUp(500);
      }, 2000);

      },
      error: err=>{
        console.log(err);
        var resultDiv=document.getElementById('observations-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-danger alert-with-icon" data-notify="container">
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
</div>
@endsection