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

    public function testScript()
    {
        $info = getMyDepartmentAndTeams(15717, "courses");
        // $info = getMyDepartmentAndTeams(15717);
        _e($info, true, true);
    }

    public function pendingDocument($companySid, $employeeSid)
    {
        $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($companySid, 'employee', $employeeSid, 0, 0);
        //
        _e(count($assigned_documents), true);
        //
        foreach ($assigned_documents as $key => $assigned_document) {
            if ($assigned_document['archive'] == 0) {
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                    $document_body = $assigned_document['document_description'];
                    // $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                    $magic_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                // if ($assigned_document['approval_process'] == 0) {
                if ($assigned_document['document_type'] != 'offer_letter') {
                    if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0 && $assigned_document['status'] == 1) {
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
                            unset($assigned_documents[$key]);
                        } else {
                            $assigned_sids[] = $assigned_document['document_sid'];
                        }
                    } else {
                        unset($assigned_documents[$key]);
                    }

                    /*
                        if ($is_document_completed > 0) {
                            unset($assigned_documents[$key]);
                        } else {
                            unset($assigned_documents[$key]);
                        }
                        */
                } else {
                    unset($assigned_documents[$key]);
                }
                //    } else {
                //       unset($assigned_documents[$key]);
                //   }
            }
        }
        //
        _e(count($assigned_documents), true, true);
    }

    public function assignedDocument($companySid, $employeeSid)
    {
        $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($companySid, 'employee', $employeeSid, 0, 1, 0, 0, 1, 1);
        //
        _e(count($assigned_documents), true);
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
        _e(count($assigned_documents), true, true);
    }

    public function fixRequired()
    {
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
        _e($generalDocuments, true);
        _e($documents, true, true);
        $this->load->model("v1/payroll_model", "payroll_model");
        //
        $this->payroll_model->onboardEmployee(
            53,
            21
        );
    }



    //
    public function payRollTimeCalculation($startDate = '', $endDate = '', $employeeId = '')
    {

        $startDate = '';
        $endDate = '';
        $employeeId = '49345';


        /*

        $policiesDetail  = $this->timeoff_model->getEmployeeTimeOffAmountByDate(
            $request['company_sid'],
            $request['userId'],
            $request['request_from_date'],
            [$request['timeoff_policy_sid']]
         );
*/

        $this->load->helper('timeoff');
        //
        $this->load->model('timeoff_model');
        // Get request
        // $employeeId = '49290';

        //  $requests = $this->timeoff_model->getRequestByEmployeeId($employeeId);

        //   $request = $this->timeoff_model->getRequestById(85);

        //   _e($request,true);

        // $post['employerId']='49290';
        //  $post['level'] =1;
        //   $post['filter']['employees']='49290';
        //  $post['companyId']='8578';

        //  ction: get_requests
        /*
        $post['companyId'] = 8578;
        $post['employerId'] = 8579;
        $post['employeeId'] = 8579;
        $post['level'] = 1;
        $post['type'] = 'approved';
        $post['filter']['employees'] = 49290;
        $post['filter']['policies'] = 'all';
        $post['filter']['status'] = 'all';
        $post['filter']['order'] = 'upcoming';
        $post['filter']['startDate'] = '10-27-2023';
        $post['filter']['endDate'] = '11-03-2023';
        $post['public'] = '0';
*/


        // $requests = $this->timeoff_model->getRequests($post);

        // _e($requests, true, true);

        //foreach($requests as $request ){

        // _e($request,true);

        //

        $employeeId = 49290;
        $companyId = 8578;
        $startDate = '10-27-2023';

        $policiesDetail  = $this->timeoff_model->getEmployeeTimeOffAmountByDate(
            $companyId,
            $employeeId,
            $startDate
        );

        _e($policiesDetail, true);

        //}



        //
        //     $policiesDetail  = $this->timeoff_model->getEmployeePoliciesByDate(
        //        $request['company_sid'],
        //        $request['userId'],
        //        $request['request_from_date'],
        //        [$request['timeoff_policy_sid']]
        //    );



    }
}
