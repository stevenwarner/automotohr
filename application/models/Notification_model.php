<?php

class Notification_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    //
    function getNotifications($ses, $isEMS = FALSE){
        $r = array();

        if(!$isEMS){
            // Fetch assigned documents
            $this->getAssignedDocuments($ses, $r);
        }
        // Fetch pending documents
        $this->getEmployeePendingDocuments($ses, $r);

        if(!$isEMS){
            if(checkIfAppIsEnabled('timeoff')) $this->getTimeOff($ses, $r);
        }   

        return $r; 
    }

    //
    private function getAssignedDocuments($ses, &$r){
        $c = $this->db
        ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
        ->where('authorized_document_assigned_manager.assigned_to_sid', $ses['employer_detail']['sid'])
        ->where('authorized_document_assigned_manager.company_sid', $ses['company_detail']['sid'])
        // ->where('authorized_document_assigned_manager.assigned_status', 1)
        ->where('documents_assigned.archive', 0)
        ->where('documents_assigned.status', 1)
        ->group_start()
        ->where('documents_assigned.document_description regexp "{{authorized_signature}}"', null ,false)
        ->or_where('documents_assigned.document_description regexp "{{authorized_signature_date}}"', null ,false)
        ->group_end()
        ->group_start()
        ->where('documents_assigned.authorized_signature IS NULL', null)
        ->or_where('documents_assigned.authorized_signature = ""', null)
        ->group_end()
        ->count_all_results('authorized_document_assigned_manager');
        //
        if((int)$c !== 0){
            $r[] = array(
                'count' => $c,
                'link' => base_url('authorized_document'),
                'title' => 'Assigned Authorize Documents'
            );
        }
    }

    //
    private function getTimeOff($ses, &$r){
        $a = $this->db
        ->select('sid, level_at, date_format(created_at, "%Y-%m-%d") as created_at')
        ->where('company_sid', $ses['company_detail']['sid'])
        ->where('status', 'pending')
        ->where('is_draft', '0')
        ->get('timeoff_requests');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $c = 0;
        //
        if(!sizeof($b)) return $r;
        //
        foreach ($b as $k => $v) {
            $role = $v['level_at'] == 1 ? 'teamlead' : ( $v['level_at'] == 2 ? 'supervisor' : 'approver');
            // Check current employee ina ssignment
            $count = $this->db
            ->select('role')
            ->where('timeoff_request_sid', $v['sid'])
            ->where('employee_sid', $ses['employer_detail']['sid'])
            ->where('role', $role)
            ->count_all_results('timeoff_request_assignment');
            if((int)$count !== 0) $c++;
        }
        //
        if((int)$c !== 0){
            $r[] = array(
                'count' => $c,
                'link' => base_url('timeoff/requests'),
                'title' => 'Time Off Requests'
            );
        }
    }

    //
    private function getEmployeePendingDocuments($ses, &$r){
        //
        $company_sid = $ses['company_detail']['sid'];
        $employee_id = $ses['employer_detail']['sid'];
        $assigned_documents = $this->get_assigned_documents($company_sid, 'employee', $employee_id, 1, 0);
        
        $assigned_offer_letter = $this->get_assigned_offer_letter($company_sid, 'employee', $employee_id);

        foreach ($assigned_documents as $key => $assigned_document) {
            $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                if ($assigned_document['document_type'] != 'offer_letter') {
                    if ($assigned_document['status'] == 1) {
                        if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0) {

                            if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                if ($is_magic_tag_exist == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['acknowledgment_required'] == 1) {
                                if ($assigned_document['acknowledged'] == 1) {
                                    $is_document_completed = 1;
                                } else if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['download_required'] == 1) {
                                if ($assigned_document['downloaded'] == 1) {
                                    $is_document_completed = 1;
                                } else if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['signature_required'] == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($is_magic_tag_exist == 1) {
                                if ($assigned_document['user_consent'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            }

                            if ($is_document_completed > 0) {
                                if (!empty($assigned_document['uploaded_file']) || !empty($assigned_document['submitted_description'])) {
                                    $signed_document_sids[] = $assigned_document['document_sid'];
                                    // $signed_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                } else {
                                    $completed_document_sids[] = $assigned_document['document_sid'];
                                    // $completed_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                                $completed_sids[] = $assigned_document['document_sid'];
                                $signed_documents[] = $assigned_document;
                            } else {
                                $assigned_sids[] = $assigned_document['document_sid'];
                            }
                        } else { // nothing is required so it is "No Action Required Document"
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        }
                    } else {
                        $revoked_sids[] = $assigned_document['document_sid'];
                    }
                }
        }

        // Fetch General Documents
        $generalDocuments = $this->getGeneralAssignedDocuments($employee_id, 'employee', $company_sid);

        $w4_form = $this->is_w4_form_assign('employee', $employee_id);
        $w9_form = $this->is_w9_form_assign('employee', $employee_id);
        $i9_form = $this->is_i9_form_assign('employee', $employee_id);
        // $c = count($assigned_documents) + $w4_form + $w9_form + $i9_form + count($generalDocuments);
        $c = count($assigned_documents) + $w4_form + $w9_form + $i9_form + count($generalDocuments) + $assigned_offer_letter;
        //
        if((int)$c !== 0){
            $r[] = array(
                'count' => $c,
                'link' => base_url('hr_documents_management/my_documents'),
                'title' => 'Pending Documents'
            );
        }
    }

    //
    private function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1, $archive = 0, $pp_flag = 0) {

        // $payroll_sids = $this->get_payroll_documents_sids();
        // $documents_management_sids = $payroll_sids['documents_management_sids'];
        // $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive,documents_management.visible_to_payroll');
        $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', $archive);

        if(!$fetch_offer_letter){
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        // if($pp_flag) {
        //     $this->db->group_start();
        //     $this->db->where('documents_management.visible_to_payroll',1);
        //     $this->db->or_where('documents_assigned.visible_to_payroll',1);

        //     if (!empty($documents_management_sids)) {
        //         $this->db->or_where_in('documents_management.sid',$documents_management_sids);
        //     }

        //     if (!empty($documents_assigned_sids)) {
        //         $this->db->or_where_in('documents_assigned.sid',$documents_assigned_sids);
        //     }
        //     $this->db->group_end();
        // }
        
        $this->db->join('documents_management','documents_management.sid = documents_assigned.document_sid','left');
        //$this->db->order_by('documents_assigned.assigned_date', 'desc');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    private function is_w4_form_assign($user_type, $user_sid) {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('user_consent ', 0);
        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        return $this->db->count_all_results();
    }

    private function is_w9_form_assign($user_type, $user_sid) {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_consent', NULL);
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');
        return $this->db->count_all_results();
    }

    private function is_i9_form_assign($user_type, $user_sid) {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_consent', NULL);
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');
        return $this->db->count_all_results();
    }

    private function get_payroll_documents_sids () {
        $this->db->select('document_sid, document_type');
        $this->db->where('category_sid', PP_CATEGORY_SID);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $documents_management_sids = array();
        $documents_assigned_sids = array();
        $return_array = array();

        foreach ($record_arr as $key=> $row) {
            if ($row['document_type'] == 'documents_management') {
                array_push($documents_management_sids,$row['document_sid']);
            } else {
                array_push($documents_assigned_sids,$row['document_sid']);
            }
        }

        $return_array['documents_management_sids'] = $documents_management_sids;
        $return_array['documents_assigned_sids'] = $documents_assigned_sids;

        return $return_array;
    }

    // 
    function getGeneralAssignedDocuments(
        $userSid,
        $userType,
        $companySid
    ){
        //
        $a = $this->db
        ->where('company_sid', $companySid)
        ->where('user_sid', $userSid)
        ->where('user_type', $userType)
        ->where('status', 1)
        ->where('is_completed', 0)
        ->order_by('sid', 'desc')
        ->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    private function get_assigned_offer_letter ($company_sid, $user_type, $user_sid = null)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('archive', 0);
        $this->db->where('status', 1);
        $this->db->where('user_consent', 0);
        $this->db->from('documents_assigned');

        return $this->db->count_all_results();
    }

}
