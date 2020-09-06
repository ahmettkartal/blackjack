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
            <h6 class="text-white mt-5 mb-5">Enter the game</h6>
            <form  action="{!! route("startGame") !!}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name" class=" text-white mb-1">What is your name?</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
                </div>
                <button type="submit" class="btn btn-primary">Let's Start</button>
            </form>
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
    // $.get( "", { name: "John", time: "2pm" } )
    //     .done(function( data ) {
    //         alert( "Data Loaded: " + data );
    //     });
</script>
</body>
</html>
