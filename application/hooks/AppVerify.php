<?php
/**
 * Checks apps
 */
class AppVerify{
    function checkIfAppIsEnabled(){ checkIfAppIsEnabled(FALSE, TRUE); }

    /**
     *  Login the user to the API server
     *  and generate tokens to be used later on
     * 
     *  @version 1.0
     *  @date    14/09/2021
     *  @author  Mubashir Ahmed 
     */
    function loginToAPI(){
        // // get CI instance
        // $CI = & get_instance();
        // // load the library
        // $CI->load->library('Api_auth');
        // // call the event
        // $CI->api_auth->checkAndLogin();
    }
}
