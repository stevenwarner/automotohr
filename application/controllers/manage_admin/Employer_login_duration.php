<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employer_login_duration extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/employer_login_duration_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $redirect_url = 'manage_admin';
        $function_name = 'employer_login_duration';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $companies = $this->employer_login_duration_model->get_all_companies();
        $this->data['companies'] = $companies;
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'generate_csv_file':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|xss_clean|trim');
                $this->form_validation->set_rules('employer_sid', 'employer_sid', 'required|xss_clean|trim');
                break;
        }


        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/employer_login_duration/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'generate_csv_file':

                    $activities = $this->getEmployeeActivityLog(FALSE);
                    //
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    // $output = fopen('test.csv', 'w');
                    $output = fopen('php://output', 'w');
                    fputcsv($output, array('Hours'));
                    fputcsv($output, array('','00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'));

                    for ($dCount = 1; $dCount <= date('d'); $dCount++) {
                        $dCount = $dCount <= 9 ? '0'.$dCount : $dCount; 
                        $day = array();

                        if($dCount == 01) $day[] = 'Days';
                        else $day[] = '';
                        //
                        $day[] = $dCount;
                        //
                        for ($hCount = 0; $hCount < 24; $hCount++) {
                            $hCount = $hCount <= 9 ? '0'.$hCount : $hCount;
                            $day[] = '{'.( $dCount.$hCount ).'}';
                        }
                        //
                        if(!isset($activities[$dCount])) {
                            $day = explode(',', preg_replace('/{(.*?)}/', '' , implode(',', $day)));
                        } else {
                            //
                            $tmp = implode(',', $day);
                            //
                            foreach ($activities[$dCount]['hours'] as $k0 => $v0) {
                                $tmp = preg_replace('/{('.($dCount.$v0).')}/', 'A' , $tmp);
                            }
                            $day = explode(',', preg_replace('/{(.*?)}/', '', $tmp));
                        }
                        fputcsv($output, $day);
                    }

                    fclose($output);
                    exit(0);

                    // _e($day, true, true);

                    // _e($activities, true, true);
                    // $employer_sid = $this->input->post('employer_sid');
                    // $daily_activities = array();

                    // for ($dCount = 1; $dCount <= date('d'); $dCount++) {
                    //     for ($hCount = 0; $hCount < 24; $hCount++) {
                    //         $hour_activity = $this->employer_login_duration_model->get_activity_log($employer_sid, date('Y'), date('m'), $dCount, $hCount);
                            
                    //         if (!empty($hour_activity)) {
                    //             $hour_activity = $hour_activity[0];
                    //         } else {
                    //             $hour_activity = array();
                    //         }

                    //         $daily_activities[$dCount][] = $hour_activity;
                    //     }
                    // }

                    // header('Content-Type: text/csv; charset=utf-8');
                    // header('Content-Disposition: attachment; filename=data.csv');
                    // $output = fopen('php://output', 'w');
                    // fputcsv($output, array('Hours', '', 'Days'));
                    // $header_days = array('', '');

                    // for ($headHCount = 0; $headHCount < 24; $headHCount++) {
                    //     $header_days[] = str_pad($headHCount, 2, '0', STR_PAD_LEFT);
                    // }

                    // fputcsv($output, $header_days);

                    // foreach ($daily_activities as $key => $daily_activity) {
                    //     $row_data = array('', $key);

                    //     foreach ($daily_activity as $key => $hourly_activity) {
                    //         if (!empty($hourly_activity)) {
                    //             $row_data[] = 'A';
                    //         } else {
                    //             $row_data[] = '';
                    //         }
                    //     }

                    //     fputcsv($output, $row_data);
                    // }

                    // fclose($output);
                    // exit;
                    break;
            }
        }

    }

    public function ajax_responder() {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_companies'); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_company_users':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                break;
            case 'get_login_duration_log':
                $this->form_validation->set_rules('employer_sid', 'employer_sid', 'required|trim|xss_clean');
                break;
            default:
                //do nothing
                break;
        }

        $this->data['employers'] = array();

        if ($this->form_validation->run() == false) {
            
        } else {
            $perform_action = $this->input->post('perform_action');
            
            switch ($perform_action) {
                case 'get_company_users':
                    $company_sid = $this->input->post('company_sid');
                    $employers = $this->employer_login_duration_model->get_all_employers($company_sid);

                    if (!empty($employers)) {
                        echo '<option value="">Please Select</option>' . PHP_EOL;
                        foreach ($employers as $employer) {
                            echo '<option value="' . $employer['sid'] . '">' . ucwords($employer['first_name'] . ' ' . $employer['last_name']) . '</option>' . PHP_EOL;
                        }
                    } else {
                        echo '<option value="">Please Select Company</option>' . PHP_EOL;
                    }

                    break;
                case 'get_login_duration_log':
                    $this->getEmployeeActivityLog();

                    // $daily_activities = array();

                    // for ($dCount = 1; $dCount <= date('d'); $dCount++) {
                    //     for ($hCount = 0; $hCount < 24; $hCount++) {
                    //         $hour_activity = $this->employer_login_duration_model->get_activity_log($employer_sid, date('Y'), date('m'), $dCount, $hCount);
                            
                    //         if (!empty($hour_activity)) {
                    //             $hour_activity = $hour_activity[0];
                    //         } else {
                    //             $hour_activity = array();
                    //         }

                    //         $daily_activities[$dCount][] = $hour_activity;
                    //     }
                    // }

//                     echo '<table class="table table-bordered table-hover table-striped">';
//                     echo '<thead>';
//                     echo '<th colspan="2" rowspan="2"></th>';
//                     echo '<th colspan="24" class="text-center text-success">Hours</th>';
//                     echo '<tr>';
// //                    for ($headHCount = 0; $headHCount < 24; $headHCount++) {
//                     echo '<th class="text-center">00</th><th class="text-center">01</th><th class="text-center">02</th><th class="text-center">03</th><th class="text-center">04</th><th class="text-center">05</th>';
//                     echo '<th class="text-center">06</th><th class="text-center">07</th><th class="text-center">08</th><th class="text-center">09</th><th class="text-center">10</th><th class="text-center">11</th>';
//                     echo '<th class="text-center">12</th><th class="text-center">13</th><th class="text-center">14</th><th class="text-center">15</th><th class="text-center">16</th><th class="text-center">17</th>';
//                     echo '<th class="text-center">18</th><th class="text-center">19</th><th class="text-center">20</th><th class="text-center">21</th><th class="text-center">22</th><th class="text-center">23</th>';
// //                    }
//                     echo '</tr>';
//                     echo '</thead>';
//                     echo '<tbody>';
//                     echo '<th rowspan="' . (intval(date('t')) + 1) . '" class="text-center" style="vertical-align:middle;"><span class="duration-days-strip text-success">Days</span></th>';
//                     foreach ($daily_activities as $key => $daily_activity) {
//                         echo '<tr>';
//                         echo '<th class="text-center">' . str_pad($key, 2, 0, STR_PAD_LEFT) . '</th>';
//                         foreach ($daily_activity as $hourly_activity) {
//                             if (!empty($hourly_activity)) {
//                                 echo '<td class="text-center">' . '<div class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Active">A</div>' . '</td>';
//                             } else {
//                                 echo '<td class="text-center"></td>';
//                             }
//                         }
//                         echo '</tr>';
//                     }
//                     echo '</tbody>';
//                     echo '</table>';

                    break;
                default:
                    //do nothing
                    break;
            }
        }
    }


    /**
     * Get employee activities
     * Created on: 15-08-2019
     * 
     * @param returnJSON  Bool Optional
     * Default 'TRUE'
     * 
     * @accepts POST
     * 'employee_sid'
     * 
     * @return JSON
     */
    private function getEmployeeActivityLog($returnJSON = TRUE){
        $employerId = $this->input->post('employer_sid', true);
        //
        $activities = $this->employer_login_duration_model->getEmployeeActivityLog(
            // 58, 
            $employerId, 
            date("Y-m-01"),
            date("Y-m-d")
        );
        // Set header
        header('Content-Type: application/json');
        //
        if(!$activities){
            if(!$returnJSON) return false;
            echo json_encode(array('Status' => FALSE, 'Response' => 'No record found'));
            exit(0);
        }
        // Multi dimensional to sngl array
        $activities = array_column($activities, 'action_timestamp');
        // Define default array
        $na = array();
        // Loop through
        foreach ($activities as $k0 => $v0) {
            $tmp = explode('-', $v0);
            $na[$tmp[0]]['day'] = $tmp[0];
            $na[$tmp[0]]['hours'][] = $tmp[1];
        }
        // Reset indexes
        // Replace activity array
        $activities = ($na);
        // $activities = array_values($na);
        if(!$returnJSON) return $activities;

        echo json_encode(array('Status' => TRUE, 'Response' => 'Procced...', 'Data' => $activities));
        exit(0);
    }

}
