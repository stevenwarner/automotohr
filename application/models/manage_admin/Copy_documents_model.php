<?php
class copy_documents_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getAllCompanies($active = 1) {
        $result = $this->db
        ->select('sid as company_id, CompanyName as title')
        ->where('parent_sid', 0)
        ->where('active', $active)
        ->where('is_paid', 1)
        ->where('career_page_type', 'standard_career_site')
        ->order_by('CompanyName', 'ASC')
        ->get('users');
        //
        $companies = $result->result_array();
        $result = $result->free_result();
        //
        return $companies;
    }


    function getCompanyDocuments($formpost, $limit){
        $start = $formpost['page'] == 1 ? 0 : ($formpost['page'] * $limit) - $limit;
        $this->db
        ->select('sid as document_id, document_title, document_type, archive as is_archived, "document" as type')
        ->where('company_sid', $formpost['fromCompanyId'])
        ->order_by('document_title')
        ->limit($limit, $start);

        if($formpost['type'] != 'all') $this->db->where('document_type', $formpost['type']);
        if($formpost['status'] != 'all') $this->db->where('archive', $formpost['status']);

        $result = $this->db->get('documents_management');
        //
        $documents = $result->result_array();
        $result = $result->free_result();
        //
        $returnArray = array('Documents' => $documents, 'DocumentCount' => 0);
        if($formpost['page'] == 1 && !sizeof($documents)) return $returnArray;
        if($formpost['page'] != 1) return $returnArray;
        //
        $this->db
        ->where('company_sid', $formpost['fromCompanyId'])
        ->order_by('document_title');
        if($formpost['type'] != 'all') $this->db->where('document_type', $formpost['type']);
        if($formpost['status'] != 'all') $this->db->where('archive', $formpost['status']);
        $returnArray['DocumentCount'] = $this->db->count_all_results('documents_management');
        //
        return $returnArray;
    }


    function getCompanyOfferLetters($formpost, $limit){
        $start = $formpost['page'] == 1 ? 0 : ($formpost['page'] * $limit) - $limit;
        $this->db
        ->select('sid as document_id, letter_name as document_title, letter_type as document_type, archive as is_archived, "offer_letter" as type')
        ->where('company_sid', $formpost['fromCompanyId'])
        ->order_by('letter_name')
        ->limit($limit, $start);
        //
        $result = $this->db->get('offer_letter');
        //
        $documents = $result->result_array();
        $result = $result->free_result();
        //
        $returnArray = array('Documents' => $documents, 'DocumentCount' => 0);
        if($formpost['page'] == 1 && !sizeof($documents)) return $returnArray;
        if($formpost['page'] != 1) return $returnArray;
        //
        $this->db
        ->where('company_sid', $formpost['fromCompanyId'])
        ->order_by('letter_name');
        if($formpost['type'] != 'all') $this->db->where('document_type', $formpost['type']);
        if($formpost['status'] != 'all') $this->db->where('archive', $formpost['status']);
        $returnArray['DocumentCount'] = $this->db->count_all_results('offer_letter');
        //
        return $returnArray;
    }

    function checkDocumentCopied($formpost){
        return $this->db
        ->where('from_company_sid', $formpost['fromCompanyId'])
        ->where('to_company_sid', $formpost['toCompanyId'])
        ->where('document_sid', $formpost['document']['document_id'])
        ->where('document_type', $formpost['document']['document_type'])
        ->count_all_results('copy_document_track');
    }

    function moveDocument($formpost, $id){
        // Fetch document details
        $result = $this->db
        ->select('*')
        ->where('sid', $formpost['document']['document_id'])
        ->get('documents_management');
        //
        $document = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($document)) return false;
        //
        unset(
            $document['sid'],
            $document['date_created']
        );
        $this->db->trans_begin();

        if($formpost['group'] == 1){
            $groupy = $this->moveGroup($formpost);
            if($groupy['GroupId'] == 0 && $groupy['Error']){
                $this->db->rollback();
                return false;
            }
        }

        $document['employer_sid'] = 0;
        $document['unique_key'] = generateRandomString(32);
        $document['company_sid'] = $formpost['toCompanyId'];
        if($document['document_type'] == 'uploaded'){
            // Re-upload file to AWS
            $basePath = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $document['company_sid'])).'-document/';
            $fileName = generateRandomString(3).'-'.$document['uploaded_document_s3_name'];
            $filePath = $basePath.$fileName;
            if(!file_exists($basePath)) mkdir($basePath, 0777, true);
            @file_put_contents($filePath, @file_get_contents(AWS_S3_BUCKET_URL.$document['uploaded_document_s3_name']));
            //
            downloadFileFromAWS($filePath, AWS_S3_BUCKET_URL.$document['uploaded_document_s3_name']);
            // if(!file_exists($filePath)) return false;
                // Upload file to AWS
            $this->load->library('aws_lib');
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $fileName,
                'Body' => file_get_contents($filePath),
                'ACL' => 'public-read',
                'ContentType' => getMimeType($filePath)
            ];
            if($options['Body'] == ''){
                return false;
            }

            $this->aws_lib->put_object($options);
            $document['uploaded_document_s3_name'] = $fileName;
            unlink($filePath);
        }
        $this->db->insert('documents_management', $document);
        $documentId = $this->db->insert_id();
        if(!$documentId) return false;

        // Assign document to the groups
        if(isset($groupy['GroupId']) && $groupy['GroupId'] != 0){
            $this->db->insert('documents_2_group', array(
                'group_sid' => $groupy['GroupId'],
                'document_sid' => $documentId
            ));
        }
        //
        $insertArray = array();
        $insertArray['admin_sid'] = $id;
        $insertArray['document_sid'] = $formpost['document']['document_id'];
        $insertArray['to_company_sid'] = $formpost['toCompanyId'];
        $insertArray['new_document_sid'] = $documentId;
        $insertArray['from_company_sid'] = $formpost['fromCompanyId'];
        //
        $this->db->insert('copy_document_track', $insertArray);
        $historyInsertId = $this->db->insert_id();

        if(!$historyInsertId) {
            $this->db->trans_rollback();
            return false;
        }
        else $this->db->trans_commit();
        return true;
    }

    function moveOfferLetter($formpost, $id){
        // Fetch document details
        $result = $this->db
        ->select('*')
        ->where('sid', $formpost['document']['document_id'])
        ->get('offer_letter');
        //
        $document = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($document)) return false;
        //
        unset(
            $document['sid'],
            $document['signers']
        );
        $this->db->trans_begin();

        $document['employer_sid'] = 0;
        $document['company_sid'] = $formpost['toCompanyId'];
        if($document['document_type'] == 'uploaded'){
            // Re-upload file to AWS
            $basePath = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $document['company_sid'])).'-document/';
            $fileName = generateRandomString(3).'-'.$document['uploaded_document_s3_name'];
            $filePath = $basePath.$fileName;
            if(!file_exists($basePath)) mkdir($basePath, 0777, true);
            @file_put_contents($filePath, @file_get_contents(AWS_S3_BUCKET_URL.$document['uploaded_document_s3_name']));
            //
            downloadFileFromAWS($filePath, AWS_S3_BUCKET_URL.$document['uploaded_document_s3_name']);
            // if(!file_exists($filePath)) return false;
                // Upload file to AWS
            $this->load->library('aws_lib');
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $fileName,
                'Body' => file_get_contents($filePath),
                'ACL' => 'public-read',
                'ContentType' => getMimeType($filePath)
            ];
            if($options['Body'] == ''){
                return false;
            }

            $this->aws_lib->put_object($options);
            $document['uploaded_document_s3_name'] = $fileName;
            unlink($filePath);
        }
        $this->db->insert('offer_letter', $document);
        $documentId = $this->db->insert_id();
        if(!$documentId) return false;

        //
        $insertArray = array();
        $insertArray['admin_sid'] = $id;
        $insertArray['document_sid'] = $formpost['document']['document_id'];
        $insertArray['to_company_sid'] = $formpost['toCompanyId'];
        $insertArray['new_document_sid'] = $documentId;
        $insertArray['document_type'] = 'offer_letter';
        $insertArray['from_company_sid'] = $formpost['fromCompanyId'];
        //
        $this->db->insert('copy_document_track', $insertArray);
        $historyInsertId = $this->db->insert_id();

        if(!$historyInsertId) {
            $this->db->trans_rollback();
            return false;
        }
        else $this->db->trans_commit();
        return true;
    }

    private function moveGroup($formpost){
        $returnArray = [
            'GroupId' => 0,
            'Error' => FALSE
        ];
        // Check if company has a groups
        $hasGroups = $this->db
        ->where('company_sid', $formpost['fromCompanyId'])
        ->count_all_results('documents_group_management');
            //
        if($hasGroups == 0) return $returnArray;
        // Get document group details
        $result = $this->db
        ->select('
            documents_group_management.name,
            documents_group_management.description,
            documents_group_management.status,
            documents_group_management.sort_order,
            documents_group_management.w4,
            documents_group_management.w9,
            documents_group_management.i9
        ')
        ->join('documents_group_management', 'documents_group_management.sid = documents_2_group.group_sid')
        ->where('documents_2_group.document_sid', $formpost['document']['document_id'])
        ->get('documents_2_group');
        //
        $group = $result->row_array();
        $result = $result->free_result();
                //
        if(!sizeof($group)) return $returnArray;

        $group['ip_address'] = getUserIP();
        $group['created_by_sid'] = 0;


        // Check if group exists for the company
        $result = $this->db
        ->select('sid')
        ->where('company_sid', $formpost['toCompanyId'])
        ->where('name', $group['name'])
        ->get('documents_group_management');
        //
        $records = $result->row_array();
        $result  = $result->free_result();
        // Group found
        if(sizeof($records)){
            $returnArray['GroupId'] = $records['sid'];
            return $returnArray;
        }
        $group['company_sid'] = $formpost['toCompanyId'];
        $this->db->insert('documents_group_management', $group);
        $groupId = $this->db->insert_id();
        //
        if(!$groupId){
            $returnArray['Error'] = true;
            return $returnArray;
        }
        $returnArray['GroupId'] = $groupId;
        return $returnArray;
    }
}
