<?php

namespace view;

require_once("model/StickGameObserver.php");

class GameView implements \model\StickGameObserver {

	private $numberOfSticksAIDrewLastTime = 0;
	private $playerWon = false;
	private $aiMessage = '';

	public function playerWins() {
		$this->playerWon = true;
	}

	public function playerLoose() {
		$this->playerWon = false;
	}

	public function addAiMessage($aiMessage) {
		$this->aiMessage = $aiMessage;
	}

	private function getAiMessage() : string {
		return '<p>' . $this->aiMessage . '</p>';
	}

	/**
	 * Sets the number of sticks the AI player did
	 */
	public function aiRemoved(\model\StickSelection $sticks) {
		$this->numberOfSticksAIDrewLastTime = $sticks->getAmount();
	}

	public function __construct(\model\LastStickGame $game) {
		$this->game = $game;
	}

	public function show($message) : string {
		if ($this->game->isGameOver()) {

			return 	$message .
					$this->getAiMessage() .
					$this->showSticks() . 
					$this->showWinner() . 
					$this->startOver();
		} else {
			return 	$message .
					$this->getAiMessage() .
					$this->showSticks() . 
					$this->showSelection();
		}
	}

	private function showSticks() : string {
		$numSticks = $this->game->getNumberOfSticks();
		$aiDrew = $this->numberOfSticksAIDrewLastTime;

		$opponentsMove = "";
		if ($aiDrew > 0) {
			$opponentsMove = "Your opponent drew $aiDrew stick". ($aiDrew > 1 ? "s" : "");
		}
		//Make a visualisation of the sticks 
		$sticks = "";
		for ($i = 0; $i < $numSticks; $i++) {
			$sticks .= "I"; //Sticks remaining
		}
		for (; $i < $aiDrew + $numSticks; $i++) {
			$sticks .= "."; //Sticks taken by opponent
		}
		for (; $i < \model\LastStickGame::StartingNumberOfSticks; $i++) {
			$sticks .= "_"; //old sticks
		}
		return "<p>There is $numSticks stick" . ($numSticks > 1 ? "s" : "") ." left</p>
				<p style='font-family: \"Courier New\", Courier, monospace'>$sticks</p>
				<p>$opponentsMove</p>";
	}

	private function showSelection() : string {
		
		$numSticks = $this->game->getNumberOfSticks();

		$ret = "<h2>Select number of sticks</h2>
				<p>The player who draws the last stick looses</p>";
		$ret .= "<ol>";
		for ($i = 1; $i <= 3 && $i < $numSticks; $i++ ) {

			$ret .= "<li><a href='?draw=$i'>Draw $i stick". ($i > 1 ? "s" : ""). "</a></li>";
		}
		$ret .= "<ol>";

		return $ret;
	}

	private function showWinner() : string {
		if ($this->playerWon) {
			return "<h2>Congratulations</h2>
					<p>You force the opponent to draw the last stick!</p>";
		} else {
			return "<h2>Epic FAIL!</h2>
					<p>You have to draw the last stick</p>";
		}
	}

	private function startOver() : string {

		return "<a href='?startOver'>Start new game</a>";
		
	}

	public function playerSelectSticks() : bool {
		return isset($_GET["draw"]);
	}

	public function playerStartsOver() : bool {
		return isset($_GET["startOver"]);
	}

	public function getNumberOfSticks() : \model\StickSelection {
		switch ($_GET["draw"]) {
			case 1 : return \model\StickSelection::One(); break;
			case 2 : return \model\StickSelection::Two(); break;
			case 3 : return \model\StickSelection::Three(); break;
		}
		throw new \Exception("<h1>Unauthorized input</h1>");
	}
}
