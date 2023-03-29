<?php defined('BASEPATH') or exit('No direct script access allowed');

class ProfilerHandler
{
    //
    function EnableProfiler()
    {
        $CI = &get_instance();
        $CI->output->enable_profiler( TRUE );
      
    }
}
