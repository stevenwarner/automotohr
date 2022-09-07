<?php defined('BASEPATH') || exit('No direct script access allowed');
require_once(APPPATH . 'libraries/xmlapi.php');

class Cpanelapi extends CI_Controller
{

    //
    private  $server = STORE_DOMAIN;

    //
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
    }


    // List All Subdomains 
    public function listSubdomains()
    {
        //
        $json_client = $this->getXmlinstance();
        $result = $json_client->api2_query($this->getAuthUser(), 'SubDomain', 'listsubdomains');
        $subdomainsList = json_decode($result);

       echo json_encode($subdomainsList->cpanelresult->data);

       //   echo "Total Subdomains: " . count($subdomainsList->cpanelresult->data) . "<br><br>";
     //   foreach ($subdomainsList->cpanelresult->data as $sub_row) {
       //     _e($sub_row, true);
      //  }
        // return $subdomainsList->cpanelresult->data;
    }


    // Delete Subdomain  String Param  as domain name 
    function deleteDomain($domain = '')
    {
        //
        $domain = 'test4.automotohr.com';
        $json_client = $this->getXmlinstance();

        $results = json_decode($json_client->api2_query($this->getAuthUser(), 'SubDomain', 'delsubdomain', array("domain" => $domain)));

        if ($results->cpanelresult->data[0]->result == 1) {
            echo "Deleted";
        } else {
            echo $results->cpanelresult->data[0]->reason;
        }
    }

    // Get XML Instance
    public function getXmlinstance()
    {
        $auth_details = $this->settings_model->fetch_details(THEME_AUTH);
        $auth_user = $auth_details['auth_user'];
        $auth_pass = $auth_details['auth_pass'];
        $auth_pass = "zF.=/zFdqL(xABH`E+~};H/DJ";

        $json_client = new xmlapi($this->server);
        $json_client->set_output('json');
        $json_client->set_port(2083);
        $json_client->password_auth($auth_user, $auth_pass);
        return $json_client;
    }

    //Get Auth User
    public function getAuthUser()
    {
        $auth_details = $this->settings_model->fetch_details(THEME_AUTH);
        return $auth_details['auth_user'];
    }


    public function changeSubdomainIP()
    {

      
    }
}
