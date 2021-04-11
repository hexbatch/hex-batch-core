<?php

namespace hexlet\will_lib;

class LibConTestStringAndEmpty {
	public $foo;

	public function __construct( $foo ) {
		$this->foo = $foo;
	}

	public function __toString() {
		return $this->foo;
	}

	public function __isset( $name ) {
		return false;
	}
}