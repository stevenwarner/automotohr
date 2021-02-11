<?php
exit;
/*
if(isset($_SERVER)) {

    $argc = $_SERVER['argc'];
    $argv = $_SERVER['argv'];
}

// INTERPRETTING INPUT
if ($argc > 1 && isset($argv[1])) {
    $_SERVER['PATH_INFO']   = $argv[1];
    $_SERVER['REQUEST_URI'] = $argv[1];
} else {
    $_SERVER['PATH_INFO']   = '/cron_payments/index/kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM';
    $_SERVER['REQUEST_URI'] = '/cron_payments/index/kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM';
}

/*
|---------------------------------------------------------------
| PHP SCRIPT EXECUTION TIME ('0' means Unlimited)
|---------------------------------------------------------------
|
*//*
set_time_limit(0);
$test_email = 'pauladams.egenie@gmail.com';
        //if($this->input->is_cli_request()) {
        mail($test_email, 'Cron Testing', 'Server host : ' .REPLY_TO. print_r( $_SERVER, true ));
require_once('index.php');