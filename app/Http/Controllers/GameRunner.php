<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use Illuminate\View\View;

class GameRunner extends Controller
{
    public function index(){
        return view("game.index");
    }

    public function startGame(Request $request){
        // Create a game on database
        $game = new Game();
        $game->playerName =$request->name;
        $game->delay = 10;
        $game->deck = json_encode($this->createShuffledDeck(),true);
        $game->save();

        // Define and save game decks
        $startDeck = $game->getStartDeck();
        $game->dealer_deck = json_encode($startDeck["dealer"]);
        $game->player_deck = json_encode($startDeck["player"]);
        $game->player_point = $game->calculateTotalPoint($startDeck["player"]);
        $game->dealer_point = $game->calculateTotalPoint($startDeck["dealer"]);
        $game->save();

        // After save transaction redirect to game detail
        return redirect()->route('gameDetail', ['id' => $game->id]);
    }

    /**
    * @params Request
     * @return View to game detail
     */
    public function gameDetail(Request $request){
        $game = Game::where("id", $request->id)->first();
        $id = $request->id;
        $playerName = $game->playerName;
        return view("game.detail",compact("dealerDeck", "playerDeck","dealerTotal","playerTotal","id","playerName"));
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

    /**
    * @params Request
     * @return Json object that includes game status to view
     */
    public function gameStatus(Request $request){
        $game = Game::where("id",$request->id)->first();

        // Control if game has ended before
        if ($game->game_status == "finished"){
            $dealerPoint = $game->dealerPoint();
            $playerPoint = $game->playerPoint();

            $status = [
                "status" => "finished",
                "dealerDeck" => $game->dealer_deck,
                "playerDeck" => $game->player_deck,
                "message" => "The Game Has Finished",
                "winner" => $game->getWinner(),
                "dealer_total" => $dealerPoint,
                "player_total" => $playerPoint
            ];
            return json_encode($status);
        }

        // Player wants more card
        if ($request->continue == "true"){
            $game->addCardToPlayer();
            if ($game->calculateTotalPoint($game->player_deck) >= 21){
                $game->game_status = "finished";
                $game->save();
                $status = [
                    "status" => "finished",
                    "dealerDeck" => $game->dealer_deck,
                    "playerDeck" => $game->player_deck,
                    "message" => "The Game Has Finished",
                    "winner" => $game->getWinner(),
                    "dealer_total" => $game->calculateTotalPoint() ,
                    "player_total" => $game->calculateTotalPoint(json_decode($game->player_deck))
                ];
                return json_encode($status);
            }else{
                $game = Game::where("id",$request->id)->first();
                // Hide one card of dealer
                $dealerDeck = json_decode($game->dealer_deck);
                array_shift($dealerDeck);
                //Add back card to deck
                $dealerDeck[] = ["name" => "back_card ml-5"];
                $status = [
                    "status" => "continue",
                    "dealerDeck" => json_encode($dealerDeck),
                    "playerDeck" => $game->player_deck,
                    "message" => "Continue or Stay?",
                    "winner" => "",
                    "dealer_total" =>  $game->calculateTotalPoint(json_decode($game->dealer_deck)),
                    "player_total" => $game->calculateTotalPoint(json_decode($game->player_deck))
                ];
                return json_encode($status);
            }
        }

        // Game will end
        if ($request->stay == "true"){
            $game->game_status = "finished";
            $game->save();
            // Add card to dealer until total number bigger than 1
            while ($game->dealerPoint() < 17) {
                $game->addCardToDealer();
            }

            $game = Game::where("id",$request->id)->first();
            $status = [
                "status" => "finished",
                "dealerDeck" => $game->dealer_deck,
                "playerDeck" => $game->player_deck,
                "message" => "The Game Has Finished",
                "winner" => $game->getWinner(),
                "dealer_total" => $game->dealerPoint(),
                "player_total" => $game->playerPoint()
            ];
            return json_encode($status);
        }

        // Hide one card of dealer
        $dealerDeck = json_decode($game->dealer_deck);
        array_shift($dealerDeck);
        //Add back card to deck
        $dealerDeck[] = ["name" => "back_card ml-5"];

        $status = [
            "status" => "continue",
            "dealerDeck" => json_encode($dealerDeck),
            "playerDeck" => $game->player_deck,
            "message" => "Hit or Stay?",
            "winner" => "",
            "dealer_total" => $game->dealerPoint(),
            "player_total" => $game->playerPoint()
        ];
        return json_encode($status);
    }



}
