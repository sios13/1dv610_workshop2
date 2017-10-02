<?php

namespace controller;

require_once("model/LastStickGame.php");
require_once("view/GameView.php");

class PlayGame
{
	/**
	 * @var \model\LastStickGame
	 */
	private $game;

	/**
	 * @var \view\GameView
	 */
	private $view;

	private $message = "";

	public function __construct()
	{
		$this->game = new \model\LastStickGame();
		$this->view = new \view\GameView($this->game);
	}

	public function runGame() : string
	{
		//Handle input
		if ($this->game->isGameOver()) {
			$this->doGameOver();
		} else {
			$this->playGame();
		}

		//Generate Output
		return $this->view->show($this->message);
	}

	/**
	* Called when game is still running
	*/
	private function playGame()
	{
		if ($this->view->playerSelectSticks()) {
			try {
				$sticksDrawnByPlayer = $this->view->getNumberOfSticks();
				$this->game->playerSelectsSticks($sticksDrawnByPlayer);
			} catch(\Exception $e) {
				$this->message = $e->getMessage();
			}

			$this->game->playerWinsOrAITurn($this->view);
		}
	}

	private function doGameOver()
	{
		if ($this->view->playerStartsOver()) {
			$this->game->newGame();
		}		
	}
}
