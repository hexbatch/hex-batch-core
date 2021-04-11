<?php namespace hexlet;

use hexlet\will_lib\DBSelector;

use hexlet\will_lib\JsonHelper;

require_once 'constants.php';

require_once HEXLET_LIB_VENDOR . '/autoload.php';


$mydb = DBSelector::getConnection('hexlet');
JsonHelper::print_nice($_ENV);
phpinfo();
