@extends('layouts.app', ['activePage' => 'eleves', 'titlePage' => __('Elèves & Classes')])
<?php
setlocale(LC_TIME, "fr_FR");

// dd($parents->first()->enfants());


?>
@section('content')

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div style="position: sticky; bottom: 5%; left: 0;right: 0; width: 100% ;min-height:50px">
        <div class="d-flex flex-row justify-content-center" id="account-actions-results">

        </div>

      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 mt-5">
        <div class="d-flex flex-row justify-content-center">

          @if(session('register-flag'))
          @if(session('register-flag')=='fail')
          <div class="col-md-4">
            <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
              <i class="material-icons" data-notify="icon">error</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>{{session('register-message')}}</span>
            </div>
          </div>
          @else
          @if(session('register-flag')=='success')
          <div class="col-md-4">
            <div class="alert alert-success alert-with-icon w-60" data-notify="container">
              <i class="material-icons" data-notify="icon">check_circle</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>{{session('register-message')}}</span>
            </div>
          </div>
          @endif

          @endif

          @endif
        </div>
      </div>
    </div>

    <div class="row">
      <div style="position: sticky; bottom: 5%; left: 0;right: 0; width: 100% ;min-height:50px">
        <div class="d-flex flex-row justify-content-center" id="eleves-actions-results">
  
        </div>
  
      </div>
    </div>


    <div class="row my-4">
      <div class="col">

        <div class="card  mb-3">
          <div class="card-header card-header-warning text-center">
            <h4 class="card-title"><strong>{{ __('Listing') }}</strong></h4>


          </div>
          <div class="card-body ">
            <div class="col-sm-12 ml-3 my-4">
              <h5 class="font-weight-bold text-secondary"> Ajouter un nouvel élève :</h5>
            </div>
            <div class="col-sm-12">
              <form method="POST" action="{{route('registerEleve')}}">
                @csrf

                <div class="form-row mt-2 d-flex align-items-end">
                  <div class="form-group col-md-2 ">
                    <label for="civilite-input">Civilité </label>
                    <select name="civilite" class="form-control" data-style="btn btn-link" id="civilite-input">
                      @foreach ($civilites as $civilite)
                      <option value="{{$civilite->id}}" >
                        <a href="#">{{$civilite->Des}} 
                        </a>
                      </option>
                      @endforeach
      
      
                    </select>
      
                  </div>
                  <div class="form-group col-md-5">
                    <label for="nom-input">Nom</label>
                    <input type="text" class="form-control" name="nom" id="nom-input">
                    @if ($errors->has('nom'))
                    <div id="nom-error" class="error text-danger pl-3" for="nom" style="display: block;">
                      <strong>{{ $errors->first('nom') }}</strong>
                    </div>
                    @endif

                  </div>
                  <div class="form-group col-md-5">
                    <label for="prenom-input">Prénom</label>
                    <input type="text" class="form-control" name="prenom" id="prenom-input">
                    @if ($errors->has('prenom'))
                    <div id="prenom-error" class="error text-danger pl-3" for="prenom" style="display: block;">
                      <strong>{{ $errors->first('prenom') }}</strong>
                    </div>
                    @endif

                  </div>
                </div>
                <div class="form-row mt-2">
                  <div class="form-group col-md-6">
                    <label for="adress_input">Addresse</label>
                    <input type="text" class="form-control" name="adresse" id="adresse_input">
                    @if ($errors->has('adresse'))
                    <div id="adresse-error" class="error text-danger pl-3" for="adresse" style="display: block;">
                      <strong>{{ $errors->first('adresse') }}</strong>
                    </div>
                    @endif

                  </div>
                  <div class="form-group col-md-6">
                    <label for="date-input">Date de naissance</label>
                    <input type="date" class="form-control" name="dateN" id="date-input">
                    @if ($errors->has('dateN'))
                    <div id="dateN-error" class="error text-danger pl-3" for="dateN" style="display: block;">
                      <strong>{{ $errors->first('dateN') }}</strong>
                    </div>
                    @endif

                  </div>

                </div>

                <div class="form-row mt-2 align-items-end">
                  <div class="form-group col-md-6">
                    <label for="maladie-input">Maladie</label>
                    <select name="maladie" id="maladie-input" class="form-control">
                      <option disabled selected>Choisissez une maladie...</option>
                      @foreach ($maladies as $maladie)
                      <option value="{{$maladie->id}}">{{$maladie->Des}}</option>

                      @endforeach
                    </select>
                    @if ($errors->has('maladie'))
                    <div id="maladie-error" class="error text-danger pl-3" for="maladie" style="display: block;">
                      <strong>{{ $errors->first('maladie') }}</strong>
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-6">
                    <label for="autre-input">Autre</label>
                    <input type="text" class="form-control" name="autre" id="autre-input">

                  </div>
                </div>
                <div class="form-row mt-2">
                  <div class="form-group col-md-6">
                    <label for="formation-input">Formation(s): </label>
                    <select name="formation" class="form-control" data-style="btn btn-link" id="formation-input">
                      @foreach ($formations as $formation)
                      <option value="{{$formation->id}}" >
                        <a href="#">{{$formation->Des}} 
                        </a>
                      </option>
                      @endforeach
      
      
                    </select>
      
                  </div>
                  <div class="form-group col-md-6">
                    <label for="classe-input">Classe(s): </label>
                    <select name="classe" class="form-control" data-style="btn btn-link" id="classe-input">
                      @foreach ($classes as $classe)
                      <option value="{{$classe->id}}" >
                        <a href="#">{{$classe->Des}} 
                        </a>
                      </option>
                      @endforeach
      
      
                    </select>
      
                  </div>
                  
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
              </form>
            </div>

            <div class="col-sm-12 ml-3 mt-4">
              <h5 class="font-weight-bold text-secondary"> Liste des élèves :</h5>
            </div>
            <div class="col-sm-12 ml-3">
              <input class="form-control" id="search-eleve" type="text" placeholder="Rechercher un élève..">
            </div>
            <table class="table table-responsive-lg">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Prenom</th>
                  <th scope="col">Age</th>
                  <th scope="col">Parents</th>
                  <th scope="col">Maladie(s)</th>
                  <th scope="col" class="text-center">Actions</th>

                </tr>
              </thead>
              <tbody id="eleves-table">
                @if($eleves->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucun élève</h4>
                  </td>
                </tr>
                @endif
                @foreach ($eleves as $idx => $eleve)
                <tr id="{{$eleve->id}}">
                  <td>{{$idx+1}}</td>
                  <td>{{$eleve->Nom}}</td>
                  <td>{{$eleve->Prenom}}</td>
                  <td>{{$eleve->Age}}</td>
                  <td>{{
                     implode(',', array_map(function($enf){
                      return $enf->Prenom;
                    },$eleve->parents()->all() ) )
                  }}</td>
                  <td>{{$eleve->maladie()->Des?? null}}</td>
                  <td class="td-actions d-flex justify-content-around">
                    {{-- <button type="button" id="{{$eleve->id}}" rel="tooltip" data-toggle="tooltip" data-placement="top"
                      title="Modifier" class="my-3 btn btn-round btn-warning"
                      onclick="confirm_parent_account(event)">
                      <i class="material-icons">check</i>
                    </button> --}}
                    <button type="button" id="{{$eleve->id}}" rel="tooltip" data-toggle="tooltip" data-placement="top"
                      title="Supprimer" class="my-3 btn btn-round btn-danger" onclick="delete_eleve(event)">
                      <i class="material-icons">block</i>
                    </button>
                  </td>



                </tr>
                @endforeach

              </tbody>
            </table>





          </div>

        </div>

      </div>


    </div>

    
    <div class="row">
      <div class="col-sm-12 mt-5">
        <div class="d-flex flex-row justify-content-center">

          @if(session('affectation-flag'))
          @if(session('affectation-flag')=='fail')
          <div class="col-md-4">
            <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
              <i class="material-icons" data-notify="icon">error</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>{{session('affectation-message')}}</span>
            </div>
          </div>
          @else
          @if(session('affectation-flag')=='success')
          <div class="col-md-4">
            <div class="alert alert-success alert-with-icon w-60" data-notify="container">
              <i class="material-icons" data-notify="icon">check_circle</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>{{session('affectation-message')}}</span>
            </div>
          </div>
          @endif

          @endif

          @endif
        </div>
      </div>
    </div>

    {{-- Affectations --}}
    <div class="row mt-2 mb-3">
      <div class="col">

        <div class="card  mb-3">
          <div class="card-header card-header-info text-center">
            <h4 class="card-title"><strong>{{ __('Affectations') }}</strong></h4>


          </div>
          <div class="card-body ">
            {{-- Formations --}}
            <div class="container">
              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Formations :</h5>
                </div>
                <div class="col-sm-12">
                  <form id="affect-eleve-formation-form" method="POST" action="{{route('affectEleveFormation')}}">
                    @csrf
                    <div class="form-row my-3">

                      <div class="form-group col-md-4">
                        <label for="affect-eleve-formation-eleve">Afftecter l'élève : </label>
                        <select name="eleve" class="form-control" data-live-search="true"
                          data-style="btn btn-link" id="affect-eleve-formation-eleve">
                          @foreach ($eleves as $eleve)
                          <option value="{{$eleve->id}}" >
                          <a href="#">{{$eleve->Nom}} {{$eleve->Prenom}}
                          </a>
                          </option>
                          @endforeach


                        </select>

                      </div>
                      <div class="form-group col-md-4">
                        <label for="affect-eleve-formation-formation">A la formation: </label>
                        <select name="formation" class="form-control" data-style="btn btn-link" id="affect-eleve-formation-formation">
                          @foreach ($formations as $formation)
                          <option value="{{$formation->id}}" >
                            <a href="#">{{$formation->Des}} 
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>

                      <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <button type="submit" class="btn btn-info">Affecter</button>

                      </div>
                    </div>

                  </form>
                </div>
              </div>




            </div>

            {{-- Classes --}}
            <div class="container">
              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Classes :</h5>
                </div>
                <div class="col-sm-12">
                  <form id="affect-eleve-classe-form" method="POST" action="{{route('affectEleveClasse')}}">
                    @csrf
                    <div class="form-row my-3">

                      <div class="form-group col-md-4">
                        <label for="affect-eleve-classe-eleve">Afftecter l'élève : </label>
                        <select name="eleve" class="form-control" data-live-search="true"
                          data-style="btn btn-link" id="affect-eleve-classe-eleve">
                          @foreach ($eleves as $eleve)
                          <option value="{{$eleve->id}}" >
                          <a href="#">{{$eleve->Nom}} {{$eleve->Prenom}}
                          </a>
                          </option>
                          @endforeach


                        </select>

                      </div>
                      <div class="form-group col-md-4">
                        <label for="affact-eleve-classe-classe">A la classe: </label>
                        <select name="classe" class="form-control" data-style="btn btn-link" id="affact-eleve-classe-classe">
                          @foreach ($classes as $classe)
                          <option value="{{$classe->id}}" >
                            <a href="#">{{$classe->Des}} 
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>

                      <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <button type="submit" class="btn btn-info">Affecter</button>

                      </div>
                    </div>

                  </form>
                </div>
              </div>




            </div>






          </div>

        </div>

      </div>




    </div>








  </div>
</div>
@endsection
@push('js')
{{-- Search in table script --}}
<script>
  $(document).ready(function(){
  $("#search-eleve").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#eleves-table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<script>
  
    function delete_eleve(e){
      updateParentStatus(e,"R");
    }

    function delete_eleve(e){
      var target=e.currentTarget;
      var id=target.id;

    $.ajax({
      url: "{{url('/eleves')}}"+"/"+id,
      type: 'DELETE',
      data: {
      
        } ,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest',

      },
      

      success: res=>{
        console.log(JSON.parse(res));
        $(`tr[id="${id}"]`).remove();
        var resultDiv=document.getElementById('eleves-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-success alert-with-icon w-60" data-notify="container">
        <i class="material-icons" data-notify="icon">check</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
      <span>Elève supprimé avec succès</span>
      </div>
        `
        
        $(resultDiv).show();
       

      },
      error: err=>{
        console.log(err);
        var resultDiv=document.getElementById('eleves-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-danger alert-with-icon w-60" data-notify="container">
        <i class="material-icons" data-notify="icon">error</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
      <span> Une erreur s'est produite. ${err.message || (err.responseText && JSON.parse(err.responseText).message) || "Impossible de mettre à jours l'état"}</span>
      </div>
        `
        $(resultDiv).show();
      
      }


    });}
  
</script>


@endpush