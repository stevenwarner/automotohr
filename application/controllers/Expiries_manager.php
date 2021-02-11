<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expiries_manager extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('expiries_manager_model');
    }

    public function index() {
        $licenses = $this->expiries_manager_model->GetAllLicensesCron();
        
        $expired = array();
        $expiringInOneDay = array();
        $expiringInSevenDays = array();
        $expiringInThirtyDays = array();
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $TodayPlusOneDay = date_add_day(date('Y-m-d 00:00:00'), 1);
        $TodayPlusSevenDays = date_add_days(date('Y-m-d 00:00:00'), 7);
        $TodayPlusThirtyDays = date_add_days(date('Y-m-d 00:00:00'), 30);
 
        foreach($licenses as $key => $license) {
            $user_details = $this->expiries_manager_model->GetUserDetails($license['users_sid']);

            if(!empty($user_details)){
                $user_details = $user_details[0];
                $licenses[$key]['user_details'] = $user_details;
                $licenseDetails = unserialize($license['license_details']); //Unserialize License Information
                $licenses[$key]['license_details'] = $licenseDetails;
                $expiry = formatDateForDb($licenseDetails['license_expiration_date']);

                if($TodayPlusThirtyDays == $expiry) {
                $expirations_flag = $this->expiries_manager_model->GetCompanyExpiration($user_details['parent_sid']);
                
                    if($expirations_flag[0]['expiration_manager']){
                        $expirations_manager = $this->expiries_manager_model->GetExpirationsManagerEmails($user_details['parent_sid']);
;
                        foreach($expirations_manager as $expiration_handler) {
                            if($expiration_handler['status'] == 'active'){
                                $replacement_array['contact-name'] = $expiration_handler['contact_name'];
                                $replacement_array['remaining-days'] = 30;
                                $replacement_array['first-name'] = $user_details['first_name'];
                                $replacement_array['last-name'] = $user_details['last_name'];
                                $replacement_array['license-type'] = $license['license_type'];
                                
                                log_and_send_templated_email(EXPIRATIONS_MANAGER_NOTIFICATION, $expiration_handler['email'], $replacement_array);
                            }
                        }
                    }
                }
            }
        }
        die();
    }
}
