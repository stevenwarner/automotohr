<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Company model
 * 
 * Holds all the company interactions
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar@automotohr.com>
 * @version 1.0 
 * 
 */
class Company_model extends CI_Model {

    /**
     * Entry point
     */
    function __construct() {
        // Inherit parent class properties and methods
        parent::__construct();
    }


    /**
     * Get all active companies
     * 
     * @param array  $columns
     * @param array  $where
     * @param string $method
     * @param array  $orderBy
     * @return array
     */
    public function getAllCompanies(
        $columns = ['*'],
        $where = [],
        $method = 'result_array',
        $orderBy = ['CompanyName', 'ASC']
    ){
        // Set the default where clause
        if(!$where){
            $where['parent_sid'] = 0;
            $where['active'] = 
            $where['is_paid'] = 1;
            $where['career_page_type'] = 'standard_career_site';
        }
        //
        $this->db
        ->select($columns)
        ->where($where)
        ->from('users')
        ->order_by($orderBy[0], $orderBy[1]);
        // Execute the query
        $obj = $this->db->get();
        // Get he data
        $results = $obj->$method();
        // Free up the memory
        $obj = $obj->free_result();
        //
        return $results ?: [];
    }


    /**
     * Get company groups with documents
     * 
     * @param array $where
     * @return array
     */
    public function getCompanyGroupsWithDocuments(
        $where,
        $groupColumns = ['*'],
        $documentColumns = ['*']
    ){
        //
        $companyGroups = $this->getCompanyGroups($where, $groupColumns);
        //
        if(!$companyGroups){
            return [];
        }
        //
        return $this->getCompanyGroupDocumentsByGroupIds(
            $companyGroups,
            $documentColumns
        );
    }

    /**
     * Get company groups
     * 
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return array
     */
    public function getCompanyGroups(
        $where,
        $columns = ['*'],
        $orderBy = ['name', 'ASC']
    ){
        //
        $this->db
        ->select($columns)
        ->where($where)
        ->order_by($orderBy[0], $orderBy[1])
        ->from('documents_group_management');
        //
        $obj = $this->db->get();
        //
        $results = $obj->result_array();
        //
        $obj = $obj->free_result();
        //
        return $results;
    }

    /**
     * Get company documents by groups
     * 
     * @param array $groups
     * @param array $columns
     * @return array
     */
    public function getCompanyGroupDocumentsByGroupIds(
        $groups,
        $columns = ['*']
    ){
        // Make document array
        $groupsArray = [];
        //
        foreach($groups as $group){
            //
            $groupsArray[$group['sid']] = $group;
            $groupsArray[$group['sid']]['total'] = 0;
            $groupsArray[$group['sid']]['documents'] = [];
        }
        // Set the default group array
        // Get the document ids
        $this->db
        ->select($columns)
        ->join('documents_management', 'documents_management.sid = documents_2_group.document_sid', 'inner')
        ->where_in('documents_2_group.group_sid', array_keys($groupsArray))
        ->where([
            'archive' => 0,
            'is_specific' => 0
        ]);
        //
        $obj = $this->db->get('documents_2_group');
        //
        $results = $obj->result_array();
        //
        $obj = $obj->free_result();
        //
        if(empty($results)){
            return $groupsArray;
        }
        //
        foreach($results as $document){
            //
            $groupsArray[$document['group_sid']]['total']++;
            //
            $groupsArray[$document['group_sid']]['documents'][] = $document;
        }
        //
        return $groupsArray;
    }

    /**
     * Migrate groups with documents
     * 
     * @param int   $fromCompanyId
     * @param int   $toCompanyId
     * @param array $groupIds
     * 
     * @return array
     */
    public function migrateGroupsWithDocumentsByIds(
        int $fromCompanyId,
        int $toCompanyId,
        array $groupIds
    ){
        //
        $groups = $this->getCompanyGroupsWithDocuments([
            'company_sid'=> $fromCompanyId,
            'status' => 1,
            'sid IN('.(implode(',', $groupIds)).')' => null
        ], [
            'sid',
            'name',
            'description',
            'sort_order',
            'w4',
            'w9',
            'i9',
            'eeoc',
            'dependents',
            'direct_deposit',
            'drivers_license',
            'emergency_contacts',
            'occupational_license'
        ], [
            'documents_2_group.group_sid', 
            'documents_management.document_title',
            'documents_management.document_description',
            'documents_management.document_type',
            'documents_management.uploaded_document_original_name',
            'documents_management.uploaded_document_extension',
            'documents_management.uploaded_document_s3_name',
            'documents_management.unique_key',
            'documents_management.onboarding',
            'documents_management.action_required',
            'documents_management.acknowledgment_required',
            'documents_management.download_required',
            'documents_management.signature_required',
            'documents_management.to_all_employees',
            'documents_management.video_required',
            'documents_management.video_source',
            'documents_management.video_url',
            'documents_management.sort_order',
            'documents_management.automatic_assign_in',
            'documents_management.automatic_assign_type',
            'documents_management.visible_to_payroll',
            'documents_management.is_available_for_na',
            'documents_management.assign_date',
            'documents_management.assign_time',
            'documents_management.is_required',
            'documents_management.isdoctolibrary',
            'documents_management.visible_to_document_center',
            'documents_management.is_confidential'
        ]);
        //
        if(empty($groups)){
            return [];
        }
        //
        $ipAddress = getUserIP();
        $createdAt = date('Y-m-d H:i:s', strtotime('now'));
        //
        foreach($groups as $group){
            // Check if group already exists
            $cg = $this->checkOrGetData( 'documents_group_management', [
                'name' => $group['name'],
                'company_sid' => $toCompanyId,
                'status' => 1
            ], ['sid'], 'row_array');
            // Insert the group if not found
            if(empty($cg)){
                // Lets add a group
                $companyGroupId = $this->addData('documents_group_management', [
                    'name' => $group['name'],
                    'description' => $group['description'],
                    'company_sid' => $toCompanyId,
                    'status' => 1,
                    'sort_order' => $group['sort_order'],
                    'w4' => $group['w4'],
                    'w9' => $group['w9'],
                    'i9' => $group['i9'],
                    'eeoc' => $group['eeoc'],
                    'dependents' => $group['dependents'],
                    'direct_deposit' => $group['direct_deposit'],
                    'drivers_license' => $group['drivers_license'],
                    'emergency_contacts' => $group['emergency_contacts'],
                    'occupational_license' => $group['occupational_license'],
                    'created_date' => $createdAt,
                    'ip_address' => $ipAddress
                ]);
            } else{
                // Extract the group id
                $companyGroupId = $cg['sid'];
            }
            // Lets handle the documents
            if(empty($group['documents'])){
                // If there are no documents,no need to execute rest of code
                // move to next iteration
                continue;
            }
            // Iterate through the documents
            foreach($group['documents'] as $document){
                // Lets check if document already exists
                $cd = $this->checkOrGetData('documents_management', [
                    'archive' => 0, // make sure document is not archived
                    'company_sid' => $toCompanyId, // belongs to the destination company
                    'is_specific' => 0, // document is created for company not employee/applicant
                    'document_title' => $document['document_title'] // match the name as well
                ], ['sid'], 'row_array');
                //
                if(empty($cd)){
                    //
                    unset($document['group_sid']);
                    //
                    $document['company_sid'] = $toCompanyId;
                    $document['employer_sid'] = 0;
                    $document['date_created'] = $createdAt;
                    $document['copied_doc_sid'] =
                    $document['archive'] =
                    $document['has_approval_flow'] = 
                    $document['is_specific'] = 0;
                    $document['managers_list'] = 
                    $document['allowed_employees'] = 
                    $document['allowed_departments'] = 
                    $document['allowed_teams'] = 
                    $document['assigned_employee_list'] = 
                    $document['document_approval_employees'] = 
                    $document['document_approval_note'] = 
                    $document['confidential_employees'] = 
                    $document['is_specific_type'] = null;
                    // Insert data
                    $companyDocumentId = $this->addData(
                        'documents_management', 
                        $document
                    );
                } else{
                    $companyDocumentId = $cd['sid'];
                }
                //
                if(!$this->checkOrGetData(
                    'documents_2_group', [
                        'group_sid' => $companyGroupId,
                        'document_sid' => $companyDocumentId
                    ],
                    ['sid'], 'count_all_results'
                )){
                    //
                    $this->addData(
                        'documents_2_group', [
                            'group_sid' => $companyGroupId,
                            'document_sid' => $companyDocumentId
                        ]
                    );
                }
            }
        }
        //
        return true;
    }

    /**
     * Check or get company data
     * 
     * @param string $table
     * @param array  $where
     * @param array  $columns
     * @param string $method
     * 
     * @return array|int
     */
    public function checkOrGetData(
        string $table,
        array $where,
        array $columns = ['*'],
        string $method = 'result_array'
    ){
        //
        $this->db
        ->select($columns)
        ->where($where)
        ->from($table);
        //
        if($method == 'count_all_results'){
            return $this->db->$method();
        }
        //
        $obj = $this->db->get();
        //
        $result = $obj->$method();
        //
        $obj = $obj->free_result();
        //
        return $result;
    }

    /**
     * Add company data to table
     *
     * @param string $table
     * @param array $insertArray
     *
     * @return int
     */
    public function addData(
        string $table,
        array $insertArray
    ){
        //
        $this->db->insert($table, $insertArray);
        //
        return $this->db->insert_id();
    }

    /**
     * Update data into table
     *
     * @param string $table
     * @param array  $where
     * @param array $updateArray
     *
     * 
     */
    public function updateData(
        string $table,
        array $where,
        array $updateArray
    ){
        //
        $this->db->where($where);
        $this->db->update($table, $updateArray);
        //
    }

    /**
     * Delete data from table
     *
     * @param string $table
     * @param array  $where
     *
     * 
     */
    public function deleteRow(
        string $table,
        array $where
    ){
        //
        $this->db->where($where);
        $this->db->delete($table);
        //
    }

}
