<?php

class Notification_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    //
    function getNotifications($ses, $isEMS = FALSE, $companyEmployeesForVerification = FALSE, $companyApplicantsForVerification = FALSE, $isEMSEnabled = 0)
    {
        $r = array();

        if (!$isEMS && $isEMSEnabled == 1) {
            // Fetch assigned documents
            $this->getAssignedDocuments($ses, $r, $companyEmployeesForVerification, $companyApplicantsForVerification);
        }
        // Fetch pending documents
        $this->getEmployeePendingDocuments($ses, $r);

        // Fetch pending documents
        $this->getEmployeePendingLarningCenterItem($ses, $r);

        if (!$isEMS) {
            if (checkIfAppIsEnabled('timeoff')) $this->getTimeOff($ses, $r);
        }

        return $r;
    }

    //
    private function getAssignedDocuments($ses, &$r, $companyEmployeesForVerification = FALSE, $companyApplicantsForVerification = FALSE)
    {
        //
        if (!$companyEmployeesForVerification) {
            $companyEmployeesForVerification = $this->getAllCompanyInactiveEmployee($ses['company_detail']['sid']);
        }
        //
        if (!$companyApplicantsForVerification) {
            $companyApplicantsForVerification = $this->getAllCompanyInactiveApplicant($ses['company_detail']['sid']);
        }
        //
        $data = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $ses['employer_detail']['sid'])
            ->where('authorized_document_assigned_manager.company_sid', $ses['company_detail']['sid'])
            // ->where('authorized_document_assigned_manager.assigned_status', 1)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->group_start()
            ->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false)
            ->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false)
            ->group_end()
            ->group_start()
            ->where('documents_assigned.authorized_signature IS NULL', null)
            ->or_where('documents_assigned.authorized_signature = ""', null)
            ->group_end()
            ->get('authorized_document_assigned_manager');
        $data_obj = $data->result_array();
        // ->count_all_results('authorized_document_assigned_manager');
        //
        foreach ($data_obj as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        //
        $data2 = $this->db
            ->select("user_type, user_sid")
            ->join('documents_assigned', 'authorized_document_assigned_manager.document_assigned_sid = documents_assigned.sid', 'inner')
            ->where('authorized_document_assigned_manager.assigned_to_sid', $ses['employer_detail']['sid'])
            ->where('authorized_document_assigned_manager.company_sid', $ses['company_detail']['sid'])
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.status', 1)
            ->where('documents_assigned.document_description like "%{{authorized_editable_date}}%"', null, false)
            ->where('documents_assigned.authorized_editable_date', null)
            ->get('authorized_document_assigned_manager');
        $data_obj2 = $data2->result_array();
        //
        foreach ($data_obj2 as $key => $v) {
            if ($v["user_type"] == "applicant") {
                if (in_array($v["user_sid"], $companyApplicantsForVerification)) {
                    unset($data_obj[$key]);
                }
            }

            if ($v["user_type"] == "employee") {
                if (in_array($v["user_sid"], $companyEmployeesForVerification)) {
                    unset($data_obj[$key]);
                }
            }
        }
        // 
        $c = count($data_obj) + count($data_obj2);
        //
        if ((int)$c !== 0) {
            $r[] = array(
                'count' => $c,
                'link' => base_url('authorized_document'),
                'title' => 'Pending Authorized Documents'
            );
        }
    }

    //
    private function getTimeOff($ses, &$r)
    {
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
        if (!sizeof($b)) return $r;
        //
        foreach ($b as $k => $v) {
            $role = $v['level_at'] == 1 ? 'teamlead' : ($v['level_at'] == 2 ? 'supervisor' : 'approver');
            // Check current employee ina ssignment
            $count = $this->db
                ->select('role')
                ->where('timeoff_request_sid', $v['sid'])
                ->where('employee_sid', $ses['employer_detail']['sid'])
                ->where('role', $role)
                ->count_all_results('timeoff_request_assignment');
            if ((int)$count !== 0) $c++;
        }
        //
        if ((int)$c !== 0) {
            $r[] = array(
                'count' => $c,
                'link' => base_url('timeoff/requests'),
                'title' => 'Time Off Requests'
            );
        }
    }

    //
    private function getEmployeePendingDocuments($ses, &$r)
    {
        //
        $company_sid = $ses['company_detail']['sid'];
        $employee_id = $ses['employer_detail']['sid'];
        $assigned_documents = $this->get_assigned_documents($company_sid, 'employee', $employee_id, 1, 0);

        $assigned_offer_letter = $this->get_assigned_offer_letter($company_sid, 'employee', $employee_id);

        foreach ($assigned_documents as $key => $assigned_document) {
            //
            $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
            //
            if ($assigned_document['archive'] == 0) {
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                if ($assigned_document['approval_process'] == 0) {
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
                } else {
                    unset($assigned_documents[$key]);
                }
            } else if ($assigned_document['archive'] == 1) {
                unset($assigned_documents[$key]);
            }
        }

        // Fetch General Documents
        $generalDocuments = $this->getGeneralAssignedDocuments($employee_id, 'employee', $company_sid);

        $w4_form = $this->is_w4_form_assign('employee', $employee_id);
        $w9_form = $this->is_w9_form_assign('employee', $employee_id);
        $i9_form = $this->is_i9_form_assign('employee', $employee_id);
        $eeo_form_status = getCompanyEEOCFormStatus($company_sid);
        //
        if ($eeo_form_status == 1) {
            $eeoc_form = $this->is_eeoc_form_assign('employee', $employee_id);
        } else {
            $eeoc_form = 0;
        }
        $stateFormCount = $this->getMyPendingStateFormsCount($ses);
        $c = count($assigned_documents) + $w4_form + $w9_form + $i9_form + $eeoc_form + count($generalDocuments) + $assigned_offer_letter + $stateFormCount;
        //
        if ((int)$c !== 0) {
            $r[] = array(
                'count' => $c,
                'link' => base_url('hr_documents_management/my_documents'),
                'title' => 'Pending Documents'
            );
        }
    }

    //
    private function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1, $archive = 0, $pp_flag = 0)
    {

        // $payroll_sids = $this->get_payroll_documents_sids();
        // $documents_management_sids = $payroll_sids['documents_management_sids'];
        // $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

        $this->db->select('
            documents_assigned.*,
            documents_management.acknowledgment_required,
            documents_management.download_required,
            documents_management.signature_required,
            documents_management.archive as company_archive,
            documents_management.visible_to_payroll');
        $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', $archive);

        if (!$fetch_offer_letter) {
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

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        //$this->db->order_by('documents_assigned.assigned_date', 'desc');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    private function is_w4_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('user_consent ', 0);
        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        return $this->db->count_all_results();
    }

    private function is_w9_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_consent', NULL);
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');
        return $this->db->count_all_results();
    }

    private function is_i9_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_consent', NULL);
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');
        return $this->db->count_all_results();
    }

    private function is_eeoc_form_assign($user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')['portal_detail']['eeo_form_profile_status']) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->where('is_expired', 0);
            $this->db->where('status', 1);
            $this->db->from('portal_eeo_form');
            return $this->db->count_all_results();
        } else {
            return 0;
        }
    }

    private function get_payroll_documents_sids()
    {
        $this->db->select('document_sid, document_type');
        $this->db->where('category_sid', PP_CATEGORY_SID);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $documents_management_sids = array();
        $documents_assigned_sids = array();
        $return_array = array();

        foreach ($record_arr as $key => $row) {
            if ($row['document_type'] == 'documents_management') {
                array_push($documents_management_sids, $row['document_sid']);
            } else {
                array_push($documents_assigned_sids, $row['document_sid']);
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
    ) {
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

    private function get_assigned_offer_letter($company_sid, $user_type, $user_sid = null)
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

    private function getEmployeePendingLarningCenterItem($ses, &$r)
    {
        $company_sid = $ses['company_detail']['sid'];
        $user_sid = $ses['employer_detail']['sid'];
        //
        // Get online video count
        $online_video_count = $this->onlineVideoCount($company_sid, $user_sid, 'employee');
        //
        // get training session count
        $training_session_count = $this->trainingSessionCount($company_sid, $user_sid, 'employee');
        //
        $learning_center_count = $online_video_count + $training_session_count;
        // print_r($ses['employer_detail']['sid']);
        // echo '<br>';
        // print_r($ses['company_detail']['sid']);
        // echo '<br>';
        // print_r($ses['employer_detail']);
        // die('stop here');
        //
        if ((int)$learning_center_count !== 0) {
            $r[] = array(
                'count' => $learning_center_count,
                'link' => base_url('my_learning_center'),
                'title' => 'Learning Center'
            );
        }
    }

    private function onlineVideoCount($company_sid, $user_sid, $user_type, $fromProfile = false)
    {
        $r = [];
        //
        if ($user_type == 'employee') {
            // Get all employees
            $this->db->select('sid, created_date, video_title, video_description, video_source, video_id, video_start_date, screening_questionnaire_sid')
                ->select('learning_center_online_videos.video_start_date')
                ->select('learning_center_online_videos.expired_start_date')
                ->where('company_sid', $company_sid)
                ->where('employees_assigned_to', 'all')
                ->order_by('created_date', 'DESC');
            //
            if (!$fromProfile) {
                $this->db
                    ->where('video_start_date <= ', date('Y-m-d', strtotime('now')))
                    ->group_start()
                    ->where('expired_start_date >= ', date('Y-m-d', strtotime('now')))
                    ->or_where('expired_start_date IS NULL', NULL)
                    ->group_end();
            }
            //
            $a = $this->db->get('learning_center_online_videos');
            $b = $a->result_array();
            $a->free_result();
            //
            $ids = array();
            //
            if (sizeof($b)) {
                foreach ($b as $k => $v) {
                    $ids[$v['sid']] = $v['sid'];
                }
            }
            //
            $r = $b;
        }
        // Get specific employees
        $this->db->select('learning_center_online_videos.sid')
            ->select('learning_center_online_videos.created_date')
            ->select('learning_center_online_videos.video_title')
            ->select('learning_center_online_videos.video_description')
            ->select('learning_center_online_videos.video_source')
            ->select('learning_center_online_videos.video_id')
            ->select('learning_center_online_videos.video_start_date')
            ->select('learning_center_online_videos.expired_start_date')
            ->select('learning_center_online_videos.screening_questionnaire_sid')
            ->select('learning_center_online_videos_assignments.learning_center_online_videos_sid')
            ->where('learning_center_online_videos_assignments.user_type', $user_type)
            ->where('learning_center_online_videos_assignments.user_sid', $user_sid)
            ->where('learning_center_online_videos_assignments.status', 1)
            ->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC')
            ->join('learning_center_online_videos', 'learning_center_online_videos.sid = learning_center_online_videos_assignments.learning_center_online_videos_sid');
        //
        if (!$fromProfile) {
            $this->db
                ->where('learning_center_online_videos.video_start_date <= ', date('Y-m-d', strtotime('now')))
                ->group_start()
                ->where('learning_center_online_videos.expired_start_date >= ', date('Y-m-d', strtotime('now')))
                ->or_where('learning_center_online_videos.expired_start_date IS NULL', NULL)
                ->group_end();
        }
        //
        $a = $this->db->get('learning_center_online_videos_assignments');
        $b = $a->result_array();
        $a->free_result();
        //
        if (sizeof($b)) {
            foreach ($b as $k => $v) {
                $ids[$v['sid']] = $v['sid'];
            }
        }
        //
        $r = array_merge($r, $b);
        //
        $current_date = date('Y-m-d');
        $video_count = 0;
        $screening_questionnaire_check = 1;
        //
        foreach ($r as $key => $single_r) {
            $video_start_date = date('Y-m-d', strtotime($single_r['video_start_date']));

            if ($video_start_date < $current_date) {

                $this->db->select('watched,sid');
                $this->db->where('learning_center_online_videos_sid', $single_r['sid']);
                $this->db->where('user_sid', $user_sid);
                $this->db->where('user_type', $user_type);
                $user_video_result = $this->db->get('learning_center_online_videos_assignments')->row_array();

                if (empty($user_video_result) || $user_video_result['watched'] == 0) {
                    $video_count = $video_count + 1;
                } else {
                    if (!empty($single_r['screening_questionnaire_sid']) || $single_r['screening_questionnaire_sid'] != 0) {
                        $this->db->select('sid');
                        $this->db->where('video_assign_sid', $user_video_result['sid']);
                        $this->db->where('video_sid', $single_r['sid']);
                        $user_video_questionnaire_result = $this->db->get('learning_center_screening_questionnaire')->row_array();

                        if (empty($user_video_questionnaire_result)) {
                            $video_count = $video_count + 1;
                        }
                    }
                }
            }
        }

        return $video_count;
    }

    private function trainingSessionCount($company_sid, $user_sid, $user_type)
    {
        $result = $this->db
            ->select('
            employees_assigned_to,
            session_status,
            sid as id
        ', false)
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->get();
        //
        $result_arr = $result->result_array();
        $result->free_result();
        $trainingSessionCount = 0;
        //
        if (sizeof($result_arr)) {
            foreach ($result_arr as $k0 => $v0) {
                if ($v0['session_status'] != 'pending' && $v0['session_status'] != 'scheduled') continue;
                //
                if ($v0['employees_assigned_to'] == 'specific') {
                    // Check if it is assigned to login employee
                    if (
                        $this->db
                        ->where('training_session_sid', $v0['id'])
                        ->where('user_sid', $user_sid)
                        ->where('user_type', $user_type)
                        ->count_all_results('learning_center_training_sessions_assignments') == 0
                    ) {
                        continue;
                    }
                }
                $trainingSessionCount++;
            }
        }
        return $trainingSessionCount;
    }

    public function getMyApprovalDocuments($employee_sid)
    {
        //
        $this->db->select('portal_document_assign_flow_employees.sid');

        $this->db->where('portal_document_assign_flow_employees.assigner_sid', $employee_sid);
        $this->db->where('portal_document_assign_flow_employees.status', 1);
        $this->db->where('portal_document_assign_flow_employees.assigner_turn', 1);
        $this->db->where('portal_document_assign_flow.assign_status', 1);
        $this->db->where('documents_assigned.approval_process', 1);

        $this->db->join('portal_document_assign_flow', 'portal_document_assign_flow.sid = portal_document_assign_flow_employees.portal_document_assign_sid', 'inner');
        $this->db->join('documents_assigned', 'documents_assigned.approval_flow_sid = portal_document_assign_flow.sid', 'inner');
        $records_obj = $this->db->get('portal_document_assign_flow_employees');

        $my_documents = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($my_documents)) {
            $return_data = $my_documents;
        }

        return $return_data;
    }

    function getAllCompanyInactiveEmployee($companySid)
    {
        $a = $this->db
            ->select('
            sid
        ')
            ->where('parent_sid', $companySid)
            ->where('active', 0)
            ->where('parent_sid <> ', 0)
            ->or_where('terminated_status', 1)
            ->order_by('first_name', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    function getAllCompanyInactiveApplicant($companySid)
    {
        $a = $this->db
            ->select('
            portal_applicant_jobs_list.portal_job_applications_sid as sid
        ')
            ->where('portal_applicant_jobs_list.company_sid', $companySid)
            ->group_start()
            ->where('portal_applicant_jobs_list.archived', 1)
            ->or_where('portal_job_applications.hired_status', 1)
            ->group_end()
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    /**
     * get pending state forms for employees
     *
     * @param array $ses
     * @param array $r
     * @return int
     */
    private function getMyPendingStateFormsCount($ses): int
    {
        //
        return $this->db
            ->where("company_sid", $ses["company_detail"]["sid"])
            ->where("user_sid", $ses["employer_detail"]["sid"])
            ->where("status", 1)
            ->where("user_consent", 0)
            ->count_all_results("portal_state_form");
    }
}
