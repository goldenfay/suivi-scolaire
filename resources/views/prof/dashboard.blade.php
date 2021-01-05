@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\koolphp\Card;
use \koolreport\widgets\google\PieChart;
$nbr_eleves_formation=$report->dataStore("nbr_eleves_formation");
$nbr_eleves_classe=$report->dataStore("nbr_eleves_classe");
$revenues_formation=$report->dataStore("revenues_formation");
// dd($report->dataStore('nbr_eleves_formation')->sum("Count"));
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
    {{-- 
      <div class="row">
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-success">
              <div class="ct-chart" id="dailySalesChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Daily Sales</h4>
              <p class="card-category">
                <span class="text-success"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in today sales.</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> updated 4 minutes ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-warning">
              <div class="ct-chart" id="websiteViewsChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Email Subscriptions</h4>
              <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-danger">
              <div class="ct-chart" id="completedTasksChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Completed Tasks</h4>
              <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header card-header-tabs card-header-primary">
              <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                  <span class="nav-tabs-title">Tasks:</span>
                  <ul class="nav nav-tabs" data-tabs="tabs">
                    <li class="nav-item">
                      <a class="nav-link active" href="#profile" data-toggle="tab">
                        <i class="material-icons">bug_report</i> Bugs
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#messages" data-toggle="tab">
                        <i class="material-icons">code</i> Website
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#settings" data-toggle="tab">
                        <i class="material-icons">cloud</i> Server
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane active" id="profile">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="" checked>
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Sign contract for "What are conference organizers afraid of?"</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="">
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="">
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                        </td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="" checked>
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Create 4 Invisible User Experiences you Never Knew About</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="messages">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="" checked>
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                        </td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="">
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Sign contract for "What are conference organizers afraid of?"</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="settings">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="">
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="" checked>
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                        </td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="" checked>
                              <span class="form-check-sign">
                                <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </td>
                        <td>Sign contract for "What are conference organizers afraid of?"</td>
                        <td class="td-actions text-right">
                          <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                            <i class="material-icons">edit</i>
                          </button>
                          <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </td>
                      </tr>
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
              <h4 class="card-title">Employees Stats</h4>
              <p class="card-category">New employees on 15th September, 2016</p>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-hover">
                <thead class="text-warning">
                  <th>ID</th>
                  <th>Name</th>
                  <th>Salary</th>
                  <th>Country</th>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Dakota Rice</td>
                    <td>$36,738</td>
                    <td>Niger</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Minerva Hooper</td>
                    <td>$23,789</td>
                    <td>Curaçao</td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Sage Rodriguez</td>
                    <td>$56,142</td>
                    <td>Netherlands</td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>Philip Chaney</td>
                    <td>$38,735</td>
                    <td>Korea, South</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> --}}
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