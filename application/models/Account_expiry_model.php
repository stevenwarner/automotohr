<?php

class account_expiry_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function save_account_expiry($data) {
        $this->db->insert('account_expiry', $data);
        $result = $this->db->affected_rows();
        if ($result == 1)
            return true;
        else
            return false;
    }

}

?>