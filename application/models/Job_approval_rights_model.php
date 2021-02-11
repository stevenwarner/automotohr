<?php
class Job_approval_rights_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function UpdateRights($company_sid, $user_sid, $approval_rights_status, $module = 'jobs') {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_sid);
        $data = array();

        if ($module == 'jobs') {
            $data['has_job_approval_rights'] = $approval_rights_status;
        } elseif ($module == 'applicants') {
            $data['has_applicant_approval_rights'] = $approval_rights_status;
        }

        $this->db->update('users', $data);
    }

    public function ResetRights($company_sid, $module = 'jobs') {
        $this->db->where('parent_sid', $company_sid);
        $data = array();

        if ($module == 'jobs') {
            $data['has_job_approval_rights'] = 0;
        } elseif ($module == 'applicants') {
            $data['has_applicant_approval_rights'] = 0;
        }

        $this->db->update('users', $data);
    }

    public function GetUsersWithApprovalRights($company_sid, $module = 'jobs') {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);

        if ($module == 'jobs') {
            $this->db->where('has_job_approval_rights', 1);
        } elseif ($module == 'applicants') {
            $this->db->where('has_applicant_approval_rights', 1);
        }

        $record = $this->db->get('users');
        $myReturn = $record->result_array();
        $record->free_result();
        
        if (!empty($myReturn)) {
            return $myReturn;
        } else {
            return array();
        }
    }

    public function GetModuleStatus($company_sid, $module = 'jobs') {
        if ($module == 'jobs') {
            $this->db->select('has_job_approval_rights');
        } elseif ($module == 'applicants') {
            $this->db->select('has_applicant_approval_rights');
        } else {
            $this->db->select('has_task_management_rights');
        }

        $this->db->where('sid', $company_sid);
        $this->db->limit(1);
        
        $record = $this->db->get('users');
        $myData = $record->result_array();
        $record->free_result();

        if (!empty($myData)) {
            if ($module == 'jobs') {
                return $myData[0]['has_job_approval_rights'];
            } elseif ($module == 'applicants') {
                return $myData[0]['has_applicant_approval_rights'];
            } else {
                return $myData[0]['has_task_management_rights'];
            }
        } else {
            return 0;
        }
    }

    public function GetApplicantApprovalModuleStatus($company_sid) {
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        $this->db->limit(1);
        
        $record = $this->db->get('users');
        $myData = $record->result_array();
        $record->free_result();
        
        if (!empty($myData)) {
            return $myData[0]['has_applicant_approval_rights'];
        } else {
            return 0;
        }
    }

    public function UpdateModuleStatus($company_sid, $status, $module = 'jobs') {
        $data = array();

        if ($module == 'jobs') {
            $data['has_job_approval_rights'] = $status;
        } elseif ($module == 'applicants') {
            $data['has_applicant_approval_rights'] = $status;
        } elseif ('tasks_management') {
            $data['has_task_management_rights'] = $status;
        }

        if (!empty($data)) {
            $this->db->where('sid', $company_sid);
            $this->db->update('users', $data);
        }
    }

    public function UpdateApplicantApprovalModuleStatus($company_sid, $status) {
        $data = array();
        $data['has_applicant_approval_rights'] = $status;
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $data);
    }

    function GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $approval_status, $keywords, $limit = 0, $start = 1) {
        // Updated on: 22-04-2019
        if ($employer_sid != 0) {
            $this->db
                    ->select('portal_job_listings.*')
                    ->group_start()
                    ->where('portal_job_listings.active', 1)
                    ->or_where('portal_job_listings.active', 0)
                    ->group_end()
                    ->where('portal_job_listings_visibility.company_sid', $company_sid)
                    ->where('portal_job_listings_visibility.employer_sid', $employer_sid)
                    ->where('portal_job_listings.approval_status', $approval_status)
                    ->order_by('portal_job_listings.sid', 'DESC')
                    ->from('portal_job_listings_visibility')
                    ->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'inner');

            if ($limit > 0) {
                $this->db->limit($limit, $start);
            }

            if (!empty($keywords)) {
                $this->db->like('Title', $keywords);
            }

            $result = $this->db->get();
            $return_arr = $result->result_array();
            $result = $result->free_result();
            return $return_arr;
        }

        // For Full access
        $this->db->select('*')
                ->group_start()
                ->where('active', 1)
                ->or_where('active', 0)
                ->group_end()
                ->order_by('sid', 'DESC')
                ->where('user_sid', $company_sid)
                ->where('approval_status', $approval_status)
                ->from('portal_job_listings');

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    function GetAllJobsCompanySpecific($company_sid, $keywords, $approval_status, $limit = 0, $start = 1) {
        $this->db->select('*');
        $this->db->group_start();
        $this->db->where('active', 1);
        $this->db->or_where('active', 0);
        $this->db->group_end();
//        $this->db->where('active', 1);
        $this->db->where('user_sid', $company_sid);
        $this->db->where('approval_status', $approval_status);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        
        $result = $this->db->get('portal_job_listings');
        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        return $result_arr;
    }

    function GetAllJobsCountCompanyAndEmployerSpecific($company_sid, $employer_sid, $approval_status, $keywords) {
        $this->db->select('portal_job_listings.*, portal_job_listings_visibility.job_sid');
        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where('portal_job_listings.approval_status', $approval_status);

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        
        return $this->db->get('portal_job_listings_visibility')->num_rows();
    }

    function GetAllJobsCountCompanySpecific($company_sid, $approval_status, $keywords) {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('approval_status', $approval_status);

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        
        return $this->db->get('portal_job_listings')->num_rows();
    }

    function GetAllJobsTitlesCompanySpecific($company_sid, $approval_status) {
        $this->db->select('sid, Title');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('approval_status', $approval_status);
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        
        $result = $this->db->get('portal_job_listings');
        $result_arr = $result->result_array();
        $result = $result->free_result();
        
        return $result_arr;
    }

    function GetAllJobsTitlesCompanyAndEmployerSpecific($company_sid, $employer_sid, $approval_status) {
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.sid');
        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where('portal_job_listings.approval_status', $approval_status);
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        
        $result = $this->db->get('portal_job_listings_visibility');
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    function UpdateApprovalStatus($employer_sid, $jobId, $status, $company_sid) {
        $data = array();
        $data['approval_status'] = $status;
        $data['approval_status_by'] = $employer_sid;
        $approval_status_change_datetime = date('Y-m-d h:j:00');
        $data['approval_status_change_datetime'] = $approval_status_change_datetime;
                
        $this->db->where('sid', $jobId);
        $this->db->update('portal_job_listings', $data);
        $this->db->where('portal_job_listings_sid', $jobId);
        return $this->db->update('portal_job_listings_record', $data);
    }

    function GetUserFullName($user_sid) {
        if ($user_sid != 0) {
            $this->db->select('first_name, last_name');
            $this->db->where('sid', $user_sid);
            $this->db->limit(1);
            
            $result = $this->db->get('users');
            $myData = $result->result_array();
            $result = $result->free_result();
            $myReturn = 'Not Found';

            if (!empty($myData)) {
                $myReturn = $myData[0]['first_name'] . ' ' . $myData[0]['last_name'];
            }
        } else {
            $myReturn = STORE_NAME;
        }

        return ucwords($myReturn);
    }

    function GetUserIdsToWhomJobIsVisible($job_sid) {
        $this->db->select('*');
        $this->db->where('job_sid', $job_sid);
        
        $result = $this->db->get('portal_job_listings_visibility');
        $myData = $result->result_array();
        $result = $result->free_result();
        
        $idList = array();

        if (!empty($myData)) {
            foreach ($myData as $row) {
                $idList[] = $row['employer_sid'];
            }
        }

        return $idList;
    }

    function GetUserProfile($user_sid) {
        $this->db->where('sid', $user_sid);
        
        $result = $this->db->get('users');
        $myData = $result->result_array();
        $result = $result->free_result();

        if (!empty($myData)) {
            return $myData[0];
        } else {
            return array();
        }
    }

    function GetJobData($job_sid) {
        $this->db->where('sid', $job_sid);
        
        $result = $this->db->get('portal_job_listings');
        $myData = $result->result_array();
        $result = $result->free_result();

        if (!empty($myData)) {
            return $myData[0];
        } else {
            return array();
        }
    }

    function get_assigned_tasks($employer_id, $status, $type = null) {
        $this->db->select('*');

        if ($type == null || $type == 'to_me') {
            $this->db->where('employer_sid', $employer_id);
        } else {
            $this->db->where('assigned_by_sid', $employer_id);
        }

        $this->db->where('status', $status);
        $this->db->order_by('assigned_date', 'DESC');
        
        $record = $this->db->get('assignment_management');
        $record_array = $record->result_array();
        $record->free_result();
        
        return $record_array;
    }

    public function GetAllUsers($company_sid) {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $result = $this->db->get('users')->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    function getemployeedetails($sid, $company_id) {
        $this->db->select('first_name, last_name, email, parent_sid');
        $this->db->where('active', 1);
        $this->db->where('sid', $sid);
        $this->db->where('parent_sid', $company_id);
        $this->db->from('users');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    /**
     * Check if employee has approval access
     *
     * @param $company_sid Integer
     * @param $employee_sid Integer
     *
     * @return Integer
     */
    function check_employee_has_approval_rights($company_sid, $employee_sid) {
        $result = $this->db
                ->select('has_job_approval_rights')
                ->from('users')
                ->where('parent_sid', $company_sid)
                ->where('sid', $employee_sid)
                ->get();
        $has_rights = $result->row_array()['has_job_approval_rights'];
        $result = $result->free_result();
        return $has_rights;
    }

    function update_job_listings($sid,$data){
        $this->db->where('sid',$sid);
        $this->db->update('portal_job_listings',$data);
    }

    function refund_pay_per_job($job_title = 'Unknown', $ppj_id = 0) { //echo 'I AM IN MISC 1297 <pre>';
        $userdata = $this->session->userdata('logged_in');
        $company_sid = $userdata['company_detail']['sid'];
        $employer_sid = $userdata['employer_detail']['sid'];
        $first_name = $userdata['employer_detail']['first_name'];
        $last_name = $userdata['employer_detail']['last_name'];
        $product_id = $ppj_id;
        $this->load->model('ext_model');
        $this->load->model('manage_admin/receipts_model');
        $error_flag = false;
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $order_amount = 0;
        $order_description = 'This is auto generated invoice as a refund for Job Title: '.$job_title.'.

Job was in approval process but it was rejected by employee name: '.$first_name.' '.$last_name.'

Date: '.date('d M, Y H:i:s').'

';
        $proucts_sid = array();
        $card_error = 'no_error';
        $purchased_date = date('Y-m-d H:i:s');
        //For Commission Invoice
        $id_to_quantity = array();
        $id_to_rooftops = array();
        $product_sids = array();
        $product_quantity = 0;
        $products = '';

        if ($product_id > 0) {
            $products_details = db_get_products_details($product_id);
            $products_details['qty'] = 1;
            $products_details['no_of_days'] = $products_details['expiry_days'];
        }

        if (!empty($products_details)) { // step 1 - check if the ordered product is still active or not
            $no_of_days = $products_details['no_of_days'];
            $product_qty = $products_details['number_of_postings'] * $products_details['qty'];
            $active = $products_details['active'];

            if ($active == '0') { // the product is offline
                $error_flag = true;
                $response[] = 'Product is no longer available!';
            }
            $daily = $products_details['daily'];

            if ($daily > 0) {
                $expiry_days = $no_of_days;
                $product_quantity = $no_of_days; //For Commission Invoice
            } else {
                $expiry_days = $products_details['expiry_days'];
                $product_quantity = 1; //For Commission Invoice
            }
//            $cost_price = $this->ext_model->get_product_cost_price($products_details['sid']);
            $cost_price = 0;

            $ordered_products[] = array('product_sid'                           => $products_details['sid'],
                'product_qty'                           => $product_qty,
                'product_remaining_qty'                 => $product_qty,
                'order_qty'                             => $products_details['qty'],
                'product_price'                         => $products_details['price'],
                'cost_price'                            => $cost_price,
                'product_total'                         => 0,
                'company_sid'                           => $company_sid);

            $serialized_items_info['custom_text'][]                             = '';
            $serialized_items_info['item_qty'][]                                = $product_qty;
            $serialized_items_info['item_price'][]                              = 0;
            $serialized_items_info['products'][]                                = $products_details['sid'];
            $serialized_items_info['item_remaining_qty'][]                      = $product_qty;
            $serialized_items_info['no_of_days'][]                              = $expiry_days;
            $serialized_items_info['flag'][]                                    = 'no_edit';
            $serialized_items_info['cost_price'][]                              = $cost_price;
            $serialized_items_info['total_cost'][]                              = isset($products_details['qty']) ? $cost_price * $products_details['qty'] : $cost_price;
            $order_amount                                                       = 0;
            $order_description                                                  .= 'Product: 1 * ' . $products_details ['name'];
            $proucts_sid[]                                                      = $products_details['sid'];
            //For Commission Invoice
            $id_to_quantity[$products_details['sid']]                           = $product_quantity;
            $id_to_rooftops[$products_details['sid']]                           = 1;
            $product_sids[]                                                     = $products_details['sid'];
        }

        $order_final_total                                                      = $order_amount;

        // array - 0 reserved for error flag or success flag
        // array - 1 reserved for Products error flag
        // array - 2 reserved for coupon code error flag
        // array - 3 reserved for paypal error flag
        // array - 4 reserved for produts array
        //exit;
        if ($error_flag) {
            $array[0] = "error";

            if (empty($response)) {
                $array[1] = 'no_error';
            } else {
                $array[1] = implode(",", $response);
            }

            $array[2] = 'no_coupon';
            $array[3] = $card_error;
            $array[4] = $ppj_id;
//            echo json_encode($array);
//            exit();
        } else {

            $orders_data = array('order_status'     => 'paid',
                'employer_sid'                      => $employer_sid,
                'purchased_date'                    => $purchased_date,
                'company_sid'                       => $company_sid,
                'total'                             => 0,
                'payment_method'                    => 'Free_checkout',
                'verification_response'             => NULL);
            $invoice_data = array('user_sid'        => $employer_sid,
                'company_sid'                       => $company_sid,
                'date'                              => $purchased_date,
                'payment_method'                    => 'Free_checkout',
                'invoice_description'               => $order_description,
                'total_discount'                    => 0,
                'sub_total'                         => 0,
                'total'                             => 0,
                'serialized_items_info'             => serialize($serialized_items_info),
                'status'                            => 'Paid',
                'payment_date'                      => $purchased_date,
                'verification_response'             => NULL,
                'product_sid'                       => implode(',', $proucts_sid),
                'credit_card_number'                => NULL,
                'credit_card_type'                  => NULL);

            $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

            foreach ($ordered_products as $ordered_product) { // insert products details in DB
                $this->ext_model->cc_add_product($order_id, $ordered_product);
            }

            $invoice_id = $this->ext_model->cc_add_invoice($invoice_data);



            $this->load->model('manage_admin/invoice_model');
            $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
            $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
            $custom_data = unserialize($invoiceData["serialized_items_info"]);
            for ($i = 0; $i < count($custom_data['products']); $i++) {
                $custom_product_id = $custom_data['products'][$i];

                if (strpos($custom_product_id, 'custom') === false) {
                    $products_name = db_get_product_name($custom_product_id);
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                } else {
                    $products_name = $custom_data["custom_text"][$i];
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                }
            }

            $replacement_array = array();
            $replacement_array['site_url'] = base_url();
            $replacement_array['date'] = month_date_year(date('Y-m-d'));
            $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
            $replacement_array['invoice_id'] = $invoice_id;
            $replacement_array['product_list'] = $products;
            $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
            $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
            $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

            if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
            } else {
                $replacement_array['special_discount'] = '$' . '0.00';
            }

            $replacement_array['invoice_description'] = $order_description;
            $company_sid = $invoiceData['company_sid'];
            send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);



            if ($error_flag) {
                $array[0] = "error";
                $array[1] = 'no_error';
                $array[2] = 'no_coupon';
                $array[3] = $card_error;
//                echo json_encode($array);
//                exit();
            } else {
                $array[0] = "success";
                $array[4] = $serialized_items_info;
//                echo json_encode($array);
//                exit();
            }
        }
    }

    function get_pay_per_job_status($sid) {
        $this->db->select('per_job_listing_charge, career_site_listings_only');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $theme_name = $record_arr[0];
    }
}
