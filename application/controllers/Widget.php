<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Widget extends Public_Controller
{
    private $security_details;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('widget_model');
        $session_details = $this->session->userdata('logged_in');
        $sid = $session_details['employer_detail']['sid'];
        $this->security_details = db_get_access_level_details($sid);
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $security_details = $this->security_details;
            $data['security_details'] = $security_details;
            $data['session'] = $this->session->userdata('logged_in');
            $data['title'] = "Widget";
            $employer_id = $data["session"]["company_detail"]["sid"];

            $company = $this->widget_model->get_company_detail($employer_id);
            $api_key = $company["api_key"];

            $data['script_tag'] = '<script id="autom' . $employer_id . '" class="automotosocial_webwidget" type="text/javascript" src="' . STORE_FULL_URL_SSL . 'job_widget/jobs.js"></script><div id="automoto-widget-container"></div>';
            $data['api_link'] = base_url() . "widget/xml_api?api_key=" . $api_key . "&attributes=sid,user_sid,active,views,activation_date,Title,JobType,JobCategory,Location_Country,Location_State,Location_ZipCode,JobDescription,JobRequirements,SalaryType,Location_City,Salary,YouTube_Video";

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/widget');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function xml_api()
    {
        if (isset($_GET['api_key']) && $_GET['api_key'] != "") {
            $api_key = $_GET['api_key'];
            $result = $this->widget_model->get_company_id_by_api_key($api_key);
            if ($result->num_rows() > 0) {
                $data = $result->result_array();
                $user_id = $data[0]["sid"];
                if (isset($_GET['attributes']) && $_GET['attributes'] != "") {
                    $attributes = $_GET['attributes'];
                    $result = $this->widget_model->get_jobs_by_company_id($attributes, $user_id);
                    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                    $root_element = "jobs"; //fruits
                    $xml .= "<$root_element total='" . $result->num_rows() . "'>";

                    if ($result->num_rows() > 0) {
                        foreach ($result->result_array() as $result_array) {
                            $xml .= "<job>";
                            //loop through each key,value pair in row
                            foreach ($result_array as $key => $value) {
                                //$key holds the table column name
                                $xml .= "<$key>";
                                //embed the SQL data in a CDATA element to avoid XML entity issues
                                $xml .= "$value";
                                //and close the element
                                $xml .= "</$key>";
                            }
                            $xml .= "</job>";
                        }
                        //close the root element
                        $xml .= "</$root_element>";

                        //send the xml header to the browser
                        header("Content-Type:text/xml");
                        header("Content-Disposition: attachment; filename=Portal_jobs.xml");
                        //output the XML data
                        echo $xml;
                    } else {
                        echo "No listing(s) found against this Employer";
                    }
                } else {
                    echo "No Attribute(s) selected";
                }
            } else {
                echo "Invalid API key";
            }
        } else {
            echo "API key missing";
        }
    }
}
