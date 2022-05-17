<?php
/**
 * Document center model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar.ahmed@egenienext.com>
 * @version 1.0
 */
class DocumentCenter_model extends CI_Model{

    /**
     * Holds the name of the tables
     */
    private $tables;
    
    /**
     * Constructor
     * @method __construct
     */
    public function __construct(){
        //
        parent::__construct();
        //
        $this->tables['DM'] = 'documents_management';
        $this->tables['DMH'] = 'documents_management_history';
        $this->tables['DA'] = 'documents_assigned';
        $this->tables['DAH'] = 'documents_assigned_history';
    }

    /**
     * Assigns a document
     * 
     * Cases:
     * - When a document is assigned and data is not provided
     * - When a document is assigned and data is provided
     * 
     * @param number $documentId
     * @param number $userId
     * @param string $userType
     * @param number $assignerId
     * @param number $companyId
     * @param array  $data
     * 
     * @return array
     */
    public function assignDocument(
        $documentId,
        $userId,
        $userType,
        $assignerId,
        $companyId,
        $data = []
    ){
        // Set the return name
        $returnArray = [];
        //
        $document = [];
        // Means a new document is directly assigned without modified
        if(!$data){
            $document = $this->GetDocumentData(
                $this->tables['DM'], [
                    'document_title',
                    'document_description',
                    'document_type',
                    'uploaded_document_original_name',
                    'uploaded_document_extension',
                    'uploaded_document_s3_name',
                    // Part of document library
                    'isdoctolibrary',
                    // Document conditions
                    'acknowledgment_required',
                    'download_required',
                    'signature_required',
                    'is_required',
                    // Document approver employees
                    'document_approval_employees',
                    'document_approval_note',
                    // Authorized signers
                    'managers_list',
                    // Visibility
                    'visible_to_payroll',
                    'allowed_employees',
                    'allowed_departments',
                    'allowed_teams',
                ], [
                    'sid' => $documentId,
                    'company_sid' => $companyId
                ]
            );
        } else{
            // TODO
            // reset the keys and forms an array
        }
        // Check if the document is already assigned
        $assignedDocumentId = $this->handleAlreadyAssignedDocument(
            $documentId,
            $userId,
            $userType,
            $companyId
        );
        //
        $ia = [];
        $ia['assigned_date'] = GetDate();
        $ia['assigned_by'] = $assignerId;
        //
        if($assignedDocumentId){
            // Update the document
        } else{
            // Insert the document
            $ia['company_sid'] = $companyId;
            $ia['user_sid'] = $userId;
            $ia['user_type'] = $userType;
            $ia['document_sid'] = $documentId;
        }
        //
        _e($ia, true);
        _e($assignedDocumentId, true);
        _e($document, true, true);
    }

    /**
     * Get the document
     * 
     * @param string $tableName
     * @param array  $columns
     * @param array  $whereArray
     * @param string $recordType
     *               - single_column [Returns a single columns]
     *               - single [Returns a single row - default]
     *               - multiple [Returns multiple records]
     *               - count [Returns count]
     * @param array  $orderBy
     *               - [column, type]
     * 
     * @return array
     */
    public function GetDocumentData(
        $tableName,
        $columns = ['*'],
        $whereArray = [],
        $recordType = 'single',
        $orderBy = ['sid', 'DESC']
    ){
        //
        $this->db->select($columns);
        //
        if($whereArray){
            $this->db->where($whereArray);
        }
        //
        if($recordType != 'multiple'){
            $this->db->limit(1);
        }
        //
        if($orderBy){
            $this->db->order_by($orderBy[0], $orderBy[1]);
        }
        //
        if($recordType == 'count'){
            return $this->db->count_all_results($tableName);
        }
        //
        $q = $this->db->get($tableName);
        //
        if($recordType == 'single_column'){
            //
            $qs = $q->row_array();
            //
            return isset($qs[$columns[0]]) ? $qs[$columns[0]] : '';
        }
        //
        $func = $recordType === 'single' ? 'row_array' : 'result_array';
        //
        return $q->$func() ? $q->$func() : [];
    }

    /**
     * Handles assigned document logic
     * 
     * @method GetDocumentData
     * 
     * @param string $documentId
     * @param string $userId
     * @param string $userType
     * @param string $companyId
     * 
     * @return number
     */
    public function handleAlreadyAssignedDocument(
        $documentId,
        $userId,
        $userType,
        $companyId
    ){
        // Set where array
        $whereArray = [
            'document_sid' => $documentId,
            'user_sid' => $userId,
            'user_type' => $userType,
            'company_sid' => $companyId
        ];
        // Check if the document was already assigned
        if(!$this->GetDocumentData(
            $this->tables['DA'],
            ['sid'],
            $whereArray,
            'count'
        )){
            return 0;
        }
        // Get the assigned document
        $assignedDocument = $this->GetDocumentData(
            $this->tables['DA'],
            ['sid'],
            $whereArray,
            'single'
        );
        // TODO
        // Move document to history
        // Flush current document
        //
        return $assignedDocument;
    }
}