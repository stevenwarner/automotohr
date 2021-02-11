<?php
echo str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
echo '<br>Base URL: '.base_url();
echo '<br>Parent Base URL: '.(isset($_SERVER['HTTPS']) ? STORE_PROTOCOL_SSL : STORE_PROTOCOL ).$_SERVER['HTTP_HOST'];
echo '<pre>';
print_r($_SESSION);
echo '<hr>';
print_r($_SERVER);
?>