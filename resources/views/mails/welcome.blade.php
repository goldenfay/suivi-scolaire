<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .row: {
            width: 100%;
            margin: 5px 3px;
            
        }
        .mt-3: {
           
            margin-top: 15px;

        }
        .d-flex : {
            display: "flex";
        }
        .flex-row : {
            flex-direction: "row";
        }
        .justify-content-center : {
            justify-content: "center";
        }
        p: {
            margin: 4px 0;
        }
    </style>    
</head>

<body>
    @if($details->user=='parent')
    <div class="container ml-3">
        <div class="row">
            <div class="d-flex flex-row justify-content-center">
                <h3 align="center" >Suivi Scolaire</h3>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p>Chèr(e) {{$details->destinataire}} ,</p>
                <p class="mt-2">Merci d'avoir rejoindre l'application de suivi scolaire. Votre compte sera très
                    prochainement activé.</p>
                <p class="mt-1">L'application vous permet de suivre le progrès de vos enfants et d'être informés par les
                    correspondances et les évènements pédagogiques à tout moment.</p>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p> L'administration vous souhaite une agréable expérience avec l'application.</p>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p> Cordialement.</p>
                <b class="mt-4 font-weight-bold"> Scolarité.</b>

            </div>
        </div>

    </div>
    @elseif($details->user=='prof')
    <div class="container ml-3">
        <div class="row">
            <div class="d-flex flex-row justify-content-center">
                <h3 align="center" >Suivi Scolaire</h3>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p>Mr/Mme {{$details->destinataire}} ,</p>
                <p class="mt-2">Merci d'avoir rejoindre l'équipe d'enseignants de l'application de suivi scolaire. Votre compte sera très
                    prochainement activé.</p>
                <p class="mt-1">L'application vous permet de communiquer facilement avec les parents de vos élèves via un cahier de correspondance numérique.</p>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p> L'administration vous souhaite une agréable expérience avec l'application.</p>

            </div>
        </div>
        <div class="row mt-3">
            <div>
                <p> Cordialement.</p>
                <b class="mt-4 font-weight-bold"> Scolarité.</b>

            </div>
        </div>

    </div>
    @endif


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>