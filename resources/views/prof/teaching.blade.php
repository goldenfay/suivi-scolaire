@extends('layouts.app', ['activePage' => 'enseignement', 'titlePage' => __('Enseignement')])

<?php
$days=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];
$hours=[
  "08:00-09:00","09:00-10:00","10:00-11:00","11:00-12:00",
  "13:30-14:30","14:30-15:30","15:30-16:30"
];
?>
@section('content')
@push('styles')
    <link href="{{ asset('css')."/calendar.css" }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('propeller')."/css/propeller.css" }}">
    <link rel="stylesheet" href="{{ asset('propeller')."/css/propeller.min.css" }}"> --}}
@endpush
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
              <a href="{{route('prof.enseignement')}}/{{$classe->classe->Id}}">{{$classe->classe->Des}}
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
                              {{-- onclick="alert({{$eleve->Id}})" --}}
                              >
                            <a href="{{route("prof.correspondance",$eleve->Id)}}">

                                <i class="far fa-edit"></i>
                              </a>
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
    <div class="row">
      
      <div class="col-sm-8">
        <h4>Planifier une épreuve</h4>
        <div class="card">
          <div class="card-body">
            
            <form method="POST" action="{{url("evaluations/add")}}" id="addObservation-form">
              @csrf
  
              <input type="hidden"  value="{{$user->prof->Id}}" name="profId"/>
              <div class="form-row my-3">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="matiere-input">Matiere</label>
                  <select id="matiere-input" name="matiere" class="form-control">
                    <option value="Discipline">ANG</option>
                    <option value="Appréciation">PHYS</option>
                    <option value="Information">AR</option>
                    <option value="Convocation">FR</option>
                  </select>
                  @if ($errors->has('matiere'))
                    <div id="matiere-error" class="error text-danger pl-3" for="matiere" style="display: block;">
                      <strong>{{ $errors->first('matiere') }}</strong>
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="titre-input">Titre</label>
                  <input type="text" class="form-control" name="titre" id="titre-input" placeholder="Titre de la communication ...">
                  @if ($errors->has('titre'))
                    <div id="titre-error" class="error text-danger pl-3" for="titre" style="display: block;">
                      <strong>{{ $errors->first('titre') }}</strong>
                    </div>
                  @endif
                </div>
  
                
              </div>
              
              <div class="form-group my-3">
                <label for="contenu-input">Date</label>
                <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                  <input type="date" class="form-control" id="timepicker">
                </div>
                @if ($errors->has('corps'))
                <div id="corps-error" class="error text-danger pl-3" for="corps" style="display: block;">
                  <strong>{{ $errors->first('corps') }}</strong>
                </div>
                @endif
              </div>
              <div class="form-row my-3">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="heure-input">Heure</label>
                  <select id="heure-input" name="heure" class="form-control">
                    @foreach($hours as $hour)
                    <option value="{{$hour}}">{{$hour}}</option>
                    @endforeach
                    
                  </select>
                  @if ($errors->has('heure'))
                    <div id="heure-error" class="error text-danger pl-3" for="heure" style="display: block;">
                      <strong>{{ $errors->first('heure') }}</strong>
                    </div>
                  @endif
                </div>
                
                
              </div>
              
              
              <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
          </div>

        </div>
        
      </div>
      <div class="col-sm-4">
        <h4>Calendrier</h4>
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
{{--     
<script src="{{ asset('propeller')."/js/propeller.js" }}"></script>
<script src="{{ asset('propeller')."/js/propeller.min.js" }}"></script>
    <!-- Propeller textfield js --> 
    <script type="text/javascript" src="http://propeller.in/components/textfield/js/textfield.js"></script>

    <!-- Datepicker moment with locales -->
    <script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/moment-with-locales.js"></script>

    <!-- Propeller Bootstrap datetimepicker -->
    <script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/bootstrap-datetimepicker.js"></script> --}}

  <script>
    $(document).ready(function() {
  //     $('#timepicker').datetimepicker({
	// 	format: 'LT'
	// });
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
@endpush