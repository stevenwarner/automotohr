<?php defined('BASEPATH') or exit('No direct script access allowed');

class Print_export extends Public_Controller{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 
     */
    public function manageCompanies(){
        // Needs to be login
        $this->checkLogin();
        //
        $this->load->model('manage_admin/company_model');
        //
        $get = $this->input->get(null, true);
        // Lets fetch the data 
        $companies = $this->company_model->get_all_companies_date(
            $get['contact_name'],
            $get['company_name'],
            $get['company_type'],
            $get['company_status'],
            !empty($get['registration_from_date']) ? formatDateToDB($get['registration_from_date'], 'm-d-Y', DB_DATE).' 00:00:00' : null,
            !empty($get['registration_to_date']) ? formatDateToDB($get['registration_to_date'], 'm-d-Y', DB_DATE).' 00:00:00' : null,
            null,
            null,
            [
                'sid as Id', 
                'ContactName as Contact Name',
                'CompanyName as Company Name',
                'PhoneNumber as Phone Number',
                'registration_date as Registration Date',
                'expiry_date as Expiration Date',
                'active as  Status'
            ]
        );
        //
        if($get['action'] == 'export'){
            //
            $fileName = 'Companies_'.date('d_m_Y_H_i_s', strtotime('now'));
            //
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.($fileName).'.csv');
            $handler = fopen('php://output', 'w');
            fputcsv($handler, ['Id', 'Contact Name', 'Company Name', 'Phone Number', 'Registration Date', 'Expiration Date', 'Status']);
            //
            if(!empty($companies)){
                foreach($companies as $company){
                    fputcsv($handler, [
                        $company['Id'],
                        $company['Contact Name'],
                        $company['Company Name'],
                        phonenumber_format($company['Phone Number']),
                        date_with_time($company['Registration Date']),
                        !empty($company['Expiration Date']) ? date_with_time($company['Expiration Date']) : 'Not Set',
                        $company['Status'] == 1 ? 'Active' : 'In-Active'
                    ]);
                }
            }
            fclose($handler);
            exit(0);
        }
        //
        $data['records'] = $companies;
        $this->load->view('2022/pd/header', $data);
        $this->load->view('2022/pd/print');
        $this->load->view('2022/pd/footer');
    }


    /**
     * 
     */
    private function checkLogin(){
        //
        $session = $this->session->userdata('user_id');
        //
        if(!$session){
            return redirect('/');
        }
        //
        return $session;
    }
}
