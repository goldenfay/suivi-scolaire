@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
?>
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row my-4">
      <div class="col-sm-4 d-flex align-items-center">
        <h5> Classe :</h5>
      </div>
      <div class="col-sm-8">
        <form id="change_eleve_form" method="GET" class="form-row">
          <div class="form-group col-md-8">
            <label for="exampleFormControlSelect1">Spécifiez la classe à gérer</label>
          <select class="form-control" value="{{property_exists($currentClasse->classe,"Id")?$currentClasse->classe->Id: ""}}" data-style="btn btn-link" id="exampleFormControlSelect1">
              @foreach ($user->classes as $classe)
            <option  value="{{__($classe->classe->Id)}}">
              <a href="{{route('enseignement')}}/{{$classe->classe->Id}}">{{$classe->classe->Des}}
              </a>
            </option>
              @endforeach
              
              
            </select>

          </div>
          <div class="col-md-4 d-flex align-items-center">
            <button type="submit" class="btn btn-primary" >Consulter</button>

          </div>

        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Liste des élèves</h5>
            <div>
              <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Age</th>
                        <th class="text-center">Remarques</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!reset($currentClasse->eleves))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucun élève n'est inscrit dans cette classe</h4>
                      </td>
                    </tr>

                    @else
                      @foreach ($currentClasse->eleves as $eleve)
                      <tr>
                          <td class="text-center">{{$eleve->Code}}</td>
                          <td>{{$eleve->Nom}}</td>
                          <td>{{$eleve->Prenom}}</td>
                          <td>{{$eleve->Age}}</td>
                          <td class="text-center">
                            @if(array_key_exists($eleve->Id,$user->observations_per_eleve))
                          <span class="badge badge-warning">{{$user->observations_per_eleve[$eleve->Id]}} </span> évènnements non validés
                            @endif

                            
                          </td>
                          <td class="td-actions text-center">
                              <button type="button" rel="tooltip"
                              data-toggle="tooltip" 
                              data-placement="top" 
                              title="Consulter"  
                              class="btn btn-round btn-info"
                              onclick="alert({{$eleve->Id}})"
                              >
                                  <i class="material-icons">person</i>
                              </button>
                              <button type="button" rel="tooltip"
                              data-toggle="tooltip" 
                              data-placement="top" 
                              title="Ecrire dans son cahier de correpondance"  
                              class="btn btn-round btn-primary"
                              onclick="alert({{$eleve->Id}})"
                              >
                                  <i class="material-icons">edit</i>
                              </button>
                             
                          </td>
                      </tr>
                          
                      @endforeach
                    @endif
                    
                </tbody>
            </table>
            </div>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>
  <script>
    document.getElementById('change_eleve_form').addEventListener('submit', (e) =>{
      e.preventDefault();
      
      const destination=window.location='/prof/enseignement/' + 
    encodeURIComponent(document.getElementById('exampleFormControlSelect1').value);
    if(window.location.href===destination) return
      window.location=destination;
      
    });
    
  </script>
</div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
@endpush