<?php

class subaccount_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function add_subaccount($data) {
        $this->db->insert('users', $data);
    }

}
