abstract class MoveStrategy {
	 protected $board;

	 function __contruct(Board $board = null) {
	 	  $this->board = $board;
	 }

	 abstract function pickPlace();

	 function toJson() {
	 	  return array('name' => get_class($this));
	}

	static function fromJson() {
	       $strategy = new static();
	       return $stategy;
	}
}