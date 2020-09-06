<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Game;

class GameRunner extends Controller
{
    public function index(){
        return view("game.index");
    }

    public function startGame(Request $request){
        $game = new Game();
        $game->playerName =$request->name;
        $game->delay = 10;
        $game->deck = json_encode($this->createShuffledDeck(),true);
        $game->save();
        $startDeck = $game->getStartDeck();
        $game->dealer_deck = json_encode($startDeck["dealer"]);
        $game->player_deck = json_encode($startDeck["player"]);
        $game->player_point = $game->calculateTotalPoint($startDeck["player"]);
        $game->dealer_point = $game->calculateTotalPoint($startDeck["dealer"]);
        $game->save();
        return redirect()->route('gameDetail', ['id' => $game->id]);
    }

    public function gameDetail(Request $request){
        $game = Game::where("id", $request->id)->first();
        $dealerDeck = json_decode($game->dealer_deck);
        // Hide 1 card from player
        array_pop($dealerDeck);

        $playerDeck = json_decode($game->player_deck);
        $playerTotal = $game->player_point;
        $dealerTotal = $game->dealer_point;
        $id = $request->id;
        return view("game.detail",compact("dealerDeck", "playerDeck","dealerTotal","playerTotal","id"));
    }

    /**
     * Creates game deck
     * @params deckNumber => how many deck will create
     * @return Array shuffled game deck
     */
    public function createShuffledDeck($deckNumber = 6){
        // Define card name and values
        $cardValues = [2=>"two", 3=>"three", 4=>"four", 5=>"five",6=>"six",7=>"seven",8=>"eight",9=>"nine",10=>"ten",11=>"ace",12=>"jack",13=>"queen",14=>"king"];
        $cardNames = ["clubs","hearts","spades","diamonds"];
        $deck = [];

        // Create cards
        foreach ($cardNames as $cardName){
            foreach ($cardValues as $value => $card){
                $newCard = [];
                $newCard ["name"] = $card."_".$cardName;
                $newCard["value"] = $value < 12 ? $value : 10;
                array_push($deck,$newCard);
            }
        }

        // Create 6 copy of created deck
        $gameDeck = [];
        for ($i=0; $i < $deckNumber; $i++) {
            $gameDeck = array_merge($gameDeck, $deck);
        }

        // Shuffle game deck
        shuffle($gameDeck);
        return $gameDeck;
    }

    public function gameStatus(Request $request){
        $game = Game::where("id",$request->id)->first();
        if ($request->addCard == "true"){
            $gameDeck = json_decode($game->deck);
            $playerDeck = json_decode($game->player_deck );
            array_push($playerDeck, array_shift($gameDeck));
            $game->deck = json_encode($gameDeck);
            $game->player_deck = json_encode($playerDeck);
            $game->save();
        }
        $gameDeck = json_decode($game->deck);
        $playerDeck = json_decode($game->player_deck );
        $dealerDeck = json_decode($game->dealer_deck);

        $playerPoint = $game->calculateTotalPoint($playerDeck);
        $dealerPoint = $game->calculateTotalPoint($dealerDeck);



    }



}
