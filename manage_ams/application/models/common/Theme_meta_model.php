<?php

class theme_meta_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Private Var
    public $tableName = 'portal_themes_meta_data';

    function fGetPaidThemeByName($employer_id, $theme_name){
        $this->db->where('user_sid', $employer_id);
        $this->db->where('theme_name', $theme_name);
        $result = $this->db->get('portal_themes')->result_array();
        return $result[0];
    }

    /**
     * Get Theme Meta Data
     * @param $companyId sid of the Current Company
     * @param $themeName Theme Id for which Meta Data is being stored
     * @param $pageName Name of the Page for which meta information is being saved.
     * @param $metaKey Meta Key to be used for identifying Data
     * @return mixed Retrieve a single record.
     */
    function fRetrieveThemeMeta($companyId, $themeName, $pageName, $metaKey){
        $this->db->where('theme_name', $themeName);
        $this->db->where('page_name', $pageName);
        $this->db->where('company_id', $companyId);
        $this->db->where('meta_key', $metaKey);
        $Return = $this->db->get($this->tableName, 1)->result_array();

        if(!empty($Return)){
            return $Return[0];
        } else {
            return array();
        }
    }


    /**
     * Check If Meta Data Already Exists
     * @param $companyId
     * @param $themeName
     * @param $pageName
     * @param $metaKey
     * @return bool
     */
    function fCheckIfMetaExists($companyId, $themeName, $pageName, $metaKey){
        $data = $this->fRetrieveThemeMeta($companyId, $themeName,$pageName,$metaKey);

        if(sizeof($data) > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Theme Meta Data
     * @param $companyId
     * @param $themeName
     * @param $pageName
     * @param $metaKey
     * @return mixed|string
     */
    function fGetThemeMetaData($companyId, $themeName, $pageName, $metaKey){
        $myReturn = array();
        $data = $this->fRetrieveThemeMeta($companyId, $themeName, $pageName, $metaKey);
        
        if(sizeof($data)>0){
            $myReturn = unserialize($data['meta_value']);
        }
        
        return $myReturn;
    }

    /**
     * Get Theme Meta Data
     * @param $companyId
     * @return mixed|string
     */
    function getAdditionalSections($companyId){
        $this->db->where('company_sid',$companyId);
        $this->db->where('status',1);
        $data = $this->db->get('portal_theme4_additional_sections')->result_array();
        return $data;
    }
}