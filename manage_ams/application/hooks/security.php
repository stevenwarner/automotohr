<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Runs on every request
 * 
 * @author  AutommotoHR <www.automotohr.com>
 * @version 1.0
 */
class Security{

    /**
     * Holds the database conenction
     * 
     * @type reference
     */
    private $con;

    /**
     * Holds the database username
     * 
     * @type string
     */
    private $username;

    /**
     * Holds the database password
     * 
     * @type string
     */
    private $password;

    /**
     * Holds the database host
     * 
     * @type string
     */
    private $host;

    /**
     * Holds the database name
     * 
     * @type string
     */
    private $database;

    /**
     * Sets the credentials 
     */
    function __construct(){
        //
        $DB = getCreds('AHR')->DB;
        //
        $this->username = $DB->User;
        $this->host = $DB->Host;
        $this->password = $DB->Password;
        $this->database = $DB->Database;
    }

    /**
     * Checks if an IP is blocked or not
     * 
     */
    public function checkBlockedIps(){
        //
        if($this->getDataCountFromDB("SELECT admin_sid FROM `blocked_ips` WHERE `ip_address` = '".($this->getUserIp()). "' and is_block = 1 LIMIT 1;") !== 0){
            exit(0);
        }
    }

    /**
     * Get's the user IP
     * 
     * @return string
     */
    private function getUserIp(){
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
       
        return  strpos($ipaddress, ',') !== FALSE ? explode(',', $ipaddress)[0] : $ipaddress;
    }

    /**
     * Connect to the database
     * 
     * @return reference
     */
    private function dbConnect(){
        //
        $this->con = new mysqli($this->host, $this->username, $this->password, $this->database);
        // Check connection
        if ($this->con->connect_error) {
            exit(0);
        }
        //
        return $this;
    }

    /**
     * Get the count of a query
     * 
     * @param string $sql
     * 
     * @return number
     */
    private function getDataCountFromDB($sql){
        //
        $this->dbConnect();
        //
        $result = $this->con->query($sql);
        //
        if(!$result){
            return 0;
        }
        //
        return $result->num_rows;
    }
}
