<?php
// Version
define('VERSION', '3.0.2.0');

 $path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']); // определяем директорию скрипта
 chdir($path_parts['dirname']);

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');


// Cron Controller
require_once(DIR_SYSTEM.'library/cron.php');
$cron = new Cron();

require_once(DIR_SYSTEM . 'library/cron_bootstrap.php');


require_once(DIR_APPLICATION.'cron_tasks.php');

if (!$cron->isRun()) {
    exit;
}

$registry->set('cron', $cron);

$cron->run($registry);


// end file