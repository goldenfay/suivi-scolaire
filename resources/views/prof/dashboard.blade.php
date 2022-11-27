@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])

<?php
use koolreport\widgets\koolphp\Table;
use koolreport\widgets\koolphp\Card;
use koolreport\widgets\google\PieChart;
$nbr_eleves_formation = $report->dataStore('nbr_eleves_formation');
$nbr_eleves_classe = $report->dataStore('nbr_eleves_classe');
$revenues_formation = $report->dataStore('revenues_formation');

$days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi'];
$hours = ['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '13:30-14:30', '14:30-15:30', '15:30-16:30'];

?>
@section('content')
    @push('styles')
        <link href="{{ asset('css') . '/calendar.css' }}" rel="stylesheet">
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
                            <h3 class="card-title">{{ $nbr_eleves_formation->count() }}</h3>
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
                            <h3 class="card-title">{{ $nbr_eleves_formation->sum('Count') }}</h3>
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
                            <h3 class="card-title">{{ $revenues_formation->sum('Total') }}</h3>
                        </div>

                    </div>
                </div>


            </div>


            {{-- Formations statistics section --}}
            <h4> Formations</h4>
            <div class="row mb-3">
                <div class="col-sm-12 col-md-6">
                    @if ($nbr_eleves_formation->count() > 0)
                        <canvas id="eleves-per-formation-chart-div"></canvas>
                    @else
                        <p class="lead">Aucune donnée à afficher</p>
                    @endif

                    {{-- {{ PieChart::create([
                        'title' => 'Répartition des élèves sur les formations',
                        'dataSource' => $report->dataStore('nbr_eleves_formation'),
                        'columns' => [
                            'NomF' => ['label' => 'Nom Formation'],
                            'Count',
                        ],
                    ]) }} --}}
                </div>

                <div class="col-sm-12 col-md-6">

                    {{ PieChart::create([
                        'title' => 'Revenues  des formations',
                        'dataSource' => $report->dataStore('revenues_formation'),
                        'columns' => [
                            'NomF' => ['label' => 'Nom Formation'],
                            'Total' => ['suffix' => 'DA'],
                        ],
                        'colorScheme' => ['#00876c', '#10689c', '#a2b997', '#dfa47e', '#d43d51'],
                    ]) }}
                </div>




            </div>


            {{-- Classes statistics section --}}
            <h4> Classes</h4>
            <div class="row mb-3">
                <div class="col-sm-12 col-md-6">

                    {{ PieChart::create([
                        'title' => 'Répartition des élèves sur les différentes classes',
                        'dataSource' => $report->dataStore('nbr_eleves_classe'),
                        'columns' => [
                            'NomC' => ['label' => 'Classe'],
                            'Count',
                        ],
                        'colorScheme' => ['#003f5c', '#f95d6a', '#2f4b7c', '#ffa600', '#665191', '#a05195', '#d45087', '#ff7c43'],
                    ]) }}
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="h-100 d-flex flex-row justify-content-center align-items-center">

                        {{-- <h5 class="card-title">Calendrier des évaluations</h5> --}}
                        <div class="calendar-container calendar-box jzdbasf light-orange-bg mt-2" id="up-events-calendar">

                            <div class="jzdcalt">{{ date('F, Y') }} </div>
                            <span>Ven</span>
                            <span>Sam</span>
                            @foreach ($days as $day)
                                <span>{{ substr($day, 0, 3) }}</span>
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
                            <h3 class="card-title">{{ $user->observationsCounts->sum('Count') }}</h3>
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
                            <h3 class="card-title">{{ $user->observationsCounts->where('Etat', '!=', 'VAL')->sum('Count') }}
                            </h3>
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
                            <h3 class="card-title">
                                {{ $user->observationsCounts->where('Type', 'Convocation')->sum('Count') }}</h3>
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
                            <h3 class="card-title">
                                {{ $user->observationsCounts->where('Type', 'Appréciation')->sum('Count') }}</h3>
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
            var prof = @json($user->prof);
            var evals_plans_url = "{{ url('/evaluations/planning/prof') }}";
            fetchRows(`${evals_plans_url}/${prof.id}`).then(
                res => {
                    var result = JSON.parse(res);

                    var calendarEvents = result.evaluations
                        .map(eval => ({
                            day: new Date(eval.Date).getDate(),
                            title: `Examen en ${eval.Matiere}`

                        }))
                    displayEvents('up-events-calendar', calendarEvents);


                },
                err => {

                }
            );
            // Javascript method's body can be found in assets/js/demos.js
            md.initDashboardPageCharts();
        });
    </script>
@endpush
@push('js')
    <script src="{{ asset('js') }}/charts.js"></script>


    <script>
        $(document).ready(function() {

            /** 
             * Render charts
             */
            var stats = {
                eleves_per_formation: @json($nbr_eleves_formation->toJson())
            };
            console.log(stats)
            var
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
            stats.eleves_per_formation.forEach(row => {
                eleves_per_formation_stats.labels.push(row.NomF)
                eleves_per_formation_stats.datasets[0].data.push(row.Count)
            })
            // stats.revenues_formation.forEach(row => {
            //     revenus_per_formation_stats.labels.push(row.NomF)
            //     revenus_per_formation_stats.datasets[0].data.push(row.Total)
            // })
            // stats.eleves_per_classe.forEach(row => {
            //     eleves_per_classe_stats.labels.push(row.NomC)
            //     eleves_per_classe_stats.datasets[0].data.push(row.Count)
            // })
            // stats.month_observations.forEach((row, index) => {
            //     month_observations_stats.labels.push(row.Type)
            //     month_observations_stats.datasets.push({
            //         label: row.Type,
            //         backgroundColor: ['rgb(255, 99, 132)',
            //             'rgb(255, 159, 64)',
            //             'rgb(255, 205, 86)',
            //             'rgb(75, 192, 192)',
            //             'rgb(54, 162, 235)',
            //             'rgb(153, 102, 255)',
            //             'rgb(201, 203, 207)'
            //         ][index],
            //         data: [row.Count]
            //     })
            // })
            // Render charts
            stats.eleves_per_formation.length > 0 && renderChart("eleves-per-formation-chart-div", {
                type: 'pie',
                title: "Répartition des élèves sur les formations",
                data: eleves_per_formation_stats
            })
            // stats.revenues_formation.length > 0 && renderChart("revenus-per-formation-chart-div", {
            //     type: 'pie',
            //     title: "Revenues  des formations",
            //     data: revenus_per_formation_stats,
            //     tooltips: {
            //         callbacks: {
            //             label: function(tooltipItems, data) {
            //                 return tooltipItems.yLabel + ' DA';
            //             }
            //         }
            //     },
            // })
            // stats.eleves_per_classe.length > 0 && renderChart("eleves-per-classe-chart-div", {
            //     type: 'pie',
            //     title: "Répartition des élèves sur les différentes classes",
            //     data: eleves_per_classe_stats,
            // })
            // stats.month_observations.length > 0 && renderChart("month-observ-chart-div", {
            //     type: 'bar',
            //     title: "Observation ce mois-ci",
            //     data: month_observations_stats,
            //     barThickness: '100'
            // })
        });
    </script>
@endpush
