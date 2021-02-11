<?php
class market_place_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getProducts()
    {
        $this->db->where('featured', 1);
        $this->db->where('active', 1);
        $this->db->where('in_market', 1);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('products')->result_array();
    }

    function getProductsDetail($sid = null)
    {
        $this->db->where('active', 1);
        $this->db->where('sid', $sid);
        return $this->db->get('products')->result_array();
    }

    function insertToCart($data, $redirecturl)
    {
        $this->db->insert('shopping_cart', $data, $sid = null);
        if ($this->db->affected_rows() != 1) {
            $this->session->set_flashdata('message', '<b>Error!</b> Could not add to cart, Please try Again!');
            redirect($redirecturl, 'refresh');
        } else {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $cart_data = db_get_cart_content($company_sid);

            $sess_array = array(
                'company_detail' => $data['session']['company_detail'],
                'employer_detail' => $data['session']['employer_detail'],
                'cart' => $cart_data,
                'portal_detail' => $data['session']['portal_detail'],
                'clocked_status' => $data['session']['clocked_status']
            );

            if (isset($data['session']['is_super']) && intval($data['session']['is_super']) == 1) {
                $sess_array['is_super'] = 1;
            } else {
                $sess_array['is_super'] = 0;
            }

            $this->session->set_userdata('logged_in', $sess_array);
            $this->session->set_flashdata('message', '<b>Success!</b> Product added to your shopping cart.');
            redirect($redirecturl, 'refresh');
        }
    }

    function updateCart($data, $redirecturl, $sid)
    {
        $this->db->where('sid', $sid);
        $this->db->update('shopping_cart', $data);

        if ($this->db->affected_rows() != 1) {
            $this->session->set_flashdata('message', '<b>Error!</b> Could not add to cart, Please try Again!');
            redirect($redirecturl, 'refresh');
        } else {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $cart_data = db_get_cart_content($company_sid);
            $sess_array = array(
                'company_detail' => $data['session']['company_detail'],
                'employer_detail' => $data['session']['employer_detail'],
                'cart' => $cart_data,
                'portal_detail' => $data['session']['portal_detail'],
                'clocked_status' => $data['session']['clocked_status']
            );

            if (isset($data['session']['is_super']) && intval($data['session']['is_super']) == 1) {
                $sess_array['is_super'] = 1;
            } else {
                $sess_array['is_super'] = 0;
            }

            $this->session->set_userdata('logged_in', $sess_array);
            $this->session->set_flashdata('message', '<b>Success!</b> Product added to your shopping cart.');
            redirect($redirecturl, 'refresh');
        }
    }

    function getProductType($productType, $company_id)
    {
        if ($productType != 'all') {
            $this->db->where('product_type', $productType);
        }

        $this->db->where('in_market', 1);
        $this->db->where('active', 1);
        //
        // if(!in_array($company_id, [51, 57])) $this->db->where('product_brand != ', 'assurehire');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('products')->result_array();
    }

    function get_company_status($sid)
    {
        $this->db->select('career_site_listings_only, per_job_listing_charge');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr[0];
    }
}
