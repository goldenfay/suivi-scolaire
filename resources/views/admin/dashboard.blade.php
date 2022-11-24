@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\Card;
use \koolreport\widgets\google\PieChart;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\widgets\google\LineChart;
$nbr_eleves_formation=$report->dataStore("nbr_eleves_formation");
$nbr_eleves_classe=$report->dataStore("nbr_eleves_classe");
$revenues_formation=$report->dataStore("revenues_formation");
$nbr_parents=$report->dataStore("nbr_parents");
$days=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];

?>
@section('content')

<div class="content">
  <div class="container-fluid">
    <div class="row my-3">
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <p class="card-category">Formations fournies</p>
            <h3 class="card-title">{{$nbr_eleves_formation->count()}}</h3>
          </div>
          
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
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
      <div class="col-lg-3 col-md-3 col-sm-6">
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
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="material-icons">escalator_warning</i>
            </div>
            <p class="card-category">Parents Inscrits</p>
            <h3 class="card-title">{{$nbr_parents->sum('Count')}}</h3>
          </div>

        </div>
      </div>


    </div>


    <h4> Statistiques</h4>
    <div class="row mb-3">
      <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
        {{$report->dataStore("nbr_prof_formation")->count()}}
        @if($report->dataStore("nbr_prof_formation")->count()>0)
        {{
          ColumnChart::create(array(
          "title"=>"Répartition des enseignants par formation",
          "dataSource"=>$report->dataStore("nbr_prof_formation"),
          "columns"=>array(
              "NomF"=>array("label"=>"Formation"),
              "Count"
        )
        ))
        }}
        @else
        <h5 class="text-muted">Aucune donnée à afficher</h5>
        @endif


      </div>

      <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
        <div class="h-100 d-flex flex-row justify-content-center align-items-center">

          {{PieChart::create(array(
            "title"=>"Répartition des élèves sur les formations",
            "dataSource"=>$report->dataStore('nbr_eleves_formation'),
            "columns"=>array(
              "NomF"=>array("label"=>"Nom Formation"),
              "Count")
            )) 
          }}


        </div>


      </div>




    </div>

    <div class="row mb-3">
      <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">

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

      <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
        <div class="h-100 d-flex flex-row justify-content-center align-items-center">

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


      </div>




    </div>

    <div class="row mb-3">
      {{-- <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
        {{PieChart::create(array(
          "title"=>"Répartition des élèves sur les formations",
          "dataSource"=>$report->dataStore('nbr_eleves_formation'),
          "columns"=>array(
            "NomF"=>array("label"=>"Nom Formation"),
            "Count")
          )) 
        }}

        
      </div> --}}

      <div class="col-sm-12 col-md-12 d-flex flex-row justify-content-center align-items-center" >
        {{-- <div class="w-100 d-flex flex-row justify-content-center align-items-center"> --}}

          {{
            LineChart::create(array(
              "title"=>"Total des correspondances entre parents/prof par mois",
          "dataSource"=>$report->dataStore('nbr_observ_month'),
          "columns"=>array(
            "Mois"=>array("label"=>"Mois"),
            "Count"),
              "options"=>array(
                  "curveType"=>"function"
              )
          ))
          }}


        {{-- </div> --}}


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
      
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
</script>
@endpush