<?php

class Promotions_model extends CI_Model {

    protected $messages;

    function __construct() {
        parent::__construct();
    }

    function get_all_promotions() {
        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $this->db->from('promotions');
        return $this->db->get()->result_array();
    }

    function total_promotions() {
        $this->db->select('*');
        $this->db->from('promotions');
        return $this->db->get()->num_rows();
    }

    public function promotion_details($sid = NULL) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('promotions');
        return $this->db->get()->result_array();
    }

    function save_coupon($code, $discount, $type, $start_date, $end_date, $maximum_uses, $active) {
        if (!empty($start_date)) {
            $start_date = explode('-', $start_date);
            $start_date = $start_date['2'] . '-' . $start_date['0'] . '-' . $start_date['1'];
        }
        if (!empty($end_date)) {
            $end_date = explode('-', $end_date);
            $end_date = $end_date['2'] . '-' . $end_date['0'] . '-' . $end_date['1'];
        }
        $data = array(
            'code' => $code,
            'discount' => $discount,
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'maximum_uses' => $maximum_uses,
            'active' => (empty($active) ? 0 : 1)
        );
        $this->db->insert('promotions', $data);
        //echo $this->db->insert_string('promotions', $data);
        ($this->db->affected_rows() != 1) ? $this->session->set_flashdata('message', 'Promotional code is add failed, Please try Again!') : $this->session->set_flashdata('message', 'Promotional code is added successfully');
    }

    function edit_coupon($sid, $code, $discount, $type, $start_date, $end_date, $maximum_uses, $active) {

        $start_date_array = array();
        $end_date_array = array();

        if (!empty($start_date)) {
            $start_date = explode('-', $start_date);
            $start_date = $start_date['2'] . '-' . $start_date['0'] . '-' . $start_date['1'];
            $start_date_array = array('start_date' => $start_date);
        }

        if (!empty($end_date)) {
            $end_date = explode('-', $end_date);
            $end_date = $end_date['2'] . '-' . $end_date['0'] . '-' . $end_date['1'];
            $end_date_array = array('end_date' => $end_date);
        }

        $dates = array_merge($start_date_array, $end_date_array);

        $data = array(
            'code' => $code,
            'discount' => $discount,
            'type' => $type,
            'maximum_uses' => $maximum_uses,
            'active' => (empty($active) ? 0 : 1)
        );

        $data = array_merge($data, $dates);

        $this->db->where('sid', $sid);
        $result = $this->db->update('promotions', $data);

        (!$result) ? $this->session->set_flashdata('message', 'Update Failed, Please try Again!') : $this->session->set_flashdata('message', 'Promotional code updated successfully');
    }

    /**
     * messages
     *
     * Get the messages
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function messages() {
        $_output = '';
        foreach ($this->messages as $message) {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
        }

        return $_output;
    }

    function delete_promotion($id) {
        $this->db->where('sid', $id);
        $this->db->delete('promotions');
    }

    function deactive_promotion($id) {
        $data = array(
            'active' => 0
        );
        $this->db->where('sid', $id);
        $this->db->update('promotions', $data);
    }

    function active_promotion($id) {
        $data = array(
            'active' => 1
        );
        $this->db->where('sid', $id);
        $this->db->update('promotions', $data);
    }

}
