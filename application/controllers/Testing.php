<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('hr_documents_management_model');
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }


    public function setExistingCompanyStatus()
    {
        $companies = $this->db
            ->get("gusto_companies")
            ->result_array();

        foreach ($companies as $value) {
            $this->db->insert("gusto_companies_mode", [
                "company_sid" => $value["company_sid"],
                "stage" =>  "demo",
                "created_at" => getSystemDate()
            ]);
        }
    }

    public function bulkAssign(int $companyId)
    {
        // get the company employees
        $employees = $this->db->where([
            "parent_sid" => $companyId,
            "active" => 1,
            "is_executive_admin" => 0,
            "terminated_status" => 0
        ])

            ->get("users")
            ->result_array();

        $employerId = getCompanyAdminSid($companyId);
        foreach ($employees as $v0) {
            // assign direct deposit
            $this->assignDDI([
                "userSid" => $v0["sid"],
                "userType" => "employee",
                "company_sid" => $companyId,
                "documentType" => "direct_deposit",
                "employer_sid" => $employerId,
                "sid" => $this->getDDId($v0["sid"]),
                "note" => "",
                "isRequired" => 1
            ]);

            // assign w4
            $this->assignW4([
                "userSid" => $v0["sid"],
                "userType" => "employee",
                "company_sid" => $companyId,
            ]);

            // assign state form
            $this->assignStateForm([
                "userSid" => $v0["sid"],
                "userType" => "employee",
                "company_sid" => $companyId,
            ]);
        }
    }

    private function assignDDI($post)
    {
        $this->load->model("hr_documents_management_model");
        //
        $insertId = $this->hr_documents_management_model->assignGeneralDocument(
            $post['userSid'],
            $post['userType'],
            $post["company_sid"],
            $post['documentType'],
            $post["employer_sid"],
            $post['sid'],
            $post['note'],
            $post['isRequired']
        );
        //
        if (!$insertId) {
            return;
        }
        $company_sid = $post["company_sid"];
        $company_name = getCompanyName($company_sid, "employee");
        //
        $hf = message_header_footer_domain($company_sid, $company_name);
        // Send Email and SMS
        $replacement_array = array();
        //
        $userInfoE = $this->hr_documents_management_model->getUserData(
            $post['userSid'],
            $post['userType'],
            $company_sid
        );
        //
        if ($post['userType'] == 'employee') {
            //
            $replacement_array['contact-name'] = ucwords($userInfoE['first_name'] . ' ' . $userInfoE['last_name']);
            $replacement_array['company_name'] = ucwords($company_name);
            $replacement_array['username'] = $replacement_array['contact-name'];
            $replacement_array['firstname'] = $replacement_array['first_name'] = $userInfoE['first_name'];
            $replacement_array['lastname'] = $replacement_array['last_name'] = $userInfoE['last_name'];
            $replacement_array['baseurl'] = base_url();
            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
            //
            $user_extra_info = array();
            $user_extra_info['user_sid'] = $post['userSid'];
            $user_extra_info['user_type'] = $post['userType'];
            //
            $this->load->model('Hr_documents_management_model', 'HRDMM');

            //
            if ($this->hr_documents_management_model->doSendEmail($post['userSid'], $post['userType'], "HREMS13")) {
                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $userInfoE['email'], $replacement_array, $hf, 1, $user_extra_info);
            }
        }

        return true;
    }

    private function getDDId($userId)
    {
        $result = $this->db->select("sid")
            ->where([
                "user_sid" => $userId,
                "user_type" => "employee"
            ])
            ->get("documents_assigned_general")
            ->row_array();

        return $result ? $result["sid"] : 0;
    }

    private function assignW4($post)
    {
        $this->load->model("hr_documents_management_model");
        $user_sid = $post["userSid"];
        $user_type = $post["userType"];
        $company_sid = $post["company_sid"];
        $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
        //
        if (empty($w4_form_history)) {
            $w4_data_to_insert = array();
            $w4_data_to_insert['employer_sid'] = $user_sid;
            $w4_data_to_insert['company_sid'] = $company_sid;
            $w4_data_to_insert['user_type'] = $user_type;
            $w4_data_to_insert['sent_status'] = 1;
            $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
            $w4_data_to_insert['status'] = 1;
            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
        } else {
            $w4_data_to_update                                          = array();
            $w4_data_to_update['sent_date']                             = date('Y-m-d H:i:s');
            $w4_data_to_update['status']                                = 1;
            $w4_data_to_update['signature_timestamp']                   = NULL;
            $w4_data_to_update['signature_email_address']               = NULL;
            $w4_data_to_update['signature_bas64_image']                 = NULL;
            $w4_data_to_update['init_signature_bas64_image']            = NULL;
            $w4_data_to_update['ip_address']                            = NULL;
            $w4_data_to_update['user_agent']                            = NULL;
            $w4_data_to_update['uploaded_file']                         = NULL;
            $w4_data_to_update['uploaded_by_sid']                       = 0;
            $w4_data_to_update['user_consent']                          = 0;

            $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_update);
        }

        //
        $w4_sid = getVerificationDocumentSid($user_sid, $user_type, 'w4');
        keepTrackVerificationDocument($user_sid, "employee", 'assign', $w4_sid, 'w4', 'Document Center');
    }

    private function assignStateForm($post)
    {
        $this->load->model("hr_documents_management_model");
        $userId = $post["userSid"];
        $userType = $post["userType"];
        $company_sid = $post["company_sid"];
        // assign the form
        $insertArray = [];
        $insertArray["company_sid"] = $company_sid;
        $insertArray["state_form_sid"] = 1;
        $insertArray["user_sid"] = $userId;
        $insertArray["user_type"] = $userType;
        $insertArray["fields_json"] = json_encode([]);
        $insertArray["status"] = 1;
        $insertArray["user_consent"] = 0;
        $insertArray["user_consent_at"] = null;
        $insertArray["created_at"] = $insertArray["updated_at"]
            = getSystemDate();
        //
        $this->db->insert("portal_state_form", $insertArray);
    }

    public function scormFix () {
        $this->load->view('Testing');
    }
}
