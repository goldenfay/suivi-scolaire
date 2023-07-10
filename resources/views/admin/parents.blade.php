@extends('layouts.app', ['activePage' => 'parents', 'titlePage' => __('Gestion Des Parents')])
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
    <div class="row my-4">
      <div class="col">
        
          <div class="card  mb-3">
            <div class="card-header card-header-warning text-center">
              <h4 class="card-title"><strong>{{ __('Comptes en attente de validation') }}</strong></h4>
             
              
            </div>
            <div class="card-body ">
              <table class="table table-responsive-lg">
                <thead >
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col" class="text-center">Actions</th>
                    
                  </tr>
                </thead>
                <tbody>
                  @if($parents->where('Etat','ATV')->count()==0)
                  <tr>
                    <td colspan="8">
                      <h4 class="text-secondary text-center"> Aucun parent</h4>
                    </td>
                  </tr>
                  @endif
                  @foreach ($parents->where('Etat','ATV') as $idx => $parent)
                  <tr id="{{$parent->id}}">
                    <td>{{$idx+1}}</td>
                    <td>{{$parent->civilite()->Des ?? null}}.  {{$parent->Nom}}</td>
                    <td>{{$parent->Prenom}}</td>
                    <td>{{$parent->Email}}</td>
                    <td>{{$parent->NumTel}}</td>
                    <td  class="td-actions d-flex justify-content-around">
                      <button type="button" id="{{$parent->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Valider le compte" class="my-3 btn btn-round btn-success"
                        onclick="confirm_parent_account(event)"
                        >
                        <i class="material-icons">check</i>
                      </button>
                      <button type="button" id="{{$parent->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Refuser" class="my-3 btn btn-round btn-danger"
                        onclick="refuse_parent_account(event)"
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
            <h4 class="card-title"><strong>{{ __('Parents Confirmés') }}</strong></h4>
           
            
          </div>
          <div class="card-body ">
            <table class="table table-responsive-lg">
              <thead >
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Prenom</th>
                  <th scope="col">Email</th>
                  <th scope="col">Enfants</th>
                  <th scope="col">Téléphone</th>
                  
                </tr>
              </thead>
              <tbody>
                @if($parents->where('Etat','V')->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucun professeur</h4>
                  </td>
                </tr>
                @endif
                @foreach ($parents->where('Etat','V') as $idx => $parent)
                <tr>
                  <td>{{$idx+1}}</td>
                  <td>{{$parent->civilite()->Des ?? null}}.  {{$parent->Nom}}</td>
                  <td>{{$parent->Prenom}}</td>
                  <td>{{$parent->Email}}</td>
                  <td>{{
                    implode(',', array_map(function($enf){
                      return $enf->Prenom;
                    },$parent->enfants()->all() ) )
                  }}
                  </td>
                  <td>{{$parent->NumTel}}</td>
                  
  
                  
  
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
                  <h5> Parentalité :</h5>
                </div>
                <div class="col-sm-12">
                  <form id="affect-prof-class-form" method="POST" action="{{route('affectParentChildren')}}">
                    @csrf
                    <div class="form-row my-3">
          
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-select">Afftecter le parent : </label>
                        <select name="parent" 
                        class="form-control selectpicker show-tick" 
                        data-live-search="true" 
                        data-style="btn btn-link" id="affect-class-prof-select">
                          @foreach ($parents->where('Etat','V') as $parent)
                          <option value="{{$parent->id}}" >
                            <a href="#">{{$parent->Nom}} {{$parent->Prenom}}
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-class">Etant parent de: </label>
                        <select name="children[]" multiple
                        class="form-control selectpicker show-tick" 
                        data-live-search="true" 
                        data-style="btn btn-link" id="affect-class-prof-class"
                        >
                          @foreach ($eleves as $eleve)
                          <option 
                          value="{{$eleve->id}}" 
                          data-tokens="{{implode(' ',[$eleve->Nom,$eleve->Prenom])}}"
                            >
                            <a href="#">{{$eleve->Nom}}  {{$eleve->Prenom}} 
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
    {{-- Comptes refusés --}}
    <div class="row my-4">
      <div class="col">
        
          <div class="card  mb-3">
            <div class="card-header card-header-danger text-center">
              <h4 class="card-title"><strong>{{ __('Comptes refusés') }}</strong></h4>
             
              
            </div>
            <div class="card-body ">
              <table class="table table-responsive-lg">
                <thead >
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Téléphone</th>
                    {{-- <th scope="col" class="text-center">Actions</th> --}}
                    
                  </tr>
                </thead>
                <tbody>
                  @if($parents->where('Etat','R')->count()==0)
                  <tr>
                    <td colspan="8">
                      <h4 class="text-secondary text-center"> Aucun parent</h4>
                    </td>
                  </tr>
                  @endif
                  @foreach ($parents->where('Etat','R') as $idx => $parent)
                  <tr id="{{$parent->id}}">
                    <td>{{$idx+1}}</td>
                    <td>{{$parent->civilite()->Des ?? null}}.  {{$parent->Nom}}</td>
                    <td>{{$parent->Prenom}}</td>
                    <td>{{$parent->Email}}</td>
                    <td>{{$parent->NumTel}}</td>
                    {{-- <td  class="td-actions d-flex justify-content-around">
                      <button type="button" id="{{$parent->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Valider le compte" class="my-3 btn btn-round btn-success"
                        onclick="confirm_parent_account(event)"
                        >
                        <i class="material-icons">check</i>
                      </button>
                      <button type="button" id="{{$parent->id}}" rel="tooltip" data-toggle="tooltip"
                        data-placement="top" title="Refuser" class="my-3 btn btn-round btn-danger"
                        onclick="refuse_parent_account(event)"
                        >
                        <i class="material-icons">block</i>
                      </button>
                    </td> --}}
    
                    
    
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
    function confirm_parent_account(e){
      updateParentStatus(e,"V");
    }
    function refuse_parent_account(e){
      updateParentStatus(e,"R");
    }

    function updateParentStatus(e,etat){
      var target=e.currentTarget;
      var id=target.id;

    $.ajax({
      url: "{{url("/confirmations/parent")}}"+"/"+id,
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