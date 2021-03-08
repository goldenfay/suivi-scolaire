@extends('layouts.app', ['activePage' => 'enseignament', 'titlePage' => __('Correspondances & Observations')])

<?php
function badge_class($etat){

switch($etat){
  case "NV": return "badge-danger";
  case "V": return "badge-dark";
  case "ATV": return "badge-warning";
  case "VAL": return "badge-success";
  default: return "";

}
}
?>
@section('content')
<div class="content">

  <div class="container-fluid">
    <div class="row">

      <div class="col-sm-12 d-flex align-items-center justify-content-center">
        <h5 class="h2 text-warning"> {{$eleve->Nom}} {{$eleve->Prenom}}</h5>
      </div>
    </div>
    <div class="row my-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center ">Cahier de correspondance</h5>

          <div class="col-sm-12">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Titre</th>
                  <th class="text-center">Contenu</th>
                  <th class="text-center">Etat</th>
                </tr>
              </thead>
              <tbody>
                @if(!reset($observations))
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune observation</h4>
                  </td>
                </tr>
                @endif
                @foreach ($observations as $idx => $observation)
                <tr>
                  <td class="text-center">{{$idx+1}}</td>
                  <td>{{$observation->Date}}</td>
                  <td>{{$observation->Type}}</td>
                  <td>{{$observation->Libelle}}</td>
                  <td>{{$observation->Corps}}</td>
                  <td class="d-flex justify-content-center badge badge-pill {{badge_class($observation->Etat)}}">{{
                          ($observation->Etat=="NV"? "Non consultée":
                          ($observation->Etat=="V"? "Consultée":
                          ($observation->Etat=="ATV"? "En attente de validation":
                          ($observation->Etat=="VAL"? "Validée":$observation->Etat))) )
                          }}</td>

                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Rédiger une nouvelle observation</h5>
          <div class="col-sm-12 mt-5">
            <div class="d-flex flex-row justify-content-center">

              @if(session('flag'))
              @if(session('flag')=='fail')
              <div class="col-md-4">
                <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
                  <i class="material-icons" data-notify="icon">error</i>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>{{session('message')}}</span>
                </div>
              </div>
              @else
              @if(session('flag')=='success')
              <div class="col-md-4">
                <div class="alert alert-success alert-with-icon w-60" data-notify="container">
                  <i class="material-icons" data-notify="icon">check_circle</i>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>{{session('message')}}</span>
                </div>
              </div>
              @endif

              @endif

              @endif
            </div>
          </div>
          <div class="col-sm-12">
            <form method="POST" action="{{url("observations/add")}}" id="addObservation-form">
              @csrf
              {{-- @method('POST') --}}
              {{-- <input type="hidden" name="_method" value="POST"> --}}
              <input type="hidden" value="{{$eleve->id}}" name="eleveId" />
              <input type="hidden" value="{{$user->prof->id}}" name="profId" />
              <div class="form-row my-3 d-flex align-items-end">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="type-input">Type</label>
                  <select id="type-input" name="type" class="form-control">
                    <option value="Discipline">Discipline</option>
                    <option value="Appréciation">Appréciation</option>
                    <option value="Information">Information</option>
                    <option value="Convocation">Convocation</option>
                  </select>
                  @if ($errors->has('type'))
                  <div id="type-error" class="error text-danger pl-3" for="type" style="display: block;">
                    <strong>{{ $errors->first('type') }}</strong>
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="libelle-input">Titre</label>
                  <input type="text" class="form-control" name="libelle" id="libelle-input"
                    placeholder="Titre de la communication ...">
                  @if ($errors->has('libelle'))
                  <div id="libelle-error" class="error text-danger pl-3" for="libelle" style="display: block;">
                    <strong>{{ $errors->first('libelle') }}</strong>
                  </div>
                  @endif
                </div>


              </div>

              <div class="form-group my-3">
                <label for="contenu-input">Contenu</label>
                <textarea type="text" class="form-control" name="corps" id="contenu-input"
                  placeholder="Spécifiez le corps de votre correspondance ..."></textarea>
                @if ($errors->has('corps'))
                <div id="corps-error" class="error text-danger pl-3" for="corps" style="display: block;">
                  <strong>{{ $errors->first('corps') }}</strong>
                </div>
                @endif
              </div>


              <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>


          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
  $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
   
</script>
@endpush