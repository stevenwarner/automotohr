<?php defined('BASEPATH') OR exit('No direct script access allowed');

class reference_checks_public extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('reference_checks_model');
    }

    public function index($verificationKey = NULL) { 
        if($verificationKey != NULL){ //Handle Post
            $dataToSave                                                         = array();
            if(isset($_POST['perform_action'])){
                switch($_POST['perform_action']){
                    case 'save_work_reference':
                            $reference_type                                     = $_POST['reference_type'];
                            $reference_title                                    = $_POST['reference_title'];
                            $reference_name                                     = $_POST['reference_name'];
                            $organization_name                                  = $_POST['organization_name'];
                            $department_name                                    = $_POST['department_name'];
                            $branch_name                                        = $_POST['branch_name'];
                            $reference_relation                                 = $_POST['reference_relation'];
                            $work_period_start                                  = $_POST['work_period_start'];
                            $work_period_end                                    = $_POST['work_period_end'];
                            $reference_email                                    = $_POST['reference_email'];
                            $reference_phone                                    = $_POST['reference_phone'];
                            $work_other_information                             = $_POST['work_other_information'];
                            $best_time_to_call                                  = $_POST['best_time_to_call'];

                        if ($work_period_start == '') {
                                $work_period_start                              = null;
                        } else {
                                $work_period_start                              = date('Y-m-d H:i:s', formatDateForDb($work_period_start));
                        }

                        if ($work_period_end == '') {
                                $work_period_end                                = null;
                        } else {
                                $work_period_end                                = date('Y-m-d H:i:s', formatDateForDb($work_period_end));
                        }

                        $dataToSave = array(    'reference_type'                => $reference_type,
                                                'reference_title'               => $reference_title,
                                                'reference_name'                => $reference_name,
                                                'organization_name'             => $organization_name,
                                                'department_name'               => $department_name,
                                                'branch_name'                   => $branch_name,
                                                'program_name'                  => '',
                                                'reference_relation'            => $reference_relation,
                                                'period_start'                  =>  $work_period_start,
                                                'period_end'                    => $work_period_end,
                                                'reference_email'               => $reference_email,
                                                'reference_phone'               => $reference_phone,
                                                'other_information'             => $work_other_information,
                                                'best_time_to_call'             => $best_time_to_call
                                            );

                        break;
                        
                    case 'save_personal_reference':
                            $reference_type                                     = $_POST['reference_type'];
                            $reference_title                                    = $_POST['reference_title'];
                            $reference_name                                     = $_POST['reference_name'];
                            $reference_relation                                 = $_POST['reference_relation'];
                            $period                                             = $_POST['relationship_period'];
                            $reference_email                                    = $_POST['reference_email'];
                            $reference_phone                                    = $_POST['reference_phone'];
                            $work_other_information                             = $_POST['work_other_information'];
                            $best_time_to_call                                  = $_POST['best_time_to_call'];
                            //$organization_name                                = $_POST['organization_name'];
                            //$department_name                                  = $_POST['department_name'];
                            //$branch_name                                      = $_POST['branch_name'];
                            //$program_name                                     = $_POST['program_name'];
                            //$work_period_start                                = $_POST['personal_period_start'];
                            //$work_period_end                                  = $_POST['personal_period_end'];

                        $dataToSave = array(    'reference_type'                => $reference_type,
                                                'reference_title'               => $reference_title,
                                                'reference_name'                => $reference_name,
                                                'organization_name'             => '',
                                                'department_name'               => '',
                                                'branch_name'                   => '',
                                                'program_name'                  => '',
                                                'reference_relation'            => $reference_relation,
                                                'period_start'                  => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                                'period_end'                    => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                                'period'                        => $period,
                                                'reference_email'               => $reference_email,
                                                'reference_phone'               => $reference_phone,
                                                'other_information'             => $work_other_information,
                                                'best_time_to_call'             => $best_time_to_call
                                            );
                        break;
                }
            }


            $data                                                               = array();
            $data['page_title']                                                 = 'Add Reference Checks';
            //Check User Type
            $userTypeCode                                                       = strtoupper(substr($verificationKey, 0, 3));

            switch($userTypeCode){
                case 'APP':
                    $applicant_info                                             = $this->reference_checks_model->GetApplicantDetailsWithVerificationKey($verificationKey);
                    if(!empty($applicant_info)){
                        $company_sid                                            = $applicant_info['employer_sid'];
                        $user_sid                                               = $applicant_info['sid'];
                        $users_type                                             = 'applicant';
                        $job_id                                                 = '';

                        //Save Data
                        if(isset($_POST['perform_action'])){
                            if(!empty($dataToSave)) {
                                $this->reference_checks_model->Save(null, $user_sid, $company_sid, $users_type, $dataToSave);
                            }
                        }

                        $current_references                                     = $this->reference_checks_model->GetAllReferences($user_sid, $company_sid, $users_type);
                        //Send Information to View
                        $data['applicant_info']                                 = $applicant_info;
                        $data['current_references']                             = $current_references;
                    } else {
                        redirect('reference_checks_public/error', 'refresh');
                    }
                    break;
                case 'EMP':
                    //todo
                    break;
            }

            $this->load->view('reference_checks/reference_checks_add_public', $data);
        } else {
            redirect('reference_checks_public_error_message', 'reload');
        }
    }

    public function error(){
        $data['title']                                                          = 'Add References - Error';
        $this->load->view('reference_checks/reference_checks_public_error_message', $data);
    }
}
