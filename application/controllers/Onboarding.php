<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('onboarding_model');
        $this->load->model('form_wi9_model');
        $this->load->model('application_tracking_system_model');
        $this->load->model('hr_documents_management_model');
        $this->load->model('learning_center_model');
        $this->load->model('eeo_model');
        $this->load->library('pdfgenerator');
        $this->load->library('encryption');
        //
        $this->_ssv = false;
        $ses = $this->session->userData('logged_in');
        //
        if (sizeof($ses)) {
            $this->_ssv = getSSV($ses['employer_detail']);
        }
    }

    public function hr_documents($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data = array();
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
            $company_name = $onboarding_details['company_info']['CompanyName'];

            if (!empty($onboarding_details)) {
                $data['title'] = 'Onboarding: ' . $company_name;
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $groups_assign = $this->hr_documents_management_model->get_all_documents_group_assigned($company_info['sid'], 'applicant', $applicant_sid);

                // 
                $data['GID']['company_sid'] = $company_info['sid'];
                $data['GID']['user_sid'] = $applicant_info['sid'];
                $data['GID']['user_type'] = 'applicant';
                $data['GID']['user_info'] = $applicant_info;
                //
                $data['NotCompletedGeneralDocuments'] = $this->hr_documents_management_model->getGeneralDocumentCount(
                    $data['GID']['user_sid'],
                    $data['GID']['user_type'],
                    $data['GID']['company_sid']
                );

                $assigned_sids                          = array();
                $no_action_required_sids                = array();
                $completed_sids                         = array();
                $revoked_sids                           = array();
                $completed_documents                    = array();
                $no_action_required_documents           = array();
                $payroll_documents_sids                 = array();
                $uncompleted_payroll_documents          = array();
                $completed_payroll_documents            = array();
                $uncompleted_offer_letter               = array();
                $completed_offer_letter                 = array();
                $signed_documents                       = array();
                $no_action_required_payroll_documents   = array();

                $sendGroupEmail = 0;

                if (!empty($groups_assign)) {
                    foreach ($groups_assign as $value) {
                        $system_document = $this->hr_documents_management_model->get_document_group($value['group_sid']);

                        if (isset($system_document['w4']) && $system_document['w4'] == 1) {
                            $is_w4_assign = $this->hr_documents_management_model->check_w4_form_exist('applicant', $applicant_sid);
                            if (empty($is_w4_assign)) {
                                $w4_data_to_insert = array();
                                $w4_data_to_insert['employer_sid'] = $applicant_sid;
                                $w4_data_to_insert['company_sid'] = $company_info['sid'];
                                $w4_data_to_insert['user_type'] = 'applicant';
                                $w4_data_to_insert['sent_status'] = 1;
                                $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $w4_data_to_insert['status'] = 1;
                                $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                                //
                                $sendGroupEmail = 1;
                            }
                        }

                        if (isset($system_document['w9']) && $system_document['w9'] == 1) {
                            $is_w9_assign = $this->hr_documents_management_model->check_w9_form_exist('applicant', $applicant_sid);

                            if (empty($is_w9_assign)) {
                                $w9_data_to_insert = array();
                                $w9_data_to_insert['user_sid'] = $applicant_sid;
                                $w9_data_to_insert['company_sid'] = $company_info['sid'];
                                $w9_data_to_insert['user_type'] = 'applicant';
                                $w9_data_to_insert['sent_status'] = 1;
                                $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $w9_data_to_insert['status'] = 1;
                                $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                                //
                                $sendGroupEmail = 1;
                            }
                        }

                        if (isset($system_document['i9']) && $system_document['i9'] == 1) {
                            $is_i9_assign = $this->hr_documents_management_model->check_i9_exist('applicant', $applicant_sid);

                            if (empty($is_i9_assign)) {
                                $i9_data_to_insert = array();
                                $i9_data_to_insert['user_sid'] = $applicant_sid;
                                $i9_data_to_insert['user_type'] = 'applicant';
                                $i9_data_to_insert['company_sid'] = $company_info['sid'];
                                $i9_data_to_insert['sent_status'] = 1;
                                $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['status'] = 1;
                                $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                                //
                                $sendGroupEmail = 1;
                            }
                        }

                        if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_applicant_document_center']) {
                            if (!empty($system_document['eeoc']) && $system_document['eeoc'] == 1) {
                                $is_eeoc_assign = $this->hr_documents_management_model->check_eeoc_exist($applicant_sid, 'applicant');

                                if (empty($is_eeoc_assign)) {
                                    $eeoc_data_to_insert = array();
                                    $eeoc_data_to_insert['application_sid'] = $applicant_sid;
                                    $eeoc_data_to_insert['users_type'] = 'applicant';
                                    $eeoc_data_to_insert['status'] = 1;
                                    $eeoc_data_to_insert['is_expired'] = 0;
                                    $eeoc_data_to_insert['portal_applicant_jobs_list_sid'] = $jobs_listing;
                                    $eeoc_data_to_insert['last_sent_at'] = date('Y-m-d H:i:s', strtotime('now'));
                                    $eeoc_data_to_insert['assigned_at'] = date('Y-m-d H:i:s', strtotime('now'));
                                    $eeoc_data_to_insert['last_assigned_by'] = 0;
                                    //
                                    $this->hr_documents_management_model->insert_eeoc_form_record($eeoc_data_to_insert);
                                    //
                                    $sendGroupEmail = 1;
                                }
                            }
                        }
                    }
                }

                $assign_group_documents = $this->hr_documents_management_model->get_assign_group_documents($company_info['sid'], 'applicant', $applicant_sid);

                if (!empty($assign_group_documents)) {
                    foreach ($assign_group_documents as $key => $assign_group_document) {
                        $is_document_assign = $this->hr_documents_management_model->check_document_already_assigned($company_info['sid'], 'applicant', $applicant_sid, $assign_group_document['document_sid']);

                        if ($is_document_assign == 0 && $assign_group_document['document_sid'] > 0) {
                            $document = $this->hr_documents_management_model->get_hr_document_details($company_info['sid'], $assign_group_document['document_sid']);
                            if (!sizeof($document)) continue;
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_info['sid'];
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $assign_group_document['assigned_by_sid'];
                            $data_to_insert['user_type'] = 'applicant';
                            $data_to_insert['user_sid'] = $applicant_sid;
                            $data_to_insert['document_type'] = $document['document_type'];
                            $data_to_insert['document_sid'] = $assign_group_document['document_sid'];
                            $data_to_insert['status'] = 1;
                            $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_insert['document_title'] = $document['document_title'];
                            $data_to_insert['document_description'] = $document['document_description'];
                            $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                            $data_to_insert['signature_required'] = $document['signature_required'];
                            $data_to_insert['download_required'] = $document['download_required'];
                            $data_to_insert['is_confidential'] = $document['is_confidential'];
                            $data_to_insert['is_required'] = $document['is_required'];
                            $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];
                            $data_to_insert['assign_location'] = "assign_group_from_employee_hr_document";
                            
                            //
                            $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            //
                            if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                                $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $document['document_description']);
                                //
                                if ($isAuthorized == 1) {
                                    // Managers handling
                                    $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                        $document['managers_list'],
                                        $assignment_sid,
                                        $company_info['sid'],
                                        $assign_group_document['assigned_by_sid']
                                    );
                                }
                            }
                            //
                            if ($document['has_approval_flow'] == 1) {
                                $this->HandleApprovalFlow(
                                    $assignment_sid,
                                    $document['document_approval_note'],
                                    $document["document_approval_employees"],
                                    0,
                                    $document['managers_list']
                                );
                            } else {
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }

                $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_info['sid'], 'applicant', $applicant_sid, 0);
                //
                $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
                $documents_management_sids = $payroll_sids['documents_management_sids'];
                $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];
                //
                foreach ($assigned_documents as $key => $assigned_document) {
                    //
                    if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                        $assigned_document['pay_roll_catgory'] = 1;
                    } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                        $assigned_document['pay_roll_catgory'] = 1;
                    } else {
                        $assigned_document['pay_roll_catgory'] = 0;
                    }
                    //
                    if ($assigned_document['document_sid'] == 0) {
                        if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                            if ($assigned_document['pay_roll_catgory'] == 0) {
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
                        //
                        $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                        //
                        if ($assigned_document['user_consent'] == 1) {
                            $assigned_document['archive'] = 0;
                        }
                        //
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
                                $magic_codes = array('{{signature}}', '{{inital}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}');

                                if (str_replace($magic_codes, '', $document_body) != $document_body) {
                                    $is_magic_tag_exist = 1;
                                }
                            }
                            //
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
                        } else if ($assigned_document['archive'] == 1) {
                            unset($assigned_documents[$key]);
                        }
                    }
                }
                //
                $data['history_doc_sids'] = $history_doc_sids;
                //
                //
                foreach ($signed_documents as $cd_key => $signed_document) {
                    $signed_documents[$cd_key]["is_history"] = 0;
                    $signed_documents[$cd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history('applicant', $applicant_sid, $signed_document['sid']);

                    if (($key = array_search($signed_document['sid'], $history_doc_sids)) !== false) {
                        unset($history_doc_sids[$key]);
                    }
                }

                foreach ($completed_payroll_documents as $prd_key => $payroll_document) {
                    $completed_payroll_documents[$prd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history('applicant', $applicant_sid, $payroll_document['sid']);

                    if (($key = array_search($payroll_document['sid'], $history_doc_sids)) !== false) {
                        unset($history_doc_sids[$key]);
                    }
                }

                if (!empty($history_doc_sids)) {
                    foreach ($history_doc_sids as $key => $doc_id) {
                        $his_docs = $this->hr_documents_management_model->check_if_document_has_history('applicant', $applicant_sid, $doc_id);
                        foreach ($his_docs as $key => $his_doc) {
                            $his_doc["is_history"] = 1;
                            $his_doc["history"] = array();
                            array_push($signed_documents, $his_doc);
                        }
                    }
                }
                //
                $documents = $this->hr_documents_management_model->get_assigned_documents($company_info['sid'], 'applicant', $applicant_sid);
                $data['documents'] = $documents;

                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_info['sid'], $signed_documents, $no_action_required_documents, 0);
                $data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
                $data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];


                $data['company_eeoc_form_status'] = $company_eeo_status;
                $data['completed_documents'] = $completed_documents;
                $data['no_action_required_documents'] = $no_action_required_documents;

                $current_assigned_offer_letter = $this->hr_documents_management_model->get_current_assigned_offer_letter($company_info['sid'], 'applicant', $applicant_sid);

                if (!empty($current_assigned_offer_letter)) {
                    if ($current_assigned_offer_letter[0]['user_consent'] == 1) {
                        $completed_offer_letter = $current_assigned_offer_letter[0];
                    } else {
                        $uncompleted_offer_letter = $current_assigned_offer_letter[0];
                    }
                }

                $data['w4_form'] = $this->onboarding_model->get_w4_form('applicant', $applicant_sid);
                $data['i9_form'] = $this->onboarding_model->get_i9_form('applicant', $applicant_sid);
                $data['w9_form'] = $this->onboarding_model->get_w9_form('applicant', $applicant_sid);
                //
                $completed_w4 = array();
                //
                if (!empty($data['w4_form']) && $data['w4_form']['user_consent'] == 1) {
                    $data['w4_form']["form_status"] = "Current";
                    array_push($completed_w4, $data['w4_form']);
                }
                //
                $w4_history = $this->hr_documents_management_model->is_W4_history_exist($data['w4_form']['sid'], 'applicant', $applicant_sid);
                //
                if (!empty($w4_history)) {
                    foreach ($w4_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_w4, $history);
                    }
                }
                //
                $completed_w9 = array();
                //
                if (!empty($data['w9_form']) && $data['w9_form']['user_consent'] == 1) {
                    $data['w9_form']["form_status"] = "Current";
                    array_push($completed_w9, $data['w9_form']);
                }
                //
                $w9_history = $this->hr_documents_management_model->is_W9_history_exist($data['w9_form']['sid'], 'applicant', $applicant_sid);
                //
                if (!empty($w9_history)) {
                    foreach ($w9_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_w9, $history);
                    }
                }
                //
                $completed_i9 = array();
                //
                if (!empty($data['i9_form']) && $data['i9_form']['user_consent'] == 1) {
                    $data['i9_form']["form_status"] = "Current";
                    array_push($completed_i9, $data['i9_form']);
                }
                //
                $i9_history = $this->hr_documents_management_model->is_I9_history_exist($data['i9_form']['sid'], 'applicant', $applicant_sid);
                //
                if (!empty($i9_history)) {
                    foreach ($i9_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_i9, $history);
                    }
                }
                //
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $data['completed_i9']                           = $completed_i9;
                $data['completed_w9']                           = $completed_w9;
                $data['completed_w4']                           = $completed_w4;
                $data['assigned_documents']                     = $assigned_documents;
                $data['completed_offer_letter']                 = $completed_offer_letter;
                $data['uncompleted_offer_letter']               = $uncompleted_offer_letter;
                $data['uncompleted_payroll_documents']          = $uncompleted_payroll_documents;
                $data['completed_payroll_documents']            = $completed_payroll_documents;
                $data['payroll_documents_sids']                 = $payroll_documents_sids;
                $data['assigned_offer_letter']                  = $current_assigned_offer_letter;
                $data['assigned_sids']                          = $assigned_sids;
                $data['revoked_sids']                           = $revoked_sids;
                $data['user_type']                              = 'employee';
                $data['user_sid']                               = $applicant_sid;
                $data['company_name'] = $onboarding_details['company_info']['CompanyName'];
                $data['company_eeoc_form_status']               = $company_eeo_status;
                $data['no_action_required_payroll_documents']   = $no_action_required_payroll_documents;

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/documents_new');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function sign_hr_document($doc = NULL, $unique_sid = NULL, $document_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $data['doc'] = $doc;

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $data['title'] = 'Automoto HR Onboarding';
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid, $doc);
                $save_offer_letter_type = '';
                //
                if ($document['document_type'] == 'uploaded') {
                    if (strpos($document['document_s3_name'], '&') !== false) {
                        $document['document_s3_name'] = modify_AWS_file_name($document['sid'], $document['document_s3_name'], "document_s3_name");
                    }

                    if (strpos($document['uploaded_file'], '&') !== false) {
                        $document['uploaded_file'] = modify_AWS_file_name($document['sid'], $document['uploaded_file'], "uploaded_file");
                    }
                }
                //
                if (!empty($document['document_description'])) {
                    $document_body = $document['document_description'];
                    $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}', 'select');
                    $magic_signature_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_signature_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'consent_only';
                    } else if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'save_only';
                    }
                }

                $data['save_offer_letter_type'] = $save_offer_letter_type;

                if (!empty($document)) {
                    if (!empty($document['form_input_data'])) {
                        $form_input_data = unserialize($document['form_input_data']);
                        $data['form_input_data'] = json_encode(json_decode($form_input_data, true));
                    }
                    //
                    if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) {

                        if (!empty($document['authorized_signature'])) {
                            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        }

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        if (!empty($document['authorized_editable_date'])) {
                            $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
                            $document['document_description'] = str_replace('{{authorized_editable_date}}', $authorized_editable_date, $document['document_description']);
                        }

                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 1) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        if (!empty($document['authorized_editable_date'])) {
                            $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
                            $document['document_description'] = str_replace('{{authorized_editable_date}}', $authorized_editable_date, $document['document_description']);
                        }
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 0) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        if (!empty($document['authorized_editable_date'])) {
                            $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
                            $document['document_description'] = str_replace('{{authorized_editable_date}}', $authorized_editable_date, $document['document_description']);
                        }
                    }
                    //echo '<pre>';print_r($document); die();

                    $document_content = replace_tags_for_document($company_info['sid'], $applicant_sid, 'applicant', $document['document_description'], $document['document_sid']);
                    $document['document_description'] = $document_content;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                }

                if ($document['document_type'] == 'offer_letter') {
                    $data['attached_video'] = array();
                } else {
                    $attached_video = $this->hr_documents_management_model->get_document_attached_video($document['document_sid']);
                    $data['attached_video'] = $attached_video;
                }

                $data['document'] = $document;

                $document_type = $document['document_type'];
                $e_signature_data = get_e_signature($company_info['sid'], $applicant_sid, 'applicant');
                $signed_flag = false;
                $data['document_type'] = $document_type;

                if ($document['user_consent'] == 1) {
                    $signed_flag = true;
                }

                $data['signed_flag'] = $signed_flag;
                $data['save_post_url'] = current_url();

                if (!empty($document['signature']) && $document['signature'] != NULL) {
                    $e_signature_data = array();
                    $e_signature_data['company_sid'] = $document['company_sid'];
                    $e_signature_data['user_type'] = $document['user_type'];
                    $e_signature_data['user_sid'] = $document['user_sid'];
                    $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                    $e_signature_data['signature'] = $document['signature'];
                    $signed_flag = true;
                }

                $e_signature_data['user_consent'] = $document['user_consent'];
                $data['e_signature_data'] = $e_signature_data;
                $data['signed_flag'] = $signed_flag;
                $data['first_name'] = $applicant_info['first_name'];
                $data['last_name'] = $applicant_info['last_name'];
                $data['email'] = $applicant_info['email'];
                $data['company_sid'] = $applicant_info["employer_sid"];
                $data['users_type'] = 'applicant';
                $data['users_sid'] = $applicant_sid;
                $data['back_url'] = base_url('onboarding/hr_documents/' . $unique_sid);
                $data['download_url'] = base_url('onboarding/download_hr_document/' . $unique_sid . '/' . $document['sid']);
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                if ($document['acknowledged'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Document Status:</strong> You have successfully Acknowledged this document';
                    $acknowledgement_button_txt = 'Acknowledged';
                    $acknowledgement_button_css = 'btn-warning';
                    $acknowledgement_button_action = 'javascript:;';
                } else {
                    $acknowledgement_status = '<strong class="text-danger">Document Status:</strong> You have not yet acknowledged this document';
                    $acknowledgement_button_txt = 'I Acknowledge';
                    $acknowledgement_button_css = 'blue-button';
                    $acknowledgement_button_action = 'func_acknowledge_document();';
                }

                $acknowledgment_action_title = 'Document Action: <b>Acknowledgement Required!</b>';
                $acknowledgment_action_desc = '<b>Acknowledge the receipt of this document</b>';

                if ($document_type == 'uploaded' || $document['offer_letter_type'] == 'uploaded') {
                    $download_action_title = 'Document Action: <b>Save / Download</b>';
                    $download_action_desc = '<b>Please download this document to Sign / Fill. </b>';
                    $download_button_action = base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']);

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successfully downloaded this document';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                        $print_button_action = '';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> You have not yet download this document';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                        $print_button_action = '';
                    }

                    $document_filename = $document['document_s3_name'];
                    $name = explode(".", $document_filename);
                    $url_segment_submitted = $name[0];
                    $extension = $name[1];

                    if (strtolower($extension) == 'pdf') {
                        $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.pdf';
                    } else if (strtolower($extension) == 'doc') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edoc&wdAccPdf=0';
                    } else if (strtolower($extension) == 'docx') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edocx&wdAccPdf=0';
                    } else if (strtolower($extension) == 'xls') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Exls';
                    } else if (strtolower($extension) == 'xlsx') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Exlsx';
                    } else if (strtolower($extension) == 'csv') {
                        $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.csv';
                    } else {
                        $print_button_action = base_url('onboarding/print_applicant_upload_img/' . $document['document_s3_name']);
                    }
                } else { // generated document
                    $download_button_action = base_url('onboarding/print_applicant_generated_doc/original/' . $unique_sid . '/' . $document['sid'] . '/' . 'download');
                    $download_action_title = 'Document Action: <b>Download / Print</b>';
                    $download_action_desc = '<b>You can Download / Print this document for your reference</b>';

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successfully printed this document';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> You have not yet printed this document';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                    }

                    if (!empty($document['uploaded_file'])) {
                        $document_filename = $document['uploaded_file'];
                        $name = explode(".", $document_filename);
                        $url_segment_submitted = $name[0];
                        $extension = $name[1];

                        if (strtolower($extension) == 'pdf') {
                            $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.pdf';
                        } else if (strtolower($extension) == 'doc') {
                            $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edoc&wdAccPdf=0';
                        } else if (strtolower($extension) == 'docx') {
                            $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edocx&wdAccPdf=0';
                        } else {
                            $print_button_action = base_url('onboarding/print_applicant_upload_img/' . $document['uploaded_file']);
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1) {
                        if ($document['acknowledged'] == 1 && $document['downloaded'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 0) {
                        if ($document['acknowledged'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if ($document['acknowledgment_required'] == 0 && $document['download_required'] == 1) {
                        if ($document['downloaded'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if (empty($document['submitted_description'])) {
                        $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                        $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                    } else {
                        $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                        $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                    }
                }


                if ($document['uploaded'] == 1) {
                    $uploaded_status = '<strong class="text-success">Document Status:</strong> You have successfully uploaded signed copy. In case you uploaded wrong document you can replace with the correct version by re uploading it.';
                    $uploaded_button_txt = 'Re-Upload Document';
                    $uploaded_button_css = 'btn-warning';
                } else {
                    $uploaded_status = '<strong class="text-danger">Document Status:</strong> Upload the Signed Document, You have not yet uploaded this document';
                    $uploaded_button_txt = 'Upload Document';
                    $uploaded_button_css = 'blue-button';
                }

                $uploaded_action_title = 'Document Action: <b>Upload Signed Copy!</b>';
                $uploaded_action_desc = '<b>Please sign this document and upload the signed copy.</b>';

                $data['download_action_title'] = $download_action_title;
                $data['download_action_desc'] = $download_action_desc;
                $data['download_button_txt'] = $download_button_txt;
                $data['download_button_action'] = $download_button_action;
                $data['download_status'] = $download_status;
                $data['download_button_css'] = $download_button_css;
                $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                $data['acknowledgement_status'] = $acknowledgement_status;
                $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                $data['acknowledgement_button_action'] = $acknowledgement_button_action;
                $data['uploaded_action_title'] = $uploaded_action_title;
                $data['uploaded_action_desc'] = $uploaded_action_desc;
                $data['uploaded_button_txt'] = $uploaded_button_txt;
                $data['uploaded_status'] = $uploaded_status;
                $data['uploaded_button_css'] = $uploaded_button_css;
                $data['print_button_action'] = $print_button_action;
                $data['company_name'] = $onboarding_details['company_info']['CompanyName'];
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/sign_hr_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');
            $applicant_sid = $onboarding_details['applicant_sid'];
            $company_info = $onboarding_details['company_info'];

            $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid, $doc);

            $is_authorized_document = 'no';

            if (!empty($document['document_description'])) {
                $magic_authorized_codes = array('{{authorized_signature}}', '{{authorized_signature_date}}', '{{authorized_signature_date}}');
                $document_body = $document['document_description'];

                if (str_replace($magic_authorized_codes, '', $document_body) != $document_body) {
                    $is_authorized_document = 'yes';
                }
            }

            switch ($perform_action) {
                case 'acknowledge_document':
                    $unique_sid = $this->input->post('unique_sid');
                    $user_type = 'applicant';
                    $user_sid = $this->input->post('user_sid');
                    // $document_sid = $this->input->post('document_sid');

                    if ($doc == 'o') {
                        $document_info = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid, $doc);

                        if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1 && $document_info['download_required'] == 1)) {
                            if ($document_info['downloaded'] == 1) {
                                $data_to_update = array();
                                $data_to_update['acknowledged'] = 1;
                                $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                if ($document_info['signature_required'] == 0 && $document_info['user_consent'] == 0) {
                                    $data_to_update['user_consent'] = 1;
                                    $data_to_update['form_input_data'] = 's:2:"{}";';
                                    $data_to_update['signature_timestamp'] = date('Y-m-d');

                                    if ($is_authorized_document == 'yes') {
                                        $this->sendEmailToManager($document_sid, $user_sid, $company_info);
                                    }
                                }

                                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                            } else {
                                $data_to_update = array();
                                $data_to_update['acknowledged'] = 1;
                                $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');
                                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                            }
                        } else if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1)) {
                            $data_to_update = array();
                            $data_to_update['acknowledged'] = 1;
                            $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                            if ($document_info['signature_required'] == 0 && $document_info['user_consent'] == 0) {
                                $data_to_update['user_consent'] = 1;
                                $data_to_update['form_input_data'] = 's:2:"{}";';
                                $data_to_update['signature_timestamp'] = date('Y-m-d');

                                if ($is_authorized_document == 'yes') {
                                    $this->sendEmailToManager($document_sid, $applicant_sid, $company_info);
                                }
                            }

                            $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                        }
                    } else {
                        $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_sid);
                    }

                    $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                    redirect('onboarding/sign_hr_document/' . $doc . '/' . $unique_sid . '/' . $document_sid, 'refresh');
                    break;
                case 'upload_document':
                    $unique_sid = $this->input->post('unique_sid');
                    $user_type = 'applicant';
                    $user_sid = $this->input->post('user_sid');
                    $document_type = $this->input->post('document_type');
                    // $document_sid = $this->input->post('document_sid');
                    $company_sid = $this->input->post('company_sid');
                    $aws_file_name = upload_file_to_aws('upload_file', $company_sid, $doc . '_' . $document_sid, time());
                    $uploaded_file = '';

                    if ($aws_file_name != 'error') {
                        $uploaded_file = $aws_file_name;
                    }

                    if (!empty($uploaded_file)) {
                        if ($doc == 'o') {
                            $data_to_update = array();
                            $data_to_update['uploaded'] = 1;
                            $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                            $data_to_update['signature_timestamp'] = date('Y-m-d');
                            $data_to_update['user_consent'] = 1;
                            $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);

                            if ($is_authorized_document == 'yes') {
                                $this->sendEmailToManager($document_sid, $applicant_sid, $company_info);
                            }
                        } else {
                            $this->hr_documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                        }

                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                    }

                    redirect('onboarding/sign_hr_document/' . $doc . '/' . $unique_sid . '/' . $document_sid, 'refresh');
                    break;
                case 'sign_document':

                    $save_input_values = array();
                    $users_type = 'applicant';
                    $users_sid = $onboarding_details['applicant_sid'];
                    $save_signature = $this->input->post('save_signature');
                    $save_initial = $this->input->post('save_signature_initial');
                    $save_date = $this->input->post('save_signature_date');
                    $user_consent = $this->input->post('user_consent');
                    $base64_pdf = $this->input->post('save_PDF');

                    if (isset($_POST['save_input_values']) && !empty($_POST['save_input_values'])) {
                        $save_input_values = $_POST['save_input_values'];
                    }
                    $save_input_values = serialize($save_input_values);

                    $data_to_update = array();

                    if ($save_signature == 'yes' || $save_initial == 'yes') {
                        $company_sid = $onboarding_details['company_sid'];
                        $signature = get_e_signature($company_sid, $onboarding_details['applicant_sid'], $users_type);

                        if ($save_signature == 'yes') {
                            $data_to_update['signature_base64'] = $signature['signature_bas64_image'];
                        }

                        if ($save_initial == 'yes') {
                            $data_to_update['signature_initial'] = $signature['init_signature_bas64_image'];
                        }
                    }

                    $data_to_update['signature_email'] = $onboarding_details['applicant_info']['email'];
                    $data_to_update['signature_ip'] = getUserIP();
                    $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $data_to_update['submitted_description'] = $base64_pdf;
                    $data_to_update['uploaded'] = 1;
                    $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                    $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                    $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                    $data_to_update['form_input_data'] = $save_input_values;
                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $users_sid, $users_type, $data_to_update);

                    if ($is_authorized_document == 'yes') {
                        $this->sendEmailToManager($document_sid, $applicant_sid, $company_info);
                    }

                    $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');
                    redirect('onboarding/sign_hr_document/' . $doc . '/' . $unique_sid . '/' . $document_sid, 'refresh');
                    break;
            }
        }
    }

    function sendEmailToManager($document_sid, $applicant_sid, $company_info)
    {
        $applicant_name = get_applicant_name($applicant_sid);
        $company_name = $company_info['CompanyName'];
        $company_sid = $company_info['sid'];
        //
        $assign_managers = $this->hr_documents_management_model->getAllAuthorizedAssignManagers($company_sid, $document_sid);
        //
        if (!empty($assign_managers)) {
            //
            $email_template_id = $this->hr_documents_management_model->getAuthorizedManagerTemplate('Authorized Manager Notification');
            //
            $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('view_assigned_authorized_document/' . $document_sid) . '">Assign Authorized Document</a>';
            //
            foreach ($assign_managers as $manager) {
                $replacement_array['first_name'] = $manager['first_name'];
                $replacement_array['last_name'] = $manager['last_name'];
                $replacement_array['employee_name'] = $applicant_name;
                $replacement_array['link'] = $link_html;
                $to_email = $manager['email'];

                $message_header_footer = message_header_footer($company_sid, ucwords($company_name));
                //
                $user_extra_info = array();
                $user_extra_info['user_sid'] = $applicant_sid;
                $user_extra_info['user_type'] = "applicant";
                //
                log_and_send_templated_email($email_template_id, $to_email, $replacement_array, $message_header_footer, 1, $user_extra_info);
            }
        }
    }

    public function calendar($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data['title'] = 'HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_events = $this->application_tracking_system_model->getApplicantEvents($company_info['sid'], $applicant_info['sid'], 'applicant'); //Getting Events
                $data['applicant_events'] = $applicant_events;
                $data['applicant'] = $applicant_info;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $extra_info = $company_info['extra_info'];
                $company_eeo_status = 1;

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/calendar');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function getting_started($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
            // echo '<pre>'; print_r($onboarding_details); echo '</pre>';
            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $extra_info = $company_info['extra_info'];
                $company_sid = $company_info['sid'];
                $data['session']['company_detail'] = $company_info;
                $company_eeo_status = 1;

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $configuration = $this->onboarding_model->get_onboarding_configuration('applicant', $applicant_sid);

                $welcome_video = $this->onboarding_model->get_onboarding_configuration_welcome_video($company_sid, $applicant_sid, 'applicant');
                $locations_data = $this->get_single_record_from_array($configuration, 'section', 'locations');
                $custom_office_locations = $this->onboarding_model->get_custom_office_records($company_sid, $applicant_sid, 'applicant', 'location', 1);
                $data['custom_office_locations'] = $custom_office_locations;
                //
                $custom_useful_link = $this->onboarding_model->get_custom_office_records($company_sid, $applicant_sid, 'applicant', 'useful_link');
                $data['custom_useful_link'] = $custom_useful_link;
                //
                $timings_data = $this->get_single_record_from_array($configuration, 'section', 'timings');
                $people_data = $this->get_single_record_from_array($configuration, 'section', 'people');
                $credentials_data = $this->get_single_record_from_array($configuration, 'section', 'credentials');

                if (!empty($credentials_data)) {
                    $credentials_joining_date = unserialize($credentials_data['items']);
                    $user_joining_date = $credentials_joining_date['joining_date'];
                } else {
                    $user_joining_date = '';
                }

                $items_data = $this->onboarding_model->get_assigned_custom_office_record_sids($company_sid, $applicant_info['sid'], 'applicant', 'item', 2); // fetch items from new table
                //echo '<pre>'; print_r($locations_data); echo '</pre>'; exit;
                $locations = empty($locations_data) ? array() : $locations_data['items_details'];
                $timings = empty($timings_data) ? array() : $timings_data['items_details'];
                $people = empty($people_data) ? array() : $people_data['items_details'];
                // $items = empty($items_data) ? array() : $items_data['items_details'];
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                }
                // get custom timings
                $data['custom_office_timings'] = $this->onboarding_model->get_assigned_custom_office_record_sids($company_sid, $applicant_info['sid'], 'applicant', 'timing', 2);
                //
                $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions_new('applicant', $applicant_sid);
                // $assigned_sessions                                          = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                $learning_center_status = count($assigned_sessions);

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                }

                // _e($assigned_sessions, true);
                // _e($learning_center_status, true, true);

                $onboarding_instructions_data = $this->onboarding_model->get_company_instructions($company_info['sid'], 0, $applicant_sid);

                if (empty($onboarding_instructions_data)) {
                    $onboarding_instructions = "<b>Welcome to {{company_name}}</b><p>We are excited to have you join our team.! You are just few steps away from becoming a welcome and valued member of our amazing team.</p><p>Click through and complete each step of the on-boarding steps above to set up your account and complete the HR compliance and On-boarding process.</p><p>These items are time sensitive, so please complete them as soon as possible.</p><p>Please Note: If you are unsure or unable to complete any of the on-boarding steps, please leave them blank and continue with rest of the steps.</p><p>Contact our team with any questions you may have and again we are looking forward to you joining the team.</p>";
                } else {
                    $onboarding_instructions = $onboarding_instructions_data[0]['instructions'];
                }

                $onboarding_instructions = str_replace('{{company_name}}', ucwords($data['session']['company_detail']['CompanyName']), $onboarding_instructions);
                $data['onboarding_instructions'] = $onboarding_instructions;

                $onboarding_disclosure_data = $this->onboarding_model->get_company_disclosure($company_info['sid'], 0, $applicant_sid);

                if (empty($onboarding_disclosure_data)) {
                    $onboarding_disclosure = "<b>Company Disclosure</b>";
                } else {
                    $onboarding_disclosure = $onboarding_disclosure_data[0]['disclosure'];
                }

                $data['onboarding_disclosure'] = $onboarding_disclosure;

                $assign_links = $this->onboarding_model->onboarding_assign_useful_links($applicant_sid, $company_sid);
                $data['locations'] = array_merge($locations, $custom_office_locations);
                $data['timings'] = $timings;
                $data['people'] = $people;
                $data['items'] = $items_data;
                $data['links'] = $assign_links;
                $data['welcome_video'] = $welcome_video;
                $data['joining_date'] = $user_joining_date;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $data['onboarding_progress'] = $onboarding_progress;

                //
                $data['companyDefaultAddress'] = $this->onboarding_model->getPrimaryAddress($company_sid);

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/getting_started_applicant');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                // $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function my_profile($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        // $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
        $applicant_sid = $onboarding_details['applicant_sid'];
        $applicant_information = $this->onboarding_model->get_applicant_information($applicant_sid);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $data['applicant_information'] = $applicant_information;
                $data['applicant_sid'] = $applicant_sid;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $company_ssn_dob = $this->onboarding_model->check_company_ssn_dob($company_info['sid']);

                $data['ssn_required'] = $company_ssn_dob['ssn_required'];
                $data['dob_required'] = $company_ssn_dob['dob_required'];
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                //
                $data['_ssv'] = $this->_ssv;
                $portalData = getPortalData(
                    $company_info['sid'],
                    ["uniform_sizes"]
                );
                if ($portalData["uniform_sizes"]) {
                    $this->form_validation->set_rules('uniform_top_size', 'Uniform top size', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('uniform_bottom_size', 'Uniform bottom size', 'required|trim|xss_clean');
                }

                $data['portalData'] = $portalData;

                // $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/on_boarding_my_profile');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $company_sid = $this->input->post('company_sid');
            $applicant_sid = $this->input->post('applicant_sid');
            $file_name = upload_file_to_aws('pictures', $company_sid, 'profile_picture', $applicant_sid);
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            // $email = $this->input->post('email');
            $address = $this->input->post('address');
            $phone_number = $this->input->post('phone_number');
            $city = $this->input->post('city');
            $zipcode = $this->input->post('zipcode');
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $secondary_email = $this->input->post('secondary_email');
            $secondary_PhoneNumber = $this->input->post('secondary_PhoneNumber');
            $other_email = $this->input->post('other_email');
            $other_PhoneNumber = $this->input->post('other_PhoneNumber');
            // $referred_by_name = $this->input->post('referred_by_name');
            // $referred_by_email = $this->input->post('referred_by_email');
            $linkedin_profile_url = $this->input->post('linkedin_profile_url');
            $employee_number = $this->input->post('employee_number');
            $SSN = $this->input->post('ssn');
            $date_of_birth = $this->input->post('dob');
            $gender = $this->input->post('gender');
            $marital_status = $this->input->post('marital_status');

            if (!empty($date_of_birth)  && !preg_match(XSYM_PREG, $date_of_birth)) {
                $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
            } else {
                $DOB = '';
            }

            $YouTube_Video = $this->input->post('YouTube_Video');
            //  $title = $this->input->post('title');
            //  $division = $this->input->post('division');
            //  $department = $this->input->post('department');
            //  $office_location = $this->input->post('office_location');
            $interests = $this->input->post('interests');
            $short_bio = $this->input->post('short_bio');
            $url_prams = array();
            $video_source = $this->input->post('video_source');
            $video_id = '';

            if ($video_source != 'no_video') {
                if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["upload_video"]["name"]);
                    $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $upload_video_file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('onboarding/my_profile' . '/' . $unique_sid, 'refresh');
                    }

                    $video_id = $upload_video_file_name;
                } else {
                    $video_id = $this->input->post('yt_vm_video_url');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else if ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                        $video_id = $this->input->post('pre_upload_video_url');
                    }
                }
            }

            $primary_info = array();
            $primary_info['first_name'] = $first_name;
            $primary_info['last_name'] = $last_name;
            // $primary_info['email'] = $email;
            $primary_info['address'] = $address;
            $primary_info['phone_number'] = $phone_number;
            $primary_info['zipcode'] = $zipcode;
            $primary_info['country'] = $country;
            $primary_info['state'] = $state;
            $primary_info['city'] = $city;
            // $primary_info['referred_by_name'] = $referred_by_name;
            // $primary_info['referred_by_email'] = $referred_by_email;
            $primary_info['linkedin_profile_url'] = $linkedin_profile_url;
            $primary_info['gender'] = $gender;
            $primary_info['marital_status'] = $marital_status;
            //
            if (!preg_match(XSYM_PREG, $SSN)) $primary_info['ssn'] = $SSN;
            if (!preg_match(XSYM_PREG, $date_of_birth)) $primary_info['dob'] = $DOB;
            $primary_info['video_type'] = $video_source;
            $primary_info['YouTube_Video'] = $video_id;

            if ($file_name != 'error') {
                $primary_info['pictures'] = $file_name;
            }

            $secondary_info = array();
            $secondary_info['secondary_email'] = $secondary_email;
            $secondary_info['secondary_PhoneNumber'] = $secondary_PhoneNumber;
            $secondary_info['other_email'] = $other_email;
            $secondary_info['other_PhoneNumber'] = $other_PhoneNumber;
            // $secondary_info['title'] = $title;
            // $secondary_info['division'] = $division;
            // $secondary_info['department'] = $department;
            // $secondary_info['office_location'] = $office_location;
            $secondary_info['interests'] = $interests;
            $secondary_info['short_bio'] = $short_bio;
            $primary_info['extra_info'] = serialize($secondary_info);

            //
            $primary_info['uniform_top_size'] = $this->input->post('uniform_top_size');
            $primary_info['uniform_bottom_size'] = $this->input->post('uniform_bottom_size');


            //Ful Employment Application Form Update data
            $full_emp_app = isset($applicant_information['full_employment_application']) && !empty($applicant_information['full_employment_application']) ? unserialize($applicant_information['full_employment_application']) : array();
            $full_emp_app['PhoneNumber'] = $this->input->post('PhoneNumber');
            $full_emp_app['TextBoxTelephoneOther'] = $this->input->post('other_PhoneNumber');
            $full_emp_app['TextBoxAddressStreetFormer3'] = $this->input->post('other_email');
            $primary_info['full_employment_application'] = serialize($full_emp_app);
            //
            $primary_info['languages_speak'] = null;
            //
            $languages_speak = $this->input->post('secondaryLanguages');
            //
            if ($languages_speak) {
                $primary_info['languages_speak'] = implode(',', $languages_speak);
            }
            //
            $this->onboarding_model->update_applicant_information($company_sid, $applicant_sid, $primary_info);
            $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'general_information');
            //
            if ($gender != "other") {
                $dataToUpdate = array();
                $dataToUpdate['gender'] = ucfirst($gender);
                $this->onboarding_model->update_eeoc($applicant_sid, 'applicant', $dataToUpdate);
            }
            //
            $this->session->set_flashdata('message', '<strong>Success:</strong> Profile Information Successfully Updated!');
            //Redirect to next step
            redirect('onboarding/general_information/' . $unique_sid, 'refresh');
        }
    }

    public function colleague_profile($unique_sid, $employee_sid)
    {
        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Colleague Profile';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $employer = $this->onboarding_model->get_employee_info($company_info['sid'], $employee_sid);
                $data['employer'] = $employer;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/colleague_profile');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function direct_deposit($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $bank_details = $this->onboarding_model->get_bank_details('applicant', $applicant_sid);
                $data['bank_details'] = $bank_details;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['company_sid'] = $applicant_info['employer_sid'];
                $data['applicant_sid'] = $applicant_info['sid'];
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                // $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/bank_details');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $company_sid = $this->input->post('company_sid');
            $users_sid = $this->input->post('users_sid');
            $account_title = $this->input->post('account_title');
            $routing_transaction_number = $this->input->post('routing_transaction_number');
            $account_number = $this->input->post('account_number');
            $financial_institution_name = $this->input->post('financial_institution_name');
            $account_type = $this->input->post('account_type');
            $enable_learbing_center_flag = $this->input->post('enable_learbing_center_flag');
            $data_to_save = array();
            $data_to_save['users_type'] = 'applicant';
            $data_to_save['users_sid'] = $users_sid;
            $data_to_save['account_title'] = $account_title;
            $data_to_save['routing_transaction_number'] = $routing_transaction_number;
            $data_to_save['account_number'] = $account_number;
            $data_to_save['financial_institution_name'] = $financial_institution_name;
            $data_to_save['account_type'] = $account_type;
            $data_to_save['company_sid'] = $company_sid;
            $pictures = upload_file_to_aws('picture', $onboarding_details['company_info']['sid'], 'picture', $onboarding_details['applicant_sid'], AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $data_to_save['voided_cheque'] = $pictures;
            }
            $this->onboarding_model->save_bank_details('applicant', $users_sid, $data_to_save);
            $this->onboarding_model->increment_section_save_count($users_sid, 'applicant', 'bank_details');
            $this->session->set_flashdata('message', '<strong>Success:</strong> Direct Deposit Information Successfully Updated!');
            if ($enable_learbing_center_flag) {
                redirect('onboarding/learning_center/' . $unique_sid, 'refresh');
            } else {
                redirect('onboarding/my_credentials/' . $unique_sid, 'refresh');
            }
        }
    }

    public function emergency_contacts($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                $applicant_information = $this->onboarding_model->get_applicant_information($applicant_sid);
                $data['applicant_information'] = $applicant_information;
                $emergency_contacts = $this->onboarding_model->get_applicant_emergency_contacts('applicant', $applicant_sid);
                $data['emergency_contacts'] = $emergency_contacts;
                $data['company_sid'] = $applicant_information['employer_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['onboarding_flag'] = true;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }
                // $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/emergency_contacts');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'add_emergency_contact':
                    $company_sid = $this->input->post('company_sid');
                    $applicant_sid = $this->input->post('users_sid');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $email = $this->input->post('email');
                    $PhoneNumber = $this->input->post('PhoneNumber');
                    $country = $this->input->post('country');
                    $state = $this->input->post('state');
                    $city = $this->input->post('city');
                    $Location_ZipCode = $this->input->post('Location_ZipCode');
                    $Location_Address = $this->input->post('Location_Address');
                    $Relationship = $this->input->post('Relationship');
                    $priority = $this->input->post('priority');
                    $data_to_insert = array();
                    $data_to_insert['users_sid'] = $applicant_sid;
                    $data_to_insert['first_name'] = $first_name;
                    $data_to_insert['last_name'] = $last_name;
                    $data_to_insert['email'] = $email;
                    $data_to_insert['PhoneNumber'] = $PhoneNumber;
                    $data_to_insert['Location_Country'] = $country;
                    $data_to_insert['Location_State'] = $state;
                    $data_to_insert['Location_City'] = $city;
                    $data_to_insert['Location_ZipCode'] = $Location_ZipCode;
                    $data_to_insert['Location_Address'] = $Location_Address;
                    $data_to_insert['Relationship'] = $Relationship;
                    $data_to_insert['priority'] = $priority;
                    $data_to_insert['users_type'] = 'applicant';
                    $this->onboarding_model->insert_applicant_emergency_contact($data_to_insert);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'emergency_contacts');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Added!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'delete_emergency_contact':
                    $applicant_sid = $this->input->post('users_sid');
                    $contact_sid = $this->input->post('contact_sid');
                    $this->onboarding_model->delete_emergency_contact('applicant', $applicant_sid, $contact_sid);
                    $this->onboarding_model->decrement_section_save_count($applicant_sid, 'emergency_contacts');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Deleted!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
            }
        }
    }

    public function edit_emergency_contacts($unique_sid, $contact_sid)
    {
        $data['title'] = 'HR Onboarding';
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $data['onboarding_details'] = $onboarding_details;
            $applicant_info = $onboarding_details['applicant_info'];
            $data['applicant'] = $applicant_info;
            $company_info = $onboarding_details['company_info'];
            $data['session']['company_detail'] = $company_info;
            $data['company_info'] = $company_info;
            $data['unique_sid'] = $unique_sid;
            $applicant_sid = $onboarding_details['applicant_sid'];
            $data['applicant_sid'] = $applicant_sid;
            $data['sid'] = $contact_sid;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['states'] = $data_states_encode;
            $applicant_information = $this->onboarding_model->get_applicant_information($applicant_sid);
            $data['applicant_information'] = $applicant_information;
            $emergency_contacts = $this->onboarding_model->emergency_contacts_details($contact_sid);
            $data['emergency_contacts'] = $emergency_contacts[0];
            $data['company_sid'] = $applicant_information['employer_sid'];
            $data['applicant_sid'] = $applicant_sid;
            $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
            $data['onboarding_progress'] = $onboarding_progress;
            $data['onboarding_flag'] = true;
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|valid_email');
            // $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('Relationship', 'Relationship', 'trim|xss_clean|required');
            $this->form_validation->set_rules('priority', 'Priority', 'trim|xss_clean|required');
            $this->form_validation->set_message('is_unique', '%s is already registered!');
            $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
            $learning_center_status = count($videos);
            $data['enable_learbing_center'] = false;

            if ($learning_center_status > 0) {
                $data['enable_learbing_center'] = true;
            } else {
                $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                $learning_center_status = count($assigned_sessions);

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                }
            }

            if ($this->form_validation->run() === FALSE) {
                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }
                $company_id = $company_info['sid'];

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_id);

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/edit_onboarding_emergency_contacts');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $update_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'Location_Country' => $this->input->post('Location_Country'),
                    'Location_State' => $this->input->post('Location_State'),
                    'Location_City' => $this->input->post('Location_City'),
                    'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                    'Location_Address' => $this->input->post('Location_Address'),
                    'PhoneNumber' => $this->input->post('PhoneNumber'),
                    'Relationship' => $this->input->post('Relationship'),
                    'priority' => $this->input->post('priority')
                );

                $sid = $this->input->post('sid');
                $result = $this->onboarding_model->edit_emergency_contacts($update_data, $sid);
                redirect(base_url('onboarding/general_information/' . $unique_sid), 'location');
            }
        } else { //Onboarding Complete or Expired
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function license_info($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $occupational_license = $this->onboarding_model->get_license_details('applicant', $applicant_sid, 'occupational');

                if (!empty($occupational_license)) {
                    $occupational_license['license_details'] = unserialize($occupational_license['license_details']);
                }

                $data['occupational_license_details'] = $occupational_license;
                $drivers_license = $this->onboarding_model->get_license_details('applicant', $applicant_sid, 'drivers');

                if (!empty($drivers_license)) {
                    $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                }

                $data['drivers_license_details'] = $drivers_license;
                $license_types = array();
                $license_types['Sales License'] = 'Sales License';
                $license_types['Commercial Driverâ€™s License'] = 'Commercial Driverâ€™s License';
                $license_types['Non-commercial Driverâ€™s License'] = 'Non-commercial Driverâ€™s License';
                $license_types['Restricted Driverâ€™s License'] = 'Restricted Driverâ€™s License';
                $license_types['Basic Driverâ€™s License'] = 'Basic Driverâ€™s License';
                $license_types['Identification Card'] = 'Identification Card';
                $license_types['College Diploma'] = 'College Diploma';
                $license_types['Training'] = 'Training';
                $license_types['Other'] = 'Other';
                $data['license_types'] = $license_types;
                $license_classes = array();
                $license_classes['Class A'] = 'Class A';
                $license_classes['Class B'] = 'Class B';
                $license_classes['Class C'] = 'Class C';
                $data['license_classes'] = $license_classes;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                // $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/license_info');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'update_occupational_license_information':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $license_type = $this->input->post('license_type');
                    $license_authority = $this->input->post('license_authority');
                    $license_class = $this->input->post('license_class');
                    $license_number = $this->input->post('license_number');
                    $license_issue_date = $this->input->post('license_issue_date');
                    $license_expiration_date = $this->input->post('license_expiration_date');
                    $license_indefinite = $this->input->post('license_indefinite');
                    $license_notes = $this->input->post('license_notes');
                    $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $applicant_sid);
                    $data_to_serialize = array();
                    $data_to_serialize['license_type'] = $license_type;
                    $data_to_serialize['license_authority'] = $license_authority;
                    $data_to_serialize['license_class'] = $license_class;
                    $data_to_serialize['license_number'] = $license_number;
                    $data_to_serialize['license_issue_date'] = $license_issue_date;
                    $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                    $data_to_serialize['license_indefinite'] = $license_indefinite;
                    $data_to_serialize['license_notes'] = $license_notes;
                    $data_to_save = array();
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['license_type'] = 'occupational';
                    $data_to_save['license_details'] = serialize($data_to_serialize);

                    if ($license_file != 'error' && !empty($license_file)) {
                        $data_to_save['license_file'] = $license_file;
                    }

                    $this->onboarding_model->save_license_information('applicant', $applicant_sid, 'occupational', $data_to_save);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'occupational_license');
                    $this->session->set_flashdata('message', '<strong>Success</strong> License Information Updated!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'update_drivers_license_information':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $license_type = $this->input->post('license_type');
                    $license_authority = $this->input->post('license_authority');
                    $license_class = $this->input->post('license_class');
                    $license_number = $this->input->post('license_number');
                    $license_issue_date = $this->input->post('license_issue_date');
                    $license_expiration_date = $this->input->post('license_expiration_date');
                    $license_indefinite = $this->input->post('license_indefinite');
                    $license_notes = $this->input->post('license_notes');
                    $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $applicant_sid);
                    $data_to_serialize = array();
                    $data_to_serialize['license_type'] = $license_type;
                    $data_to_serialize['license_authority'] = $license_authority;
                    $data_to_serialize['license_class'] = $license_class;
                    $data_to_serialize['license_number'] = $license_number;
                    $data_to_serialize['license_issue_date'] = $license_issue_date;
                    $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                    $data_to_serialize['license_indefinite'] = $license_indefinite;
                    $data_to_serialize['license_notes'] = $license_notes;
                    $data_to_save = array();
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['license_type'] = 'drivers';
                    $data_to_save['license_details'] = serialize($data_to_serialize);

                    if ($license_file != 'error' && !empty($license_file)) {
                        $data_to_save['license_file'] = $license_file;
                    }

                    $this->onboarding_model->save_license_information('applicant', $applicant_sid, 'drivers', $data_to_save);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'drivers_license');
                    $this->session->set_flashdata('message', '<strong>Success</strong> License Information Updated!');
                    redirect('onboarding/license_info/' . $unique_sid, 'refresh');
                    break;
            }
        }
    }

    public function general_information($unique_sid, $key = null)
    {
        $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $occupational_license = $this->onboarding_model->get_license_details('applicant', $applicant_sid, 'occupational');

                if (!empty($occupational_license)) {
                    $occupational_license['license_details'] = unserialize($occupational_license['license_details']);
                }

                $data['occupational_license_details'] = $occupational_license;
                $drivers_license = $this->onboarding_model->get_license_details('applicant', $applicant_sid, 'drivers');

                if (!empty($drivers_license)) {
                    $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                }

                $data['drivers_license_details'] = $drivers_license;
                $dependents = $this->onboarding_model->get_dependant_information('applicant', $applicant_sid);
                $data['dependents_arr'] = $dependents;
                $emergency_contacts = $this->onboarding_model->get_applicant_emergency_contacts('applicant', $applicant_sid);
                $data['emergency_contacts'] = $emergency_contacts;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $bank_details = $this->onboarding_model->get_bank_details('applicant', $applicant_sid);
                $data['bank_details'] = $bank_details;
                $equipments = $this->onboarding_model->get_equipment_info('applicant', $applicant_sid);
                $data['equipments'] = $equipments;
                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                $license_types = array();
                $license_types['Sales License'] = 'Sales License';
                $license_types['Commercial Driverâ€™s License'] = 'Commercial Driverâ€™s License';
                $license_types['Non-commercial Driverâ€™s License'] = 'Non-commercial Driverâ€™s License';
                $license_types['Restricted Driverâ€™s License'] = 'Restricted Driverâ€™s License';
                $license_types['Basic Driverâ€™s License'] = 'Basic Driverâ€™s License';
                $license_types['Identification Card'] = 'Identification Card';
                $license_types['College Diploma'] = 'College Diploma';
                $license_types['Training'] = 'Training';
                $license_types['Other'] = 'Other';
                $data['license_types'] = $license_types;
                $license_classes = array();
                $license_classes['None'] = 'None';
                $license_classes['Class A'] = 'Class A';
                $license_classes['Class B'] = 'Class B';
                $license_classes['Class C'] = 'Class C';
                $license_classes['Other'] = 'Other';
                $data['license_classes'] = $license_classes;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $data['_ssv'] = $this->_ssv;

                //
                $this->load->model('direct_deposit_model');

                $company_sid = $company_info['sid'];
                $company_name = $company_info['CompanyName'];
                $employee_number = $this->direct_deposit_model->get_user_extra_info('applicant', $applicant_sid, $company_sid);
                $data['data'] = $this->direct_deposit_model->getDDI('applicant', $applicant_sid, $company_sid);
                $data['dd_user_type'] = 'applicant';
                $data['dd_user_sid'] = $applicant_sid;
                $data['company_id'] = $company_sid;
                $data['company_name'] = $company_name;
                $data['employee_number'] = $employee_number;
                $users_sign_info = get_e_signature($company_sid, $applicant_sid, 'applicant');
                // echo '<pre>';
                // echo $employee_number;
                // print_r($users_sign_info);
                // die('stop');
                $data['users_sign_info'] = $users_sign_info;
                $data['cn'] = $this->direct_deposit_model->getUserData($applicant_sid, 'applicant');
                $data['send_email_notification'] = 'no';
                //
                $data['generalAssignments'] = $this->direct_deposit_model->getGeneralAssignments($company_sid, $applicant_sid, 'applicant');
                $data['keyIndex'] = $key;

                //
                $data['dependents_yes_text'] = $this->lang->line('dependents_yes_text');
                $data['dependents_no_text'] = $this->lang->line('dependents_no_text');

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_sid);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/onboarding_new_general_info');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'update_occupational_license_information':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $license_type = $this->input->post('license_type');
                    $license_authority = $this->input->post('license_authority');
                    $license_class = $this->input->post('license_class');
                    $license_number = $this->input->post('license_number');
                    $license_issue_date = $this->input->post('license_issue_date');
                    $license_expiration_date = $this->input->post('license_expiration_date');
                    $license_indefinite = $this->input->post('license_indefinite');
                    $license_notes = $this->input->post('license_notes');
                    $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $applicant_sid);
                    $data_to_serialize = array();
                    $data_to_serialize['license_type'] = $license_type;
                    $data_to_serialize['license_authority'] = $license_authority;
                    $data_to_serialize['license_class'] = $license_class;
                    $data_to_serialize['license_number'] = $license_number;
                    $data_to_serialize['license_issue_date'] = $license_issue_date;
                    $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                    $data_to_serialize['license_indefinite'] = $license_indefinite;
                    $data_to_serialize['license_notes'] = $license_notes;
                    $data_to_save = array();
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['license_type'] = 'occupational';
                    $data_to_save['license_details'] = serialize($data_to_serialize);
                    $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;

                    if ($license_file != 'error' && !empty($license_file)) {
                        $data_to_save['license_file'] = $license_file;
                    }

                    $this->onboarding_model->save_license_information('applicant', $applicant_sid, 'occupational', $data_to_save, $dateOfBirth);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'occupational_license');
                    $this->session->set_flashdata('message', '<strong>Success</strong> License Information Updated!');
                    //
                    checkAndUpdateDD($applicant_sid, 'applicant', $onboarding_details['company_info']['sid'], 'occupational_license');

                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'update_drivers_license_information':
                    $applicant_sid = $this->input->post('applicant_sid');
                    //
                    $drivers_license = $this->onboarding_model->get_license_details('applicant', $applicant_sid, 'drivers');
                    //
                    if (!empty($drivers_license)) {
                        $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                    }
                    //
                    $license_type = $this->input->post('license_type');
                    $license_authority = $this->input->post('license_authority');
                    $license_class = $this->input->post('license_class');
                    $license_number = $this->input->post('license_number');
                    $license_issue_date = $this->input->post('license_issue_date');
                    $license_expiration_date = $this->input->post('license_expiration_date');
                    $license_indefinite = $this->input->post('license_indefinite');
                    $license_notes = $this->input->post('license_notes');
                    $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $applicant_sid);
                    $data_to_serialize = array();
                    $data_to_serialize['license_type'] = $license_type;
                    $data_to_serialize['license_authority'] = $license_authority;
                    $data_to_serialize['license_class'] = $license_class;
                    //
                    if (!preg_match(XSYM_PREG, $license_number)) $data_to_serialize['license_number'] = $license_number;
                    else $data_to_serialize['license_number'] = isset($drivers_license['license_details']['license_number']) ? $drivers_license['license_details']['license_number'] : '';
                    if (!preg_match(XSYM_PREG, $license_issue_date)) $data_to_serialize['license_issue_date'] = $license_issue_date;
                    else $data_to_serialize['license_issue_date'] = isset($drivers_license['license_details']['license_issue_date']) ? $drivers_license['license_details']['license_issue_date'] : '';
                    if (!preg_match(XSYM_PREG, $license_expiration_date)) $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                    else $data_to_serialize['license_expiration_date'] = isset($drivers_license['license_details']['license_expiration_date']) ? $drivers_license['license_details']['license_expiration_date'] : '';
                    //
                    $data_to_serialize['license_indefinite'] = $license_indefinite;
                    $data_to_serialize['license_notes'] = $license_notes;
                    $data_to_save = array();
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['license_type'] = 'drivers';
                    $data_to_save['license_details'] = serialize($data_to_serialize);
                    //
                    if (!preg_match(XSYM_PREG, $this->input->post('dob')))
                        $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;
                    else $dateOfBirth['dob'] = $onboarding_details['applicant_info']['dob'];

                    if ($license_file != 'error' && !empty($license_file)) {
                        $data_to_save['license_file'] = $license_file;
                    }

                    $this->onboarding_model->save_license_information('applicant', $applicant_sid, 'drivers', $data_to_save, $dateOfBirth);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'drivers_license');
                    $user_full_emp_app = $onboarding_details['applicant_info']['full_employment_application'];
                    $full_emp_form = array();
                    if (sizeof($user_full_emp_app)) {
                        $full_emp_form = !empty($user_full_emp_app) && $user_full_emp_app != NULL ? unserialize($user_full_emp_app) : array();
                    }
                    $full_emp_form['TextBoxDriversLicenseNumber'] = $license_number;
                    $full_emp_form['TextBoxDriversLicenseExpiration'] = $license_expiration_date;

                    $serial_form = array();
                    $serial_form['full_employment_application'] = serialize($full_emp_form);
                    $this->onboarding_model->update_applicant_information($onboarding_details['applicant_info']['employer_sid'], $applicant_sid, $serial_form);
                    $this->session->set_flashdata('message', '<strong>Success</strong> License Information Updated!');
                    //

                    checkAndUpdateDD($applicant_sid, 'applicant', $onboarding_details['company_info']['sid'], 'drivers_license');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'update_bank_details':
                    $company_sid = $this->input->post('company_sid');
                    $users_sid = $this->input->post('users_sid');
                    $account_title = $this->input->post('account_title');
                    $routing_transaction_number = $this->input->post('routing_transaction_number');
                    $account_number = $this->input->post('account_number');
                    $financial_institution_name = $this->input->post('financial_institution_name');
                    $account_type = $this->input->post('account_type');
                    $enable_learbing_center_flag = $this->input->post('enable_learbing_center_flag');
                    $data_to_save = array();
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['users_sid'] = $users_sid;
                    $data_to_save['account_title'] = $account_title;
                    $data_to_save['routing_transaction_number'] = $routing_transaction_number;
                    $data_to_save['account_number'] = $account_number;
                    $data_to_save['financial_institution_name'] = $financial_institution_name;
                    $data_to_save['account_type'] = $account_type;
                    $data_to_save['company_sid'] = $company_sid;
                    $pictures = upload_file_to_aws('picture', $company_sid, 'picture', $users_sid, AWS_S3_BUCKET_NAME);

                    if (!empty($pictures) && $pictures != 'error') {
                        $data_to_save['voided_cheque'] = $pictures;
                    }

                    $this->onboarding_model->save_bank_details('applicant', $users_sid, $data_to_save);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Direct Deposit Information Updated!');
                    //
                    checkAndUpdateDD($applicant_sid, 'applicant', $company_sid, 'direct_deposit');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'add_dependent':
                    $company_sid = $this->input->post('company_sid');
                    $applicant_sid = $this->input->post('users_sid');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $address = $this->input->post('address');
                    $address_line = $this->input->post('address_line');
                    $Location_Country = $this->input->post('Location_Country');
                    $Location_State = $this->input->post('Location_State');
                    $city = $this->input->post('city');
                    $postal_code = $this->input->post('postal_code');
                    $phone = $this->input->post('phone');
                    $birth_date = $this->input->post('birth_date');
                    $relationship = $this->input->post('relationship');
                    $ssn = $this->input->post('ssn');
                    $gender = $this->input->post('gender');
                    $family_member = $this->input->post('family_member');
                    $data_to_serialize = array();
                    $data_to_serialize['first_name'] = $first_name;
                    $data_to_serialize['last_name'] = $last_name;
                    $data_to_serialize['address'] = $address;
                    $data_to_serialize['address_line'] = $address_line;
                    $data_to_serialize['Location_Country'] = $Location_Country;
                    $data_to_serialize['Location_State'] = $Location_State;
                    $data_to_serialize['city'] = $city;
                    $data_to_serialize['postal_code'] = $postal_code;
                    $data_to_serialize['phone'] = $phone;
                    $data_to_serialize['birth_date'] = $birth_date;
                    $data_to_serialize['relationship'] = $relationship;
                    $data_to_serialize['ssn'] = $ssn;
                    $data_to_serialize['gender'] = $gender;
                    $data_to_serialize['family_member'] = $family_member;
                    $data_to_save = array();
                    $data_to_save['company_sid'] = $company_sid;
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['dependant_details'] = serialize($data_to_serialize);

                    if (isDontHaveDependens($company_sid, $applicant_sid, 'applicant') > 0) {
                        isDontHaveDependensDelete($company_sid, $applicant_sid, 'applicant');
                    }

                    $this->onboarding_model->insert_dependent_information($data_to_save);

                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'dependents');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Added!');
                    //
                    checkAndUpdateDD($applicant_sid, 'applicant', $company_sid, 'dependents');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'add_dependent_dont_have':
                    $company_sid = $this->input->post('company_sid');
                    $applicant_sid = $this->input->post('users_sid');
                    $data_to_serialize = array();
                    $data_to_save = array();
                    $data_to_save['company_sid'] = $company_sid;
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['have_dependents'] = '0';

                    $data_to_save['dependant_details'] = serialize($data_to_serialize);

                    haveDependensDelete($company_sid, $applicant_sid, 'applicant');

                    if (isDontHaveDependens($company_sid, $applicant_sid, 'applicant') > 0) {
                        $this->session->set_flashdata('message', '<strong>Success</strong> Saved!');
                        redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                        break;
                    }


                    $this->onboarding_model->insert_dependent_information($data_to_save);

                    $this->session->set_flashdata('message', '<strong>Success</strong> Saved!');
                    //
                    checkAndUpdateDD($applicant_sid, 'applicant', $company_sid, 'dependents');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;

                case 'delete_dependent':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $dependent_sid = $this->input->post('dependent_sid');
                    $this->onboarding_model->delete_dependent_information('applicant', $applicant_sid, $dependent_sid);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Deleted!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'add_emergency_contact':
                    $company_sid = $this->input->post('company_sid');
                    $applicant_sid = $this->input->post('users_sid');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $email = $this->input->post('email');
                    $PhoneNumber = $this->input->post('PhoneNumber');
                    $country = $this->input->post('contact_country');
                    $state = $this->input->post('contact_state');
                    $city = $this->input->post('city');
                    $Location_ZipCode = $this->input->post('Location_ZipCode');
                    $Location_Address = $this->input->post('Location_Address');
                    $Relationship = $this->input->post('Relationship');
                    $priority = $this->input->post('priority');
                    $data_to_insert = array();
                    $data_to_insert['users_sid'] = $applicant_sid;
                    $data_to_insert['first_name'] = $first_name;
                    $data_to_insert['last_name'] = $last_name;
                    $data_to_insert['email'] = $email;
                    $data_to_insert['PhoneNumber'] = $PhoneNumber;
                    $data_to_insert['Location_Country'] = $country;
                    $data_to_insert['Location_State'] = $state;
                    $data_to_insert['Location_City'] = $city;
                    $data_to_insert['Location_ZipCode'] = $Location_ZipCode;
                    $data_to_insert['Location_Address'] = $Location_Address;
                    $data_to_insert['Relationship'] = $Relationship;
                    $data_to_insert['priority'] = $priority;
                    $data_to_insert['users_type'] = 'applicant';
                    $this->onboarding_model->insert_applicant_emergency_contact($data_to_insert);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'emergency_contacts');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Added!');
                    //
                    checkAndUpdateDD($applicant_sid, 'applicant', $company_sid, 'emergency_contacts');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
                case 'delete_emergency_contact':
                    $applicant_sid = $this->input->post('users_sid');
                    $contact_sid = $this->input->post('contact_sid');
                    $this->onboarding_model->delete_emergency_contact('applicant', $applicant_sid, $contact_sid);
                    $this->onboarding_model->decrement_section_save_count($applicant_sid, 'emergency_contacts');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Deleted!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
            }
        }
    }

    public function dependents($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                $dependents = $this->onboarding_model->get_dependant_information('applicant', $applicant_sid);
                $data['dependents'] = $dependents;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['onboarding_flag'] = true;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/dependents');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'add_dependent':
                    $company_sid = $this->input->post('company_sid');
                    $applicant_sid = $this->input->post('users_sid');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $address = $this->input->post('address');
                    $address_line = $this->input->post('address_line');
                    $Location_Country = $this->input->post('Location_Country');
                    $Location_State = $this->input->post('Location_State');
                    $city = $this->input->post('city');
                    $postal_code = $this->input->post('postal_code');
                    $phone = $this->input->post('phone');
                    $birth_date = $this->input->post('birth_date');
                    $relationship = $this->input->post('relationship');
                    $ssn = $this->input->post('ssn');
                    $gender = $this->input->post('gender');
                    $family_member = $this->input->post('family_member');
                    $data_to_serialize = array();
                    $data_to_serialize['first_name'] = $first_name;
                    $data_to_serialize['last_name'] = $last_name;
                    $data_to_serialize['address'] = $address;
                    $data_to_serialize['address_line'] = $address_line;
                    $data_to_serialize['Location_Country'] = $Location_Country;
                    $data_to_serialize['Location_State'] = $Location_State;
                    $data_to_serialize['city'] = $city;
                    $data_to_serialize['postal_code'] = $postal_code;
                    $data_to_serialize['phone'] = $phone;
                    $data_to_serialize['birth_date'] = $birth_date;
                    $data_to_serialize['relationship'] = $relationship;
                    $data_to_serialize['ssn'] = $ssn;
                    $data_to_serialize['gender'] = $gender;
                    $data_to_serialize['family_member'] = $family_member;
                    $data_to_save = array();
                    $data_to_save['company_sid'] = $company_sid;
                    $data_to_save['users_sid'] = $applicant_sid;
                    $data_to_save['users_type'] = 'applicant';
                    $data_to_save['dependant_details'] = serialize($data_to_serialize);
                    $this->onboarding_model->insert_dependent_information($data_to_save);
                    $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'dependents');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Added!');
                    redirect('onboarding/dependents/' . $unique_sid, 'refresh');
                    break;
                case 'delete_dependent':
                    $applicant_sid = $this->input->post('applicant_sid');
                    $dependent_sid = $this->input->post('dependent_sid');
                    $this->onboarding_model->delete_dependent_information('applicant', $applicant_sid, $dependent_sid);
                    $this->onboarding_model->decrement_section_save_count($applicant_sid, 'dependents');
                    $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Deleted!');
                    redirect('onboarding/general_information/' . $unique_sid, 'refresh');
                    break;
            }
        }
    }

    public function edit_dependant_information($unique_sid, $dependant_sid)
    {
        $data['title'] = 'HR Onboarding';
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $data['onboarding_details'] = $onboarding_details;
            $applicant_info = $onboarding_details['applicant_info'];
            $data['applicant'] = $applicant_info;
            $company_info = $onboarding_details['company_info'];
            $data['session']['company_detail'] = $company_info;
            $data['company_info'] = $company_info;
            $data['unique_sid'] = $unique_sid;
            $applicant_sid = $onboarding_details['applicant_sid'];
            $data['applicant_sid'] = $applicant_sid;
            $data['sid'] = $dependant_sid;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $dependantData = $this->onboarding_model->dependant_details($dependant_sid);

            if (!empty($dependantData)) {
                $dependant_details = $dependantData[0];
            } else { // emergency contact does not exists.
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('settings'), "refresh");
                }
            }

            $dependents = $this->onboarding_model->get_dependant_information('applicant', $applicant_sid);
            $data['dependents'] = $dependents;
            $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
            $data['onboarding_progress'] = $onboarding_progress;
            $data['onboarding_flag'] = true;
            $dependant_data = unserialize($dependant_details['dependant_details']);
            $dependant_data['sid'] = $dependant_details['sid'];
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['dependant_info'] = $dependant_data;
            $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
            $learning_center_status = count($videos);
            $data['enable_learbing_center'] = false;

            if ($learning_center_status > 0) {
                $data['enable_learbing_center'] = true;
            } else {
                $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                $learning_center_status = count($assigned_sessions);

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                }
            }

            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('relationship', 'Relationship', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {
                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status;
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/edit_onboarding_dependents');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $formpost = $this->input->post(null, true);
                $dependantDataToSave['dependant_details'] = serialize($formpost);
                $this->onboarding_model->update_dependant_info($dependant_sid, $dependantDataToSave);
                $this->session->set_flashdata('message', '<b>Success:</b> Dependent info updated successfully');
                redirect(base_url('onboarding/general_information/' . $unique_sid), "location");
            }
        } else { //Onboarding Complete or Expired
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function full_employment_application($unique_sid)
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxNameMiddle', 'Middle Name', 'trim|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('suffix', 'Suffix', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxSSN', 'TextBoxSSN', 'required|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxDOB', 'Date of Birth', 'required|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressEmailConfirm', 'Confirm Email Address', 'valid_email|required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|trim|xss_clean|is_unique[portal_job_applications.email]');
        $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressLenghtCurrent', 'How Long', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxAddressInternationalCurrent', 'Non USA Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressStreetFormer1', 'Former Residence', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressLenghtFormer1', 'How Long?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressCityFormer1', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListAddressStateFormer1', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressZIPFormer1', 'Zip Code', 'trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxAddressInternationalFormer1', 'Non USA Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressStreetFormer2', 'Former Residence', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressLenghtFormer2', 'How Long?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressCityFormer2', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListAddressStateFormer2', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressZIPFormer2', 'Zip Code', 'trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxAddressInternationalFormer2', 'Non USA Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressStreetFormer3', 'Other Mailing Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressCityFormer3', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListAddressStateFormer3', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAddressZIPFormer3', 'Zip Code', 'trim|xss_clean');
        $this->form_validation->set_rules('PhoneNumber', 'Primary Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxTelephoneMobile', 'Mobile Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxTelephoneOther', 'Other Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListPostionTime', 'Job position', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxPositionDesired', 'more position', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxWorkBeginDate', 'Begin Date', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxWorkCompensation', 'Expected Compensation', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListWorkTransportation', 'Have Transportation', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years or older?', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListAliases', 'Any other names', 'trim|xss_clean');
        $this->form_validation->set_rules('nickname_or_othername_details', 'other name explaination', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListDriversLicenseQuestion', 'Drivers License Question', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'Drivers License Number', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListDriversState', 'Drivers State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'Expiration date', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'Drivers License Plead Guilty', 'trim|xss_clean');
        $this->form_validation->set_rules('license_guilty_details', 'license guilty details', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationHighSchoolName', 'Education High School Name', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEducationHighSchoolGraduated', 'High School Graduated', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationHighSchoolCity', 'Education City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationHighSchoolState', 'Education State', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearBegin', 'Year', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearEnd', 'Year', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationCollegeName', 'College / University', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEducationCollegeGraduated', 'Did you graduate?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationCollegeCity', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationCollegeState', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthBegin', 'Month begin', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearBegin', 'Year', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationCollegeMajor', 'Major', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationCollegeDegree', 'Degree Earned', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationOtherName', 'Other School', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEducationOtherGraduated', 'Did you graduate?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationOtherCity', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationOtherState', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearBegin', 'Strat Year', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationOtherMajor', 'Other Major', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationOtherDegree', 'Other Degree', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseName', 'Professional License Type', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseIssuingAgencyState', 'Issuing Agency/State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseNumber', 'License Number', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position/Title', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Dates of Employment', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Year Begin', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Year End', 'trim|xss_clean');
        // $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin1', 'Starting Compensation', 'trim|xss_clean');
        // $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd1', 'Ending Compensation', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'May we contact this employer?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Employer Reason Leave', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerName2', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition2', 'Position/Title', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress2', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerCity2', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerState2', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber2', 'Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2', 'Dates of Employment', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2', 'Year Begin', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2', 'Year End', 'trim|xss_clean');
        //$this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin2', 'Starting Compensation', 'trim|xss_clean');
        //$this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd2', 'Ending Compensation', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor2', 'Supervisor', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact2_0', 'May we contact this employer?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave2', 'Employer Reason Leave', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerName3', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition3', 'Position/Title', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress3', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerCity3', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerState3', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber3', 'Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3', 'Dates of Employment', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3', 'Year Begin', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3', 'Month End', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3', 'Year End', 'trim|xss_clean');
        // $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin3', 'Starting Compensation', 'trim|xss_clean');
        // $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd3', 'Ending Compensation', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor3', 'Supervisor', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact3_0', 'May we contact this employer?', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave3', 'Employer Reason Leave', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEmploymentEverTerminated', 'Employment Ever Terminated', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEverTerminatedReason', 'Ever Terminated Reason', 'trim|xss_clean');
        $this->form_validation->set_rules('RadioButtonListEmploymentEverResign', 'Employment Ever Resign', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEverResignReason', 'Employment Resign Reason', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentGaps', 'Employer Gaps', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxEmploymentEmployerNoContact', 'Employer No Contact', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceName1', ' Reference Name', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAcquainted1', 'Reference Acquainted', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAddress1', 'Reference Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceCity1', 'Reference City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListReferenceState1', 'Reference State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber1', 'Telephone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceEmail1', 'Reference Email', 'valid_email|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceName2', 'Reference Name', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAcquainted2', 'Reference Acquainted', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAddress2', 'Reference Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceCity2', 'Reference City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListReferenceState2', 'Reference State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber2', 'Telephone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceEmail2', 'Reference Email', 'valid_email|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceName3', 'Reference Name', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAcquainted3', 'Reference Acquainted', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceAddress3', 'Reference Address', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceCity3', 'Reference City', 'trim|xss_clean');
        $this->form_validation->set_rules('DropDownListReferenceState3', 'Reference State', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber3', 'Telephone Number', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxReferenceEmail3', 'Reference Email', 'valid_email|trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAdditionalInfoWorkExperience', 'Additional Work Experience Information', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAdditionalInfoWorkTraining', 'Additional Work Training Information', 'trim|xss_clean');
        $this->form_validation->set_rules('TextBoxAdditionalInfoWorkConsideration', 'Additional Work Consideration Information', 'trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxAgreement1786', 'CheckBoxAgreement1786', 'required|trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxAgree', 'Acknowledge Agree', 'required|trim|xss_clean');
        $this->form_validation->set_rules('CheckBoxTerms', 'Terms of Acceptance', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $applicant_info = $this->onboarding_model->get_applicant_information($applicant_sid);
                $full_employment_application = array();
                $data['applicant'] = $applicant_info;

                if (isset($applicant_info['full_employment_application'])) {
                    $full_employment_application = unserialize($applicant_info['full_employment_application']);
                }

                $field_names = array();
                $field_names[] = 'first_name';
                $field_names[] = 'last_name';
                $field_names[] = 'email';

                foreach ($field_names as $field_name) {
                    if (isset($applicant_info[$field_name])) {
                        $full_employment_application[$field_name] = $applicant_info[$field_name];
                    }
                }

                if (isset($applicant_info['phone_number'])) {
                    $full_employment_application['PhoneNumber'] = $applicant_info['phone_number'];
                }

                if (isset($applicant_info['address'])) {
                    $full_employment_application['Location_Address'] = $applicant_info['address'];
                }

                if (isset($applicant_info['city'])) {
                    $full_employment_application['Location_City'] = $applicant_info['city'];
                }

                if (isset($applicant_info['state'])) {
                    $full_employment_application['Location_State'] = $applicant_info['state'];
                }

                if (isset($applicant_info['country'])) {
                    $full_employment_application['Location_Country'] = $applicant_info['country'];
                }

                if (isset($applicant_info['zipcode'])) {
                    $full_employment_application['Location_ZipCode'] = $applicant_info['zipcode'];
                }

                $data['application'] = $full_employment_application;
                $data['states'] = db_get_active_states(227);
                $data['starting_year_loop'] = 1930;
                $suffix_values = array();
                $suffix_values[] = array('key' => 'Junior', 'value' => 'JR');
                $suffix_values[] = array('key' => 'Senior', 'value' => 'SR');
                $suffix_values[] = array('key' => 'II', 'value' => '2');
                $suffix_values[] = array('key' => 'III', 'value' => '3');
                $suffix_values[] = array('key' => 'IV', 'value' => '4');
                $suffix_values[] = array('key' => 'V', 'value' => 'V');
                $data['suffix_values'] = $suffix_values;
                $months = array();
                $months[] = 'January';
                $months[] = 'February';
                $months[] = 'March';
                $months[] = 'April';
                $months[] = 'May';
                $months[] = 'June';
                $months[] = 'July';
                $months[] = 'August';
                $months[] = 'September';
                $months[] = 'October';
                $months[] = 'November';
                $months[] = 'December';
                $data['months'] = $months;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/full_employment_application');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $applicant_sid = $this->input->post('applicant_sid');
            $unique_sid = $this->input->post('unique_sid');
            $field_names = array();
            $field_names[] = 'first_name';
            $field_names[] = 'last_name';
            $field_names[] = 'email';
            $field_names[] = 'Location_Address';
            $field_names[] = 'Location_City';
            $field_names[] = 'Location_State';
            $field_names[] = 'Location_ZipCode';
            $field_names[] = 'PhoneNumber';
            $field_names[] = 'unique_sid';
            $formpost = $this->input->post(NULL, TRUE);
            $full_employment_application = array();

            foreach ($formpost as $key => $value) {
                if (!in_array($key, $field_names)) {
                    $full_employment_application[$key] = $value;
                }
            }

            $full_employment_application['client_ip'] = getUserIP();
            $full_employment_application['client_signature_timestamp'] = date('Y-m-d H:i:s');
            $this->onboarding_model->update_full_employement_application($applicant_sid, $full_employment_application);
            $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'full_employment_application');
            $this->session->set_flashdata('message', '<strong>Success</strong> Application Updated!');
            redirect('onboarding/full_employment_application/' . $unique_sid, 'refresh');
        }
    }

    function eeoc_form($unique_sid)
    {
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $data['title'] = 'Automoto HR Onboarding';
            $data['onboarding_details'] = $onboarding_details;
            $applicant_info = $onboarding_details['applicant_info'];
            $data['applicant'] = $applicant_info;
            $company_info = $onboarding_details['company_info'];
            $data['session']['company_detail'] = $company_info;
            $data['company_info'] = $company_info;
            $data['unique_sid'] = $unique_sid;
            $applicant_sid = $onboarding_details['applicant_sid'];
            $data['applicant_sid'] = $applicant_sid;
            $eeoc = $this->onboarding_model->get_eeoc('applicant', $applicant_sid);
            //$eeoc_status = $this->eeo_model->get_eeo_form_status('applicant', $applicant_sid);
            $eeoc_status = 'Yes';
            //
            if (!empty($eeoc)) {
                $eeoc['eeoc_form_status'] = $eeoc_status;
            } else {
                $gender = get_user_gender($applicant_info['sid'], 'applicant');
                $eeoc['gender'] = $gender;
                $eeoc['eeoc_form_status'] = $eeoc_status;
            }
            //
            $data['eeoc'] = $eeoc;
            $data['eeoc_status'] = $eeoc_status;
            $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
            $company_eeo_status = 1;
            $extra_info = $company_info['extra_info'];

            if (!is_null($extra_info)) {
                $extra_info = unserialize($extra_info);
                $company_eeo_status = $extra_info['EEO'];
            }

            $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

            if ($company_eeo_status == 0) {
                $this->session->set_flashdata('message', '<strong>Error</strong> This section is disabled by your employer!');
                redirect('onboarding/getting_started/' . $unique_sid, 'refresh');
            }

            $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
            $learning_center_status = count($videos);
            $enable_learbing_center = false;

            if ($learning_center_status > 0) {
                $enable_learbing_center = true;
            } else {
                $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                $learning_center_status = count($assigned_sessions);

                if ($learning_center_status > 0) {
                    $enable_learbing_center = true;
                }
            }

            $data['enable_learbing_center'] = $enable_learbing_center;
            $data['dl_citizen'] = getEEOCCitizenShipFlag($data['company_info']['sid']);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/eeoc_form');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $users_type = $this->input->post('users_type');
                $users_sid = $this->input->post('users_sid');
                $eeoc_form_status = $this->input->post('eeoc_form_status');
                $us_citizen = $this->input->post('us_citizen');
                $visa_status = $this->input->post('visa_status');
                $group_status = $this->input->post('group_status');
                $veteran = $this->input->post('veteran');
                $disability = $this->input->post('disability');
                $gender = $this->input->post('gender');
                $data_to_save = array();
                $data_to_save['users_type'] = 'applicant';
                $data_to_save['application_sid'] = $users_sid;
                $data_to_save['us_citizen'] = $us_citizen;
                $data_to_save['visa_status'] = $visa_status;
                $data_to_save['group_status'] = $group_status;
                $data_to_save['veteran'] = $veteran;
                $data_to_save['disability'] = $disability;
                $data_to_save['gender'] = $gender;
                $data_to_save['is_expired'] = 1;
                $data_to_save['last_completed_on'] = date('Y-m-d H:i:s', strtotime('now'));
                $this->onboarding_model->save_eeoc('applicant', $users_sid, $data_to_save);
                $data_to_update = array();
                $data_to_update['eeo_form'] = $eeoc_form_status;
                $this->eeo_model->update_eeo_form_status($users_type, $users_sid, $eeoc_form_status);
                //
                $dataToUpdate = array();
                $dataToUpdate['gender'] = strtolower($gender);
                update_user_gender($applicant_info['sid'], 'applicant', $dataToUpdate);
                //
                $this->session->set_flashdata('message', '<strong>Success</strong> EEOC Updated!');

                if ($enable_learbing_center) {
                    redirect('onboarding/learning_center/' . $unique_sid, 'refresh');
                } else {
                    redirect('onboarding/my_credentials/' . $unique_sid, 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function required_equipment($unique_sid)
    {
        redirect('onboarding/getting_started/' . $unique_sid, 'refresh');
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $equipments = $this->onboarding_model->get_required_equipment('applicant', $applicant_sid);
                $data['equipments'] = $equipments;
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/required_equipment');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $applicant_sid = $this->input->post('applicant_sid');
            $backpack = $this->input->post('backpack');
            $messenger_bag = $this->input->post('messenger_bag');
            $macbook = $this->input->post('macbook');
            $iphone = $this->input->post('iphone');
            $ipad = $this->input->post('ipad');
            $backpack = empty($backpack) ? 0 : $backpack;
            $messenger_bag = empty($messenger_bag) ? 0 : $messenger_bag;
            $macbook = empty($macbook) ? 0 : $macbook;
            $iphone = empty($iphone) ? 0 : $iphone;
            $ipad = empty($ipad) ? 0 : $ipad;
            $data_to_save = array();
            $data_to_save['users_sid'] = $applicant_sid;
            $data_to_save['users_type'] = 'applicant';
            $data_to_save['backpack'] = $backpack;
            $data_to_save['messenger_bag'] = $messenger_bag;
            $data_to_save['macbook'] = $macbook;
            $data_to_save['iphone'] = $iphone;
            $data_to_save['ipad'] = $ipad;
            $this->onboarding_model->save_required_equipment('applicant', $applicant_sid, $data_to_save);
            $this->onboarding_model->increment_section_save_count($applicant_sid, 'applicant', 'required_equipments');
            $this->session->set_flashdata('message', '<strong>Success</strong> Required Equipments Updated!');
        }
    }

    public function configuration()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $onboarding_instructions_data = $this->onboarding_model->get_company_instructions($company_sid);
            $onboarding_instructions_sid = 0;
            $data['onboarding_instructions_data'] = $onboarding_instructions_data;

            if (empty($onboarding_instructions_data)) {
                $onboarding_instructions = "<b>Welcome to {{company_name}}</b><p>We are excited to have you join our team.! You are just few steps away from becoming a welcome and valued member of our amazing team.</p><p>Click through and complete each step of the on-boarding steps above to set up your account and complete the HR compliance and On-boarding process.</p><p>These items are time sensitive, so please complete them as soon as possible.</p><p>Please Note: If you are unsure or unable to complete any of the on-boarding steps, please leave them blank and continue with rest of the steps.</p><p>Contact our team with any questions you may have and again we are looking forward to you joining the team.</p>";
            } else {
                $onboarding_instructions_sid = $onboarding_instructions_data[0]['sid'];
                $onboarding_instructions = $onboarding_instructions_data[0]['instructions'];
            }


            $onboarding_disclosure_data = $this->onboarding_model->get_company_disclosure($company_sid);
            $onboarding_disclosure_sid = 0;
            $data['onboarding_disclosure_data'] = $onboarding_disclosure_data;
            if (empty($onboarding_disclosure_data)) {
                $onboarding_disclosure = "<b>Company Disclosure</b>";
            } else {
                $onboarding_disclosure_sid = $onboarding_disclosure_data[0]['sid'];
                $onboarding_disclosure = $onboarding_disclosure_data[0]['disclosure'];
            }


            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Onboarding Configuration';
                $data['company_sid'] = $company_sid;
                $onboarding_welcome_videos = $this->onboarding_model->get_company_welcome_videos($company_sid); //Welcome Videos
                $data['onboarding_welcome_video'] = $onboarding_welcome_videos;
                $onboarding_instructions = str_replace('{{company_name}}', ucwords($data['session']['company_detail']['CompanyName']), $onboarding_instructions);
                $data['onboarding_instructions'] = $onboarding_instructions;
                $office_locations = $this->onboarding_model->get_all_office_locations($company_sid); //Office Locations
                $data['office_locations'] = $office_locations;
                $office_timings = $this->onboarding_model->get_all_office_timings($company_sid); //Office Timings
                $data['office_timings'] = $office_timings;
                $what_to_bring_items = $this->onboarding_model->get_all_what_to_bring_items($company_sid); //What To Briing
                $data['what_to_bring_items'] = $what_to_bring_items;
                $useful_links = $this->onboarding_model->get_all_links($company_sid); //Useful Links
                $data['useful_links'] = $useful_links;
                $ems_notification = $this->onboarding_model->get_all_ems_notification($company_sid); //Useful Links
                $data['ems_notification'] = $ems_notification;

                $data['onboarding_disclosure'] = $onboarding_disclosure;

                $employees = $this->onboarding_model->get_all_employees($company_sid); //Employees
                $employees_for_select = array();


                foreach ($employees as $employee) {
                    $employees_for_select[$employee['sid']] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                }

                $data['employees'] = $employees_for_select;
                $people = $this->onboarding_model->get_all_people_to_meet($company_sid); //People to Meet
                $data['people'] = $people;

                $this->load->view('main/header', $data);
                $this->load->view('onboarding/configuration');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'insert_welcome_video':
                        $video_title = $this->input->post('welcome_video_title');
                        $video_source = $this->input->post('welcome_video_source');
                        $is_active = $this->input->post('welcome_video_status');

                        if (isset($_FILES['welcome_video_upload']) && !empty($_FILES['welcome_video_upload']['name'])) {
                            $random = generateRandomString(5);
                            $company_id = $data['session']['company_detail']['sid'];
                            $target_file_name = basename($_FILES["welcome_video_upload"]["name"]);
                            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/";
                            $target_file = $target_dir . $file_name;
                            $filename = $target_dir . $company_id;

                            if (!file_exists($filename)) {
                                mkdir($filename);
                            }

                            if (move_uploaded_file($_FILES["welcome_video_upload"]["tmp_name"], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["welcome_video_upload"]["name"]) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect('onboarding/configuration', 'refresh');
                            }

                            $video_id = $file_name;
                        } else {
                            $video_id = $this->input->post('yt_vm_video_url');

                            if ($video_source == 'youtube') {
                                $url_prams = array();
                                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                if (isset($url_prams['v'])) {
                                    $video_id = $url_prams['v'];
                                } else {
                                    $video_id = '';
                                }
                            } else {
                                $video_id = $this->vimeo_get_id($video_id);
                            }
                        }

                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['is_active'] = $is_active;
                        $data_to_insert['title'] = $video_title;
                        $data_to_insert['video_source'] = $video_source;
                        $data_to_insert['video_url'] = $video_id;
                        $data_to_insert['inserted_by_sid'] = $employer_sid;
                        $onboarding_welcome_video_sid = 0;
                        $this->onboarding_model->insert_update_welcome_video($data_to_insert, $onboarding_welcome_video_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Welcome Video Insert successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_welcome_video':
                        $welcome_video_sid = $this->input->post('welcome_video_sid');
                        $company_sid = $this->input->post('company_sid');

                        $this->onboarding_model->delete_welcome_video($welcome_video_sid, $company_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Welcome video deleted successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'enable_disable_welcome_video':
                        $welcome_video_sid = $this->input->post('welcome_video_sid');
                        $company_sid = $this->input->post('company_sid');
                        $enable_status = $this->input->post('enable_status');

                        if ($enable_status == 0) {
                            $data_to_update['is_active'] = 1;
                        } else if ($enable_status == 1) {
                            $data_to_update['is_active'] = 0;
                        }

                        $this->onboarding_model->change_welcome_video_status($data_to_update, $welcome_video_sid, $company_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Welcome video status changed successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'make_welcome_video_default_undefault':
                        $welcome_video_sid = $this->input->post('welcome_video_sid');
                        $company_sid = $this->input->post('company_sid');
                        $default_status = $this->input->post('default_status');

                        if ($default_status == 0) {
                            $data_to_update['is_default'] = 1;
                        } else if ($default_status == 1) {
                            $data_to_update['is_default'] = 0;
                        }

                        $data_to_undefault['is_default'] = 0;
                        $this->onboarding_model->undefault_welcome_video_default_status($data_to_undefault, $company_sid);
                        $this->onboarding_model->change_welcome_video_status($data_to_update, $welcome_video_sid, $company_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Welcome video default status changed successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'onboarding_instructions':
                        $instructions = $this->input->post('instructions');
                        $instructions = str_replace('{{company_name}}', ucwords($data['session']['company_detail']['CompanyName']), $instructions);
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['instructions'] = $instructions;
                        $data_to_insert['modified_by_sid'] = $employer_sid;
                        $this->onboarding_model->insert_update_onboarding_instructions($data_to_insert, $onboarding_instructions_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Instructions updated successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_new_office_location':
                        $company_sid = $this->input->post('company_sid');
                        $location_title = $this->input->post('location_title');
                        $location_address = $this->input->post('location_address');
                        $location_telephone = $this->input->post('location_telephone');
                        $location_fax = $this->input->post('location_fax');
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['location_title'] = $location_title;
                        $data_to_insert['location_address'] = $location_address;
                        $data_to_insert['location_telephone'] = $location_telephone;
                        $data_to_insert['location_fax'] = $location_fax;
                        $data_to_insert['location_status'] = 1;
                        $this->onboarding_model->insert_office_location($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Location Successfully Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_office_location':
                        $company_sid = $this->input->post('company_sid');
                        $office_location_sid = $this->input->post('office_location_sid');
                        $this->onboarding_model->delete_office_location($company_sid, $office_location_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Location Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_new_getting_started_section':
                        $company_sid = $this->input->post('company_sid');
                        $section_title = $this->input->post('section_title');
                        $section_content = $this->input->post('section_content');
                        $section_video = $this->input->post('section_video');
                        $section_video_status = $this->input->post('section_video_status');
                        $section_video_source = $this->input->post('section_video_source');
                        $section_image_status = $this->input->post('section_image_status');
                        $section_sort_order = $this->input->post('section_sort_order');
                        $image = upload_file_to_aws('section_image', $company_sid, 'onboarding_section_image', '');
                        $section_image = '';

                        if ($image != 'error') {
                            $section_image = $image;
                        }

                        if ($section_video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($section_video, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $section_video = $url_prams['v'];
                            } else {
                                $section_video = '';
                            }
                        } else {
                            $section_video = (int) substr(parse_url($section_video, PHP_URL_PATH), 1);
                        }

                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['section_title'] = $section_title;
                        $data_to_insert['section_content'] = htmlentities($section_content);
                        $data_to_insert['section_status'] = 1;

                        if ($section_video != '') {
                            $data_to_insert['section_video'] = $section_video;
                        }

                        if ($section_image != '') {
                            $data_to_insert['section_image'] = $section_image;
                        }

                        if ($section_video_status == 2) {
                            $section_video_status = 0;
                            $data_to_insert['section_video'] = '';
                        }

                        if ($section_image_status == 2) {
                            $section_image_status = 0;
                            $data_to_insert['section_image'] = '';
                        }

                        $data_to_insert['section_video_status'] = $section_video_status;
                        $data_to_insert['section_video_source'] = $section_video_source;
                        $data_to_insert['section_image_status'] = $section_image_status;
                        $data_to_insert['section_sort_order'] = $section_sort_order;
                        $data_to_insert['section_unique_id'] = str_replace(' ', '_', $section_title) . '_' . $company_sid;

                        $this->onboarding_model->insert_getting_started_section($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Section Successfully Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'update_new_getting_started_section':
                        $section_sid = $this->input->post('section_sid');
                        $company_sid = $this->input->post('company_sid');
                        $section_title = $this->input->post('section_title');
                        $section_content = $this->input->post('section_content');
                        $section_video = $this->input->post('section_video');
                        $section_video_status = $this->input->post('section_video_status');
                        $section_video_source = $this->input->post('section_video_source');
                        $section_image_status = $this->input->post('section_image_status');
                        $section_sort_order = $this->input->post('section_sort_order');
                        $image = upload_file_to_aws('section_image', $company_sid, 'onboarding_section_image', '');
                        $section_image = '';

                        if ($image != 'error') {
                            $section_image = $image;
                        }

                        if ($section_video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($section_video, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $section_video = $url_prams['v'];
                            } else {
                                $section_video = '';
                            }
                        } else {
                            $section_video = (int) substr(parse_url($section_video, PHP_URL_PATH), 1);
                        }

                        $data_to_update = array();
                        $data_to_update['company_sid'] = $company_sid;
                        $data_to_update['section_title'] = $section_title;
                        $data_to_update['section_content'] = htmlentities($section_content);
                        $data_to_update['section_status'] = 1;

                        if ($section_video != '') {
                            $data_to_update['section_video'] = $section_video;
                        }

                        if ($section_image != '') {
                            $data_to_update['section_image'] = $section_image;
                        }

                        if ($section_video_status == 2) {
                            $section_video_status = 0;
                            $data_to_update['section_video'] = '';
                        }

                        if ($section_image_status == 2) {
                            $section_image_status = 0;
                            $data_to_update['section_image'] = '';
                        }

                        $data_to_update['section_video_status'] = $section_video_status;
                        $data_to_update['section_video_source'] = $section_video_source;
                        $data_to_update['section_image_status'] = $section_image_status;
                        $data_to_update['section_sort_order'] = $section_sort_order;
                        $data_to_update['section_unique_id'] = str_replace(' ', '_', $section_title) . '_' . $company_sid;
                        $this->onboarding_model->update_getting_started_section($section_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Section Successfully Updated!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_getting_started_section':
                        $company_sid = $this->input->post('company_sid');
                        $getting_started_section_sid = $this->input->post('getting_started_section_sid');
                        $this->onboarding_model->delete_getting_started_section($company_sid, $getting_started_section_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Section Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_new_office_timings':
                        $company_sid = $this->input->post('company_sid');
                        $title = $this->input->post('title');
                        $start_time = $this->input->post('start_time');
                        $end_time = $this->input->post('end_time');
                        $start_time = DateTime::createFromFormat('h:iA', $start_time)->format('H:i:s');
                        $end_time = DateTime::createFromFormat('h:iA', $end_time)->format('H:i:s');
                        // Convert time
                        // $start_time = reset_datetime(array(
                        //    'datetime' => date('Y-m-d').''.$start_time,
                        //    'from_format' => 'Y-m-dh:iA',
                        //    'format' => 'H:i:s',
                        //    '_this' => $this,
                        //    'revert' => true,
                        //    'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                        // ));
                        // $end_time = reset_datetime(array(
                        //    'datetime' => date('Y-m-d').''.$end_time,
                        //    'from_format' => 'Y-m-dh:iA',
                        //    'format' => 'H:i:s',
                        //    '_this' => $this,
                        //    'revert' => true,
                        //    'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                        // ));
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['title'] = $title;
                        $data_to_insert['start_time'] = $start_time;
                        $data_to_insert['end_time'] = $end_time;
                        $data_to_insert['status'] = 1;
                        // _e($data_to_insert, true, true);
                        $this->onboarding_model->insert_office_timings($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Office Timing Successfully Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_office_timings':
                        $company_sid = $this->input->post('company_sid');
                        $office_timings_sid = $this->input->post('office_timings_sid');
                        $this->onboarding_model->delete_office_timing($company_sid, $office_timings_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Office Timing Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_new_what_to_bring_item':
                        $company_sid = $this->input->post('company_sid');
                        $item_title = $this->input->post('item_title');
                        $item_description = $this->input->post('item_description');
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['item_title'] = $item_title;
                        $data_to_insert['item_description'] = $item_description;
                        $data_to_insert['status'] = 1;
                        $this->onboarding_model->insert_what_to_bring_item($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Item Successfully Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_what_to_bring_item':
                        $company_sid = $this->input->post('company_sid');
                        $what_to_bring_item_sid = $this->input->post('what_to_bring_item_sid');
                        $this->onboarding_model->delete_what_to_bring_item($company_sid, $what_to_bring_item_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Item Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_new_useful_link':
                        $company_sid = $this->input->post('company_sid');
                        $link_title = $this->input->post('link_title');
                        $link_description = $this->input->post('linkDescription');
                        $link_url = $this->input->post('link_url');
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['link_title'] = $link_title;
                        $data_to_insert['link_description'] = $link_description;
                        $data_to_insert['link_url'] = $link_url;
                        $data_to_insert['status'] = 1;
                        $this->onboarding_model->insert_useful_links_record($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Link Successfully Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_useful_link':
                        $company_sid = $this->input->post('company_sid');
                        $useful_link_sid = $this->input->post('useful_link_sid');
                        $this->onboarding_model->delete_useful_link($company_sid, $useful_link_sid);
                        $this->onboarding_model->inactive_useful_configuration_links($useful_link_sid, $company_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Link Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_ems_notification':
                        $company_sid = $this->input->post('company_sid');
                        $notification_sid = $this->input->post('notification_sid');
                        $this->onboarding_model->delete_ems_notification($company_sid, $notification_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Notification Successfully Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_people_to_meet_record':
                        $company_sid = $this->input->post('company_sid');
                        $employee_sid = $this->input->post('employee_sid');
                        $notes = $this->input->post('notes');
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employee_sid;
                        $data_to_insert['notes'] = $notes;
                        $data_to_insert['status'] = 1;
                        $this->onboarding_model->insert_people_to_meet_record($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Person Record Added!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'delete_person_to_meet_record':
                        $company_sid = $this->input->post('company_sid');
                        $person_to_meet_sid = $this->input->post('person_to_meet_sid');
                        $this->onboarding_model->delete_people_to_meet_record($company_sid, $person_to_meet_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Person Record Deleted!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                    case 'insert_ems_dashboard':
                        $insert_data = array();
                        $company_sid = $this->input->post('company_sid');
                        $title = $this->input->post('title');
                        $description = $this->input->post('description');
                        $video_source = $this->input->post('video_source');
                        $employees_assigned_to = $this->input->post('employees_assigned_to');
                        $sort_order = $this->input->post('sort_order');
                        $employees_assigned_sid = $this->input->post('employees_assigned_sid');
                        $image_status = $this->input->post('image_status');
                        $video_status = $this->input->post('video_status');

                        $insert_data['title'] = $title;
                        $insert_data['company_sid'] = $company_sid;
                        $insert_data['description'] = $description;
                        $insert_data['video_source'] = $video_source;
                        $insert_data['assigned_to'] = $employees_assigned_to;
                        $insert_data['sort_order'] = $sort_order;
                        $insert_data['image_code'] = '';
                        $insert_data['image_status'] = $image_status;
                        $insert_data['video_status'] = $video_status;
                        $insert_data['status'] = 1;
                        $insert_data['created_date'] = date('Y-m-d H:i:s');
                        $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

                        if (!empty($pictures) && $pictures != 'error') {
                            $insert_data['image_code'] = $pictures;
                        }

                        if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                            $random = date('H:i:s') . generateRandomString(5);
                            $company_id = $data['session']['company_detail']['sid'];
                            $target_file_name = basename($_FILES["video_upload"]["name"]);
                            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/";
                            $target_file = $target_dir . $file_name;
                            $filename = $target_dir . $company_id;

                            if (!file_exists($filename)) {
                                mkdir($filename);
                            }

                            if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect('onboarding/configuration', 'refresh');
                            }

                            $video_id = $file_name;
                        } else {
                            $video_id = $this->input->post('url');

                            if ($video_source == 'youtube') {
                                $url_prams = array();
                                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                if (isset($url_prams['v'])) {
                                    $video_id = $url_prams['v'];
                                } else {
                                    $video_id = '';
                                }
                            } else {
                                $video_id = $this->vimeo_get_id($video_id);
                            }
                        }

                        $insert_data['video_url'] = $video_id;
                        $notification_id = $this->onboarding_model->insert_dashboard_notification($insert_data);

                        if ($employees_assigned_to == 'specific') {
                            foreach ($employees_assigned_sid as $emp_sid) {
                                $insert_data = array('ems_notification_sid' => $notification_id, 'employee_sid' => $emp_sid);
                                $this->onboarding_model->insert_assigned_configuration($insert_data);
                            }
                        }

                        $this->session->set_flashdata('message', '<strong>Success: </strong> New Notification Added Successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;

                    case 'onboarding_disclosure':
                        $disclosure = $this->input->post('disclosure');
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['disclosure'] = $disclosure;
                        $data_to_insert['modified_by_sid'] = $employer_sid;
                        $this->onboarding_model->insert_update_onboarding_disclosure($data_to_insert, $onboarding_disclosure_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Disclosure updated successfully!');
                        redirect('onboarding/configuration', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_welcome_video($welcome_video_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $onboarding_welcome_videos = $this->onboarding_model->get_company_welcome_video($welcome_video_sid);
            $data['title'] = 'Edit Welcome Video';
            $data['company_sid'] = $company_sid;
            $data['security_details'] = $security_details;
            $data['onboarding_welcome_video'] = $onboarding_welcome_videos;
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('onboarding/configuration_welcome_video_edit');
                $this->load->view('main/footer');
            } else {
                $video_title = $this->input->post('welcome_video_title');
                $video_source = $this->input->post('welcome_video_source');
                $is_active = $this->input->post('welcome_video_status');
                $welcome_video_sid = $this->input->post('welcome_video_sid');

                if (isset($_FILES['welcome_video_upload']) && !empty($_FILES['welcome_video_upload']['name'])) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["welcome_video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["welcome_video_upload"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["welcome_video_upload"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('onboarding/edit_welcome_video' . '/' . $welcome_video_sid, 'refresh');
                    }

                    $video_id = $file_name;
                } else {
                    $video_id = $this->input->post('yt_vm_video_url');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } elseif ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    } elseif ($video_source == 'upload') {
                        $video_id = $this->input->post('welcome_video_old_url');
                    }
                }

                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['is_active'] = $is_active;

                if ($is_active != 0 && !empty($video_id)) {
                    $data_to_insert['video_url'] = $video_id;
                } else if (!empty($video_id)) {
                    $data_to_insert['video_url'] = $video_id;
                }

                $data_to_insert['title'] = $video_title;
                $data_to_insert['video_source'] = $video_source;
                $data_to_insert['inserted_by_sid'] = $employee_sid;
                $onboarding_welcome_video_sid = $welcome_video_sid;
                $this->onboarding_model->insert_update_welcome_video($data_to_insert, $onboarding_welcome_video_sid);
                $this->session->set_flashdata('message', '<strong>Success: </strong> Welcome Video Update successfully!');
                redirect('onboarding/configuration', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function getUsefulLink($useful_link_sid, $company_sid)
    {
        $useful_link = $this->onboarding_model->get_edit_link($company_sid, $useful_link_sid);
        $return_data = array();

        if (!empty($useful_link[0])) {
            $return_data['sid'] = $useful_link[0]['sid'];
            $return_data['company_sid'] = $useful_link[0]['company_sid'];
            $return_data['link_title'] = $useful_link[0]['link_title'];
            $return_data['link_url'] = $useful_link[0]['link_url'];
            $return_data['link_description'] = $useful_link[0]['link_description'];
        }

        echo json_encode($return_data);
    }

    public function updateUsefulLink()
    {
        $sid = $this->input->post('sid');
        $title = $this->input->post('link_title');
        $description = $this->input->post('link_description');
        $link = $this->input->post('useful_link');
        $edit_data = array();
        $edit_data['link_title'] = $title;
        $edit_data['link_description'] = $description;
        $edit_data['link_url'] = $link;
        $this->onboarding_model->update_usefull_link($sid, $edit_data);
    }

    public function getOfficeLocation($location_sid, $company_sid)
    {
        $location = $this->onboarding_model->get_edit_location($company_sid, $location_sid);
        $return_data = array();

        if (!empty($location[0])) {
            $return_data['location_sid'] = $location[0]['sid'];
            $return_data['company_sid'] = $location[0]['company_sid'];
            $return_data['location_title'] = $location[0]['location_title'];
            $return_data['location_address'] = $location[0]['location_address'];
            $return_data['location_telephone'] = $location[0]['location_telephone'];
            $return_data['location_fax'] = $location[0]['location_fax'];
        }

        echo json_encode($return_data);
    }

    public function updateLocation()
    {
        $sid = $this->input->post('sid');
        $title = $this->input->post('location_title');
        $address = $this->input->post('location_address');
        $phone = $this->input->post('location_telephone');
        $fax = $this->input->post('location_fax');
        $edit_data = array();
        $edit_data['location_title'] = $title;
        $edit_data['location_address'] = $address;
        $edit_data['location_telephone'] = $phone;
        $edit_data['location_fax'] = $fax;
        $this->onboarding_model->update_location($sid, $edit_data);
    }

    public function getOfficeHours($hours_sid, $company_sid)
    {
        $timming = $this->onboarding_model->getOfficeHours($company_sid, $hours_sid);
        $return_data = array();

        if (!empty($timming[0])) {
            $return_data['hours_sid'] = $timming[0]['sid'];
            $return_data['company_sid'] = $timming[0]['company_sid'];
            $return_data['hours_title'] = $timming[0]['title'];
            $return_data['hours_start_time'] = DateTime::createFromFormat('H:i:s', $timming[0]['start_time'])->format('h:i A');
            $return_data['hours_end_time'] = DateTime::createFromFormat('H:i:s', $timming[0]['end_time'])->format('h:i A');
        }

        echo json_encode($return_data);
    }

    public function updateOfficeHours()
    {
        $sid = $this->input->post('sid');
        $title = $this->input->post('hours_title');
        $start = $this->input->post('hours_start');
        $end = $this->input->post('hours_end');
        $start_time = DateTime::createFromFormat('h:iA', $start)->format('H:i:s');
        $end_time = DateTime::createFromFormat('h:iA', $end)->format('H:i:s');
        $edit_data = array();
        $edit_data['title'] = $title;
        $edit_data['start_time'] = $start_time;
        $edit_data['end_time'] = $end_time;
        $this->onboarding_model->updateOfficeHours($sid, $edit_data);
    }

    public function customOfficeLocation()
    {
        $company_sid = $this->input->post('company_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        $location_title = $this->input->post('location_title');
        $location_address = $this->input->post('location_address');
        $location_telephone = $this->input->post('location_phone');
        $location_fax = $this->input->post('location_fax');
        $insert_custom_data = array();
        $insert_custom_data['custom_type'] = 'location';
        $insert_custom_data['company_sid'] = $company_sid;
        $insert_custom_data['user_type'] = $user_type;

        if ($user_type == 'employee') {
            $insert_custom_data['employee_sid'] = $user_sid;
        } else {
            $insert_custom_data['applicant_sid'] = $user_sid;
        }

        $insert_custom_data['location_title'] = $location_title;
        $insert_custom_data['location_address'] = $location_address;
        $insert_custom_data['location_phone'] = $location_telephone;
        $insert_custom_data['location_fax'] = $location_fax;
        $insert_custom_data['status'] = 1;
        $insert_custom_data['is_custom'] = 1;
        $record_sid = $this->onboarding_model->custom_assignment_insert_data($insert_custom_data);
        echo json_encode($record_sid);
    }

    public function customOfficeHours()
    {
        $company_sid = $this->input->post('company_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        $title = $this->input->post('hour_title');
        $start = $this->input->post('hour_start_time');
        $end = $this->input->post('hour_end_time');
        $start_time = DateTime::createFromFormat('h:iA', $start)->format('H:i:s');
        $end_time = DateTime::createFromFormat('h:iA', $end)->format('H:i:s');
        $insert_custom_data = array();
        $insert_custom_data['custom_type'] = 'timing';
        $insert_custom_data['company_sid'] = $company_sid;
        $insert_custom_data['user_type'] = $user_type;

        if ($user_type == 'employee') {
            $insert_custom_data['employee_sid'] = $user_sid;
        } else {
            $insert_custom_data['applicant_sid'] = $user_sid;
        }

        $insert_custom_data['hour_title'] = $title;
        $insert_custom_data['hour_start_time'] = $start_time;
        $insert_custom_data['hour_end_time'] = $end_time;
        $insert_custom_data['status'] = 1;
        $insert_custom_data['is_custom'] = 1;
        $record_sid = $this->onboarding_model->custom_assignment_insert_data($insert_custom_data);
        echo json_encode($record_sid);
    }


    public function customOfficeUsefullLink()
    {
        $company_sid = $this->input->post('company_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        $title = $this->input->post('link_title');
        $link_description = $this->input->post('link_description');
        $link_url = $this->input->post('link_url');
        //
        $insert_custom_data = array();
        $insert_custom_data['custom_type'] = 'useful_link';
        $insert_custom_data['company_sid'] = $company_sid;
        $insert_custom_data['user_type'] = $user_type;

        if ($user_type == 'employee') {
            $insert_custom_data['employee_sid'] = $user_sid;
        } else {
            $insert_custom_data['applicant_sid'] = $user_sid;
        }

        $insert_custom_data['link_title'] = $title;
        $insert_custom_data['link_description'] = $link_description;
        $insert_custom_data['link_url'] = $link_url;
        $insert_custom_data['status'] = 1;
        $insert_custom_data['is_custom'] = 1;
        $record_sid = $this->onboarding_model->custom_assignment_insert_data($insert_custom_data);
        echo json_encode($record_sid);
    }

    public function customOfficeUsefullLink_update()
    {
        $record_sid = $this->input->post('record_sid');
        $title = $this->input->post('link_title');
        $link_description = $this->input->post('link_description');
        $link_url = $this->input->post('link_url');
        //
        $update_custom_data = array();

        $update_custom_data['link_title'] = $title;
        $update_custom_data['link_description'] = $link_description;
        $update_custom_data['link_url'] = $link_url;

        $record_sid = $this->onboarding_model->custom_assignment_update_record($record_sid, $update_custom_data);
        echo json_encode($record_sid);
    }

    public function customWhatToBring()
    {
        $company_sid = $this->input->post('company_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        $item_title = $this->input->post('item_title');
        $item_discription = $this->input->post('item_description');
        $insert_custom_data = array();
        $insert_custom_data['custom_type'] = 'item';
        $insert_custom_data['company_sid'] = $company_sid;
        $insert_custom_data['user_type'] = $user_type;

        if ($user_type == 'employee') {
            $insert_custom_data['employee_sid'] = $user_sid;
        } else {
            $insert_custom_data['applicant_sid'] = $user_sid;
        }

        $insert_custom_data['item_title'] = $item_title;
        $insert_custom_data['item_description'] = $item_discription;
        $insert_custom_data['status'] = 1;
        $insert_custom_data['is_custom'] = 1;
        $record_sid = $this->onboarding_model->custom_assignment_insert_data($insert_custom_data);
        echo json_encode($record_sid);
    }

    public function change_custom_status()
    {
        $sid = $this->input->post('custom_record_sid');
        $status = $this->input->post('custom_record_status');
        $update_custom_status = array();
        $update_custom_status['status'] = $status;
        $this->onboarding_model->custom_assignment_update_record($sid, $update_custom_status);
    }

    public function getCustomRecord($sid = NULL)
    {
        $return_data = array();

        if ($sid != NULL) {
            $custom_record = $this->onboarding_model->get_custom_record($sid);

            if (!empty($custom_record[0])) {
                $return_data = $custom_record[0];
                $custom_type = $return_data['custom_type'];

                if ($custom_type == 'timing') {
                    $hour_start_time = date('h:i A', strtotime($return_data['hour_start_time']));
                    $hour_end_time = date('h:i A', strtotime($return_data['hour_end_time']));
                    $return_data['hour_start_time'] = $hour_start_time;
                    $return_data['hour_end_time'] = $hour_end_time;
                }
            }
        }

        echo json_encode($return_data);
    }

    public function updateCustomOfficeLocation()
    {
        $sid = $this->input->post('sid');
        $location_title = $this->input->post('location_title');
        $location_address = $this->input->post('location_address');
        $location_telephone = $this->input->post('location_phone');
        $location_fax = $this->input->post('location_fax');
        $update_custom_data = array();
        $update_custom_data['location_title'] = $location_title;
        $update_custom_data['location_address'] = $location_address;
        $update_custom_data['location_phone'] = $location_telephone;
        $update_custom_data['location_fax'] = $location_fax;
        $this->onboarding_model->custom_assignment_update_record($sid, $update_custom_data);
    }

    public function updateCustomOfficeHours()
    {
        $sid = $this->input->post('sid');
        $hour_title = $this->input->post('hour_title');
        $hour_start_time = $this->input->post('hour_start_time');
        $hour_end_time = $this->input->post('hour_end_time');
        $hour_start_time = DateTime::createFromFormat('h:iA', $hour_start_time)->format('H:i:s');
        $hour_end_time = DateTime::createFromFormat('h:iA', $hour_end_time)->format('H:i:s');
        $update_custom_data = array();
        $update_custom_data['hour_title'] = $hour_title;
        $update_custom_data['hour_start_time'] = $hour_start_time;
        $update_custom_data['hour_end_time'] = $hour_end_time;
        $this->onboarding_model->custom_assignment_update_record($sid, $update_custom_data);
    }

    public function updateCustomOfficeItem()
    {
        $sid = $this->input->post('sid');
        $item_title = $this->input->post('item_title');
        $item_description = $this->input->post('item_description');
        $update_custom_data = array();
        $update_custom_data['item_title'] = $item_title;
        $update_custom_data['item_description'] = $item_description;
        $this->onboarding_model->custom_assignment_update_record($sid, $update_custom_data);
    }

    public function updateWelcomeVideoStatus()
    {
        $sid = $this->input->post('sid');
        $status = $this->input->post('status');
        $edit_data = array();
        $edit_data['is_active'] = $status;
        $this->onboarding_model->insert_update_welcome_video($edit_data, $sid);
    }

    public function updateWelcomeVideoSource()
    {
        $sid = $this->input->post('welcome_sid');
        $source = $this->input->post('welcome_source');

        if (isset($_FILES['welcome_video_upload']) && !empty($_FILES['welcome_video_upload']['name'])) {
            $random = generateRandomString(5);
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $target_file_name = basename($_FILES["welcome_video_upload"]["name"]);
            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
            $target_dir = "assets/uploaded_videos/";
            $target_file = $target_dir . $file_name;
            $filename = $target_dir . $company_id;

            if (!file_exists($filename)) {
                mkdir($filename);
            }

            if (move_uploaded_file($_FILES["welcome_video_upload"]["tmp_name"], $target_file)) {
                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["welcome_video_upload"]["name"]) . ' has been uploaded.');
            } else {
                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                redirect('onboarding/updateWelcomeVideoSource', 'refresh');
            }

            $video_id = $file_name;
        } else {
            $video_id = $this->input->post('welcome_url');

            if ($source == 'youtube') {
                $url_prams = array();
                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                if (isset($url_prams['v'])) {
                    $video_id = $url_prams['v'];
                } else {
                    $video_id = '';
                }
            } else {
                $video_id = $this->vimeo_get_id($video_id);
            }
        }

        $edit_data = array();
        $edit_data['video_source'] = $source;
        $edit_data['video_url'] = $video_id;
        $this->onboarding_model->insert_update_welcome_video($edit_data, $sid);
    }

    public function getPerson($person_sid, $company_sid)
    {
        $person = $this->onboarding_model->getPersonToMeet($company_sid, $person_sid);
        $return_data = array();

        if (!empty($person[0])) {
            $return_data['person_sid'] = $person[0]['sid'];
            $return_data['company_sid'] = $person[0]['company_sid'];
            $return_data['employer_sid'] = $person[0]['employer_sid'];
            $return_data['notes'] = $person[0]['notes'];
        }

        echo json_encode($return_data);
    }

    public function checkPerson($company_sid, $employer_sid)
    {
        $check = $this->onboarding_model->checkEmployeer($company_sid, $employer_sid);
        echo $check;
    }

    public function checkPersonBeforeEdit($company_sid, $employer_sid, $record_sid)
    {
        $check = $this->onboarding_model->checkemployerBeforeEdit($company_sid, $employer_sid);

        if (!empty($check)) {
            if ($check[0]['sid'] == $record_sid) {
                echo false;
            } else {
                echo true;
            }
        } else {
            echo false;
        }
    }

    public function updatePerson()
    {
        $sid = $this->input->post('sid');
        $employer = $this->input->post('person_sid');
        $notes = $this->input->post('person_notes');
        $edit_data = array();
        $edit_data['employer_sid'] = $employer;
        $edit_data['notes'] = $notes;
        $this->onboarding_model->updatePersonToMeet($sid, $edit_data);
    }

    public function getBringItem($item_sid, $company_sid)
    {
        $item = $this->onboarding_model->getItemToBring($company_sid, $item_sid);
        $return_data = array();

        if (!empty($item[0])) {
            $return_data['item_sid'] = $item[0]['sid'];
            $return_data['company_sid'] = $item[0]['company_sid'];
            $return_data['item_title'] = $item[0]['item_title'];
            $return_data['item_description'] = $item[0]['item_description'];
        }

        echo json_encode($return_data);
    }

    public function updateBringItem()
    {
        $sid = $this->input->post('sid');
        $title = $this->input->post('item_title');
        $description = $this->input->post('item_description');
        $edit_data = array();
        $edit_data['item_title'] = $title;
        $edit_data['item_description'] = $description;
        $this->onboarding_model->updateItemToBring($sid, $edit_data);
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function ajax_responder()
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            //Handle Get
        } else { //Handle Post
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'edit_getting_started_section':
                    $section_sid = $this->input->post('section_sid');
                    $section = $this->onboarding_model->get_single_getting_started_section($section_sid);
                    $view_data = array();
                    $view_data['company_sid'] = $section['company_sid'];
                    $view_data['section'] = $section;
                    $this->load->view('onboarding/configuration_getting_started_section', $view_data);
                    break;
                case 'generate_getting_started_section_preview':
                    $section_sid = $this->input->post('section_sid');
                    $section = $this->onboarding_model->get_single_getting_started_section($section_sid);
                    $view_data = array();
                    $view_data['section'] = $section;
                    $this->load->view('onboarding/getting_started_section_preview_partial', $view_data);
                    break;
                case 'get_location_map':
                    $location_sid = $this->input->post('location_sid');
                    $location = $this->onboarding_model->get_single_office_locations($location_sid);
                    $map = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($location['location_address']) . "&zoom=13&size=400x400&key=" . GOOGLE_API_KEY;
                    echo '<div class="img-thumbnail text-center"><img style="width: 100%;" src="' . $map . '" alt="Map no found!" class="img-responsive" /></div>';
                    break;
            }
        }
    }

    public function print_applicant_upload_img($image_url)
    {
        $document_file = AWS_S3_BUCKET_URL . $image_url;
        $data['print'] = '';
        $data['download'] = NULL;
        $data['file_name'] = NULL;
        $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
        $this->load->view('hr_documents_management/print_generated_document', $data);
    }

    public function print_applicant_generated_doc($type, $unique_sid = NULL, $sid, $download = NULL)
    {
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $company_sid = $onboarding_details['company_sid'];
            $user_sid = $onboarding_details['applicant_sid'];
            $user_type = 'applicant';
            $document = $this->hr_documents_management_model->get_submitted_generated_document($sid);

            if ($type == 'original') {
                // $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                // $value = '<div class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                // $document_content = str_replace('[Target User Input Field]', $value, $document_content);
                // $value = '<br><input type="checkbox" class="user_checkbox"/>';
                // $document_content = str_replace('[Target User Checkbox]', $value, $document_content);
                // //E_signature process
                // $signature_bas64_image = '<a class="btn btn- btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: '.SIGNATURE_MAX_HEIGHT.';" src=""  id="draw_upload_img" />';
                // $init_signature_bas64_image = '<a class="btn btn- btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: '.SIGNATURE_MAX_HEIGHT.';" src=""  id="target_signature_init" />';
                // $signature_timestamp = '<a class="btn btn- btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';
                // $value = ' ';
                // $document_content = str_replace($signature_bas64_image, $value, $document_content);
                // $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
                // $document_content = str_replace($signature_timestamp, $value, $document_content);

                $document_content = $document['document_description'];
                $document_sid = $document['document_sid'];

                $value = '------------------------------';
                $document_content = str_replace('{{first_name}}', $value, $document_content);
                $document_content = str_replace('{{last_name}}', $value, $document_content);
                $document_content = str_replace('{{email}}', $value, $document_content);
                $document_content = str_replace('{{job_title}}', $value, $document_content);
                $document_content = str_replace('{{company_name}}', $value, $document_content);
                $document_content = str_replace('{{company_address}}', $value, $document_content);
                $document_content = str_replace('{{company_phone}}', $value, $document_content);
                $document_content = str_replace('{{career_site_url}}', $value, $document_content);
                $document_content = str_replace('{{signature}}', $value, $document_content);
                $document_content = str_replace('{{inital}}', $value, $document_content);
                $document_content = str_replace('{{sign_date}}', $value, $document_content);
                $document_content = str_replace('{{signature_print_name}}', $value, $document_content);
                $document_content = str_replace('{{short_text}}', $value, $document_content);
                $authorized_base64 = get_authorized_base64_signature($company_sid, $document_sid);

                if (empty($document['authorized_signature'])) {
                    $authorized_signature = '------------------------------(Authorized Signature Requireq)';
                } else {
                    $authorized_signature = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '">';
                }

                if (!empty($document['authorized_signature_date'])) {
                    $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                } else {
                    $authorized_signature_date = '------------------------------(Authorized Sign Date Requireq)';
                }

                if (!empty($document['authorized_editable_date'])) {
                    $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
                } else {
                    $authorized_editable_date = '------------------------------(Authorized Date Required)';
                }

                $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);
                $document_content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_content);
                $document_content = str_replace('{{authorized_editable_date}}', $authorized_editable_date, $document_content);

                $value = '<br><input type="checkbox" class="user_checkbox"/>';
                $document_content = str_replace('{{checkbox}}', $value, $document_content);

                $value = '<div style="border: 1px dotted #000; padding:5px;"  contenteditable="true"></div>';
                $document_content = str_replace('{{text}}', $value, $document_content);
                $document_content = str_replace('{{short_text_required}}', ' (<b>Required Field</b>)'.$value, $document_content);
                $document_content = str_replace('{{text_required}}', ' (<b>Required Field</b>)'.$value, $document_content);

                $value = '<div style="border: 1px dotted #000; padding:5px; min-height: 145px;" class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                $document_content = str_replace('{{text_area}}', $value, $document_content);
                $document_content = str_replace('{{text_area_required}}', ' (<b>Required Field</b>)'.$value, $document_content);
                //
                $checkboxRequired = '<div class="row">';
                $checkboxRequired .= '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
                $checkboxRequired .= '(<b>Required Field</b>)';
                $checkboxRequired .= '</div>';
                $checkboxRequired .= '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
                $checkboxRequired .= '<input type="checkbox" class="user_checkbox input-grey" />Agree';
                $checkboxRequired .= '</div>';
                $checkboxRequired .= '<br>';
                $checkboxRequired .= '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
                $checkboxRequired .= '<input type="checkbox" class="user_checkbox input-grey" />Disagree';
                $checkboxRequired .= '</div>';
                $checkboxRequired .= '</div>';
                //
                $document_content = str_replace('{{checkbox_required}}', $checkboxRequired, $document_content);
                //
                $data['print'] = $type;
                $data['download'] = $download;
                $data['file_name'] = $document['document_title'];
                $data['original_document_description'] = $document_content;
            } else if ($type == 'submitted') {
                $data['print'] = $type;
                $data['download'] = $download;
                $data['file_name'] = $document['document_title'];
                $data['document'] = $document;
            }

            $this->load->view('hr_documents_management/print_generated_document', $data);
        }
    }

    public function download_assign_document($unique_sid, $document_sid, $print_type)
    {
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $company_sid = $onboarding_details['company_sid'];
            $applicant_sid = $onboarding_details['applicant_sid'];

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid);

                if ($document['document_type'] == 'generated') {
                    $data['document'] = $document;
                    $data['load_view'] = $load_view;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['security_details'] = $security_details;
                    $data['title'] = 'Learning Center - Supported Document';
                    $data['employee'] = $data['session']['employer_detail'];
                    $data['print'] = $print_type;

                    if ($print_type == 'original') {
                        $document_content = replace_tags_for_document($company_sid, $applicant_sid, 'applicant', $document['document_description'], $document['document_sid']);
                        $value = '<div class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                        $document_content = str_replace('[Target User Input Field]', $value, $document_content);
                        $value = '<br><input type="checkbox" class="user_checkbox"/>';
                        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);
                        //E_signature process
                        $signature_bas64_image = '<a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
                        $init_signature_bas64_image = '<a class="btn btn-sm blue-button get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
                        $signature_timestamp = '<a class="btn btn-sm blue-button get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';
                        $value = ' ';
                        $document_content = str_replace($signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($signature_timestamp, $value, $document_content);

                        $data['original_document_description'] = $document_content;
                    } else {
                        if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) {
                            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                            $file_name = $document['uploaded_file'];
                            $temp_file_path = $temp_path . $file_name;

                            if (file_exists($temp_file_path)) {
                                unlink($temp_file_path);
                            }

                            $this->load->library('aws_lib');
                            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);

                            if (file_exists($temp_file_path)) {
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($temp_file_path));
                                $handle = fopen($temp_file_path, 'rb');
                                $buffer = '';

                                while (!feof($handle)) {
                                    $buffer = fread($handle, 4096);
                                    echo $buffer;
                                    ob_flush();
                                    flush();
                                }

                                fclose($handle);
                                unlink($temp_file_path);
                            }
                        }
                    }

                    $this->load->view('hr_documents_management/download_generated_document', $data);
                } else {
                    $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;

                    if ($print_type == 'original') {
                        $file_name = $document['document_s3_name'];
                    } else {
                        $file_name = $document['uploaded_file'];
                    }

                    $temp_file_path = $temp_path . $file_name;

                    if (file_exists($temp_file_path)) {
                        unlink($temp_file_path);
                    }

                    $this->load->library('aws_lib');
                    $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);

                    if (file_exists($temp_file_path)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . $file_name . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($temp_file_path));
                        $handle = fopen($temp_file_path, 'rb');
                        $buffer = '';

                        while (!feof($handle)) {
                            $buffer = fread($handle, 4096);
                            echo $buffer;
                            ob_flush();
                            flush();
                        }

                        fclose($handle);
                        unlink($temp_file_path);
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function downloaded_generated_doc($user_sid, $company_sid, $document_sid, $user_type)
    {
        $document = $this->onboarding_model->get_required_document_info($company_sid, $user_sid, $user_type, $document_sid);

        if ($document['document_type'] == 'offer_letter') {
            // $document_info = $this->onboarding_model->get_assign_offer_letter_info($document['document_sid']);
            if (!empty($document) && ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1)) {
                if ($document['acknowledged'] == 1) {
                    $data_to_update = array();
                    $data_to_update['downloaded'] = 1;
                    $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');

                    if ($document['signature_required'] == 0 && $document['user_consent'] == 0) {
                        $data_to_update['user_consent'] = 1;
                        $data_to_update['form_input_data'] = 's:2:"{}";';
                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                    }

                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                } else {
                    $data_to_update = array();
                    $data_to_update['downloaded'] = 1;
                    $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');
                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                }
            } else if (!empty($document) && ($document['download_required'] == 1)) {
                $data_to_update = array();
                $data_to_update['downloaded'] = 1;
                $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');

                if ($document['signature_required'] == 0 && $document['user_consent'] == 0) {
                    $data_to_update['user_consent'] = 1;
                    $data_to_update['form_input_data'] = 's:2:"{}";';
                    $data_to_update['signature_timestamp'] = date('Y-m-d');
                }

                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
            }
        } else {
            $this->hr_documents_management_model->downloaded_generated_doc_on($company_sid, $user_sid, $document_sid, $user_type);
        }
    }

    public function download_hr_document($unique_sid = NULL, $document_sid)
    {
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $data['onboarding_details'] = $onboarding_details;
            $applicant_info = $onboarding_details['applicant_info'];
            $data['applicant_info'] = $applicant_info;
            $company_info = $onboarding_details['company_info'];
            $data['session']['company_detail'] = $company_info;
            $data['company_info'] = $company_info;
            $applicant_sid = $onboarding_details['applicant_sid'];
            $data['applicant_sid'] = $applicant_sid;
            $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
            $data['onboarding_progress'] = $onboarding_progress;
            $data['unique_sid'] = $unique_sid;
            $data['applicant'] = $applicant_info;
            $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid);
            $data['document'] = $document;
            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
            $file_name = $document['document_original_name'];
            $temp_file_path = $temp_path . $file_name;

            if (file_exists($temp_file_path)) {
                unlink($temp_file_path);
            }

            $this->load->library('aws_lib');
            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['document_s3_name'], $temp_file_path);

            if (file_exists($temp_file_path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($temp_file_path));
                $handle = fopen($temp_file_path, 'rb');
                $buffer = '';

                while (!feof($handle)) {
                    $buffer = fread($handle, 4096);
                    echo $buffer;
                    ob_flush();
                    flush();
                }

                fclose($handle);
                unlink($temp_file_path);
            }

            $this->hr_documents_management_model->update_download_status('applicant', $applicant_sid, $document_sid);
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function setup($user_type, $user_sid, $job_list_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            //
            if ($data['session']['employer_detail']['access_level_plus'] != 1) {
                check_access_permissions($security_details, 'dashboard', 'setup');
            }
            //
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $pp_flag = $data['session']['employer_detail']['pay_plan_flag'];
            $user_info = array();

            //Removed pay plan 
            $pp_flag = 0;
            //

            switch ($user_type) {
                case 'employee':
                    $data = employee_right_nav($user_sid);
                    $employee_info = $this->onboarding_model->get_employee_information($company_sid, $user_sid);
                    $user_info = array();
                    $user_info['sid'] = $employee_info['sid'];
                    $user_info['first_name'] = $employee_info['first_name'];
                    $user_info['last_name'] = $employee_info['last_name'];
                    $user_info['pictures'] = $employee_info['profile_picture'];
                    $user_info['email'] = $employee_info['email'];
                    $data['employer'] = $employee_info;
                    $custom_office_locations = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'employee', 'location');
                    $data['custom_office_locations'] = $custom_office_locations;
                    $custom_office_timings = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'employee', 'timing', 1);
                    $data['custom_office_timings'] = $custom_office_timings;
                    $custom_office_items = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'employee', 'item');
                    $data['custom_office_items'] = $custom_office_items;
                    $custom_useful_links = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'employee', 'useful_link');
                    $data['custom_useful_links'] = $custom_useful_links;
                    $videos = $this->learning_center_model->get_all_online_videos($company_sid);
                    $data['videos'] = $videos;
                    $welcome_video = $this->onboarding_model->get_onboarding_setup_welcome_video($company_sid, $user_sid, 'employee'); // Welcome Videos
                    $data['welcome_video'] = $welcome_video;
                    $welcome_videos_collection = $this->onboarding_model->get_welcome_videos_collection($company_sid); // Welcome Videos collection
                    $data['welcome_videos_collection'] = $welcome_videos_collection;
                    $sessions = $this->learning_center_model->get_all_training_sessions_new($company_sid);
                    $data['sessions'] = $sessions;
                    $sessions = $this->learning_center_model->get_assigned_online_videos('employee', $user_sid);

                    // disclosure
                    $onboarding_disclosure_data = $this->onboarding_model->get_company_disclosure($company_sid, $user_sid);

                    if (empty($onboarding_disclosure_data)) {
                        $onboarding_disclosure = "<b>Company Disclosure</b>";
                    } else {
                        $onboarding_disclosure = $onboarding_disclosure_data[0]['disclosure'];
                    }


                    $data['onboarding_disclosure'] = $onboarding_disclosure;

                    $video_session = array();

                    if (sizeof($sessions) > 0) {
                        foreach ($sessions as $session) {
                            $video_session[] = $session['learning_center_online_videos_sid'];
                        }
                    }

                    $data['assign_videos'] = $video_session;
                    $sessions = $this->learning_center_model->get_assigned_training_session('employee', $user_sid);
                    $training_session = array();

                    if (sizeof($sessions) > 0) {
                        foreach ($sessions as $session) {
                            $training_session[] = $session['training_session_sid'];
                        }
                    }

                    $data['assign_session'] = $training_session;
                    $onboarding_instructions_data = $this->onboarding_model->get_company_instructions($company_sid, $user_sid);

                    if (empty($onboarding_instructions_data)) {
                        $onboarding_instructions = "<b>Welcome to {{company_name}}</b><p>We are excited to have you join our team.! You are just few steps away from becoming a welcome and valued member of our amazing team.</p><p>Click through and complete each step of the on-boarding steps above to set up your account and complete the HR compliance and On-boarding process.</p><p>These items are time sensitive, so please complete them as soon as possible.</p><p>Please Note: If you are unsure or unable to complete any of the on-boarding steps, please leave them blank and continue with rest of the steps.</p><p>Contact our team with any questions you may have and again we are looking forward to you joining the team.</p>";
                    } else {
                        $onboarding_instructions = $onboarding_instructions_data[0]['instructions'];
                    }

                    $onboarding_instructions = str_replace('{{company_name}}', ucwords($data['session']['company_detail']['CompanyName']), $onboarding_instructions);
                    $data['onboarding_instructions'] = $onboarding_instructions;

                    break;
                case 'applicant':
                    $ats_params = $this->session->userdata('ats_params');
                    $data = applicant_right_nav($user_sid, $job_list_sid, $ats_params);
                    $data['job_list_sid'] = $job_list_sid;
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);
                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $custom_office_locations = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'applicant', 'location');
                    $data['custom_office_locations'] = $custom_office_locations;
                    $custom_office_timings = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'applicant', 'timing');
                    $data['custom_office_timings'] = $custom_office_timings;
                    $custom_office_items = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'applicant', 'item');
                    $data['custom_office_items'] = $custom_office_items;
                    $custom_useful_links = $this->onboarding_model->get_custom_office_records($company_sid, $user_sid, 'applicant', 'useful_link');
                    $data['custom_useful_links'] = $custom_useful_links;
                    $user_exists = $this->onboarding_model->check_if_user_already_exists($user_info['email'], $company_sid);
                    $data['user_exists'] = $user_exists;
                    $videos = $this->learning_center_model->get_all_online_videos($company_sid); // Online Videos
                    $data['videos'] = $videos;
                    $welcome_video = $this->onboarding_model->get_onboarding_setup_welcome_video($company_sid, $user_sid, 'applicant'); // Welcome Videos
                    $data['welcome_video'] = $welcome_video;
                    $welcome_videos_collection = $this->onboarding_model->get_welcome_videos_collection($company_sid); // Welcome Videos collection
                    $data['welcome_videos_collection'] = $welcome_videos_collection;
                    $sessions = $this->learning_center_model->get_all_training_sessions_new($company_sid); // Training Session
                    $data['sessions'] = $sessions;
                    $sessions = $this->learning_center_model->get_assigned_online_videos('applicant', $user_sid); // Assigned Video Session
                    // disclosure
                    $onboarding_disclosure_data = $this->onboarding_model->get_company_disclosure($company_sid, 0, $user_sid);

                    if (empty($onboarding_disclosure_data)) {
                        $onboarding_disclosure = "<b>Company Disclosure</b>";
                    } else {
                        $onboarding_disclosure = $onboarding_disclosure_data[0]['disclosure'];
                    }

                    $data['onboarding_disclosure'] = $onboarding_disclosure;

                    $video_session = array();

                    if (sizeof($sessions) > 0) {
                        foreach ($sessions as $session) {
                            $video_session[] = $session['learning_center_online_videos_sid'];
                        }
                    }

                    $data['assign_videos'] = $video_session;
                    $sessions = $this->learning_center_model->get_assigned_training_session('applicant', $user_sid); // Assigned Training Session
                    $training_session = array();

                    if (sizeof($sessions) > 0) {
                        foreach ($sessions as $session) {
                            $training_session[] = $session['training_session_sid'];
                        }
                    }

                    $data['assign_session'] = $training_session;
                    $onboarding_instructions_data = $this->onboarding_model->get_company_instructions($company_sid, 0, $user_sid);

                    if (empty($onboarding_instructions_data)) {
                        $onboarding_instructions = "<b>Welcome to {{company_name}}</b><p>We are excited to have you join our team.! You are just few steps away from becoming a welcome and valued member of our amazing team.</p><p>Click through and complete each step of the on-boarding steps above to set up your account and complete the HR compliance and On-boarding process.</p><p>These items are time sensitive, so please complete them as soon as possible.</p><p>Please Note: If you are unsure or unable to complete any of the on-boarding steps, please leave them blank and continue with rest of the steps.</p><p>Contact our team with any questions you may have and again we are looking forward to you joining the team.</p>";
                    } else {
                        $onboarding_instructions = $onboarding_instructions_data[0]['instructions'];
                    }

                    $onboarding_instructions = str_replace('{{company_name}}', ucwords($data['session']['company_detail']['CompanyName']), $onboarding_instructions);
                    $data['onboarding_instructions'] = $onboarding_instructions;

                    break;

                default:
                    $this->session->set_flashdata('message', '<strong>Error: </strong> You must specify!');
                    redirect('dashboard', 'refresh');
                    break;
            }

            $sendGroupEmail = 0;
            $assign_group_documents = $this->hr_documents_management_model->get_assign_group_documents($company_sid, $user_type, $user_sid);

            if (!empty($assign_group_documents)) {
                foreach ($assign_group_documents as $key => $assign_group_document) {
                    $is_document_assign = $this->hr_documents_management_model->check_document_already_assigned($company_sid, $user_type, $user_sid, $assign_group_document['document_sid']);

                    if ($is_document_assign == 0 && $assign_group_document['document_sid'] > 0) {
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $assign_group_document['document_sid']);
                        if (!empty($document)) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $assign_group_document['assigned_by_sid'];
                            $data_to_insert['user_type'] = $user_type;
                            $data_to_insert['user_sid'] = $user_sid;
                            $data_to_insert['document_type'] = $document['document_type'];
                            $data_to_insert['document_sid'] = $assign_group_document['document_sid'];
                            $data_to_insert['status'] = 1;
                            $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_insert['document_title'] = $document['document_title'];
                            $data_to_insert['document_description'] = $document['document_description'];
                            $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                            $data_to_insert['signature_required'] = $document['signature_required'];
                            $data_to_insert['download_required'] = $document['download_required'];
                            $data_to_insert['is_confidential'] = $document['is_confidential'];
                            $data_to_insert['confidential_employees'] = $document['confidential_employees'];
                            $data_to_insert['is_required'] = $document['is_required'];
                            $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];
                            $data_to_insert['assign_location'] = "assign_group_from_employee_setup_onboarding";


                            //
                            $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            //
                            if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                                $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $document['document_description']);
                                //
                                if ($isAuthorized == 1) {
                                    // Managers handling
                                    $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                        $document['managers_list'],
                                        $assignment_sid,
                                        $company_sid,
                                        $assign_group_document['assigned_by_sid']
                                    );
                                }
                            }
                            //

                            if ($document['has_approval_flow'] == 1) {
                                $this->HandleApprovalFlow(
                                    $assignment_sid,
                                    $document['document_approval_note'],
                                    $document["document_approval_employees"],
                                    0,
                                    $document['managers_list']
                                );
                            } else {
                                // Managers handling
                                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                    $document['managers_list'],
                                    $assignment_sid,
                                    $company_sid,
                                    $employer_sid
                                );
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }
            }
            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);

            ///get Group Documents 
            //Params  company id , user type , user id , pp flag
            $doc_group_data = $this->hr_documents_management_model->checkAndAssignGroups($company_sid, $user_type, $user_sid, $pp_flag, $employer_sid);
            extract($doc_group_data);
            $data['groups'] = $groups;


            if ($sendGroupEmail == 1 && $user_type == 'employee') {
                //
                $hf = message_header_footer(
                    $company_sid,
                    ucwords($company_name)
                );
                //
                $replacement_array = array();
                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                $replacement_array['baseurl'] = base_url();
                //
                $extra_user_info = array();
                $extra_user_info["user_type"] = $user_type;
                $extra_user_info["user_sid"] = $user_sid;
                $this->load->model('hr_documents_management_model');
                if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS19")) {
                    //
                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS,  $user_info['email'], $replacement_array, $hf, 1, $extra_user_info);
                }
            }

            $all_assigned_sids = array();
            $revoked_sids = array();
            $all_assigned_documents = $this->hr_documents_management_model->get_all_assigned_documents($company_sid, $user_type, $user_sid, 0);

            foreach ($all_assigned_documents as $assigned) {
                if ($assigned['document_type'] != 'offer_letter') {

                    if ($assigned['status'] == 1) {
                        $all_assigned_sids[] = $assigned['document_sid'];
                    } else {
                        $revoked_sids[] = $assigned['document_sid'];
                    }
                }
            }

            $data['user_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($user_sid, $user_type); //getting average rating of applicant
            $access_levels = $this->onboarding_model->get_security_access_levels();
            $data['access_levels'] = $access_levels;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $data['eeo_form_info'] = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                $data['title'] = 'Setup Onboarding';
                $office_locations = $this->onboarding_model->get_all_office_locations($company_sid);
                $data['office_locations'] = $office_locations;
                $office_timings = $this->onboarding_model->get_all_office_timings($company_sid);
                $data['office_timings'] = $office_timings;
                $people_to_meet = $this->onboarding_model->get_all_people_to_meet($company_sid);
                $data['people_to_meet'] = $people_to_meet;
                $what_to_bring = $this->onboarding_model->get_all_what_to_bring_items($company_sid);
                $data['what_to_bring'] = $what_to_bring;
                $useful_links = $this->onboarding_model->get_all_links($company_sid);
                $data['useful_links'] = $useful_links;
                $offer_letters = $this->onboarding_model->get_all_offer_letters($company_sid, $user_sid);
                $data['offer_letters'] = $offer_letters;
                $all_uploaded_generated_doc = $this->hr_documents_management_model->get_uploaded_generated_documents($company_sid, 0, $pp_flag); //Second Param is Archive Status
                $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, $user_type, $user_sid, 1, 0);
                $assign_u_doc_sids = array();
                $assign_g_doc_sids = array();
                $assign_u_doc_urls = array();
                $offer_letter_body = '';
                $assigned_offer_letter_sid = 0;
                $offer_letter_status = 0;
                $assigned_offer_letter_type = '';
                $assign_offer_letter_s3_url = '';
                $assign_offer_letter_name = '';
                $assigned_offer_letter_type = '';
                $offer_letter_iframe_url = '';

                $data['assignedOfferLetter'] = [];

                foreach ($assigned_documents as $assigned_document) {
                    if ($assigned_document['document_type'] == 'uploaded') {
                        $assign_u_doc_sids[] = $assigned_document['document_sid'];
                        $assign_u_doc_urls[$assigned_document['document_sid']] = AWS_S3_BUCKET_URL . $assigned_document['document_s3_name'];
                    }

                    if ($assigned_document['document_type'] == 'generated') {
                        $assign_g_doc_sids[] = $assigned_document['document_sid'];
                    }

                    if ($assigned_document['document_type'] == 'offer_letter') {
                        $data['assignedOfferLetter'] = $assigned_document;
                        $offer_letter_body = $assigned_document['document_description'];
                        $assigned_offer_letter_sid = $assigned_document['document_sid'];
                        $offer_letter_status = $assigned_document['status'];
                        $assigned_offer_letter_type = $assigned_document['offer_letter_type'];
                        $assign_offer_letter_s3_url = $assigned_document['document_s3_name'];
                        $assign_offer_letter_name = $assigned_document['document_original_name'];
                        $offer_letter_iframe_url  = '';

                        if ($assigned_offer_letter_type == "uploaded") {
                            $offer_letter_url         = $assigned_document['document_s3_name'];
                            $offer_letter_file_info   = explode(".", $offer_letter_url);
                            $offer_letter_name        = $offer_letter_file_info[0];
                            $offer_letter_extension   = $offer_letter_file_info[1];

                            if (in_array($offer_letter_extension, ['pdf', 'csv', 'ppt', 'pptx'])) {
                                $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';
                            } else if (in_array($offer_letter_extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                                $offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_url);
                            }
                        }
                    }
                }

                //
                if (sizeof($data['assignedOfferLetter'])) $this->hr_documents_management_model->getManagersListSingle($data['assignedOfferLetter']);

                //
                $data['all_documents'] = $all_uploaded_generated_doc;

                $data['all_uploaded_generated_doc'] = $all_uploaded_generated_doc;
                $data['all_assigned_sids'] = $all_assigned_sids;
                $data['revoked_sids'] = $revoked_sids;
                $data['assigned_documents'] = $assigned_documents;
                $data['assign_u_doc_sids'] = $assign_u_doc_sids;
                $data['assign_g_doc_sids'] = $assign_g_doc_sids;
                $data['offer_letter_body'] = $offer_letter_body;
                $data['offer_letter_status'] = $offer_letter_status;
                $data['assigned_offer_letter_sid'] = $assigned_offer_letter_sid;
                $data['assigned_offer_letter_type'] = $assigned_offer_letter_type;
                $data['assign_offer_letter_s3_url'] = $assign_offer_letter_s3_url;
                $data['assign_offer_letter_name'] = $assign_offer_letter_name;
                $data['assigned_offer_letter_type'] = $assigned_offer_letter_type;
                $data['assign_offer_letter_iframe_url'] = $offer_letter_iframe_url;
                $data['user_info'] = $user_info;
                $data['user_type'] = $user_type;
                $data['user_sid'] = $user_sid;
                $data['company_sid'] = $company_sid;
                $data['company_name'] = $company_name;
                $data['employer_sid'] = $employer_sid;
                $data['EmployeeSid'] = $user_sid;
                $data['Type'] = $user_type;
                $configuration = $this->onboarding_model->get_onboarding_configuration($user_type, $user_sid);
                $onboarding_applicant_info = $this->onboarding_model->get_onboarding_applicants($user_sid);
                $unique_sid = isset($onboarding_applicant_info['unique_sid']) ? $onboarding_applicant_info['unique_sid'] : '';
                $email_sent_date = isset($onboarding_applicant_info['email_sent_date']) && $onboarding_applicant_info['email_sent_date'] != NULL && !empty($onboarding_applicant_info['email_sent_date']) ? date('m-d-Y h:i:s A', strtotime($onboarding_applicant_info['email_sent_date'])) : '';
                $sections_data = $this->get_single_record_from_array($configuration, 'section', 'sections');
                $locations_data = $this->get_single_record_from_array($configuration, 'section', 'locations');
                $timings_data = $this->get_single_record_from_array($configuration, 'section', 'timings');
                $people_data = $this->get_single_record_from_array($configuration, 'section', 'people');
                $items_data = $this->onboarding_model->get_assigned_custom_office_record_sids($company_sid, $user_sid, $user_type, 'item', 0); // fetch items from new table
                $credentials_data = $this->get_single_record_from_array($configuration, 'section', 'credentials');
                $sections = empty($sections_data) ? array() : unserialize($sections_data['items']);
                $locations = empty($locations_data) ? array() : unserialize($locations_data['items']);
                $timings = empty($timings_data) ? array() : unserialize($timings_data['items']);
                $people = empty($people_data) ? array() : unserialize($people_data['items']);
                $items = $this->convert_array_to_1d($items_data);
                $credentials = empty($credentials_data) ? array() : unserialize($credentials_data['items']);
                //
                $data['departmentSid'] = $onboarding_applicant_info['department_sid'];
                $data['teamSid'] = $onboarding_applicant_info['team_sid'];


                if (!isset($credentials['instructions']) || empty($credentials['instructions'])) {
                    $credentials['instructions'] = '<p>Please create your login credentials to access your employee panel</p><p><strong>Suggestion for User Name:</strong><br />You can use your first name and last name all one word all lower case. Example: johnsmith<br />if the username is already taken than you can add any number with it Example: johnsmith123</p><p><strong>Suggestion for Password:</strong><br />Please create secure password with Alpha Numeric and special combination and do not share your password with anyone.</p>';
                } else {
                    $mystring = 'lorem ipsum';
                    $pos = strpos(strtolower($credentials['instructions']), $mystring);

                    if ($pos === false) {
                        // it is clean instructions. no need to change
                    } else {
                        $credentials['instructions'] = '<p>Please create your login credentials to access your employee panel</p><p><strong>Suggestion for User Name:</strong><br />You can use your first name and last name all one word all lower case. Example: johnsmith<br />if the username is already taken than you can add any number with it Example: johnsmith123</p><p><strong>Suggestion for Password:</strong><br />Please create secure password with Alpha Numeric and special combination and do not share your password with anyone.</p>';
                    }
                }

                if (!isset($credentials['access_level'])) {
                    $credentials['access_level'] = 'Employee';
                }

                if (!isset($credentials['joining_date'])) {
                    $credentials['joining_date'] = date('m/d/Y');
                }

                $assign_links = $this->onboarding_model->get_assign_useful_links($user_type, $user_sid, $company_sid);
                $data['sections'] = $sections != null ? $sections : array();
                $data['locations'] = $locations != null ? $locations : array();
                $data['timings'] = $timings != null ? $timings : array();
                $data['people'] = $people != null ? $people : array();
                $data['items'] = $items != null ? $items : array();
                $data['links'] = $assign_links != null ? $assign_links : array();
                $data['credentials'] = $credentials != null ? $credentials : array();
                $data['user_sid'] = $user_sid;
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['company_name'] = $company_name;
                $data['unique_sid'] = $unique_sid;
                $data['email_sent_date'] = $email_sent_date;

                $data['managers_list'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);

                $data['assigned_groups'] = $assigned_groups;
                $w4_form_data = $this->onboarding_model->get_original_w4_form_assigned($user_type, $user_sid); //W4 Form
                $data['w4_form_data'] = $w4_form_data;
                $w9_form_data = $this->onboarding_model->get_w9_form_assigned($user_type, $user_sid); //W9 Form
                $data['w9_form_data'] = $w9_form_data;
                $i9_form_data = $this->onboarding_model->get_i9_form_assigned($user_type, $user_sid); //i9 Form
                $data['i9_form_data'] = $i9_form_data;
                $data['employment_statuses'] = $this->application_tracking_system_model->getEmploymentStatuses();
                $data['employment_types'] = $this->application_tracking_system_model->getEmploymentTypes();
                $data['pp_flag'] = $pp_flag;
                //
                $approval_documents = $this->hr_documents_management_model->get_user_approval_pending_documents($user_type, $user_sid);
                $data['approval_documents'] = array_column($approval_documents, "document_sid");
                $approval_offer_letters = $this->hr_documents_management_model->get_user_approval_pending_offer_letters($user_type, $user_sid);
                $data['approval_offer_letters'] = array_column($approval_offer_letters, "document_sid");
                //
                $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
                $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
                //
                $companyExtraInfo = unserialize($this->session->userdata('logged_in')['company_detail']['extra_info']);
                //
                $data['onboarding_eeo_form_status'] = isset($companyExtraInfo['EEO']) ? $companyExtraInfo['EEO'] : 0;
                //


                /// Group Docs
                $active_groups = array();
                $in_active_groups = array();
                $group_ids = array();
                $group_docs = array();
                $document_ids = array();


                $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);

                if (!empty($groups)) {
                    foreach ($groups as $key => $group) {
                        $document_status = $this->hr_documents_management_model->is_document_assign_2_group($group['sid']);
                        $groups[$key]['document_status'] = $document_status;
                        $group_status = $group['status'];
                        $group_sid = $group['sid'];
                        $group_ids[] = $group_sid;
                        $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 0, $pp_flag);
                        $otherDocuments = getGroupOtherDocuments($group);
                        $otherDocumentCount = count($otherDocuments);

                        if ($group_status) {
                            $active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'direct_deposit' => $group['direct_deposit'],
                                'drivers_license' => $group['drivers_license'],
                                'occupational_license' => $group['occupational_license'],
                                'emergency_contacts' => $group['emergency_contacts'],
                                'dependents' => $group['dependents'],
                                'documents_count' => count($group_documents) + $otherDocumentCount,
                                'documents' => $group_documents,
                                'other_documents' => $otherDocuments
                            );
                        } else {
                            $in_active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'direct_deposit' => $group['direct_deposit'],
                                'drivers_license' => $group['drivers_license'],
                                'occupational_license' => $group['occupational_license'],
                                'emergency_contacts' => $group['emergency_contacts'],
                                'dependents' => $group['dependents'],
                                'documents_count' => count($group_documents) + $otherDocumentCount,
                                'documents' => $group_documents,
                                'other_documents' => $otherDocuments
                            );
                        }
                    }
                }


                if (!empty($group_ids)) {
                    $group_docs = $this->hr_documents_management_model->get_distinct_group_docs($group_ids);
                }

                if (!empty($group_docs)) { // document are assigned to any group.
                    foreach ($group_docs as $group_doc) {
                        $document_ids[] = $group_doc['document_sid'];
                    }
                }

                $data['active_groups'] = $active_groups;
                $data['active_categories'] = $active_categories;
                $data['in_active_groups'] = $in_active_groups;
                $data['groups'] = $groups;
                $data['assigned_sids'] = $all_assigned_sids;



                $data['assigned_documents'] =
                    cleanAssignedDocumentsByPermission(
                        $data['assigned_documents'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );

                $data['uncompleted_payroll_documents'] =
                    cleanAssignedDocumentsByPermission(
                        $data['uncompleted_payroll_documents'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );

                $data['payroll_documents_sids'] =
                    cleanAssignedDocumentsByPermission(
                        $data['payroll_documents_sids'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //
                $data['categories_documents_completed'] =
                    cleanAssignedDocumentsByPermission(
                        $data['categories_documents_completed'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //
                $data['completed_offer_letter'] =
                    cleanAssignedDocumentsByPermission(
                        $data['completed_offer_letter'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //
                $data['completed_payroll_documents'] =
                    cleanAssignedDocumentsByPermission(
                        $data['completed_payroll_documents'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //
                $data['no_action_required_documents'] =
                    cleanAssignedDocumentsByPermission(
                        $data['no_action_required_documents'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //    
                $data['no_action_required_payroll_documents'] =
                    cleanAssignedDocumentsByPermission(
                        $data['no_action_required_payroll_documents'],
                        $data['session']['employer_detail'],
                        $employeeDepartments
                    );
                //

                $confidential_sids = array();
                //
                $confidential_sids =  array_merge($confidential_sids, is_array($data['no_action_required_payroll_documents']) ? array_column($data['no_action_required_payroll_documents'], 'document_sid') : []);
                $confidential_sids =  array_merge($confidential_sids, is_array($data['no_action_required_documents']) ? array_column($data['no_action_required_documents'], 'document_sid') : []);
                $confidential_sids =  array_merge($confidential_sids, is_array($data['assigned_documents']) ? array_column($data['assigned_documents'], 'document_sid') : []);
                $confidential_sids =  array_merge($confidential_sids, is_array($data['completed_offer_letter']) ? array_column($data['completed_offer_letter'], 'document_sid') : []);
                $confidential_sids =  array_merge($confidential_sids, is_array($data['completed_payroll_documents']) ? array_column($data['completed_payroll_documents'], 'document_sid') : []);
                $confidential_sids =  array_merge($confidential_sids, is_array($data['categories_documents_completed']) ? array_column($data['categories_documents_completed'], 'document_sid') : []);
                //
                $confidential_sids = array_flip($confidential_sids);
                $data['confidential_sids'] = $confidential_sids;




                $this->load->view('main/header', $data);
                $this->load->view('onboarding/setup');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'assign_document':
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $check_exist = $this->hr_documents_management_model->check_assigned_document($document_sid, $user_sid, $user_type);

                        if (!empty($check_exist)) {
                            $assignment_sid = $check_exist[0]['sid'];
                            $assigned_document = $this->hr_documents_management_model->get_assigned_document_details($company_sid, $assignment_sid);
                            unset($assigned_document['sid']);
                            $assigned_document['doc_sid'] = $assignment_sid;
                            $this->hr_documents_management_model->insert_documents_assignment_record_history($assigned_document);
                            $data_to_update = array();
                            $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                            $data_to_update['company_sid'] = $company_sid;
                            $data_to_update['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_update['assigned_by'] = $employer_sid;
                            $data_to_update['user_type'] = $user_type;
                            $data_to_update['user_sid'] = $user_sid;
                            $data_to_update['document_type'] = $document_type;
                            $data_to_update['document_sid'] = $document_sid;
                            $data_to_update['status'] = 1;
                            $data_to_update['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_update['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_update['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_update['document_title'] = $document['document_title'];
                            $data_to_update['document_description'] = $this->input->post('document_description');
                            $data_to_update['status'] = 1;
                            $data_to_update['acknowledged'] = NULL;
                            $data_to_update['acknowledged_date'] = NULL;
                            $data_to_update['downloaded'] = NULL;
                            $data_to_update['downloaded_date'] = NULL;
                            $data_to_update['uploaded'] = NULL;
                            $data_to_update['uploaded_date'] = NULL;
                            $data_to_update['uploaded_file'] = NULL;
                            $data_to_update['signature_timestamp'] = NULL;
                            $data_to_update['signature'] = NULL;
                            $data_to_update['signature_email'] = NULL;
                            $data_to_update['signature_ip'] = NULL;
                            $data_to_update['user_consent'] = 0;
                            $data_to_update['submitted_description'] = NULL;
                            $data_to_update['signature_base64'] = NULL;
                            $data_to_update['signature_initial'] = NULL;
                            $data_to_update['is_required'] = $document['is_required'];
                            $data_to_update['fillable_document_slug'] = $document['fillable_document_slug'];
                            $this->hr_documents_management_model->update_documents($assignment_sid, $data_to_update, 'documents_assigned');
                        } else {
                            $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $employer_sid;
                            $data_to_insert['user_type'] = $user_type;
                            $data_to_insert['user_sid'] = $user_sid;
                            $data_to_insert['document_type'] = $document_type;
                            $data_to_insert['document_sid'] = $document_sid;
                            $data_to_insert['status'] = 1;
                            $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_insert['document_title'] = $document['document_title'];
                            $data_to_insert['document_description'] = $this->input->post('document_description');
                            $data_to_insert['is_required'] = $document['is_required'];
                            $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];


                            $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                        }


                        // Managers handling
                        $this->hr_documents_management_model->addManagersToAssignedDocuments(
                            $document['managers_list'],
                            $assignment_sid,
                            $company_sid,
                            $employer_sid
                        );

                        die();
                        break;
                    case 'remove_document':
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        //
                        $assigned = $this->hr_documents_management_model->getAssignedDocumentByIdAndEmployeeId(
                            $document_sid,
                            $user_sid
                        );
                        //
                        $assignInsertId = $assigned['sid'];
                        //
                        unset($assigned['sid']);
                        unset($assigned['is_pending']);
                        //
                        $h = $assigned;
                        $h['doc_sid'] = $assignInsertId;
                        //
                        $this->hr_documents_management_model->insert_documents_assignment_record_history($h);
                        //
                        $data = array();
                        $data['status'] = 0;
                        $this->hr_documents_management_model->assign_revoke_assigned_documents($document_sid, $document_type, $user_sid, $user_type, $data);
                        die();
                        break;
                    case 'assign_w4': //W4 Form Active
                        $already_assigned_w4 = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);

                        if (empty($already_assigned_w4)) {
                            $w4_data_to_insert = array();
                            $w4_data_to_insert['employer_sid'] = $user_sid;
                            $w4_data_to_insert['company_sid'] = $company_sid;
                            $w4_data_to_insert['user_type'] = $user_type;
                            $w4_data_to_insert['sent_status'] = 1;
                            $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w4_data_to_insert['status'] = 1;
                            $w4_data_to_insert['last_assign_by'] = $employer_sid;

                            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                        } else {
                            //
                            $w4_data_to_update                                          = array();
                            $w4_data_to_update['sent_date']                             = date('Y-m-d H:i:s');
                            $w4_data_to_update['status']                                = 1;
                            $w4_data_to_update['signature_timestamp']                   = NULL;
                            $w4_data_to_update['signature_email_address']               = NULL;
                            $w4_data_to_update['signature_bas64_image']                 = NULL;
                            $w4_data_to_update['init_signature_bas64_image']            = NULL;
                            $w4_data_to_update['ip_address']                            = NULL;
                            $w4_data_to_update['user_agent']                            = NULL;
                            $w4_data_to_update['uploaded_file']                         = NULL;
                            $w4_data_to_update['uploaded_by_sid']                       = 0;
                            $w4_data_to_update['user_consent']                          = 0;
                            $w4_data_to_update['last_assign_by'] = $employer_sid;

                            //
                            $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_update);
                        }
                        //
                        $w4_sid = getVerificationDocumentSid($user_sid, $user_type, 'w4');
                        keepTrackVerificationDocument($security_sid, "employee", 'assign', $w4_sid, 'w4', 'Setup Panel');
                        //
                        echo date_format(new DateTime(date('Y-m-d H:i:s')), 'M d Y h:i a');
                        die();
                        break;
                    case 'remove_w4': //W4 Form Deactive
                        $already_assigned_w4 = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
                        //
                        $already_assigned_w4['form_w4_ref_sid'] = $already_assigned_w4['sid'];
                        unset($already_assigned_w4['sid']);
                        $this->hr_documents_management_model->w4_forms_history($already_assigned_w4);
                        //
                        $this->hr_documents_management_model->deactivate_w4_forms($user_type, $user_sid);
                        //
                        $w4_sid = getVerificationDocumentSid($user_sid, $user_type, 'w4');
                        keepTrackVerificationDocument($security_sid, "employee", 'revoke', $w4_sid, 'w4', 'Setup Panel');
                        //
                        echo 'removed';
                        die();
                        break;
                    case 'assign_i9': //I9 Form Active
                        $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);

                        if (empty($already_assigned_i9)) {
                            $i9_data_to_insert = array();
                            $i9_data_to_insert['user_sid'] = $user_sid;
                            $i9_data_to_insert['user_type'] = $user_type;
                            $i9_data_to_insert['company_sid'] = $company_sid;
                            $i9_data_to_insert['sent_status'] = 1;
                            $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $i9_data_to_insert['status'] = 1;
                            $i9_data_to_insert['version'] = getSystemDate('Y');
                            $i9_data_to_insert['last_assign_by'] = $employer_sid;

                            $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                        } else {
                            //
                            $data_to_update = array();
                            $data_to_update["status"] = 1;
                            $data_to_update["sent_status"] = 1;
                            $data_to_update["sent_date"] = date('Y-m-d H:i:s');
                            $data_to_update["section1_emp_signature"] = NULL;
                            $data_to_update["section1_emp_signature_init"] = NULL;
                            $data_to_update["section1_emp_signature_ip_address"] = NULL;
                            $data_to_update["section1_emp_signature_user_agent"] = NULL;
                            $data_to_update["section1_preparer_signature"] = NULL;
                            $data_to_update["section1_preparer_signature_init"] = NULL;
                            $data_to_update["section1_preparer_signature_ip_address"] = NULL;
                            $data_to_update["section1_preparer_signature_user_agent"] = NULL;
                            $data_to_update["user_consent"] = NULL;
                            $data_to_update["s3_filename"] = NULL;
                            $data_to_update["version"] = getSystemDate('Y');
                            $data_to_update["section1_preparer_json"] = NULL;
                            $data_to_update["section3_authorized_json"] = NULL;
                            $data_to_update['last_assign_by'] = $employer_sid;

                            //
                            $this->hr_documents_management_model->reassign_i9_forms($user_type, $user_sid, $data_to_update);
                        }
                        //
                        $i9_sid = getVerificationDocumentSid($user_sid, $user_type, 'i9');
                        keepTrackVerificationDocument($security_sid, "employee", 'assign', $i9_sid, 'i9', 'Setup Panel');
                        //
                        echo date_format(new DateTime(date('Y-m-d H:i:s')), 'M d Y h:i a');
                        die();
                        break;
                    case 'remove_i9': //I9 Form Deactive
                        $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);
                        //
                        $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                        unset($already_assigned_i9['sid']);
                        $this->hr_documents_management_model->i9_forms_history($already_assigned_i9);
                        //
                        $this->hr_documents_management_model->deactivate_i9_forms($user_type, $user_sid);
                        //
                        $i9_sid = getVerificationDocumentSid($user_sid, $user_type, 'i9');
                        keepTrackVerificationDocument($security_sid, "employee", 'revoke', $i9_sid, 'i9', 'Setup Panel');
                        //
                        echo 'removed';
                        die();
                        break;
                    case 'assign_w9': //W4 Form Active
                        $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);

                        if (empty($already_assigned_w9)) {
                            $w9_data_to_insert = array();
                            $w9_data_to_insert['user_sid'] = $user_sid;
                            $w9_data_to_insert['company_sid'] = $company_sid;
                            $w9_data_to_insert['user_type'] = $user_type;
                            $w9_data_to_insert['sent_status'] = 1;
                            $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w9_data_to_insert['status'] = 1;
                            $w9_data_to_insert['last_assign_by'] = $employer_sid;

                            $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                        } else {
                            //
                            $already_assigned_w9 = array();
                            $already_assigned_w9['ip_address'] = NULL;
                            $already_assigned_w9['user_agent'] = NULL;
                            $already_assigned_w9['active_signature'] = NULL;
                            $already_assigned_w9['signature'] = NULL;
                            $already_assigned_w9['user_consent'] = NULL;
                            $already_assigned_w9['signature_timestamp'] = NULL;
                            $already_assigned_w9['signature_email_address'] = NULL;
                            $already_assigned_w9['signature_bas64_image'] = NULL;
                            $already_assigned_w9['init_signature_bas64_image'] = NULL;
                            $already_assigned_w9['signature_ip_address'] = NULL;
                            $already_assigned_w9['signature_user_agent'] = NULL;
                            $already_assigned_w9['sent_date'] = date('Y-m-d H:i:s');
                            $already_assigned_w9['status'] = 1;
                            $already_assigned_w9['uploaded_file'] = NULL;
                            $already_assigned_w9['uploaded_by_sid'] = 0;
                            $already_assigned_w9['last_assign_by'] = $employer_sid;

                            //
                            $this->hr_documents_management_model->activate_w9_forms($user_type, $user_sid, $already_assigned_w9);
                        }
                        //
                        $w9_sid = getVerificationDocumentSid($user_sid, $user_type, 'w9');
                        keepTrackVerificationDocument($security_sid, "employee", 'assign', $w9_sid, 'w9', 'Setup Panel');
                        //
                        echo date_format(new DateTime(date('Y-m-d H:i:s')), 'M d Y h:i a');
                        die();
                        break;
                    case 'remove_w9': //W9 Form Deactive
                        $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);
                        //
                        $already_assigned_w9['w9form_ref_sid'] = $already_assigned_w9['sid'];
                        unset($already_assigned_w9['sid']);
                        $this->hr_documents_management_model->w9_forms_history($already_assigned_w9);
                        //
                        $this->hr_documents_management_model->deactivate_w9_forms($user_type, $user_sid);
                        //
                        $w9_sid = getVerificationDocumentSid($user_sid, $user_type, 'w9');
                        keepTrackVerificationDocument($security_sid, "employee", 'revoke', $w9_sid, 'w9', 'Setup Panel');
                        //
                        echo 'removed';
                        die();
                        break;
                    case 'assign_offer_letter':
                        $offer_letter_sid = $this->input->post('letter_sid');
                        $letter_body = $this->input->post('letter_body');
                        $offer_letter_type = $this->input->post('letter_type');


                        if (!empty($letter_body) || $offer_letter_type == "uploaded") {
                            $offer_letter_title = $this->hr_documents_management_model->get_assigned_offer_letter_title($offer_letter_sid);
                            $letter_name = $offer_letter_title;
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $employer_sid;
                            $data_to_insert['user_type'] = $user_type;
                            $data_to_insert['user_sid'] = $user_sid;
                            $data_to_insert['document_type'] = 'offer_letter';
                            $data_to_insert['document_sid'] = $offer_letter_sid;
                            $data_to_insert['document_title'] = $letter_name;
                            $data_to_insert['offer_letter_type'] = $offer_letter_type;

                            if ($offer_letter_type == "hybrid_document") {
                                $upload_letter_description = $this->onboarding_model->get_assign_offer_letter_info($offer_letter_sid);
                                $data_to_insert['document_description'] = $upload_letter_description['letter_body'];

                                $document_original_name   = $upload_letter_description['uploaded_document_original_name'];
                                $offer_letter_file_info   = explode(".", $document_original_name);
                                $offer_letter_name        = $offer_letter_file_info[0];
                                $offer_letter_extension   = $offer_letter_file_info[1];

                                $data_to_insert['document_original_name'] = $document_original_name;
                                $data_to_insert['document_extension'] = $offer_letter_extension;
                                $data_to_insert['document_s3_name'] = $upload_letter_description['uploaded_document_s3_name'];
                                $data_to_insert['document_description'] = $letter_body;
                            } else if ($offer_letter_type == "uploaded") {
                                $upload_letter_description = $this->onboarding_model->get_assign_offer_letter_info($offer_letter_sid);
                                $data_to_insert['document_description'] = $upload_letter_description['letter_body'];

                                $document_original_name   = $upload_letter_description['uploaded_document_original_name'];
                                $offer_letter_file_info   = explode(".", $document_original_name);
                                $offer_letter_name        = $offer_letter_file_info[0];
                                $offer_letter_extension   = $offer_letter_file_info[1];

                                $data_to_insert['document_original_name'] = $document_original_name;
                                $data_to_insert['document_extension'] = $offer_letter_extension;
                                $data_to_insert['document_s3_name'] = $upload_letter_description['uploaded_document_s3_name'];
                            } else {


                                $data_to_insert['document_description'] = $letter_body;
                                $data_to_insert['document_description'] = $letter_body;
                                // Document Settings - Confidential
                                $data_to_insert['is_confidential'] = isset($_POST['setting_is_confidential']) && $_POST['setting_is_confidential'] == 'on' ? 1 : 0;

                                $data_to_insert['has_approval_flow'] = 0;
                                $data_to_insert['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
                                // Assigner handling
                                if ($_POST['has_approval_flow'] == 'on') {
                                    $data_to_insert['has_approval_flow'] = 1;
                                    $data_to_insert['document_approval_employees'] = isset($_POST['document_approval_employees']) && $_POST['document_approval_employees'] ? $_POST['document_approval_employees'] : '';
                                    $data_to_insert['document_approval_note'] = $_POST['document_approval_note'];
                                }
                            }


                            $already_assigned = $this->onboarding_model->check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, 'offer_letter');

                            if (!empty($already_assigned)) {
                                foreach ($already_assigned as $key => $previous_offer_letter) {
                                    $previous_assigned_sid = $previous_offer_letter['sid'];
                                    $already_moved = $this->onboarding_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                                    if ($already_moved == 'no') {
                                        $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                                        unset($previous_offer_letter['sid']);
                                        $this->onboarding_model->insert_documents_assignment_record_history($previous_offer_letter);
                                    }
                                }
                            }

                            $this->onboarding_model->disable_all_previous_letter($company_sid, $user_type, $user_sid, 'offer_letter');

                            $data_to_insert['status'] = 1;
                            $verification_key = random_key(80);
                            $assignment_sid = $this->onboarding_model->insert_documents_assignment_record($data_to_insert);


                            $this->onboarding_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
                            // Managers handling
                            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                $this->input->post('signers'),
                                $assignment_sid,
                                $company_sid,
                                $employer_sid
                            );
                        }

                        echo 'assigned';
                        die();
                        break;
                    case 'active_revoke_offer_letter':
                        $updated_status = $this->input->post('status');
                        $data = array();
                        $data['status'] = $updated_status;
                        $this->hr_documents_management_model->assign_revoke_assigned_offer_documents('offer_letter', $user_sid, $user_type, $data);
                        die();
                        break;
                    case 'remove_EEOC': //EEOC Form Deactive
                        $this->hr_documents_management_model->deactivate_EEOC_forms($user_type, $user_sid);
                        //
                        $eeoc_sid = getVerificationDocumentSid($user_sid, $user_type, 'eeoc');
                        keepTrackVerificationDocument($security_sid, "employee", 'revoke', $eeoc_sid, 'eeoc', 'Setup Panel');
                        //
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');
                        //
                        if ($user_type == 'employee') {
                            redirectHandler('onboarding/setup/employee/' . $user_sid . '#documents', 'refresh');
                        } else {
                            redirectHandler('onboarding/setup/applicant/' . $user_sid . '#documents', 'refresh');
                        }

                        break;
                    case 'assign_EEOC': //EEOC Form Active
                        $this->hr_documents_management_model->activate_EEOC_forms($user_type, $user_sid);
                        //
                        $eeoc_sid = getVerificationDocumentSid($user_sid, $user_type, 'eeoc');
                        keepTrackVerificationDocument($security_sid, "employee", 'assign', $eeoc_sid, 'eeoc', 'Setup Panel');
                        //
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');
                        //
                        if ($user_type == 'employee') {
                            redirectHandler('onboarding/setup/employee/' . $user_sid . '#documents', 'refresh');
                        } else {
                            redirectHandler('onboarding/setup/applicant/' . $user_sid . '#documents', 'refresh');
                        }
                        break;
                }

                $company_sid = $this->input->post('company_sid');
                $video_session = $this->input->post('video_session');
                $tsession = $this->input->post('tsession');
                $employer_sid = $this->input->post('employer_sid');
                $user_type = $this->input->post('user_type');
                $user_sid = $this->input->post('user_sid');
                $company_name = $this->input->post('company_name');
                $sections = $this->input->post('sections');
                $instructions = $this->input->post('onboarding_instructions');
                $locations = $this->input->post('locations');
                $timings = $this->input->post('timings');
                $people = $this->input->post('people');
                $items = $this->input->post('items');
                $links = $this->input->post('links');
                $u_documents = $this->input->post('all_documents');
                // $g_documents = $this->input->post('g_documents');
                $unique_sid = $this->input->post('unique_sid');
                $job_list_sid = $this->input->post('job_list_sid');
                $credentials_instructions = $this->input->post('credentials_instructions');
                $credentials_access_level = $this->input->post('credentials_access_level');
                $credentials_joining_date = $this->input->post('credentials_joining_date');
                $disclosure = $this->input->post('onboarding_disclosure');

                $job_list_info = $this->onboarding_model->get_job_list_data($job_list_sid);

                if (empty($unique_sid)) {
                    $unique_sid = random_key(80);
                }

                if (!empty($video_session)) {
                    $this->onboarding_model->update_specific_online_videos($user_sid, $user_type, array('status' => 0));

                    foreach ($video_session as $id) {
                        $video_session_data = array();
                        $video_session_data['learning_center_online_videos_sid'] = $id;
                        $video_session_data['user_type'] = $user_type;
                        $video_session_data['user_sid'] = $user_sid;
                        $video_session_data['date_assigned'] = date('Y-m-d H:i:s');
                        $video_session_data['status'] = 1;
                        $check_assignment = $this->onboarding_model->check_already_assign($user_sid, $user_type, $id);

                        if (sizeof($check_assignment) > 0) {
                            $this->onboarding_model->update_assign_online_videos($check_assignment[0]['sid'], array('status' => 1));
                        } else {
                            $video_session_data['watched'] = 0;
                            $add_id = $this->onboarding_model->assign_online_videos($video_session_data);
                        }
                    }
                } else {
                    $this->onboarding_model->unassign_online_videos($user_sid, $user_type, array('status' => 0));
                }

                $this->onboarding_model->save_update_setup_onboarding_instructions($instructions, $user_type, $user_sid, $data['session']['company_detail']['sid'], $data['session']['employer_detail']['sid']);

                $this->onboarding_model->save_update_setup_onboarding_disclosure($disclosure, $user_type, $user_sid, $data['session']['company_detail']['sid'], $data['session']['employer_detail']['sid']);



                if (!empty($tsession)) {
                    $this->onboarding_model->update_specific_training_session($user_sid, $user_type, array('status' => 0));

                    foreach ($tsession as $id) {
                        $tsession_data = array();
                        $tsession_data['training_session_sid'] = $id;
                        $tsession_data['user_type'] = $user_type;
                        $tsession_data['user_sid'] = $user_sid;
                        $tsession_data['date_assigned'] = date('Y-m-d H:i:s');
                        $tsession_data['status'] = 1;
                        $check_assignment = $this->onboarding_model->check_already_assign_training($user_sid, $user_type, $id);

                        if (sizeof($check_assignment) > 0) {
                            $this->onboarding_model->update_assign_training($check_assignment[0]['sid'], array('status' => 1));
                        } else {
                            $add_id = $this->onboarding_model->assign_training($tsession_data);
                        }
                    }
                } else {
                    $this->onboarding_model->unassign_training_session($user_sid, $user_type, array('status' => 0));
                }

                if ($user_type == 'applicant') {

                    $employee_status = $this->input->post('employee-status');
                    $employee_type = $this->input->post('employee-type');
                    $onboarding_data = array();
                    $onboarding_data['company_sid'] = $company_sid;
                    $onboarding_data['employer_sid'] = $employer_sid;
                    $onboarding_data['applicant_sid'] = $user_sid;
                    $onboarding_data['unique_sid'] = $unique_sid;
                    $onboarding_data['onboarding_start_date'] = date('Y-m-d H:i:s');
                    $onboarding_data['onboarding_status'] = 'in_process';
                    $onboarding_data['job_list_sid'] = $job_list_sid;
                    $onboarding_data['job_sid'] = isset($job_list_info['job_sid']) ? $job_list_info['job_sid'] : 0;

                    //
                    $teamId = $this->input->post('teamId');
                    //
                    if ($teamId && $teamId != 0) {
                        $onboarding_data['team_sid'] = $teamId;
                        $onboarding_data['department_sid'] = getDepartmentColumnByTeamId($teamId, 'department_sid');
                    } else {
                        $onboarding_data['team_sid'] = 0;
                        $onboarding_data['department_sid'] = 0;
                    }

                    $this->onboarding_model->mark_applicant_for_onboarding($user_sid);
                    $this->onboarding_model->update_applicant_status_type($user_sid, array('employee_status' => $employee_status, 'employee_type' => $employee_type));
                    $this->onboarding_model->save_onboarding_applicant($company_sid, $user_sid, $onboarding_data);
                }
                $data_to_save = array();
                $data_to_save['company_sid'] = $company_sid;
                $data_to_save['user_type'] = $user_type;
                $data_to_save['user_sid'] = $user_sid;
                $data_to_save['section'] = 'sections';
                $data_to_save['items'] = serialize($sections);
                $this->onboarding_model->save_onboarding_config($company_sid, $user_type, $user_sid, 'sections', $data_to_save);
                $data_to_save['section'] = 'locations';
                $data_to_save['items'] = serialize($locations);
                $this->onboarding_model->save_onboarding_config($company_sid, $user_type, $user_sid, 'locations', $data_to_save);
                $data_to_save['section'] = 'timings';
                $data_to_save['items'] = serialize($timings);
                $this->onboarding_model->save_onboarding_config($company_sid, $user_type, $user_sid, 'timings', $data_to_save);
                $data_to_save['section'] = 'people';
                $data_to_save['items'] = serialize($people);
                $this->onboarding_model->save_onboarding_config($company_sid, $user_type, $user_sid, 'people', $data_to_save);
                //disable all default record before insert new record
                $this->onboarding_model->disable_default_record($company_sid, $user_type, $user_sid, 'item');

                if (!empty($items)) {
                    foreach ($items as $key => $item) {
                        $target_item = $this->onboarding_model->get_target_what_to_bring_item($item);
                        $item_title = $target_item['item_title'];
                        $item_discription = $target_item['item_description'];
                        $item_data_to_save = array();
                        $item_data_to_save['parent_sid'] = $item;
                        $item_data_to_save['custom_type'] = 'item';
                        $item_data_to_save['company_sid'] = $company_sid;
                        $item_data_to_save['user_type'] = $user_type;

                        if ($user_type == 'employee') {
                            $item_data_to_save['employee_sid'] = $user_sid;
                        } else {
                            $item_data_to_save['applicant_sid'] = $user_sid;
                        }

                        $item_data_to_save['item_title'] = $item_title;
                        $item_data_to_save['item_description'] = $item_discription;
                        $item_data_to_save['status'] = 1;
                        $item_present = $this->onboarding_model->default_record_item_exist($company_sid, $user_type, $user_sid, 'item', $item);

                        if ($item_present == true) {
                            $this->onboarding_model->enable_default_record($company_sid, $user_type, $user_sid, 'item', $item);
                        } else {
                            $record_sid = $this->onboarding_model->custom_assignment_insert_data($item_data_to_save);
                        }
                    }
                }

                if (!empty($links)) {
                    $this->onboarding_model->configuration_link_update_status($user_sid, $company_sid, $user_type);

                    foreach ($links as $key => $value) {
                        $link_sid = $this->input->post('link-sid-' . $value);
                        $link_data_arr = array();
                        $link_data_arr['link_sid'] = $link_sid;
                        $link_data_arr['link_title'] = $this->input->post('sid-' . $value . '-title');
                        $link_data_arr['link_description'] = $this->input->post('sid-' . $value . '-description');
                        $link_data_arr['link_url'] = $this->input->post('sid-' . $value . '-url');
                        $link_data_arr['link_status'] = $this->input->post('sid-' . $value . '-status');
                        $link_data_arr['assign_status'] = 1;
                        $link_data_arr['company_sid'] = $company_sid;
                        $link_data_arr['user_type'] = $user_type;
                        $is_assign_link = $this->onboarding_model->is_assign_link_configuration($link_sid, $user_sid, $company_sid, $user_type);

                        if ($is_assign_link) {
                            $update_links = array();
                            $update_links['link_description'] = $this->input->post('sid-' . $value . '-description');
                            $update_links['assign_status'] = 1;
                            $this->onboarding_model->active_useful_configuration_link($link_sid, $user_sid, $company_sid, $user_type, $update_links);
                        } else {
                            if ($user_type == 'applicant') {
                                $link_data_arr['applicant_sid'] = $user_sid;
                            } else {
                                $link_data_arr['employee_sid'] = $user_sid;
                            }
                            $this->onboarding_model->save_onboarding_links_config($link_data_arr);
                        }
                    }
                } else {
                    $this->onboarding_model->configuration_link_update_status($user_sid, $company_sid, $user_type);
                }

                if ($user_type == 'applicant') {
                    $data_to_save['section'] = 'credentials';
                    $credentials = array();
                    $credentials['instructions'] = htmlentities($credentials_instructions);
                    $credentials['access_level'] = $credentials_access_level;
                    $credentials['joining_date'] = $credentials_joining_date;
                    $data_to_save['items'] = serialize($credentials);
                    $this->onboarding_model->save_onboarding_config($company_sid, $user_type, $user_sid, 'credentials', $data_to_save);
                }

                // Added on: 14-05-2019
                // if ($user_type == 'applicant')
                // $this->send_email_to_onboarding_applicant($company_sid, $company_name, $user_sid, $unique_sid);

                if ($user_type == 'applicant') { //Initialize Progress
                    $this->onboarding_model->initialize_section_status_information($company_sid, $user_type, $user_sid);
                    $this->session->set_flashdata('message', 'Applicant Successfully Setup for Onboarding!');
                    redirect('onboarding/setup/applicant/' . $user_sid . '/' . $job_list_sid, 'refresh');
                } else if ($user_type == 'employee') {
                    $this->session->set_flashdata('message', 'Employee Panel Successfully Configured!');
                    redirect('onboarding/setup/employee/' . $user_sid, 'refresh');
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function send_offer_letter($user_type, $user_sid, $job_list_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data = array();
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            $user_info = array();
            $offer_letter_body = '';
            $offer_letter_status = 0;
            $assigned_offer_letter_sid = 0;
            $assigned_offer_letter_type = 'generated';
            $offer_letter_iframe_url = '';
            $user_assigned_offer_letter_sid = 0;
            $assign_offer_letter_s3_url = '';
            $assign_offer_letter_name = '';

            $offer_letters = $this->onboarding_model->get_all_offer_letters($company_sid, $user_sid);

            if ($user_type == 'applicant') {
                $user_info = $this->onboarding_model->get_applicant_information($user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $assigned_offer_letter = $this->onboarding_model->get_assigned_offer_letter($company_sid, $user_type, $user_sid);

                if (!empty($assigned_offer_letter)) {
                    $offer_letter_body = $assigned_offer_letter['document_description'];
                    $user_assigned_offer_letter_sid = $assigned_offer_letter['sid'];
                    $assigned_offer_letter_sid = $assigned_offer_letter['document_sid'];
                    $offer_letter_status = $assigned_offer_letter['status'];
                    $assigned_offer_letter_type = $assigned_offer_letter['offer_letter_type'];
                    $assign_offer_letter_s3_url = $assigned_offer_letter['document_s3_name'];
                    $assign_offer_letter_name = $assigned_offer_letter['document_original_name'];

                    if ($assigned_offer_letter_type == "uploaded") {

                        $offer_letter_iframe_url  = '';
                        $offer_letter_url         = $assigned_offer_letter['document_s3_name'];
                        $offer_letter_file_info   = explode(".", $offer_letter_url);
                        $offer_letter_name        = $offer_letter_file_info[0];
                        $offer_letter_extension   = $offer_letter_file_info[1];

                        if (in_array($offer_letter_extension, ['pdf', 'csv', 'ppt', 'pptx'])) {
                            $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';
                        } else if (in_array($offer_letter_extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                            $offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_url);
                        }
                    }
                }

                $ats_params = $this->session->userdata('ats_params');
                $data = applicant_right_nav($user_sid, $job_list_sid, $ats_params);
                $data['job_list_sid'] = $job_list_sid;
                $data['user_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($user_sid, $user_type);
            } else if ($user_type == 'employee') {
                $user_info = $this->onboarding_model->get_employee_info($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> User not found!');
                    redirect('employee_management', 'refresh');
                }

                $assigned_offer_letter = $this->onboarding_model->get_assigned_offer_letter($company_sid, $user_type, $user_sid);

                if (!empty($assigned_offer_letter)) {
                    $offer_letter_body = $assigned_offer_letter['document_description'];
                    $user_assigned_offer_letter_sid = $assigned_offer_letter['sid'];
                    $assigned_offer_letter_sid = $assigned_offer_letter['document_sid'];
                    $offer_letter_status = $assigned_offer_letter['status'];
                    $assigned_offer_letter_type = $assigned_offer_letter['offer_letter_type'];
                    $assign_offer_letter_s3_url = $assigned_offer_letter['document_s3_name'];
                    $assign_offer_letter_name = $assigned_offer_letter['document_original_name'];

                    if ($assigned_offer_letter_type == "uploaded") {

                        $offer_letter_iframe_url  = '';
                        $offer_letter_url         = $assigned_offer_letter['document_s3_name'];
                        $offer_letter_file_info   = explode(".", $offer_letter_url);
                        $offer_letter_name        = $offer_letter_file_info[0];
                        $offer_letter_extension   = $offer_letter_file_info[1];

                        if (in_array($offer_letter_extension, ['pdf', 'csv'])) {
                            $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';
                        } else if (in_array($offer_letter_extension, ['doc', 'docx'])) {
                            $offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_url);
                        }
                    }
                }
                // _e($assigned_offer_letter, true, true);

                $data = employee_right_nav($user_sid);
                $employee_info = $this->onboarding_model->get_employee_information($company_sid, $user_sid);
                $user_info = array();
                $user_info['sid'] = $employee_info['sid'];
                $user_info['first_name'] = $employee_info['first_name'];
                $user_info['last_name'] = $employee_info['last_name'];
                $user_info['pictures'] = $employee_info['profile_picture'];
                $user_info['email'] = $employee_info['email'];
                $user_info['verification_key'] = $employee_info['emp_offer_letter_key'];
                $data['employer'] = $employee_info;
            }

            $data['assignedOfferLetter'] = sizeof($assigned_offer_letter) ? $assigned_offer_letter : [];

            $data['managers_list'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            // Get departments & teams
            $data['departments'] = $this->hr_documents_management_model->getDepartments($company_sid);
            $data['teams'] = $this->hr_documents_management_model->getTeams($company_sid, $data['departments']);
            $data['title'] = 'Assign Offer Letter / Pay Plan';
            $data['user_sid'] = $user_sid;
            $data['user_type'] = $user_type;
            $data['user_info'] = $user_info;
            $data['offer_letters'] = $offer_letters;
            $data['offer_letter_body'] = $offer_letter_body;
            $data['offer_letter_status'] = $offer_letter_status;
            $data['assigned_offer_letter_sid'] = $assigned_offer_letter_sid;
            $data['assigned_offer_letter_type'] = $assigned_offer_letter_type;
            $data['assign_offer_letter_s3_url'] = $assign_offer_letter_s3_url;
            $data['assign_offer_letter_name'] = $assign_offer_letter_name;
            $data['user_assigned_offer_letter_sid'] = $user_assigned_offer_letter_sid;
            $data['assign_offer_letter_iframe_url'] = $offer_letter_iframe_url;
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;
            $data['EmployeeSid'] = $user_sid;
            $data['Type'] = $user_type;

            // Approval Flow
            $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, '');

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('onboarding/send_offer_letter');
                $this->load->view('main/footer');
            } else {


                $perform_action = $this->input->post('perform_action');
                $offer_letter_sid = $this->input->post('offer_letter_select');
                $letter_body = $this->input->post('letter_body');
                $offer_letter_type = $this->input->post('letter_type');
                //
                $post = $this->input->post(NULL, TRUE);
                //
                $do_descpt = '';

                if (!empty($letter_body) || $offer_letter_type == "uploaded") {
                    $offer_letter_title = $this->hr_documents_management_model->get_assigned_offer_letter_title($offer_letter_sid);

                    $letter_name = $offer_letter_title;
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['assigned_by'] = $employer_sid;
                    $data_to_insert['user_type'] = $user_type;
                    $data_to_insert['user_sid'] = $user_sid;
                    $data_to_insert['document_type'] = 'offer_letter';
                    $data_to_insert['document_sid'] = $offer_letter_sid;
                    $data_to_insert['document_title'] = $letter_name;
                    $data_to_insert['offer_letter_type'] = $offer_letter_type;

                    if ($offer_letter_type == "hybrid_document") {
                        $upload_letter_description = $this->onboarding_model->get_assign_offer_letter_info($offer_letter_sid);
                        $data_to_insert['document_description'] = $upload_letter_description['letter_body'];

                        $document_original_name   = $upload_letter_description['uploaded_document_original_name'];
                        $offer_letter_file_info   = explode(".", $document_original_name);
                        $offer_letter_name        = $offer_letter_file_info[0];
                        $offer_letter_extension   = $offer_letter_file_info[1];

                        $data_to_insert['document_original_name'] = $document_original_name;
                        $data_to_insert['document_extension'] = $offer_letter_extension;
                        $data_to_insert['document_s3_name'] = $upload_letter_description['uploaded_document_s3_name'];
                        $data_to_insert['document_description'] = $letter_body;
                        $do_descpt = $letter_body;
                    } else if ($offer_letter_type == "uploaded") {
                        $upload_letter_description = $this->onboarding_model->get_assign_offer_letter_info($offer_letter_sid);
                        $data_to_insert['document_description'] = $upload_letter_description['letter_body'];

                        $document_original_name   = $this->input->post('document_original_name');
                        $offer_letter_file_info   = explode(".", $document_original_name);
                        $offer_letter_name        = $offer_letter_file_info[0];
                        $offer_letter_extension   = $offer_letter_file_info[1];

                        $data_to_insert['document_original_name'] = $document_original_name;
                        $data_to_insert['document_extension'] = $offer_letter_extension;
                        $data_to_insert['document_s3_name'] = $this->input->post('document_s3_name');
                    } else {
                        $data_to_insert['document_description'] = $letter_body;
                        $do_descpt = $letter_body;
                    }

                    $already_assigned = $this->onboarding_model->check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, 'offer_letter');

                    if (!empty($already_assigned)) {
                        foreach ($already_assigned as $key => $previous_offer_letter) {
                            $previous_assigned_sid = $previous_offer_letter['sid'];
                            $already_moved = $this->onboarding_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                            if ($already_moved == 'no') {
                                $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                                unset($previous_offer_letter['sid']);
                                $this->onboarding_model->insert_documents_assignment_record_history($previous_offer_letter);
                            }
                            //
                            $this->onboarding_model->delete_from_oirignal_table($previous_assigned_sid);
                        }
                    }
                    //
                    $this->onboarding_model->disable_all_previous_letter($company_sid, $user_type, $user_sid, 'offer_letter');

                    $data_to_insert['status'] = 1;
                    //
                    if (isset($post['visible_to_payroll'])) {
                        $data_to_insert['visible_to_payroll'] = $post['visible_to_payroll'];
                    }
                    //
                    if (isset($post['roles'])) {
                        $data_to_insert['allowed_roles'] = implode(',', $post['roles']);
                    }
                    //
                    if (isset($post['employees'])) {
                        $data_to_insert['allowed_employees'] = implode(',', $post['employees']);
                    }
                    //
                    if (isset($post['departments'])) {
                        $data_to_insert['allowed_departments'] = implode(',', $post['departments']);
                    }
                    //
                    if (isset($post['teams'])) {
                        $data_to_insert['allowed_teams'] = implode(',', $post['teams']);
                    }
                    //

                    // Document Settings - Confidential
                    $data_to_insert['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                    //
                    $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                    //
                    $data_to_insert['confidential_employees'] = NULL;
                    //
                    if ($post['confidentialSelectedEmployees']) {
                        $data_to_insert['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                    }

                    // Assigner handling
                    $data_to_insert['has_approval_flow'] = 0;
                    $data_to_insert['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
                    // Assigner handling
                    if ($post['has_approval_flow'] == 'on') {
                        $data_to_insert['has_approval_flow'] = 1;
                        $data_to_insert['document_approval_note'] = $post['approvers_note'];
                        $data_to_insert['document_approval_employees'] = isset($post['approvers_list']) && $post['approvers_list'] ? implode(',', $post['approvers_list']) : '';
                    }

                    $verification_key = random_key(80);
                    $assignOfferLetterId = $this->onboarding_model->insert_documents_assignment_record($data_to_insert);
                    $this->onboarding_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
                    //
                    $signers = $this->input->post('js-signers');
                    //
                    if ($data_to_insert['has_approval_flow'] == 1) {

                        $managersList = '';

                        //
                        if ($do_descpt && isset($signers) && $signers != null && str_replace('{{authorized_signature}}', '', $do_descpt) != $do_descpt) {

                            $managersList =  implode(',', $signers);
                        }
                        //
                        $approvers_list = $data_to_insert['document_approval_employees']; //isset($post['assigner']) ? implode(',', $post['assigner']) : "";
                        $approvers_note = $data_to_insert['document_approval_note']; //isset($post['assigner_note']) ? $post['assigner_note'] : "";
                        //

                        $this->HandleApprovalFlow(
                            $assignOfferLetterId,
                            $approvers_note,
                            $approvers_list,
                            $post['sendEmail'],
                            $managersList
                        );
                    } else {

                        //
                        if ($do_descpt && isset($signers) && $signers != null && str_replace('{{authorized_signature}}', '', $do_descpt) != $do_descpt) {
                            // Managers handling
                            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                $signers,
                                $assignOfferLetterId,
                                $company_sid,
                                $employer_sid
                            );
                            //
                            $managersList =  implode(',', $signers);
                            //
                            $this->hr_documents_management_model->change_document_approval_status(
                                $assignOfferLetterId,
                                [
                                    'managersList' => $managersList
                                ]
                            );
                        }
                        //
                        if ($perform_action == 'save_and_send_offer_letter') {
                            $applicant_sid = $user_info['sid'];
                            $applicant_email = $user_info['email'];
                            $applicant_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

                            if ($user_type == 'applicant') {
                                $url = base_url() . 'onboarding/my_offer_letter/' . $verification_key;
                            } else {
                                $url = base_url() . 'onboarding/my_offer_letter/' . $verification_key . '/e';
                            }

                            $emailTemplateBody = 'Dear ' . $applicant_name . ', <br>';
                            $emailTemplateBody = $emailTemplateBody . '<strong>Congratulations and Welcome to ' . $company_name . '</strong>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . 'We have attached an offer letter with this email for you.' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . 'Please complete this offer letter by clicking on the link below.' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Offer Letter</a>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<em>If you have any questions at all, please feel free to send us a note at any time and we will get back to you as quickly as we can.</em>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<strong>The HR Team at ' . $company_name . '</strong>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email; Please Do Not reply!</strong>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                            $from = FROM_EMAIL_NOTIFICATIONS;
                            $to = $applicant_email;
                            $subject = 'Offer Letter';
                            $from_name = ucwords(STORE_DOMAIN);
                            $email_hf = message_header_footer_domain($company_sid, $company_name);
                            $body = $email_hf['header']
                                . $emailTemplateBody
                                . $email_hf['footer'];
                            sendMail($from, $to, $subject, $body, $from_name);
                            $this->session->set_flashdata('message', '<strong>Success: </strong> Offer letter / Pay plan assigned and sent successfully!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Success: </strong> Offer letter / Pay plan assigned successfully!');
                        }
                    }
                }



                redirect('onboarding/send_offer_letter/' . $user_type . '/' . $user_sid, 'refresh');
                if ($user_type == 'applicant') {
                    redirect('applicant_profile/' . $user_sid . '/' . $job_list_sid, 'refresh');
                } else {
                    redirect('employee_profile/' . $user_sid, 'refresh');
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function view_offer_letter($user_type, $user_sid, $job_list_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data = array();
            $user_info = array();
            $offer_letter = array();
            $offer_letter_sort_history = array();

            if ($user_type == 'applicant') {
                $user_info = $this->onboarding_model->get_applicant_information($user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $assigned_offer_letter = $this->onboarding_model->get_assigned_and_signed_offer_letter($company_sid, $user_type, $user_sid);
                $offer_letter_history = $this->onboarding_model->get_signed_offer_letter_from_history($company_sid, $user_type, $user_sid);

                $ats_params = $this->session->userdata('ats_params');
                $data = applicant_right_nav($user_sid, $job_list_sid, $ats_params);
                $applicant_offer_letters = array();

                if (!empty($assigned_offer_letter)) {
                    $letter_type = $assigned_offer_letter['offer_letter_type'];
                    $applicant_offer_letters[0]['letter_status']        = 'Current';
                    $applicant_offer_letters[0]['sid']                  = $assigned_offer_letter['sid'];
                    $applicant_offer_letters[0]['letter_sid']           = $assigned_offer_letter['sid'];
                    $applicant_offer_letters[0]['letter_title']         = $assigned_offer_letter['document_title'];
                    $applicant_offer_letters[0]['letter_type']          = $letter_type;
                    $applicant_offer_letters[0]['assigned_date']        = $assigned_offer_letter['assigned_date'];
                    $applicant_offer_letters[0]['uploaded_date']        = $assigned_offer_letter['uploaded_date'];
                    $applicant_offer_letters[0]['document_sid']         = $assigned_offer_letter['document_sid'];
                    $applicant_offer_letters[0]['archive']              = $assigned_offer_letter['archive'];
                    $applicant_offer_letters[0]['visible_to_payroll']   = $assigned_offer_letter['visible_to_payroll'];
                    $applicant_offer_letters[0]['archive']              = $assigned_offer_letter['archive'];
                    $applicant_offer_letters[0]['acknowledged_date']    = $assigned_offer_letter['acknowledged_date'];
                    $applicant_offer_letters[0]['downloaded_date']      = $assigned_offer_letter['downloaded_date'];
                    $applicant_offer_letters[0]['signature_timestamp']  = $assigned_offer_letter['signature_timestamp'];

                    if ($letter_type == 'uploaded') {
                        $applicant_offer_letters[0]['uploaded_file'] = $assigned_offer_letter['uploaded_file'];
                    } else {
                        $applicant_offer_letters[0]['document_description'] = $record['document_description'];
                        $applicant_offer_letters[0]['submitted_description'] = $assigned_offer_letter['submitted_description'];
                    }
                }

                if (!empty($offer_letter_history)) {
                    foreach ($offer_letter_history as $key => $record) {
                        if (!empty($record['signature_timestamp']) && $record['offer_letter_type'] != 'uploaded') {
                            $offer_letter_history[$key]['sort_date'] = date('m-d-Y', strtotime($record['signature_timestamp']));
                        } else if (!empty($record['uploaded_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['uploaded_date']));
                        } else if (!empty($record['acknowledged_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['acknowledged_date']));
                        } else if (!empty($record['downloaded_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['downloaded_date']));
                        }
                    }

                    usort($offer_letter_history, function ($a, $b) {
                        return $a['sort_date'] < $b['sort_date'];
                    });

                    foreach ($offer_letter_history as $key => $record) {
                        $letter_type = $record['offer_letter_type'];
                        $applicant_offer_letters[$key + 1]['letter_status']         = 'Previous';
                        $applicant_offer_letters[$key + 1]['sid']                   = $record['sid'];
                        $applicant_offer_letters[$key + 1]['letter_sid']            = $record['doc_sid'];
                        $applicant_offer_letters[$key + 1]['letter_title']          = $record['document_title'];
                        $applicant_offer_letters[$key + 1]['letter_type']           = $letter_type;
                        $applicant_offer_letters[$key + 1]['assigned_date']         = $record['assigned_date'];
                        $applicant_offer_letters[$key + 1]['uploaded_date']         = $record['uploaded_date'];
                        $applicant_offer_letters[$key + 1]['document_sid']          = $record['document_sid'];
                        $applicant_offer_letters[$key + 1]['archive']               = $record['archive'];
                        $applicant_offer_letters[$key + 1]['visible_to_payroll']    = $record['visible_to_payroll'];
                        $applicant_offer_letters[$key + 1]['acknowledged_date']     = $record['acknowledged_date'];
                        $applicant_offer_letters[$key + 1]['downloaded_date']       = $record['downloaded_date'];
                        $applicant_offer_letters[$key + 1]['signature_timestamp']   = $record['signature_timestamp'];
                        $applicant_offer_letters[$key + 1]['archive']               = $record['archive'];

                        if ($letter_type == 'uploaded') {
                            $applicant_offer_letters[$key + 1]['uploaded_file'] = $record['uploaded_file'];
                        } else {
                            $applicant_offer_letters[$key + 1]['document_description'] = $record['document_description'];
                            $applicant_offer_letters[$key + 1]['submitted_description'] = $record['submitted_description'];
                        }
                    }
                }

                usort($applicant_offer_letters, function ($a, $b) {
                    return $a['signature_timestamp'] < $b['signature_timestamp'];
                });

                $data['user_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($user_sid, $user_type);
            } else if ($user_type == 'employee') {
                $user_info = $this->onboarding_model->get_employee_info($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> User not found!');
                    redirect('employee_management', 'refresh');
                }

                $data = employee_right_nav($user_sid);
                $employee_info = $this->onboarding_model->get_employee_information($company_sid, $user_sid);
                $user_info = array();
                $user_info['sid'] = $employee_info['sid'];
                $user_info['first_name'] = $employee_info['first_name'];
                $user_info['last_name'] = $employee_info['last_name'];
                $user_info['pictures'] = $employee_info['profile_picture'];
                $user_info['email'] = $employee_info['email'];
                $user_info['emp_offer_letter_key'] = $employee_info['emp_offer_letter_key'];
                $data['employer'] = $employee_info;
                $assigned_offer_letter = $this->onboarding_model->get_assigned_and_signed_offer_letter($company_sid, $user_type, $user_sid);
                $offer_letter_history = $this->onboarding_model->get_signed_offer_letter_from_history($company_sid, $user_type, $user_sid);
                $applicant_offer_letters = array();

                if (!empty($assigned_offer_letter)) {
                    $letter_type = $assigned_offer_letter['offer_letter_type'];
                    $applicant_offer_letters[0]['letter_status']        = 'Current';
                    $applicant_offer_letters[0]['sid']                  = $assigned_offer_letter['sid'];
                    $applicant_offer_letters[0]['letter_sid']           = $assigned_offer_letter['sid'];
                    $applicant_offer_letters[0]['letter_title']         = $assigned_offer_letter['document_title'];
                    $applicant_offer_letters[0]['letter_type']          = $letter_type;
                    $applicant_offer_letters[0]['assigned_date']        = $assigned_offer_letter['assigned_date'];
                    $applicant_offer_letters[0]['uploaded_date']        = $assigned_offer_letter['uploaded_date'];
                    $applicant_offer_letters[0]['document_sid']         = $assigned_offer_letter['document_sid'];
                    $applicant_offer_letters[0]['archive']              = $assigned_offer_letter['archive'];
                    $applicant_offer_letters[0]['visible_to_payroll']   = $assigned_offer_letter['visible_to_payroll'];
                    $applicant_offer_letters[0]['acknowledged_date']    = $assigned_offer_letter['acknowledged_date'];
                    $applicant_offer_letters[0]['downloaded_date']      = $assigned_offer_letter['downloaded_date'];
                    $applicant_offer_letters[0]['signature_timestamp']  = $assigned_offer_letter['signature_timestamp'];
                    $applicant_offer_letters[0]['archive']              = $assigned_offer_letter['archive'];

                    if ($letter_type == 'hybrid_document') {
                        $applicant_offer_letters[0]['uploaded_file'] = $assigned_offer_letter['uploaded_file'] == '' ? $assigned_offer_letter['document_s3_name'] : $assigned_offer_letter['uploaded_file'];
                        $applicant_offer_letters[0]['submitted_description'] = $assigned_offer_letter['submitted_description'];
                    } else if ($letter_type == 'uploaded') {
                        $applicant_offer_letters[0]['uploaded_file'] = $assigned_offer_letter['uploaded_file'];
                    } else {
                        $applicant_offer_letters[0]['document_description'] = $assigned_offer_letter['document_description'];
                        $applicant_offer_letters[0]['submitted_description'] = $assigned_offer_letter['submitted_description'];
                    }
                }

                if (!empty($offer_letter_history)) {

                    foreach ($offer_letter_history as $key => $record) {
                        if (!empty($record['signature_timestamp']) && $record['offer_letter_type'] != 'uploaded') {
                            $offer_letter_history[$key]['sort_date'] = date('m-d-Y', strtotime($record['signature_timestamp']));
                        } else if (!empty($record['uploaded_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['uploaded_date']));
                        } else if (!empty($record['acknowledged_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['acknowledged_date']));
                        } else if (!empty($record['downloaded_date'])) {
                            $offer_letter_history[$key]['sort_date']  = date('m-d-Y', strtotime($record['downloaded_date']));
                        }
                    }

                    usort($offer_letter_history, function ($a, $b) {
                        return $a['sort_date'] < $b['sort_date'];
                    });

                    foreach ($offer_letter_history as $key => $record) {
                        $letter_type = $record['offer_letter_type'];
                        $applicant_offer_letters[$key + 1]['letter_status']         = 'Previous';
                        $applicant_offer_letters[$key + 1]['sid']                   = $record['sid'];
                        $applicant_offer_letters[$key + 1]['letter_sid']            = $record['doc_sid'];
                        $applicant_offer_letters[$key + 1]['letter_title']          = $record['document_title'];
                        $applicant_offer_letters[$key + 1]['letter_type']           = $letter_type;
                        $applicant_offer_letters[$key + 1]['assigned_date']         = $record['assigned_date'];
                        $applicant_offer_letters[$key + 1]['uploaded_date']         = $record['uploaded_date'];
                        $applicant_offer_letters[$key + 1]['document_sid']          = $record['document_sid'];
                        $applicant_offer_letters[$key + 1]['archive']               = $record['archive'];
                        $applicant_offer_letters[$key + 1]['visible_to_payroll']    = $record['visible_to_payroll'];
                        $applicant_offer_letters[$key + 1]['acknowledged_date']     = $record['acknowledged_date'];
                        $applicant_offer_letters[$key + 1]['downloaded_date']       = $record['downloaded_date'];
                        $applicant_offer_letters[$key + 1]['signature_timestamp']   = $record['signature_timestamp'];
                        $applicant_offer_letters[$key + 1]['archive']               = $record['archive'];

                        if ($letter_type == 'hybrid_document') {
                            $applicant_offer_letters[$key + 1]['uploaded_file'] = $record['uploaded_file'] == '' ? $record['document_s3_name'] : $record['uploaded_file'];
                            $applicant_offer_letters[$key + 1]['submitted_description'] = $record['submitted_description'];
                        } else if ($letter_type == 'uploaded') {
                            $applicant_offer_letters[$key + 1]['uploaded_file'] = $record['uploaded_file'];
                        } else {
                            $applicant_offer_letters[$key + 1]['document_description'] = $record['document_description'];
                            $applicant_offer_letters[$key + 1]['submitted_description'] = $record['submitted_description'];
                        }
                    }
                }

                usort($applicant_offer_letters, function ($a, $b) {
                    return $a['signature_timestamp'] < $b['signature_timestamp'];
                });
            }

            $data['user_info'] = $user_info;
            $data['title'] = 'View Offer Letter / Pay Plan';
            $data['user_sid'] = $user_sid;
            $data['user_type'] = $user_type;
            $data['job_list_sid'] = $job_list_sid;

            //
            if (count($applicant_offer_letters)) {
                foreach ($applicant_offer_letters as $key => $letter) {
                    //
                    $applicant_offer_letters[$key]['signed_on'] = '';
                    //
                    if (isset($letter['signature_timestamp']) && $letter['signature_timestamp'] != '0000-00-00 00:00:00') {
                        $applicant_offer_letters[$key]['signed_on'] = $letter['signature_timestamp'];
                    } else if ($letter['letter_type'] == 'uploaded') {
                        if (isset($letter['uploaded_date']) && $letter['uploaded_date'] != '0000-00-00 00:00:00') {
                            $applicant_offer_letters[$key]['signed_on'] = $letter['uploaded_date'];
                        }
                    }
                }
                //
                function r_sort($a, $b)
                {
                    return $a['signed_on'] < $b['signed_on'];
                }
                //
                usort($applicant_offer_letters, 'r_sort');
            }

            if (empty($applicant_offer_letters)) {
                $is_assign = $this->onboarding_model->is_offer_letter_assign($company_sid, $user_type, $user_sid);
                $data['is_assign'] = $is_assign;
            } else {
                // echo '<pre>';
                // print_r($offer_letter_sort_history);
                // usort($offer_letter_sort_history, function($a, $b) {
                //     return $a['date'] < $b['date'];
                // });
                // print_r($offer_letter_sort_history);
                // die('stop');
            }

            $data['offer_letters'] = $applicant_offer_letters;
            $this->load->view('main/header', $data);
            $this->load->view('onboarding/view_assigned_offer_letter');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    public function revoke_offer_letter($offer_letter_sid)
    {
        $data_to_update = array();
        $data_to_update['archive'] = 1;
        $this->onboarding_model->revoke_applicant_offer_letter($offer_letter_sid, $data_to_update);
        echo 'success';
    }

    public function my_offer_letter($verification_key, $type = 'a')
    {
        $check_user_exist = $this->onboarding_model->check_applicant_exist($verification_key, $type);

        if (!empty($check_user_exist)) {

            if ($type == 'a') {
                $company_sid = $check_user_exist['employer_sid'];
                $user_type = 'applicant';
                $user_sid = $check_user_exist['sid'];
                $document_type = 'offer_letter';
                $company_info = $this->onboarding_model->get_applicant_company_info($company_sid);
                $applicant = $check_user_exist;
                $offer_letter = $this->onboarding_model->check_my_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type);

                if (!empty($offer_letter) && $offer_letter['status'] == 1) {
                    $document_info = $this->onboarding_model->get_assign_offer_letter_info($offer_letter['document_sid']);
                    $letter_body = $offer_letter['document_description'];
                    $save_offer_letter_type = '';
                    $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}', 'select');
                    $magic_signature_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_signature_codes, '', $letter_body) != $letter_body) {
                        $save_offer_letter_type = 'consent_only';
                    } else if (str_replace($magic_codes, '', $letter_body) != $letter_body) {
                        $save_offer_letter_type = 'save_only';
                    }

                    $data = array();
                    $data['title'] = 'Offer Letter';
                    $data['company_info'] = $company_info;
                    $data['applicant'] = $applicant;
                    $data['document_info'] = $document_info;
                    $data['save_offer_letter_type'] = $save_offer_letter_type;

                    if ($offer_letter['acknowledged'] == 1) {
                        $acknowledgement_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully Acknowledged this offer letter';
                        $acknowledgement_button_txt = 'Acknowledged';
                        $acknowledgement_button_css = 'btn-warning';
                        $acknowledgement_button_action = 'javascript:;';
                    } else {
                        $acknowledgement_status = '<strong class="text-danger">Offer Letter Status:</strong> You have not yet acknowledged this offer letter';
                        $acknowledgement_button_txt = 'I Acknowledge';
                        $acknowledgement_button_css = 'blue-button';
                        $acknowledgement_button_action = 'func_acknowledge_document();';
                    }

                    $acknowledgment_action_title = 'Document Action: <b>Acknowledgement Required!</b>';
                    $acknowledgment_action_desc = '<b>Acknowledge the receipt of this offer letter</b>';

                    $download_action_title = 'Document Action: <b>Download / Print</b>';
                    $download_action_desc = '<b>You can Download / Print this offer letter for your reference</b>';
                    $download_button_action = 'javascript:;';

                    if ($offer_letter['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully printed this offer letter';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                    } else {
                        $download_status = '<strong class="text-danger">Offer Letter Status:</strong> You have not yet printed this offer letter';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                    }

                    if (empty($offer_letter['submitted_description'])) {
                        $print_button_action = base_url('onboarding/print_applicant_offer_letter/original/' . $offer_letter['sid']);
                    } else {
                        $print_button_action = base_url('onboarding/print_applicant_offer_letter/submitted/' . $offer_letter['sid']);
                    }

                    if ($offer_letter['document_sid'] != 0) {
                        $original_doc_sid = $offer_letter['document_sid'];
                        $document_feature = $this->onboarding_model->get_offer_letter_feature_info($original_doc_sid);
                        $offer_letter['acknowledgment_required'] = $document_feature['acknowledgment_required'];
                        $offer_letter['download_required'] = $document_feature['download_required'];
                        $offer_letter['signature_required'] = $document_feature['signature_required'];
                    }

                    if ($offer_letter['offer_letter_type'] == 'uploaded') {
                        $uploaded_action_title = 'Document Action: Upload Signed Copy!';
                        $uploaded_action_desc = 'Please sign this document and upload the signed copy.';
                        if ($offer_letter['uploaded'] == 1) {
                            $uploaded_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully uploaded signed copy. In case you uploaded wrong document you can replace with the correct version by re uploading it';
                            $uploaded_button_txt = 'Re-Upload Document';
                            $uploaded_button_css = 'btn-warning';
                        } else {
                            $uploaded_status = '<strong class="text-danger">Offer Letter Status:</strong> Upload the Signed Document, You have not yet uploaded this document';
                            $uploaded_button_txt = 'Upload Document';
                            $uploaded_button_css = 'blue-button';
                        }
                        $data['uploaded_action_title'] = $uploaded_action_title;
                        $data['uploaded_action_desc'] = $uploaded_action_desc;
                        $data['uploaded_status'] = $uploaded_status;
                        $data['uploaded_button_txt'] = $uploaded_button_txt;
                        $data['uploaded_button_css'] = $uploaded_button_css;
                    } else {
                        if (!empty($offer_letter['authorized_signature']) && $offer_letter['user_consent'] == 0) {
                            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $offer_letter['authorized_signature'] . '" id="show_authorized_signature">';
                            $offer_letter['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $offer_letter['document_description']);

                            if (!empty($offer_letter['authorized_signature_date'])) {
                                $authorized_signature_date = '<p><strong>' . date_with_time($offer_letter['authorized_signature_date']) . '</strong></p>';
                                $offer_letter['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $offer_letter['document_description']);
                            }

                            if (!empty($document['authorized_editable_date'])) {
                                $authorized_editable_date = ' <strong>' . formatDateToDB($document['authorized_editable_date'], DB_DATE, DATE) . '</strong>';
                                $offer_letter['document_description'] = str_replace('{{authorized_editable_date}}', $authorized_signature_date, $offer_letter['document_description']);
                            } 
                        }
                        //
                        $document_content = replace_tags_for_document($company_info['sid'], $user_sid, $user_type, $offer_letter['document_description'], $offer_letter['document_sid']);
                        $offer_letter['document_description'] = $document_content;
                    }



                    $data['offer_letter'] = $offer_letter;
                    $data['download_action_title'] = $download_action_title;
                    $data['download_action_desc'] = $download_action_desc;
                    $data['download_button_txt'] = $download_button_txt;
                    $data['download_button_action'] = $download_button_action;
                    $data['download_status'] = $download_status;
                    $data['download_button_css'] = $download_button_css;
                    $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                    $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                    $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                    $data['acknowledgement_status'] = $acknowledgement_status;
                    $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                    $data['acknowledgement_button_action'] = $acknowledgement_button_action;
                    $data['print_button_action'] = $print_button_action;
                    $data['method'] = 'sign_hr_document';


                    $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

                    if ($this->form_validation->run() == false) {
                        if ($offer_letter['user_consent'] == 1) {
                            $this->load->view('onboarding/thank_you');
                        } else {
                            $this->load->view('onboarding/applicant_boarding_header', $data);
                            $this->load->view('onboarding/view_offer_letter');
                            $this->load->view('onboarding/on_boarding_footer');
                        }
                    } else {
                        $perform_action = $this->input->post('perform_action');

                        switch ($perform_action) {
                            case 'acknowledge_document':
                                $user_type = $this->input->post('user_type');
                                $user_sid = $this->input->post('user_sid');
                                $document_sid = $this->input->post('document_sid');
                                // $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_sid);

                                if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1 && $document_info['download_required'] == 1)) {
                                    if ($offer_letter['downloaded'] == 1) {
                                        $data_to_update = array();
                                        $data_to_update['acknowledged'] = 1;
                                        $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                        if ($document_info['signature_required'] == 0) {
                                            $data_to_update['user_consent'] = 1;
                                            $data_to_update['form_input_data'] = 's:2:"{}";';
                                        }

                                        $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                    } else {
                                        $data_to_update = array();
                                        $data_to_update['acknowledged'] = 1;
                                        $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');
                                        $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                    }
                                } else if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1)) {
                                    $data_to_update = array();
                                    $data_to_update['acknowledged'] = 1;
                                    $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                    if ($document_info['signature_required'] == 0) {
                                        $data_to_update['user_consent'] = 1;
                                        $data_to_update['form_input_data'] = 's:2:"{}";';
                                    }

                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                }

                                $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                                redirect('onboarding/my_offer_letter/' . $verification_key, 'refresh');
                                break;
                            case 'sign_document':
                                $save_input_values = array();
                                $users_type = 'applicant';
                                $save_signature = $this->input->post('save_signature');
                                $save_initial = $this->input->post('save_signature_initial');
                                $save_date = $this->input->post('save_signature_date');
                                $user_consent = $this->input->post('user_consent');
                                $base64_pdf = $this->input->post('save_PDF');

                                if (isset($_POST['save_input_values']) && !empty($_POST['save_input_values'])) {
                                    $save_input_values = $_POST['save_input_values'];
                                }
                                $save_input_values = serialize($save_input_values);

                                $data_to_update = array();

                                if ($save_signature == 'yes' || $save_initial == 'yes' || $save_date == 'yes') {
                                    $company_sid = $company_info['sid'];

                                    $signature = get_e_signature($company_sid, $user_sid, $user_type);

                                    if ($save_signature == 'yes') {
                                        $data_to_update['signature_base64'] = $signature['signature_bas64_image'];
                                    }

                                    if ($save_initial == 'yes') {
                                        $data_to_update['signature_initial'] = $signature['init_signature_bas64_image'];
                                    }

                                    if ($save_date == 'yes') {
                                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                                    }
                                }

                                $data_to_update['signature_email'] = $applicant['email'];
                                $data_to_update['signature_ip'] = getUserIP();
                                $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                                $data_to_update['submitted_description'] = $base64_pdf;
                                $data_to_update['uploaded'] = 1;
                                $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                                $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                                $data_to_update['form_input_data'] = $save_input_values;
                                $this->hr_documents_management_model->update_assigned_documents($offer_letter['sid'], $user_sid, $users_type, $data_to_update);
                                //Email Sending Section Start
                                $notifications_status = get_notifications_status($company_sid);
                                $onboarding_request_email_notification = 0;

                                if (!empty($notifications_status)) {
                                    $onboarding_request_email_notification = $notifications_status['offer_letter_notification'];
                                }

                                if ($onboarding_request_email_notification == 1) { //Send email to Users which are registered to receive notifications
                                    $emailTemplateData = get_email_template(OFFER_LETTER_SIGNED_NOTIFICATION);
                                    $employer_name = $applicant['first_name'] . ' ' . $applicant['last_name'];
                                    $offer_letter_handlers = get_notification_email_contacts($company_sid, 'offer_letter');
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    $job_sid = $this->hr_documents_management_model->get_applicant_jobs_list_id($user_sid);

                                    if (!empty($offer_letter_handlers)) {
                                        foreach ($offer_letter_handlers as $emp_info) {
                                            if (!empty($emp_info)) {
                                                $sms_notify = 0;
                                                $contact_no = 0;
                                                if ($company_sms_notification_status) {
                                                    if ($emp_info['employer_sid'] != 0) {
                                                        $notify_by = get_employee_sms_status($this, $emp_info['employer_sid']);
                                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                            $contact_no = $notify_by['PhoneNumber'];
                                                            $sms_notify = 1;
                                                        }
                                                    } else {
                                                        if (!empty($emp_info['contact_no'])) {
                                                            $contact_no = $emp_info['contact_no'];
                                                            $sms_notify = 1;
                                                        }
                                                    }
                                                    if ($sms_notify) {
                                                        $this->load->library('Twilioapp');
                                                        // Send SMS
                                                        $sms_template = get_company_sms_template($this, $company_sid, 'offer_letter');
                                                        $replacement_sms_array = array(); //Send Payment Notification to admin.
                                                        $replacement_sms_array['applicant_name'] = $applicant['first_name'] . ' ' . $applicant['last_name'];
                                                        $replacement_sms_array['contact_name'] = ucwords(strtolower($emp_info['contact_name']));
                                                        $sms_body = 'This sms is to inform you that ' . $applicant['first_name'] . ' ' . $applicant['last_name'] . ' has Signed their Offer Letter / Pay Plan.';
                                                        if (sizeof($sms_template) > 0) {
                                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_sms_array);
                                                        }
                                                        sendSMS(
                                                            $contact_no,
                                                            $sms_body,
                                                            trim(ucwords(strtolower($emp_info['contact_name']))),
                                                            $emp_info['email'],
                                                            $this,
                                                            $sms_notify,
                                                            $company_sid
                                                        );
                                                    }
                                                }
                                                $userEmail = $emp_info['email'];
                                                $userFullName = ucwords($emp_info['contact_name']);
                                                $employee_profile = base_url('onboarding/view_offer_letter/applicant') . '/' . $user_sid . '/' . $job_sid['sid'];
                                                $link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $employee_profile . '">View Signed Offer Letter</a>';
                                                $emailTemplateBody = $emailTemplateData['text'];
                                                $emailTemplateBody = str_replace('{{firstname}}', ucwords($applicant['first_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{lastname}}', ucwords($applicant['last_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{first_name}}', ucwords($applicant['first_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{last_name}}', ucwords($applicant['last_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{link}}', $link, $emailTemplateBody);
                                                $from = $emailTemplateData['from_email'];
                                                $to = $userEmail;
                                                $subject = $emailTemplateData['subject'];
                                                $subject = str_replace('{{company_name}}', ucwords($company_info['CompanyName']), $subject);
                                                $from_name = $emailTemplateData['from_name'];
                                                $body = EMAIL_HEADER
                                                    . $emailTemplateBody
                                                    . EMAIL_FOOTER;
                                                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                                            }
                                        }
                                    }
                                }

                                $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Offer Letter!');
                                redirect('onboarding/my_offer_letter/' . $verification_key, 'refresh');
                                break;
                            case 'upload_document':
                                $user_type = $this->input->post('user_type');
                                $user_sid = $this->input->post('user_sid');
                                $document_sid = $this->input->post('document_sid');
                                $document_type = $this->input->post('document_type');
                                $company_sid = $this->input->post('company_sid');
                                $aws_file_name = upload_file_to_aws('upload_file', $company_sid, 'uploaded_offer_letter_' . $document_sid, time());

                                $uploaded_file = '';

                                if ($aws_file_name != 'error') {
                                    $uploaded_file = $aws_file_name;
                                }

                                if (!empty($uploaded_file)) {
                                    $data_to_update = array();
                                    $data_to_update['uploaded'] = 1;
                                    $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                    $data_to_update['user_consent'] = 1;
                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);

                                    // $this->onboarding_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                                    $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded Successful!');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                                }
                                redirect('onboarding/my_offer_letter/' . $verification_key, 'refresh');
                                break;
                        }
                    }
                } else {
                    $this->load->view('onboarding/onboarding_error');
                }
            } elseif ($type == 'e') {
                $company_sid = $check_user_exist['parent_sid'];
                $user_type = 'employee';
                $user_sid = $check_user_exist['sid'];
                $document_type = 'offer_letter';
                $company_info = $this->onboarding_model->get_applicant_company_info($company_sid);
                $employee = $check_user_exist;
                $offer_letter = $this->onboarding_model->check_my_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type);

                if (!empty($offer_letter) && $offer_letter['status'] == 1) {
                    $document_info = $this->onboarding_model->get_assign_offer_letter_info($offer_letter['document_sid']);
                    $letter_body = $offer_letter['document_description'];
                    $save_offer_letter_type = '';
                    $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}', 'select');
                    $magic_signature_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_signature_codes, '', $letter_body) != $letter_body) {
                        $save_offer_letter_type = 'consent_only';
                    } else if (str_replace($magic_codes, '', $letter_body) != $letter_body) {
                        $save_offer_letter_type = 'save_only';
                    }

                    $document_content = replace_tags_for_document($company_info['sid'], $user_sid, $user_type, $offer_letter['document_description'], $offer_letter['document_sid']);
                    $offer_letter['document_description'] = $document_content;
                    $data = array();
                    $data['title'] = 'Offer Letter';
                    $data['company_info'] = $company_info;
                    $data['employee'] = $employee;
                    $data['document_info'] = $document_info;
                    $data['save_offer_letter_type'] = $save_offer_letter_type;

                    if ($offer_letter['acknowledged'] == 1) {
                        $acknowledgement_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully Acknowledged this offer letter';
                        $acknowledgement_button_txt = 'Acknowledged';
                        $acknowledgement_button_css = 'btn-warning';
                        $acknowledgement_button_action = 'javascript:;';
                    } else {
                        $acknowledgement_status = '<strong class="text-danger">Offer Letter Status:</strong> You have not yet acknowledged this offer letter';
                        $acknowledgement_button_txt = 'I Acknowledge';
                        $acknowledgement_button_css = 'blue-button';
                        $acknowledgement_button_action = 'func_acknowledge_document();';
                    }

                    $acknowledgment_action_title = 'Document Action: <b>Acknowledgement Required!</b>';
                    $acknowledgment_action_desc = '<b>Acknowledge the receipt of this offer letter</b>';
                    $download_action_title = 'Document Action: <b>Download / Print</b>';
                    $download_action_desc = '<b>You can Download / Print this offer letter for your reference</b>';
                    $download_button_action = 'javascript:;';

                    if ($offer_letter['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully printed this offer letter';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                    } else {
                        $download_status = '<strong class="text-danger">Offer Letter Status:</strong> You have not yet printed this offer letter';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                    }

                    if (empty($offer_letter['submitted_description'])) {
                        $print_button_action = base_url('onboarding/print_applicant_offer_letter/original/' . $offer_letter['sid']);
                    } else {
                        $print_button_action = base_url('onboarding/print_applicant_offer_letter/submitted/' . $offer_letter['sid']);
                    }

                    $original_doc_sid = $offer_letter['document_sid'];
                    $document_feature = $this->onboarding_model->get_offer_letter_feature_info($original_doc_sid);
                    $offer_letter['acknowledgment_required'] = $document_feature['acknowledgment_required'];
                    $offer_letter['download_required'] = $document_feature['download_required'];
                    $offer_letter['signature_required'] = $document_feature['signature_required'];

                    if ($offer_letter['offer_letter_type'] == 'uploaded') {
                        $uploaded_action_title = 'Document Action: Upload Signed Copy!';
                        $uploaded_action_desc = 'Please sign this document and upload the signed copy.';
                        if ($offer_letter['uploaded'] == 1) {
                            $uploaded_status = '<strong class="text-success">Offer Letter Status:</strong> You have successfully uploaded signed copy. In case you uploaded wrong document you can replace with the correct version by re uploading it';
                            $uploaded_button_txt = 'Re-Upload Document';
                            $uploaded_button_css = 'btn-warning';
                        } else {
                            $uploaded_status = '<strong class="text-danger">Offer Letter Status:</strong> Upload the Signed Document, You have not yet uploaded this document';
                            $uploaded_button_txt = 'Upload Document';
                            $uploaded_button_css = 'blue-button';
                        }
                        $data['uploaded_action_title'] = $uploaded_action_title;
                        $data['uploaded_action_desc'] = $uploaded_action_desc;
                        $data['uploaded_status'] = $uploaded_status;
                        $data['uploaded_button_txt'] = $uploaded_button_txt;
                        $data['uploaded_button_css'] = $uploaded_button_css;
                    }

                    $data['offer_letter'] = $offer_letter;
                    $data['download_action_title'] = $download_action_title;
                    $data['download_action_desc'] = $download_action_desc;
                    $data['download_button_txt'] = $download_button_txt;
                    $data['download_button_action'] = $download_button_action;
                    $data['download_status'] = $download_status;
                    $data['download_button_css'] = $download_button_css;
                    $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                    $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                    $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                    $data['acknowledgement_status'] = $acknowledgement_status;
                    $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                    $data['acknowledgement_button_action'] = $acknowledgement_button_action;
                    $data['print_button_action'] = $print_button_action;
                    $data['method'] = 'sign_hr_document';
                    $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

                    if ($this->form_validation->run() == false) {
                        if ($offer_letter['user_consent'] == 1) {
                            $this->load->view('onboarding/thank_you');
                        } else {
                            $this->load->view('onboarding/applicant_boarding_header', $data);
                            $this->load->view('onboarding/view_offer_letter');
                            $this->load->view('onboarding/on_boarding_footer');
                        }
                    } else {
                        $perform_action = $this->input->post('perform_action');

                        switch ($perform_action) {
                            case 'acknowledge_document':
                                $user_type = $this->input->post('user_type');
                                $user_sid = $this->input->post('user_sid');
                                $document_sid = $this->input->post('document_sid');
                                // $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_sid);

                                if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1 && $document_info['download_required'] == 1)) {
                                    if ($offer_letter['downloaded'] == 1) {
                                        $data_to_update = array();
                                        $data_to_update['acknowledged'] = 1;
                                        $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                        if ($document_info['signature_required'] == 0) {
                                            $data_to_update['user_consent'] = 1;
                                            $data_to_update['form_input_data'] = 's:2:"{}";';
                                        }

                                        $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                    } else {
                                        $data_to_update = array();
                                        $data_to_update['acknowledged'] = 1;
                                        $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');
                                        $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                    }
                                } else if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1)) {
                                    $data_to_update = array();
                                    $data_to_update['acknowledged'] = 1;
                                    $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                    if ($document_info['signature_required'] == 0) {
                                        $data_to_update['user_consent'] = 1;
                                        $data_to_update['form_input_data'] = 's:2:"{}";';
                                    }

                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                }

                                $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                                redirect('onboarding/my_offer_letter/' . $verification_key . '/e', 'refresh');
                                break;
                            case 'sign_document':
                                $save_input_values = array();
                                $users_type = 'employee';
                                $save_signature = $this->input->post('save_signature');
                                $save_initial = $this->input->post('save_signature_initial');
                                $save_date = $this->input->post('save_signature_date');
                                $user_consent = $this->input->post('user_consent');
                                $base64_pdf = $this->input->post('save_PDF');

                                if (isset($_POST['save_input_values']) && !empty($_POST['save_input_values'])) {
                                    $save_input_values = $_POST['save_input_values'];
                                }
                                $save_input_values = serialize($save_input_values);

                                $data_to_update = array();

                                if ($save_signature == 'yes' || $save_initial == 'yes' || $save_date == 'yes') {
                                    $company_sid = $company_info['sid'];

                                    $signature = get_e_signature($company_sid, $user_sid, $user_type);

                                    if ($save_signature == 'yes') {
                                        $data_to_update['signature_base64'] = $signature['signature_bas64_image'];
                                    }

                                    if ($save_initial == 'yes') {
                                        $data_to_update['signature_initial'] = $signature['init_signature_bas64_image'];
                                    }

                                    if ($save_date == 'yes') {
                                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                                    }
                                }

                                $data_to_update['signature_email'] = $employee['email'];
                                $data_to_update['signature_ip'] = getUserIP();
                                $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                                $data_to_update['submitted_description'] = $base64_pdf;
                                $data_to_update['uploaded'] = 1;
                                $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                                $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                                $data_to_update['form_input_data'] = $save_input_values;
                                $this->hr_documents_management_model->update_assigned_documents($offer_letter['sid'], $user_sid, $users_type, $data_to_update);
                                //Email Sending Section Start
                                $notifications_status = get_notifications_status($company_sid);
                                $onboarding_request_email_notification = 0;

                                if (!empty($notifications_status)) {
                                    $onboarding_request_email_notification = $notifications_status['offer_letter_notification'];
                                }

                                if ($onboarding_request_email_notification == 1) { //Send email to Users which are registered to receive notifications
                                    $emailTemplateData = get_email_template(OFFER_LETTER_SIGNED_NOTIFICATION);
                                    $offer_letter_handlers = get_notification_email_contacts($company_sid, 'offer_letter');
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if (!empty($offer_letter_handlers)) {
                                        foreach ($offer_letter_handlers as $emp_info) {
                                            if (!empty($emp_info)) {
                                                $sms_notify = 0;
                                                $contact_no = 0;
                                                if ($company_sms_notification_status) {
                                                    if ($emp_info['employer_sid'] != 0) {
                                                        $notify_by = get_employee_sms_status($this, $emp_info['employer_sid']);
                                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                            $contact_no = $notify_by['PhoneNumber'];
                                                            $sms_notify = 1;
                                                        }
                                                    } else {
                                                        if (!empty($emp_info['contact_no'])) {
                                                            $contact_no = $emp_info['contact_no'];
                                                            $sms_notify = 1;
                                                        }
                                                    }
                                                    if ($sms_notify) {
                                                        $this->load->library('Twilioapp');
                                                        // Send SMS
                                                        $sms_template = get_company_sms_template($this, $company_sid, 'offer_letter');
                                                        $replacement_sms_array = array(); //Send Payment Notification to admin.
                                                        $replacement_sms_array['applicant_name'] = $employee['first_name'] . ' ' . $employee['last_name'];
                                                        $replacement_sms_array['contact_name'] = ucwords(strtolower($emp_info['contact_name']));
                                                        $sms_body = 'This sms is to inform you that ' . $employee['first_name'] . ' ' . $employee['last_name'] . ' has Signed their Offer Letter / Pay Plan.';
                                                        if (sizeof($sms_template) > 0) {
                                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_sms_array);
                                                        }
                                                        sendSMS(
                                                            $contact_no,
                                                            $sms_body,
                                                            trim(ucwords(strtolower($emp_info['contact_name']))),
                                                            $emp_info['email'],
                                                            $this,
                                                            $sms_notify,
                                                            $company_sid
                                                        );
                                                    }
                                                }
                                                $userEmail = $emp_info['email'];
                                                $userFullName = ucwords($emp_info['contact_name']);
                                                $employee_profile = base_url('onboarding/view_offer_letter/employee') . '/' . $user_sid;
                                                $link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $employee_profile . '">View Signed Offer Letter</a>';
                                                $emailTemplateBody = $emailTemplateData['text'];
                                                $emailTemplateBody = str_replace('{{firstname}}', ucwords($employee['first_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{lastname}}', ucwords($employee['last_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{first_name}}', ucwords($employee['first_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{last_name}}', ucwords($employee['last_name']), $emailTemplateBody);
                                                $emailTemplateBody = str_replace('{{link}}', $link, $emailTemplateBody);
                                                $from = $emailTemplateData['from_email'];
                                                $to = $userEmail;
                                                $subject = $emailTemplateData['subject'];
                                                $subject = str_replace('{{company_name}}', ucwords($company_info['CompanyName']), $subject);
                                                $from_name = $emailTemplateData['from_name'];
                                                $body = EMAIL_HEADER
                                                    . $emailTemplateBody
                                                    . EMAIL_FOOTER;
                                                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                                            }
                                        }
                                    }
                                }

                                $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Offer Letter!');
                                redirect('onboarding/my_offer_letter/' . $verification_key . '/e', 'refresh');
                                break;
                            case 'upload_document':
                                $user_type = $this->input->post('user_type');
                                $user_sid = $this->input->post('user_sid');
                                $document_sid = $this->input->post('document_sid');
                                $document_type = $this->input->post('document_type');
                                $company_sid = $this->input->post('company_sid');
                                $aws_file_name = upload_file_to_aws('upload_file', $company_sid, 'uploaded_offer_letter_' . $document_sid, time());

                                $uploaded_file = '';

                                if ($aws_file_name != 'error') {
                                    $uploaded_file = $aws_file_name;
                                }

                                if (!empty($uploaded_file)) {
                                    $data_to_update = array();
                                    $data_to_update['uploaded'] = 1;
                                    $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                    $data_to_update['user_consent'] = 1;
                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);

                                    // $this->onboarding_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                                    $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded Successful!');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                                }
                                redirect('onboarding/my_offer_letter/' . $verification_key . '/e', 'refresh');
                                break;
                        }
                    }
                } else {
                    $this->load->view('onboarding/onboarding_error');
                }
            } else { //If anyone tried to play with url 2nd param
                $this->load->view('onboarding/onboarding_error');
            }
        } else {
            $this->load->view('onboarding/onboarding_error');
        }
    }

    /**
     * Print Applicant offer Letter
     * Created on: 15-07-2019
     *
     * @param $type String
     * @param $$offer_letter_sid Integer
     * @param $download String
     *
     * @return VOID
     */
    public function print_applicant_offer_letter($type, $offer_letter_sid, $download = NULL)
    {
        $offer_letter = $this->hr_documents_management_model->get_submitted_generated_document($offer_letter_sid);
        $company_sid = $offer_letter['company_sid'];
        $user_sid = $offer_letter['user_sid'];
        $user_type = $offer_letter['user_type'];

        if ($type == 'original') {
            $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $offer_letter['document_description'], $offer_letter['document_sid']);
            $value = '<div class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
            $document_content = str_replace('[Target User Input Field]', $value, $document_content);
            $value = '<br><input type="checkbox" class="user_checkbox"/>';
            $document_content = str_replace('[Target User Checkbox]', $value, $document_content);
            //E_signature process
            $signature_bas64_image = '<a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
            $init_signature_bas64_image = '<a class="btn btn-sm blue-button get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
            $signature_timestamp = '<a class="btn btn-sm blue-button get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';
            $value = ' ';
            $document_content = str_replace($signature_bas64_image, $value, $document_content);
            $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
            $document_content = str_replace($signature_timestamp, $value, $document_content);

            $data['print'] = $type;
            $data['download'] = $download;
            $data['file_name'] = $offer_letter['document_title'];
            $data['original_document_description'] = $document_content;
        } else if ($type == 'submitted') {
            $data['print'] = $type;
            $data['download'] = $download;
            $data['file_name'] = $offer_letter['document_title'];
            $data['document'] = $offer_letter;
        }

        $this->load->view('hr_documents_management/print_generated_document', $data);
    }

    public function send_applicant_resume_request($user_type, $user_sid, $job_list_sid = null)
    {

        if ($this->session->userdata('logged_in')) {

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            // if (in_array($company_sid, array("7", "51"))) {
            if (!in_array($company_sid, array("0"))) {

                $requested_job_sid = $this->input->post('job_sid', true);
                $requested_job_type = $this->input->post('job_type', true);
                // echo $requested_job_sid.'<br>';
                // echo $requested_job_type.'<br>';
                // die();

                $data               = array();
                $user_info          = array();
                $emailTemplate      = '';
                $default_subject    = '';
                $default_template   = '';

                $emailTemplate = $this->onboarding_model->get_send_resume_template($company_sid);

                if (!empty($emailTemplate)) {
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['message_body']);
                } else {
                    $emailTemplate = get_email_template(SEND_RESUME_REQUEST);
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['text']);
                }

                $user_info = $this->onboarding_model->get_applicant_information($user_sid);
                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $verification_key = '';
                $applicant_email = $user_info['email'];

                if ($user_info['verification_key'] == NULL || empty($user_info['verification_key'])) {
                    $verification_key = random_key(80);
                    $this->onboarding_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
                } else {
                    $verification_key = $user_info['verification_key'];
                }

                $data_to_insert = array();
                $data_to_insert['company_sid']              = $company_sid;
                $data_to_insert['user_type']                = $user_type;
                $data_to_insert['user_sid']                 = $user_sid;
                $data_to_insert['user_email']               = $applicant_email;
                $data_to_insert['requested_by']             = $employer_sid;
                $data_to_insert['requested_subject']        = $default_subject;
                $data_to_insert['requested_message']        = $default_template;
                $data_to_insert['requested_ip_address']     =  getUserIP();
                $data_to_insert['requested_user_agent']     = $_SERVER['HTTP_USER_AGENT'];
                $data_to_insert['request_status']           = 1;
                $data_to_insert['requested_date']           = date('Y-m-d H:i:s');
                $data_to_insert['job_sid']                  = $requested_job_sid;
                $data_to_insert['job_type']                 = $requested_job_type;


                $this->onboarding_model->deactivate_old_resume_request($company_sid, $user_type, $user_sid, $requested_job_sid, $requested_job_type);
                $this->onboarding_model->insert_resume_request($data_to_insert);

                $subject = $default_subject;
                $message_body = $default_template;

                $requested_job_sid = $this->encryption->encrypt($requested_job_sid);
                $requested_job_sid = str_replace('/', '$job', $requested_job_sid);
                $requested_job_type = $this->encryption->encrypt($requested_job_type);
                $requested_job_type = str_replace('/', '$type', $requested_job_type);

                $url = base_url() . 'onboarding/send_requested_resume/' . $verification_key . '/' . $requested_job_sid . '/' . $requested_job_type;
                $link_btn = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Send Resume</a>';

                $message_body = str_replace('{{link}}', $link_btn, $message_body);

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $applicant_email;
                $from_name = ucwords(STORE_DOMAIN);
                $email_hf = message_header_footer_domain($company_sid, $company_name);
                $body = $email_hf['header']
                    . $message_body
                    . $email_hf['footer'];

                log_and_sendEmail($from, $to, $subject, $body, $from_name);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Resume Request Sent Successfully!');
                redirect('applicant_profile/' . $user_sid . '/' . $job_list_sid, 'refresh');
            } else {


                $data               = array();
                $user_info          = array();
                $emailTemplate      = '';
                $default_subject    = '';
                $default_template   = '';

                $emailTemplate = $this->onboarding_model->get_send_resume_template($company_sid);

                if (!empty($emailTemplate)) {
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['message_body']);
                } else {
                    $emailTemplate = get_email_template(SEND_RESUME_REQUEST);
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['text']);
                }

                $user_info = $this->onboarding_model->get_applicant_information($user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $verification_key = '';
                $applicant_email = $user_info['email'];

                if ($user_info['verification_key'] == NULL || empty($user_info['verification_key'])) {
                    $verification_key = random_key(80);
                    $this->onboarding_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
                } else {
                    $verification_key = $user_info['verification_key'];
                }

                $data_to_insert = array();
                $data_to_insert['company_sid']              = $company_sid;
                $data_to_insert['user_type']                = $user_type;
                $data_to_insert['user_sid']                 = $user_sid;
                $data_to_insert['user_email']               = $applicant_email;
                $data_to_insert['requested_by']             = $employer_sid;
                $data_to_insert['requested_subject']        = $default_subject;
                $data_to_insert['requested_message']        = $default_template;
                $data_to_insert['requested_ip_address']     =  getUserIP();
                $data_to_insert['requested_user_agent']     = $_SERVER['HTTP_USER_AGENT'];
                $data_to_insert['request_status']           = 1;
                $data_to_insert['requested_date']           = date('Y-m-d H:i:s');
                $data_to_insert['job_sid']                  = NULL;
                $data_to_insert['job_type']                 = NULL;

                // $current_resume_s3_name = $this->onboarding_model->get_current_resume($company_sid, $user_sid);

                // if (!empty($current_resume_s3_name)) {
                //     $data_to_insert['old_resume_s3_name'] = $current_resume_s3_name;
                //     $this->onboarding_model->reset_old_resume($company_sid, $user_sid);

                // }

                $this->onboarding_model->deactivate_old_resume_request_old($company_sid, $user_type, $user_sid);
                $this->onboarding_model->insert_resume_request($data_to_insert);

                $subject = $default_subject;
                $message_body = $default_template;

                $url = base_url() . 'onboarding/send_requested_resume/' . $verification_key;
                $link_btn = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Send Resume</a>';

                $message_body = str_replace('{{link}}', $link_btn, $message_body);

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $applicant_email;
                $from_name = ucwords(STORE_DOMAIN);
                $email_hf = message_header_footer_domain($company_sid, $company_name);
                $body = $email_hf['header']
                    . $message_body
                    . $email_hf['footer'];
                // sendMail($from, $to, $subject, $body, $from_name);
                log_and_sendEmail($from, $to, $subject, $body, $from_name);

                $this->session->set_flashdata('message', '<strong>Success: </strong> Resume Request Sent Successfully!');
                redirect('applicant_profile/' . $user_sid . '/' . $job_list_sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function send_requested_resume($verification_key, $job_sid = null, $job_type = null)
    {

        $applicant = $this->onboarding_model->check_applicant_exist($verification_key);

        if (!empty($applicant)) {

            $user_type          = 'applicant';
            $user_sid           = $applicant['sid'];
            $company_sid        = $applicant['employer_sid'];

            // if (in_array($company_sid, array("7", "51"))) {
            if (!in_array($company_sid, array("0"))) {
                $requested_job_sid  = str_replace('$job', '/', $job_sid);
                $requested_job_sid  = $this->encryption->decrypt($requested_job_sid);
                $requested_job_type = str_replace('$type', '/', $job_type);
                $requested_job_type = $this->encryption->decrypt($requested_job_type);

                $resume_request = $this->onboarding_model->get_user_resume_request($company_sid, $user_type, $user_sid, $requested_job_sid, $requested_job_type);

                if (!empty($resume_request)) {

                    $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

                    if ($this->form_validation->run() == false) {
                        if ($resume_request['is_respond'] == 1) {
                            $this->load->view('onboarding/thank_you');
                        } else {

                            $company_info = $this->onboarding_model->get_applicant_company_info($company_sid);

                            $job_title = '';
                            if ($requested_job_type == 'job') {
                                $job_name = $this->onboarding_model->get_job_title($requested_job_sid, $company_sid);
                                $job_title = 'Upload Resume For ' . $job_name;
                            } else if ($requested_job_type == 'desired_job') {
                                $job_name = $this->onboarding_model->get_desired_job_title($requested_job_sid, $company_sid);
                                $job_title = 'Upload Resume For ' . $job_name;
                            } else if ($requested_job_type == 'job_not_applied') {
                                $job_title = 'Upload Resume';
                            }

                            $user_first_name    = $applicant['first_name'];
                            $user_last_name     = $applicant['last_name'];
                            $user_email         = $applicant['email'];
                            $user_phone         = $applicant['phone_number'];
                            $user_picture       = $applicant['pictures'];
                            $company_name       = $company_info['CompanyName'];

                            $data = array();
                            $data['title']              = $job_title;
                            $data['user_first_name']    = $user_first_name;
                            $data['user_last_name']     = $user_last_name;
                            $data['user_email']         = $user_email;
                            $data['user_phone']         = $user_phone;
                            $data['user_picture']       = $user_picture;
                            $data['company_sid']        = $company_sid;
                            $data['company_name']       = $company_name;

                            $this->load->view('onboarding/onboarding_public_header', $data);
                            $this->load->view('onboarding/upload_resume');
                            $this->load->view('onboarding/onboarding_public_footer');
                        }
                    } else {
                        $resume_original_name   = '';
                        $data_to_update         = array();
                        $user_type              = 'applicant';
                        $user_sid               = $applicant['sid'];
                        $resume_name            = 'resume ' . $applicant['first_name'] . ' ' . $applicant['last_name'];

                        $resume_s3_name = upload_file_to_aws('upload_resume', $company_sid, str_replace(' ', '-', $resume_name), $user_sid, AWS_S3_BUCKET_NAME);

                        if ($resume_s3_name != 'error') {

                            if (isset($_FILES['upload_resume']['name'])) {
                                $resume_original_name = $_FILES['upload_resume']['name'];
                            }

                            $resume_file_info = pathinfo($resume_original_name);

                            if (isset($resume_file_info['extension'])) {
                                $data_to_update['resume_extension'] = $resume_file_info['extension'];
                            }

                            $data_to_update['resume_original_name'] = $resume_original_name;
                            $data_to_update['resume_s3_name'] = $resume_s3_name;
                        }

                        $old_s3_resume = $this->onboarding_model->get_single_job_detail($user_sid, $company_sid, $requested_job_sid, $requested_job_type);

                        $data_to_update['is_respond']           = 1;
                        $data_to_update['old_resume_s3_name']   = $old_s3_resume;
                        $data_to_update['response_date']        = date('Y-m-d H:i:s');
                        $data_to_update['response_ip_address']  = getUserIP();
                        $data_to_update['response_user_agent']  = $_SERVER['HTTP_USER_AGENT'];

                        //update resume log record.
                        $this->onboarding_model->update_resume_request($company_sid, $user_sid, $user_type, $requested_job_sid, $requested_job_type, $data_to_update);
                        //update resume in "portal_applicant_jobs_list" table.
                        $this->onboarding_model->update_resume_applicant_job_list($user_sid, $company_sid, $requested_job_sid, $requested_job_type, $resume_s3_name);
                        redirect('onboarding/send_requested_resume/' . $verification_key . '/' . $job_sid, 'refresh');
                    }
                } else {
                    $this->load->view('onboarding/onboarding_error');
                }
            } else {

                $company_info = $this->onboarding_model->get_applicant_company_info($company_sid);
                $old_resume_s3_name = !empty($applicant['resume']) ? $applicant['resume'] : '';
                $is_resume_request = $this->onboarding_model->get_user_resume_request_old($company_sid, $user_type, $user_sid);


                if (!empty($is_resume_request)) {

                    $data = array();
                    $data['title'] = 'Upload Resume';
                    $data['company_info'] = $company_info;
                    $data['applicant'] = $applicant;
                    $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

                    if ($this->form_validation->run() == false) {
                        if ($is_resume_request['is_respond'] == 1) {
                            $this->load->view('onboarding/thank_you');
                        } else {
                            $this->load->view('onboarding/applicant_boarding_header', $data);
                            $this->load->view('onboarding/upload_resume');
                            $this->load->view('onboarding/on_boarding_footer');
                        }
                    } else {
                        $user_type = 'applicant';
                        $user_sid = $applicant['sid'];
                        $resume_original_name = '';
                        $resume_name = 'resume ' . $applicant['first_name'] . ' ' . $applicant['last_name'];

                        $resume_s3_name = upload_file_to_aws('upload_resume', $company_sid, str_replace(' ', '_', $resume_name), $user_sid, AWS_S3_BUCKET_NAME);

                        if (isset($_FILES['upload_resume']['name'])) {
                            $resume_original_name = $_FILES['upload_resume']['name'];
                        }

                        $resume_file_info = pathinfo($resume_original_name);
                        $data_to_update = array();

                        if (isset($resume_file_info['extension'])) {
                            $data_to_update['resume_extension'] = $resume_file_info['extension'];
                        }

                        if ($resume_s3_name != 'error') {
                            $data_to_update['resume_original_name'] = $resume_original_name;
                            $data_to_update['resume_s3_name'] = $resume_s3_name;
                        }

                        $data_to_update['is_respond'] = 1;
                        $data_to_update['response_date'] = date('Y-m-d H:i:s');
                        $data_to_update['response_ip_address'] = getUserIP();
                        $data_to_update['response_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_update['old_resume_s3_name']  = $old_resume_s3_name;
                        $data_to_update['request_status']      = 1;
                        $this->onboarding_model->update_resume_request_old($company_sid, $user_sid, $user_type, $data_to_update);
                        $this->onboarding_model->update_applicant_resume($user_sid, $resume_s3_name);
                        redirect('onboarding/send_requested_resume/' . $verification_key, 'refresh');
                    }
                } else {
                    $this->load->view('onboarding/onboarding_error');
                }
            }
        } else {
            $this->load->view('onboarding/onboarding_error');
        }
    }

    public function view_applicant_resume($user_type, $user_sid, $job_list_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid            = $data['session']['company_detail']['sid'];
            $company_name           = $data['session']['company_detail']['CompanyName'];
            $employer_sid           = $data['session']['employer_detail']['sid'];

            $data                   = array();
            $user_info              = array();
            $resume_listing         = array();
            $applicant_resume_list  = array();

            $user_info = $this->onboarding_model->get_applicant_information($user_sid);
            $user_average_rating = $this->application_tracking_system_model->getApplicantAverageRating($user_sid, $user_type);

            if (empty($user_info)) {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Applicant not found!');
                redirect('application_tracking_system/active/all/all/all/all', 'refresh');
            }

            $ats_params = $this->session->userdata('ats_params');
            $data = applicant_right_nav($user_sid, $job_list_sid, $ats_params);

            // if (in_array($company_sid, array("7", "51"))) {
            if (!in_array($company_sid, array("0"))) {

                // get resume from portal_job_applications table
                $applicant_resume = $this->onboarding_model->get_current_resume($company_sid, $user_sid);
                $applicant_resume_history = $this->onboarding_model->get_applicant_resume_history($company_sid, $user_sid);

                if (!empty($applicant_resume) && $applicant_resume != NULL) {
                    $applicant_resume_list[0]['type']       = 'Current Resume';
                    $applicant_resume_list[0]['resume_url'] = $applicant_resume;
                }

                // if (!empty($applicant_resume_history)) {
                //     foreach ($applicant_resume_history as $key => $applicant_history) {
                //         $applicant_resume_list[$key+1]['type']       = 'Previous Resume';
                //         $applicant_resume_list[$key+1]['resume_url'] = $applicant_resume;
                //     }
                // }

                // get all resumes from portal_applicant_jobs_list table
                $applicant_current_resumes = $this->onboarding_model->get_current_resume_by_job_detail($user_sid, $company_sid);

                if (!empty($applicant_current_resumes)) {
                    $continue = 'on';
                    foreach ($applicant_current_resumes as $parent_key => $current_resume) {
                        $job_sid            = $current_resume['job_sid'];
                        $desired_job_title  = $current_resume['desired_job_title'];

                        if (!empty($job_sid) && $job_sid > 0) {

                            $resume_listing[$parent_key]['job_name']                    = $current_resume['title'];
                            $resume_listing[$parent_key]['last_update']                 = $current_resume['last_update'];
                            $resume_listing[$parent_key]['resumes'][0]['type']          = 'Current Resume';
                            $resume_listing[$parent_key]['resumes'][0]['resume_url']    = $current_resume['resume'];

                            $history = $this->onboarding_model->get_this_resume_history($company_sid, $user_sid, $job_sid, 'job');

                            if (!empty($history)) {
                                foreach ($history as $child => $resume) {

                                    $resume_listing[$parent_key]['resumes'][$child + 1]['type'] = 'Previous Resume';
                                    $resume_listing[$parent_key]['resumes'][$child + 1]['resume_url'] = $resume['old_resume_s3_name'];
                                }
                            }
                        } else if ($job_sid == 0 && !empty($desired_job_title)) {

                            $resume_listing[$parent_key]['job_name']                    = $current_resume['desired_job_title'];
                            $resume_listing[$parent_key]['last_update']                 = $current_resume['last_update'];
                            $resume_listing[$parent_key]['resumes'][0]['type']          = 'Current Resume';
                            $resume_listing[$parent_key]['resumes'][0]['resume_url']    = $current_resume['resume'];

                            $applicant_job_list_sid = $current_resume['sid'];
                            $history = $this->onboarding_model->get_this_resume_history($company_sid, $user_sid, $applicant_job_list_sid, 'desired_job');

                            if (!empty($history)) {
                                foreach ($history as $child => $resume) {

                                    $resume_listing[$parent_key]['resumes'][$child + 1]['type'] = 'Previous Resume';
                                    $resume_listing[$parent_key]['resumes'][$child + 1]['resume_url'] = $resume['old_resume_s3_name'];
                                }
                            }
                        } else if ($job_sid == 0 && empty($desired_job_title)) {
                            if ($continue == 'on') {

                                $continue = 'off';
                                $resume_listing[$parent_key]['job_name']                    = 'Job Not Applied';
                                $resume_listing[$parent_key]['last_update']                 = $current_resume['last_update'];
                                $resume_listing[$parent_key]['resumes'][0]['type']          = 'Current Resume';
                                $resume_listing[$parent_key]['resumes'][0]['resume_url']    = $current_resume['resume'];

                                $history = $this->onboarding_model->get_this_resume_history($company_sid, $user_sid, $job_sid, 'job_not_applied');

                                if (!empty($history)) {
                                    foreach ($history as $child => $resume) {

                                        $resume_listing[$parent_key]['resumes'][$child + 1]['type'] = 'Previous Resume';
                                        $resume_listing[$parent_key]['resumes'][$child + 1]['resume_url'] = $resume['old_resume_s3_name'];
                                    }
                                }
                            }
                        }
                    }
                }

                $data['user_sid']               = $user_sid;
                $data['user_info']              = $user_info;
                $data['user_type']              = 'applicant';
                $data['job_list_sid']           = $job_list_sid;
                $data['resume_listing']         = $resume_listing;
                $data['user_average_rating']    = $user_average_rating;
                $data['title']                  = 'View Resume library';
                $data['applicant_resume_list']  = $applicant_resume_list;

                $this->load->view('main/header', $data);
                $this->load->view('onboarding/view_applicant_resume_new');
                $this->load->view('main/footer');
            } else {
                $current_resume = $this->onboarding_model->get_current_resume_old($company_sid, $user_sid);
                $old_resume_list = $this->onboarding_model->get_old_resume_old($company_sid, $user_type, $user_sid);

                if (!empty($current_resume)) {
                    $resume_listing[0]['name'] = 'Current Resume';
                    $resume_listing[0]['resume'] = $current_resume;
                } else {
                    $request_date = $this->onboarding_model->get_current_request_date($company_sid, $user_type, $user_sid);
                    if (!empty($request_date)) {
                        $resume_listing[0]['request_date'] = $request_date;
                        $resume_listing[0]['name'] = 'Current Resume';
                        $resume_listing[0]['resume'] = 'not_found';
                    }
                }

                if (!empty($old_resume_list)) {
                    foreach ($old_resume_list as $key => $old_resume) {
                        if (!empty($old_resume['old_resume_s3_name'])) {
                            $resume_listing[$key + 1]['name'] = 'Previous Resume';
                            $resume_listing[$key + 1]['resume'] = $old_resume['old_resume_s3_name'];
                        }
                    }
                }

                $data['job_list_sid'] = $job_list_sid;
                $data['title'] = 'View Resume library';
                $data['user_sid'] = $user_sid;
                $data['user_info'] = $user_info;
                $data['user_type'] = 'applicant';
                $data['resume_listing'] = $resume_listing;
                $data['user_average_rating'] = $user_average_rating;

                $this->load->view('main/header', $data);
                $this->load->view('onboarding/view_applicant_resume_old');
                $this->load->view('main/footer');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function assign_welcome_video_from_library()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_sid = $data['session']['employer_detail']['sid'];
            $sid = $this->input->post('welcome_video_sid');
            $user_sid = $this->input->post('user_sid');
            $user_type = $this->input->post('usertype');
            $company_sid = $this->input->post('company_sid');
            $video_assign = $this->onboarding_model->get_assign_welcome_video($sid, $company_sid);
            $is_video_exist = $this->onboarding_model->is_welcome_video_exist($company_sid, $user_type, $user_sid);

            if (!empty($is_video_exist)) {
                $data_to_update = array();
                $data_to_update['is_custom'] = 0;
                $data_to_update['title'] = $video_assign['title'];
                $data_to_update['video_source'] = $video_assign['video_source'];
                $data_to_update['video_url'] = $video_assign['video_url'];
                $data_to_update['inserted_by_sid'] = $employer_sid;
                $update_record = $is_video_exist['sid'];
                $this->onboarding_model->insert_update_welcome_video($data_to_update, $update_record);
            } else {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;

                if ($user_type == 'applicant') {
                    $data_to_insert['applicant_sid'] = $user_sid;
                } else if ($user_type == 'employee') {
                    $data_to_insert['employee_sid'] = $user_sid;
                }

                $data_to_insert['is_active'] = 1;
                $data_to_insert['is_custom'] = 0;
                $data_to_insert['title'] = $video_assign['title'];
                $data_to_insert['video_source'] = $video_assign['video_source'];
                $data_to_insert['video_url'] = $video_assign['video_url'];
                $data_to_insert['inserted_by_sid'] = $employer_sid;
                $this->onboarding_model->insert_update_welcome_video($data_to_insert);
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function add_custom_welcome_video()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $inserted_by_sid = $data['session']['employer_detail']['sid'];
            $user_sid = $this->input->post('user_sid');
            $user_type = $this->input->post('user_type');

            if ($user_type == 'applicant') {
                $job_list_sid = $this->input->post('job_list_sid');
            }

            $video_source = $this->input->post('welcome_video_source');
            $is_video_exist = $this->onboarding_model->is_welcome_video_exist($company_sid, $user_type, $user_sid);

            if (isset($_FILES['welcome_video_upload']) && !empty($_FILES['welcome_video_upload']['name'])) {
                $random = generateRandomString(5);
                $company_id = $data['session']['company_detail']['sid'];
                $target_file_name = basename($_FILES["welcome_video_upload"]["name"]);
                $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/";
                $target_file = $target_dir . $file_name;
                $filename = $target_dir . $company_id;

                if (!file_exists($filename)) {
                    mkdir($filename);
                }

                if (move_uploaded_file($_FILES["welcome_video_upload"]["tmp_name"], $target_file)) {
                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["welcome_video_upload"]["name"]) . ' has been uploaded.');
                } else {
                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                    redirect('onboarding/add_custom_welcome_video', 'refresh');
                }

                $video_url = $file_name;
            } else {
                $video_url = $this->input->post('yt_vm_video_url');

                if ($video_source == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_url = $url_prams['v'];
                    } else {
                        $video_url = '';
                    }
                } else {
                    $video_url = $this->vimeo_get_id($video_url);
                }
            }

            if (!empty($is_video_exist)) {
                $data_to_update = array();
                $data_to_update['is_custom'] = 1;
                $data_to_update['video_source'] = $video_source;
                $data_to_update['video_url'] = $video_url;
                $data_to_update['inserted_by_sid'] = $inserted_by_sid;
                $update_record = $is_video_exist['sid'];
                $this->onboarding_model->insert_update_welcome_video($data_to_update, $update_record);
            } else {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;

                if ($user_type == 'applicant') {
                    $data_to_insert['applicant_sid'] = $user_sid;
                } else if ($user_type == 'employee') {
                    $data_to_insert['employee_sid'] = $user_sid;
                }

                $data_to_insert['is_active'] = 1;
                $data_to_insert['is_custom'] = 1;
                $data_to_insert['video_source'] = $video_source;
                $data_to_insert['video_url'] = $video_url;
                $data_to_insert['inserted_by_sid'] = $inserted_by_sid;
                $this->onboarding_model->insert_update_welcome_video($data_to_insert);
            }

            if ($user_type == 'applicant') {
                redirect('onboarding/setup/applicant/' . $user_sid . '/' . $job_list_sid, 'refresh');
            } else if ($user_type == 'employee') {
                redirect('onboarding/setup/employee/' . $user_sid, 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
            redirect('login', 'refresh');
        }
    }

    public function sendEmailToApplicant()
    {
        $company_sid = $this->input->post('company_sid');
        $company_name = $this->input->post('company_name');
        $user_sid = $this->input->post('user_sid');
        $unique_sid = $this->input->post('unique_sid');
        //
        $response = array();
        //
        if (!empty($unique_sid)) {
            //
            $applicant_info = $this->onboarding_model->get_applicant_information($user_sid);
            $applicant_email = $applicant_info['email'];
            $applicant_name = $applicant_info['first_name'] . ' ' . $applicant_info['last_name'];
            $onboarding_portal_link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url('onboarding/getting_started/' . $unique_sid) . '">Onboarding Portal</a>';
            $replacement_array = array();
            $replacement_array['company_name'] = $company_name;
            $replacement_array['applicant_name'] = $applicant_name;
            $replacement_array['onboarding_portal_link'] = $onboarding_portal_link;
            //
            $user_extra_info = array();
            $user_extra_info['user_sid'] = $user_sid;
            $user_extra_info['user_type'] = "applicant";
            //
            log_and_send_templated_email(APPLICANT_ONBOARDING_WELCOME, $applicant_email, $replacement_array, message_header_footer_domain($company_sid, $company_name), 1, $user_extra_info);
            $sent_date = date('Y-m-d H:i:s');
            $this->onboarding_model->update_emailSent_date($unique_sid, array('email_sent_date' => $sent_date));
            //
            $response['status'] = TRUE;
            $response['message'] = 'A Notification email has been sent at ' . date('m-d-Y h:i:s A', strtotime($sent_date));
        } else {
            //
            $response['status'] = FALSE;
            $response['message'] = 'Please save applicant setup panel first!';
        }

        //
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }

    public function documents($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data = array();
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
            $company_name = $onboarding_details['company_info']['CompanyName'];

            if (!empty($onboarding_details)) {
                $data['title'] = 'Onboarding: ' . $company_name;
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $documents = $this->hr_documents_management_model->get_assigned_documents($company_info['sid'], 'applicant', $applicant_sid);
                $data['documents'] = $documents;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $data['i9_form'] = $this->onboarding_model->get_i9_form('applicant', $applicant_sid);

                $old_documents = $this->onboarding_model->get_old_system_documents('applicant', $applicant_sid);
                $data['old_system_documents'] = $old_documents;
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/documents');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function download_u_document($unique_sid, $document_sid)
    {
        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid, 'uploaded');
                // echo '<pre>'; print_r($onboarding_details); echo '<pre>';
                $data['document'] = $document;
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document['document_original_name'];
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['document_s3_file_name'], $temp_file_path);

                if (file_exists($temp_file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $file_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($temp_file_path));
                    $handle = fopen($temp_file_path, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                    unlink($temp_file_path);
                }

                $this->hr_documents_management_model->update_download_status('applicant', $applicant_sid, 'uploaded', $document_sid);
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function sign_u_document($unique_sid, $document_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid, 'uploaded');
                $data['document'] = $document;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/sign_u_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'acknowledge_document':
                    $unique_sid = $this->input->post('unique_sid');
                    $user_type = $this->input->post('user_type');
                    $user_sid = $this->input->post('user_sid');
                    $document_type = $this->input->post('document_type');
                    $document_sid = $this->input->post('document_sid');
                    $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_type, $document_sid);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                    redirect('onboarding/sign_u_document/' . $unique_sid . '/' . $document_sid, 'refresh');
                    break;
                case 'upload_document':
                    $unique_sid = $this->input->post('unique_sid');
                    $user_type = $this->input->post('user_type');
                    $user_sid = $this->input->post('user_sid');
                    $document_type = $this->input->post('document_type');
                    $document_sid = $this->input->post('document_sid');
                    $company_sid = $this->input->post('company_sid');
                    $aws_file_name = upload_file_to_aws('upload_file', $company_sid, $document_type . '_' . $document_sid, time());
                    $uploaded_file = '';

                    if ($aws_file_name != 'error') {
                        $uploaded_file = $aws_file_name;
                    }

                    if (!empty($uploaded_file)) {
                        $this->hr_documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                    }

                    redirect('onboarding/sign_u_document/' . $unique_sid . '/' . $document_sid, 'refresh');
                    break;
            }
        }
    }

    public function sign_g_document($unique_sid, $document_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid);

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_info['sid'], $onboarding_details['job_list_sid'], 'applicant', $document['document_content'], $document['document_sid']);
                    $document['document_content'] = $document_content;
                }

                $data['document'] = $document;
                $company_sid = $company_info['sid'];
                $e_signature_data = get_e_signature($company_sid, $applicant_sid, 'applicant');
                $signed_flag = false;

                if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                    $e_signature_data = array();
                    $e_signature_data['company_sid'] = $document['company_sid'];
                    $e_signature_data['first_name'] = $document['first_name'];
                    $e_signature_data['last_name'] = $document['last_name'];
                    $e_signature_data['email_address'] = $document['email_address'];
                    $e_signature_data['user_type'] = $document['user_type'];
                    $e_signature_data['user_sid'] = $document['user_sid'];
                    $e_signature_data['signature'] = $document['signature'];
                    $e_signature_data['signature_hash'] = $document['signature_hash'];
                    $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                    $e_signature_data['signature_bas64_image'] = $document['signature_bas64_image'];
                    $e_signature_data['active_signature'] = $document['active_signature'];
                    $e_signature_data['ip_address'] = $document['ip_address'];
                    $e_signature_data['user_agent'] = $document['user_agent'];
                    $e_signature_data['user_consent'] = $document['user_consent'];
                    $signed_flag = true;
                }

                $data['e_signature_data'] = $e_signature_data;
                $data['user_consent'] = $document['user_consent'];
                $data['signed_flag'] = $signed_flag;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/sign_g_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'sign_document':
                    $company_sid = $this->input->post('company_sid');
                    $users_type = $this->input->post('user_type');
                    $users_sid = $this->input->post('user_sid');
                    $ip_address = $this->input->post('ip_address');
                    $users_agent = $this->input->post('user_agent');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $email_address = $this->input->post('email_address');
                    $signature_timestamp = $this->input->post('signature_timestamp');
                    $documents_assignment_sid = $this->input->post('documents_assignment_sid');
                    $active_signature = $this->input->post('active_signature');
                    $signature = $this->input->post('signature');
                    $drawn_signature = $this->input->post('drawn_signature');
                    $user_consent = $this->input->post('user_consent');
                    $data_to_update = array();
                    $data_to_update['first_name'] = $first_name;
                    $data_to_update['last_name'] = $last_name;
                    $data_to_update['email_address'] = $email_address;
                    $data_to_update['signature'] = $signature;
                    $data_to_update['signature_hash'] = md5($signature);
                    $data_to_update['signature_timestamp'] = $signature_timestamp;
                    $data_to_update['signature_bas64_image'] = $drawn_signature;
                    $data_to_update['ip_address'] = $ip_address;
                    $data_to_update['user_agent'] = $users_agent;
                    $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                    $data_to_update['active_signature'] = $active_signature;
                    $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                    $this->onboarding_model->sign_document($company_sid, $users_type, $users_sid, $documents_assignment_sid, $data_to_update);
                    $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');
                    redirect('onboarding/sign_g_document/' . $unique_sid . '/' . $document_sid);
                    break;
            }
        }
    }

    public function sign_offer_letter($unique_sid, $document_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $document = $this->hr_documents_management_model->get_assigned_document('applicant', $applicant_sid, $document_sid, null, 1, 1);

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_info['sid'], $onboarding_details['job_list_sid'], 'applicant', $document['document_content'], $document['document_sid']);
                    $document['document_content'] = $document_content;
                }

                $data['document'] = $document;
                $company_sid = $company_info['sid'];
                $e_signature_data = get_e_signature($company_sid, $applicant_sid, 'applicant');
                $signed_flag = false;

                if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                    $e_signature_data = array();
                    $e_signature_data['company_sid'] = $document['company_sid'];
                    $e_signature_data['first_name'] = $document['first_name'];
                    $e_signature_data['last_name'] = $document['last_name'];
                    $e_signature_data['email_address'] = $document['email_address'];
                    $e_signature_data['user_type'] = $document['user_type'];
                    $e_signature_data['user_sid'] = $document['user_sid'];
                    $e_signature_data['signature'] = $document['signature'];
                    $e_signature_data['signature_hash'] = $document['signature_hash'];
                    $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                    $e_signature_data['signature_bas64_image'] = $document['signature_bas64_image'];
                    $e_signature_data['active_signature'] = $document['active_signature'];
                    $e_signature_data['ip_address'] = $document['ip_address'];
                    $e_signature_data['user_agent'] = $document['user_agent'];
                    $e_signature_data['user_consent'] = $document['user_consent'];
                    $signed_flag = true;
                }

                $data['e_signature_data'] = $e_signature_data;
                $data['signed_flag'] = $signed_flag;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;
                $data['user_consent'] = $document['user_consent'];

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/sign_g_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'sign_document':
                    $company_sid = $this->input->post('company_sid');
                    $users_type = $this->input->post('user_type');
                    $users_sid = $this->input->post('user_sid');
                    $ip_address = $this->input->post('ip_address');
                    $users_agent = $this->input->post('user_agent');
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $email_address = $this->input->post('email_address');
                    $signature_timestamp = $this->input->post('signature_timestamp');
                    $documents_assignment_sid = $this->input->post('documents_assignment_sid');
                    $active_signature = $this->input->post('active_signature');
                    $signature = $this->input->post('signature');
                    $drawn_signature = $this->input->post('drawn_signature');
                    $user_consent = $this->input->post('user_consent');
                    $data_to_update = array();
                    $data_to_update['first_name'] = $first_name;
                    $data_to_update['last_name'] = $last_name;
                    $data_to_update['email_address'] = $email_address;
                    $data_to_update['signature'] = $signature;
                    $data_to_update['signature_hash'] = md5($signature);
                    $data_to_update['signature_timestamp'] = $signature_timestamp;
                    $data_to_update['signature_bas64_image'] = $drawn_signature;
                    $data_to_update['ip_address'] = $ip_address;
                    $data_to_update['user_agent'] = $users_agent;
                    $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                    $data_to_update['active_signature'] = $active_signature;
                    $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                    $this->onboarding_model->sign_document($company_sid, $users_type, $users_sid, $documents_assignment_sid, $data_to_update);
                    $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');
                    redirect('onboarding/sign_offer_letter/' . $unique_sid . '/' . $document_sid);
                    break;
            }
        }
    }

    public function e_signature($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        $applicant_sid = '';

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $documents = empty($documents_data) ? array() : $documents_data['items_details'];
                $data['documents'] = $documents;
                $company_sid = $company_info['sid'];
                $e_signature_data = get_e_signature($company_sid, $applicant_sid, 'applicant');

                if (!empty($e_signature_data)) {
                    $data['consent'] = $e_signature_data['user_consent'];
                } else {
                    $data['consent'] = 0;
                }

                $data['e_signature_data'] = $e_signature_data;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/e_signature');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $form_post = $this->input->post();
            $insert_signature = set_e_signature($form_post);

            if ($insert_signature != 'error') {
                redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
            }
        }
    }

    private function get_single_record_from_array($records, $key, $value)
    {
        if (is_array($records)) {
            foreach ($records as $record) {
                foreach ($record as $k => $v) {
                    if ($k == $key && $v == $value) {
                        return $record;
                    }
                }
            }

            return array();
        } else {
            return array();
        }
    }

    public function learning_center($unique_sid)
    {
        $this->load->model('learning_center_model');
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $data['videos'] = $videos;
                $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions_new('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                foreach ($assigned_sessions as $key => $a_session) {
                    $attend_status = $a_session['attend_status'];

                    switch ($attend_status) {
                        case 'attended':
                        case 'unable_to_attend':
                            $assigned_sessions[$key]['uta_btn_status'] = 'disabled';
                            $assigned_sessions[$key]['wa_btn_status'] = 'disabled';
                            $assigned_sessions[$key]['a_btn_status'] = 'disabled';
                            break;
                        case 'will_attend':
                            $assigned_sessions[$key]['uta_btn_status'] = 'disabled';
                            $assigned_sessions[$key]['wa_btn_status'] = 'disabled';
                            $assigned_sessions[$key]['a_btn_status'] = 'enabled';
                            break;
                        default:
                            $assigned_sessions[$key]['uta_btn_status'] = 'enabled';
                            $assigned_sessions[$key]['wa_btn_status'] = 'enabled';
                            $assigned_sessions[$key]['a_btn_status'] = 'enabled';
                            break;
                    }
                }

                $data['assigned_sessions'] = $assigned_sessions;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/onboarding_my_learning_center');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');
            $unique_sid = $this->input->post('unique_sid');

            switch ($perform_action) {
                case 'mark_attend_status':
                    $session_assignment_sid = $this->input->post('session_assignment_sid');
                    $user_type = $this->input->post('user_type');
                    $user_sid = $this->input->post('user_sid');
                    $attend_status = $this->input->post('attend_status');
                    $this->learning_center_model->update_attend_status($user_type, $user_sid, $session_assignment_sid, $attend_status);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Status Updated!');
                    redirect('onboarding/learning_center/' . $unique_sid, 'refresh');
                    break;
            }
        }
    }

    public function watch_video($unique_sid, $video_sid)
    {
        $this->load->model('learning_center_model');
        $unique_key = $unique_sid;
        $video = $this->learning_center_model->get_single_online_video($video_sid);
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
        $applicant_sid = $onboarding_details['applicant_sid'];
        $assignment = $this->learning_center_model->get_video_assignment('applicant', $applicant_sid, $video_sid);
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['attempt_status'] = $assignment['attempt_status'];

                if ($assignment['attempt_status']) {
                    $attempted_questionnaire = $this->learning_center_model->get_video_questionnaire_attempt($video_sid, $assignment['sid']);
                    $data['attempted_questionnaire_timestamp'] = $attempted_questionnaire[0]['attend_timestamp'];
                }

                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $data['video'] = $video;
                $assignment = $this->learning_center_model->get_video_assignment('applicant', $applicant_sid, $video_sid);
                $data['assignment'] = $assignment;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);

                if ($video['video_source'] == 'upload') {
                    $video_url = base_url() . 'assets/uploaded_videos/' . $video['video_id'];
                    $data['video_url'] = $video_url;
                } else {
                    $data['video_url'] = '';
                }

                $data['job_details'] = array();

                if ($video['screening_questionnaire_sid'] > 0) {
                    $portal_screening_questionnaires = $this->learning_center_model->get_screening_questionnaire_by_id($video['screening_questionnaire_sid']);
                    $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                    $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                    $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                    $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                    $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                    $list['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                    $list['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                    $list['my_id'] = 'q_question_' . $video['screening_questionnaire_sid'];
                    $screening_questions_numrows = $this->learning_center_model->get_screenings_count_by_id($video['screening_questionnaire_sid']);

                    if ($screening_questions_numrows > 0) {
                        $screening_questions = $this->learning_center_model->get_screening_questions_by_id($video['screening_questionnaire_sid']);

                        foreach ($screening_questions as $qkey => $qvalue) {
                            $questions_sid = $qvalue['sid'];
                            $list['q_question_' . $video['screening_questionnaire_sid']][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                            $screening_answers_numrows = $this->learning_center_model->get_screening_answer_count_by_id($questions_sid);

                            if ($screening_answers_numrows) {
                                $screening_answers = $this->learning_center_model->get_screening_answers_by_id($questions_sid);

                                foreach ($screening_answers as $akey => $avalue) {
                                    $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                }
                            }
                        }
                    }

                    $data['job_details'] = $list;
                }

                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $attachments = $this->learning_center_model->get_attached_document($video_sid);
                $data['supported_documents'] = $attachments;

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/watch_video');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        } else {
            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'questionnaire') {
                $post_screening_questionnaires = $this->learning_center_model->get_screening_questionnaire_by_id($video['screening_questionnaire_sid']);
                $array_questionnaire = array();
                $questionnaire_name = $post_screening_questionnaires[0]['name'];
                $q_name = $post_screening_questionnaires[0]['name'];
                $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                $all_questions_ids = $_POST['all_questions_ids'];
                $questionnaire_serialize = '';
                $total_score = 0;
                $total_questionnaire_score = 0;
                $q_passing = 0;
                $array_questionnaire = array();
                $overall_status = 'Pass';
                $is_string = 0;
                $screening_questionnaire_results = array();

                foreach ($all_questions_ids as $key => $value) {
                    $q_passing = 0;
                    $post_questions_sid = $value;
                    $caption = 'caption' . $value;
                    $type = 'type' . $value;
                    $answer = $_POST[$type] . $value;
                    $questions_type = $_POST[$type];
                    $my_question = '';
                    $individual_score = 0;
                    $individual_passing_score = 0;
                    $individual_status = 'Pass';
                    $result_status = array();

                    if (isset($_POST[$caption])) {
                        $my_question = $_POST[$caption];
                    }

                    $my_answer = NULL;

                    if (isset($_POST[$answer])) {
                        $my_answer = $_POST[$answer];
                    }

                    if ($questions_type != 'string') { // get the question possible score
                        $q_passing = $this->learning_center_model->get_possible_score_of_questions($post_questions_sid, $questions_type);
                    }

                    if ($my_answer != NULL) { // It is required question
                        if (is_array($my_answer)) {
                            $answered = array();
                            $answered_result_status = array();
                            $answered_question_score = array();
                            $total_questionnaire_score += $q_passing;
                            $is_string = 1;

                            foreach ($my_answer as $answers) {
                                $result = explode('@#$', $answers);
                                $a = $result[0];
                                $answered_question_sid = $result[1];
                                $question_details = $this->learning_center_model->get_individual_question_details($answered_question_sid);

                                if (!empty($question_details)) {
                                    $questions_score = $question_details['score'];
                                    $questions_result_status = $question_details['result_status'];
                                    $questions_result_value = $question_details['value'];
                                }

                                $score = $questions_score;
                                $total_score += $questions_score;
                                $individual_score += $questions_score;
                                $individual_passing_score = $q_passing;
                                $answered[] = $a;
                                $result_status[] = $questions_result_status;
                                $answered_result_status[] = $questions_result_status;
                                $answered_question_score[] = $questions_score;
                            }
                        } else { // hassan WORKING area
                            $result = explode('@#$', $my_answer);
                            $total_questionnaire_score += $q_passing;
                            $a = $result[0];
                            $answered = $a;
                            $answered_result_status = '';
                            $answered_question_score = 0;

                            if (isset($result[1])) {
                                $answered_question_sid = $result[1];
                                $question_details = $this->learning_center_model->get_individual_question_details($answered_question_sid);

                                if (!empty($question_details)) {
                                    $questions_score = $question_details['score'];
                                    $questions_result_status = $question_details['result_status'];
                                    $questions_result_value = $question_details['value'];
                                }

                                $is_string = 1;
                                $score = $questions_score;
                                $total_score += $questions_score;
                                $individual_score += $questions_score;
                                $individual_passing_score = $q_passing;
                                $result_status[] = $questions_result_status;
                                $answered_result_status = $questions_result_status;
                                $answered_question_score = $questions_score;
                            }
                        }

                        if (!empty($result_status)) {
                            if (in_array('Fail', $result_status)) {
                                $individual_status = 'Fail';
                                $overall_status = 'Fail';
                            }
                        }
                    } else { // it is optional question
                        $answered = '';
                        $individual_passing_score = $q_passing;
                        $individual_score = 0;
                        $individual_status = 'Candidate did not answer the question';
                        $answered_result_status = '';
                        $answered_question_score = 0;
                    }

                    $array_questionnaire[$my_question] = array(
                        'answer' => $answered,
                        'passing_score' => $individual_passing_score,
                        'score' => $individual_score,
                        'status' => $individual_status,
                        'answered_result_status' => $answered_result_status,
                        'answered_question_score' => $answered_question_score
                    );
                } // here

                $questionnaire_result = $overall_status;
                $datetime = date('Y-m-d H:i:s');
                $remote_addr = getUserIP();
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $array_questionnaire_serialize = serialize($array_questionnaire);

                $screening_questionnaire_results = array(
                    'video_sid'                 => $video_sid,
                    'video_assign_sid'          => $assignment['sid'],
                    'video_title'               => $video['video_title'],
                    'company_sid'               => 0,
                    'questionnaire_name'        => $questionnaire_name,
                    'questionnaire'             => $array_questionnaire_serialize,
                    'questionnaire_result'      => $questionnaire_result,
                    'attend_timestamp'          => $datetime,
                    'questionnaire_ip_address'  => $remote_addr,
                    'questionnaire_user_agent'  => $user_agent
                );

                $this->learning_center_model->insert_questionnaire_result($screening_questionnaire_results);
                $this->learning_center_model->update_video_attempt_status('applicant', $applicant_sid, $video_sid);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Questionnaire Saved Successfully');
                redirect('onboarding/watch_video/' . $unique_key . '/' . $video_sid);
            }

            $video_sid = $this->input->post('video_sid');
            $user_type = $this->input->post('user_type');
            $user_sid = $this->input->post('user_sid');
            $this->learning_center_model->update_video_watched_status($user_type, $user_sid, $video_sid);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Video marked as watched!');
            redirect('onboarding/watch_video/' . $unique_key . '/' . $video_sid);
        }
    }

    function track_video($str)
    {
        $user_sid = $this->input->post('id');
        $user_type = $this->input->post('type');
        $video_sid = $this->input->post('v_id');
        $video_duration = $this->input->post('v_duration');
        $video_completed = $this->input->post('v_completed');
        $isWatched = $this->learning_center_model->get_video_assignment($user_type, $user_sid, $video_sid);

        if ($isWatched['watched'] == 0 && $isWatched['completed'] == 0) {
            $this->learning_center_model->update_video_completed_status($user_type, $user_sid, $video_sid, $video_duration);
            return date('m-d-Y h:i A');
        } elseif ($isWatched['watched'] == 1 && $isWatched['completed'] == 0 && $video_completed == 0) {
            $this->learning_center_model->update_video_watched_duration($user_type, $user_sid, $video_sid, $video_duration);
        } elseif ($isWatched['watched'] == 1 && $isWatched['completed'] == 0 && $video_completed == 1) {
            $this->learning_center_model->update_video_watched_completed($user_type, $user_sid, $video_sid, $video_duration);
        }
    }

    public function my_credentials($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');

        if ($this->form_validation->run() == false) {
            $data = array();
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $configuration = $this->onboarding_model->get_onboarding_configuration('applicant', $applicant_sid);
                $credentials_data = $this->get_single_record_from_array($configuration, 'section', 'credentials');
                $credentials = empty($credentials_data) ? array() : unserialize($credentials_data['items']);
                $data['credentials'] = $credentials != null ? $credentials : array();
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/my_credentials');
                $this->load->view('onboarding/on_boarding_footer');
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function validate_vimeo()
    {
        $url = $this->input->post('url');
        $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($url);
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $api_url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $response = curl_exec($cSession);
        curl_close($cSession);
        $response = json_decode($response, true);

        if (isset($response['video_id'])) {
            echo 'correct';
        } else {
            echo 'false';
        }
    }

    public function form_w9($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info']; //echo '<pre>'; print_r($onboarding_details);
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $data['w9_form'] = $this->onboarding_model->get_w9_form('applicant', $applicant_sid);

                if (sizeof($data['w9_form']) > 0) {
                    $previous_form = $this->form_wi9_model->fetch_form('w9', 'applicant', $applicant_sid);
                    $data['pre_form'] = $previous_form;
                    $field = array(
                        'field' => 'w9_name',
                        'label' => 'Name',
                        'rules' => 'xss_clean|trim|required'
                    );

                    $order_field = array(
                        'field' => 'w9_business_name',
                        'label' => 'Business Name',
                        'rules' => 'xss_clean|trim|required'
                    );

                    $config[] = $field;
                    $config[] = $order_field;
                    $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->form_validation->set_rules($config);

                    if ($this->form_validation->run() == FALSE) {
                        $this->load->view('onboarding/applicant_boarding_header', $data);
                        $this->load->view('onboarding/form_w9');
                        $this->load->view('onboarding/on_boarding_footer');
                    } else {
                        $formpost = $this->input->post(NULL, TRUE);

                        if (isset($formpost['w9_federaltax_classification'])) {
                            if ($formpost['w9_federaltax_classification'] == 'llc') {
                                $formpost['w9_federaltax_description'] = $formpost['w9_llc_federaltax_description'];
                            } elseif ($formpost['w9_federaltax_classification'] == 'other') {
                                $formpost['w9_federaltax_description'] = $formpost['w9_other_federaltax_description'];
                            }
                        }

                        $formpost['created_date'] = date('Y-m-d H:i:s');
                        $signature = get_e_signature($company_info['sid'], $applicant_sid, 'applicant');
                        $formpost['signature_timestamp'] = $signature['signature_timestamp'];
                        $formpost['signature_email_address'] = $signature['email_address'];
                        $formpost['signature_bas64_image'] = $signature['signature_bas64_image'];
                        $formpost['init_signature_bas64_image'] = $signature['init_signature_bas64_image'];
                        $formpost['signature_ip_address'] = $signature['ip_address'];
                        $formpost['signature_user_agent'] = $signature['user_agent'];
                        $formpost['active_signature'] = $signature['active_signature'];
                        $formpost['ip_address'] = getUserIP();
                        $formpost['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $formpost['user_consent'] = 1;
                        //
                        unset($formpost['w9_llc_federaltax_description']);
                        unset($formpost['w9_other_federaltax_description']);
                        unset($formpost['submit']);
                        //
                        $this->form_wi9_model->update_form('w9', 'applicant', $applicant_sid, $formpost);
                        //
                        $w9_sid = getVerificationDocumentSid($applicant_sid, 'applicant', 'w9');
                        keepTrackVerificationDocument($applicant_sid, 'applicant', 'completed', $w9_sid, 'w9', 'Blue Panel');
                        //
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');

                        redirect('onboarding/hr_documents' . '/' . $unique_sid, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Form Not Found');
                    redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                }
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function form_w4($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_sid'] = $company_info['sid'];
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $data['w4_form'] = $this->onboarding_model->get_original_w4_form('applicant', $applicant_sid);

                if (sizeof($data['w4_form']) > 0) {
                    $previous_form = $this->form_wi9_model->fetch_form('w4', 'applicant', $applicant_sid);
                    $data['pre_form'] = $previous_form;
                    $e_signature_data = get_e_signature($company_info['sid'], $applicant_sid, 'applicant');
                    $data['e_signature_data'] = $e_signature_data;
                    $field = array(
                        'field' => 'w4_first_name',
                        'label' => 'First Name',
                        'rules' => 'xss_clean|trim|required'
                    );

                    // $order_field = array(
                    //     'field' => 'w4_middle_name',
                    //     'label' => 'Middle Name',
                    //     'rules' => 'xss_clean|trim|required'
                    // );

                    if (isset($_GET['submit']) && $_GET['submit'] == 'Download PDF') {
                        //$view = $this->load->view('form_w4/form_w4', $data, TRUE);
                        $view = $this->load->view('form_w4/download_w4_2023', $data, TRUE);
                        $this->pdfgenerator->generate($view, 'Form W4', true, 'A4');
                    }

                    $config[] = $field;
                    $config[] = $order_field;
                    $data['save_post_url'] = current_url();
                    $data['users_type'] = 'applicant';
                    $data['users_sid'] = $applicant_sid;
                    $data['first_name'] = $onboarding_details['applicant_info']['first_name'];
                    $data['last_name'] = $onboarding_details['applicant_info']['last_name'];
                    $data['email'] = $onboarding_details['applicant_info']['email'];
                    $data['documents_assignment_sid'] = null;
                    // $data['signed_flag'] = false;
                    $data['signed_flag'] = isset($previous_form['user_consent']) && $previous_form['user_consent'] == 1 ? true : false;
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->form_validation->set_rules($config);

                    $assign_on = date("Y-m-d", strtotime($previous_form['sent_date']));
                    $compare_date = date("Y-m-d", strtotime('2020-01-06'));
                    $compare_date_2025 = date("Y-m-d", strtotime('2025-01-02'));
                  
                    if ($this->form_validation->run() == FALSE) {
                        $this->load->view('onboarding/applicant_boarding_header', $data);

                        if ($assign_on >= $compare_date_2025) {
                            $this->load->view('onboarding/2025_w4_form');
                        }elseif ($assign_on >= $compare_date) {
                            $this->load->view('onboarding/2020_w4_form');
                        } else {
                            $this->load->view('onboarding/form_w4');
                        }

                        // $this->load->view('onboarding/form_w4');
                        //  $this->load->view('onboarding/2020_w4_form');
                        $this->load->view('onboarding/on_boarding_footer');
                    } else {

                        $first_name = $this->input->post('w4_first_name');
                        $middle_name = $this->input->post('w4_middle_name') ?? '';
                        $last_name = $this->input->post('w4_last_name');
                        $ss_number = $this->input->post('ss_number');
                        $home_address = $this->input->post('home_address');
                        $city = $this->input->post('city');
                        $state = $this->input->post('state');
                        $zip = $this->input->post('zip');
                        $marriage_status = $this->input->post('marriage_status');
                        $signature_timestamp = date('Y-m-d H:i:s');
                        $signature_email_address = $this->input->post('email_address');
                        $signature_base64 = $this->input->post('signature_bas64_image');
                        $initial_base64 = $this->input->post('init_signature_bas64_image');
                        $signature_ip_address = $this->input->post('signature_ip_address');
                        $signature_user_agent = $this->input->post('signature_user_agent');
                        $emp_name = $this->input->post('emp_name');
                        $emp_address = $this->input->post('emp_address');
                        $first_date_of_employment = $this->input->post('first_date_of_employment');
                        $emp_identification_number = $this->input->post('emp_identification_number');
                        $user_consent = $this->input->post('user_consent');

                        if (!empty($first_date_of_employment)) {
                            $first_date_of_employment = DateTime::createFromFormat('m-d-Y', $first_date_of_employment)->format('Y-m-d');
                        }

                        if ($assign_on >= $compare_date) {
                            $mjsw_status            = $this->input->post('mjsw_status');
                            $dependents_children    = $this->input->post('dependents_children');
                            $other_dependents       = $this->input->post('other_dependents');
                            $claim_total_amount     = $this->input->post('claim_total_amount');
                            $other_income           = $this->input->post('other_income');
                            $other_deductions       = $this->input->post('other_deductions');
                            $additional_tax         = $this->input->post('additional_tax');
                            $signature_date         = $this->input->post('signature_date');
                            $mjw_two_jobs           = $this->input->post('mjw_two_jobs');
                            $mjw_three_jobs_a       = $this->input->post('mjw_three_jobs_a');
                            $mjw_three_jobs_b       = $this->input->post('mjw_three_jobs_b');
                            $mjw_three_jobs_c       = $this->input->post('mjw_three_jobs_c');
                            $mjw_pp_py              = $this->input->post('mjw_pp_py');
                            $mjw_divide             = $this->input->post('mjw_divide');
                            $dw_input_1             = $this->input->post('dw_input_1');
                            $dw_input_2             = $this->input->post('dw_input_2');
                            $dw_input_3             = $this->input->post('dw_input_3');
                            $dw_input_4             = $this->input->post('dw_input_4');
                            $dw_input_5             = $this->input->post('dw_input_5');

                            if (!empty($signature_date)) {
                                $signature_timestamp = DateTime::createFromFormat('m-d-Y', $signature_date)->format('Y-m-d');
                            }
                        } else {
                            $different_last_name = $this->input->post('different_last_name');
                            $number_of_allowance = $this->input->post('number_of_allowance');
                            $additional_amount = $this->input->post('additional_amount');
                            $claim_exempt = $this->input->post('claim_exempt');
                            $paw_yourself = $this->input->post('paw_yourself');
                            $paw_married = $this->input->post('paw_married');
                            $paw_head = $this->input->post('paw_head');
                            $paw_single_wages = $this->input->post('paw_single_wages');
                            $paw_child_tax = $this->input->post('paw_child_tax');
                            $paw_dependents = $this->input->post('paw_dependents');
                            $paw_other_credit = $this->input->post('paw_other_credit');
                            $paw_accuracy = $this->input->post('paw_accuracy');
                            $daaiw_estimate = $this->input->post('daaiw_estimate');
                            $daaiw_enter_status = $this->input->post('daaiw_enter_status');
                            $daaiw_subtract_line_2 = $this->input->post('daaiw_subtract_line_2');
                            $daaiw_estimate_of_adjustment = $this->input->post('daaiw_estimate_of_adjustment');
                            $daaiw_add_line_3_4 = $this->input->post('daaiw_add_line_3_4');
                            $daaiw_estimate__of_nonwage = $this->input->post('daaiw_estimate__of_nonwage');
                            $daaiw_subtract_line_6 = $this->input->post('daaiw_subtract_line_6');
                            $daaiw_divide_line_7 = $this->input->post('daaiw_divide_line_7');
                            $daaiw_enter_number_personal_allowance = $this->input->post('daaiw_enter_number_personal_allowance');
                            $daaiw_add_line_8_9 = $this->input->post('daaiw_add_line_8_9');
                            $temjw_personal_allowance = $this->input->post('temjw_personal_allowance');
                            $temjw_num_in_table_1 = $this->input->post('temjw_num_in_table_1');
                            $temjw_more_line2 = $this->input->post('temjw_more_line2');
                            $temjw_num_from_line2 = $this->input->post('temjw_num_from_line2');
                            $temjw_num_from_line1 = $this->input->post('temjw_num_from_line1');
                            $temjw_subtract_5_from_4 = $this->input->post('temjw_subtract_5_from_4');
                            $temjw_amount_in_table_2 = $this->input->post('temjw_amount_in_table_2');
                            $temjw_multiply_7_by_6 = $this->input->post('temjw_multiply_7_by_6');
                            $temjw_divide_8_by_period = $this->input->post('temjw_divide_8_by_period');
                        }

                        $data_to_update = array();
                        $data_to_update['first_name'] = $first_name;
                        $data_to_update['middle_name'] = $middle_name;
                        $data_to_update['last_name'] = $last_name;
                        $data_to_update['ss_number'] = $ss_number;
                        $data_to_update['home_address'] = $home_address;
                        $data_to_update['city'] = $city;
                        $data_to_update['state'] = $state;
                        $data_to_update['zip'] = $zip;
                        $data_to_update['marriage_status'] = $marriage_status;
                        $data_to_update['signature_timestamp'] = $signature_timestamp;
                        $data_to_update['signature_email_address'] = $signature_email_address;
                        $data_to_update['signature_bas64_image'] = $signature_base64;
                        $data_to_update['init_signature_bas64_image'] = $initial_base64;
                        $data_to_update['ip_address'] = $signature_ip_address;
                        $data_to_update['user_agent'] = $signature_user_agent;
                        $data_to_update['emp_name'] = $emp_name;
                        $data_to_update['emp_address'] = $emp_address;
                        $data_to_update['first_date_of_employment'] = $first_date_of_employment;
                        $data_to_update['emp_identification_number'] = $emp_identification_number;
                        $data_to_update['user_consent'] = $user_consent;

                        if ($assign_on >= $compare_date) {
                            $data_to_update['mjsw_status'] = $mjsw_status;
                            $data_to_update['dependents_children'] = $dependents_children;
                            $data_to_update['other_dependents'] = $other_dependents;
                            $data_to_update['claim_total_amount'] = $claim_total_amount;
                            $data_to_update['other_income'] = $other_income;
                            $data_to_update['other_deductions'] = $other_deductions;
                            $data_to_update['additional_tax'] = $additional_tax;
                            $data_to_update['mjw_two_jobs'] = $mjw_two_jobs;
                            $data_to_update['mjw_three_jobs_a'] = $mjw_three_jobs_a;
                            $data_to_update['mjw_three_jobs_b'] = $mjw_three_jobs_b;
                            $data_to_update['mjw_three_jobs_c'] = $mjw_three_jobs_c;
                            $data_to_update['mjw_pp_py'] = $mjw_pp_py;
                            $data_to_update['mjw_divide'] = $mjw_divide;
                            $data_to_update['dw_input_1'] = $dw_input_1;
                            $data_to_update['dw_input_2'] = $dw_input_2;
                            $data_to_update['dw_input_3'] = $dw_input_3;
                            $data_to_update['dw_input_4'] = $dw_input_4;
                            $data_to_update['dw_input_5'] = $dw_input_5;
                        } else {
                            $data_to_update['different_last_name'] = $different_last_name;
                            $data_to_update['number_of_allowance'] = $number_of_allowance;
                            $data_to_update['additional_amount'] = $additional_amount;
                            $data_to_update['claim_exempt'] = $claim_exempt;
                            $data_to_update['paw_yourself'] = $paw_yourself;
                            $data_to_update['paw_married'] = $paw_married;
                            $data_to_update['paw_head'] = $paw_head;
                            $data_to_update['paw_single_wages'] = $paw_single_wages;
                            $data_to_update['paw_child_tax'] = $paw_child_tax;
                            $data_to_update['paw_dependents'] = $paw_dependents;
                            $data_to_update['paw_other_credit'] = $paw_other_credit;
                            $data_to_update['paw_accuracy'] = $paw_accuracy;
                            $data_to_update['daaiw_estimate'] = $daaiw_estimate;
                            $data_to_update['daaiw_enter_status'] = $daaiw_enter_status;
                            $data_to_update['daaiw_subtract_line_2'] = $daaiw_subtract_line_2;
                            $data_to_update['daaiw_estimate_of_adjustment'] = $daaiw_estimate_of_adjustment;
                            $data_to_update['daaiw_add_line_3_4'] = $daaiw_add_line_3_4;
                            $data_to_update['daaiw_estimate__of_nonwage'] = $daaiw_estimate__of_nonwage;
                            $data_to_update['daaiw_subtract_line_6'] = $daaiw_subtract_line_6;
                            $data_to_update['daaiw_divide_line_7'] = $daaiw_divide_line_7;
                            $data_to_update['daaiw_enter_number_personal_allowance'] = $daaiw_enter_number_personal_allowance;
                            $data_to_update['daaiw_add_line_8_9'] = $daaiw_add_line_8_9;
                            $data_to_update['temjw_personal_allowance'] = $temjw_personal_allowance;
                            $data_to_update['temjw_num_in_table_1'] = $temjw_num_in_table_1;
                            $data_to_update['temjw_more_line2'] = $temjw_more_line2;
                            $data_to_update['temjw_num_from_line2'] = $temjw_num_from_line2;
                            $data_to_update['temjw_num_from_line1'] = $temjw_num_from_line1;
                            $data_to_update['temjw_subtract_5_from_4'] = $temjw_subtract_5_from_4;
                            $data_to_update['temjw_amount_in_table_2'] = $temjw_amount_in_table_2;
                            $data_to_update['temjw_multiply_7_by_6'] = $temjw_multiply_7_by_6;
                            $data_to_update['temjw_divide_8_by_period'] = $temjw_divide_8_by_period;
                        }
                        //  
                        $w4_sid = getVerificationDocumentSid($applicant_sid, 'applicant', 'w4');
                        keepTrackVerificationDocument($applicant_sid, 'applicant', 'completed', $w4_sid, 'w4', 'Blue Panel');
                        //
                        $this->form_wi9_model->update_form('w4', 'applicant', $applicant_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');
                        redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Form Not Found');
                    redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                }
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function form_i9($unique_sid)
    {
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Automoto HR Onboarding';
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                redirect('forms/i9/user/section/applicant/' . $onboarding_details['applicant_sid'] . '/applicant_onboarding');
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $data['session']['company_detail'] = $company_info;
                $data['company_info'] = $company_info;
                $data['unique_sid'] = $unique_sid;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $onboarding_progress = $this->onboarding_model->get_onboarding_progress_percentage('applicant', $applicant_sid, 10);
                $data['onboarding_progress'] = $onboarding_progress;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status; //$this->onboarding_model->check_company_eeoc_form_status($company_info['sid']);
                $data['section2_flag'] = false;
                $data['users_type'] = 'applicant';
                $data['i9_form'] = $this->onboarding_model->get_i9_form('applicant', $applicant_sid);
                $data['signed_flag'] = isset($data['i9_form']['user_consent']) && $data['i9_form']['user_consent'] == 1 ? true : false;
                $data['states'] = db_get_active_states(227);
                $company_sid = $company_info['sid'];
                $e_signature_data = get_e_signature($company_sid, $applicant_sid, 'applicant');
                $data['e_signature_data'] = $e_signature_data;
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                if (sizeof($data['i9_form']) > 0) {
                    $previous_form = $this->form_wi9_model->fetch_form('i9', 'applicant', $applicant_sid);
                    $data['pre_form'] = $previous_form;
                    $field = array(
                        'field' => 'section1_last_name',
                        'label' => 'Last Name',
                        'rules' => 'xss_clean|trim|required'
                    );

                    $order_field = array(
                        'field' => 'section1_first_name',
                        'label' => 'First Name',
                        'rules' => 'xss_clean|trim|required'
                    );

                    $config[] = $field;
                    $config[] = $order_field;

                    $data['prepare_signature'] = 'get_prepare_signature';
                    $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                    $this->form_validation->set_rules($config);

                    if ($this->form_validation->run() == FALSE) {
                        $this->load->view('onboarding/applicant_boarding_header', $data);
                        $this->load->view('onboarding/form_i9');
                        $this->load->view('onboarding/on_boarding_footer');
                    } else {
                        $formpost = $this->input->post(NULL, TRUE);
                        $insert_data = array(); //Section 1 Data Array Starts
                        $insert_data['section1_last_name'] = $formpost['section1_last_name'];
                        $insert_data['section1_first_name'] = $formpost['section1_first_name'];
                        $insert_data['section1_middle_initial'] = $formpost['section1_middle_initial'];
                        $insert_data['section1_other_last_names'] = $formpost['section1_other_last_names'];
                        $insert_data['section1_address'] = $formpost['section1_address'];
                        $insert_data['section1_apt_number'] = $formpost['section1_apt_number'];
                        $insert_data['section1_city_town'] = $formpost['section1_city_town'];
                        $insert_data['section1_state'] = $formpost['section1_state'];
                        $insert_data['section1_zip_code'] = $formpost['section1_zip_code'];
                        $insert_data['section1_date_of_birth'] = empty($formpost['section1_date_of_birth']) || $formpost['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_date_of_birth'])->format('Y-m-d H:i:s');
                        $insert_data['section1_social_security_number'] = $formpost['section1_social_security_number'];
                        $insert_data['section1_emp_email_address'] = $formpost['section1_emp_email_address'];
                        $insert_data['section1_emp_telephone_number'] = $formpost['section1_emp_telephone_number'];
                        $insert_data['section1_penalty_of_perjury'] = $formpost['section1_penalty_of_perjury'];
                        $options = array();

                        if ($formpost['section1_penalty_of_perjury'] == 'permanent-resident') {
                            $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                            $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                        } elseif ($formpost['section1_penalty_of_perjury'] == 'alien-work') {
                            $options['section1_alien_registration_number_one'] = $formpost['section1_alien_registration_number_one'];
                            $options['section1_alien_registration_number_two'] = $formpost['section1_alien_registration_number_two'];
                            $options['alien_authorized_expiration_date'] = empty($formpost['alien_authorized_expiration_date']) || $formpost['alien_authorized_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['alien_authorized_expiration_date'])->format('Y-m-d H:i:s');
                            $options['form_admission_number'] = $formpost['form_admission_number'];
                            $options['foreign_passport_number'] = $formpost['foreign_passport_number'];
                            $options['country_of_issuance'] = $formpost['country_of_issuance'];
                        }

                        $insert_data['section1_alien_registration_number'] = serialize($options);
                        $signature = get_e_signature($company_info['sid'], $applicant_sid, 'applicant');
                        $applicant_e_signature = $signature['signature_bas64_image'];
                        $applicant_e_signature_init = $signature['init_signature_bas64_image'];
                        $insert_data['section1_emp_signature'] = $applicant_e_signature;
                        $insert_data['section1_emp_signature_init'] = $applicant_e_signature_init;
                        $insert_data['section1_emp_signature_ip_address'] = getUserIP();
                        $insert_data['section1_emp_signature_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['section1_today_date'] = empty($formpost['section1_today_date']) || $formpost['section1_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_today_date'])->format('Y-m-d H:i:s');
                        $options = array();
                        $options['section1_preparer_or_translator'] = $formpost['section1_preparer_or_translator'];
                        $options['number-of-preparer'] = $formpost['number-of-preparer'];
                        $insert_data['section1_preparer_or_translator'] = serialize($options);
                        $insert_data['section1_preparer_today_date'] = empty($formpost['section1_preparer_today_date']) || $formpost['section1_preparer_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section1_preparer_today_date'])->format('Y-m-d H:i:s');
                        $insert_data['section1_preparer_last_name'] = $formpost['section1_preparer_last_name'];
                        $insert_data['section1_preparer_first_name'] = $formpost['section1_preparer_first_name'];
                        $insert_data['section1_preparer_city_town'] = $formpost['section1_preparer_city_town'];
                        $insert_data['section1_preparer_address'] = $formpost['section1_preparer_address'];
                        $insert_data['section1_preparer_state'] = $formpost['section1_preparer_state'];
                        $insert_data['section1_preparer_zip_code'] = $formpost['section1_preparer_zip_code'];
                        $insert_data['user_consent'] = 1;
                        // Section 1 Ends
                        if (sizeof($previous_form) == 0 || !$previous_form['applicant_flag'] || $applicant_sid == $previous_form['emp_app_sid']) {
                            $insert_data['emp_app_sid'] = $applicant_sid;
                            $insert_data['applicant_flag'] = 1;
                            $insert_data['applicant_filled_date'] = date('Y-m-d H:i:s');
                        } else if ($applicant_sid != $previous_form['emp_app_sid']) { // Section 2 Data Array Starts
                            // set mail body for DEVs
                            $mailbody = [];
                            $mailbody['usersid'] = $applicant_sid;
                            $mailbody['companysid'] = $data['session']['company_detail']['sid'];
                            $mailbody['previous_form_sid'] = $previous_form['emp_app_sid'];
                            $mailbody['reviewer_signature_base64'] = '';

                            $insert_data['section2_last_name'] = $formpost['section2_last_name'];
                            $insert_data['section2_first_name'] = $formpost['section2_first_name'];
                            $insert_data['section2_middle_initial'] = $formpost['section2_middle_initial'];
                            $insert_data['section2_citizenship'] = $formpost['section2_citizenship'];
                            $insert_data['section2_lista_part1_document_title'] = $formpost['section2_lista_part1_document_title'];
                            $insert_data['section2_lista_part1_issuing_authority'] = $formpost['section2_lista_part1_issuing_authority'];
                            $insert_data['section2_lista_part1_document_number'] = $formpost['section2_lista_part1_document_number'];
                            $insert_data['section2_lista_part1_expiration_date'] = empty($formpost['section2_lista_part1_expiration_date']) || $formpost['section2_lista_part1_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part1_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_lista_part2_document_title'] = $formpost['section2_lista_part2_document_title'];
                            $insert_data['section2_lista_part2_issuing_authority'] = $formpost['section2_lista_part2_issuing_authority'];
                            $insert_data['section2_lista_part2_document_number'] = $formpost['section2_lista_part2_document_number'];
                            $insert_data['section2_lista_part2_expiration_date'] = empty($formpost['section2_lista_part2_expiration_date']) || $formpost['section2_lista_part2_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part2_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_lista_part3_document_title'] = $formpost['section2_lista_part3_document_title'];
                            $insert_data['section2_lista_part3_issuing_authority'] = $formpost['section2_lista_part3_issuing_authority'];
                            $insert_data['section2_lista_part3_document_number'] = $formpost['section2_lista_part3_document_number'];
                            $insert_data['section2_lista_part3_expiration_date'] = empty($formpost['section2_lista_part3_expiration_date']) || $formpost['section2_lista_part3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part3_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_additional_information'] = $formpost['section2_additional_information'];
                            $insert_data['section2_listb_document_title'] = $formpost['section2_listb_document_title'];
                            $insert_data['section2_listb_issuing_authority'] = $formpost['section2_listb_issuing_authority'];
                            $insert_data['section2_listb_document_number'] = $formpost['section2_listb_document_number'];
                            $insert_data['section2_listb_expiration_date'] = empty($formpost['section2_listb_expiration_date']) || $formpost['section2_listb_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listb_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_listc_document_title'] = $formpost['section2_listc_document_title'];
                            $insert_data['section2_listc_dhs_extra_field'] = $formpost['section2_listc_dhs_extra_field'];
                            $insert_data['section2_listc_issuing_authority'] = $formpost['section2_listc_issuing_authority'];
                            $insert_data['section2_listc_document_number'] = $formpost['section2_listc_document_number'];
                            $insert_data['section2_listc_expiration_date'] = empty($formpost['section2_listc_expiration_date']) || $formpost['section2_listc_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listc_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_firstday_of_emp_date'] = empty($formpost['section2_firstday_of_emp_date']) || $formpost['section2_firstday_of_emp_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_firstday_of_emp_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_sig_emp_auth_rep'] = $formpost['section2_sig_emp_auth_rep'];
                            $insert_data['section2_today_date'] = empty($formpost['section2_today_date']) || $formpost['section2_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_today_date'])->format('Y-m-d H:i:s');
                            $insert_data['section2_title_of_emp'] = $formpost['section2_title_of_emp'];
                            $insert_data['section2_last_name_of_emp'] = $formpost['section2_last_name_of_emp'];
                            $insert_data['section2_first_name_of_emp'] = $formpost['section2_first_name_of_emp'];
                            $insert_data['section2_emp_business_name'] = $formpost['section2_emp_business_name'];
                            $insert_data['section2_emp_business_address'] = $formpost['section2_emp_business_address'];
                            $insert_data['section2_city_town'] = $formpost['section2_city_town'];
                            $insert_data['section2_state'] = $formpost['section2_state'];
                            $insert_data['section2_zip_code'] = $formpost['section2_zip_code'];
                            $insert_data['section3_pre_last_name'] = $formpost['section3_pre_last_name'];
                            $insert_data['section3_pre_first_name'] = $formpost['section3_pre_first_name'];
                            $insert_data['section3_pre_middle_initial'] = $formpost['section3_pre_middle_initial'];
                            $insert_data['section3_last_name'] = $formpost['section3_last_name'];
                            $insert_data['section3_first_name'] = $formpost['section3_first_name'];
                            $insert_data['section3_middle_initial'] = $formpost['section3_middle_initial'];
                            $insert_data['section3_rehire_date'] = empty($formpost['section3_rehire_date']) || $formpost['section3_rehire_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_rehire_date'])->format('Y-m-d H:i:s');
                            $insert_data['section3_document_title'] = $formpost['section3_document_title'];
                            $insert_data['section3_document_number'] = $formpost['section3_document_number'];
                            $insert_data['section3_expiration_date'] = empty($formpost['section3_expiration_date']) || $formpost['section3_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_expiration_date'])->format('Y-m-d H:i:s');
                            $insert_data['section3_emp_sign'] = $formpost['section3_emp_sign'];
                            $insert_data['section3_today_date'] = empty($formpost['section3_today_date']) || $formpost['section3_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_today_date'])->format('Y-m-d H:i:s');
                            $insert_data['section3_name_of_emp'] = $formpost['section3_name_of_emp'];
                            $insert_data['emp_app_sid'] = $applicant_sid;
                            $insert_data['employer_flag'] = 1;
                            $insert_data['employer_filled_date'] = date('Y-m-d H:i:s');
                        }

                        // set data for I9 tracking
                        $i9TrackerData = [];
                        $i9TrackerData['data'] = $insert_data;
                        $i9TrackerData['loggedIn_person_id'] = '0';
                        $i9TrackerData['previous_form_sid'] = $previous_form['emp_app_sid'];
                        $i9TrackerData['session_employer_id'] = $data['session']['employer_detail']['sid'];
                        $i9TrackerData['session_company_id'] = $data['session']['company_detail']['sid'];
                        $i9TrackerData['reviewer_signature_base64'] = $formpost['section3_emp_sign'];
                        $i9TrackerData['module'] = 'fi9/onboarding';
                        //
                        portalFormI9Tracker($applicant_sid, 'applicant', $i9TrackerData);

                        //
                        $this->form_wi9_model->update_form('i9', 'applicant', $applicant_sid, $insert_data);
                        //
                        $i9_sid = getVerificationDocumentSid($applicant_sid, 'applicant', 'i9');
                        keepTrackVerificationDocument($applicant_sid, 'applicant', 'completed', $i9_sid, 'i9', 'Blue Panel');
                        //
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Request Submitted Successfully!');
                        redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Form Not Found');
                    redirect('onboarding/hr_documents/' . $unique_sid, 'refresh');
                }
            } else { //Onboarding Complete or Expired
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function enable_disable_notification($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->onboarding_model->update_ems_notification($id, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    public function edit_ems_notification($sid)
    {
        echo 'lllll';
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $company_sid = $data["session"]["company_detail"]["sid"];
        $employer_sid = $data["session"]["employer_detail"]["sid"];
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
        $data['company_sid'] = $company_sid;
        $ems_notification = $this->onboarding_model->get_ems_notification_by_id($sid);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Onboarding Configuration';
            $employees = $this->onboarding_model->get_all_employees($company_sid); //Employees
            $employees_for_select = array();

            foreach ($employees as $employee) {
                $employees_for_select[$employee['sid']] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
            }

            $data['employees'] = $employees_for_select;
            $data['ems_notification'] = $ems_notification;
            $this->load->view('main/header', $data);
            $this->load->view('onboarding/configuration_dashboard_notification_edit');
            $this->load->view('main/footer');
        } else {
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $video_source = $this->input->post('video_source');
            $employees_assigned_to = $this->input->post('employees_assigned_to');
            $sort_order = $this->input->post('sort_order');
            $employees_assigned_sid = $this->input->post('employees_assigned_sid');
            $source_flag = $this->input->post('source-flag');
            $image_status = $this->input->post('image_status');
            $video_status = $this->input->post('video_status');
            $update_data['title'] = $title;
            $update_data['description'] = $description;
            $update_data['video_source'] = $video_source;
            $update_data['assigned_to'] = $employees_assigned_to;
            $update_data['sort_order'] = $sort_order;
            $update_data['image_status'] = $image_status;
            $update_data['video_status'] = $video_status;
            $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
            // $pictures = 'Docs.ac';
            if (!empty($pictures) && $pictures != 'error') {
                $update_data['image_code'] = $pictures;
            }

            if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                $random = date('H-i-s') . generateRandomString(5);
                $company_id = $data['session']['company_detail']['sid'];
                $target_file_name = basename($_FILES["video_upload"]["name"]);
                $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/";
                $target_file = $target_dir . $file_name;
                $filename = $target_dir . $company_id;

                if (!file_exists($filename)) {
                    mkdir($filename);
                }

                if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                } else {
                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                    redirect('onboarding/configuration', 'refresh');
                }

                $video_id = $file_name;
                $update_data['video_url'] = $video_id;
            } else {
                $video_id = $this->input->post('url');

                if ($video_source == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else {
                    $video_id = $this->vimeo_get_id($video_id);
                }

                if ($source_flag != 'upload') {
                    $update_data['video_url'] = $video_id;
                }
            }

            $this->onboarding_model->update_ems_notification($sid, $update_data);

            if ($employees_assigned_to == 'specific') {
                foreach ($employees_assigned_sid as $emp_sid) {
                    if (!in_array($emp_sid, $ems_notification[0]['assigned_emp'])) {
                        $update_data = array('ems_notification_sid' => $sid, 'employee_sid' => $emp_sid);
                        $this->onboarding_model->insert_assigned_configuration($update_data);
                    }
                }

                if (sizeof($ems_notification[0]['assigned_emp']) > 0) {
                    foreach ($ems_notification[0]['assigned_emp'] as $selected) {
                        if (!in_array($selected, $employees_assigned_sid)) {
                            $this->onboarding_model->delete_assigned_configuration($selected, $sid);
                        }
                    }
                }
            }

            $this->session->set_flashdata('message', '<strong>Success: </strong> Notification Updated Successfully!');
            redirect('onboarding/configuration', 'refresh');
        }
    }

    function convert_array_to_1d($my_array)
    {
        $return_array = array();

        if (is_array($my_array) && !empty($my_array)) {
            foreach ($my_array as $k => $v) {
                $return_array[] = $v['parent_sid'];
            }

            return $return_array;
        } else {
            return $return_array;
        }
    }

    function view_supported_attachment_document($unique_sid, $document_sid)
    {
        if ($this->form_validation->run() == false) {
            $data = array();
            $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

            if (!empty($onboarding_details)) {
                $data['onboarding_details'] = $onboarding_details;
                $applicant_info = $onboarding_details['applicant_info'];
                $data['applicant_info'] = $applicant_info;
                $company_info = $onboarding_details['company_info'];
                $company_sid = $onboarding_details['company_info']['sid'];
                $data['company_info'] = $company_info;
                $data['session']['company_detail'] = $company_info;
                $applicant_sid = $onboarding_details['applicant_sid'];
                $data['applicant_sid'] = $applicant_sid;
                $data['unique_sid'] = $unique_sid;
                $data['applicant'] = $applicant_info;
                $data['complete_steps'] = $this->onboarding_model->check_updated_sections($applicant_info['employer_sid'], 'applicant', $applicant_info['sid']);
                $company_eeo_status = 1;
                $extra_info = $company_info['extra_info'];

                if (!is_null($extra_info)) {
                    $extra_info = unserialize($extra_info);
                    $company_eeo_status = $extra_info['EEO'];
                }

                $data['company_eeoc_form_status'] = $company_eeo_status;
                $videos = $this->learning_center_model->get_my_online_videos('applicant', $applicant_sid);
                $learning_center_status = count($videos);
                $data['enable_learbing_center'] = false;
                $data['title'] = 'Learning Center - Supported Document';

                if ($learning_center_status > 0) {
                    $data['enable_learbing_center'] = true;
                } else {
                    $assigned_sessions = $this->learning_center_model->get_assigned_training_sessions('applicant', $applicant_sid);
                    $learning_center_status = count($assigned_sessions);

                    if ($learning_center_status > 0) {
                        $data['enable_learbing_center'] = true;
                    }
                }

                $tracking_document = '';
                $tracking_document = $this->learning_center_model->get_document_tracking($company_sid, $applicant_sid, 'applicant', $document_sid);
                $supporting_document = $this->learning_center_model->get_attach_document($document_sid, $company_sid);

                if (empty($supporting_document)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Document not found!');
                    redirect('learning_center/my_learning_center', 'refresh');
                }

                if (empty($tracking_document)) {
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['user_sid'] = $applicant_sid;
                    $data_to_insert['user_type'] = 'applicant';
                    $data_to_insert['document_sid'] = $document_sid;
                    $data_to_insert['is_preview'] = 1;
                    $data_to_insert['preview_date'] = date('Y-m-d H:i:s');
                    $this->learning_center_model->insert_preview_attach_document($data_to_insert);
                    $tracking_document = $this->learning_center_model->get_document_tracking($company_sid, $applicant_sid, 'applicant', $document_sid);
                }

                if ($tracking_document['is_preview'] == 1) {
                    $preview_status = '<strong class="text-success">Document Status:</strong> You have previewed this document';
                    $preview_date = my_date_format($tracking_document['preview_date']);
                    $preview_on = '<b>Previewed On: ' . $preview_date . '</b>';
                    $data['preview_status'] = $preview_status;
                    $data['preview_on'] = $preview_on;
                }

                if ($tracking_document['is_downloaded'] == 1) {
                    $download_status = '<strong class="text-success">Document Status:</strong> You have downloaded this document';
                    $download_date = my_date_format($tracking_document['downloaded_date']);
                    $download_on = '<b>download On: ' . $download_date . '</b>';
                    $data['download_status'] = $download_status;
                    $data['download_on'] = $download_on;
                    $data['download_button_css'] = 'btn-warning';
                    $data['download_button_text'] = 'Re-Download';
                } else {
                    $download_status = '<strong class="text-success">Document Status:</strong> You have not yet downloaded this document';
                    $data['download_status'] = $download_status;
                    $data['download_button_css'] = 'blue-button';
                    $data['download_button_text'] = 'Download';
                }

                $back_url = base_url('onboarding/watch_video/' . $unique_sid . '/' . $supporting_document['video_sid']);
                $learning_center_url = base_url('onboarding/learning_center/' . $unique_sid);
                $download_button_action = base_url('onboarding/download_supported_document/' . $unique_sid . '/' . $document_sid);
                $data['download_button_action'] = $download_button_action;
                $data['supporting_document'] = $supporting_document;
                $data['tracking_document'] = $tracking_document;
                $data['back_url'] = $back_url;
                $data['learning_center_url'] = $learning_center_url;

                $this->load->view('onboarding/applicant_boarding_header', $data);
                $this->load->view('onboarding/supporting_document_preview');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong> The Onboarding Url has now Expired Please Login or Contact Your HR for Help!');
                redirect('login', 'refresh');
            }
        }
    }

    public function download_supported_document($unique_sid, $document_sid)
    {
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $company_sid = $onboarding_details['company_info']['sid'];
            $applicant_sid = $onboarding_details['applicant_sid'];

            if ($this->form_validation->run() == false) {
                $document = $this->learning_center_model->get_attach_document($document_sid, $company_sid);
                $data['document'] = $document;
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document['upload_file_name'];
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['upload_file_name'], $temp_file_path);

                if (file_exists($temp_file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $file_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($temp_file_path));
                    $handle = fopen($temp_file_path, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                    unlink($temp_file_path);
                }

                $data_to_update = array();
                $data_to_update['is_downloaded'] = 1;
                $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');
                $this->learning_center_model->update_download_status($company_sid, $applicant_sid, 'applicant', $document_sid, $data_to_update);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    /**
     * Send email to onboarding applicant
     * Created on: 14-05-2019
     *
     * @param $company_sid Integer
     * @param $company_name String
     * @param $user_sid Integer
     * @param $unique_sid String
     *
     * @return VOID
     */
    public function send_email_to_onboarding_applicant($company_sid, $company_name, $user_sid, $unique_sid)
    {
        $applicant_info = $this->onboarding_model->get_applicant_information($user_sid);
        $applicant_email = $applicant_info['email'];
        $applicant_name = $applicant_info['first_name'] . ' ' . $applicant_info['last_name'];
        $onboarding_portal_link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url('onboarding/getting_started/' . $unique_sid) . '">Onboarding Portal</a>';
        $replacement_array = array();
        $replacement_array['company_name'] = $company_name;
        $replacement_array['applicant_name'] = $applicant_name;
        $replacement_array['onboarding_portal_link'] = $onboarding_portal_link;
        //
        $user_extra_info = array();
        $user_extra_info['user_sid'] = $user_sid;
        $user_extra_info['user_type'] = "applicant";
        //
        log_and_send_templated_email(APPLICANT_ONBOARDING_WELCOME, $applicant_email, $replacement_array, message_header_footer_domain($company_sid, $company_name), 1, $user_extra_info);
    }

    public function print_form_w4($unique_sid)
    {
        $data['title'] = 'Automoto HR Onboarding';
        $onboarding_details = $this->onboarding_model->get_details_by_unique_sid($unique_sid);

        if (!empty($onboarding_details)) {
            $company_sid  = $onboarding_details['company_info'];
            ['sid'];
            $applicant_sid = $onboarding_details['applicant_sid'];
            $data['title'] = 'Form W-4';

            $previous_form = $this->onboarding_model->get_original_w4_form('applicant', $applicant_sid);
            $data['pre_form'] = $previous_form;
            // $this->load->view('form_w4/print_w4_form', $data);
            $this->load->view('form_w4/print_w4_2023', $data);
        } else {
            redirect('login', "refresh");
        }
    }


    //
    function document($token)
    {
        // Load encryptipn library
        $this->load->library('encryption', 'encrypt');
        // Clean token
        $redirect_token = $token;
        $token = str_replace(['$eb$eb$1', '$eb$eb$2'], ['/', '+'], $token);
        // Decode and convert to array
        $d = explode('/', $this->encrypt->decode($token));
        //
        // Check for valid details
        if ($d[0] == "I9" || $d[0] == "w4") {
            if (count($d) < 3) redirect('/');
        } else {
            if (count($d) < 4) redirect('/');
        }
        //
        if ($d[0] == "I9") {
            redirect('forms/i9/user/section/applicant/' . $d[1] . '/public_link');
        }
        //
        $document = [];
        // Validate and check for expire
        $this->load->model('hr_documents_management_model', 'hdm');
        //
        if ($d[0] == "I9" || $d[0] == "w4") {
            $type = $d[0];
            $user_sid = $d[1];
            //
            $document = $this->hdm->checkForFederalFillableExiredToken(
                $type,
                $user_sid
            );
        } else {
            $type = 'document';
            //
            if (isset($d[4])) $type = $d[4];
            //
            $document = $this->hdm->checkForExiredToken(
                $d[0],
                $type
            );
        }
        //
        $data['session']['company_detail'] = $this->hdm->getCompanyInfo($document['company_sid']);
        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['company_name'] = $data['session']['company_detail']['CompanyName'];
        //
        if ($d[0] == "I9" || $d[0] == "w4") {
            $data['users_sid'] = $userSid = $d[1];
            $data['users_type'] = $userType = "applicant";
        } else {
            $data['users_sid'] = $userSid = $d[1];
            $data['users_type'] = $userType = $d[2];
        }
        //
        $data['first_name'] = $document['user']['first_name'];
        $data['last_name'] = $document['user']['last_name'];
        $data['email'] = $document['user']['email'];
        //
        $data['Signed'] = false;
        //
        $data['Expired'] = false;
        //
        $post = $this->input->post(NULL, FALSE);
        //
        if (count($post)) {
            //
            if ($post['perform_action'] == "sign_w4_document" || $post['perform_action'] == "sign_i9_document") {
                //
                $result = $this->saveFederalFillable($post);
                //
                if ($result['Status']) {
                    $data['Signed'] = true;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong>' . $result['Message']);
                    redirect('hr_documents_management/document' . $redirect_token, 'refresh');
                }
            } else {
                $save_input_values = array();
                $users_type = $data['users_type'];
                $users_sid = $data['users_sid'];
                $save_signature = $post['save_signature'];
                $save_initial = $post['save_signature_initial'];
                $save_date = $post['save_signature_date'];
                $user_consent = $post['user_consent'];
                $base64_pdf = $post['save_PDF'];

                if (isset($post['save_input_values']) && !empty($post['save_input_values'])) {
                    $save_input_values = $post['save_input_values'];
                }

                $save_input_values = serialize($save_input_values);

                $data_to_update = array();

                if ($save_signature == 'yes' || $save_initial == 'yes' || $save_date == 'yes') {
                    $signature = get_e_signature($data['company_sid'], $data['users_sid'], $data['users_type']);

                    if ($save_signature == 'yes') {
                        $data_to_update['signature_base64'] = $signature['signature_bas64_image'];
                    }

                    if ($save_initial == 'yes') {
                        $data_to_update['signature_initial'] = $signature['init_signature_bas64_image'];
                    }

                    if ($save_date == 'yes') {
                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                    }
                }

                $data_to_update['signature_email'] = $data['email'];
                $data_to_update['signature_ip'] = getUserIP();
                $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $data_to_update['submitted_description'] = $base64_pdf;
                $data_to_update['uploaded'] = 1;
                $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                $data_to_update['form_input_data'] = $save_input_values;
                $this->hdm->update_assigned_documents($post['document_sid'], $data['users_sid'], $data['users_type'], $data_to_update);
                //
                $data['Signed'] = true;
            }
        } else {
            //
            if ((!$document || !count($document)) || ($document['link_creation_time'] <= strtotime('now')) || (isset($document['user_consent']) && $document['user_consent'] == 1)) {
                $data['Expired'] = true;
            } else {
                //
                if ($type == 'document') {
                    //
                    if (!empty($document['document_description'])) {
                        $document_body = $document['document_description'];
                        $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', '{{short_text_required}}', '{{text_required}}', '{{text_area_required}}', '{{checkbox_required}}', 'select');
                        $magic_signature_codes = array('{{signature}}', '{{inital}}');

                        if (str_replace($magic_signature_codes, '', $document_body) != $document_body) {
                            $save_offer_letter_type = 'consent_only';
                        } else if (str_replace($magic_codes, '', $document_body) != $document_body) {
                            $save_offer_letter_type = 'save_only';
                        }
                    }
                    //
                    // if(preg_match('/{{(.*?)}}/', $document['document_description'])) $document['signature_required'] = true;
                    $document['save_offer_letter_type'] = $save_offer_letter_type;
                    //
                    $document['print_content'] = $document['document_description'];
                    //
                    $e[0] = $document;
                    //
                    isDocumentCompleted($e);
                    //
                    if (count($e)) $data['Signed'] = true;

                    //
                    if (!empty($document['document_description'])) {
                        replaceDocumentContentTags(
                            $document['document_description'],
                            $data['session']['company_detail']['sid'],
                            $d[1],
                            $d[2],
                            $document['document_sid']
                        );
                        replaceDocumentContentTags(
                            $document['print_content'],
                            $data['session']['company_detail']['sid'],
                            $document['user_sid'],
                            $document['user_type'],
                            $document['document_sid'],
                            true
                        );
                    }
                    //
                    $document['file_path'] = getFilePathForIframe($document);
                } else if ($type == 'dependents') {
                    //
                    $this->load->model('dependents_model');
                    //
                    $depedents = $this->dependents_model->get_dependant_info($userType, $userSid);
                    //
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];
                    //
                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $data['countries'] = $d;
                    $data['dependents'] = $depedents;
                } else if ($type == 'emergency_contacts') {
                    //
                    $this->load->model('emergency_contacts_model');
                    //
                    $emergencyContacts = $this->emergency_contacts_model->get_emergency_contacts($userType, $userSid);
                    //
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];
                    //
                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $data['countries'] = $d;
                    $data['emergencyContacts'] = $emergencyContacts;
                } else if ($type == 'drivers_license') {
                    //
                    $this->load->model('dashboard_model');
                    //
                    $data['license'] = $this->dashboard_model->get_license_info($userSid, $userType, 'drivers');
                } else if ($type == 'occupational_license') {
                    //
                    $this->load->model('dashboard_model');
                    //
                    $data['license'] = $this->dashboard_model->get_license_info($userSid, $userType, 'occupational');
                } else if ($type == 'direct_deposit') {
                    //
                    $this->load->model('direct_deposit_model');
                    $employee_number = $this->direct_deposit_model->get_user_extra_info($userType, $userSid, $data['company_sid']);
                    $data['employee_number'] = $employee_number;
                    $data['data'] = $this->direct_deposit_model->getDDI($userType, $userSid, $data['company_sid']);
                    //
                    if (isset($data['data'][0])) $data['data'][0]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][0]['voided_cheque']));
                    if (isset($data['data'][1])) $data['data'][1]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][1]['voided_cheque']));
                    //
                    $data[$userType] = $data['cn'] = $this->direct_deposit_model->getUserData($userSid, $userType);
                    $data['users_sign_info'] = get_e_signature($data['company_sid'], $userSid, $userType);
                } else if ($type == 'w4') {
                    $applicant_info = array();
                    $applicant_info['employer_sid'] = $data['company_sid'];
                    $applicant_info['sid'] = $d[1];
                    $applicant_info['first_name'] = $data['first_name'];
                    $applicant_info['last_name'] = $data['last_name'];
                    $applicant_info['email'] = $data['email'];
                    //
                    $data['applicant'] = $applicant_info;
                    //
                    $data['w4_form'] = $this->onboarding_model->get_original_w4_form('applicant', $d[1]);
                    $previous_form = $this->form_wi9_model->fetch_form('w4', 'applicant', $d[1]);
                    $data['pre_form'] = $previous_form;
                    $e_signature_data = get_e_signature($data['company_sid'], $d[1], 'applicant');
                    $data['e_signature_data'] = $e_signature_data;
                    $data['signed_flag'] = false;
                } else if ($type == 'I9') {
                    $applicant_info = array();
                    $applicant_info['employer_sid'] = $data['company_sid'];
                    $applicant_info['sid'] = $d[1];
                    $applicant_info['first_name'] = $data['first_name'];
                    $applicant_info['last_name'] = $data['last_name'];
                    $applicant_info['email'] = $data['email'];
                    //
                    $data['applicant'] = $applicant_info;
                    // 
                    $data['i9_form'] = $this->onboarding_model->get_i9_form('applicant', $d[1]);
                    $data['signed_flag'] = isset($data['i9_form']['user_consent']) && $data['i9_form']['user_consent'] == 1 ? true : false;
                    $data['states'] = db_get_active_states(227);
                    $e_signature_data = get_e_signature($data['company_sid'], $d[1], 'applicant');
                    $data['e_signature_data'] = $e_signature_data;
                    $previous_form = $this->form_wi9_model->fetch_form('i9', 'applicant', $d[1]);
                    $data['pre_form'] = $previous_form;
                    $data['form'] = $previous_form;
                    $data['prepare_signature'] = 'get_prepare_signature';
                }
                //
                $data['document'] = $document;
            }
            $data['type'] = $type;
            //
            $data[$data['users_type']] = [
                'sid' => $data['users_sid'],
                'parent_sid' => $data['company_sid'],
                'employer_sid' => $data['company_sid'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email']
            ];
        }


        $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($document['company_sid']);

        $data['dependents_yes_text'] = $this->lang->line('dependents_yes_text');
        $data['dependents_no_text'] = $this->lang->line('dependents_no_text');


        $this->load->view('onboarding/applicant_boarding_header_public', $data);
        //
        $page = 'public/documents/document_public';
        if ($type != 'document') {
            if ($d[0] == "I9") {
                $page = 'public/documents/form_i9_new';
            } else if ($d[0] == "w4") {
                $page = 'public/documents/form_w4';
            } else {
                $page = 'public/documents/general';
            }
        }

        if ($data['Signed']) $page = 'public/documents/signed_public';
        if ($data['Expired']) $page = 'public/documents/expired_public';
        //
        $this->load->view($page);
        $this->load->view('onboarding/on_boarding_footer');
    }

    //
    function mark_document()
    {
        //
        $post = $this->input->post(NULL, FALSE);
        //
        $resp = [
            'Status' => FALSE,
            'Response' => 'Invalid Request.'
        ];
        //
        $this->load->model('hr_documents_management_model', 'hrm');
        //
        if ($post['action'] == 'acknowledge') {
            //
            $this->hrm->updateAssignedDocument(
                $post['assignedDocumentSid'],
                [
                    'acknowledged' => 1,
                    'acknowledged_date' => date('Y-m-d H:i:s', strtotime('now'))
                ]
            );
            //
            $resp['Status'] = TRUE;
            $resp['Response'] = "Document acknowledged successfully.";
            //
            $this->res($resp);
        } else if ($post['action'] == 'download') {
            //
            $this->hrm->updateAssignedDocument(
                $post['assignedDocumentSid'],
                [
                    'downloaded' => 1,
                    'downloaded_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'pdf_base64' => isset($post['pdf']) ? $post['pdf'] : NULL
                ]
            );
            //
            $resp['Status'] = TRUE;
            $resp['Response'] = "Document downloaded successfully.";
            //
            $this->res($resp);
        }
    }


    //
    function download($assignedDocumentSid)
    {
        //
        $data = [];
        //
        $this->load->model('hr_documents_management_model', 'hdm');
        //
        $document = $this->hdm->checkForExiredToken(
            $assignedDocumentSid
        );
        //
        if (!count($document)) {
            die('Not found');
        }
        //
        $filepath = getFilePathForIframe($document, false);
        //
        $dir = APPPATH . '../temp_files/';
        //
        if (!is_dir($dir)) mkdir($dir, 777, true);
        //
        if ($document['document_type'] == 'hybrid_document') {
            //
            $this->load->library('zip');
            //
            $download_file = $document['document_title'] . '-' . date('Y_m_d_H_i_s') . '.zip';
            //
            $dirs = $dir . $document['document_title'] . '-part1.pdf';
            //
            downloadFileFromAWS($dirs, $filepath);
            //
            $this->zip->read_file($dirs);
            unlink($dirs);
            //
            $dirs = $dir . $document['document_title'] . '-part2.pdf';
            //
            $f = fopen($dirs, 'w');
            //
            fwrite($f, base64_decode(str_replace('data:application/pdf;base64,', '', $document['pdf_base64'])));
            fclose($f);
            $this->zip->read_file($dirs);
            unlink($dirs);
            //
            $this->zip->download($download_file);
            //
            unlink($download_file);
        }
        //
        if ($document['document_type'] == 'uploaded') {
            //
            $dir .= $document['document_title'] . '.pdf';
            //
            downloadFileFromAWS($dir, $filepath);
            //
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($dir) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir));
            flush(); // Flush system output buffer
            readfile($dir);
            unlink($dir);
        }
        //
        if ($document['document_type'] == 'generated') {
            //
            $dir .= $document['document_title'] . '.pdf';
            //
            $f = fopen($dir, 'w');
            //
            fwrite($f, base64_decode(str_replace('data:application/pdf;base64,', '', $document['pdf_base64'])));
            fclose($f);
            //
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($dir) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir));
            flush(); // Flush system output buffer
            readfile($dir);
            unlink($dir);
        }
    }

    //
    private function res($resp)
    {
        header('Content-Type: application/json');
        echo json_encode($resp);
    }

    // Expire link crons
    function expire_document_links()
    {
        //
        $this->load->model('hr_documents_management_model', 'hrm');
        //
        $this->hrm->expireLinks();
    }

    //
    function dependent_add()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->db->insert(
            'dependant_information',
            [
                'users_sid' => $post['userSid'],
                'users_type' => $post['userType'],
                'company_sid' => $post['companySid'],
                'dependant_details' => serialize([
                    'first_name' => $post['firstName'],
                    'last_name' => $post['lastName'],
                    'address' => $post['address'],
                    'address_line' => $post['address2'],
                    'Location_Country' => $post['country'],
                    'Location_State' => $post['state'],
                    'city' => $post['city'],
                    'postal_code' => $post['postalCode'],
                    'phone' => $post['phone'],
                    'birth_date' => $post['birthDate'],
                    'relationship' => $post['relationship'],
                    'ssn' => $post['ssn'],
                    'gender' => $post['gender'],
                    'family_member' => $post['familyMember']
                ])
            ]
        );
        //
        $this->db
            ->where('user_sid', $post['userSid'])
            ->where('user_type', $post['userType'])
            ->where('document_type', 'dependents')
            ->where('company_sid', $post['companySid'])
            ->update(
                'documents_assigned_general',
                [
                    'is_completed' => 1
                ]
            );
    }

    //
    function dependent_edit()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->db
            ->where('sid', $post['sid'])
            ->update(
                'dependant_information',
                [
                    'dependant_details' => serialize([
                        'first_name' => $post['firstName'],
                        'last_name' => $post['lastName'],
                        'address' => $post['address'],
                        'address_line' => $post['address2'],
                        'Location_Country' => $post['country'],
                        'Location_State' => $post['state'],
                        'city' => $post['city'],
                        'postal_code' => $post['postalCode'],
                        'phone' => $post['phone'],
                        'birth_date' => $post['birthDate'],
                        'relationship' => $post['relationship'],
                        'ssn' => $post['ssn'],
                        'gender' => $post['gender'],
                        'family_member' => $post['familyMember']
                    ])
                ]
            );
        //
        $this->db
            ->where('user_sid', $post['userSid'])
            ->where('user_type', $post['userType'])
            ->where('document_type', 'dependents')
            ->where('company_sid', $post['companySid'])
            ->update(
                'documents_assigned_general',
                [
                    'is_completed' => 1
                ]
            );
    }

    //
    function emergency_contacts_add()
    {
        //
        $session = $this->session->userdata('logged_in');
        $post = $this->input->post(NULL, TRUE);
        //
        $this->db->insert(
            'emergency_contacts',
            [
                'users_sid' => $post['userSid'],
                'users_type' => $post['userType'],
                'first_name' => $post['firstName'],
                'last_name' => $post['lastName'],
                'email' => $post['email'],
                'Location_State' => $post['state'],
                'Location_Country' => $post['country'],
                'Location_City' => $post['city'],
                'Location_ZipCode' => $post['postalCode'],
                'Location_Address' => $post['address'],
                'PhoneNumber' => $post['phone'],
                'Relationship' => $post['relationship'],
                'priority' => $post['priority']
            ]
        );
        //
        $this->db
            ->where('user_sid', $post['userSid'])
            ->where('user_type', $post['userType'])
            ->where('document_type', 'emergency_contacts')
            ->where('company_sid', $post['companySid'])
            ->update(
                'documents_assigned_general',
                [
                    'is_completed' => 1
                ]
            );
        //-- Send Email 
        if ($this->onboarding_model->get_notification_email_configuration($post['companySid']) > 0) {
            // Send document completion alert
            broadcastAlert(
                DOCUMENT_NOTIFICATION_TEMPLATE,
                'general_information_status',
                'emergency_contact',
                $post['companySid'],
                $session['company_detail']['CompanyName'],
                $post['firstName'],
                $post['lastName'],
                $post['userSid'],
                [],
                $post['userType']

            );
        }
    }

    //
    function emergency_contacts_edit()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->db
            ->where('sid', $post['sid'])
            ->update(
                'emergency_contacts',
                [
                    'first_name' => $post['firstName'],
                    'last_name' => $post['lastName'],
                    'email' => $post['email'],
                    'Location_State' => $post['state'],
                    'Location_Country' => $post['country'],
                    'Location_City' => $post['city'],
                    'Location_ZipCode' => $post['postalCode'],
                    'Location_Address' => $post['address'],
                    'PhoneNumber' => $post['phone'],
                    'Relationship' => $post['relationship'],
                    'priority' => $post['priority']
                ]
            );
        //
        $this->db
            ->where('user_sid', $post['userSid'])
            ->where('user_type', $post['userType'])
            ->where('document_type', 'emergency_contacts')
            ->where('company_sid', $post['companySid'])
            ->update(
                'documents_assigned_general',
                [
                    'is_completed' => 1
                ]
            );
    }

    /** 
     * TODO
     * Re-validate incoming file
     * 
     * */
    function license_edit()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $fileName = null;
        //
        if (!empty($_FILES['file'])) $fileName = put_file_on_aws('file');
        //
        if ($post['sid'] != 0) {
            $d = [
                'license_details' => serialize([
                    'license_type' => $post['type'],
                    'license_authority' => $post['authority'],
                    'license_class' => $post['class'],
                    'license_number' => $post['number'],
                    'license_issue_date' => $post['issueDate'],
                    'license_expiration_date' => $post['expirationDate'],
                    'license_indefinite' => $post['indefinite'],
                    'license_notes' => $post['notes']
                ])
            ];
            //
            if ($fileName != null) $d['license_file'] = $fileName;
            //
            $this->db
                ->where('sid', $post['sid'])
                ->update(
                    'license_information',
                    $d
                );
        } else {
            $this->db
                ->insert(
                    'license_information',
                    [
                        'users_sid' => $post['userSid'],
                        'users_type' => $post['userType'],
                        'license_type' => $post['ctype'],
                        'license_file' => $fileName,
                        'license_details' => serialize([
                            'license_type' => $post['type'],
                            'license_authority' => $post['authority'],
                            'license_class' => $post['class'],
                            'license_number' => $post['number'],
                            'license_issue_date' => $post['issueDate'],
                            'license_expiration_date' => $post['expirationDate'],
                            'license_indefinite' => $post['indefinite'],
                            'license_notes' => $post['notes']
                        ])
                    ]
                );
        }
        //
        $this->db
            ->where('user_sid', $post['userSid'])
            ->where('user_type', $post['userType'])
            ->where('document_type', $post['ctype'] == 'drivers' ? 'drivers_license' : 'occupational_license')
            ->where('company_sid', $post['companySid'])
            ->update(
                'documents_assigned_general',
                [
                    'is_completed' => 1
                ]
            );
    }

    //
    function handler()
    {
        //
        $post = $this->input->post();
        //
        $company_sid = isset($post['companySid']) ? $post['companySid'] : $this->session->userdata('logged_in')['company_detail']['sid'];
        $employer_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];
        $company_name =  isset($post['companyName']) ? $post['companyName'] : $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        //
        switch ($post['action']) {
            case 'send_resume_request':
                //
                $data                       = array();
                $user_info                  = array();
                $emailTemplate              = '';
                $default_subject            = '';
                $default_template           = '';
                $user_sid                   = $post['user_sid'];
                $user_type                  = $post['user_type'];
                $job_list_sid               = $post['job_list_sid'];
                $requested_job_sid          = $post['requested_job_sid'];
                $requested_job_type         = $post['requested_job_type'];

                $emailTemplate = $this->onboarding_model->get_send_resume_template($company_sid);

                if (!empty($emailTemplate)) {
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['message_body']);
                } else {
                    $emailTemplate = get_email_template(SEND_RESUME_REQUEST);
                    $default_subject = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['subject']);
                    $default_template  = replace_tags_for_document($company_sid, $user_sid, $user_type, $emailTemplate['text']);
                }

                $user_info = $this->onboarding_model->get_applicant_information($user_sid);

                if (empty($user_info)) {
                    $resp = array();
                    $resp['Status'] = FALSE;
                    $resp['Response'] = '<strong>Success: </strong> Applicant not found!';
                    header('Content-Type: application/json');
                    echo @json_encode($resp);
                    exit(0);
                }

                $verification_key = '';
                $applicant_email = $user_info['email'];

                if ($user_info['verification_key'] == NULL || empty($user_info['verification_key'])) {
                    $verification_key = random_key(80);
                    $this->onboarding_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);
                } else {
                    $verification_key = $user_info['verification_key'];
                }

                $data_to_insert = array();
                $data_to_insert['company_sid']              = $company_sid;
                $data_to_insert['user_type']                = $user_type;
                $data_to_insert['user_sid']                 = $user_sid;
                $data_to_insert['user_email']               = $applicant_email;
                $data_to_insert['requested_by']             = $employer_sid;
                $data_to_insert['requested_subject']        = $default_subject;
                $data_to_insert['requested_message']        = $default_template;
                $data_to_insert['requested_ip_address']     =  getUserIP();
                $data_to_insert['requested_user_agent']     = $_SERVER['HTTP_USER_AGENT'];
                $data_to_insert['request_status']           = 1;
                $data_to_insert['requested_date']           = date('Y-m-d H:i:s', strtotime('now'));
                $data_to_insert['job_sid']                  = $requested_job_sid;
                $data_to_insert['job_type']                 = $requested_job_type;


                $this->onboarding_model->deactivate_old_resume_request($company_sid, $user_type, $user_sid, $requested_job_sid, $requested_job_type);
                $this->onboarding_model->insert_resume_request($data_to_insert);

                $subject = $default_subject;
                $message_body = $default_template;

                $requested_job_sid = $this->encryption->encrypt($requested_job_sid);
                $requested_job_sid = str_replace('/', '$job', $requested_job_sid);
                $requested_job_type = $this->encryption->encrypt($requested_job_type);
                $requested_job_type = str_replace('/', '$type', $requested_job_type);

                $url = base_url() . 'onboarding/send_requested_resume/' . $verification_key . '/' . $requested_job_sid . '/' . $requested_job_type;
                $link_btn = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Send Resume</a>';
                //
                $message_body = str_replace('{{link}}', $link_btn, $message_body);
                //
                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $applicant_email;
                $from_name = ucwords(STORE_DOMAIN);
                $email_hf = message_header_footer_domain($company_sid, $company_name);
                $body = $email_hf['header']
                    . $message_body
                    . $email_hf['footer'];
                //
                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                //
                $resp = array();
                $resp['Status'] = TRUE;
                $resp['Response'] = '<strong>Success! </strong> You have successfully sent a resume request.';
                header('Content-Type: application/json');
                echo @json_encode($resp);
                exit(0);
                break;
        }
    }

    function my_eeoc_form()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            //
            $company_detail = $session['company_detail'];
            $employee_detail = $session['employer_detail'];
            //
            $employee_sid = $employee_detail['sid'];
            $security_details = db_get_access_level_details($employee_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $company_detail['sid'];
            $company_name = $employee_detail['CompanyName'];
            $user_type = 'employee';

            if (!$this->hr_documents_management_model->hasEEOCPermission($company_sid, 'eeo_on_employee_document_center')) {
                return redirect('hr_documents_management/my_documents');
            }
            //
            $print_url = base_url('hr_documents_management/print_eeoc_form/print' . '/' . $employee_sid . '/' . $user_type);
            $download_url = base_url('hr_documents_management/print_eeoc_form/download' . '/' . $employee_sid . '/' . $user_type);
            $eeo_form_status = 1;
            $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($employee_sid, $user_type);
            //
            if ($eeo_form_info['status'] == 0) {
                redirect('hr_documents_management/my_documents', 'refresh');
            }
            //
            if ((isset($eeo_form_info['gender']) && empty($eeo_form_info['gender'])) || empty($eeo_form_info)) {
                $gender = get_user_gender($employee_sid, $user_type);
                $eeo_form_info['gender'] = $gender;
            }
            //
            $data['session']            = $session;
            $data['title']              = 'My EEOC';
            $data['company_sid']        = $company_sid;
            $data['company_info']       = $company_detail;
            $data['first_name']         = $employee_detail['first_name'];
            $data['last_name']          = $employee_detail['last_name'];
            $data['email']              = $employee_detail['email'];
            $data['employee']           = $employee_detail;
            $data['users_type']         = $user_type;
            $data['print_url']          = $print_url;
            $data['download_url']       = $download_url;
            $data['eeo_form_status']    = $eeo_form_status;
            $data['eeo_form_info']      = $eeo_form_info;
            $data['id']                 = $eeo_form_info['sid'];
            $data['location']           = "Blue Panel";
            $data['dl_citizen']         = getEEOCCitizenShipFlag($company_sid);
            //
            $this->load->view('onboarding/on_boarding_header', $data);
            $this->load->view('eeo/employee_eeoc');
            $this->load->view('onboarding/on_boarding_footer');
        } else {
            redirect('login', 'refresh');
        }
    }




    // Approval Flow 




    /**
     * Handle document approval flow
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $document_sid
     * @param string $initiator_note
     * @param array  $approvers_list
     * @param string $send_email
     * @param array  $managers_list
     * 
     * @return
     */
    private function HandleApprovalFlow(
        $document_sid,
        $initiator_note,
        $approvers_list,
        $send_email,
        $managers_list
    ) {

        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        //
        // Set insert data array
        //
        $ins = [];
        $ins['company_sid'] = $company_sid;
        $ins['document_sid'] = $document_sid;
        $ins['assigned_by'] = $employer_sid;
        $ins['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['assigner_note'] = $initiator_note;
        $ins['status'] = 1;
        $ins['is_pending'] = 0; // 0 = Pending, 1 = Accepted, 2 = Rejected
        //
        // Lets revoke all previous document flows if exist
        $this->hr_documents_management_model->revoke_document_previous_flow($document_sid);

        // Lets insert the record
        $approvalInsertId = $this->hr_documents_management_model->insert_documents_assignment_flow($ins);
        //
        // Update user assigned document
        $this->hr_documents_management_model->change_document_approval_status(
            $document_sid,
            [
                'approval_process' => 1,
                'approval_flow_sid' => $approvalInsertId,
                'sendEmail' => $send_email,
                'managersList' => $managers_list,
                'has_approval_flow' => 1,
                'document_approval_employees' => $approvers_list,
                'document_approval_note' => $initiator_note,
            ]
        );
        //
        $this->AddAndSendNotificationsToApprovalEmployees(
            $approvalInsertId,
            $document_sid,
            $approvers_list,
            $initiator_note
        );
        //
        return true;
    }

    /**
     * Add and sends email notifications
     * to selected approval employees
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $approval_flow_sid
     * @param number $document_sid
     * @param array  $approvers_list
     * @param string $initiator_note
     */
    function AddAndSendNotificationsToApprovalEmployees(
        $approval_flow_sid,
        $document_sid,
        $approvers_list,
        $initiator_note
    ) {

        if (!empty($approvers_list)) {
            $approvalEmployees = explode(",", $approvers_list);
            //
            foreach ($approvalEmployees as $key => $approver_sid) {
                $is_default_approver = $this->hr_documents_management_model->is_default_approver($approver_sid);
                //
                if ($is_default_approver) {
                    $data_to_insert = array();
                    $data_to_insert['portal_document_assign_sid'] = $approval_flow_sid;
                    $data_to_insert['assigner_sid'] = $approver_sid;
                    //
                    if ($key == 0) {
                        $data_to_insert['assign_on'] = date('Y-m-d H:i:s', strtotime('now'));
                        $data_to_insert['assigner_turn'] = 1;
                    }
                    //
                    $this->hr_documents_management_model->insert_assigner_employee($data_to_insert);
                    //
                    if ($key == 0) {
                        //
                        // Send Email to first approver of this document
                        $this->SendEmailToCurrentApprover($document_sid);
                    }
                }
            }
        } else {

            $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
            //
            $default_approver = $this->hr_documents_management_model->getDefaultApprovers(
                $document_info['company_sid'],
                $document_info['approval_flow_sid'],
                $document_info['has_approval_flow']
            );

            if (!empty($default_approver)) {
                //
                $approver_sid = 0;
                $approver_email = "";
                //
                if (is_numeric($default_approver) && $default_approver > 0) {
                    $approver_sid = $default_approver;
                    //
                    $this->hr_documents_management_model->change_document_approval_status(
                        $document_sid,
                        [
                            'document_approval_employees' => $approver_sid
                        ]
                    );
                } else {
                    $approver_email = $default_approver;
                }
                //

                $this->hr_documents_management_model->insert_assigner_employee(
                    [
                        'portal_document_assign_sid' =>  $document_info['approval_flow_sid'],
                        'assigner_sid' => $approver_sid,
                        'approver_email' => $approver_email,
                        'assign_on' =>  date('Y-m-d H:i:s', strtotime('now')),
                        'assigner_turn' => 1,
                    ]
                );
                //
                // Send Email to first approver of this document
                $this->SendEmailToCurrentApprover($document_sid);
            }
        }
    }

    function SendEmailToCurrentApprover($document_sid)
    {

        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
        //
        $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
        //
        $approver_info = array();
        $current_approver_reference = '';
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            //
            $default_approver = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
            //
            $approver_name = explode(" ", $default_approver["contact_name"]);
            //
            $approver_info['first_name'] = isset($approver_name[0]) ? $approver_name[0] : "";
            $approver_info['last_name'] = isset($approver_name[1]) ? $approver_name[1] : "";
            $approver_info['email'] = $default_approver["email"];
            //
            $current_approver_reference = $default_approver["email"];
        } else {
            //
            $approver_info = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $current_approver_info["assigner_sid"]);
            //
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }

        //
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        //
        // Get the initiator name
        $document_initiator_name = getUserNameBySID($approvers_flow_info["assigned_by"]);
        //
        // Get the company name
        $company_name = getCompanyNameBySid($document_info['company_sid']);
        //
        // Get assigned document user name
        if ($document_info['user_type'] == 'employee') {
            //
            $t = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        }
        //
        $hf = message_header_footer_domain($document_info['company_sid'], $company_name);
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $accept_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'accept')
        );
        //
        $reject_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'reject')
        );
        //
        $view_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'view')
        );
        //
        $approval_public_link_accept = base_url("hr_documents_management/public_approval_document") . '/' . $accept_code;
        $approval_public_link_reject = base_url("hr_documents_management/public_approval_document") . '/' . $reject_code;
        $approval_public_link_view = base_url("hr_documents_management/public_approval_document") . '/' . $view_code;
        // 
        $replacement_array['initiator']             = $document_initiator_name;
        $replacement_array['contact-name']          = $document_assigned_user_name;
        $replacement_array['company_name']          = ucwords($company_name);
        $replacement_array['username']              = $replacement_array['contact-name'];
        $replacement_array['firstname']             = $approver_info['first_name'];
        $replacement_array['lastname']              = $approver_info['last_name'];
        $replacement_array['first_name']            = $approver_info['first_name'];
        $replacement_array['last_name']             = $approver_info['last_name'];
        $replacement_array['document_title']        = $document_info['document_title'];
        $replacement_array['user_type']             = $document_info['user_type'];
        $replacement_array['note']                  = $approvers_flow_info["assigner_note"];
        $replacement_array['baseurl']               = base_url();
        $replacement_array['accept_link']           = $approval_public_link_accept;
        $replacement_array['reject_link']           = $approval_public_link_reject;
        $replacement_array['view_link']             = $approval_public_link_view;
        //
        // Send email notification to approver with a private link
        log_and_send_templated_email(HR_DOCUMENTS_APPROVAL_FLOW, $approver_info['email'], $replacement_array, $hf, 1);
    }


    function saveFederalFillable($post)
    {
        //
        $applicant_sid = $post['user_sid'];
        //
        $resp = [
            'Status' => false,
            'Message' => ''
        ];
        //
        if ($post["perform_action"] == "sign_w4_document") {
            if (empty($post["w4_first_name"]) || empty($post["w4_middle_name"])) {
                //
                $message = "";
                //
                if (empty($post["w4_first_name"]) && empty($post["w4_middle_name"])) {
                    $message = "Please provide First and Middle name.";
                } else if (empty($post["w4_first_name"])) {
                    $message = "Please provide First name.";
                } else if (empty($post["w4_middle_name"])) {
                    $message = "Please provide Middle name.";
                }
                //
                $resp['Message'] = $message;
                return $resp;
            } else {
                //
                $signature_timestamp        = date('Y-m-d H:i:s');
                $signature_date             = $post['signature_date'];
                $first_date_of_employment   = $post['first_date_of_employment'];
                //
                if (!empty($signature_date)) {
                    $signature_timestamp = DateTime::createFromFormat('m-d-Y', $signature_date)->format('Y-m-d');
                }
                //
                if (!empty($first_date_of_employment)) {
                    $first_date_of_employment = DateTime::createFromFormat('m-d-Y', $first_date_of_employment)->format('Y-m-d');
                }
                //
                $data_to_update = array();
                $data_to_update['first_name'] = $post['w4_first_name'];
                $data_to_update['middle_name'] = $post['w4_middle_name'];
                $data_to_update['last_name'] = $post['w4_last_name'];
                $data_to_update['ss_number'] = $post['ss_number'];
                $data_to_update['home_address'] = $post['home_address'];
                $data_to_update['city'] = $post['city'];
                $data_to_update['state'] = $post['state'];
                $data_to_update['zip'] = $post['zip'];
                $data_to_update['marriage_status'] = $post['marriage_status'];
                $data_to_update['signature_timestamp'] = $signature_timestamp;
                $data_to_update['signature_email_address'] = $post['email_address'];
                $data_to_update['signature_bas64_image'] = $post['signature_bas64_image'];
                $data_to_update['init_signature_bas64_image'] = $post['init_signature_bas64_image'];
                $data_to_update['ip_address'] = $post['signature_ip_address'];
                $data_to_update['user_agent'] = $post['signature_user_agent'];
                $data_to_update['emp_name'] = $post['emp_name'];
                $data_to_update['emp_address'] = $post['emp_address'];
                $data_to_update['first_date_of_employment'] = $first_date_of_employment;
                $data_to_update['emp_identification_number'] = $post['emp_identification_number'];
                $data_to_update['user_consent'] = $post['user_consent'];
                $data_to_update['mjsw_status'] = $post['mjsw_status'];
                $data_to_update['dependents_children'] = $post['dependents_children'];
                $data_to_update['other_dependents'] = $post['other_dependents'];
                $data_to_update['claim_total_amount'] = $post['claim_total_amount'];
                $data_to_update['other_income'] = $post['other_income'];
                $data_to_update['other_deductions'] = $post['other_deductions'];
                $data_to_update['additional_tax'] = $post['additional_tax'];
                $data_to_update['mjw_two_jobs'] = $post['mjw_two_jobs'];
                $data_to_update['mjw_three_jobs_a'] = $post['mjw_three_jobs_a'];
                $data_to_update['mjw_three_jobs_b'] = $post['mjw_three_jobs_b'];
                $data_to_update['mjw_three_jobs_c'] = $post['mjw_three_jobs_c'];
                $data_to_update['mjw_pp_py'] = $post['mjw_pp_py'];
                $data_to_update['mjw_divide'] = $post['mjw_divide'];
                $data_to_update['dw_input_1'] = $post['dw_input_1'];
                $data_to_update['dw_input_2'] = $post['dw_input_2'];
                $data_to_update['dw_input_3'] = $post['dw_input_3'];
                $data_to_update['dw_input_4'] = $post['dw_input_4'];
                $data_to_update['dw_input_5'] = $post['dw_input_5'];

                //  
                $w4_sid = getVerificationDocumentSid($applicant_sid, 'applicant', 'w4');
                keepTrackVerificationDocument($applicant_sid, 'applicant', 'completed', $w4_sid, 'w4', 'Public Link');
                //
                $this->form_wi9_model->update_form('w4', 'applicant', $applicant_sid, $data_to_update);
                //
                $resp['Message'] = "W4 save successfully";
                $resp['Status'] = true;
                //
                return $resp;
            }
        } else {
            if (empty($post["section1_last_name"]) || empty($post["section1_first_name"])) {
                $message = "";
                //
                if (empty($post["section1_last_name"]) && empty($post["section1_first_name"])) {
                    $message = "Please provide First and Last name.";
                } else if (empty($post["section1_last_name"])) {
                    $message = "Please provide Last name.";
                } else if (empty($post["section1_first_name"])) {
                    $message = "Please provide First name.";
                }
                //
                $resp['Message'] = $message;
                //
                return $resp;
            } else {
                //
                $signature = get_e_signature($company_info['sid'], $applicant_sid, 'applicant');
                $applicant_e_signature = $signature['signature_bas64_image'];
                $applicant_e_signature_init = $signature['init_signature_bas64_image'];
                //
                $options_1 = array();
                //
                if ($post['section1_penalty_of_perjury'] == 'permanent-resident') {
                    $options_1['section1_alien_registration_number_one'] = $post['section1_alien_registration_number_one'];
                    $options_1['section1_alien_registration_number_two'] = $post['section1_alien_registration_number_two'];
                } else if ($post['section1_penalty_of_perjury'] == 'alien-work') {
                    $options_1['section1_alien_registration_number_one'] = $post['section1_alien_registration_number_one'];
                    $options_1['section1_alien_registration_number_two'] = $post['section1_alien_registration_number_two'];
                    $options_1['alien_authorized_expiration_date'] = empty($post['alien_authorized_expiration_date']) || $post['alien_authorized_expiration_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $post['alien_authorized_expiration_date'])->format('Y-m-d H:i:s');
                    $options_1['form_admission_number'] = $post['form_admission_number'];
                    $options_1['foreign_passport_number'] = $post['foreign_passport_number'];
                    $options_1['country_of_issuance'] = $post['country_of_issuance'];
                }
                //
                $options_2 = array();
                $options_2['section1_preparer_or_translator'] = $post['section1_preparer_or_translator'];
                $options_2['number-of-preparer'] = $post['number-of-preparer'];
                //
                $section1_today_date = empty($post['section1_today_date']) || $post['section1_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $post['section1_today_date'])->format('Y-m-d H:i:s');
                //
                $section1_date_of_birth = empty($post['section1_date_of_birth']) || $post['section1_date_of_birth'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $post['section1_date_of_birth'])->format('Y-m-d H:i:s');
                //
                $section1_preparer_today_date = empty($post['section1_preparer_today_date']) || $post['section1_preparer_today_date'] == 'N/A' ? null : DateTime::createFromFormat('m-d-Y', $post['section1_preparer_today_date'])->format('Y-m-d H:i:s');
                //
                $data_to_update = array(); //Section 1 Data Array Starts
                $data_to_update['section1_last_name'] = $post['section1_last_name'];
                $data_to_update['section1_first_name'] = $post['section1_first_name'];
                $data_to_update['section1_middle_initial'] = $post['section1_middle_initial'];
                $data_to_update['section1_other_last_names'] = $post['section1_other_last_names'];
                $data_to_update['section1_address'] = $post['section1_address'];
                $data_to_update['section1_apt_number'] = $post['section1_apt_number'];
                $data_to_update['section1_city_town'] = $post['section1_city_town'];
                $data_to_update['section1_state'] = $post['section1_state'];
                $data_to_update['section1_zip_code'] = $post['section1_zip_code'];
                $data_to_update['section1_date_of_birth'] = $section1_date_of_birth;
                $data_to_update['section1_social_security_number'] = $post['section1_social_security_number'];
                $data_to_update['section1_emp_email_address'] = $post['section1_emp_email_address'];
                $data_to_update['section1_emp_telephone_number'] = $post['section1_emp_telephone_number'];
                $data_to_update['section1_penalty_of_perjury'] = $post['section1_penalty_of_perjury'];
                $data_to_update['section1_alien_registration_number'] = serialize($options_1);
                $data_to_update['section1_emp_signature'] = $applicant_e_signature;
                $data_to_update['section1_emp_signature_init'] = $applicant_e_signature_init;
                $data_to_update['section1_emp_signature_ip_address'] = getUserIP();
                $data_to_update['section1_emp_signature_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $data_to_update['section1_today_date'] = $section1_today_date;
                $data_to_update['section1_preparer_or_translator'] = serialize($options_2);
                $data_to_update['section1_preparer_today_date'] = $section1_preparer_today_date;
                $data_to_update['section1_preparer_last_name'] = $post['section1_preparer_last_name'];
                $data_to_update['section1_preparer_first_name'] = $post['section1_preparer_first_name'];
                $data_to_update['section1_preparer_city_town'] = $post['section1_preparer_city_town'];
                $data_to_update['section1_preparer_address'] = $post['section1_preparer_address'];
                $data_to_update['section1_preparer_state'] = $post['section1_preparer_state'];
                $data_to_update['section1_preparer_zip_code'] = $post['section1_preparer_zip_code'];
                $data_to_update['user_consent'] = 1;
                $data_to_update['emp_app_sid'] = $applicant_sid;
                $data_to_update['applicant_flag'] = 1;
                $data_to_update['applicant_filled_date'] = date('Y-m-d H:i:s');
                //
                $this->form_wi9_model->update_form('i9', 'applicant', $applicant_sid, $data_to_update);
                //
                $i9_sid = getVerificationDocumentSid($applicant_sid, 'applicant', 'i9');
                keepTrackVerificationDocument($applicant_sid, 'applicant', 'completed', $i9_sid, 'i9', 'Public Link');
                //
                //
                $resp['Message'] = "I9 save successfully";
                $resp['Status'] = true;
                //
                return $resp;
            }
        }
    }


    public function officeLocation()
    {
        //
        $post = $this->input->post(null, true);
        //
        $this->db
            ->where('company_sid', $post['companyId'])
            ->update(
                'onboarding_office_locations',
                [
                    'is_primary' => 0
                ]
            );
        //
        $this->db
            ->where('company_sid', $post['companyId'])
            ->where('sid', $post['rowId'])
            ->update(
                'onboarding_office_locations',
                [
                    'is_primary' => 1
                ]
            );
    }

    //
    function dependent_add_blanck()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        haveDependensDelete($post['companySid'], $post['userSid'], $post['userType']);

        if (isDontHaveDependens($post['companySid'], $post['userSid'], $post['userType']) <= 0) {
            $this->db->insert(
                'dependant_information',
                [
                    'users_sid' => $post['userSid'],
                    'users_type' => $post['userType'],
                    'company_sid' => $post['companySid'],
                    'have_dependents' => '0',
                    'dependant_details' => serialize([])
                ]
            );
            //
            $this->db
                ->where('user_sid', $post['userSid'])
                ->where('user_type', $post['userType'])
                ->where('document_type', 'dependents')
                ->where('company_sid', $post['companySid'])
                ->update(
                    'documents_assigned_general',
                    [
                        'is_completed' => 1
                    ]
                );
        }
    }
}
