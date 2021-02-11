<?php

class Home_page_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_home_page_custom_data() {
        return $this->db->get('home_page')->row_array();
    }

    function insert_home_page_custom_data($dataToSave) {
        $this->db->insert('home_page', $dataToSave);
    }

    function update_home_page_custom_data($dataToSave) {
        $this->db->where('sid', 1)->update('home_page', $dataToSave);
    }

}
