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
// Run 
// $hook['post_controller'][] = array(
//     'filepath' => 'hooks',
//     'filename' => 'AppVerify.php',
//     'function' => 'loginToAPI',
//     'class' => 'AppVerify'
// );