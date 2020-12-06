@extends('layouts.app', ['activePage' => 'reports', 'titlePage' => __('Reports')])
<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\Card;
use \koolreport\widgets\google\PieChart;
$totals_this_month=$report->dataStore("totals_this_month")->first(function($row){ return True;});
        $totals_last_month=$report->dataStore("totals_last_month")->first(function($row){ return True;});
?>
@section('content')
<div class="content">
  <div class="container-fluid">
   
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="fas fa-user-graduate"></i>
            </div>
            <p class="card-category">Total des élèves</p>
            <h3 class="card-title">{{$report->dataStore('all_ecoles')->sum('total_eleves')}}
              <small>Elèves</small>
              
            </h3>
          </div>
          
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-success card-header-icon">
            <div class="card-icon">
              <i class="material-icons">attach_money</i>
            </div>
            <p class="card-category">Total des revenues</p>
            <h3 class="card-title">{{$report->dataStore('all_ecoles')->sum('total_revenues')}}
              <small>DA</small>
            </h3>
          </div>
          
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="fas fa-school"></i>
            </div>
            <p class="card-category">Nombre d'établissements</p>
            <h3 class="card-title">{{$report->dataStore('all_ecoles')->count()}}
              <small>Ecoles</small>
            </h3>
          </div>
          
        </div>
      </div>
      
      
    </div>
    <h4> Ce mois-ci</h4>
        <div class="row">
            <div class="col-sm-6">
                {{

                  Card::create([
                          "title"=>"Nouvels eleves",
                          "value"=>$totals_this_month["total_eleves"],
                          "baseValue"=>$totals_last_month["total_eleves"]
                          
                          ])
                          
                }} 
                
            </div>
            <div class="col-sm-6">
                {{
                  Card::create([
                      "title"=>"Revenues",
                      "value"=>$totals_this_month["total_revenues"],
                          "baseValue"=>$totals_last_month["total_revenues"]
                          
                          ])
                          

                }} 
                
            </div>
           
        </div>




    <h4 class="card-title">Récapitulatif des établissements</h4>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Nombre d'élèves et revenues pour chaque établissement</h5>
  
          </div>
          <div class="card-body">
            {{$report->render() }}
          </div>
        </div>
      </div>
    </div>
    <h4>Vue graphique</h4>
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Répartition des èlèves sur les établissements</h4>
  
          </div>
          <div class="card-body">
            {{PieChart::create(array(
              "dataSource"=>$report->dataStore('all_ecoles'),
              "columns"=>array("nom_ecole","total_eleves")
          )) }}
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Répartition des èlèves sur les établissements</h4>
  
          </div>
          <div class="card-body">
            {{PieChart::create(array(
              "dataSource"=>$report->dataStore('all_ecoles'),
              "columns"=>array("nom_ecole","total_eleves")
          )) }}
          </div>
        </div>
      </div>
    </div>
    
    
  </div>
</div>
  </div>
</div>
@endsection