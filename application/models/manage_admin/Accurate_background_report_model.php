<?php

class Accurate_background_report_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Get all active main companies
     * Created on: 29-05-2019
     * 
     * @return Array|Bool
     *
     */
    function get_all_companies(){
        $result = $this->db
        ->select('sid, CompanyName')
        ->from('users')
        ->where('parent_sid', 0)
        ->where('active', 1)
        ->where('is_paid', 1)
        ->where('career_page_type', 'standard_career_site')
        ->order_by('sid', 'DESC')
        ->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
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
        if ($product_type != 'all') $this->db->where('background_check_orders.product_type', $product_type);
        if (sizeof($ids_array)) $this->db->where_in('background_check_orders.sid', $ids_array);


        $this->db
        ->where('DATE_FORMAT(background_check_orders.date_applied, "%Y-%m-%d") BETWEEN "' . $from_date . '" and "' . $to_date . '"')
        ->order_by("background_check_orders.sid", "desc");

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
            else
                $in_status = strtolower($tmp_array['orderStatus']['status']);

            $in_status = $in_status == 'draft' ? 'awaiting_candidate_input' : $in_status;

            if($do_count) $status_array[$in_status][] = $v0['order_sid'];

            if($in_status != $status && $status != 'all') { unset($result_arr[$k0]); continue; }
            else $result_arr[$k0]['status'] = $v0['status'] = ucwords($in_status);

            if(!$do_count){
                //
                $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = 'Candidate Not Found';
                //
                $result = $this->db
                ->select('concat(first_name," ",last_name) as full_name')
                ->where('sid', $v0['users_sid'])
                ->get( $v0['users_type'] == 'applicant' ? 'portal_job_applications' : 'users');
                $result2_arr = $result->row_array();
                $result = $result->free_result();
                if(sizeof($result2_arr)) $result_arr[$k0]['user_first_name'] = $v0['user_first_name'] = ucwords($result2_arr['full_name']);
                //
                $result_arr[$k0]['product_name'] = $v0['product_name'] = str_replace(['?Ã¡'], '', utf8_encode($v0['product_name']));
                $result_arr[$k0]['product_type'] = $v0['product_type'] = ucwords(str_replace('-', ' ', $v0['product_type']));
                //
                unset($result_arr[$k0]['order_response']);

                //
                $status_color = '';
                //
                if($v0['status'] == 'Draft') $status_color = 'style="color: #FF0000"';
                elseif($v0['status'] == 'Pending') $status_color = 'style="color: #0000FF";';
                elseif($v0['status'] == 'Completed') $status_color = 'style="color: #006400";';
                elseif($v0['status'] == 'Cancelled') $status_color = 'style="color: #FF8C00";';
                //
                $rows .= '<tr>';
                $rows .= '    <td>'.convert_date_to_frontend_format($v0['date_applied']).'</td>';
                $rows .= '    <td>'.$v0['first_name'].' '.$v0['last_name'].'</td>';
                $rows .= '    <td>'.$v0['user_first_name'].'</td>';
                $rows .= '    <td>'.ucfirst($v0['users_type']).'</td>';
                $rows .= '    <td>'.$v0['product_name'].'</td>';
                $rows .= '    <td>'.$v0['product_type'].'</td>';
                $rows .= '    <td>'.ucwords($v0['cname']).'</td>';
                $rows .= '    <td '.$status_color.'>'.($v0['status'] == 'Draft' ? 'Awaiting Candidate Input' : ucwords(str_replace('_', ' ', $v0['status']))).'</td>';
                $rows .= '    <td class="no-print"><a class="btn btn-success btn-sm" href="'.base_url().'manage_admin/accurate_background/order_status/'.$v0['order_sid'].'" >Order Status</a></td>';
                $rows .= '</tr>';
            }
        }

        if($export) return $result_arr;
        return $do_count ? array( 'TotalRecords' => count($result_arr), 'StatusArray' => $status_array ) : $rows;
    }

}
