@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\Card;
use \koolreport\widgets\google\PieChart;
$nbr_eleves_formation=$report->dataStore("nbr_eleves_formation");
$nbr_eleves_classe=$report->dataStore("nbr_eleves_classe");
$revenues_formation=$report->dataStore("revenues_formation");

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
    <div class="row my-3">
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <p class="card-category">Formations enseignées</p>
            <h3 class="card-title">{{$nbr_eleves_formation->count()}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <i class="material-icons">group</i>
            </div>
            <p class="card-category">Elèves dans toutes les formations</p>
            <h3 class="card-title">{{$nbr_eleves_formation->sum("Count")}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-success card-header-icon">
            <div class="card-icon">
              <i class="material-icons">attach_money</i>
            </div>
            <p class="card-category">Revenues Estimées</p>
            <h3 class="card-title">{{$revenues_formation->sum("Total")}}</h3>
          </div>

        </div>
      </div>


    </div>


    {{-- Formations statistics section --}}
    <h4> Formations</h4>
    <div class="row mb-3">
      <div class="col-sm-12 col-md-6">

        {{PieChart::create(array(
            "title"=>"Répartition des élèves sur les formations",
            "dataSource"=>$report->dataStore('nbr_eleves_formation'),
            "columns"=>array(
              "NomF"=>array("label"=>"Nom Formation"),
              "Count")
            )) 
          }}
      </div>

      <div class="col-sm-12 col-md-6">

        {{PieChart::create(array(
            "title"=>"Revenues  des formations",
            "dataSource"=>$report->dataStore('revenues_formation'),
            "columns"=>array(
              "NomF"=>array("label"=>"Nom Formation"),
              "Total"=>array("suffix"=>"DA")),
            "colorScheme"=>array(
              "#00876c","#10689c","#a2b997","#dfa47e","#d43d51"
            ) 

            )) 

          }}
      </div>




    </div>


    {{-- Classes statistics section --}}
    <h4> Classes</h4>
    <div class="row mb-3">
      <div class="col-sm-12 col-md-6">

        {{PieChart::create(array(
            "title"=>"Répartition des élèves sur les différentes classes",
            "dataSource"=>$report->dataStore('nbr_eleves_classe'),
            "columns"=>array(
              "NomC"=>array("label"=>"Classe"),
              "Count"),
              "colorScheme"=>array(
                "#003f5c","#f95d6a","#2f4b7c","#ffa600","#665191","#a05195","#d45087","#ff7c43"
              )
            )) 
          }}
      </div>

      <div class="col-sm-12 col-md-6">
        <div class="h-100 d-flex flex-row justify-content-center align-items-center">

          {{-- <h5 class="card-title">Calendrier des évaluations</h5> --}}
          <div class="calendar-container calendar-box jzdbasf light-orange-bg mt-2" id="up-events-calendar">

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

    {{-- Communications & observations summary --}}
    <h4>Communication</h4>
    <div class="row mb-3">
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="material-icons">history_edu</i>
            </div>
            <p class="card-category">Correspondances</p>
            <h3 class="card-title">{{$user->observationsCounts->sum('Count')}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <i class="material-icons">pending_actions</i>
            </div>
            <p class="card-category">En attente</p>
            <h3 class="card-title">{{$user->observationsCounts->where('Etat','!=','VAL')->sum('Count')}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-danger card-header-icon">
            <div class="card-icon">
              <i class="material-icons">escalator_warning</i>
            </div>
            <p class="card-category">Convocations</p>
            <h3 class="card-title">{{$user->observationsCounts->where('Type','Convocation')->sum('Count')}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-success card-header-icon">
            <div class="card-icon">
              <i class="material-icons">emoji_events</i>
            </div>
            <p class="card-category">Appréciations</p>
            <h3 class="card-title">{{$user->observationsCounts->where('Type','Appréciation')->sum('Count')}}</h3>
          </div>

        </div>
      </div>


    </div>
    
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('js') }}/calendar.js"></script>
<script src="{{ asset('js') }}/services/teacher-services.js"></script>


<script>
  $(document).ready(function() {
      var prof=@json($user->prof);
      var evals_plans_url="{{url("/evaluations/planning/prof")}}";
      fetchRows(`${evals_plans_url}/${prof.id}`).then(
        res=>{
          var result=JSON.parse(res);

          var calendarEvents=result.evaluations
          .map(eval=>({
            day: new Date(eval.Date).getDate(),
            title: `Examen en ${eval.Matiere}`

          }))
          displayEvents('up-events-calendar',calendarEvents);


        },
        err=>{

        }
      );
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
</script>
@endpush