<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends Admin_Controller {
// class Sms extends CI_Controller {

    private $resp;
    private $limit = 100;
    private $listSize = 5;

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/sms_model');
        // Set default return array
        $this->resp['Status'] = FALSE;
        $this->resp['Response'] = 'Invalid request made.';
    }

    /**
     * SMS
     * Created on: 16-07-2019
     * 
     * @param $page String 
     * 'send', 'view'
     * Default is send
     * 
     * @return VOID
     */
    function index($page = 'send'){
        $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'login', 'index'); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $page = strtolower(trim($page));
        $this->data['page_view'] = !in_array($page, array('send', 'view')) ? 'send' : $page;
        $this->render('manage_admin/sms/index');
    }


    /**
     * Handles AJAX events
     * Created on: 17-07-2019
     * 
     * @accepts $_POST
     * action
     * 'send_sms'
     * 
     * 
     * @return JSON
     */
    function handler(){
        // Check for post type && AJAX request
        if($this->input->method(TRUE) !== 'POST' || !$this->input->is_ajax_request()) $this->resp();
        // Check for post size && 'action' index
        if(!sizeof($this->input->post()) || !$this->input->post('action', TRUE)) $this->resp();

        // Save cleaned post to local variable
        $form_data = $this->input->post(NULL, TRUE);

        // Load twilio sms library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        switch ($form_data['action']) {
            case 'send_sms':
                // Check for required fiels
                // Check for receiver phone
                if(!isset($form_data['receiver_phone_number']) || $form_data['receiver_phone_number'] == ''){
                    $this->resp['Response'] = 'Receiver phone number is required.';
                    $this->resp();
                }
                // Check for message
                if(!isset($form_data['message']) || trim($form_data['message']) == ''){
                    $this->resp['Response'] = 'Message field can not be empty.';
                    $this->resp();
                }
                // Default sms type will always be 'sandbox'
                $sms_type = 'sandbox';
                // Set sms type
                if(isset($form_data['sms_type'])) $sms_type = $form_data['sms_type'];

                //
                $this
                ->twilio
                ->setReceiverPhone($form_data['receiver_phone_number']);

                if($sms_type === 'production'){
                    $this
                    ->twilio
                    ->setMessageServiceSID($form_data['sender_phone_number'])
                    // ->setMessageServiceSID('MG359e34ef1e42c763d3afc96c5ff28eaf')
                    ->getPhone($form_data['sender_phone_number_type'], 'number');
                }

                $resp = $this
                ->twilio
                ->setMode($sms_type)
                ->setMessage($form_data['message'])
                ->sendMessage();
                // ->debug();

                // _e($resp, true, true);
                // Check & Handling Errors
                if(!is_array($resp)) { $this->resp['Response'] = 'System failed to send sms.'; $this->resp(); }
                if(isset($resp['Error'])){ $this->resp['Response'] = $resp['Error']; $this->resp(); }
                // Set Insert Array
                $insert_array = $resp['DataArray'];
                $insert_array['sender_user_id'] = $this->ion_auth->user()->row()->id;
                $insert_array['sender_user_type'] = 'admin';
                $insert_array['receiver_user_type'] = 'admin';
                $insert_array['module_slug'] = 'admin';
                // Add data in database
                $insert_id = $this->sms_model->_insert('portal_sms', $insert_array);
                if(!$insert_id) { $this->resp['Response'] = 'System failed save sms'; $this->resp(); }

                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Sms sent to '.( $insert_array['receiver_phone_number'] ).'.';
                $this->resp();                
            break;

            case 'fetch_sms':
                // 
                $inset = 0;
                $offset = $this->limit;
                if($form_data['page'] > 1){
                    $inset = ( $form_data['page'] - 1 ) * $this->limit;
                    $offset = $inset * $form_data['page'];
                }
                //
                $sms = $this->sms_model->get_sms(
                    'admin', 
                    $this->ion_auth->user()->row()->id,
                    $inset,
                    $offset,
                    $form_data['total_records']
                );
                //
                if(!$sms || !sizeof($sms)){
                    $this->resp['Response'] = 'No record found.';
                    $this->resp();
                }
                //
                $this->resp['Status'] = TRUE;
                $this->resp['Data']   = $sms['Records'];
                $this->resp['Limit']  = $this->limit;
                $this->resp['ListSize'] = $this->listSize;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['TotalPages'] = ceil($sms['TotalRecords']/$this->limit);
                $this->resp['TotalRecords'] = $sms['TotalRecords'];
                $this->resp();
            break;

            case 'fetch_detail_by_phone':

             // 
                $inset = 0;
                $offset = $this->limit;
                if($form_data['page'] > 1){
                    $inset = ( $form_data['page'] - 1 ) * $this->limit;
                    $offset = $inset * $form_data['page'];
                }

                //
                $sms = $this->sms_model->get_sms_by_phonenumber(
                    'admin', 
                    $this->ion_auth->user()->row()->id, 
                    $form_data['phone_number'],
                    $inset,
                    $offset,
                    $form_data['total_records']
                );
                //
                if(!$sms || !sizeof($sms)){
                    $this->resp['Response'] = 'No record found.';
                    $this->resp();
                }
                // Update phone status
                $this->sms_model->update_read_status_by_phone_number_and_module($form_data['phone_number'], 'admin');
                //
                $this->resp['Status'] = TRUE;
                $this->resp['Data']   = $sms['Records'];
                $this->resp['Limit']  = $this->limit;
                $this->resp['ListSize'] = $this->listSize;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['TotalPages'] = ceil($sms['TotalRecords']/$this->limit);
                $this->resp['TotalRecords'] = $sms['TotalRecords'];
                $this->resp();
            break;
            
            default: $this->resp(); break;
        }
        _e($form_data, true, true);

    }

    /**
     * Send json
     * Created on: 17-07-2019
     * 
     * @return JSON
     */
    function resp(){
        header('Content-Type: application/json');
        echo @json_encode($this->resp);
        exit(0);
    }

}
