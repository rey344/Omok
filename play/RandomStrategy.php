class RamdomStrategy extends MoveStrategy {

      public function pickPlace() {
      	     availableMoves = [];  
	  // Loop through the board to find available places
	     for($i = 0; $i < count($this->board); $i++) {
	     	    for($j = 0; $j < count($this->board[$i]); $j++) {
		    	   if (this->board[$i][$j] === false) {
			      $availableMoves[] = [$i, $j]; // add cordinates to availableMoves
			      }
		}
	}

		// if there are available moves, pick one randomly
		if (!empty($availableMoves)) {
		   $ramdomIndex = array_rand(availableMoves); // select a ramdom move
		   return $availableMoves[$ramdomIndex]; //return the row and column of the move
		} else {
		       return null; // no available moves
	        }
		
	}
		  
		 
}