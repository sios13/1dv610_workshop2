<?php

namespace model;

class AIPlayer
{
	private $message = '';

	public function getMessage()
	{
		return $this->message;
	}

	/**
	* Slightly evil AI player
	* @param int $amountOfSticksLeft
	* @return \model\StickSelection
	*/
	public function getSelection($amountOfSticksLeft) {

		$drawInteger = $this->determineDrawInteger($amountOfSticksLeft);

		//change from integer into valid StickSelection
		return $this->changeIntegerToStickSelection($drawInteger);
	}

	private function determineDrawInteger($amountOfSticksLeft) {
		$desiredAmountAfterDraw = array(21, 17, 13, 9, 5, 1);

		foreach ($desiredAmountAfterDraw as $desiredStics) {
			if ($amountOfSticksLeft > $desiredStics ) {
				$difference = $amountOfSticksLeft - $desiredStics;

				if ($difference > 3 || $difference < 1) {
					$drawInteger = rand() % 3 + 1; // [1-3]
					$this->message = "AIPlayer - \"Grr...\"";
				} else {
					$drawInteger = $difference;
					$this->message = "AIPlayer - \"Got you, you have already lost!!!\"";
				}
				break;
			}
			
		}

		return $drawInteger;
	}

	private function changeIntegerToStickSelection($drawInteger) {
		switch ($drawInteger) {
			case 1 : return StickSelection::One(); break;
			case 2 : return StickSelection::Two(); break;
			case 3 : return StickSelection::Three(); break;
		}

		//should never go here
		assert(false); 
	}
}