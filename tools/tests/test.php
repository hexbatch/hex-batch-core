<?php

require_once '../constants.php';

require_once HEXLET_LIB_VENDOR . '/autoload.php';

class SendMail
{
	private $to;

	public function setTo($to)
	{
		$this->to = $to;
	}

	public function send()
	{
		echo "sending mail to {$this->to} now ...\n";
	}

	public function get_me() {
		return 'hello me';
	}

	public function get_json() {
		return '{"apple": false}';
	}
}

$v8 = new V8Js();

$typeExporter = $v8->executeString('(function(typeName, instance) { this[typeName] = instance.constructor; })');
$typeExporter('SendMail', new SendMail());

$script = file_get_contents('test.js');

$v8->executeString( $script);