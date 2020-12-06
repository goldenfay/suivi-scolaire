@extends('layouts.app', ['activePage' => 'enfants', 'titlePage' => __('Suivi Des Enfants')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row my-4">
      <div class="col-sm-4 d-flex align-items-center">
        <h5> Elève en cours de consultation :</h5>
      </div>
      <div class="col-sm-8">
        <form id="change_eleve_form" method="GET" class="form-row">
          <div class="form-group col-md-8">
            <label for="exampleFormControlSelect1">Veuillez choisir un élève à consulter</label>
          <select class="form-control" value="{{$eleve->eleve->Id}}" data-style="btn btn-link" id="exampleFormControlSelect1">
              @foreach ($children as $child)
            <option  value="{{__($child->eleve->Id)}}">
              <a href="{{route('enfants')}}/{{$child->eleve->Id}}">{{$child->eleve->Prenom}}
              </a>
            </option>
              @endforeach
              
              
            </select>

          </div>
          <div class="col-md-4 d-flex align-items-center">
            <button type="submit" class="btn btn-primary" onlcick="change_current_eleve()">Consulter</button>

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
                    <button type="button" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Marquer en attente de validation" class="my-3 btn btn-round btn-warning">
                        <i class="material-icons">pending_actions</i>
                    </button>
                    @endif
                    <button type="button" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Valider" class="my-3 btn btn-round btn-success">
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

    <h4> Evaluations récentes</h4>
    <div class="row mb-3">
      <div class="col-sm-12">
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
    
    
  </div>
  <script>
    document.getElementById('change_eleve_form').addEventListener('submit', (e) =>{
      e.preventDefault();
      
      window.location='/enfants/' + 
    encodeURIComponent(document.getElementById('exampleFormControlSelect1').value);
      
    });
    function change_current_eleve() {
      // e.preventDefault();
      
      window.location='/enfants/' + 
    encodeURIComponent(document.getElementById('exampleFormControlSelect1').value);
      
    }
  </script>
</div>
@endsection