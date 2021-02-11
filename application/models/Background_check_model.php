<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Background_check_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getCompanyDetail($company_sid) {
        $this->db->where('sid', $company_sid);
        
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();
        
        if(!empty($records_arr)) {
            $return_data = $records_arr[0];
        }
        
        return $return_data;
    }

    function saveBackgroundCheck($data) { //checking if the already applied for background check 
        $res = $this->db->where('company_sid', $data['company_sid'])->get('background_check');

        if ($res->num_rows() > 0) {
            $result = $res->row_array();
            $data['document_request'];
            $this->db->where('sid', $result['sid'])->update('background_check', $data);
        } else {
            $this->db->insert('background_check', $data);
        }
    }

    function applicant_details($applicant_id, $company_sid) {
        $this->db->select('*');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_id);
        return $this->db->get('portal_job_applications');
    }

    function getProductsAlreadyAppliedOn($app_id, $user_type, $product_type) {
        return $this->db
        ->select('background_check_orders.*, products.product_brand')
        ->join('products', 'products.sid = background_check_orders.product_sid', 'left')
        ->where('background_check_orders.users_sid', $app_id)
                        ->where('background_check_orders.users_type', $user_type)
                        ->where('background_check_orders.product_type', $product_type)
                        ->get('background_check_orders');
    }

    function get_order_details($order_sid) {
        $this->db->select('*');
        $this->db->where('sid', $order_sid);
        $order = $this->db->get('background_check_orders')->result_array();

        if (!empty($order)) {
            return $order[0];
        } else {
            return array();
        }
    }

    function saveBackgroundOrder($data) {
        $this->db->insert('background_check_orders', $data);
    }

    function getBackgroundOrderDetails($reportId) {
        return $this->db->where('sid', $reportId)->get('background_check_orders')->result_array();
    }

    function updateBackgroundCheckOrder($reportId, $updatedDataToSave) {
        $this->db->set($updatedDataToSave)
                ->where('sid', $reportId)
                ->update('background_check_orders');
    }

    function getBackgroundDocument($company_sid) {
        $this->db->select('document_request');
        $this->db->where('company_sid', $company_sid);
        $resultObject = $this->db->get('background_check');
        
        if ($resultObject->num_rows() > 0) {
            $result = $resultObject->result_array();
            return $result[0]['document_request'];
        } else {
            return -1;
        }
    }

    function get_all_accurate_background_checks_by_company($company_sid, $usertype, $usersid, $orderersid, $product_type, $start_time, $end_time, $limit = null, $start = null) {
        $this->db->select('*,background_check_orders.sid as order_sid');
        $this->db->join('users', 'background_check_orders.employer_sid = users.sid');
        $this->db->where('company_sid', $company_sid);
        
        if ($product_type != 'all') {
            $this->db->where('product_type', $product_type);
        }
        
        if ($usersid != 'all' && $usersid != 0) {
            $this->db->where('users_sid', $usersid);
        }
        
        if ($orderersid != 'all') {
            $this->db->where('employer_sid', $orderersid);
        }
        
        if ($usertype != 'all') {
            $this->db->where('background_check_orders.users_type', strtolower($usertype));
        }
        
        $this->db->where('background_check_orders.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_time)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_time)) . '"');
        $this->db->limit($limit, $start);
        $this->db->order_by("background_check_orders.date_applied", "desc");
        $result = $this->db->get('background_check_orders')->result_array();
        return $result;
    }

    function get_all_accurate_background_checks_by_company_count($company_sid, $usertype, $usersid, $orderersid, $product_type, $start_time, $end_time) {
        $this->db->where('company_sid', $company_sid);
        
        if ($product_type != 'all') {
            $this->db->where('product_type', $product_type);
        }
        
        if ($usersid != 'all' && $usersid != 0) {
            $this->db->where('users_sid', $usersid);
        }
        
        if ($orderersid != 'all') {
            $this->db->where('employer_sid', $orderersid);
        }
        
        if ($usertype != 'all') {
            $this->db->where('background_check_orders.users_type', strtolower($usertype));
        }
        
        $this->db->where('background_check_orders.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_time)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_time)) . '"');
        return $this->db->count_all_results('background_check_orders');
    }

    function companyPurchasedProducts($productIds, $companyId, $product_type = NULL) {
        $i = 0;
        $productArray = array();
        $productIDsInArray = array();
        //Getting all invoices against the company which are paid STARTS
        $orders = $this->db->get_where('invoices', array('company_sid' => $companyId, 'status' => 'Paid'))->result_array();
        //Getting all invoices against the company which are paid ENDS
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);

            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) {//if the product is already added in the array.
                        foreach ($productArray as $myKey => $pro) {
                            if ($pro['product_sid'] == $product && $pro['no_of_days'] == $dataArray['no_of_days'][$key]) {
                                $pro['remaining_qty'] = $pro['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                            } else if (!in_array($product, $productIDsInArray)) {
                                $productArray[$i]['product_sid'] = $product;
                                $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            }
                            $productArray[$myKey] = $pro;
                        }
                    } else {//if the product is not already added in the array.
                        if ($dataArray['item_remaining_qty'][$key] > 0) {
                            $productIDsInArray[$i] = $product;
                            $productArray[$i]['product_sid'] = $product;
                            $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                            
                            if (isset($dataArray['no_of_days']))
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            $i++;
                        }
                    }
                }
            }
        }

        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        
        foreach ($productArray as $key => $pro) {
            foreach ($products as $myKey => $product) {
                if ($pro['product_sid'] == $product['sid']) {
                    $pro['product_image'] = $product['product_image'];
                    $pro['name'] = $product['name'];
                    $productArray[$key] = $pro;
                }
            }
        }
        
        return $productArray;
    }

    function update_order_status($order_sid, $order_status) {
        $data_to_update = array();
        $data_to_update['order_response'] = $order_status;
        $this->db->where('sid', $order_sid);
        $this->db->update('background_check_orders', $data_to_update);
    }

    function generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, $quantity) {
        $this->db->select('*');
        $this->db->where('sid', $product_sid);
        $product = $this->db->get('products')->result_array();

        if (!empty($product)) {
            $product = $product[0];
            $invoice_data = array();
            $invoice_data['user_sid'] = $employer_sid;
            $invoice_data['company_sid'] = $company_sid;
            $invoice_data['date'] = date('Y-m-d H:i:s');
            $invoice_data['payment_method'] = 'background_check_refund';
            $invoice_data['coupon_code'] = 'background_check';
            $invoice_data['coupon_type'] = 'fixed';
            $invoice_data['total_discount'] = 0;
            $invoice_data['sub_total'] = 0;
            $invoice_data['total'] = 0;
            $invoice_data['status'] = 'Paid';
            $invoice_data['verification_response'] = 99;
            $invoice_data['product_sid'] = $product_sid;
            $invoice_data['description'] = 'This invoice is generated when Background check order is cancelled by Candidate or Accurate Background.';
            $invoice_data['payment_date'] = date('Y-m-d H:i:s');
            $item_array = array();
            $item_array['custom_text'] = array('');
            $item_array['item_qty'] = array($quantity);
            $item_array['item_price'] = array(0);
            $item_array['products'] = array($product_sid);
            $item_array['item_remaining_qty'] = array($quantity);
            $item_array['no_of_days'] = array($product['expiry_days']);
            $item_array['flag'] = array('no_edit');
            $invoice_data['serialized_items_info'] = serialize($item_array);
            $this->db->insert('invoices', $invoice_data);
        }
    }

    function update_order_refund_status($background_check_order_sid, $status = 0) {
        $data_to_update = array();
        $data_to_update['order_refunded'] = $status;
        $this->db->where('sid', $background_check_order_sid);
        $this->db->update('background_check_orders', $data_to_update);
    }

    function get_user_sid($first_name, $last_name) {
        $this->db->select('sid');
        
        if (!empty($first_name)) {
            $this->db->where('first_name', $first_name);
        }
        
        if (!empty($last_name)) {
            $this->db->where('last_name', $last_name);
        }
        
        $result = $this->db->get('portal_job_applications')->result_array();
        return $result;
    }

    function getCompanyAccounts($company_id) {
        $args = array('parent_sid' => $company_id, 'active' => 1, 'career_page_type' => 'standard_career_site');
        $this->db->select('sid,username,email,first_name,last_name,access_level,is_executive_admin,PhoneNumber');
        //$this->db->where('is_executive_admin', 0);
        $res = $this->db->get_where('users', $args);
        $ret = $res->result_array();
        return $ret;
    }

    function get_applicants_by_query($company_id, $query) {
        $result = $this->db
                ->select('portal_job_applications.sid as id')
                ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " - Applicant" ) as value ')
                ->where('portal_job_applications.employer_sid', $company_id)
                ->where('portal_job_applications.archived', 0)
                ->where('portal_job_applications.hired_status', 0)
                ->group_start()
                ->like('concat(portal_job_applications.first_name, " ", portal_job_applications.last_name)', $query)
//            ->or_like('portal_job_applications.email', $query)
                ->group_end()
                ->order_by('value', 'DESC')
                ->group_by('id')
                ->limit(7)
                ->get('portal_job_applications');

        $result_arr1 = $result->result_array();
        $result = $this->db
                ->select('users.sid as id')
                ->select('concat( users.first_name, " ", users.last_name, " - Employee") as value ')
                ->where('users.parent_sid', $company_id)
                ->where('users.archived', 0)
                ->group_start()
                ->like('concat(users.first_name, " ", users.last_name)', $query)
//            ->or_like('users.email', $query)
                ->group_end()
                ->order_by('value', 'DESC')
                ->group_by('id')
                ->limit(3)
                ->get('users');

        $result_arr2 = $result->result_array();
        return $result_arr = array_merge($result_arr1, $result_arr2);
    }


    /**
     * Fetch accurate background report
     *
     * @param
     *
     * @return Array|Bool
     *
     */
    function get_all_accurate_background( 
        $company_sid = false,
        $users_type = false,
        $users_sid = false,
        $order_sid = false,
        $product_type = false,
        $status = false,
        $from_date = false,
        $to_date = false,
        $inset = 0,
        $offset = 0,
        $do_count = false,
        $ids_array = array(),
        $export = false
    ) {
        $columns = 'background_check_orders.employer_sid,
            users.username,
            users.first_name,
            users.last_name,
            companies.CompanyName as cname,
            background_check_orders.users_sid,
            background_check_orders.users_type,
            background_check_orders.order_response,
            background_check_orders.product_name,
            background_check_orders.product_type,
            background_check_orders.sid as order_sid, 
            background_check_orders.date_applied';

        if($do_count) $columns = 'background_check_orders.order_response, background_check_orders.sid as order_sid';
        $this->db->select($columns)
        ->from('background_check_orders')
        ->join('users', 'background_check_orders.employer_sid = users.sid')
        ->join('users as companies', 'background_check_orders.company_sid = companies.sid');

        if($company_sid != 'all') $this->db->where('background_check_orders.company_sid', $company_sid);
        if($users_sid != 'all' && $users_sid != 0) $this->db->where('users.sid', $users_sid);
        if($users_type != 'all') $this->db->where('background_check_orders.users_type', $users_type);
        if ($product_type != 'all') $this->db->where('background_check_orders.product_type', $product_type);
        if (sizeof($ids_array)) $this->db->where_in('background_check_orders.sid', $ids_array);
        if ($order_sid && $order_sid != 'all') $this->db->where('employer_sid', $order_sid);

        $this->db
        ->where('DATE_FORMAT(background_check_orders.date_applied, "%Y-%m-%d") BETWEEN "' . $from_date . '" and "' . $to_date . '"')
        ->order_by("background_check_orders.date_applied", "desc");

        if(!$do_count && !$export) $this->db->limit($offset, $inset);
        // if(sizeof($ids_array))
        // _e($this->db->get_compiled_select(), true);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();

        if(!sizeof($result_arr)) return $do_count ? 0 : $result_arr;

        // if($do_count) if($status == 'all') return count($result_arr);
        if(!$do_count) $rows = '';
        if($do_count) $status_array = array(
            'pending' => array(),
            'cancelled' => array(),
            'completed' => array(),
            'awaiting_candidate_input' => array()
        );

        foreach ($result_arr as $k0 => $v0) {
            $tmp_array = @unserialize($v0['order_response']);
            if(!sizeof($tmp_array)) $in_status = 'pending';
            else if(!isset($tmp_array['orderStatus'])) $in_status = 'pending';
            else if($tmp_array['orderStatus']['status'] == '' || $tmp_array['orderStatus']['status'] == NULL) $in_status = 'pending';
            else
                $in_status = strtolower($tmp_array['orderStatus']['status']);

            $in_status = $in_status == 'draft' ? 'awaiting_candidate_input' : $in_status;

            if($do_count) $status_array[$in_status][] = $v0['order_sid'];

            if(!in_array( $in_status, $status) && !in_array( 'all', $status)) { unset($result_arr[$k0]); continue; }
            else $result_arr[$k0]['status'] = $v0['status'] = ucwords($in_status);

            if(!$do_count){
                $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = 'Candidate Not Found';

                $result = $this->db
                ->select('concat(first_name," ",last_name) as full_name')
                ->where('sid', $v0['users_sid'])
                ->get( $v0['users_type'] == 'applicant' ? 'portal_job_applications' : 'users');
                $result2_arr = $result->row_array();
                $result = $result->free_result();
                if(sizeof($result2_arr)) $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = ucwords($result2_arr['full_name']);
                //
                $result_arr[$k0]['product_name'] = $v0['product_name'] = preg_replace('/[^0-9a-zA-Z\s-_]+/', ' ', utf8_encode($v0['product_name']));
                // $result_arr[$k0]['product_name'] = $v0['product_name'] = str_replace(['?Ã¡'], '', utf8_encode($v0['product_name']));
                $result_arr[$k0]['product_type'] = $v0['product_type'] = ucwords(str_replace('-', ' ', $v0['product_type']));
                unset($result_arr[$k0]['order_response']);
                $status_color = '';

                if($v0['status'] == 'Draft') $status_color = 'style="color: #FF0000"';
                elseif($v0['status'] == 'Pending') $status_color = 'style="color: #0000FF";';
                elseif($v0['status'] == '' || $v0['status'] == NULL) $status_color = 'style="color: #0000FF";';
                elseif($v0['status'] == 'Completed') $status_color = 'style="color: #006400";';
                elseif($v0['status'] == 'Cancelled') $status_color = 'style="color: #FF8C00";';
                //
                $rows .= '<tr>';
                $rows .= '    <td>'.reset_datetime(array('datetime' => $v0['date_applied'], '_this' => $this)).'</td>';
                $rows .= '    <td>'.$v0['first_name'].' '.$v0['last_name'].'</td>';
                $rows .= '    <td>'.$v0['user_first_name'].'</td>';
                $rows .= '    <td>'.ucfirst($v0['users_type']).'</td>';
                $rows .= '    <td>'.$v0['product_name'].'</td>';
                $rows .= '    <td>'.$v0['product_type'].'</td>';
                $rows .= '    <td>'.ucwords($v0['cname']).'</td>';
                $rows .= '    <td '.$status_color.'>'.($v0['status'] == 'Draft' ? 'Awaiting Candidate Input' : ($v0['status'] == '' || $v0['status'] == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $v0['status']))).'</td>';
                $rows .= '    <td class="no-print"><a class="btn btn-success btn-sm" href="'.base_url().( strtolower($v0['product_type']) == 'drug testing' ? 'drug_test' : 'background_check' ).'/'.$v0['users_type'].'/'.$v0['users_sid'].'" >View Report</a></td>';
                $rows .= '</tr>';
            }
        }

        if($export) return $result_arr;
        return $do_count ? array( 'TotalRecords' => count($result_arr), 'StatusArray' => $status_array ) : $rows;
    }

}
