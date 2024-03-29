@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
use koolreport\widgets\koolphp\Table;
use koolreport\widgets\koolphp\Card;
use koolreport\widgets\google\PieChart;
use koolreport\widgets\google\ColumnChart;
use koolreport\widgets\google\LineChart;
$nbr_eleves_formation = $report['eleves_per_formation'];
$nbr_eleves_classe = $report['eleves_per_classe'];
$revenues_formation = $report['revenues_formation'];
$nbr_parents = $report['nbr_parents'];
$days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi'];

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
                            <h3 class="card-title">{{ $nbr_eleves_formation->count() }}</h3>
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
                            <h3 class="card-title">{{ $revenues_formation->sum('Total') }}</h3>
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
                            <h3 class="card-title">{{ $nbr_eleves_formation->sum('Count') }}</h3>
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
                            <h3 class="card-title">{{ $nbr_parents }}</h3>
                        </div>

                    </div>
                </div>


            </div>


            <h4> Statistiques</h4>
            <div class="row mb-3">
                <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
                    @if ($report['profs_per_formation']->count() > 0)
                        <canvas id="profs-per-formation-chart-div"></canvas>
                    @else
                        <p class="lead">Aucune donnée à afficher</p>
                    @endif


                </div>

                <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
                    <div class="h-100 d-flex flex-row justify-content-center align-items-center">
                        @if ($report['eleves_per_formation']->count() > 0)
                            <canvas id="eleves-per-formation-chart-div"></canvas>
                        @else
                            <p class="lead">Aucune donnée à afficher</p>
                        @endif

                    </div>


                </div>




            </div>

            <div class="row mb-3">
                <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
                    @if ($report['revenues_formation']->count() > 0)
                        <canvas id="revenus-per-formation-chart-div"></canvas>
                    @else
                        <p class="lead">Aucune donnée à afficher</p>
                    @endif
                </div>

                <div class="col-sm-12 col-md-6 d-flex flex-row justify-content-center align-items-center">
                    <div class="h-100 d-flex flex-row justify-content-center align-items-center">
                        @if ($report['eleves_per_classe']->count() > 0)
                            <canvas id="eleves-per-classe-chart-div"></canvas>
                        @else
                            <p class="lead">Aucune donnée à afficher</p>
                        @endif


                    </div>


                </div>




            </div>

            <div class="row mb-3">

                <div class="col-sm-12 col-md-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="w-100 d-flex flex-row justify-content-center align-items-center">
                        @if ($report['month_observations']->count() > 0)
                            <canvas id="month-observ-chart-div"></canvas>
                        @else
                            <p class="lead">Aucune donnée à afficher</p>
                        @endif

                        {{-- {{
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
          }} --}}


                    </div>


                </div>




            </div>

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js') }}/charts.js"></script>


    <script>
        $(document).ready(function() {

            // Javascript method's body can be found in assets/js/demos.js
            md.initDashboardPageCharts();

            /** 
             * Render charts
             */
            var stats = @json($report);
            console.log(stats);
            var profs_per_formation_stats = {
                    labels: [],
                    datasets: [{
                        label: '# Profs',
                        backgroundColor: '#FFB1C1',
                        data: []
                    }]
                },
                eleves_per_formation_stats = {
                    labels: [],
                    datasets: [{
                        label: '# Elèves',
                        backgroundColor: ['#9BD0F5', '#ff6384', '#36A2EB', '#FF9F40', '#9966FF'],
                        data: []
                    }]
                },
                revenus_per_formation_stats = {
                    labels: [],
                    datasets: [{
                        label: 'Revenus',
                        backgroundColor: ["#00876c", "#10689c", "#a2b997", "#dfa47e", "#d43d51"],
                        data: []
                    }]
                },
                eleves_per_classe_stats = {
                    labels: [],
                    datasets: [{
                        label: 'Elèves',
                        backgroundColor: ["#003f5c", "#f95d6a", "#2f4b7c", "#ffa600", "#665191", "#a05195",
                            "#d45087", "#ff7c43"
                        ],
                        data: []
                    }]
                },
                month_observations_stats = {
                    labels: [],
                    datasets: []
                }
            //Prepare charts data
            stats.profs_per_formation.forEach(row => {
                profs_per_formation_stats.labels.push(row.NomF)
                profs_per_formation_stats.datasets[0].data.push(row.Count)
            })
            stats.eleves_per_formation.forEach(row => {
                eleves_per_formation_stats.labels.push(row.NomF)
                eleves_per_formation_stats.datasets[0].data.push(row.Count)
            })
            stats.revenues_formation.forEach(row => {
                revenus_per_formation_stats.labels.push(row.NomF)
                revenus_per_formation_stats.datasets[0].data.push(row.Total)
            })
            stats.eleves_per_classe.forEach(row => {
                eleves_per_classe_stats.labels.push(row.NomC)
                eleves_per_classe_stats.datasets[0].data.push(row.Count)
            })
            stats.month_observations.forEach((row, index) => {
                month_observations_stats.labels.push(row.Type)
                month_observations_stats.datasets.push({
                    label: row.Type,
                    backgroundColor: ['rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ][index],
                    data: [row.Count]
                })
            })
            // Render charts
            stats.profs_per_formation.length > 0 && renderChart("profs-per-formation-chart-div", {
                title: "Répartition des enseignants par formation",
                data: profs_per_formation_stats
            })
            stats.eleves_per_formation.length > 0 && renderChart("eleves-per-formation-chart-div", {
                type: 'pie',
                title: "Répartition des élèves sur les formations",
                data: eleves_per_formation_stats
            })
            stats.revenues_formation.length > 0 && renderChart("revenus-per-formation-chart-div", {
                type: 'pie',
                title: "Revenues  des formations",
                data: revenus_per_formation_stats,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return tooltipItems.yLabel + ' DA';
                        }
                    }
                },
            })
            stats.eleves_per_classe.length > 0 && renderChart("eleves-per-classe-chart-div", {
                type: 'pie',
                title: "Répartition des élèves sur les différentes classes",
                data: eleves_per_classe_stats,
            })
            stats.month_observations.length > 0 && renderChart("month-observ-chart-div", {
                type: 'bar',
                title: "Observation ce mois-ci",
                data: month_observations_stats,
                barThickness: '100'
            })
        });
    </script>
@endpush
