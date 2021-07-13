<?php

/**
 * Make performance management urls
 * 
 * @employee  Mubashir Ahmed
 * @date      02/02/2021
 * 
 * @param Array|String $args (Optional)
 * 
 * @return String
 */
if(!function_exists('purl')){
    function purl($args = ''){
        $url =  rtrim(base_url('performance-management'), '/');
        //
        if(is_array($args) && count($args)) $url .=  implode('/', $args);
        else if(!empty($args)) $url .= '/'.$args;
        //
        return rtrim($url, '/');
    }
}

/**
 * Send JSON response to browser
 * 
 * @employee Mubashir Ahmed
 * @date     02/10/2021
 * 
 * @param Array $in
 * @return VOID
 */
if(!function_exists('res')){
    function res($in){
        header('Content-Type: application/json');
        echo json_encode($in);
        exit(0);
    }
}