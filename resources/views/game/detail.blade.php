<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="{!! asset("assets/css/cards.css") !!}">
    <link rel="stylesheet" href="{!! asset("assets/css/game.css") !!}">
    <title>Medianova BlackJack</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center dealer">
            <div class="title text-white mb-3 mt-3">
                <h5>DEALER</h5>
            </div>
            <div class="dealer-cards">
                @foreach($dealerDeck as $card)
                    <div class="card {!! $card->name !!}"></div>
                @endforeach
                <div class="card back_card ml-5" style="background-image: url('{!! asset("assets/img/back-card.jpg") !!} ')"></div>
            </div>

        </div>

    </div>
    <div class="row justify-content-center ">
        <div class="col-md-6 text-center gamer ">
            <div class="title text-white mb-3 mt-3">
                <h5>PLAYER</h5>
            </div>
            <div class="player-cards">
                @foreach($playerDeck as $card)
                    <div class="card {!! $card->name !!}"></div>
                @endforeach
            </div>
            <div class="total text-white">Total:
                <span class="total-player">{!! $playerTotal !!}</span>
            </div>
        </div>
    </div>
    <div class="row justify-content-center ">
        <div class="col-md-6 text-center mt-5">
            <button id="stay" type="button" class="btn btn-success">Stay</button>
            <button id="bust" type="button" class="btn btn-danger">Bust</button>

        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script type="text/javascript">
   $(document).ready(function () {
       getGameStatus();
   });

   function getGameStatus (){
       $.post( "{!! route("gameStatus") !!}", { _token: "{{ csrf_token() }}", id: "{{ $id }}"  })
           .done(function( data ) {
               alert( "Data Loaded: " + data );
           });
   }

   $("#stay").click(function () {
       $.post( "{!! route("gameStatus") !!}", { _token: "{{ csrf_token() }}", id: "{{ $id }}", addCard:"true"  })
           .done(function( data ) {
               alert( "Data Loaded: " + data );
           });
   });

   $("#bust").click(function () {

   });

    // $.get( "", { name: "John", time: "2pm" } )
    //     .done(function( data ) {
    //         alert( "Data Loaded: " + data );
    //     });
</script>
</body>
</html>
