<?php

namespace hexlet\will_lib\exceptions;

use Throwable;

class CurlHelperException extends WillLibException {

	protected $data;

	public function __construct( $data, $message, $code = 0, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
		$this->message = $message;
		$this->data    = $data;

		//overwrites message using the older version of message set above
		$this->message = (string) $this;
	}

	/**
	 * Returns the error type of the exception that has been thrown.
	 */
	public function getData() {
		return $this->data;
	}

	public function __toString() {
		$data = print_r( $this->data, true );
		$code = $this->getCode();

		return "[$code] " . $this->message . "\n$data";
	}


}