@extends('layouts.app', ['activePage' => 'enseignants', 'titlePage' => __('Gestion Des Enseignants')])
<?php
setlocale(LC_TIME, "fr_FR");



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
    <div class="row my-4">
      <div class="col">
        
          <div class="card  mb-3">
            <div class="card-header card-header-warning text-center">
              <h4 class="card-title"><strong>{{ __('Comptes en attente de validation') }}</strong></h4>
             
              
            </div>
            <div class="card-body ">
              <table class="table">
                <thead >
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col" class="text-center">Actions</th>
                    
                  </tr>
                </thead>
                <tbody>
                  @if($enseignants->where('Etat','ATV')->count()==0)
                  <tr>
                    <td colspan="8">
                      <h4 class="text-secondary text-center"> Aucun professeur</h4>
                    </td>
                  </tr>
                  @endif
                  @foreach ($enseignants->where('Etat','ATV') as $idx => $enseignant)
                  <tr id="{{$enseignant->id}}">
                    <td>{{$idx+1}}</td>
                    <td>{{$enseignant->Nom}}</td>
                    <td>{{$enseignant->Prenom}}</td>
                    <td>{{$enseignant->Email}}</td>
                    <td>{{$enseignant->Age}}</td>
                    <td  class="td-actions d-flex justify-content-around">
                      <button type="button" id="{{$enseignant->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Valider le compte" class="my-3 btn btn-round btn-success"
                        onclick="confirm_prof_account(event)"
                        >
                        <i class="material-icons">check</i>
                      </button>
                      <button type="button" id="{{$enseignant->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Refuser" class="my-3 btn btn-round btn-danger"
                        onclick="refuse_prof_account(event)"
                        >
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
    
    <div class="row mt-2 mb-3">
      <div class="col">
        
        <div class="card  mb-3">
          <div class="card-header card-header-primary text-center">
            <h4 class="card-title"><strong>{{ __('Enseignants Confirmés') }}</strong></h4>
           
            
          </div>
          <div class="card-body ">
            <table class="table">
              <thead >
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Prenom</th>
                  <th scope="col">Email</th>
                  <th scope="col">Age</th>
                  <th scope="col">Classes</th>
                  
                </tr>
              </thead>
              <tbody>
                @if($enseignants->where('Etat','V')->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucun professeur</h4>
                  </td>
                </tr>
                @endif
                @foreach ($enseignants->where('Etat','V') as $idx => $enseignant)
                <tr>
                  <td>{{$idx+1}}</td>
                  <td>{{$enseignant->Nom}}</td>
                  <td>{{$enseignant->Prenom}}</td>
                  <td>{{$enseignant->Email}}</td>
                  <td>{{$enseignant->Age}}</td>
                  <td>{{
                    implode(',', array_map(function($cl){
                     return $cl->Des;
                   },$enseignant->classes()->all() ) )
                 }}
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
            <div class="container">
              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Afftecter une classe :</h5>
                </div>
                <div class="col-sm-12">
                  <form id="affect-prof-class-form" method="POST" action="{{route('affectProfClasse')}}">
                    @csrf
                    <div class="form-row my-3">
          
                      <div class="form-group col-md-3">
                        <label for="affect-class-prof-select">Afftecter le professeur : </label>
                        <select name="enseignant" class="form-control" data-style="btn btn-link" id="affect-class-prof-select">
                          @foreach ($enseignants->where('Etat','V') as $enseignant)
                          <option value="{{$enseignant->id}}" >
                            <a href="#">{{$enseignant->Nom}} {{$enseignant->Prenom}}
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="form-group col-md-3">
                        <label for="affect-class-prof-class">A la classe: </label>
                        <select name="classe" class="form-control" data-style="btn btn-link" id="affect-class-prof-class">
                          @foreach ($classes as $classe)
                          <option value="{{$classe->id}}" >
                            <a href="#">{{$classe->Des}} 
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="form-group col-md-3">
                        <label for="affect-class-prof-matiere">Pour enseigner: </label>
                        <select name="matiere" class="form-control" data-style="btn btn-link" id="affect-class-prof-matiere">
                          @foreach ($matieres as $matiere)
                          <option value="{{$matiere->id}}" >
                            <a href="#">{{$matiere->Des}} 
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="col-md-3 d-flex align-items-center justify-content-center">
                        <button type="submit" class="btn btn-info">Affecter</button>
          
                      </div>
                    </div>
          
                  </form>
                </div>
              </div>


              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Afftecter une formation :</h5>
                </div>
                <div class="col-sm-12">
                  <form id="affct-prof-formation-form" method="POST" action="{{route('affectProfFormation')}}">
                    @csrf
                    <div class="form-row my-3">
          
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-matiere">Afftecter le professeur : </label>
                        <select name="enseignant" class="form-control" data-style="btn btn-link" id="affect-formation-prof-select">
                          @foreach ($enseignants->where('Etat','V') as $enseignant)
                          <option value="{{$enseignant->id}}" >
                            <a href="#">{{$enseignant->Nom}} {{$enseignant->Prenom}}
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="form-group col-md-4">
                        <label for="affect-formation-prof-formation-select">A la formation: </label>
                        <select name="formation" class="form-control" data-style="btn btn-link" id="affect-formation-prof-formation-select">
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
            
         
      
          </div>
          
        </div>
      
      </div>




    </div>

    {{-- Enseignants refusés --}}
    <div class="row my-4">
      <div class="col">
        
          <div class="card  mb-3">
            <div class="card-header card-header-danger text-center">
              <h4 class="card-title"><strong>{{ __('Comptes refusés') }}</strong></h4>
             
              
            </div>
            <div class="card-body ">
              <table class="table">
                <thead >
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col" class="text-center">Actions</th>
                    
                  </tr>
                </thead>
                <tbody>
                  @if($enseignants->where('Etat','R')->count()==0)
                  <tr>
                    <td colspan="8">
                      <h4 class="text-secondary text-center"> Aucun professeur</h4>
                    </td>
                  </tr>
                  @endif
                  @foreach ($enseignants->where('Etat','R') as $idx => $enseignant)
                  <tr id="{{$enseignant->id}}">
                    <td>{{$idx+1}}</td>
                    <td>{{$enseignant->Nom}}</td>
                    <td>{{$enseignant->Prenom}}</td>
                    <td>{{$enseignant->Email}}</td>
                    <td>{{$enseignant->Age}}</td>
                    <td  class="td-actions d-flex justify-content-around">
                      <button type="button" id="{{$enseignant->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Valider le compte" class="my-3 btn btn-round btn-success"
                        onclick="confirm_prof_account(event)"
                        >
                        <i class="material-icons">check</i>
                      </button>
                      <button type="button" id="{{$enseignant->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Refuser" class="my-3 btn btn-round btn-danger"
                        onclick="refuse_prof_account(event)"
                        >
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

    

  </div>
</div>
@endsection
@push('js')

<script>
    function confirm_prof_account(e){
      updateProfStatus(e,"V");
    }
    function refuse_prof_account(e){
      updateProfStatus(e,"R");
    }

    function updateProfStatus(e,etat){
      var target=e.currentTarget;
      var id=target.id;

    $.ajax({
      url: "{{url("/confirmations/prof")}}"+"/"+id,
      type: 'PUT',
      data: {
        Etat: etat,
        } ,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest',

      },
      

      success: res=>{
        console.log(JSON.parse(res));
        $(`tr[id="${id}"]`).remove();
        var resultDiv=document.getElementById('account-actions-results');
        resultDiv.innerHTML=`
        <div class="alert alert-success alert-with-icon w-60" data-notify="container">
          <i class="material-icons" data-notify="icon">check</i>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
          </button>
          <span>Opération effectuée avec succès</span>
        </div>
        `
        
        $(resultDiv).show();
       

      },
      error: err=>{
        console.log(err);
        var resultDiv=document.getElementById('account-actions-results');
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