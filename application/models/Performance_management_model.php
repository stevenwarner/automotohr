<?php

class Performance_management_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

     /**
     * Get company templates
     * 
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * 
     * @return Array
     */
    function getCompanyTemplates(
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        //
        $a = $this->db->get('performance_management_templates');
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        return $b;        
    }

    /**
     * Get personal templates
     * 
     * @employee Mubashir Ahmed
     * @date     02/09/2021
     * 
     * @param Integer      $companyId
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * @param Integer      $page 
     *                     Default is '0'
     * @param Integer      $limit 
     *                     Default is '100'
     * 
     * @return Array
     */
    function getPersonalTemplates(
        $companyId, 
        $columns = '*', 
        $archived = 0,
        $page = 0,
        $limit = 100
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        // For pagination
        if($page != 0){
            $inset = 0;
            $offset = 0;
            $this->db->limit($inset, $offset);
        }
        //
        $a = $this->db->get('performance_management_company_templates');
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        if($page == 1){
            return [
                'Records' => $b,
                'Count' => $this->db->where('is_archived', $archived)->count_all_results('performance_management_company_templates')
            ];
        }
        //
        return $b;        
    }


    /**
     * Get personal template questions
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer $templateId
     * @return Array
     */
    function getPersonalQuestionsById($templateId){
        return 
        $this->db
        ->select('questions')
        ->where('sid', $templateId)
        ->get('performance_management_company_templates')
        ->row_array()['questions'];
    }

    /**
     * Get default template questions
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer $templateId
     * @return Array
     */
    function getCompanyQuestionsById($templateId){
        return 
        $this->db
        ->select('questions')
        ->where('sid', $templateId)
        ->get('performance_management_templates')
        ->row_array()['questions'];
    }
    
    /**
     * Get personal template by id
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer       $templateId
     * @param  String|Array  $columns
     * @return Array
     */
    function getPersonalTemplateById($templateId, $columns = '*'){
        return 
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $templateId)
        ->get('performance_management_company_templates')
        ->row_array();
    }
    
    /**
     * Get default template by id
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer       $templateId
     * @param  String|Array  $columns
     * @return Array
     */
    function getCompanyTemplateById($templateId, $columns = '*'){
        return 
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $templateId)
        ->get('performance_management_templates')
        ->row_array();
    }
    
}
