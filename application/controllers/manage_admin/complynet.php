<?php defined('BASEPATH') or exit('No direct script access allowed');

class Complynet extends Admin_Controller
{

    private $limit = 10;
    private $applicantLimit = 10;
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/complynet_model');
        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    //
    public function index()
    {

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'copy_applicants');
        $this->data['page_title'] = 'Manage Companies';
        $this->data['active_companies'] = $this->complynet_model->get_all_companies();

        //
        $total_records = $this->complynet_model->get_complynet_maped_companies(null, null, true);
        $base_url = base_url('manage_admin/complynet');
        $offset = 0;
        $page_number  = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $records_per_page = 4; //PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }


        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 3;
        $config['num_links'] = 5;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data['page_number'] = $page_number;
        $this->data['links'] = $this->pagination->create_links();
        $this->data['company_records'] = $this->complynet_model->get_complynet_maped_companies($records_per_page, $offset);
        $this->render('manage_admin/complynet/manage_companies', 'admin_master');
    }

    public function mapcompany()
    {
        //
        $automotohrcompany = $this->input->post('automotohrcompany');
        $complynetcompany = $this->input->post('complynetcompany');
        $automotohrcompanydata = explode('#', $automotohrcompany);
        $complynetcompanydata = explode('#', $complynetcompany);

        $data_insert['automotohr_sid'] = $automotohrcompanydata[0];
        $data_insert['automotohr_name'] = $automotohrcompanydata[1];
        $data_insert['status'] = $automotohrcompanydata[2];
        $data_insert['complynet_sid'] = $complynetcompanydata[0];
        $data_insert['complynet_name'] = $complynetcompanydata[1];
        $data_insert['created_at'] = date('Y-m-d H:i:s');
        //
        $query_status = $this->complynet_model->mapcompany($data_insert);
        if ($query_status == 'saved') {
            $this->session->set_flashdata('message', '<b>Success:</b> Company Maped Successfully');
        }
        echo $query_status;
    }

    //
    function changecomplynetstatus()
    {
        $automotohr_sid = $this->input->post('automotohr_sid');
        $complynet_status = $this->input->post('complynet_status');
        $this->complynet_model->update_complynet_status($automotohr_sid, $complynet_status);
        $this->session->set_flashdata('message', '<b>Success:</b> Company Status Changed Successfully');
    }



    //
    public function getcompanyemployees($company_id)
    {

        $employees = $this->complynet_model->get_active_employees_detail($company_id);
        $result_head = '<table class="table table-bordered table-hover table-striped table-condensed">
         <thead>
             <tr>
                 <th class="col-xs-4">Employee Name</th>
                 <th class="col-xs-2">Email</th>
              
             </tr>
         </thead>
         <thead>';

        //
        $result_row = '';
        if (!empty($employees)) {
            foreach ($employees as $emp_row) {

                $result_row .=  '<tr>                  
             <td>' . getUserNameBySID($emp_row['sid'], $remake = true) . '</td>
             <td>' . $emp_row['email'] . '</td>
                     </tr>';
            }
        } else {

            $result_row .=  '<tr><td colspan="5" class="text-center"><div class="no-data">No employee found.</div></td> </tr>';
        }

        //
        $result_footer = '</thead>
         </table>';
        echo $result_head . $result_row . $result_footer;
    }
}
