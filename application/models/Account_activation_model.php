<?php

class account_activation_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user_detail_by_activation_key($activationKey) {
        return $this->db->get_where('users', array('activation_key' => $activationKey));
    }

    function get_standard_products() {
        $keyword = "standard";
        $this->db->where('product_type', 'account-package');
        $this->db->where("name LIKE '%$keyword%'");
        $this->db->where('active', 1);
        $this->db->where('in_market', 1);
        $this->db->order_by('sort_order', 'DESC');
        return $this->db->get('products')->result_array();
    }

    function get_enterprise_products() {
        $keyword = "enterprise";
        $this->db->where('product_type', 'account-package');
        $this->db->where("name LIKE '%$keyword%'");
        $this->db->where('active', 1);
        $this->db->where('in_market', 1);
        $this->db->order_by('sort_order', 'DESC');
        return $this->db->get('products')->result_array();
    }

}
