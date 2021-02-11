<?php

class send_received_docs_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_documents($company_sid) {
        $data = $this->db->get_where('hr_user_document', array('company_sid' => $company_sid, 'document_type' => 'document'))->result_array();
        return $data;
    }

}
