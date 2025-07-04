<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account_activation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->model('account_activation_model');
    }

    public function index($activationKey = NULL) {


        $data['title'] = "Account Activation";
        if ($activationKey != NULL) {
            $userDataObject = $this->account_activation_model->get_user_detail_by_activation_key($activationKey);
            if ($userDataObject->num_rows() > 0) {
                $data['activationKey'] = $activationKey;
                $companyDetail = $userDataObject->result_array();
                $data['companyDetail'] = $companyDetail[0];


                //Getting Account package products from DB
                $this->load->model('market_place_model');
                $data['products'] = $this->market_place_model->getProductType('account-package');
                $data['standard_products'] = $this->account_activation_model->get_standard_products();
                $data['enterprise_products'] = $this->account_activation_model->get_enterprise_products();


                foreach ($data['standard_products'] as $key => $product) {
                    $data['standard_products'][$key]['special_discount'] = 0;
                    if ($data['companyDetail']['discount_amount'] > 0) {
                        //checking the special discount starts
                        if ($data['companyDetail']['discount_type'] == 'fixed') {
                            $special_discount = $data['companyDetail']['discount_amount'];
                        } else {
                            $special_discount = round((($product['price'] * $data['companyDetail']['discount_amount']) / 100), 2);
                        }
                        $data['standard_products'][$key]['special_discount'] = $special_discount;
                    }
                }

                foreach ($data['enterprise_products'] as $key => $product) {
                    $data['enterprise_products'][$key]['special_discount'] = 0;
                    //checking the special discount starts
                    if ($data['companyDetail']['discount_amount'] > 0) {
                        if ($data['companyDetail']['discount_type'] == 'fixed') {
                            $special_discount = $data['companyDetail']['discount_amount'];
                        } else {
                            $special_discount = round((($product['price'] * $data['companyDetail']['discount_amount']) / 100), 2);
                        }
                        $data['enterprise_products'][$key]['special_discount'] = $special_discount;
                    }
                }
                //checking the special discount ends
//                echo "<pre>";
//                print_r($data['enterprise_products']);
//                echo "</pre>";

                $data['information'] = "Buy a Product to Activate your Company Account.";

                $this->load->view('account_activation/account_activation', $data);
            } else {
                $data['error_message'] = "<b>Notification: </b>The link has Expired!";
                $this->load->view('account_activation/error_file', $data);
            }
        } else {
            $data['error_message'] = "<b>Notification: </b>The link has Expired!";
            $this->load->view('account_activation/error_file', $data);
        }
    }

    public function user_validation() {
        $data = $this->input->post(NULL, TRUE);
        echo check_username_password($data['username'], $data['password']);
    }

}
