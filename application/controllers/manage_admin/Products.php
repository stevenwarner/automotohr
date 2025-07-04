<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('manage_admin/products_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'list_products';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Market Place Products';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $db_products = $this->products_model->get_products();
        $this->data['db_products'] = $db_products;
        $this->render('manage_admin/products/listings_view');
    }

    public function add_new_product() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'add_new_product';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Add New Market Place Product';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Product Name', 'trim|required|is_unique[products.name]');
        $this->form_validation->set_rules('short_description', 'Short Description', 'trim');
        $this->form_validation->set_rules('detailed_description', 'Detailed Description', 'trim');
        $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
        $this->form_validation->set_rules('cost_price', 'Cost Price', 'trim|numeric|required');
        $this->form_validation->set_rules('availability_from', 'Start Date', 'trim');
        $this->form_validation->set_rules('number_of_postings', 'No of Listings', 'trim|numeric|required');
        $this->form_validation->set_rules('expiry_days', 'Expiry Days', 'trim|numeric|greater_than[0]|required');
        $this->form_validation->set_rules('active', 'active', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->load->helper('form');
            $this->render('manage_admin/products/add_new_product');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            
            foreach ($formpost as $key => $value) {
                if ($key != 'availability_to' && $key != 'availability_from' && $key != 'active' && $key != 'detailed_description' && $key != 'product_brand') { // exclude these values from array
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    
                    $product_data[$key] = $value;
                }
            }

            if (empty($formpost['product_brand'])) {
                $product_data['product_brand'] = NULL;
            } else {
                $product_data['product_brand'] = $formpost['product_brand'];
            }

            if (empty($formpost["daily"])) {
                $product_data['daily'] = 0;
            } else {
                $product_data['daily'] = 1;
            }

            if (empty($formpost["active"])) {
                $product_data['active'] = 0;
            } else {
                $product_data['active'] = 1;
            }

            if (empty($formpost["featured"])) {
                $product_data['featured'] = 0;
            } else {
                $product_data['featured'] = 1;
            }

            if (empty($formpost["in_market"])) {
                $product_data['in_market'] = 0;
            } else {
                $product_data['in_market'] = 1;
            }

            if (empty($formpost["includes_deluxe_theme"])) {
                $product_data['includes_deluxe_theme'] = 0;
            } else {
                $product_data['includes_deluxe_theme'] = 1;
            }

            if (empty($formpost["includes_facebook_api"])) {
                $product_data['includes_facebook_api'] = 0;
            } else {
                $product_data['includes_facebook_api'] = 1;
            }

            if (!empty($formpost["maximum_number_of_employees"]) && intval($formpost["maximum_number_of_employees"]) > 0) {
                $product_data['maximum_number_of_employees'] = $formpost['maximum_number_of_employees'];
            }else{
                $product_data['maximum_number_of_employees'] = null;
            }

            if (!empty($formpost["detailed_description"])) {
                $product_data['detailed_description'] = html_entity_decode($formpost['detailed_description']);
            }

            if ($formpost['availability_from'] != "" && !empty($formpost['availability_from'])) {
                $timestamp_availability_from = explode('-', $formpost['availability_from']);
                $month_availability_from = $timestamp_availability_from[0];
                $day_availability_from = $timestamp_availability_from[1];
                $year_availability_from = $timestamp_availability_from[2];
                $availability_from = $year_availability_from . '-' . $month_availability_from . '-' . $day_availability_from;
                $product_data['availability_from'] = $availability_from;
            }

            if ($formpost['availability_to'] != "" && !empty($formpost['availability_to'])) {
                $timestamp_availability_to = explode('-', $formpost['availability_to']);
                $month_availability_to = $timestamp_availability_to[0];
                $day_availability_to = $timestamp_availability_to[1];
                $year_availability_to = $timestamp_availability_to[2];
                $availability_to = $year_availability_to . '-' . $month_availability_to . '-' . $day_availability_to;
                $product_data['availability_to'] = $availability_to;
            }

            //uploading image to AWS
            if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != '') {
                $file = explode(".", $_FILES["product_image"]["name"]);
                $file_name = str_replace(" ", "-", $file[0]);
                $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                $aws = new AwsSdk();
                $aws->putToBucket($pictures, $_FILES["product_image"]["tmp_name"], AWS_S3_BUCKET_NAME);
                $product_data['product_image'] = $pictures;
            }

            $this->products_model->save_product($product_data);
            redirect('manage_admin/products', 'refresh');
        }
    }

    public function edit($edit_id = NULL) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'edit_product';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($edit_id == NULL) {
            redirect('manage_admin/products', 'refresh');
        } else {
            $this->data['page_title'] = 'Edit Market Place Product';
            $edit_data = $this->products_model->edit_product($edit_id);

            if (empty($edit_data)) {//if model return data not found
                $this->session->set_flashdata('message', "Product Not Found");
                redirect('manage_admin/products', 'refresh');
            }

            if ($edit_data[0]['availability_from'] != null) {
                $date = $edit_data[0]['availability_from'];
                $dateArray = explode("-", $date);
                $edit_data[0]['availability_from'] = $dateArray[1] . "-" . substr($dateArray[2], 0, 2) . "-" . $dateArray[0];
            }

            if ($edit_data[0]['availability_to'] != null) {
                $date = $edit_data[0]['availability_to'];
                $dateArray = explode("-", $date);
                $edit_data[0]['availability_to'] = $dateArray[1] . "-" . substr($dateArray[2], 0, 2) . "-" . $dateArray[0];
            }

            if ($edit_data[0]['daily'] == 1) {
                $edit_data[0]['daily'] = 'checked';
            }

            if ($edit_data[0]['active'] == 1) {
                $edit_data[0]['active'] = 'checked';
            }

            if ($edit_data[0]['featured'] == 1) {
                $edit_data[0]['featured'] = 'checked';
            }

            if ($edit_data[0]['in_market'] == 1) {
                $edit_data[0]['in_market'] = 'checked';
            }


            if ($edit_data[0]['includes_deluxe_theme'] == 1) {
                $edit_data[0]['includes_deluxe_theme'] = 'checked';
            }

            if ($edit_data[0]['includes_facebook_api'] == 1) {
                $edit_data[0]['includes_facebook_api'] = 'checked';
            }

            $this->data['edit_data'] = $edit_data[0];

            if (isset($_POST) && $edit_data[0]['name'] != $this->input->post('name')) {
                $this->form_validation->set_rules('name', 'Product Name', 'trim|required|is_unique[products.name]');
            } else {
                $this->form_validation->set_rules('name', 'Product Name', 'trim|required');
            }

            $this->form_validation->set_rules('short_description', 'Short Description', 'trim');
            $this->form_validation->set_rules('detailed_description', 'Detailed Description', 'trim');
            $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
            $this->form_validation->set_rules('cost_price', 'Cost Price', 'trim|numeric|required');
            $this->form_validation->set_rules('availability_from', 'Start Date', 'trim');
            $this->form_validation->set_rules('number_of_postings', 'No of Listings', 'trim|numeric|required');
            $this->form_validation->set_rules('expiry_days', 'Expiry Days', 'trim|numeric|greater_than[0]|required');
            $this->form_validation->set_rules('active', 'active', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->load->helper('form');
                $this->render('manage_admin/products/edit_product');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                
                foreach ($formpost as $key => $value) {
                    if ($key != 'availability_to' && $key != 'availability_from' && $key != 'active' && $key != 'old_picture' && $key != 'detailed_description' && $key != 'product_brand') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        
                        $product_data[$key] = $value;
                    }
                }

                if (empty($formpost['product_brand'])) {
                    $product_data['product_brand'] = NULL;
                } else {
                    $product_data['product_brand'] = $formpost['product_brand'];
                }
                
                if (empty($formpost['daily'])) {
                    $product_data['daily'] = 0;
                } else {
                    $product_data['daily'] = 1;
                }

                if (empty($formpost["active"])) {
                    $product_data['active'] = 0;
                } else {
                    $product_data['active'] = 1;
                }

                if (empty($formpost["featured"])) {
                    $product_data['featured'] = 0;
                } else {
                    $product_data['featured'] = 1;
                }

                if (empty($formpost["in_market"])) {
                    $product_data['in_market'] = 0;
                } else {
                    $product_data['in_market'] = 1;
                }

                if (empty($formpost["includes_deluxe_theme"])) {
                    $product_data['includes_deluxe_theme'] = 0;
                } else {
                    $product_data['includes_deluxe_theme'] = 1;
                }

                if (empty($formpost["includes_facebook_api"])) {
                    $product_data['includes_facebook_api'] = 0;
                } else {
                    $product_data['includes_facebook_api'] = 1;
                }

                if (!empty($formpost["maximum_number_of_employees"]) && intval($formpost["maximum_number_of_employees"]) > 0) {
                    $product_data['maximum_number_of_employees'] = $formpost['maximum_number_of_employees'];
                } else {
                    $product_data['maximum_number_of_employees'] = null;
                }


                if (!empty($formpost["detailed_description"])) {
                    $product_data['detailed_description'] = html_entity_decode($formpost['detailed_description']);
                }

                if ($formpost['availability_from'] != "" && !empty($formpost['availability_from'])) {
                    $timestamp_availability_from = explode('-', $formpost['availability_from']);
                    $month_availability_from = $timestamp_availability_from[0];
                    $day_availability_from = $timestamp_availability_from[1];
                    $year_availability_from = $timestamp_availability_from[2];
                    $availability_from = $year_availability_from . '-' . $month_availability_from . '-' . $day_availability_from;
                    $product_data['availability_from'] = $availability_from;
                }

                if ($formpost['availability_to'] != "" && !empty($formpost['availability_to'])) {
                    $timestamp_availability_to = explode('-', $formpost['availability_to']);
                    $month_availability_to = $timestamp_availability_to[0];
                    $day_availability_to = $timestamp_availability_to[1];
                    $year_availability_to = $timestamp_availability_to[2];
                    $availability_to = $year_availability_to . '-' . $month_availability_to . '-' . $day_availability_to;
                    $product_data['availability_to'] = $availability_to;
                }

                if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != '') {
                    $file = explode(".", $_FILES['product_image']['name']);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    $aws = new AwsSdk();
                    $aws->putToBucket($pictures, $_FILES["product_image"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $product_data['product_image'] = $pictures;
                } else {
                    $product_data['product_image'] = $formpost["old_picture"];
                }

                $msg = $this->products_model->update_product($edit_id, $product_data);
                $this->session->set_flashdata('message', $msg);
                redirect('manage_admin/products', 'refresh');
            }
        }
    }

    function product_task() {
        $redirect_url       = 'manage_admin';
        $function_name      = 'activate_deactivate_products';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $action = $this->input->post("action");
        $product_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->products_model->delete_product($product_id);
        } else if ($action == 'activate') {
            $this->products_model->activate_product($product_id);
            $this->session->set_flashdata('message', 'Product Activated Successfully!');
        } else if ($action == 'deactivate') {
            $this->products_model->deactivate_product($product_id);
            $this->session->set_flashdata('message', 'Product Deactivated Successfully!');
        }
    }

    public function clone_product($edit_id = NULL) {
        $redirect_url       = 'manage_admin';
        $function_name      = 'clone_product';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($edit_id == NULL) {
            redirect('manage_admin/products', 'refresh');
        } else {
            $this->data['page_title'] = 'Clone Market Place Product';
            $edit_data = $this->products_model->edit_product($edit_id);

            if ($edit_data[0]['availability_from'] != null) {
                $date = $edit_data[0]['availability_from'];
                $dateArray = explode("-", $date);
                $edit_data[0]['availability_from'] = $dateArray[1] . "-" . substr($dateArray[2], 0, 2) . "-" . $dateArray[0];
            }

            if ($edit_data[0]['availability_to'] != null) {
                $date = $edit_data[0]['availability_to'];
                $dateArray = explode("-", $date);
                $edit_data[0]['availability_to'] = $dateArray[1] . "-" . substr($dateArray[2], 0, 2) . "-" . $dateArray[0];
            }

            if ($edit_data[0]['daily'] == 1) {
                $edit_data[0]['daily'] = 'checked';
            }


            if ($edit_data[0]['active'] == 1) {
                $edit_data[0]['active'] = 'checked';
            }

            if ($edit_data[0]['featured'] == 1) {
                $edit_data[0]['featured'] = 'checked';
            }

            if ($edit_data[0]['in_market'] == 1) {
                $edit_data[0]['in_market'] = 'checked';
            }

            if ($edit_data[0]['includes_deluxe_theme'] == 1) {
                $edit_data[0]['includes_deluxe_theme'] = 'checked';
            }

            if ($edit_data[0]['includes_facebook_api'] == 1) {
                $edit_data[0]['includes_facebook_api'] = 'checked';
            }

            $this->data['edit_data'] = $edit_data[0];
            
            if ($edit_data == 0) {
                $this->session->set_flashdata('message', "Product Not Found");
                redirect('manage_admin/products', 'refresh');
            }

            $this->form_validation->set_rules('name', 'Product Name', 'trim|required|is_unique[products.name]');
            $this->form_validation->set_rules('short_description', 'Short Description', 'trim');
            $this->form_validation->set_rules('detailed_description', 'Detailed Description', 'trim');
            $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
            $this->form_validation->set_rules('availability_from', 'Start Date', 'trim');
            $this->form_validation->set_rules('number_of_postings', 'No of Listings', 'trim|numeric|required');
            $this->form_validation->set_rules('expiry_days', 'Expiry Days', 'trim|numeric|greater_than[0]|required');
            $this->form_validation->set_rules('active', 'active', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->load->helper('form');
                $this->render('manage_admin/products/clone_product');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                
                foreach ($formpost as $key => $value) {
                    if ($key != 'availability_to' && $key != 'availability_from' && $key != 'active' && $key != 'old_picture' && $key != 'detailed_description' && $key != 'product_brand') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        
                        $product_data[$key] = $value;
                    }
                }

                if (empty($formpost['product_brand'])) {
                    $product_data['product_brand'] = NULL;
                } else {
                    $product_data['product_brand'] = $formpost['product_brand'];
                }
                
                if (empty($formpost['daily'])) {
                    $product_data['daily'] = 0;
                } else {
                    $product_data['daily'] = 1;
                }

                if (empty($formpost["active"])) {
                    $product_data['active'] = 0;
                } else {
                    $product_data['active'] = 1;
                }


                if (empty($formpost["featured"])) {
                    $product_data['featured'] = 0;
                } else {
                    $product_data['featured'] = 1;
                }

                if (empty($formpost["in_market"])) {
                    $product_data['in_market'] = 0;
                } else {
                    $product_data['in_market'] = 1;
                }

                if (empty($formpost["includes_deluxe_theme"])) {
                    $product_data['includes_deluxe_theme'] = 0;
                } else {
                    $product_data['includes_deluxe_theme'] = 1;
                }

                if (empty($formpost["includes_facebook_api"])) {
                    $product_data['includes_facebook_api'] = 0;
                } else {
                    $product_data['includes_facebook_api'] = 1;
                }

                if (!empty($formpost["maximum_number_of_employees"]) && intval($formpost["maximum_number_of_employees"]) > 0) {
                    $product_data['maximum_number_of_employees'] = $formpost['maximum_number_of_employees'];
                } else {
                    $product_data['maximum_number_of_employees'] = null;
                }

                if (!empty($formpost["detailed_description"])) {
                    $product_data['detailed_description'] = html_entity_decode($formpost['detailed_description']);
                }
                
                if ($formpost['availability_from'] != "" && !empty($formpost['availability_from'])) {
                    $timestamp_availability_from = explode('-', $formpost['availability_from']);
                    $month_availability_from = $timestamp_availability_from[0];
                    $day_availability_from = $timestamp_availability_from[1];
                    $year_availability_from = $timestamp_availability_from[2];
                    $availability_from = $year_availability_from . '-' . $month_availability_from . '-' . $day_availability_from;
                    $product_data['availability_from'] = $availability_from;
                }

                if ($formpost['availability_to'] != "" && !empty($formpost['availability_to'])) {
                    $timestamp_availability_to = explode('-', $formpost['availability_to']);
                    $month_availability_to = $timestamp_availability_to[0];
                    $day_availability_to = $timestamp_availability_to[1];
                    $year_availability_to = $timestamp_availability_to[2];
                    $availability_to = $year_availability_to . '-' . $month_availability_to . '-' . $day_availability_to;
                    $product_data['availability_to'] = $availability_to;
                }
                
                if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != '') { //uploading image to AWS
                    $file = explode(".", $_FILES["product_image"]["name"]);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    $aws = new AwsSdk();
                    $aws->putToBucket($pictures, $_FILES["product_image"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $product_data['product_image'] = $pictures;
                } else {
                    $product_data['product_image'] = $formpost["old_picture"];
                }

                $msg = $this->products_model->clone_product($product_data);
                $this->session->set_flashdata('message', $msg);
                redirect('manage_admin/products', 'refresh');
            }
        }
    }

}
