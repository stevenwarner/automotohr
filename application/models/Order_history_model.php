<?php

class order_history_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_orders($company_sid, $limit = null, $start = null)
    {
        $this->db->select('invoices.*,invoices.sid as invoice_number');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by("invoices.sid", "desc");

        $this->db->join('users', 'users.sid = invoices.user_sid');
        $records = $this->db->get_where('invoices', array('company_sid' => $company_sid))->result_array();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $record['credit_notes'] = $this->get_invoice_credit_notes($record['invoice_number']);
                $records[$key] = $record;
            }
        }

        return $records;
    }

    public function get_active_products()
    {
        $this->db->select('sid, name');
        $this->db->where('active', 1);
        return $this->db->get('products')->result_array();
    }

    public function get_active_jobs($company_sid)
    {
        $this->db->select('sid, Title');
        $this->db->where('active', 1);
        $this->db->where('user_sid', $company_sid);
        return $this->db->get('portal_job_listings')->result_array();
    }

    public function get_job_products($company_sid, $limit = null, $start = null, $search = '', $between = '')
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        if ($search != '' && $search != NULL) {
            $this->db->where($search);
        }

        if ($between != '' && $between != NULL) {
            $this->db->where($between);
        }

        $this->db->order_by("sid", "desc");
        $products = $this->db->get('jobs_to_feed')->result_array();

        $i = 0;
        foreach ($products as $product) {
            $job_title = get_job_title($product['job_sid']);
            $product_name = db_get_products_details($product['product_sid']);
            $products[$i]['job_title'] = $job_title;

            if (isset($product_name['name'])) {
                $products[$i]['product_name'] = $product_name['name'];
            } else {
                $products[$i]['product_name'] = '';
            }

            $i++;
        }

        return $products;
    }

    public function get_job_products_count($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('jobs_to_feed')->num_rows();
    }

    // Update on: 03-06-2019
    public function get_orders_total($company_sid)
    {
        return $this->db
            ->select('invoices.sid')
            ->join('users', 'users.sid = invoices.user_sid')
            ->from('invoices')
            ->where('company_sid', $company_sid)
            ->count_all_results();
        // $this->db->select('*');
        // $this->db->join('users', 'users.sid = invoices.user_sid');
        // return $this->db->get_where('invoices', array('company_sid' => $company_sid))->num_rows();
    }

    public function get_product_detail($product_sid)
    {

        $this->db->select('name');
        $this->db->where('sid', $product_sid);
        $product_detail = $this->db->get('products')->result_array();

        if (!empty($product_detail)) {
            return $product_detail[0];
        } else {
            return array();
        }
    }

    public function get_invoice_credit_notes($invoice_sid, $invoice_type = 'Marketplace')
    {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        $this->db->where('invoice_type', $invoice_type);
        $this->db->from('invoice_credit_notes');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }


    /**
     * Get company employers
     * Created on: 03-06-2019
     *
     * @param $company_sid Integer
     *
     *@return Array|Bool
     */
    function getCompanyAccounts($company_id)
    {
        $args = array('parent_sid' => $company_id, 'active' => 1, 'career_page_type' => 'standard_career_site');
        $this->db->select('sid,concat(first_name," ", last_name) as fullname, ' . (getUserFields()) . '')
            ->order_by('fullname', 'ASC');
        //$this->db->where('is_executive_admin', 0);
        $res = $this->db->get_where('users', $args);
        $ret = $res->result_array();
        $res->free_result();
        return $ret;
    }


    /**
     * Get invoices
     * Created on: 31-05-2019
     *
     * TODO
     *
     * @param $company_sid Integer
     * @param $from String
     * @param $to String
     * @param $username String
     * @param $invoice_id Integer
     * @param $payment_method String
     * @param $status String
     * @param $inset Integer
     * @param $offset Integer
     * @param $do_count Bool Optional
     * @param $get_all Bool Optional
     * @param $exec Bool Optional
     */
    function get_invoices_ajax(
        $company_sid,
        $from,
        $to,
        $username,
        $invoice_id,
        $payment_method,
        $status,
        $inset,
        $offset,
        $do_count = false,
        $get_all = false,
        $exec = false
    ) {
        $this->db
            ->select('
            invoices.sid as invoice_number,
            invoices.product_sid,
            invoices.date,
            invoices.payment_method,
            invoices.serialized_items_info,
            invoices.total,
            invoices.status,
            concat( users.first_name, " ", users.last_name) as fullname,
            users.username,
            user.CompanyName
        ')
            ->from('invoices')
            ->join('users', 'invoices.user_sid = users.sid', 'left')
            ->join('users user', 'user.sid = users.parent_sid');
        // Invoice id check
        if ($invoice_id != 'all') $this->db->where('invoices.sid', $invoice_id);
        // Username check
        if ($username && $username != 'all') $this->db->where('users.sid', $username);
        // Status check
        if ($status && $status != 'all') $this->db->where('invoices.status', $status);
        // Payment method check
        if ($payment_method && $payment_method != 'any') $this->db->where('invoices.payment_method', $payment_method);
        //
        // Date Check
        if($from != '' && $to != '') {
            $from = $from . ' 00:00:00';
            $to = $to . ' 23:59:59';
            $this->db->where(" date BETWEEN '$from' AND '$to'", NULL);
        } else if($from != '') {
            $from = $from . ' 00:00:00';
            $this->db->where(" date >= '$from'", NULL);
        } else if($to != '') {
            $to = $to . ' 23:59:59';
            $this->db->where(" date <= '$to'", NULL);
        }    
        //
        $this->db->where("invoices.company_sid", $company_sid);
        $this->db->order_by("invoices.sid", "DESC");

        if (!$do_count && !$get_all) {
            $this->db->limit($offset, $inset);
        }

        if ($exec) _e($this->db->get_compiled_select(), true);

        if ($do_count) return $this->db->count_all_results();

        $result = $this->db->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($result_arr)) return false;
        //
        $rows = '';
        foreach ($result_arr as $k0 => $v0) {
            //
            $uns = unserialize($v0['serialized_items_info']);
            $items = '';
            $product_ids = explode(',', $v0['product_sid']);
            $result_arr[$k0]['has_refund_notes'] = $v0['has_refund_notes'] = sizeof($this->get_invoice_credit_notes($v0['invoice_number'], 'Marketplace', true)) ? 1 : 0;

            $result_arr[$k0]['fullname'] = $v0['fullname'] = ucwords($v0['fullname']);
            $result_arr[$k0]['date']     = $v0['date']     = reset_datetime(array('datetime' => $v0['date'], '_this' => $this));
            $result_arr[$k0]['status']   = $v0['status']   = $v0['has_refund_notes'] == 1 ? 'Refunded' : $v0['status'];

            $rows .= '<tr>';
            $rows .= '  <td>' . $v0['invoice_number'] . '</td>';
            $rows .= '  <td>' . ($v0['fullname'] == '' ? $v0['username'] : $v0['fullname']) . '</td>';
            $rows .= '  <td>' . $v0['CompanyName'] . '</td>';
            $rows .= '  <td><p>Items Summary: </p>';
            $rows .= '      <ul class="list-unstyled invoice-description-list">';
            foreach ($product_ids as $prodcut_id_key => $product_id) {
                $product_detail  = $this->get_product_detail($product_id);
                $items .= sc_remove($product_detail['name']);
                $items .= $product_detail['name'];

                $rows .= '  <li class="invoice-description-list-item">'.sc_remove($product_detail['name']);

                //
                if (isset($uns['credit'][$product_id])) {
                    $rows .= '<br><span class="text-danger">Last Credited on ' . (formatDateToDB($uns['credit'][$product_id]['date'], 'Y-m-d H:i:s', DATE_WITH_TIME)) . '</span>';
                }
                $rows .= '</li>';
            }
            $rows .= '  </ul></td>';
            $rows .= '  <td>' . $v0['date'] . '</td>';
            $rows .= '  <td>' . $v0['payment_method'] . '</td>';
            $rows .= '  <td>$' . $v0['total'] . '</td>';
            $rows .= '  <td><span class="text-' . ($v0['status'] == 'Refunded' ? 'warning' : ($v0['status'] == 'Paid' ? 'success' : 'danger'
            )
            ) . '">' . ($v0['status']) . '</span></td>';
            $rows .= '  <td class="no-print"><a href="' . base_url('order_detail/' . $v0['invoice_number'] . '') . '" class="btn btn-success">View</a></td>';
            $rows .= '</tr>';

            $result_arr[$k0]['items'] = $items;
        }

        if ($get_all) return $result_arr;

        return $rows;
    }
}
