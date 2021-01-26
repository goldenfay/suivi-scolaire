@extends('layouts.app', ['activePage' => 'Consulter', 'titlePage' => __('Consultation Du Dossier')])

@section('content')
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
        <div class="card-header">
          <div class="container">
            <div class="row">
              <div class="col-md-6">
              <img src="{{__($eleve->Pic_Path!=null?$eleve->Pic_Path: asset('assets')."/autres/default-eleve-avatar-male.jpg" )}}" 
              class="img-fluid img-round"
              alt=""/>
                
              </div>
              <div class="col-md-6">
                <div class="d-flex flex-row align-items-center justify-content-center h-100">
                  <h3 align="center">{{$eleve->Nom}} {{$eleve->Prenom}}</h3>
                  
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
                <caption class="text-center text-primary">{{$eleve->$attr}} </caption>
                
              </div>
            </div>
                
            @endforeach
            <div class="row my-2">
              <div class="col-md-6">
                <caption class="text-center">Classes</caption>
                
              </div>
              <div class="col-md-6">
                @foreach ($classes as $classe)
                <caption class="text-center text-primary mx-auto">{{$classe->Des}} </caption>
                @endforeach
                
              </div>
            </div>
            <div class="row my-2">
              <div class="col-md-6">
                <caption class="text-center">Formations</caption>
                
              </div>
              <div class="col-md-6">
                @foreach ($formations as $formation)
                <caption class="text-center text-primary mx-auto">{{$formation->Des}} </caption>
                @endforeach
                
              </div>
            </div>
                

          </div>

        </div>
      </div>
      
    </div>
    
  </div>
</div>
@endsection