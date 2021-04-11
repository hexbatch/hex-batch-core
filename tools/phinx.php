<?php namespace hexlet;

      use hexlet\will_lib\DBSelector;

      use hexlet\will_lib\JsonHelper;

      require_once 'constants.php';

      require_once HEXLET_LIB_VENDOR . '/autoload.php';





//the environment will be whatever is set by GOKABAM_ENVIRONMENT, so does not matter if E is set in phinx command
return array(
	"paths" => array(
		"migrations" => "db/migrations",
		"seeds" => "db/seeds"
	),
	"environments" => array(
		"default_migration_table" => "phinxlog",
		"default_database" => "dev",
		"dev" => array(
			"adapter" => "mysql",
			"host" => Settings::get_setting('DB_HOST'),
			"name" => Settings::get_setting('DB_NAME'),
			"user" => Settings::get_setting('DB_USER'),
			"pass" => Settings::get_setting('DB_PASSWORD'),
			"port" => Settings::get_setting('DB_PORT'),
			"charset" => Settings::get_setting('DB_CHARSET')
		),
		"development" => array(
			"adapter" => "mysql",
			"host" => Settings::get_setting('DB_HOST'),
			"name" => Settings::get_setting('DB_NAME'),
			"user" => Settings::get_setting('DB_USER'),
			"pass" => Settings::get_setting('DB_PASSWORD'),
			"port" => Settings::get_setting('DB_PORT'),
			"charset" => Settings::get_setting('DB_CHARSET')
		),
		"production" => array(
			"adapter" => "mysql",
			"host" => Settings::get_setting('DB_HOST'),
			"name" => Settings::get_setting('DB_HOST'),
			"user" => Settings::get_setting('DB_HOST'),
			"pass" => Settings::get_setting('DB_HOST'),
			"port" => Settings::get_setting('DB_PORT'),
			"charset" => Settings::get_setting('DB_CHARSET')
		)
	),
	"version_order" => "creation"
);