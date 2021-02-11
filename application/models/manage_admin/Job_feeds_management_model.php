<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job_feeds_management_model extends CI_Model
{
    public function get_all_pending_jobs($activation_status = null, $refund_status = null)
    {
        $this->db->select('jobs_to_feed.*');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('users_a.CompanyName');
        $this->db->select('users_b.first_name');
        $this->db->select('users_b.last_name');
        $this->db->select('products.name as product_name');

        if ($activation_status !== null) {
            $this->db->where('jobs_to_feed.activation_status', $activation_status);
        } else if ($activation_status == 0) {
            $this->db->where('jobs_to_feed.activation_date', null);
            $this->db->where('jobs_to_feed.expiry_date', null);
            $this->db->where('jobs_to_feed.activation_status', $activation_status);
        }

        if ($refund_status !== null) {
            $this->db->where('refund_status', $refund_status);
        }

        $this->db->where('purchased_date >', '2018-01-01 00:00:00');

        $this->db->order_by('jobs_to_feed.purchased_date', 'DESC');

        $this->db->join('portal_job_listings', 'jobs_to_feed.job_sid = portal_job_listings.sid', 'left');
        $this->db->join('users as users_a', 'jobs_to_feed.company_sid = users_a.sid', 'left');
        $this->db->join('users as users_b', 'jobs_to_feed.employer_sid = users_b.sid', 'left');
        $this->db->join('products', 'jobs_to_feed.product_sid = products.sid', 'left');

        $records_obj = $this->db->get('jobs_to_feed');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function update_jobs_to_feed($jobs_to_feed_sid, $company_sid, $product_sid, $job_sid, $data_to_update)
    {
        $this->db->where('sid', $jobs_to_feed_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('product_sid', $product_sid);
        $this->db->where('job_sid', $job_sid);

        $this->db->update('jobs_to_feed', $data_to_update);
    }

    function generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, $quantity, $no_of_days)
    {
        $invoice_data = array();
        $invoice_data['user_sid'] = $employer_sid;
        $invoice_data['company_sid'] = $company_sid;
        $invoice_data['date'] = date('Y-m-d H:i:s');
        $invoice_data['payment_method'] = 'job_to_feed_refund';
        $invoice_data['coupon_code'] = 'job_to_feed';
        $invoice_data['coupon_type'] = 'fixed';
        $invoice_data['total_discount'] = 0;
        $invoice_data['sub_total'] = 0;
        $invoice_data['total'] = 0;
        $invoice_data['status'] = 'Paid';
        $invoice_data['verification_response'] = 99;
        $invoice_data['product_sid'] = $product_sid;
        $invoice_data['description'] = 'This invoice is generated when Job Feed Product is refunded from super admin.';
        $invoice_data['payment_date'] = date('Y-m-d H:i:s');

        $item_array = array();
        $item_array['custom_text'] = array('');
        $item_array['item_qty'] = array($quantity);
        $item_array['item_price'] = array(0);
        $item_array['products'] = array($product_sid);
        $item_array['item_remaining_qty'] = array($quantity);
        $item_array['no_of_days'] = array($no_of_days);
        $item_array['flag'] = array('no_edit');
        $invoice_data['serialized_items_info'] = serialize($item_array);

        $this->db->insert('invoices', $invoice_data);

        return $this->db->insert_id();

    }

    public function insert_invoice_track_initial_record($data_to_insert)
    {
        $this->db->insert('invoice_items_track', $data_to_insert);
    }

    public function mark_jobs_to_feed_request_as_refunded($request_sid, $refund_invoice_sid)
    {
        $this->db->set('refund_status', 1);
        $this->db->set('refund_invoice_sid', $refund_invoice_sid);
        $this->db->set('refund_date', date('Y-m-d H:i:s'));

        $this->db->where('sid', $request_sid);

        $this->db->update('jobs_to_feed');

    }

    public function set_read_status($pending_job_sid, $status)
    {
        $this->db->where('sid', $pending_job_sid);
        $this->db->set('read_status', $status);
        $this->db->from('jobs_to_feed');
        $this->db->update();
    }
}
