<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook_configuration extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('facebook_configuration_model');
    }

    public function facebook_configuration() {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'facebook_configuration'); // Param2: Redirect URL, Param3: Function Name
            
            $company_id                                                         = $data["session"]["company_detail"]["sid"];
            $employer_id                                                        = $data["session"]["employer_detail"]["sid"];
            $data['title']                                                      = "Facebook App Configuration";

            //Handle Post
            if (isset($_POST['perform_action'])) {
                if ($_POST['perform_action'] == 'save_facebook_app_config') {
                    $fbPageUrl = $_POST['fb_page_url'];
                    $fbAppId = $_POST['fb_app_id'];
                    $fbAppSecret = $_POST['fb_app_secret'];
                    $fbUniqueIdentifier = $_POST['fb_unique_identifier'];
                    $sid = $_POST['sid'];

                    if (empty($sid)) {
                        $sid = null;
                    }

                    //echo $sid;
                    $fbPageUrlClean = explode('?', $fbPageUrl);
                    $fbPageUrlClean = $fbPageUrlClean[0];
                    $this->facebook_configuration_model->Save($sid, $company_id, $fbAppId, $fbAppSecret, $fbPageUrl, $fbUniqueIdentifier);
                }
            }

            $fb_config = $this->facebook_configuration_model->GetFacebookConfiguration($company_id);

            //Check if Feature Purchased and Is Within Time Period - Start
            $feature_Available = 0;
            $fb_unique_identifier = '';


            if(empty($fb_config)){
                $this->facebook_configuration_model->Save(null, $company_id, '', '', '', '');
            }

            $fb_config = $this->facebook_configuration_model->GetFacebookConfiguration($company_id);


            if(!empty($fb_config)) {
                $feature_purchased = $fb_config['purchased'];
                $expiry_date = $fb_config['expiry_date'];
                $purchase_date = $fb_config['purchase_date'];

                $fb_unique_identifier = '';

                if ($fb_config['fb_unique_identifier'] == null) {
                    $fb_unique_identifier = generateRandomString(40);
                    $this->facebook_configuration_model->UpdateUniqueIdentifier($fb_config['sid'], $fb_unique_identifier);
                } else {
                    $fb_unique_identifier = $fb_config['fb_unique_identifier'];
                }

                $feature_Available = IsFeatureAvailable($feature_purchased, $purchase_date, $expiry_date);
            } else {
                $feature_Available = 0;
            }

            $package_details = $this->facebook_configuration_model->get_current_package_details($company_id);

            if(!empty($package_details)){
                if($package_details['includes_facebook_api'] == 1) {
                    $feature_Available = 1;
                }
            }

            //Check if Feature Purchased and Is Within Time Period - End

            $data['fb_unique_identifier'] = $fb_unique_identifier;
            $data['fb_available'] = $feature_Available;
            $data['fb_config'] = $fb_config;

            $this->load->view('main/header', $data);
            $this->load->view('facebook_configuration/facebook_configuration');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function facebook_api_instructions(){
        $data = array();
        $data['title'] = 'Facebook API Instructions';
        $this->load->view('jobs_for_facebook/facebook_api_instructions', $data);
    }
}
