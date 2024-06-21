<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assign_bulk_documents_model extends CI_Model
{
    //
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        exit(0);
    }

    function get_all_documents_category($company_sid, $status = NULL, $sort_order = NULL)
    {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->or_where('sid', PP_CATEGORY_SID);

        if ($status != NULL) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('sort_order', 'asc');

        $records_obj = $this->db->get('documents_category_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    /**
     * get company accounts  
     * for calendars
     * 
     * @param $companyId Integer
     *
     * @return Array|Bool
     */
    function fetchEmployeesByCompanyId($companyId)
    {
        $result = $this->db
            ->select('sid as id')
            ->select('
            concat(first_name," ",last_name) as fullname,
            first_name,
            last_name,
            pay_plan_flag,
            access_level,
            access_level_plus,
            job_title
        ')
            ->select('case when is_executive_admin = 1 then "Executive Admin" else access_level end as employee_type', false)
            ->where('parent_sid', $companyId)
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->order_by('fullname', 'ASC')
            ->get();
        // fetch result
        $result_arr = $result->result_array();
        // free result from memory 
        // and flush variable data
        $result = $result->free_result();
        // return output
        return $result_arr;
    }

    /**
     * Search applicant in database
     *  
     * @param $companyId Integer
     * @param $query String 
     *  
     * @return Array
     */
    function fetchApplicantByQuery($companyId, $query)
    {
        //
        $tmp = explode("_", $query);
        //
        $this->db
            ->select('portal_job_applications.sid as id')
            ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " (",portal_job_applications.email,")") as value ')
            ->where('portal_applicant_jobs_list.company_sid', $companyId)
            ->where('portal_applicant_jobs_list.archived', 0)
            ->where('portal_job_applications.hired_status', 0)
            ->order_by('value', 'DESC')
            ->group_by('id')
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');

        $this->db
            ->group_start();

        if ($tmp[1]) {
            $this->db
                ->like('concat(portal_job_applications.first_name, " ", portal_job_applications.last_name)', str_replace('_', ' ', $query));
        } else {
            $this->db
                ->where('portal_job_applications.first_name', $query)
                ->or_where('portal_job_applications.last_name', $query);
        }

        $this
            ->db
            ->or_like('portal_job_applications.email', $query)
            ->group_end();

        $result = $this->db->limit(10)
            ->get('portal_applicant_jobs_list');


        $result_arr = $result->result_array();
        $result = $result->free_result();

        return $result_arr;
    }


    /**
     * Search applicant in database
     *  
     * @param $companyId Integer
     * @param $query String 
     *  
     * @return Array
     */
    function fetcApplicantsByCompanyId($companyId)
    {
        $result = $this->db
            ->select('portal_job_applications.sid as id')
            ->select('concat( portal_job_applications.first_name, " ", portal_job_applications.last_name, " (",portal_job_applications.email,")") as value ')
            ->where('portal_applicant_jobs_list.company_sid', $companyId)
            ->where('portal_applicant_jobs_list.archived', 0)
            ->where('portal_job_applications.hired_status', 0)
            ->order_by('value', 'DESC')
            ->group_by('id')
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->get('portal_applicant_jobs_list');


        $result_arr = $result->result_array();
        $result = $result->free_result();

        return $result_arr;
    }


    function insertDocumentsAssignmentRecord($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }

    function check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function check_offer_letter_moved($document_sid, $document_type)
    {
        $this->db->select('*');;
        $this->db->where('doc_sid', $document_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    function insert_documents_assignment_record_history($data_to_insert)
    {
        $this->db->insert('documents_assigned_history', $data_to_insert);
    }

    function disable_all_previous_letter($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_type', $document_type);
        $this->db->set('status', 0);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function get_applicant_information($company_sid, $applicant_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('pictures');
        $this->db->select('phone_number as phone');
        $this->db->select('verification_key');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber as phone');
        $this->db->select('verification_key');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function add_update_categories_2_documents($document_sid, $categories, $document_type)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('document_type', $document_type);
        $this->db->delete('documents_2_category');
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $this->db->insert('documents_2_category', ['document_sid' => $document_sid, 'category_sid' => $category, 'document_type' => $document_type]);
            }
        }
    }


    //
    function insertSecureDocument($data_to_insert)
    {
        $this->db->insert('company_secure_documents', $data_to_insert);
    }

    //
    function updateSecureDocument($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('company_secure_documents', $data_to_update);
    }


    function getSecureDocuments($company_sid, $documentTitle)
    {
        $this->db->select('*');

        if ($documentTitle != '') {
            $this->db->like('document_title', $documentTitle);
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'Desc');
        $record_obj = $this->db->get('company_secure_documents');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function getSecureDocumentById($documentId)
    {
        //
        $this->db->select('company_sid, document_title, document_s3_name, created_by');
        $this->db->where('sid', $documentId);
        $record_obj = $this->db->get('company_secure_documents');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function deleteSecureDocument($documentId)
    {
        $this->db->where('sid', $documentId);
        $this->db->delete('company_secure_documents');
    }

    function getSpecificSecureDocuments($documentIds)
    {
        $this->db->select('*');
        $this->db->where_in('sid', $documentIds);
        $records_obj = $this->db->get('company_secure_documents');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }
}
