<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Market_place extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('market_place_model');
    }

    public function index($productType = NULL) {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('marketplace')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            if (isset($_SERVER['HTTP_REFERER'])) { //Handle back url - start
                $referer_url = $_SERVER['HTTP_REFERER'];

                if (strpos($referer_url, 'applicant') > 0 || strpos($referer_url, 'employee') > 0) {

                    if (strpos($referer_url, 'applicant') > 0) {
                        $referer_type = 'Applicant';
                    } else {
                        $referer_type = 'Employee';
                    }

                    if (strpos($referer_url, 'background_check') > 0) {
                        $referer_request = 'Background Check';
                    } else {
                        $referer_request = 'Drug Test';
                    }

                    $referer_array = array();
                    $referer_array['btn_text'] = 'Back To ' . $referer_type . ' ' . $referer_request;
                    $referer_array['btn_url'] = $referer_url;
                    $this->session->set_userdata('accurate_background', $referer_array);
                }
            } //Handle back url - end

            $data['session'] = $this->session->userdata('logged_in');
            $data['company_id'] = $data['session']['company_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'market_place'); // First Param: security array, 2nd param: redirect url, 3rd param: function name                      
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $limited_company_status = $this->market_place_model->get_company_status($company_id);
//            echo '<pre>'; print_r($limited_company_status); exit;
            $data['per_job_listing_charge'] = $limited_company_status['per_job_listing_charge'];
            $data['career_site_listings_only'] = $limited_company_status['career_site_listings_only'];
            //cicd = continuos integration continus deployement
            $data['title'] = 'Market Place Products';
            
            if($data['per_job_listing_charge'] == 0 && $productType == 'pay-per-job') {
                $this->session->set_flashdata('message', '<b>Error:</b> Market place product not found!');
                redirect('market_place', 'refresh');
            }
            
            if ($data['career_site_listings_only'] == 1 && $productType != 'drug-testing') {

                if ($data['per_job_listing_charge'] == 1 && $productType == 'pay-per-job') {
                    $productType = 'pay-per-job';
                } else {
                    $productType = 'background-checks';
                }
            }

            if ($productType != '') {
                $data['products'] = $this->market_place_model->getProductType($productType, $company_id);
            } else {
                $data['products'] = $this->market_place_model->getProducts();
            }

            $data['productType'] = $productType;
            $data['employer_id'] = $employer_id;

            if (isset($_POST['action']) && $_POST['action'] == 'addtocart') {
                $formpost = $this->input->post(NULL, TRUE);
                $employer_sid = $formpost['employer_sid'];
                $company_sid = $formpost['company_sid'];
                $product_sid = $formpost['product_sid'];
                $redirecturl = $formpost['redirecturl'];
                $product_check = db_check_cart_products($company_sid, $product_sid);

                if (empty($product_check)) { // this product does not exists in cart against this company
                    $qty = $formpost['qty'];
                    $no_of_days = $formpost['no_of_days'];
                    $function = 'insertToCart';
                    $sid = null;
                } else {
                    if ($product_check[0]['no_of_days'] == 0) { // it is regular product 
                        $qty = $product_check[0]['qty'] + $formpost['qty'];
                        $no_of_days = $formpost['no_of_days'];
                        $function = 'updateCart';
                        $sid = $product_check[0]['sid'];
                    } else { // it is daily listing product
                        $qty = $formpost['qty'];
                        $no_of_days = $formpost['no_of_days'];
                        $function = 'insertToCart';
                        $sid = null;
                    }
                }

                $data = array(  'employer_sid' => $formpost['employer_sid'],
                                'company_sid' => $formpost['company_sid'],
                                'product_sid' => $formpost['product_sid'],
                                'product_name' => $formpost['product_name'],
                                'qty' => $qty,
                                'no_of_days' => $no_of_days,
                                'price' => $formpost['price'],
                                'date' => date('Y-m-d H:i:s'));

                $this->market_place_model->$function($data, $redirecturl, $sid); // add this product to Database
            }


            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/marketplace');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function marketplace_details($sid = NULL) {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('marketplace')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'market_place'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           

            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Market place product not found!');
                redirect('market_place', 'refresh');
            }

            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'Market Place Products Details';
            $data['employer_id'] = $employer_id;
            $data['company_id'] = $company_id;
            $limited_company_status = $this->market_place_model->get_company_status($company_id);
            $data['per_job_listing_charge'] = $limited_company_status['per_job_listing_charge'];
            $data['career_site_listings_only'] = $limited_company_status['career_site_listings_only'];
            $result = $this->market_place_model->getProductsDetail($sid);

            if (empty($result)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Market place product not found!');
                redirect('market_place', 'refresh');
            }

            $data['product'] = $result[0];
            $productType = $data['product']['product_type'];

            if (($data['career_site_listings_only'] == 1) && ($productType != 'drug-testing' && $productType != 'background-checks' && $productType != 'pay-per-job')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Market place product not found!');
                redirect('market_place', 'refresh');

                if ($data['per_job_listing_charge'] == 0 && $productType != 'pay-per-job') {
                    $this->session->set_flashdata('message', '<b>Error:</b> Market place product not found!');
                    redirect('market_place', 'refresh');
                }
            }

            if (isset($_POST['action']) && $_POST['action'] == 'addtocart') {
                $formpost = $this->input->post(NULL, TRUE);
                $employer_sid = $formpost['employer_sid'];
                $company_sid = $formpost['company_sid'];
                $product_sid = $formpost['product_sid'];
                $redirecturl = $formpost['redirecturl'];
                $product_check = db_check_cart_products($company_sid, $product_sid);

                if (empty($product_check)) { // this product does not exists in cart against this company
                    $qty = $formpost['qty'];
                    $no_of_days = $formpost['no_of_days'];
                    $function = 'insertToCart';
                    $sid = null;
                } else {
                    if ($product_check[0]['no_of_days'] == 0) { // it is regular product 
                        $qty = $product_check[0]['qty'] + $formpost['qty'];
                        $no_of_days = $formpost['no_of_days'];
                        $function = 'updateCart';
                        $sid = $product_check[0]['sid'];
                    } else { // it is daily listing product
                        $qty = $formpost['qty'];
                        $no_of_days = $formpost['no_of_days'];
                        $function = 'insertToCart';
                        $sid = null;
                    }
                }

                $data = array(  'employer_sid' => $formpost['employer_sid'],
                                'company_sid' => $formpost['company_sid'],
                                'product_sid' => $formpost['product_sid'],
                                'product_name' => $formpost['product_name'],
                                'qty' => $qty,
                                'no_of_days' => $no_of_days,
                                'price' => $formpost['price'],
                                'date' => date('Y-m-d H:i:s'));

                $this->market_place_model->$function($data, $redirecturl, $sid); // add this product to Database
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/marketplace_details');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}