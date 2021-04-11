<?php /** @noinspection PhpUnused */

namespace hexlet\will_lib;

class MYDBValue {
	/**
	 * @var string $expression
	 */
	public $expression = null;

	/**
	 * @var mixed $value
	 */
	public $value = null;

	/**
	 * @var string $flag
	 */
	public $flag = null;


	public function __construct() {
		$this->flag = 's';
	}
}