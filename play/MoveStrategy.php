<?php

abstract class MoveStrategy {
    protected $board;

    // Constructor to set the board object
    function __construct(Board $board) {
        $this->board = $board;
    }

    abstract function pickPlace();

    function toJson() {
        return array('name' => get_class($this));
    }

    static function fromJson() {
        $strategy = new static();
        return $strategy;
    }
}
?>
