<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'list_admin_groups';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->render('manage_admin/groups/list_groups_view');
    }

    public function create()
    {
        $this->data['page_title'] = 'Add group';
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'create_admin_groups'); // Param2: Redirect URL, Param3: Function Name 
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required|is_unique[administrator_groups.name]');
        $this->form_validation->set_rules('group_description', 'Group description', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->helper('form');
            $this->render('manage_admin/groups/create_group_view');
        } else {
            $group_name = $this->input->post('group_name');
            $group_description = $this->input->post('group_description');

            $modules = $this->db->select('available_modules')->where('id',1)->get('administrator_groups')->result_array()[0]['available_modules'];
            $this->ion_auth->create_group($group_name, $group_description, '', $modules);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('manage_admin/groups', 'refresh');
        }
    }

    public function edit($group_id = NULL)
    {
        $group_id = $this->input->post('group_id') ? $this->input->post('group_id') : $group_id;
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'create_admin_groups'); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Edit group';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');
        $this->form_validation->set_rules('group_description', 'Group description', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Group id', 'trim|integer|required');

        $available_modules = $this->db->select('available_modules')->where('id',1)->get('administrator_groups')->result_array()[0]['available_modules'];
        $available_modules = unserialize($available_modules);
        if ($this->form_validation->run() === FALSE) {
            if ($group = $this->ion_auth->group((int)$group_id)->row()) {
                $this->data['group'] = $group;
                $permissions = array();
                $access_level_permissions = $group->permissions;

                if (!empty($access_level_permissions)) {
                    $permissions = unserialize($access_level_permissions);
                }

                $this->data['permissions'] = $permissions;
//                $available_modules = $group->available_modules;
//                $available_modules = unserialize($available_modules);
                $this->data['modules'] = $available_modules;

            } else {
                $this->session->set_flashdata('message', 'The group doesn\'t exist.');
                redirect('manage_admin/groups', 'refresh');
            }

            $this->load->helper('form');
            $this->render('manage_admin/groups/edit_group_view');
        } else {
            $group_name = $this->input->post('group_name');
            $group_description = $this->input->post('group_description');
            $group_id = $this->input->post('group_id');
//            $group = $this->ion_auth->group((int)$group_id)->row();
//            $available_modules = $group->available_modules;
//            $available_modules = unserialize($available_modules);
            $function_name = '';
            $update_permission = array();
            //$available_modules                                              = unserialize($available_modules);
            if (isset($_POST['function_name'])) {
                $function_name = $_POST['function_name'];
            }

            if (!empty($function_name)) { // update permissions for the user
                foreach ($function_name as $mykey => $fn_name) {
                    $key = array_search($fn_name, array_column($available_modules, 'function_name'));
                    $mykey++;
                    $update_permission[] = $available_modules[$key];
                }
            }
            $update_permission = serialize($update_permission);
            $this->ion_auth->update_group($group_id, $group_name, $group_description, $update_permission);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('manage_admin/groups', 'refresh');
        }
    }

    public function delete()
    {
        $group_id = $this->input->post('sid');
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        if (in_array('full_access', $security_details) || in_array('create_admin_groups', $security_details)) {
            $this->ion_auth->delete_group($group_id);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
        }
    }
}