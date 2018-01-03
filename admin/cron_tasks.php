<?php

$cron_file=DIR_APPLICATION."crontab/crontab.txt";
$dataCron = file_get_contents($cron_file);
$cronTask = unserialize($dataCron);
foreach ($cronTask as $value){
    $cron->call($value['key'],$value['time'], $value['parameters']);
}
 
