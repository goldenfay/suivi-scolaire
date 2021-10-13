@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Tableau de bord')])
<?php 
$days=["Dimanche","Lundi","Mardi","Mercredi","Jeudi"];

?>
@section('content')
@push('styles')
<link href="{{ asset('css')."/calendar.css" }}" rel="stylesheet">

@endpush
<div class="content">
  <div class="container-fluid">
    <div class="row my-3">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">card_membership</i>
            </div>
            <p class="card-category">Formations En Total</p>
            <h3 class="card-title">{{$nbr_formations}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <i class="material-icons">history_edu</i>
            </div>
            <p class="card-category">Correspondence cette semaine</p>
            <h3 class="card-title">{{$week_observations->sum('Count')}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-success card-header-icon">
            <div class="card-icon">
              <i class="material-icons">event</i>
            </div>
            <p class="card-category">Devoirs prochainnement</p>
            <h3 class="card-title">{{$upcomming_evals->count()}}</h3>
          </div>

        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="material-icons">hourglass_top</i>
            </div>
            <p class="card-category">Evènnements en attente</p>
            <h3 class="card-title">{{$pending_observations}}</h3>
            {{-- <h3 class="card-title">{{$week_observations->where('Etat','!=','VAL')->sum('Count')}}</h3> --}}
          </div>

        </div>
      </div>

    </div>
    {{-- Exams & remarks summary section --}}
    <h4>Epreuves Et Remarques</h4>
    <div class="row my-3">
      <div class="col-sm-12 col-md-6">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title">Sommaire Des Correspondances Cette Semaine</h4>
            <p class="card-category"></p>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-hover">
              <tbody>
                @if(!($week_observations->count()))
                    <tr>
                      <td colspan="8">
                        <h4 class="text-secondary text-center"> Aucune observation pour cette semaine</h4>
                      </td>
                    </tr>
                @endif
                @foreach ($week_observations as $observation)
                <tr>
                  <td>{{$observation->Type}}</td>
                  <td>{{$observation->Count}}</td>
                  <td>{{
                        ($observation->Etat=="NV"? "Non consultée(s)":
                        ($observation->Etat=="V"? "Consultée(s)":
                        ($observation->Etat=="ATV"? "En attente de validation":
                        ($observation->Etat=="VAL"? "Validée(s)":$observation->Etat))) )
                        }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="col-sm-12 col-md-6">


        <div>
          <div class="card calendar-container calendar-box no-bg-calendar jzdbasf mt-2" id="up-events-calendar">

            <div class="card-header card-header-warning" style="margin-top: 0%">
              <div class="d-flex flex-column justify-content-center">

                <h4 class="card-title text-center">Calendrier</h4>
              </div>
            </div>
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

    {{-- Children summary --}}
    <h4> Vos enfants</h4>
    <div class="row">
      @foreach ($user->children as $key => $child)

      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <img
                    src="{{__($child->eleve->Pic_Path!=null?$child->eleve->Pic_Path: ($child->eleve->Civilite==1 ?asset('assets')."/autres/default-eleve-avatar-male.jpg" : asset('assets')."/autres/default-eleve-avatar-female.jpg" ))}}"
                    class="img-fluid img-round" alt="" />

                </div>
                <div class="col-md-6">
                  <div class="d-flex flex-row align-items-center justify-content-center h-100">
                    <h3 align="center">{{$child->eleve->Nom}} {{$child->eleve->Prenom}}</h3>

                  </div>

                </div>
              </div>

            </div>

          </div>
          <div class="card-body">
            <div class="container">
              @foreach (["Nom","Prenom","Age","Date_Naissance","Adresse","Maladie"] as $attr)
              <div class="row my-2">
                <div class="col-md-6">
                  <caption class="text-center">{{$attr}}</caption>

                </div>
                <div class="col-md-6">
                  <caption class="text-center text-primary">{{$child->eleve->$attr}} </caption>

                </div>
              </div>

              @endforeach
              <div class="row my-2">
                <div class="col-md-6">
                  <caption class="text-center">Classes</caption>

                </div>
                <div class="col-md-6">
                  @foreach ($child->classes as $classe)
                  <caption class="text-center text-primary mx-auto">{{$classe->Des}} </caption>
                  @endforeach

                </div>
              </div>
              <div class="row my-2">
                <div class="col-md-6">
                  <caption class="text-center">Formations</caption>

                </div>
                <div class="col-md-6">
                  @foreach ($child->formations as $formation)
                  <caption class="text-center text-primary mx-auto">{{$formation->Des}} </caption>
                  @endforeach

                </div>
              </div>


            </div>

          </div>
          <div class="card-footer">
            <div class="stats float-right">
              <a href="#">Consulter</a>
            </div>
          </div>
        </div>




      </div>
      @endforeach



    </div>

  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('js') }}/calendar.js"></script>


<script>
  $(document).ready(function() {

      var calendarEvents=@json($upcomming_evals);
      calendarEvents=Object.values(calendarEvents).map(eval=>({
            day: new Date(eval.Date).getDate(),
            title: `Examen en ${eval.Matiere}`

          })).concat(Object.values(@json($upcomming_events))
          .map(event=>({
            day: new Date(event.Date).getDate(),
            title: `${event.Titre}`

          })));

      displayEvents('up-events-calendar',calendarEvents);
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
</script>
@endpush