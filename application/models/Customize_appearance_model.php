<?php class customize_appearance_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_theme($theme_id)
    {
        $this->db->where('sid', $theme_id);
        $result = $this->db->get('portal_themes')->result_array();

        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    function theme1_update_without_image($hf_bgcolor, $title_color, $f_bgcolor, $sid, $font_color, $heading_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'hf_bgcolor' => $hf_bgcolor,
            'title_color' => $title_color,
            'theme_image' => 'theme_1_img_preview.jpg',
            'f_bgcolor' => $f_bgcolor,
            'font_color' => $font_color,
            'heading_color' => $heading_color,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $this->db->where('sid', $sid);

        $data=sc_remove($data);
        $this->db->update('portal_themes', $data);
    }

    function theme1_update_with_image($hf_bgcolor, $title_color, $f_bgcolor, $pictures, $sid, $font_color, $heading_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'hf_bgcolor' => $hf_bgcolor,
            'title_color' => $title_color,
            'f_bgcolor' => $f_bgcolor,
            'theme_image' => 'theme_1_img_preview.jpg',
            'pictures' => $pictures,
            'font_color' => $font_color,
            'heading_color' => $heading_color,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_themes', $data);
    }

    function theme2_update_with_image($body_bgcolor, $hf_bgcolor, $pictures, $sid, $font_color, $heading_color, $title_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'body_bgcolor' => $body_bgcolor,
            'hf_bgcolor' => $hf_bgcolor,
            'font_color' => $font_color,
            'heading_color' => $heading_color,
            'theme_image' => 'theme_2_img_preview.jpg',
            'pictures' => $pictures,
            'title_color' => $title_color,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_themes', $data);
    }

    function theme2_update_without_image($body_bgcolor, $hf_bgcolor, $sid, $font_color, $heading_color, $title_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'body_bgcolor' => $body_bgcolor,
            'hf_bgcolor' => $hf_bgcolor,
            'font_color' => $font_color,
            'heading_color' => $heading_color,
            'theme_image' => 'theme_2_img_preview.jpg',
            'title_color' => $title_color,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_themes', $data);
    }

    function theme3_update_with_image($body_bgcolor, $pictures, $sid, $font_color, $hf_bgcolor, $title_color, $heading_color, $f_bgcolor, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'body_bgcolor' => $body_bgcolor,
            'pictures' => $pictures,
            'font_color' => $font_color,
            'title_color' => $title_color,
            'theme_image' => 'theme_3_img_preview.jpg',
            'heading_color' => $heading_color,
            'hf_bgcolor' => $hf_bgcolor,
            'f_bgcolor' => $f_bgcolor,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_themes', $data);
    }

    function theme3_update_without_image($body_bgcolor, $sid, $font_color, $hf_bgcolor, $title_color, $heading_color, $f_bgcolor, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url)
    {
        $data = array(
            'body_bgcolor' => $body_bgcolor,
            'font_color' => $font_color,
            'title_color' => $title_color,
            'theme_image' => 'theme_3_img_preview.jpg',
            'heading_color' => $heading_color,
            'hf_bgcolor' => $hf_bgcolor,
            'f_bgcolor' => $f_bgcolor,
            'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
            'job_fair_homepage_page_url' => $job_fair_homepage_page_url
        );

        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_themes', $data);
    }

    public $tableName = 'portal_themes_meta_data'; // Private Var

    function fGetPaidThemeByName($employer_id, $theme_name)
    {
        $this->db->where('user_sid', $employer_id);
        $this->db->where('theme_name', $theme_name);
        $result = $this->db->get('portal_themes')->result_array();

        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    /**
     * Insert Theme Meta Data
     * @param $companyId CompanyId
     * @param $themeName ThemeId
     * @param $pageName PageName
     * @param $metaKey MetaKey
     * @param $metaValue MixedArray
     */

    function fInsertThemeMeta($companyId, $themeName, $pageName, $metaKey, $metaValue)
    {
        $data = array(
            'theme_name' => $themeName,
            'page_name' => $pageName,
            'company_id' => $companyId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue
        );

        $data=sc_remove($data);


        $this->db->insert($this->tableName, $data);
    }

    /**
     * Update Theme Meta Data
     * @param $companyId sid of the Current Company
     * @param $themeName Theme Id for which Meta Data is being stored
     * @param $pageName Name of the Page for which meta information is being saved.
     * @param $metaKey Meta Key to be used for identifying Data
     * @param $metaValue Mixed Orginal data to be stored.
     */

    function fUpdateThemeMeta($companyId, $themeName, $pageName, $metaKey, $metaValue)
    {
        $this->db->where('theme_name', $themeName);
        $this->db->where('page_name', $pageName);
        $this->db->where('company_id', $companyId);
        $this->db->where('meta_key', $metaKey);

        $metacolum = unserialize($metaValue);

        $data =  array(
            'meta_value' => $metaValue
        );

        $data=sc_remove($data);

        $this->db->update($this->tableName, $data);
        //
        if (isset($metacolum['enable_header_overlay'])) {

            $data_2 =  array(
                'header_video_overlay' => $metacolum['enable_header_overlay']
            );

            $this->db->where('user_sid', $companyId);
            $this->db->update('portal_employer', $data_2);
        }
    }

    /**
     * Get Theme Meta Data
     * @param $companyId sid of the Current Company
     * @param $themeName Theme Id for which Meta Data is being stored
     * @param $pageName Name of the Page for which meta information is being saved.
     * @param $metaKey Meta Key to be used for identifying Data
     * @return mixed Retrieve a single record.
     */

    function fRetrieveThemeMeta($companyId, $themeName, $pageName, $metaKey)
    {
        $this->db->where('theme_name', $themeName);
        $this->db->where('page_name', $pageName);
        $this->db->where('company_id', $companyId);
        $this->db->where('meta_key', $metaKey);
        $Return = $this->db->get($this->tableName, 1)->result_array();

        if (!empty($Return)) {
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

    function fCheckIfMetaExists($companyId, $themeName, $pageName, $metaKey)
    {
        //$data = $this->fRetrieveThemeMeta($companyId, $themeName,$pageName,$metaKey);        
        $this->db->where('theme_name', $themeName);
        $this->db->where('page_name', $pageName);
        $this->db->where('company_id', $companyId);
        $this->db->where('meta_key', $metaKey);
        $this->db->from($this->tableName);
        $data = $this->db->count_all_results();

        if ($data > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $companyId
     * @param $themeName
     * @param $pageName
     * @param $metaKey
     * @param $metaValue
     */

    function fSaveThemeMetaData($companyId, $themeName, $pageName, $metaKey, $metaValue)
    {

        $valueToSave = serialize($metaValue);

        if ($this->fCheckIfMetaExists($companyId, $themeName, $pageName, $metaKey)) {
            $this->fUpdateThemeMeta($companyId, $themeName, $pageName, $metaKey, $valueToSave);
        } else {
            $this->fInsertThemeMeta($companyId, $themeName, $pageName, $metaKey, $valueToSave);
        }
        $valueToSave=sc_remove($valueToSave);

        $portal_themes['theme_name'] = $themeName;
        $portal_themes['page_name'] = $pageName;
        $portal_themes['company_id'] = $companyId;
        $portal_themes['meta_key'] = $metaKey;
        $portal_themes['meta_value'] = $valueToSave;
        $themes_data['portal_themes_meta_data'] = $portal_themes;
        send_settings_to_remarket(REMARKET_PORTAL_SYNC_THEMES_URL, $themes_data);
    }

    /**
     * @param $companyId
     * @param $themeName
     * @param $pageName
     * @param $metaKey
     * @return mixed|string
     */

    function fGetThemeMetaData($companyId, $themeName, $pageName, $metaKey)
    {
        $myReturn = array();

        if ($this->fCheckIfMetaExists($companyId, $themeName, $pageName, $metaKey)) {
            $data = $this->fRetrieveThemeMeta($companyId, $themeName, $pageName, $metaKey);
            $myReturn = unserialize($data['meta_value']);
        }

        return $myReturn;
    }

    /**
     * @param $companyId
     * @param $themeName
     * @param $pageName
     * @param $metaKey
     */

    function fDeleteThemeMetaData($companyId, $themeName, $pageName, $metaKey)
    {
        $this->db->where('theme_name', $themeName);
        $this->db->where('page_name', $pageName);
        $this->db->where('company_id', $companyId);
        $this->db->where('meta_key', $metaKey);
        $this->db->delete($this->tableName);
    }

    /**
     * get google fonts list
     */

    function get_google_fonts()
    {
        $this->db->select('sid,font_family,font_url');
        $this->db->where('status', 1);
        $result = $this->db->get('google_fonts')->result_array();
        return $result;
    }

    /**
     * get google fonts list
     */

    function get_web_fonts()
    {
        $this->db->select('*');
        $this->db->where('status', 1);
        $result = $this->db->get('web_fonts')->result_array();
        return $result;
    }

    function update_font_configurations($sid, $data)
    {

        $data=sc_remove($data);

        $this->db->where('user_sid', $sid);
        $this->db->where('theme_name', 'theme-4');
        $this->db->update('portal_themes', $data);
        $portal_themes = $data;
        $portal_themes['user_sid'] = $sid;
        $themes_data['portal_themes'] = $portal_themes;
        send_settings_to_remarket(REMARKET_PORTAL_SYNC_THEMES_URL, $themes_data);
    }

    function get_additional_sections($company_id)
    {
        $this->db->where('company_sid', $company_id);
        $result = $this->db->get('portal_theme4_additional_sections')->result_array();
        return $result;
    }

    function update_additional_sections($sid, $data)
    {
        $data=sc_remove($data);

        $this->db->where('sid', $sid);
        $this->db->update('portal_theme4_additional_sections', $data);
    }

    function add_additional_content_boxes($data)
    {
        $this->db->insert('portal_theme4_additional_sections', $data);
        return $this->db->insert_id();
    }
}
