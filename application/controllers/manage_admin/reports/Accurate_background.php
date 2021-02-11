<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accurate_background extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/accurate_background_report_model');
    }

    public function index(
        $company_sid = false, 
        $product_type = false, 
        $status = false, 
        $from_date = false, 
        $to_date = false) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/';
        $function_name = 'dashboard';
        $this->data['security_details'] = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($this->data['security_details'], $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        // For AJAX
        if($this->input->post('action')){
            // Set Search
            // Set company id
            if ($company_sid != 'all' && $company_sid !== FALSE)
                $company_sid = $company_sid;
            else $company_sid = 'all';

            // Set product type
            if ($product_type != 'all' && $product_type !== FALSE)
                $product_type = $product_type == 'background_check' ? 'background-checks' : 'drug-testing';
            else $product_type = 'all';

            // Set status type
            if ($status != 'all' && $status !== FALSE)
                $status = $status;
            else $status = 'all';

            // Set start date
            if ($from_date != 'all' && $from_date !== FALSE)
                $from_date = empty($from_date) ? null : DateTime::createFromFormat('m-d-Y', $from_date)->format('Y-m-d');
            else $from_date = date('Y-m-d');

            // Set end date
            if ($to_date != 'all' && $to_date !== FALSE)
                $to_date = empty($to_date) ? null : DateTime::createFromFormat('m-d-Y', $to_date)->format('Y-m-d');
            else $to_date = date('Y-m-d');

            // Set defaults
            $resp = array('Status' => FALSE, 'Response' => 'Invalid request.');
            $limit = 100;
            $offset = $limit; 
            $inset = 0;
            $total_pages = 1;
            // Set page and total records
            $page = $this->input->post('page', true);
            $total_records = $this->input->post('total_records', true);
            $status_records = $this->input->post('status_records', true) ? 
            $this->input->post('status_records', true) : 
            array(
                'pending' => array(),
                'cancelled' => array(),
                'completed' => array(),
                'awaiting_candidate_input' => array()
            );
            $overwrite_status = false;
            $pass = true;
            $ids_array = array();
            if(!sizeof($status_records['pending']) &&
                !sizeof($status_records['cancelled']) &&
                !sizeof($status_records['completed']) &&
                !sizeof($status_records['awaiting_candidate_input'])
            ) $overwrite_status = true;

            //
            if($status != 'all' && !$overwrite_status){
                if(!sizeof($status_records[$status])) $pass = false;
                else $ids_array = $status_records[$status];
            }

            if($this->input->post('pages', true)){

                $records = $this->accurate_background_report_model->
                get_all_accurate_background(
                    $company_sid,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
                    $inset,
                    $offset,
                    false,
                    $ids_array,
                    true
                );
                $file_name = '';
                $file_name .= str_replace( '-', '_',  $from_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $to_date );
                $file_name .= '_';
                $file_name .= str_replace( '-', '_',  $product_type );
                $file_name .= '_';
                $file_name .= $company_sid;
                $file_name .= '_';
                $file_name .=  $status;
                $file_name = generate_csv($records, $file_name);

                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Overwrite'] = $overwrite_status;
                $resp['Data'] = $file_name;

                header('Content-Type: application/json');
                echo json_encode($resp);
                exit(0);
            }
            //
            if($pass){
                //
                if($total_records == 0){
                    // Fetch records count 
                    $result_arr = $this->accurate_background_report_model->
                    get_all_accurate_background(
                        $company_sid,
                        $product_type,
                        $status,
                        $from_date,
                        $to_date,
                        $inset,
                        $offset,
                        true,
                        $ids_array
                    );

                    //

                    $total_records = $resp['TotalRecords'] = $result_arr['TotalRecords'];

                    if($overwrite_status)
                        $status_records = $resp['StatusRecords'] = $result_arr['StatusArray'];

                    if($status != 'all')
                        if(sizeof($status_records[$status])) $ids_array = $status_records[$status];

                    $resp['Limit'] = $limit;
                }else{
                    $inset = $page == 1 ? 0 : ( ( $page - 1 ) * $limit);
                    $offset = $inset * $page;
                }

                $resp['TotalPages'] = $total_pages = ceil($total_records / $limit);

                $resp['from_records'] = $inset == 0 ? 1 : $inset;
                $resp['to_records']   = $total_records < $limit ? 
                $total_records : ( $page == $total_pages ? $total_records : $inset + $limit);

                //
                $resp['Data'] = $this->accurate_background_report_model->
                get_all_accurate_background(
                    $company_sid,
                    $product_type,
                    $status,
                    $from_date,
                    $to_date,
                    $inset,
                    $offset,
                    false,
                    $ids_array
                );
            }else{
                $resp['Data'] = '';
                $resp['TotalPages'] = 0;
                $resp['from_records'] = 0;
                $resp['to_records'] = 0;
            }

            //
            $resp['Status'] = TRUE;
            $resp['Response'] = 'Proceed.';
            $resp['Overwrite'] = $overwrite_status;

            header('Content-Type: application/json');
            echo json_encode($resp);
            exit(0);
        }

        //
        $this->data['title'] = 'Accurate Background Report';
        $this->data['companies'] = $this->accurate_background_report_model->get_all_companies();
        $this->render('manage_admin/reports/accurate_background_report');
    }


    /**
     * Download file
     * Created on: 279-05-2019
     *
     * @param $type String (csv)
     * @param $file_name String
     *
     * @return VOID
     */
    function download($type, $file_name){
        $download_file = APPPATH . '../assets/'.$type.'/'.$file_name.'.'.$type;
        download_file($download_file);
        exit(0);
    }
}
