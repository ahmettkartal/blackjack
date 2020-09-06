<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = "game";

    /**
    * Returns and saves starter decks of players
     **/
    public function getStartDeck(){
        $deck = json_decode($this->deck);
        $dealerCards = [];
        $playerCards = [];


        // Select first 4 cards for players
        $dealerCards[] = array_shift($deck);
        $playerCards[] = array_shift($deck);
        $dealerCards[] = array_shift($deck);
        $playerCards[] = array_shift($deck);

        $this->deck = $deck;
        $this->player_deck = json_encode($playerCards);
        $this->dealer_deck = json_encode($dealerCards);
        $this->save();
        return ["dealer" => $dealerCards, "player" => $playerCards];
    }

    /**
    * Returns total point of given deck
     **/
    public function calculateTotalPoint($deck){
        $total = 0;
        $existAce = false;
        foreach ($deck as $card){
            if ($card->value == 11){
                $existAce = true;
            }
            $total += $card->value;
        }

        if ($total > 21 && $existAce == true){
            $total -= 10;
        }
        return $total;
    }

    /**
    * Adds new card to given deck
     **/
    public function addCard($deck){
        $gameDeck = json_decode($this->deck);
        array_push($deck, array_shift($gameDeck));
        $this->deck = $gameDeck;
        $this->save();
        return $deck;
    }

    /**
     * Returns and saves which player won the game
     **/
    public function getWinner(){
        if ($this->playerPoint() > 21){
            $this->winner = "dealer";
        }elseif ($this->dealerPoint() > 21){
            $this->winner = "player";
        }else{
            $winner = $this->dealerPoint() > $this->playerPoint() ? "dealer":"player" ;
            $this->winner = $winner;
        }

        $this->save();
        return $this->winner;
    }

    /**
    * Returns and saves total point of dealer
     **/
    public function dealerPoint(){
        $point = $this->calculateTotalPoint(json_decode($this->dealer_deck));
        $this->dealer_point = $point;
        $this->save();
        return $point;
    }

    /**
     * Returns and saves total point of player
     **/
    public function playerPoint(){
        $point = $this->calculateTotalPoint(json_decode($this->player_deck));
        $this->player_point = $point;
        $this->save();
        return $point;
    }

    /**
     * Adds and saves new card to player's deck
     **/
    public function addCardToPlayer(){
        $newDeck = $this->addCard(json_decode($this->player_deck));
        $this->player_deck = $newDeck;
        $this->save();
    }

    /**
     * Adds and saves new card to dealer's deck
     **/
    public function addCardToDealer(){
        $newDeck = $this->addCard(json_decode($this->dealer_deck));
        $this->dealer_deck = $newDeck;
        $this->save();
    }
}
