<?php

use FontLib\Table\Type\head;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Authentication
 *
 * PHP version >= 5.6
 *
 * @category   Module
 * @package    Authentication
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Authentication extends CI_Controller{
   
    /**
     * Verify the company generated
     * token
     * 
     * @param String $token
     * 
     * @return JSON
     */
    function VerifyToken($token){
        // Check if post is set
        $post = json_decode(file_get_contents("php://input"), true);
        //
        if(
            empty($post) ||
            !isset($post['TOKEN']) ||
            !$this->db
            ->where('token', $post['TOKEN'])
            ->count_all_results('api_tokens')

        ){
            header("HTTP/1.0 401 Unauthorized");
            exit(0);
        }
        //
        header("HTTP/1.0 200 OK");
    }
}