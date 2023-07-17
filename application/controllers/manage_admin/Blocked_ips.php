<?php defined('BASEPATH') or exit('No direct script access allowed');

error_reporting(E_ALL);

class Blocked_ips extends Admin_Controller
{

    private $resp;
    private $limit = 100;
    private $listSize = 5;

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/ip_model', 'ipmodal');
        // Set default return array
        $this->resp['Status'] = FALSE;
        $this->resp['Response'] = 'Invalid request made.';
    }

    /**
     * Index page
     * Created on: 01-08-2019
     * 
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     * 
     * @return VOID
     */
    public function index()
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        //
        $redirect_url   = 'manage_admin';
        $function_name  = 'blocked_app';
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        //
        $this->data['title'] = 'Blocked IPs';
        $this->data['subtitle'] = 'List Of All Blocked IPs';
        //
        $this->render('manage_admin/block_ips/view', 'admin_master');
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
    function handler()
    {
        // Check for post type && AJAX request
        if ($this->input->method(TRUE) !== 'POST' || !$this->input->is_ajax_request()) $this->resp();
        // Check for post size && 'action' index
        if (!sizeof($this->input->post()) || !$this->input->post('action', TRUE)) $this->resp();
        // Save cleaned post to local variable
        $form_data = $this->input->post(NULL, TRUE);

        //
        switch ($form_data['action']) {
            case 'fetch_records':
                // 
                $inset = 0;
                $offset = $this->limit;
                if ($form_data['page'] > 1) {
                    $inset = ($form_data['page'] - 1) * $this->limit;
                    $offset = $inset * $form_data['page'];
                }
                //
                $ips = $this->ipmodal->get_ips(
                    $inset,
                    $offset
                );
                //
                if (!$ips || !sizeof($ips)) {
                    $this->resp['Response'] = 'No record found.';
                    $this->resp();
                }
                //
                $this->resp['Status'] = TRUE;
                $this->resp['Data']   = $ips['Records'];
                $this->resp['Limit']  = $this->limit;
                $this->resp['ListSize'] = $this->listSize;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['TotalPages'] = ceil($ips['TotalRecords'] / $this->limit);
                $this->resp['TotalRecords'] = $ips['TotalRecords'];
                $this->resp();
                break;

            case 'save_ips';
                //
                $ip_list = array();
                $new_ips_rows = '';
                //
                if (!sizeof($form_data['ip_list'])) $this->resp();
                //
                foreach ($form_data['ip_list'] as $k0 => $v0) {
                    //
                    $v0 = trim($v0);
                    //
                    $ip_list[$k0]['ip_address'] = $v0;
                    //
                    $is_exist = $this->ipmodal->check_ip($v0);
                    //
                    if ($is_exist) {
                        $ip_list[$k0]['status'] = 0;
                        continue;
                    }
                    //
                    $ip_list[$k0]['status'] = 1;
                    //
                    $this->ipmodal->_insert('blocked_ips', array(
                        'admin_sid' => $this->ion_auth->user()->row()->id,
                        'ip_address' => $v0
                    ));
                    //
                    $new_ips_rows .= "DENY FROM $v0" . "\n";
                }

                //
                if ($new_ips_rows != '') {
                    $new_ips_rows = "\n" . $new_ips_rows;
                    // Append it to htaccess
                    $file_name = ROOTPATH . '.htaccess';
                    $backup_folder = ROOTPATH . 'htaccess_backups';
                    //
                    $handler = fopen($file_name, 'a');
                    $old_file_data = file_get_contents($file_name);
                    fwrite($handler, $new_ips_rows);
                    fclose($handler);
                    //
                    if (!is_dir($backup_folder)) mkdir($backup_folder, 0777);
                    //
                    $backup_file = $backup_folder . DIRECTORY_SEPARATOR . 'htaccess.' . date('dmYHis', strtotime('now'));
                    $handler = fopen($backup_file, 'w');
                    fwrite($handler, $old_file_data);
                    fclose($handler);
                }

                $this->resp['Data'] = $ip_list;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->resp();
                break;

            default:
                $this->resp();
                break;
        }
    }

    public function statusHandler()
    {
        //
        $post = $this->input->post(null, true);
        //
        $this->db->where('ip_address', $post['id'])->update('blocked_ips', ['is_block' => $post['status']]);
    }

    /**
     * Send json
     * Created on: 17-07-2019
     * 
     * @return JSON
     */
    function resp()
    {
        header('Content-Type: application/json');
        echo @json_encode($this->resp);
        exit(0);
    }
}
