<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function save_product($product_data) {
        $this->db->insert('products', $product_data);
        ($this->db->affected_rows() != 1) ? $this->session->set_flashdata('message', 'Product failed to save, Please try Again!') : $this->session->set_flashdata('message', 'Product is added successfully');
        //return "Product Saved Successfully";
    }

    public function get_products() {
        $this->db->order_by("sort_order", "ASC");
        $data = $this->db->get('products');
        return $data->result();
    }

    public function edit_product($edit_id) {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('sid', $edit_id);
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
            return $data->result_array();
        } else {
            return "0";
        }
    }

    public function update_product($edit_id, $data) {
        $this->db->where('sid', $edit_id);
        $this->db->update('products', $data);
        return "Product Updated Successfully";
    }

    public function delete_product($del_id) {
        $this->db->where('sid', $del_id);
        $this->db->delete('products');
    }
    

    public function activate_product($prod_id) {
        $data = array('active' => 1);
        $this->db->where('sid', $prod_id);
        $this->db->update('products', $data);
    }

    public function deactivate_product($prod_id) {
        $data = array('active' => 0);
        $this->db->where('sid', $prod_id);
        $this->db->update('products', $data);
    }
    
    public function clone_product($product_data) {
       $this->db->insert('products', $product_data);
        return "Product Clone Successfully";
    }
    
    

}