<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cc_expires extends Admin_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/card_expiry_model');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($flag = NULL) {
        $current_month = date('m');
        $current_year = date('Y');
        $this->data['page_title'] = 'Credit Card Status';
        $card_type = 'Active';
        $active_type = 'active_cards';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'cc_status');

        if ($flag == 'active_cards' || $flag == NULL) {
            $cards_count = $this->card_expiry_model->get_active_count(1);
        } elseif ($flag == 'inactive_cards') {
            $cards_count = $this->card_expiry_model->get_active_count(0);
            $card_type = 'In-Active';
            $active_type = 'inactive_cards';
        } elseif ($flag == 'expiring_in_month') {
            $cards_count = $this->card_expiry_model->expiring_in_month_count($current_month, $current_year);
            $card_type = 'Expiring Soon';
            $active_type = 'expiring_in_month';
        }else{
            $cards_count = $this->card_expiry_model->expired_cards(null, null, 1);
            $card_type = 'Expired';
            $active_type = 'expired';
        }

        /** pagination * */
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $records_per_page;
        }

        $baseUrl = base_url('manage_admin/cc_expires/' . $flag);
        $uri_segment = 4;
        $config = array();
        $config["base_url"] = $baseUrl;
        $config["total_rows"] = $cards_count;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = ceil($choice);
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data['links'] = $this->pagination->create_links();
        $this->data['references_count'] = $cards_count;
        /** pagination end * */
        // Updated on: 23-04-2019
        // $active_cards = array();
        // $inactive_cards = array();
        // $expiring_in_month = array();
        $cards = array();

        if ($flag == 'active_cards' || $flag == NULL) {
            $cards = $this->card_expiry_model->get_active(1, $records_per_page, $my_offset);
        } else if ($flag == 'inactive_cards') {
            $cards = $this->card_expiry_model->get_active(0, $records_per_page, $my_offset);
        } else if($flag=='expiring_in_month'){
            $cards = $this->card_expiry_model->expiring_in_month($current_month,$current_year, $records_per_page, $my_offset);
        } else{
            $cards = $this->card_expiry_model->expired_cards($records_per_page, $my_offset);
        }
//        echo '<pre>'; print_r($active_cards); echo '</pre>';
        $this->data['card_type'] = $card_type;
        $this->data['active'] = $active_type;
        // $this->data['active_cards'] = $active_cards;
        // $this->data['inactive_cards'] = $inactive_cards;
        // $this->data['expiring_in_month'] = $expiring_in_month;
        $this->data['cards'] = $cards;
        $this->render('manage_admin/cc_expires/index', 'admin_master');
    }

    public function expiry_cron() {
        $current_month = date('m');
        $current_year  = date('Y');
        $active_cards = $this->card_expiry_model->expiring_in_month($current_month,$current_year);
        $email_body = '';

        if(sizeof($active_cards)>0){
            $email_body .= FROM_INFO_EMAIL_DISCLAIMER_MSG;
            $email_body .= 'Following cards are going to expire in a month. <br><br><br>Cards Details:<br><br>';
            foreach($active_cards as $card){
                $number = str_replace('x',"",$card['number']);
                $email_body .= '<b>Company Name:</b> ' . $card['CompanyName'] . '<br>';
                $email_body .= '<b>Expiry Month:</b> ' . $card['expire_month'] . '<br>';
                $email_body .= '<b>Expiry Year:</b> '  . $card['expire_year'] . '<br>';
                $email_body .= '<b>Card Type:</b> '    . $card['type'] . '<br>';
                $email_body .= '<b>Name On Card:</b> ' . $card['name_on_card'] . '<br>';
                $email_body .= '<b>Number:</b> (last 4 digits) '       . $number . '<br>';
                $email_body .= '<br><hr>';
            }

            $email_body .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

            sendMail("info@automotohr.com", "steven@automotohr.com", 'Cards Expiring in a month', $email_body, 'AutomotoHR');
        }
    }
}