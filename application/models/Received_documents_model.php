<?php

class received_documents_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user_detail_from_hr_document($verificationKey) {
        return $this->db->get_where('hr_user_document', array('verification_key' => $verificationKey));
    }
    function get_user_detail($verificationKey) {
        return $this->db->get_where('hr_user_document', array('verification_key' => $verificationKey));
    }
      function get_user_detail_from_users($verificationKey) {
        return $this->db->get_where('users', array('verification_key' => $verificationKey));
    }

}
