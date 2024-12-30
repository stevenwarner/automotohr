<?php defined('BASEPATH') or exit('No direct script access allowed');

class Manage_ems extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('onboarding_model');
        $this->form_validation->set_error_delimiters('<p class="error"><i class="fa fa-exclamation-circle"></i> ', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library("pagination");
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('ems')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
            $employer_detail = $data['session']['employer_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'Employee Management System';
            check_access_permissions($security_details, 'dashboard', 'ems_portal'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];

            $loggedin_access_level = $employer_detail['access_level'];
            $data['access_level'] = $loggedin_access_level;
            $this->load->view('main/header', $data);
            $this->load->view('manage_ems/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ems_notification()
    {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('employeeemsnotification')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];

            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'employee_ems_notification'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];

            $loggedin_access_level = $employer_detail['access_level'];
            $data['access_level'] = $loggedin_access_level;
            $data['company_sid'] = $company_id;
            $data['title'] = 'Management EMS Notification';
            $useful_links                                                   = $this->onboarding_model->get_all_links($company_id); //Useful Links
            $data['useful_links']                                           = $useful_links;
            $ems_notification                                               = $this->onboarding_model->get_all_ems_notification($company_id); //Useful Links
            $data['ems_notification']                                       = $ems_notification;
            $employees                                                      = $this->onboarding_model->get_all_employees($company_id); //Employees
            $employees_for_select                                           = array();

            foreach ($employees as $employee) {
                $employees_for_select[$employee['sid']]                     = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . '] [' . $employee['email'] . ']';
            }
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
            $data['employees']                                              = $employees_for_select;
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_ems/ems_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_ems_notification':
                        $company_sid = $this->input->post('company_sid');
                        $notification_sid = $this->input->post('notification_sid');
                        $this->onboarding_model->delete_ems_notification($company_sid, $notification_sid);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Notification Successfully Deleted!');
                        redirect('add_ems_notification', 'refresh');
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
                        $insert_data['sort_order'] = $sort_order == '' ? 0 : $sort_order;
                        $insert_data['image_code'] = '';
                        $insert_data['image_status'] = $image_status;
                        $insert_data['video_status'] = $video_status;
                        $insert_data['status'] = 1;
                        $insert_data['created_date'] = date('Y-m-d H:i:s');
                        $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
                        //                        $pictures = 'Docs.ac';
                        if (!empty($pictures) && $pictures != 'error') {
                            $insert_data['image_code'] = $pictures;
                        }

                        if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                            $random = generateRandomString(5);
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
                                redirect('add_ems_notification', 'refresh');
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
                        redirect('add_ems_notification', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_ems_notification($sid)
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_ems', 'add_edit_employee_ems_notification'); // no need to check in this Module as Dashboard will be available to all
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
                // $employees_for_select[$employee['sid']] = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                $employees_for_select[$employee['sid']]                     = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . '] [' . $employee['email'] . ']';
            }

            $data['employees'] = $employees_for_select;
            $data['ems_notification'] = $ems_notification;
            $this->load->view('main/header', $data);
            $this->load->view('manage_ems/ems_notification_edit');
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
            //            $pictures = 'Docs.ac';
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
                    redirect('add_ems_notification', 'refresh');
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
            redirect('add_ems_notification', 'refresh');
        }
    }

    public function enable_disable_notification($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->onboarding_model->update_ems_notification($id, $data);
        print_r(json_encode(array('message' => 'updated')));
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
}
