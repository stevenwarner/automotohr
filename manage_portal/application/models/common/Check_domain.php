<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_domain extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_portal_status($server_name) {
        $data                                                                   = array();
        $data['initialized']                                                    = '1';
        $data['status']                                                         = 0;
        $data['meta_title']                                                     = '';
        $data['meta_keywords']                                                  = '';
        $data['meta_description']                                               = '';
        $data['theme_name']                                                     = 'default';
        
        $this->db->select('*');
        $this->db->from('portal_employer');
        $this->db->where('sub_domain', $server_name);
        $result                                                                 = $this->db->get()->result_array();

        if ($result) {
            $portal_employer                                                    = $result[0];
            $status                                                             = $portal_employer['status'];
            $data['status']                                                     = $status;
            
            if ($status) { // portal is active get more details
                $data['sid']                                                    = $portal_employer['sid'];
                $data['status']                                                 = 1;
                $company_sid                                                    = $portal_employer['user_sid'];
                $data['employer_id']                                            = $company_sid;
                $data['maintenance_mode']                                       = $portal_employer['maintenance_mode'];
                $data['contact_us_page']                                        = $portal_employer['contact_us_page'];
                $data['enable_company_logo']                                    = $portal_employer['enable_company_logo'];
                $data['embedded_code']                                          = $portal_employer['embedded_code'];
                $data['meta_title']                                             = $portal_employer['meta_title'];
                $data['meta_keywords']                                          = $portal_employer['meta_keywords'];
                $data['enable_facebook_footer']                                 = $portal_employer['enable_facebook_footer'];
                $data['enable_twitter_footer']                                  = $portal_employer['enable_twitter_footer'];
                $data['enable_google_footer']                                   = $portal_employer['enable_google_footer'];
                $data['enable_linkedin_footer']                                 = $portal_employer['enable_linkedin_footer'];
                $data['enable_youtube_footer']                                  = $portal_employer['enable_youtube_footer'];
                $data['enable_instagram_footer']                                = $portal_employer['enable_instagram_footer'];
                $data['enable_glassdoor_footer']                                = $portal_employer['enable_glassdoor_footer'];
                $data['is_resume_mandatory']                                    = $portal_employer['is_resume_mandatory'];
                $data['portal_type']                                            = $portal_employer['career_page_type'];
                $data['eeo_form_status']                                        = $portal_employer['eeo_form_status'];
                $data['footer_powered_by_logo']                                 = $portal_employer['footer_powered_by_logo'];
                $data['footer_logo_type']                                       = $portal_employer['footer_logo_type'];
                $data['footer_logo_text']                                       = $portal_employer['footer_logo_text'];
                $data['footer_logo_image']                                      = $portal_employer['footer_logo_image'];
                $data['employee_login_text']                                    = $portal_employer['employee_login_text'];
                $data['eeo_footer_text']                                        = $portal_employer['eeo_footer_text'];
                $data['header_video_overlay']                                   = $portal_employer['header_video_overlay'];
                $data['employee_login_text_status']                             = $portal_employer['employee_login_text_status'];
                $career_page_type                                               = $portal_employer['career_page_type'];
                $data['career_type']                                            = $career_page_type;

                if ($portal_employer['facebook_footer'] != NULL) {
                    $data['facebook_footer']                                    = $portal_employer['facebook_footer'];
                } else {
                    $data['facebook_footer']                                    = 'javascript:void(0);';
                }

                if ($portal_employer['twitter_footer'] != NULL) {
                    $data['twitter_footer']                                     = $portal_employer['twitter_footer'];
                } else {
                    $data['twitter_footer']                                     = 'javascript:void(0);';
                }

                if ($portal_employer['google_footer'] != NULL) {
                    $data['google_footer']                                      = $portal_employer['google_footer'];
                } else {
                    $data['google_footer']                                      = 'javascript:void(0);';
                }

                if ($portal_employer['linkedin_footer'] != NULL) {
                    $data['linkedin_footer']                                    = $portal_employer['linkedin_footer'];
                } else {
                    $data['linkedin_footer']                                    = 'javascript:void(0);';
                }

                if ($portal_employer['youtube_footer'] != NULL) {
                    $data['youtube_footer']                                     = $portal_employer['youtube_footer'];
                } else {
                    $data['youtube_footer']                                     = 'javascript:void(0);';
                }

                if ($portal_employer['instagram_footer'] != NULL) {
                    $data['instagram_footer']                                   = $portal_employer['instagram_footer'];
                } else {
                    $data['instagram_footer']                                   = 'javascript:void(0);';
                }

                if ($portal_employer['glassdoor_footer'] != NULL) {
                    $data['glassdoor_footer']                                   = $portal_employer['glassdoor_footer'];
                } else {
                    $data['glassdoor_footer']                                   = 'javascript:void(0);';
                }

                $this->db->select('*'); // get company details
                $this->db->where('sid', $company_sid);
                $this->db->from('users');
                $company_details                                                = $this->db->get()->result_array();
                $data['company_details']                                        = $company_details[0];
                
                $this->db->select('sid, email'); // get primary admin of the company
                $this->db->where('parent_sid', $company_sid);
                $this->db->where('is_primary_admin', 1);
                $this->db->from('users');
                $primary_admin_details                                          = $this->db->get()->result_array();

                $data['primary_admin_details'] = array();

                if (!empty($primary_admin_details)) {
                    $data['primary_admin_details']['id']                        = $primary_admin_details[0]['sid'];
                    $data['primary_admin_details']['email']                     = $primary_admin_details[0]['email'];
                }

                $this->db->select('*'); // get company template emails
                $this->db->where('company_sid', $company_sid);
                $this->db->from('portal_email_templates');
                $company_email_templates                                        = $this->db->get()->result_array();

                $data['company_email_templates']                                = array();

                if (!empty($company_email_templates)) {
                    $data['company_email_templates']                            = $company_email_templates;
                }

                $data['heading_title']                                          = $company_details[0]['CompanyName'];

                $this->db->select('sub_domain'); // get domain name
                $this->db->where('user_sid', $company_sid);
                $this->db->from('portal_employer');
                $domain_details                                                 = $this->db->get()->result_array();
                $data['domain_name']                                            = $domain_details[0]['sub_domain'];

                $this->db->select('*'); // get portal details
                $this->db->from('portal_themes');
                $this->db->where('user_sid', $company_sid);
                $this->db->where('theme_status', '1');
                $theme_data = $this->db->get()->result_array();
                $theme                                                          = $theme_data[0];
                $data['theme_sid']                                              = $theme['sid'];
                $data['theme_name']                                             = $theme['theme_name'];
                $data['body_bgcolor']                                           = $theme['body_bgcolor'];
                $data['heading_color']                                          = $theme['heading_color'];
                $data['enable_home_job_button']                                 = $theme['theme4_enable_home_job_opportunity'];
                $data['home_job_button_text']                                   = $theme['theme4_home_job_opportunity_text'];
                $data['font_color']                                             = $theme['font_color'];
                $data['hf_bgcolor']                                             = $theme['hf_bgcolor'];
                $data['title_color']                                            = $theme['title_color'];
                $data['f_bgcolor']                                              = $theme['f_bgcolor'];
                $data['theme_name']                                             = $theme['theme_name'];
                $data['pictures']                                               = $theme['pictures'];
                $data['is_paid']                                                = $theme['is_paid'];
                $font_customization                                             = $theme['font_customization'];
                $google_fonts_sid                                               = $theme['google_fonts_sid'];
                $web_fonts_sid                                                  = $theme['web_fonts_sid'];
                $theme4_btn_bgcolor                                             = $theme['theme4_btn_bgcolor'];
                $theme4_btn_txtcolor                                            = $theme['theme4_btn_txtcolor'];
                $theme4_heading_color                                           = $theme['theme4_heading_color'];
                $theme4_heading_color_span                                      = $theme['theme4_heading_color_span'];                               
                $theme4_search_container_bgcolor                                = $theme['theme4_search_container_bgcolor'];
                $theme4_search_btn_bgcolor                                      = $theme['theme4_search_btn_bgcolor'];
                $theme4_search_btn_color                                        = $theme['theme4_search_btn_color'];
                $theme4_banner_text_l1_color                                    = $theme['theme4_banner_text_l1_color'];                               
                $theme4_banner_text_l2_color                                    = $theme['theme4_banner_text_l2_color'];
                $theme4_job_title_color                                         = $theme['theme4_job_title_color'];
                
                $data['job_fairs']                                              = $this->get_all_job_fair_forms($company_sid);
                
                $theme4_enable_job_fair_homepage                                = $theme['theme4_enable_job_fair_homepage'];
                $theme4_enable_job_fair_careerpage                              = $theme['theme4_enable_job_fair_careerpage'];
                $job_fair_career_page_url                                       = $theme['job_fair_career_page_url'];
                
                if($job_fair_career_page_url == '' || $job_fair_career_page_url == NULL) {
                    foreach($data['job_fairs'] as $fair_value) {
                        if($fair_value['status'] == 1) {
                            $job_fair_career_page_url = $fair_value['page_url'];
                        }
                    }
                }
                
                $job_fair_homepage_page_url                                     = $theme['job_fair_homepage_page_url'];
                
                if($job_fair_homepage_page_url == '' || $job_fair_homepage_page_url == NULL) {
                    foreach($data['job_fairs'] as $fair_value) {
                        if($fair_value['status'] == 1) {
                            $job_fair_homepage_page_url = $fair_value['page_url'];
                        }
                    }
                }
                
                $data['font_customization']                                     = $font_customization;
                $data['google_fonts_sid']                                       = $google_fonts_sid;
                $data['web_fonts_sid']                                          = $web_fonts_sid;
                $data['theme4_btn_bgcolor']                                     = $theme4_btn_bgcolor;
                $data['theme4_btn_txtcolor']                                    = $theme4_btn_txtcolor;
                $data['theme4_heading_color']                                   = $theme4_heading_color;
                $data['theme4_heading_color_span']                              = $theme4_heading_color_span;
                $data['theme4_search_container_bgcolor']                        = $theme4_search_container_bgcolor;
                $data['theme4_search_btn_bgcolor']                              = $theme4_search_btn_bgcolor;
                $data['theme4_search_btn_color']                                = $theme4_search_btn_color;
                $data['theme4_banner_text_l1_color']                            = $theme4_banner_text_l1_color;
                $data['theme4_banner_text_l2_color']                            = $theme4_banner_text_l2_color;
                $data['theme4_job_title_color']                                 = $theme4_job_title_color;
                $data['theme4_enable_job_fair_homepage']                        = $theme4_enable_job_fair_homepage;
                $data['theme4_enable_job_fair_careerpage']                      = $theme4_enable_job_fair_careerpage;
                $data['job_fair_career_page_url']                               = $job_fair_career_page_url;
                $data['job_fair_homepage_page_url']                             = $job_fair_homepage_page_url;
                $data['custom_font_details']                                    = '';
                $data['logo_details']                                           = $this->get_career_page_logo_record($company_sid);
                
                if( $font_customization > 0 && ( $google_fonts_sid > 0 || $web_fonts_sid > 0 ) ) { // get the custom fonts details
                    if($font_customization==1) { // it means Google fonts are selected
                        $this->db->select('*');
                        $this->db->from('google_fonts');
                        $this->db->where('sid', $google_fonts_sid);
                        $custom_font_details                                    = $this->db->get()->result_array();
                    
                        if(!empty($custom_font_details)){
                            $data['custom_font_details']                        = $custom_font_details[0];
                        }
                    } else { // it means web fonts are selected
                        $this->db->select('*');
                        $this->db->from('web_fonts');
                        $this->db->where('sid', $web_fonts_sid);
                        $custom_font_details                                    = $this->db->get()->result_array();
                    
                        if(!empty($custom_font_details)){
                            $data['custom_font_details']                        = $custom_font_details[0];
                        }
                    }
                }
                
            } else { // portal is in-active show inactive view 
                $data['heading_title']                                          = '<b>Your Career site is not currently active.</b> <br> Please contact your ' . STORE_NAME . ' Representative!';
                $data['user_sid']                                               = $portal_employer['user_sid']; //$company_sid;
                $data['maintenance_mode']                                       = 0;
            }
        } else { // domain does not exists
            $data['heading_title']                                              = 'Error: 404 - Your domain does not exists!';
            $data['maintenance_mode']                                           = 0;
        }
        return $data;
    }

    public function get_active_countries() {
        $this->db->select('*');
        $this->db->where('active', '1');
        $this->db->order_by("order", "asc");
        $this->db->from('countries');
        return $this->db->get()->result_array();
    }

    public function get_active_states($sid = NULL) {
        $this->db->select('sid, state_code, state_name');
        $this->db->where('country_sid', $sid);
        $this->db->order_by("order", "asc");
        $this->db->where('active', '1');
        $this->db->from('states');
        return $this->db->get()->result_array();
    }

    public function GetSingleGoogleUploadByKey($unique_key) {
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $this->db->where('unique_key', $unique_key);
        $data = $this->db->get('google_drive_attachments')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    public function get_maintenance_mode_page_content($company_sid, $portal_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_employer_sid', $portal_sid);
        $data_row = $this->db->get('maintenance_mode_page_content')->result_array();

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
        }
    }

    function get_career_page_logo_record($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('career_page_logo');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }
    
    function get_job_fair_data($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_all_email_template_attachments($template_sid) {
        $this->db->where('portal_email_template_sid', $template_sid);
        $records_obj = $this->db->get('portal_email_templates_attachments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function fetch_template_by_sid($template_sid){
        $this->db->select('*'); // get company template emails
        $this->db->where('sid', $template_sid);
        $this->db->from('portal_email_templates');
        $company_email_template = $this->db->get()->result_array();
        return $company_email_template;
    }
    
    function get_all_job_fair_forms($company_sid) {
        $page_url_array = array();
        $this->db->select('sid, title, page_url, button_background_color, button_text_color, status');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $default_form = $record_obj->result_array();
        $record_obj->free_result();
        
        if(!empty($default_form)) {
            $default_sid = $default_form[0]['sid'];
            $default_page_url = $default_form[0]['page_url'];
            
            if($default_page_url == '' || $default_page_url == NULL) {
                $default_page_url = md5('default_'.$default_sid);
                $this->db->set('page_url', $default_page_url); //update page url at database
                $this->db->where('sid', $default_sid);
                $this->db->update('job_fairs_recruitment');
                $default_form[0]['page_url'] = $default_page_url;
            }
            
            $default_form[0]['form_type'] = 'default';
            $page_url_array[$default_page_url] = $default_form[0];
            $this->db->select('sid, title, page_url, button_background_color, button_text_color, template_sid, template_status, status, form_type'); // fetch all custom forms
            $this->db->where('company_sid', $company_sid);
            $this->db->where('form_type', 'custom');
            $record_obj = $this->db->get('job_fairs_forms');
            $custom_forms = $record_obj->result_array();
            $record_obj->free_result();

            if(!empty($custom_forms)) {
                foreach($custom_forms as $key => $custom_form) {
                    $custom_sid = $custom_form['sid'];
                    $custom_page_url = $custom_form['page_url'];

                    if($custom_page_url == '' || $custom_page_url == NULL) {
                        $custom_page_url = md5('custom_'.$custom_sid);
                        $this->db->set('page_url', $custom_page_url); //update page url at database
                        $this->db->where('sid', $custom_sid);
                        $this->db->update('job_fairs_forms');
                        $custom_forms[$key]['page_url'] = $custom_page_url;
                    }
                    
                    $default_form[] = $custom_forms[$key];
                    $page_url_array[$custom_page_url] = $custom_forms[$key];
                }
            }
        }
        
//        if(!empty($default_form)) {
//            $default_form = $default_form[0];
//        }
         
        return $page_url_array;
    }
    
    function check_if_blocked($applicant_email) {
        $this->db->where('applicant_email', $applicant_email);
        $this->db->from('blocked_applicants');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return 'blocked';
        } else {
            return 'not-blocked';
        }
    }
}