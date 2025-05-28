<?php defined('BASEPATH') or exit('No direct script access allowed');

class Email_templates extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/email_templates_model');
        $this->data['group_options'] = array(
            '' => 'Select Group Name',
            'user' => 'User Emails',
            'listing' => 'Listing Emails',
            'product' => 'Product Emails',
            'alerts' => 'Email Alerts',
            'other' => 'Other Emails',
            'super_admin_templates' => 'Super Admin Templates',
            'portal_email_templates' => 'Portal Email Templates'
        );

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'email_templates';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Email Template Groups';
        $this->data['data'] = $this->email_templates_model->get_all_templates_groups();
        $this->form_validation->set_rules('name', 'Template Name', 'trim|required|is_unique[email_templates.name]');
        $this->form_validation->set_rules('group', 'Group Name', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/email_templates/listings_view', 'admin_master');
        } else {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);

            if (in_array('full_access', $security_details) || in_array('add_email_templates_group', $security_details)) {
                $name = $this->input->post('name');
                $status = 1;
                $group = str_replace(' ', '_', strtolower($this->input->post('group')));
                $data = array(
                    'name' => $name,
                    'group' => $group,
                    'template_code' => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($name)),
                    'status' => $status,
                    'from_name' => '{{company_name}}',
                    'from_email' => FROM_EMAIL_INFO,
                );
                $insert_id = $this->email_templates_model->save_template($data);

                // Redirect to edit page
                redirect('manage_admin/email_templates/edit_email_templates_view/' . $insert_id);
            }

            redirect('manage_admin/email_templates', 'refresh');
        }
    }

    public function email_templates_view($group = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'email_templates_view';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $group = $this->input->post('group') ? $this->input->post('group') : $group;

        if (empty($group)) {
            $this->session->set_flashdata('message', 'Template group not found!');
            redirect('manage_admin/email_templates', 'refresh');
        }
        $this->data['group'] = $group;

        $this->data['page_title'] = 'Edit Email Templates';
        $this->data['data'] = $this->email_templates_model->get_all_templates($group);
        $this->render('manage_admin/email_templates/email_templates_view', 'admin_master');
    }

    public function edit_email_templates_view($sid = NULL)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_email_templates_view';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $sid = $this->input->post('sid') ? $this->input->post('sid') : $sid;

        if (empty($sid)) {
            $this->session->set_flashdata('message', 'Template E-mail not found!');
            redirect('manage_admin/email_templates', 'refresh');
        }

        $template_data = $this->email_templates_model->get_template_data($sid);
        $this->data['data'] = $template_data[0];
        $this->data['page_title'] = 'Edit "' . $template_data[0]['name'] . '" Template';
        $this->form_validation->set_rules('name', 'Template Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('group', 'Group Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('form_name', 'From Name', 'trim|xss_clean');
        $this->form_validation->set_rules('from_email', 'From Email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('cc', 'CC', 'trim|xss_clean');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|xss_clean');
        //$this->form_validation->set_rules('file','Attachment','trim');
        $this->form_validation->set_rules('text', 'Text', 'trim');

        if ($this->form_validation->run() === FALSE) {
            if ($template_data) {
                $this->data['data'] = $template_data[0];
            } else {
                $this->session->set_flashdata('message', 'email template does not exists!');
                redirect('manage_admin/email_templates', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/email_templates/edit_email_templates_view', 'admin_master');
        } else {
            $sid = $this->input->post('sid');
            $action = $this->input->post('action');

            $details = $this->input->post('text', false);

            $cleanedDetails = sc_remove($details);

            $data = array(
                'name' => $this->input->post('name'),
                'group' => str_replace(' ', '_', strtolower($this->input->post('group'))),
                'from_name' => $this->input->post('form_name'),
                'from_email' => $this->input->post('from_email'),
                'cc' => $this->input->post('cc'),
                'subject' => $this->input->post('subject'),
                'status' => $this->input->post('status'),
                'text' => $cleanedDetails
            );

            if (isset($_FILES['file']) && $_FILES['file']['name'] != '') { //uploading image to AWS
                // $file = explode(".", $_FILES['file']['name']);
                // $file_name = str_replace(" ", "-", $file[0]);
                // $pictures = $file_name . '-' . random_string(5) . '.' . $file[1];

                if ($_FILES['file']['size'] == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');

                    if ($action == 'Save') {
                        redirect('manage_admin/email_templates/email_templates_view/' . $template_data[0]['group'], 'refresh');
                    } else {
                        redirect('manage_admin/email_templates/edit_email_templates_view/' . $sid, 'refresh');
                    }
                }

                // Rename the file
                // updated on: 2019-04-19
                $original_name = $_FILES['file']['name'];
                $original_name = trim($original_name);
                $original_name = str_replace('  ', '_', $original_name);
                $original_name = str_replace(' ', '_', $original_name);
                $last_index_of_dot = strrpos($_FILES['file']['name'], '.') + 1;
                $file_ext = substr($_FILES['file']['name'], $last_index_of_dot, strlen($_FILES['file']["name"]) - $last_index_of_dot);
                $new_file_name = strtotime('now') . '-' . generateRandomString(3) . '.' . $file_ext;


                $aws = new AwsSdk();
                $aws->putToBucket($new_file_name, $_FILES["file"]["tmp_name"], AWS_S3_BUCKET_NAME);
                $data['file'] = $new_file_name;
            }

            $this->email_templates_model->update_email_template($sid, $data);

            if ($action == 'Save') {
                redirect('manage_admin/email_templates/email_templates_view/' . $template_data[0]['group'], 'refresh');
            } else {
                redirect('manage_admin/email_templates/edit_email_templates_view/' . $sid, 'refresh');
            }
        }
    }

    public function delete_email_templates_view()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'delete_email_templates_view';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $sid = $this->input->post('sid');
        $this->load->model('email_templates_model');
        $this->email_templates_model->delete_template($sid);
        $this->session->set_flashdata('message', 'Emial Template Successfully Deleted');
    }

    public function remove_image()
    {
        $action = $this->input->post('action');

        if ($action == 'remove_logo') {
            $template_id = $this->input->post('sid');
            $this->load->model('email_templates_model');
            $this->email_templates_model->delete_image($template_id);
        }
    }

    public function email_templates_listing()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'email_templates_listing';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);

        $this->data['security_details'] = $security_details;
        $this->data['page_title'] = 'Email Template library';
        $this->data['templates_data'] = $this->email_templates_model->get_all_email_templates();

        $this->render('manage_admin/email_template_module/email_listing', 'admin_master');
    }

    public function add_email_template()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'add_email_template';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);

        $this->data['security_details'] = $security_details;
        $this->data['page_title'] = 'Add Email Template';
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/email_template_module/add_email_template', 'admin_master');
        } else {
            $title_name = $this->input->post('template_name');
            $email_body = $this->input->post('email_body', false);

            $insert_email_template_data = array();
            $insert_email_template_data['template_code'] = 'super_admin';
            $insert_email_template_data['created'] = date('Y-m-d H:i:s');
            $insert_email_template_data['company_sid'] = 1;
            $insert_email_template_data['template_name'] = $title_name;
            $insert_email_template_data['from_name'] = 'NULL';
            $insert_email_template_data['from_email'] = 'NULL';
            $insert_email_template_data['subject'] = 'NULL';
            $insert_email_template_data['message_body'] = $email_body;
            $insert_email_template_data['enable_auto_responder'] = 0;

            $this->email_templates_model->insert_new_email_templates($insert_email_template_data);
            $this->session->set_flashdata('message', '<strong>Success: </strong>Email Template Submitted Successfully!');
            redirect('manage_admin/email_templates_listing', 'refresh');
        }
    }

    public function edit_email_template($sid = NULL)
    {
        if (empty($sid) || $sid < 0) {
            $this->session->set_flashdata('message', 'Template E-mail not found!');
            redirect('manage_admin/email_templates_listing', 'refresh');
        } else {
            $redirect_url = 'manage_admin';
            $function_name = 'edit_email_template';
            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);

            $this->data['security_details'] = $security_details;
            $this->data['page_title'] = 'Edit Email Template';
            $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');

            $email_template = $this->email_templates_model->get_email_template($sid);

            if (empty($email_template)) {
                $this->session->set_flashdata('message', '<strong>Success: </strong>The Record Not found!');
                redirect('manage_admin/email_templates_listing', 'refresh');
            }

            if ($this->form_validation->run() === FALSE) {
                $this->data['email_template'] = $email_template;
                $this->render('manage_admin/email_template_module/edit_email_template', 'admin_master');
            } else {
                $title_name = $this->input->post('template_name');
                $email_body = $this->input->post('email_body', false);

                $update_email_template_data = array();
                $update_email_template_data['template_code'] = 'super_admin';
                $update_email_template_data['created'] = date('Y-m-d H:i:s');
                $update_email_template_data['company_sid'] = 1;
                $update_email_template_data['template_name'] = $title_name;
                $update_email_template_data['from_name'] = 'NULL';
                $update_email_template_data['from_email'] = 'NULL';
                $update_email_template_data['subject'] = 'NULL';
                $update_email_template_data['message_body'] = $email_body;
                $update_email_template_data['enable_auto_responder'] = 0;

                $this->email_templates_model->update_email_templates($sid, $update_email_template_data);
                $this->session->set_flashdata('message', '<strong>Success: </strong>Email Template Update Successfully!');
                redirect('manage_admin/email_templates_listing', 'refresh');
            }
        }
    }
}
