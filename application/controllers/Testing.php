<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('hr_documents_management_model');
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }

    public function testScript () {
        $info = getMyDepartmentAndTeams(15717, "courses");
        // $info = getMyDepartmentAndTeams(15717);
        _e($info,true,true);
    }

    public function pendingDocument ($companySid, $employeeSid) {
        $this->load->model('dashboard_model');
        $assigned_documents = $this->dashboard_model->get_assigned_documents($companySid, 'employee', $employeeSid, 0);
        $assigned_offer_letter = $this->dashboard_model->get_assigned_offer_letter($companySid, 'employee', $employeeSid);
        $is_w4_assign = $this->dashboard_model->check_w4_form_exist('employee', $employeeSid);
        $is_w9_assign = $this->dashboard_model->check_w9_form_exist('employee', $employeeSid);
        $is_i9_assign = $this->dashboard_model->check_i9_exist('employee', $employeeSid);
        //
        $documents_count = 0;


        if (!empty($is_w4_assign)) {
            _e($is_w4_assign,true);
            $documents_count++;
        }

        _e($documents_count."w4",true);

        if (!empty($is_w9_assign)) {
            _e($is_w9_assign,true);
            $documents_count++;
        }

        _e($documents_count."w9",true);

        if (!empty($is_i9_assign)) {
            _e($is_i9_assign,true);
            $documents_count++;
        }

        _e($documents_count."i9",true);

        if (!empty($assigned_offer_letter)) {
            _e($assigned_offer_letter,true);
            $documents_count++;
        }

        _e($documents_count."OL",true);

        $this->load->model('hr_documents_management_model');
        //
        if ($this->hr_documents_management_model->hasEEOCPermission($companySid, 'eeo_on_employee_document_center')) {
            $eeoc_form = $this->hr_documents_management_model->get_eeo_form_info($employeeSid, 'employee');

            if (!empty($eeoc_form) && $eeoc_form['status'] == 1 && $eeoc_form['is_expired'] == 0) {
                $documents_count++;
            }
        }
        // $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($companySid, 'employee', $employeeSid, 0, 0);
        //
        _e(count($assigned_documents),true);
        //
        foreach ($assigned_documents as $key => $assigned_document) {
            //
            $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
            //
            $is_magic_tag_exist = 0;
            $is_document_completed = 0;
            //
            if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                $document_body = $assigned_document['document_description'];
                $magic_codes = array('{{signature}}', '{{inital}}');

                if (str_replace($magic_codes, '', $document_body) != $document_body) {
                    $is_magic_tag_exist = 1;
                }
            }
            //
          
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
                            unset($assigned_documents[$key]);
                        }
                    } else {
                        $revoked_sids[] = $assigned_document['document_sid'];
                    }
                }
            } else {
                unset($assigned_documents[$key]);
            }
        }
        //
        $documents_count = $documents_count + sizeof($assigned_documents) + $this->hr_documents_management_model->getGeneralDocumentCount(
            $employeeSid,
            'employee',
            $companySid
        );
        $GDCount = $this->hr_documents_management_model->getGeneralDocumentCount(
            $employeeSid,
            'employee',
            $companySid
        );
        //
        _e($GDCount,true);
        _e($documents_count,true);
        _e(count($assigned_documents),true,true);
    }

    public function assignedDocument ($companySid, $employeeSid) {
        $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($companySid, 'employee', $employeeSid, 0, 1, 0, 0, 1, 1);
        //
        _e(count($assigned_documents),true);
        //
        foreach ($assigned_documents as $key => $assigned_document) {
            if ($assigned_document['archive'] == 0) {
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;
                //
                //check Document Previous History
                $previous_history = $this->hr_documents_management_model->check_if_document_has_history('employee', $employer_sid, $assigned_document['sid']);
                //
                if (!empty($previous_history)) {
                    array_push($history_doc_sids, $assigned_document['sid']);
                }
                //
                if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
                $documents_management_sids = $payroll_sids['documents_management_sids'];
                $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

                if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else {
                    $assigned_document['pay_roll_catgory'] = 0;
                }

                if ($assigned_document['document_type'] != 'offer_letter') {
                    if ($assigned_document['status'] == 1) {
                        if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

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

                                if ($assigned_document['is_confidential'] == 0) {
                                    if ($assigned_document['pay_roll_catgory'] == 0) {

                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $completed_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                } else {
                                    unset($assigned_documents[$key]);
                                }
                            } else {
                                if ($assigned_document['pay_roll_catgory'] == 1) {
                                    $uncompleted_payroll_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }

                                $assigned_sids[] = $assigned_document['document_sid'];
                            }
                        } else {
                            if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {
                                //
                                if (!empty($assigned_document['authorized_signature'])) {
                                    if ($assigned_document['pay_roll_catgory'] == 0) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $completed_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                } else {
                                    if ($assigned_document['pay_roll_catgory'] == 1) {
                                        $uncompleted_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                }
                                //
                                $assigned_sids[] = $assigned_document['document_sid'];
                            } else if ($assigned_document['pay_roll_catgory'] == 0) {
                                $assigned_sids[] = $assigned_document['document_sid'];
                                $no_action_required_sids[] = $assigned_document['document_sid'];
                                $no_action_required_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                if ($assigned_document['user_consent'] == 1 && $assigned_document['document_sid'] == 0) {
                                    $no_action_required_payroll_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                            }
                        }
                    } else {
                        $revoked_sids[] = $assigned_document['document_sid'];
                    }
                }
            }
            
        }
        //
        _e(count($assigned_documents),true,true);
    }

    public function fixRequired () {
        $this->db->select('sid');
        $this->db->where('is_required', 1);
        $this->db->where('archive', 0);
        $record_obj = $this->db->get('documents_management');
        $requiredDocuments = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($requiredDocuments)) {
            foreach ($requiredDocuments as $document) {
                $data_to_update = array();
                $data_to_update['is_required'] = 1;
                $this->db->where('document_sid', $document['sid']);
                $this->db->update('documents_assigned', $data_to_update);
            }
            //
            echo "End Script";
            
        } 
    }

    public function test()
    {
        $this->load->model('hr_documents_management_model');
        //
        $company_sid = 16406;
        $user_sid = 1098997;
        $user_type = 'applicant';
        //
        $generalDocuments = $this->hr_documents_management_model->getUncompletedGeneralAssignedDocuments(
            $company_sid,
            $user_sid,
            $user_type
        );

        //
        $documents = $this->hr_documents_management_model->getUncompletedAssignedDocuments(
            $company_sid,
            $user_sid,
            $user_type
        );
        //
        _e($generalDocuments,true);
        _e($documents,true,true);
        $this->load->model("v1/payroll_model", "payroll_model");
        //
        $this->payroll_model->onboardEmployee(
            53,
            21
        );
    }

}
