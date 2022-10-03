<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller'][] = array(
        'filepath' => 'hooks',
        'filename' => 'AppVerify.php',
        'function' => 'checkIfAppIsEnabled',
        'class' => 'AppVerify'
);

/**
 * Stop the not allowed traffic
 */
$hook['pre_controller'][] = array(
        'filepath' => 'hooks',
        'filename' => 'security.php',
        'function' => 'checkBlockedIps',
        'class' => 'Security'
);

$AHR = getCreds("AHR");
//
if ($AHR->PROFILER_SHOW || $AHR->PROFILER_LOG) {
    // Profiler for all controllers
    $hook['post_controller_constructor'][] = array(
        'class'    => 'ProfilerHandler',
        'function' => 'EnableProfiler',
        'filename' => 'appprofiler.php',
        'filepath' => 'hooks',
        'params'   => array()
    );
} 

// Cache Pages
$hook['post_controller_constructor'][] = array(
    'class'    => 'CacheHandler',
    'function' => 'EnableCache',
    'filename' => 'cache.php',
    'filepath' => 'hooks',
    'params'   => array()
);