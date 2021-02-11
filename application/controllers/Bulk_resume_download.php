<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '-1');

class Bulk_resume_download extends Public_Controller {

    private $limit = 100;
    private $list_size = 5;
    private $multiple_zip_files = false;
    private $bucket_name = AWS_S3_BUCKET_NAME;

    public function __construct() {
        parent::__construct();
        $this->load->model('bulk_resume_model');
    }

    function index($zip_archive = null) {
        
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'bulk_resume'); // Param2: Redirect URL, Param3: Function Name
        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Download Applicant Resumes';
        $this->form_validation->set_rules('perform_action', 'perform_action', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $jobs = $this->bulk_resume_model->get_all_jobs($company_sid);
           
            $data['jobs'] = $jobs;
            
            $data['months'] = $this->get_months();
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/bulk_resumes_downloads_view');
            $this->load->view('main/footer');
        } 
    }

    /**
     * fetch applicants from 
     * AWS
     *  
     * @return JSON  
     */
    function fetch_applicants(){
        
        //
        if (!$this->session->userdata('logged_in')) { echo json_encode(array('Status' => FALSE, 'Response' => 'Invalid request.')); exit(0); }
        //
        if(
            !$this->input->post('page') ||
            !$this->input->post('month') ||
            !$this->input->post('year') ||
            !$this->input->post('job_id')
        ){
           echo json_encode(array('Status' => FALSE, 'Response' => 'Invalid request')); exit(0);
        }

        //
        $session = $this->session->userdata('logged_in');
        $company_sid  = $session['company_detail']['sid'];
        $company_name = strtolower(clean($session['company_detail']['CompanyName']));
        $employer_sid = $session['employer_detail']['sid'];
        
        $job_sid = $this->input->post('job_id');
        $month   = $this->input->post('month');
        $year    = $this->input->post('year');
        $page    = $this->input->post('page');
        
        //
        $date = $year.'-'.(strlen($month) == 1 ? '0'.$month : $month);

        $resumes = $this->bulk_resume_model->get_all_resume_by_job(
            $company_sid,
            $job_sid, 
            $date, 
            $page,
            $this->limit
        );

        if(!sizeof($resumes['Records'])){
            echo json_encode(array('Status' => FALSE, 'Response' => 'no record found.')); exit(0);
        }
        
        echo json_encode(array(
            'Status' => TRUE, 
            'Response' => $resumes['Records'], 
            'Limit' => $this->limit, 
            'ListSize' => $this->list_size, 
            'Total' => $resumes['Count'] 
        )); exit(0); 
    }

    /**
     * fetch applicants from 
     * AWS
     *  
     * @return JSON  
     */
    function generate_resumes(){
        //
        if (!$this->session->userdata('logged_in')) { echo json_encode(array('Status' => FALSE, 'Response' => 'Invalid request.')); exit(0); }
        //
        if(
            !$this->input->post('page') ||
            !$this->input->post('month') ||
            !$this->input->post('year') ||
            !$this->input->post('list') ||
            !$this->input->post('job_id')
        ){
           echo json_encode(array('Status' => FALSE, 'Response' => 'Invalid request')); exit(0);
        }

        //
        $session = $this->session->userdata('logged_in');
        $this->company_sid = $company_sid  = $session['company_detail']['sid'];
        $this->company_name = $company_name = strtolower(clean($session['company_detail']['CompanyName']));
        $this->employer_sid = $employer_sid = $session['employer_detail']['sid'];
        
        $this->job_sid =  $job_sid = $this->input->post('job_id');
        $month   = $this->input->post('month');
        $year    = $this->input->post('year');
        $page    = $this->input->post('page');
        $list    = $this->input->post('list');
        $type    = $this->input->post('dtype');
        //
        if($type == 'single') $list = $list[0];
        //
        if($list[0] == '*' && $type == 'all') $list = 'all';
        //
        if($list[0] == '*' && $type == 'selected') $list = [];
        //
        $company_folder = FCPATH . 'temp_files' . DIRECTORY_SEPARATOR . $company_name. DIRECTORY_SEPARATOR . $job_sid.DIRECTORY_SEPARATOR.'resumes';
        //
        if (!file_exists($company_folder)) mkdir($company_folder, 0755, true);
        //
        $path = $company_folder. DIRECTORY_SEPARATOR;
        //
        $date = $year.'-'.(strlen($month) == 1 ? '0'.$month : $month);

        $resumes = $this->bulk_resume_model->get_all_resume_by_job(
            $company_sid,
            $job_sid, 
            $date, 
            $page,
            $this->limit,
            $list
        );

        if(!sizeof($resumes)){
            echo json_encode(array('Status' => FALSE, 'Response' => 'no record found.')); exit(0);
        }

        $this->fetch_records_from_aws($resumes, $company_folder, $path, $type );
    }

    /**
     * download resumes from AWS 
     * and generate zip 
     *  
     * @param $list Array 
     * @param $company_folder String 
     * @param $path String 
     * @param $type String Optional
     *  
     * @return Array
     */
    private function fetch_records_from_aws($list, $company_folder, $path, $type = 'all'){
        // load ZIP library
        $this->load->library('zip');
        // create zip array
        // to hold all zip files
        // for download all options
        $zip_array = array();
        // error 
        $is_exception = 0;
        // Set the file name
        $file_name = strtolower( 
            $type == 'single' ? (
                $list[0]['first_name'].'-'.$list[0]['last_name']
            ) : $this->company_name
        );
        // flush directory
        $this->remove_ff($company_folder);
        // download selected array
        if($type == 'selected'){
            foreach ($list as $single) {
               if(!$this->download_resume_from_aws($single, $path)) $is_exception = 1;
            }
        } else if($type == 'single'){
            if(!$this->download_resume_from_aws($list[0], $path)) $is_exception = 1;
        }else{
            // get total chunks count
            // make chunks
            $list_chunks   = array_chunk($list, $this->limit);
            $list_chunks_size   = count($list_chunks);
            // loop through chunks
            for($i = 0; $i < $list_chunks_size; $i++){
                // get chunk of array
                $array_chunk = $list_chunks[$i];
                // count the array chgunk fo loop
                $array_chunk_count = count($array_chunk);
                //
                if($this->multiple_zip_files){
                    $k = $i + 1;
                    $chunk_path = $path.$k;
                    if(!is_dir($chunk_path)) mkdir($chunk_path, 0777);
                }
                //
                // loop through chunk
                for($j=0; $j < $array_chunk_count; $j++){
                    if(!$this->download_resume_from_aws($array_chunk[$j], ($this->multiple_zip_files ? $chunk_path.DIRECTORY_SEPARATOR : $path))) $is_exception = 1;
                }
                //
                if(!$this->multiple_zip_files) continue;
                // create zip folder for every chunk
                $this->zip->read_dir($path, false);
                $archive = $chunk_path.'.zip';
                $this->zip->archive($archive);

                $zip_array[] = $archive;
                // unlink($company_folder.DIRECTORY_SEPARATOR.$k);
                // remove files from $i name folder
                $this->remove_ff($chunk_path.DIRECTORY_SEPARATOR, array($k.'.zip'));
            }
        }
        //
        $issaved = $this->zip->read_dir($path, false);
        $archive = $path . $file_name . '.zip';
        $this->zip->archive($archive);
        $zip_generated = true;
        //
        if($this->multiple_zip_files){
            foreach ($zip_array as $k0 => $v0) {
                unlink($v0);
            }
        }
        //
        $this->remove_ff($company_folder, array($file_name.'.zip'));
        //
        $message = '<b>Success:</b> Your Resume archive has been successfully created.';
        if ($is_exception == 1) {
            $message = '<b>Warning:</b> There was a technical issue while we were trying to download your resumes. Please contact our Technical Support Team at '.TALENT_NETWORK_SUPPORT_CONTACTNO.' or '.TALENT_NETOWRK_SUPPORT_EMAIL.' if you continue experiencing issues.';
            sendEmail('support@automotohr.com', 'dev@automotohr.com', 'Bulk Resume Download Error', 'One of the company tried to bulk download resume and there was exception, please review it', ucwords(str_replace('-', ' ', $this->company_name)), REPLY_TO);
            // for new helper
            // send_email(array(
            //     'from' => 'support@automotohr.com',
            //     'to' => 'dev@automotohr.com', 
            //     'subject' => 'Bulk Resume Download Error',
            //     'body' => 'One of the company tried to bulk download resume and there was exception, please review it',
            //     'from_name' => ucwords(str_replace('-', ' ', $this->company_name)),
            //     'reply_to' => REPLY_TO
            // ));
        }
        //
        $return_data = array();
        $return_data['Status'] = (bool)!$is_exception;
        $return_data['Response'] = $message;
        $return_data['Company_name'] = $file_name;
        $return_data['Archive'] = $file_name . '.zip';
        $return_data['Directory'] = strtolower($this->company_name).DIRECTORY_SEPARATOR.$this->job_sid.DIRECTORY_SEPARATOR.'resumes';
        $return_data['Zip_generated'] = $zip_generated;
        //
        header('Content-Type: application/json');
        echo json_encode($return_data);
    }

    /**
     * delete directories and files 
     * 
     * @param $folder String  
     * @param $skip_array Array Optional  
     * 
     */
    private function remove_ff($folder, $skip_array = array()){
        // scan te dir
        $files = scandir($folder);
        // remove previous data
        if (count($files) <= 2) return false;
        $skip_array = array_merge(array('.', '..'), $skip_array);
        foreach ($files as $file) {
            if (in_array($file, $skip_array)) continue;
            //
            $file_path = $folder . DIRECTORY_SEPARATOR . $file;
            //
            if (is_file($file_path)) unlink($file_path);
        }
    }

    /**
     * download 
     *  
     */
    function download(){

        // error_reporting(E_ALL);
        //     
        $archive = FCPATH . 'temp_files' . DIRECTORY_SEPARATOR . $this->input->post('txt_file');    

        //
        if (!file_exists($archive)) return false;
        //
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename="' . $this->input->post('txt_company_name') . '"');
        header('Content-Disposition: attachment; filename="' . $this->input->post('txt_company_name') . '.zip' . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archive));
        $handle = fopen($archive, 'rb');
        $buffer = '';
        
        while (!feof($handle)) {
            // $buffer = fread($handle, 4096);
            $buffer = fread($handle, filesize($archive));
            echo $buffer;
            ob_flush();
            flush();
        }
        
        fclose($handle);

        if (is_file($archive)) unlink($archive);
    }

    /**
     * download single resume from AWS 
     *  
     * @param $resume Array 
     * @param $path String 
     *  
     * @return Bool
     */
    private function download_resume_from_aws($resume, $path){
        if(!class_exists('aws_lib')) $this->load->library('aws_lib');
        $object_type = $resume['resume'];
        $resume_ext = strrchr($resume['resume'], '.');
        $resume_email = clean($resume['email']);
        $file_name = $resume['first_name'] . '-' . $resume['last_name'] . '_' . $resume_email . $resume_ext;

        try {
            return $this->aws_lib->copy_files_buckets_to_server($this->bucket_name, $object_type, $path, $file_name);
        } catch (Exception $e) {
            $is_exception = 1;
            $exception_message = $e->getMessage();
            $this->bulk_resume_model->record_exception($this->company_sid, $this->employer_sid, $object_type, $exception_message);
            return false;
        }
    } 

    /**
     * get months 
     *  
     * @return  Array 
     */
    private function get_months(){
        return array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        );
    }
}