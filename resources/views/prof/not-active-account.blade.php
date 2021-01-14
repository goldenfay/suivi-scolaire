@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Mon Compte')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 d-flex flex-column justify-content-center">
          @isset($status)
            @if($status=='ATV')
            <h1 align="center" class="text-secondary"> <i class="material-icons"style="font-size: 4rem;" >hourglass_top</i></h1>
            <h2 align="center" class="text-secondary">

                Vote compte n'est pas encore validé au niveau de l'administration.
            </h2>
            @else
            <h1 align="center" class="text-danger"> <i class="material-icons"style="font-size: 4rem;" >block</i></h1>
            
            <h2 align="center" class="text-danger">
                <i class="material-icons" >block</i>

                Vote demande a été refusée au niveau de l'administration.
            </h2>

            @endif
          @endisset
       
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
       
      </div>
    </div>
  </div>
</div>
@endsection