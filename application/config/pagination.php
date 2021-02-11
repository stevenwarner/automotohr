<?php defined('BASEPATH') OR exit('No direct script access allowed');

function init_pagination($uri,$total_rows,$per_page=50,$segment=4){
        $ci                          = get_instance();
        $config['per_page']          = $per_page;
        $config['uri_segment']       = $segment;
        $config['base_url']          = base_url().$uri;
        $config['total_rows']        = $total_rows;
        $config['use_page_numbers']  = TRUE;

        $ci->pagination->initialize($config);
        return $config;    
    }
    