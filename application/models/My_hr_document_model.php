<?php

class my_hr_document_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getAllReceivedDocuments($type, $employer_sid) {
        return $this->db->select('*,hr_user_document.sid as user_document_sid')->join('hr_documents', 'hr_user_document.document_sid = hr_documents.sid')
                        ->get_where('hr_user_document', array('receiver_sid' => $employer_sid, 'hr_user_document.document_type' => $type))->result_array();
    }

    function getAllReceivedOfferLetters($type, $employer_sid) {
        return $this->db->select('*,hr_user_document.sid as user_document_sid')->join('offer_letter', 'hr_user_document.document_sid = offer_letter.sid')
                        ->get_where('hr_user_document', array('receiver_sid' => $employer_sid, 'hr_user_document.document_type' => $type))->result_array();
    }

    function updateUserDocument($document_id, $updatedData) {
        $this->db->where('sid', $document_id)->update('hr_user_document', $updatedData);
    }

    function get_user_document_detail($offerLetterId) {
        return $this->db->where('sid', $offerLetterId)->get('hr_user_document');
    }

    function removeVerificationKey($document_id) {
        $res = $this->db->get_where('hr_user_document', array('hr_user_document.sid' => $document_id))->result_array();
        $res = $res[0];
        
        if ($res['document_type'] == 'document') {
            $data = $this->db->get_where('hr_documents', array('sid' => $res['document_sid']))->result_array();
            $data = $data[0];
            if ($data['action_required'] == 1) {
                if ($res['acknowledged'] == 1 && $res['uploaded'] == 1) {
                    return "true";
                } else {
                    return "false";
                }
            } else {
                if ($res['acknowledged'] == 1) {
                    return "true";
                } else {
                    return "false";
                }
            }
        } else {
            if ($res['acknowledged'] == 1) {
                return "true";
            } else {
                return "false";
            }
        }
    }


    function check_document_ack_dwn_upl($applicant_sid, $document_sid)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('receiver_sid', $applicant_sid);
        $this->db->group_start();
        $this->db->where('acknowledged', 1);
        $this->db->or_where('downloaded', 1);
        $this->db->or_where('uploaded', 1);
        $this->db->group_end();

        $this->db->from('hr_user_document');
        $record_count = $this->db->count_all_results();

        //echo $record_count . ' - ';

        if ($record_count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function check_document_archive_status($document_sid)
    {
        $this->db->select('archive');
        $this->db->where('sid', $document_sid);
        $records_obj = $this->db->get('hr_documents');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr))
        {
            return $records_arr[0]['archive'];
        } else {
            return 0;
        }
    }
}
