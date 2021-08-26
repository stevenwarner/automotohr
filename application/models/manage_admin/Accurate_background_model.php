<?php

class Accurate_background_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // FUNCTIONS related to companies
    function get_all_accurate_background_checks($company_sid, $usertype, $usersid, $orderersid, $product_type, $start_time, $end_time, $limit = 0, $start = 1)
    {
        $this->db->select('background_check_orders.*,background_check_orders.sid as order_sid, t1.first_name, t1.last_name, t2.CompanyName as cname');
        $this->db->join('users as t1', 'background_check_orders.employer_sid = t1.sid');
        $this->db->join('users as t2', 'background_check_orders.company_sid = t2.sid');
        $this->db->order_by('background_check_orders.date_applied', 'DESC');
        if ($company_sid != 'all') {
            $this->db->where('company_sid', $company_sid);
        }
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
        if ($start !== 'all') {
            if ($limit > 0) {
                $this->db->limit($limit, $start);
            }
        }
        $this->db->where('background_check_orders.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_time)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_time)) . '"');
        // _e($this->db->get_compiled_select(), true, true);
        $result = $this->db->get('background_check_orders')->result_array();
        return $result;
    }

    // Updated on: 29-05-2019
    function get_all_accurate_background_checks_by_count($company_sid, $usertype, $usersid, $orderersid, $product_type, $start_time, $end_time)
    {
        if ($company_sid != 'all') $this->db->where('company_sid', $company_sid);
        if ($product_type != 'all') $this->db->where('product_type', $product_type);
        if ($usersid != 'all' && $usersid != 0) $this->db->where('users_sid', $usersid);
        if ($orderersid != 'all') $this->db->where('employer_sid', $orderersid);
        if ($usertype != 'all') $this->db->where('background_check_orders.users_type', strtolower($usertype));
        return $this->db
            ->join('users as t1', 'background_check_orders.employer_sid = t1.sid')
            ->join('users as t2', 'background_check_orders.company_sid = t2.sid')
            ->where('background_check_orders.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_time)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_time)) . '"')
            ->count_all_results('background_check_orders');
    }

    function get_all_accurate_background_activation_orders()
    {
        return $this->db->select('*,background_check.sid as order_sid')
            ->join('users', 'background_check.employer_sid = users.sid')
            ->order_by('background_check.sid', 'DESC')
            ->get('background_check')->result_array();
    }

    public function get_accurate_background_document_detail($document_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);
        $record_obj = $this->db->get('background_check');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    public function get_company_name($company_sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $company_sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['CompanyName'];
        } else {
            return array();
        }
    }

    public function update_accurate_background_document($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('background_check', $data_to_update);
    }

    public function insert_accurate_background_document_history($data_to_insert)
    {
        $this->db->insert('background_check_history', $data_to_insert);
    }

    public function activate_accurate_background($company_sid)
    {
        $data = array('background_check' => 1);
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $data);
    }

    public function deactivate_accurate_background($company_sid)
    {
        $data = array('background_check' => 0);
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $data);
    }

    public function request_document($sid)
    {
        $data = array('document_request' => 1);
        $this->db->where('sid', $sid);
        $this->db->update('background_check', $data);

        return $this->db->where('background_check.sid', $sid)
            ->join('users', 'users.sid=background_check.employer_sid')
            ->get('background_check')->row_array();
    }

    public function cancel_request_document($sid)
    {
        $data = array('document_request' => 0);
        $this->db->where('sid', $sid);
        $this->db->update('background_check', $data);
    }


    public function get_accurate_background_order_details($order_sid)
    {
        $this->db->select('background_check_orders.*, products.product_brand')
            ->join('products', 'products.sid = background_check_orders.product_sid', 'left');
        $this->db->where('background_check_orders.sid', $order_sid);
        $data_row = $this->db->get('background_check_orders')->result_array();

        //
        if(empty($data_row)){
            $this->db->select('
            "deleted" as is_deleted_status,
            background_check_orders_history.*, products.product_brand')
            ->join('products', 'products.sid = background_check_orders_history.product_sid', 'left');
            $this->db->where('background_check_orders_history.sid', $order_sid);
            $data_row = $this->db->get('background_check_orders_history')->result_array();
        }

        if (!empty($data_row)) {
            $data_row = $data_row[0];

            $company_sid = $data_row['company_sid'];

            $this->db->select('CompanyName');
            $this->db->where('sid', $company_sid);
            $company_info = $this->db->get('users')->result_array();

            if (!empty($company_info)) {
                $data_row['company_info'] = $company_info[0];
            }


            $employer_sid = $data_row['employer_sid'];

            $this->db->select('first_name, last_name');
            $this->db->where('sid', $employer_sid);
            $employer_info = $this->db->get('users')->result_array();

            if (!empty($employer_info)) {
                $data_row['employer_info'] = $employer_info[0];
            }

            $candidate_type = $data_row['users_type'];
            $candidate_sid = $data_row['users_sid'];

            if ($candidate_type == 'applicant') {
                $this->db->select('first_name, last_name, email');
                $this->db->where('sid', $candidate_sid);
                $candidate_info = $this->db->get('portal_job_applications')->result_array();

                if (!empty($candidate_info)) {
                    $data_row['candidate_info'] = $candidate_info[0];
                }
            } elseif ($candidate_type == 'employee') {
                $this->db->select('first_name, last_name, email');
                $this->db->where('sid', $candidate_sid);
                $candidate_info = $this->db->get('users')->result_array();

                if (!empty($candidate_info)) {
                    $data_row['candidate_info'] = $candidate_info[0];
                }
            }

            $package_response = $data_row['package_response'];

            if ($package_response != '') {
                $package_response = unserialize($package_response);
                $data_row['package_response'] = $package_response;
            }

            $order_response = $data_row['order_response'];

            if ($order_response != '') {
                $order_response = unserialize($order_response);
                $data_row['order_response'] = $order_response;
            }


            return $data_row;
        } else {
            return array();
        }
    }

    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    function get_applicants_by_query($query)
    {
        $result = $this->db
            ->select('portal_job_applications.sid as id')
            ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " - Applicant" ) as value ')
            ->where('portal_job_applications.archived', 0)
            ->where('portal_job_applications.hired_status', 0)
            ->group_start()
            ->like('concat(portal_job_applications.first_name, " ", portal_job_applications.last_name)', $query)
            //            ->or_like('portal_job_applications.email', $query)
            ->group_end()
            ->order_by('value', 'DESC')
            ->group_by('id')
            ->limit(8)
            ->get('portal_job_applications');

        $result_arr1 = $result->result_array();
        $result = $this->db
            ->select('users.sid as id')
            ->select('concat( users.first_name, " ", users.last_name, " - Employee") as value ')
            ->where('users.archived', 0)
            ->group_start()
            ->like('concat(users.first_name, " ", users.last_name)', $query)
            //            ->or_like('users.email', $query)
            ->group_end()
            ->order_by('value', 'DESC')
            ->group_by('id')
            ->limit(8)
            ->get('users');

        $result_arr2 = $result->result_array();
        return $result_arr = array_merge($result_arr1, $result_arr2);
    }

    function get_employee_by_query($sid)
    {
        $result = $this->db
            ->select('users.sid as id')
            ->select('concat( users.first_name, " ", users.last_name, " - Employee") as value ')
            ->where('users.archived', 0)
            ->where('users.parent_sid', $sid)
            ->order_by('value', 'ASC')
            ->group_by('id')
            ->get('users');

        $result_arr = $result->result_array();
        return $result_arr;
    }

    //
    private function GetBackgroundCheckHistory(
        $company_sid = false,
        $product_type = false,
        $status = false,
        $from_date = false,
        $to_date = false,
        $user_id = false,
        $order_sid = false,
        $inset = 0,
        $offset = 0,
        $do_count = false,
        $ids_array = array(),
        $export = false
    )
    {
        $columns = '
            "deleted" as is_deleted_status,
            background_check_orders_history.employer_sid,
            background_check_orders_history.invoice_sid,
            users.username,
            users.first_name,
            users.last_name,
            companies.CompanyName as cname,
            background_check_orders_history.users_sid,
            background_check_orders_history.users_type,
            background_check_orders_history.order_response,
            background_check_orders_history.product_name,
            background_check_orders_history.product_type,
            background_check_orders_history.sid as order_sid, 
            background_check_orders_history.date_applied';

        if ($do_count) $columns = 'background_check_orders_history.order_response, background_check_orders_history.sid as order_sid';
        $this->db->select($columns)
            ->from('background_check_orders_history')
            ->join('users', 'background_check_orders_history.employer_sid = users.sid')
            ->join('users as companies', 'background_check_orders_history.company_sid = companies.sid');

        if ($company_sid != 'all') $this->db->where('background_check_orders_history.company_sid', $company_sid);
        if ($product_type != 'all') $this->db->where('background_check_orders_history.product_type', $product_type);
        if (sizeof($ids_array)) $this->db->where_in('background_check_orders_history.sid', $ids_array);
        if ($user_id && $user_id != 0 && $user_id != 'all') $this->db->where('users_sid', $user_id);
        if ($order_sid && $order_sid != 'all') $this->db->where('employer_sid', $order_sid);

        $this->db
            ->where('DATE_FORMAT(background_check_orders_history.date_applied, "%Y-%m-%d") BETWEEN "' . $from_date . '" and "' . $to_date . '"')
            ->order_by("background_check_orders_history.date_applied", "desc");

        if (!$do_count && !$export) $this->db->limit($offset, $inset);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        return $result_arr;
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
        $product_type = false,
        $status = false,
        $from_date = false,
        $to_date = false,
        $user_id = false,
        $order_sid = false,
        $inset = 0,
        $offset = 0,
        $do_count = false,
        $ids_array = array(),
        $export = false
    ) {
        $columns = '
            background_check_orders.employer_sid,
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

        if ($do_count) $columns = 'background_check_orders.order_response, background_check_orders.sid as order_sid';
        $this->db->select($columns)
            ->from('background_check_orders')
            ->join('users', 'background_check_orders.employer_sid = users.sid')
            ->join('users as companies', 'background_check_orders.company_sid = companies.sid');

        if ($company_sid != 'all') $this->db->where('background_check_orders.company_sid', $company_sid);
        if ($product_type != 'all') $this->db->where('background_check_orders.product_type', $product_type);
        if (sizeof($ids_array)) $this->db->where_in('background_check_orders.sid', $ids_array);
        if ($user_id && $user_id != 0 && $user_id != 'all') $this->db->where('users_sid', $user_id);
        if ($order_sid && $order_sid != 'all') $this->db->where('employer_sid', $order_sid);

        $this->db
            ->where('DATE_FORMAT(background_check_orders.date_applied, "%Y-%m-%d") BETWEEN "' . $from_date . '" and "' . $to_date . '"')
            ->order_by("background_check_orders.date_applied", "desc");

        if (!$do_count && !$export) $this->db->limit($offset, $inset);
        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        $result_arr = array_merge(
            $result_arr, 
            $this->GetBackgroundCheckHistory(
                $company_sid,
                $product_type,
                $status,
                $from_date,
                $to_date,
                $user_id,
                $order_sid,
                $inset,
                $offset,
                $do_count,
                $ids_array,
                $export
            )
        );

        if (!sizeof($result_arr)) return $do_count ? 0 : $result_arr;
        if (!$do_count) $rows = '';
        if ($do_count) $status_array = array(
            'pending' => array(),
            'cancelled' => array(),
            'completed' => array(),
            'awaiting_candidate_input' => array()
        );

        if (!$do_count) {
            usort($result_arr, function($a, $b) {
                return $a['date_applied'] < $b['date_applied'];
            });
        }
            
        //  _e($result_arr, true, true);

        foreach ($result_arr as $k0 => $v0) {
            $tmp_array = @unserialize($v0['order_response']);
            if (!sizeof($tmp_array)) $in_status = 'pending';
            else if (!isset($tmp_array['orderStatus'])) $in_status = 'pending';
            else if ($tmp_array['orderStatus']['status'] == '' || $tmp_array['orderStatus']['status'] == NULL) $in_status = 'pending';
            else
                $in_status = strtolower($tmp_array['orderStatus']['status']);

            $in_status = $in_status == 'draft' ? 'awaiting_candidate_input' : $in_status;



            if ($do_count) $status_array[$in_status][] = $v0['order_sid'];

            if (!in_array($in_status, $status) && !in_array('all', $status)) {
                unset($result_arr[$k0]);
                continue;
            } else $result_arr[$k0]['status'] = $v0['status'] = ucwords($in_status);

            if (!$do_count) {
                //
                $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = 'Candidate Not Found';
                //
                $result = $this->db
                    ->select('concat(first_name," ",last_name) as full_name, email')
                    ->where('sid', $v0['users_sid'])
                    ->get($v0['users_type'] == 'applicant' ? 'portal_job_applications' : 'users');
                $result2_arr = $result->row_array();
                $result = $result->free_result();
                if (sizeof($result2_arr)) {
                    $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = ucwords($result2_arr['full_name']);
                    $result_arr[$k0]['email'] = $v0['email'] = ($result2_arr['email']);
                } else {
                    $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = "";
                    $result_arr[$k0]['email'] = $v0['email'] = "";
                }

                //
                 $result_arr[$k0]['product_name'] = 
                 $v0['product_name'] = sc_remove($v0['product_name']);

                //$result_arr[$k0]['product_name'] = $v0['product_name'] = str_replace(['?Ã¡'], '', utf8_encode($v0['product_name']));
                $result_arr[$k0]['product_type'] = $v0['product_type'] = ucwords(str_replace('-', ' ', $v0['product_type']));
                //
                unset($result_arr[$k0]['order_response']);

                //
                $status_color = '';
                //
                if ($v0['status'] == 'Draft') $status_color = 'style="color: #FF0000"';
                elseif ($v0['status'] == 'Pending') $status_color = 'style="color: #0000FF";';
                elseif ($v0['status'] == '' || $v0['status'] == NULL) $status_color = 'style="color: #0000FF";';
                elseif ($v0['status'] == 'Completed') $status_color = 'style="color: #006400";';
                elseif ($v0['status'] == 'Cancelled') $status_color = 'style="color: #FF8C00";';
                
                if(isset($v0['is_deleted_status'])) {
                    $status_color = 'style="color: #d9534f";';
                    $v0['status'] = 'Canceled & Credited'.(!empty($v0['invoice_sid']) ? ' to invoice # '.($v0['invoice_sid']).'' : '');
                }
                //
                $rows .= '<tr>';
                $rows .= '    <td>' . convert_date_to_frontend_format($v0['date_applied']) . '</td>';
                $rows .= '    <td>' . $v0['first_name'] . ' ' . $v0['last_name'] .'</td>';
                $rows .= '    <td>';
                if (!empty($v0['user_first_name']) && !empty($v0['email'])) {
                    $rows .= $v0['user_first_name'] . '<br /> ('.$v0['email'].')';
                }
                $rows .= '    </td>';
                // $rows .= '    <td>' . $v0['user_first_name'] . ' ('.($v0['email']).')</td>';
                $rows .= '    <td>' . ucfirst($v0['users_type']) . '</td>';
                $rows .= '    <td>' . $v0['product_name'] . '</td>';
                $rows .= '    <td>' . $v0['product_type'] . '</td>';
                $rows .= '    <td>' . ucwords($v0['cname']) . '</td>';
                $rows .= '    <td ' . $status_color . '>' . ($v0['status'] == 'Draft' ? 'Awaiting Candidate Input' : ($v0['status'] == '' || $v0['status'] == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $v0['status']))) . '</td>';
                $rows .= '    <td class="no-print">
                <a class="btn btn-success btn-sm" href="' . base_url() . 'manage_admin/accurate_background/order_status/' . $v0['order_sid'] . '" >Order Status</a>';
                if(isset($v0['is_deleted_status'])){
                    $rows .='
                    <button class="btn btn-success btn-sm jsRevertBGC" data-id="'.($v0['order_sid']).'">
                        Revert
                    </button>';
                } else{
                    $rows .='
                    <button class="btn btn-danger btn-sm jsRemoveBGC" data-id="'.($v0['order_sid']).'">
                        Cancel & Credit Back
                    </button>';
                }
                $rows .= '</td>';
                $rows .= '</tr>';
            }
        }

       // die('asdasd');

        if ($export) return $result_arr;
        return $do_count ? array('TotalRecords' => count($result_arr), 'StatusArray' => $status_array) : $rows;
    }


    function add_product_qty($productId, $companyId, $no_of_days = 0)
    { //Getting all invoices against the company which are paid STARTS
        $orders = $this->db->get_where('invoices', array('company_sid' => $companyId, 'status' => 'Paid'))->result_array();
        //
        $dobreak = false;
        //
        $invoiceId = 0;
        //Getting all invoices against the company which are paid ENDS
        foreach ($orders as $order) {
            //
            if($dobreak){
                continue;
            }
            //
            $invoiceId = $order['sid'];
            //
            $dataArray = unserialize($order['serialized_items_info']);

            foreach ($dataArray['products'] as $key => $product) {
                //
                if($dobreak){
                    continue;
                }
                //
                if ($no_of_days > 0) {
                    if ($product == $productId && $dataArray['no_of_days'][$key] == $no_of_days) { //I dont know why this no of days check is implemented so i have moved it one level above if the value is greater than zero then it will be applied. Hamid
                        $currentCounter = $dataArray['item_remaining_qty'][$key];
                        //
                        $currentCounter++;
                        //
                        if(!isset($dataArray['credit'])){
                            $dataArray['credit'] = [];
                        }
                        //
                        $dataArray['credit'][$productId] = [];
                        $dataArray['credit'][$productId] = [
                            'type' => 'subtracted',
                            'origanlCount' => $dataArray['item_remaining_qty'][$key],
                            'newCount' => $currentCounter,
                            'date' => date('Y-m-d H:i:s', strtotime('now'))
                        ];
                        $dataArray['item_remaining_qty'][$key] = $currentCounter;
                        $dataToUpdate['serialized_items_info'] = serialize($dataArray);
                        $this->db->where('sid', $order['sid'])->update('invoices', $dataToUpdate);
                        $dobreak = true;
                        return $invoiceId;
                    }
                } else { // Added by Hamid ( No of days check was failing hence qty was not deducted ).
                    if ($product == $productId) {
                        $currentCounter = $dataArray['item_remaining_qty'][$key];
                        
                        $currentCounter++;
                        //
                        if(!isset($dataArray['credit'])){
                            $dataArray['credit'] = [];
                        }
                        //
                        $dataArray['credit'][$productId] = [];
                        $dataArray['credit'][$productId] = [
                            'type' => 'subtracted',
                            'origanlCount' => $dataArray['item_remaining_qty'][$key],
                            'newCount' => $currentCounter,
                            'date' => date('Y-m-d H:i:s', strtotime('now'))
                        ];
                        $dataArray['item_remaining_qty'][$key] = $currentCounter;
                        
                        $dataToUpdate['serialized_items_info'] = serialize($dataArray);
                        $this->db->where('sid', $order['sid'])->update('invoices', $dataToUpdate);
                        $dobreak = true;
                        return $invoiceId;
                    }
                }
            }
        }
        //
        return $invoiceId;
    }
    
    function deduct_product_qty($productId, $companyId, $invoiceId)
    { //Getting all invoices against the company which are paid STARTS
        //
        $where  = array('company_sid' => $companyId, 'status' => 'Paid');
        //
        if(!empty($invoiceId)){
            $where['sid'] = $invoiceId;
        }
        $orders = $this->db->get_where('invoices', $where)->result_array();
        //Getting all invoices against the company which are paid ENDS
        foreach ($orders as $order) {
            //
            $invoiceId = $order['sid'];
            //
            $dataArray = unserialize($order['serialized_items_info']);

            foreach ($dataArray['products'] as $key => $product) {
                //
                if ($product == $productId && $dataArray['item_remaining_qty'][$key] > 0) {
                    $currentCounter = $dataArray['item_remaining_qty'][$key];
                    
                    $currentCounter--;
                    //
                    if(!isset($dataArray['credit'])){
                        $dataArray['credit'] = [];
                    }
                    //
                    $dataArray['credit'][$productId] = [];
                    $dataArray['credit'][$productId] = [
                        'type' => 'added',
                        'origanlCount' => $dataArray['item_remaining_qty'][$key],
                        'newCount' => $currentCounter,
                        'date' => date('Y-m-d H:i:s', strtotime('now'))
                    ];
                    //
                    $dataArray['item_remaining_qty'][$key] = $currentCounter;
                    $dataToUpdate['serialized_items_info'] = serialize($dataArray);
                    $this->db->where('sid', $order['sid'])->update('invoices', $dataToUpdate);
                    return $currentCounter;
                }
            }
        }
        //
        return -1;
    }
}
