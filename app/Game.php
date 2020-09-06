<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = "game";

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
}
