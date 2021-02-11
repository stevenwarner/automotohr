<?php
echo $_SERVER['HTTP_HOST'];

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

// INTERPRETTING INPUT
if ($argc > 1 && isset($argv[1])) {
    $_SERVER['PATH_INFO']   = $argv[1];
    $_SERVER['REQUEST_URI'] = $argv[1];
    $_SERVER['HTTP_HOST'] = 'https://www.automotohr.com';
} else {
    $_SERVER['PATH_INFO']   = '/crons/index';
    $_SERVER['REQUEST_URI'] = '/crons/index';
    $_SERVER['HTTP_HOST'] = 'https://www.automotohr.com';
}


/*
|---------------------------------------------------------------
| PHP SCRIPT EXECUTION TIME ('0' means Unlimited)
|---------------------------------------------------------------
|
*/
//mail('j.taylor.title@gmail.com', 'cron.php executed', 'file accessible');

set_time_limit(0);

require_once('index.php');