@extends('layouts.app', ['activePage' => 'classes', 'titlePage' => __('Classes & Formations')])
<?php
setlocale(LC_TIME, "fr_FR");

// dd($parents->first()->enfants());


?>
@section('content')

<div class="content">
  <div class="container-fluid">
    

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

    


    {{-- Classes --}}
    <div class="row my-4">
      <div class="col">

        <div class="card  mb-3">
          <div class="card-header card-header-success text-center">
            <h4 class="card-title"><strong>{{ __('Classes') }}</strong></h4>


          </div>
          <div class="card-body ">
            <div class="col-sm-12 ml-3 my-4">
              <h5 class="font-weight-bold text-secondary"> Ajouter une nouvelle classe :</h5>
            </div>
            <div class="col-sm-12">
              <form method="POST" action="{{route('registerClasse')}}">
                @csrf

                <div class="form-row mt-2 d-flex align-items-end">
                  <div class="form-group col-md-2 ">
                    <label for="niveau-input">Niveau </label>
                    <select name="niveau" class="form-control" data-style="btn btn-link" id="niveau-input">
                      @foreach ($niveaux as $niveau)
                      <option value="{{$niveau->id}}" >
                        <a href="#">{{$niveau->Des}} 
                        </a>
                      </option>
                      @endforeach
      
      
                    </select>
      
                  </div>
                  <div class="form-group col-md-5">
                    <label for="code-input">Code</label>
                    <input type="text" class="form-control" name="code" id="code-input">
                    @if ($errors->has('code'))
                    <div id="code-error" class="error text-danger pl-3" for="code" style="display: block;">
                      <strong>{{ $errors->first('code') }}</strong>
                    </div>
                    @endif

                  </div>
                  <div class="form-group col-md-5">
                    <label for="des-input">Intitulé</label>
                    <input type="text" class="form-control" name="des" id="des-input">
                    @if ($errors->has('des'))
                    <div id="des-error" class="error text-danger pl-3" for="des" style="display: block;">
                      <strong>{{ $errors->first('des') }}</strong>
                    </div>
                    @endif

                  </div>
                </div>
                

                
                

                <button type="submit" class="btn btn-success">Enregistrer</button>
              </form>
            </div>

            <div class="col-sm-12 ml-3 mt-4">
              <h5 class="font-weight-bold text-secondary"> Liste des classes définies :</h5>
            </div>
            <div class="col-sm-12 ml-3">
              <input class="form-control" id="search-classe" type="text" placeholder="Rechercher..">
            </div>
            <table class="table table-responsive-lg">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Code</th>
                  <th scope="col">Niveau</th>
                  <th scope="col">Intitulé</th>
                  
                  {{-- <th scope="col" class="text-center">Actions</th> --}}

                </tr>
              </thead>
              <tbody id="classes-table">
                @if($classes->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune classe</h4>
                  </td>
                </tr>
                @endif
                @foreach ($classes as $idx => $classe)
                <tr id="{{$classe->id}}">
                  <td>{{$idx+1}}</td>
                  <td>{{$classe->Code}}</td>
                  <td>{{$classe->Niveau}}</td>
                  <td>{{$classe->Des}}</td>
                  
                  



                </tr>
                @endforeach

              </tbody>
            </table>





          </div>

        </div>

      </div>


    </div>

    {{-- Formations --}}
    <div class="row my-4">
      <div class="col">

        <div class="card  mb-3">
          <div class="card-header card-header-danger text-center">
            <h4 class="card-title"><strong>{{ __('Formations') }}</strong></h4>


          </div>
          <div class="card-body ">
            <div class="col-sm-12 ml-3 my-4">
              <h5 class="font-weight-bold text-secondary"> Ajouter une nouvelle formation :</h5>
            </div>
            <div class="col-sm-12">
              <form method="POST" action="{{route('registerFormation')}}">
                @csrf

                <div class="form-row mt-2 d-flex align-items-end">
                  
                  <div class="form-group col-md-5">
                    <label for="code-input">Code</label>
                    <input type="text" class="form-control" name="code" id="code-input">
                    @if ($errors->has('code'))
                    <div id="code-error" class="error text-danger pl-3" for="code" style="display: block;">
                      <strong>{{ $errors->first('code') }}</strong>
                    </div>
                    @endif

                  </div>
                  <div class="form-group col-md-5">
                    <label for="des-input">Intitulé</label>
                    <input type="text" class="form-control" name="des" id="des-input">
                    @if ($errors->has('des'))
                    <div id="des-error" class="error text-danger pl-3" for="des" style="display: block;">
                      <strong>{{ $errors->first('des') }}</strong>
                    </div>
                    @endif

                  </div>
                </div>

                <button type="submit" class="btn btn-danger">Enregistrer</button>
              </form>
            </div>

            <div class="col-sm-12 ml-3 mt-4">
              <h5 class="font-weight-bold text-secondary"> Liste des formations définies :</h5>
            </div>
            <div class="col-sm-12 ml-3">
              <input class="form-control" id="search-formation" type="text" placeholder="Rechercher..">
            </div>
            <table class="table table-responsive-lg">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Code</th>
                  <th scope="col">Intitulé</th>
                  
                  {{-- <th scope="col" class="text-center">Actions</th> --}}

                </tr>
              </thead>
              <tbody id="formations-table">
                @if($formations->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune formation</h4>
                  </td>
                </tr>
                @endif
                @foreach ($formations as $idx => $formation)
                <tr id="{{$formation->id}}">
                  <td>{{$idx+1}}</td>
                  <td>{{$formation->Code}}</td>
                  <td>{{$formation->Des}}</td>
                  
                </tr>
                @endforeach

              </tbody>
            </table>





          </div>

        </div>

      </div>


    </div>



    {{-- Matières --}}
    <div class="row my-4">
      <div class="col">

        <div class="card  mb-3">
          <div class="card-header card-header-danger text-center">
            <h4 class="card-title"><strong>{{ __('Matières') }}</strong></h4>


          </div>
          <div class="card-body ">
            <div class="col-sm-12 ml-3 my-4">
              <h5 class="font-weight-bold text-secondary"> Définir une nouvelle matière :</h5>
            </div>
            <div class="col-sm-12">
              <form method="POST" action="{{route('registerMatiere')}}">
                @csrf

                <div class="form-row mt-2 d-flex align-items-end">
                  
                  <div class="form-group col-md-5">
                    <label for="code-input">Code</label>
                    <input type="text" class="form-control" name="code" id="code-input">
                    @if ($errors->has('code'))
                    <div id="code-error" class="error text-danger pl-3" for="code" style="display: block;">
                      <strong>{{ $errors->first('code') }}</strong>
                    </div>
                    @endif

                  </div>
                  <div class="form-group col-md-5">
                    <label for="des-input">Description</label>
                    <input type="text" class="form-control" name="des" id="des-input">
                    @if ($errors->has('des'))
                    <div id="des-error" class="error text-danger pl-3" for="des" style="display: block;">
                      <strong>{{ $errors->first('des') }}</strong>
                    </div>
                    @endif

                  </div>
                </div>

                <button type="submit" class="btn btn-danger">Enregistrer</button>
              </form>
            </div>

            <div class="col-sm-12 ml-3 mt-4">
              <h5 class="font-weight-bold text-secondary"> Liste des matières :</h5>
            </div>
            <div class="col-sm-12 ml-3">
              <input class="form-control" id="search-matiere" type="text" placeholder="Rechercher une matière..">
            </div>
            <table class="table table-responsive-lg">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Code</th>
                  <th scope="col">Description</th>
                  
                  {{-- <th scope="col" class="text-center">Actions</th> --}}

                </tr>
              </thead>
              <tbody id="formations-table">
                @if($formations->count()==0)
                <tr>
                  <td colspan="8">
                    <h4 class="text-secondary text-center"> Aucune matière</h4>
                  </td>
                </tr>
                @endif
                @foreach ($formations as $idx => $formation)
                <tr id="{{$formation->id}}">
                  <td>{{$idx+1}}</td>
                  <td>{{$formation->Code}}</td>
                  <td>{{$formation->Des}}</td>
                  
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
            {{-- Classes/Formations --}}
            <div class="container">
              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Formations et classes :</h5>
                </div>
                <div class="col-sm-12">
                  <form method="POST" action="{{route('affectClasseFormation')}}">
                    @csrf
                    <div class="form-row my-3">
          
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-select">La formation : </label>
                        <select name="formation" 
                        class="form-control selectpicker show-tick" 
                        data-live-search="true" 
                        data-style="btn btn-link" id="affect-class-prof-select">
                          @foreach ($formations as $formation)
                          <option value="{{$formation->id}}" >
                            <a href="#">{{$formation->Des}} 
                            </a>
                          </option>
                          @endforeach
          
          
                        </select>
          
                      </div>
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-class">inclue les classes: </label>
                        <select name="classes[]" multiple
                        class="form-control selectpicker show-tick" 
                        data-live-search="true" 
                        data-style="btn btn-link" id="affect-class-prof-class"
                        >
                          @foreach ($classes as $classe)
                          <option 
                          value="{{$classe->id}}" 
                            >
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
            {{-- Formation/Matieres --}}
            <div class="container">
              <div class="row">
                <div class="col-sm-12 ml-3">
                  <h5> Formations et matières :</h5>
                </div>
                <div class="col-sm-12">
                  <form  method="POST" action="{{route('affectMatiereFormation')}}">
                    @csrf
                    <div class="form-row my-3">
          
                      <div class="form-group col-md-4">
                        <label for="affect-class-prof-class">La classe: </label>
                        <select name="formation"
                        class="form-control selectpicker show-tick" 
                        data-live-search="true" 
                        data-style="btn btn-link" id="affect-class-prof-class"
                        >
                        @foreach ($formations as $formation)
                        <option 
                        value="{{$formation->id}}" 
                        >
                        <a href="#">{{$formation->Des}} 
                        </a>
                      </option>
                      @endforeach
                      
                      
                    </select>
                    
                  </div>
                  <div class="form-group col-md-4">
                    <label for="affect-class-prof-select">regroupe les matières : </label>
                    <select name="matieres[]"  multiple
                    class="form-control selectpicker show-tick" 
                    data-live-search="true" 
                    data-style="btn btn-link" id="affect-class-prof-select">
                      @foreach ($matieres as $matiere)
                      <option value="{{$matiere->id}}" >
                        <a href="#">{{$matiere->Des}} 
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
  $("#search-classe").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#classes-table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $("#search-formation").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#formations-table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endpush