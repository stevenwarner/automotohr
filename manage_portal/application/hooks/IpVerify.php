<?php
class IpVerify{
     /**
     * 
     *  @version 1.0
     *  @author  Nisar Ahmed 
     */
    // check Ipis
    function ipCheck(){

        die('sdfsdf');
        
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
       
        $user_ip = strpos($ipaddress, ',') !== FALSE ? explode(',', $ipaddress)[0] : $ipaddress;

        $blockedip = $this-> db->select('ip_address')->where('ip_address', $user_ip)->get('blocked_ips')->row();
        if(!empty($blockedip->ip_address)){
            echo "This IP is blocked: ". $blockedip->ip_address;
            exit();
        }
    }
}
