<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paypal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        require_once(APPPATH . 'libraries/PayPal-PHP-SDK/autoload.php');
    }
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data["session"]["company_detail"]["sid"];
            $data = array();
            
            

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/webapps/developer/applications/myapps
$clientId = 'AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS';
$clientSecret = 'EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKYQvQSBngp4PTTVWkPZRbL';

/** @var \Paypal\Rest\ApiContext $apiContext */
$apiContext = getApiContext($clientId, $clientSecret);
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/eeo_applicants_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
