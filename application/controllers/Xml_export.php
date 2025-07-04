<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class XML_export extends Public_Controller {
    private $security_details;
    public function __construct() {
        parent::__construct();
// Your own constructor code
        $this->load->model('xml_export_model');
//        $session_details = $this->session->userdata('logged_in');
//        $sid = $session_details['employer_detail']['sid'];
//        $this->security_details = db_get_access_level_details($sid);
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {

            $session_details = $this->session->userdata('logged_in');


            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'xml_export');

            $data['title'] = "XML Export";
            $employer_id = $data["session"]["company_detail"]["sid"];
            $user_sid = $data["session"]["employer_detail"]["sid"];

            $this->xml_export_model->add_company_feed($employer_id, $user_sid);
            $data['xml_feed_url'] = $this->xml_export_model->get_company_feed_url($employer_id);

            $result = $this->xml_export_model->get_jobs_by_company_id($employer_id);
            $data['count'] = $result->num_rows();
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/xml_export');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function export_all_jobs() {
        if ($this->session->userdata('logged_in')) {
            $security_details = $this->security_details;
            $data['security_details'] = $security_details;

//            if (in_array('full_access', $security_details) || in_array('xml_export', $security_details)){
//                // It has access to the controller
//            } else {
//                $this->session->set_flashdata('message', SECURITY_PERMISSIONS_ERROR);
//                redirect("my_settings", "location");
//            }

            $session_details = $this->session->userdata('logged_in');
            $sid = $session_details['employer_detail']['sid'];
            $this->security_details = db_get_access_level_details($sid);

            $data['session'] = $this->session->userdata('logged_in');
            $user_sid = $data["session"]["company_detail"]["sid"];
            //page title
            $data['title'] = "XML Export";

            if ($_POST['start_date'] != '' && $_POST['end_date'] != '') {
                $start_date = $_POST['start_date'];
                $s_date = explode('-', $start_date);
                $start_date = $s_date[2] . '-' . $s_date[0] . '-' . $s_date[1] . ' 00:00:00';

                $end_date = $_POST['end_date'];
                $e_date = explode('-', $end_date);
                $end_date = $e_date[2] . '-' . $e_date[0] . '-' . $e_date[1] . ' 00:00:00';

                if (isset($_POST['ej_filter'])) {
                    $attributes = join(',', $_POST['ej_filter']);
                    $sql = "SELECT $attributes  FROM `portal_job_listings` where user_sid = $user_sid and activation_date between '" . $start_date . "' and '" . $end_date . "'";
                } else {
                    $sql = "SELECT `sid`, `user_sid`, `active`, `views`, `activation_date`, `Title`, `JobType`, `JobCategory`, `Location_Country`, `Location_State`, `Location_ZipCode`, `JobDescription`, `JobRequirements`, `SalaryType`, `Location_City`, `Salary`, `YouTube_Video` FROM `portal_job_listings` where user_sid = $user_sid and activation_date between '" . $start_date . "' and '" . $end_date . "'";
                }
            } else {
                $attributes = join(',', $_POST['ej_filter']);
                $sql = "SELECT $attributes  FROM `portal_job_listings` where user_sid = $user_sid and active = 1";
            }

            $result = $this->xml_export_model->run_query($sql);

            if ($result->num_rows() > 0 && $_POST['type'] == 'xml') {

                $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                $root_element = "jobs"; //fruits
                $xml .= "<$root_element total='" . $result->num_rows() . "'>";
                foreach ($result->result_array() as $result_array) {
                    $xml .= "<job>";

                    //loop through each key,value pair in row
                    foreach ($result_array as $key => $value) {
                        //$key holds the table column name
                        $xml .= "<$key>";

                        //embed the SQL data in a CDATA element to avoid XML entity issues
                        if($key == 'JobDescription' || $key == 'JobRequirements' || $key == 'Title'){
                            $value2 = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$value));
                            $value2 = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$value2));
                            $value2 = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$value2);

                            $xml .= "$value2";
                        } else {
                            $xml .= "$value";
                        }

                        //and close the element
                        $xml .= "</$key>";
                    }

                    $xml.="</job>";
                }
                //close the root element
                $xml .= "</$root_element>";

                //send the xml header to the browser
                header("Content-Type:text/xml");
                header("Content-Disposition: attachment; filename=Portal_jobs.xml");
                //output the XML data
                echo $xml;
                exit;
            } else if ($result->num_rows() > 0  && $_POST['type'] == 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');

                // create a file pointer connected to the output stream
                $output = fopen('php://output', 'w');

                // output the column headings
                fputcsv($output, $_POST['ej_filter']);
                foreach($result->result_array() as $row )
                    fputcsv($output, $row);
            }
//            } else {
//                $_SESSION['add_job_success'] = "success";
//                $this->session->set_flashdata('message', '<b>Error:</b> No job posted between these Dates!');
//                redirect("xml_export", "location");
//            }

        } else {
            redirect(base_url('login'), "refresh");
        }//else end for session check fail
    }

    public function xml_jobs_feed($security_key){
        $company_sid = $this->xml_export_model->check_company_feed($security_key);

        if($company_sid > 0){
            $filters = array();
            $filters[] = 'sid';
            $filters[] = 'user_sid';
            $filters[] = 'active';
            $filters[] = 'views';
            $filters[] = 'activation_date';
            $filters[] = 'Title';
            $filters[] = 'JobType';
            $filters[] = 'JobCategory';
            $filters[] = 'Location_Country';
            $filters[] = 'Location_State';
            $filters[] = 'Location_ZipCode';
            $filters[] = 'JobDescription';
            $filters[] = 'JobRequirements';
            $filters[] = 'SalaryType';
            $filters[] = 'Location_City';
            $filters[] = 'Salary';
            $filters[] = 'YouTube_Video';

            $attributes = join(',', $filters);
            $sql = "SELECT $attributes  FROM `portal_job_listings` where user_sid = $company_sid and active = 1";
            $result = $this->xml_export_model->run_query($sql);

            if ($result->num_rows() > 0) {
                $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                $root_element = "jobs";
                $xml .= "<$root_element total='" . $result->num_rows() . "'>";
                foreach ($result->result_array() as $result_array) {
                    $xml .= "<job>";

                    foreach ($result_array as $key => $value) {
                        $xml .= "<$key>";
                        if($key == 'JobDescription' || $key == 'JobRequirements' || $key == 'Title'){
                            $value2 = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$value));
                            $value2 = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$value2));
                            $value2 = preg_replace("|&([^;]+?)[\s<&]|","&amp;$1 ",$value2);

                            $xml .= "$value2";
                        } else {
                            $xml .= "$value";
                        }
                        $xml .= "</$key>";
                    }
                    $xml.="</job>";
                }
                $xml .= "</$root_element>";
                header("Content-Type:text/xml");
                echo $xml;
                exit;
            } else {
                echo 'No data found.';
                exit;
            }

        } else {
            echo 'Invalid URL';
            exit;
        }
    }
}
