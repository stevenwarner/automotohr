<?php defined('BASEPATH') or exit('No direct script access allowed');

class CacheHandler
{
    //
    function EnableCache()
    {
        $CI = &get_instance();
        //
        $actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (in_array($actualLink, pagesCashe())) {
            $CI->output->cache(PAGE_CASH_TIME);
        }
    }
}