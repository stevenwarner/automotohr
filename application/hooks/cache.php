<?php defined('BASEPATH') or exit('No direct script access allowed');

class CacheHandler
{
    //
    function EnableCache()
    {
        $CI = &get_instance();
        $info =  getDomainInfo();
        //
        if ($info['host'] === 'ahr') {
            if (in_array($_SERVER['REQUEST_URI'], pagesCashe())) {
               $CI->output->cache(PAGE_CASH_TIME);

            }
        }
    }
}
