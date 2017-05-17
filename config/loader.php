<?php
$loader = new \Phalcon\Loader();
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
	array(
		APP_PATH . $config->application->controllersDir,
		APP_PATH . $config->application->libraryDir,
		APP_PATH . $config->application->modelsDir,
		APP_PATH . $config->application->tasksDir,
	)
)->register();

define("DEBUG_LOCATION", $_SERVER['DOCUMENT_ROOT']."/FormBuilder/app/logs/db.log");

require $_SERVER['DOCUMENT_ROOT'].'/core/app/library/autoload.php';