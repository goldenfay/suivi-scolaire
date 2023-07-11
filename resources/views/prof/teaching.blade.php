@extends('layouts.app', ['activePage' => 'enseignement', 'titlePage' => __('Enseignement')])

<?php
$days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi'];
$hours = ['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '13:30-14:30', '14:30-15:30', '15:30-16:30'];
$epreuveTypes = ['interrogation', 'test', 'devoir', 'examen'];
function badge_class($etat)
{
    switch ($etat) {
        case 'NV':
            return 'badge-danger';
        case 'V':
            return 'badge-warning';
        case 'ATV':
            return 'badge-default';
        case 'VAL':
            return 'badge-success';
        default:
            return '';
    }
}

?>
@section('content')
    @push('styles')
        <link href="{{ asset('css') . '/calendar.css' }}" rel="stylesheet">
        {{-- <link rel="stylesheet" href="{{ asset('propeller')."/css/propeller.css" }}">
<link rel="stylesheet" href="{{ asset('propeller')."/css/propeller.min.css" }}"> --}}
    @endpush
    <div class="content">
        <div class="container-fluid">
            @if (!$currentClasse)
                <div class="row">
                    <div class="col-md-12 d-flex flex-column justify-content-center">
                        <h1 class="text-secondary text-center"> <i class="material-icons"style="font-size: 4rem;">work_off</i>
                        </h1>
                        <h2 class="text-secondary text-center">

                            Vous n'êtes affectés à aucune classe pour le moment.
                        </h2>
                    </div>
                </div>
            @else
                <div class="row my-4">
                    <div class="col-sm-4 d-flex align-items-center">
                        <h5> Classe :</h5>
                    </div>
                    <div class="col-sm-8">
                        <form id="change_eleve_form" method="GET" class="form-row">
                            <div class="form-group col-md-8">
                                <label for="exampleFormControlSelect1">Spécifiez la classe à gérer</label>
                                <select class="form-control" {{-- value="{{property_exists($currentClasse->classe,"id")?$currentClasse->classe->id."": "pop"}}" --}} data-style="btn btn-link"
                                    id="exampleFormControlSelect1">
                                    @foreach ($user->classes as $classe)
                                        <option value="{{ $classe->classe->id }}"
                                            {{ $classe->classe->id === $currentClasse->classe->id ? 'selected' : '' }}>
                                            <a href="{{ route('prof.enseignement') }}/{{ $classe->classe->id }}">{{ $classe->classe->Des }}
                                            </a>
                                        </option>
                                    @endforeach


                                </select>

                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary">Consulter</button>

                            </div>

                        </form>
                    </div>
                </div>
                {{-- Students lsisting --}}
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
                                            @if (!reset($currentClasse->eleves))
                                                <tr>
                                                    <td colspan="8">
                                                        <h4 class="text-secondary text-center"> Aucun élève n'est inscrit
                                                            dans
                                                            cette classe</h4>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($currentClasse->eleves as $eleve)
                                                    <tr>
                                                        <td class="text-center">{{ $eleve->Code }}</td>
                                                        <td>{{ $eleve->Nom }}</td>
                                                        <td>{{ $eleve->Prenom }}</td>
                                                        <td>{{ $eleve->Age }}</td>
                                                        <td class="text-center">
                                                            @if (array_key_exists($eleve->id, $user->observations_per_eleve))
                                                                <span
                                                                    class="badge badge-warning">{{ $user->observations_per_eleve[$eleve->id] }}
                                                                </span>
                                                                évènements non validés
                                                            @endif


                                                        </td>
                                                        <td class="td-actions text-center">
                                                            <a href="{{ route('viewEleve', $eleve->id) }}">
                                                                <button type="button" rel="tooltip" data-toggle="tooltip"
                                                                    data-placement="top" title="Consulter"
                                                                    class="btn btn-round btn-info">
                                                                    <i class="material-icons">person</i>
                                                                </button>
                                                            </a>
                                                            <a href="{{ route('prof.correspondance', $eleve->id) }}">
                                                                <button type="button" rel="tooltip" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="Ecrire dans son cahier de correpondance"
                                                                    class="btn btn-round btn-primary" {{-- onclick="alert({{$eleve->id}})" --}}>
                                                                    <i class="material-icons">menu_book</i>

                                                                </button>
                                                            </a>

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

                {{-- Evaluations & exams planning section --}}
                <div class="row">

                    <div class="col-sm-8">
                        <div class="d-flex flex-row justify-content-center">
                            @if (session('flag'))
                                @if (session('flag') == 'fail')
                                    <div class="col-md-10">
                                        <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
                                            <i class="material-icons" data-notify="icon">error</i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span>{{ session('message') }}</span>
                                        </div>
                                    </div>
                                @else
                                    @if (session('flag') == 'success')
                                        <div class="col-md-10">
                                            <div class="alert alert-success alert-with-icon w-60" data-notify="container">
                                                <i class="material-icons" data-notify="icon">check_circle</i>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <span>{{ session('message') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            @endif
                        </div>
                        <h4>Planifier une épreuve</h4>
                        <div class="card">
                            <div class="card-body">

                                <form method="POST" action="{{ url('evaluations/planning/add') }}"
                                    id="addObservation-form">
                                    @csrf

                                    <input type="hidden" value="{{ $user->prof->id }}" name="profId" />
                                    <input type="hidden" value="{{ $currentClasse->classe->id }}" name="classeId" />
                                    <div class="form-row my-3 d-flex align-items-end">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="matiere-input">Matiere</label>
                                            <select id="matiere-input" name="matiere" class="form-control">

                                            </select>
                                            @if ($errors->has('matiere'))
                                                <div id="matiere-error" class="error text-danger pl-3" for="matiere"
                                                    style="display: block;">
                                                    <strong>{{ $errors->first('matiere') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="type-input">Type d'épreuve</label>
                                            <select id="type-input" name="type" class="form-control">
                                                @foreach ($epreuveTypes as $type)
                                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                                @endforeach

                                            </select>
                                            @if ($errors->has('matiere'))
                                                <div id="type-error" class="error text-danger pl-3" for="type-input"
                                                    style="display: block;">
                                                    <strong>{{ $errors->first('type') }}</strong>
                                                </div>
                                            @endif
                                        </div>



                                    </div>
                                    <div class="form-row my-3 d-flex align-items-end">
                                        <div class="form-group col-sm-12">
                                            <label for="titre-input">Titre</label>
                                            <input type="text" class="form-control" name="titre" id="titre-input"
                                                placeholder="Titre de l'épreuve ...">
                                            @if ($errors->has('titre'))
                                                <div id="titre-error" class="error text-danger pl-3" for="titre"
                                                    style="display: block;">
                                                    <strong>{{ $errors->first('titre') }}</strong>
                                                </div>
                                            @endif
                                        </div>


                                    </div>

                                    <div class="form-group my-3">
                                        <label for="contenu-input">Date</label>
                                        <div
                                            class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                            <input type="date" class="form-control" name="date" id="timepicker">
                                        </div>
                                        @if ($errors->has('date'))
                                            <div id="date-error" class="error text-danger pl-3" for="date"
                                                style="display: block;">
                                                <strong>{{ $errors->first('date') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-row my-3">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="heure-input">Heure</label>
                                            <select id="heure-input" name="heure" class="form-control">
                                                @foreach ($hours as $hour)
                                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                                @endforeach

                                            </select>
                                            @if ($errors->has('heure'))
                                                <div id="heure-error" class="error text-danger pl-3" for="heure"
                                                    style="display: block;">
                                                    <strong>{{ $errors->first('heure') }}</strong>
                                                </div>
                                            @endif
                                        </div>


                                    </div>


                                    <button type="submit" class="btn btn-primary">Planifier</button>
                                </form>
                            </div>

                        </div>

                    </div>
                    <div class="col-sm-4">
                        <h4>Calendrier</h4>
                        <div class="calendar-container calendar-box jzdbasf mt-3" id="up-events-calendar">

                            <div class="jzdcalt">{{ date('F, Y') }} </div>
                            <span>Ven</span>
                            <span>Sam</span>
                            @foreach ($days as $day)
                                <span>{{ substr($day, 0, 3) }}</span>
                            @endforeach

                        </div>

                    </div>
                </div>

                {{-- Evants planification section --}}
                <div class="row">

                    <div class="col-sm-8">
                        <div class="d-flex flex-row justify-content-center">
                            @if (session('event-flag'))
                                @if (session('event-flag') == 'fail')
                                    <div class="col-md-10">
                                        <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
                                            <i class="material-icons" data-notify="icon">error</i>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <span>{{ session('event-message') }}</span>
                                        </div>
                                    </div>
                                @else
                                    @if (session('event-flag') == 'success')
                                        <div class="col-md-10">
                                            <div class="alert alert-success alert-with-icon w-60" data-notify="container">
                                                <i class="material-icons" data-notify="icon">check_circle</i>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <span>{{ session('event-message') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            @endif
                        </div>
                        <h4>Planifier un évènement spécifique</h4>
                        <div class="card">
                            <div class="card-body">

                                <form method="POST" action="{{ url('events/planning/add') }}" id="addEventform">
                                    @csrf

                                    <input type="hidden" value="{{ $user->prof->id }}" name="profId" />
                                    <input type="hidden" value="{{ $currentClasse->classe->id }}" name="classeId" />
                                    <div class="form-row my-3">

                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="titre-input">Titre</label>
                                            <input type="text" class="form-control" name="event-titre"
                                                id="titre-input" placeholder="Titre de l'évènement' ...">
                                            @if ($errors->has('event-titre'))
                                                <div id="titre-error" class="error text-danger pl-3" for="event-titre"
                                                    style="display: block;">
                                                    <strong>{{ $errors->first('event-titre') }}</strong>
                                                </div>
                                            @endif
                                        </div>


                                    </div>

                                    <div class="form-group my-3">
                                        <label for="event-input">Date</label>
                                        <div
                                            class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
                                            <input type="date" class="form-control" name="event-date"
                                                id="event-input">
                                        </div>
                                        @if ($errors->has('event-date'))
                                            <div id="corps-error" class="error text-danger pl-3" for="event-date"
                                                style="display: block;">
                                                <strong>{{ $errors->first('event-date') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-row my-3">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="event-heure-input">Heure</label>
                                            <select id="event-heure-input" name="event-heure" class="form-control">
                                                @foreach ($hours as $hour)
                                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                                @endforeach

                                            </select>
                                            @if ($errors->has('event-heure'))
                                                <div id="event-heure-error" class="error text-danger pl-3"
                                                    for="event-heure" style="display: block;">
                                                    <strong>{{ $errors->first('event-heure') }}</strong>
                                                </div>
                                            @endif
                                        </div>


                                    </div>


                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>
            @endif
        </div>
        <script>
            document.getElementById('change_eleve_form').addEventListener('submit', (e) => {
                e.preventDefault();

                const destination = "{{ route('prof.enseignement') }}/" +
                    encodeURIComponent(document.getElementById('exampleFormControlSelect1').value);
                if (window.location.href === destination) return
                window.location = destination;

            });
        </script>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js') }}/services/teacher-services.js"></script>
    <script src="{{ asset('js') }}/calendar.js"></script>

    @if($currentClasse)
    <script>
        $(document).ready(function() {
            if (!@json($currentClasse)) return;
            var currentClass= @json($currentClasse);
            var prof = @json($user->prof);
            var classe = @json($currentClasse->classe);
            var evals_plans_url = "{{ url('/evaluations/planning/classe') }}";
            fetchRows(`${evals_plans_url}/${classe.id}/${prof.id}`).then(
                res => {
                    var result = JSON.parse(res);

                    var calendarEvents = result.evaluations
                        .map(eval => ({
                            day: new Date(eval.Date).getDate(),
                            title: `${eval.Type || "Examen"} en ${eval.Matiere}`

                        })).concat(result.events
                            .map(event => ({
                                day: new Date(event.Date).getDate(),
                                title: `${event.Titre}`

                            })))

                    displayEvents('up-events-calendar', calendarEvents);


                },
                err => {

                }
            );



            $.ajax({
                url: "{{ url('/matieres') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },


                success: res => {

                    var result = JSON.parse(res);


                    var matieresSelect = document.getElementById('matiere-input');
                    matieresSelect.innerHTML = `
                          ${result.matieres.map(matiere=>`
                                      <option value="${matiere.id}">${matiere.Des}</option>
                                    `).join('')} `

                },
                error: err => {
                    console.log("Error fetching matieres", err);

                }


            })

            //     $('#timepicker').datetimepicker({
            // 	format: 'LT'
            // });
            // Javascript method's body can be found in assets/js/demos.js
            md.initDashboardPageCharts();
        });
    </script>
    @endif
@endpush
