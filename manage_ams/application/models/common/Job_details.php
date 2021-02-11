<?php
class Job_details extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function fetch_all_active_jobs_filtered($company_sid, $country, $state, $city, $category, $keyword) {
        $jobs_list = array();
        $this->db->select('*');
        $this->db->where('corporate_company_sid', $company_sid);
        $this->db->limit(1);
        $automotive_group = $this->db->get('automotive_groups')->result_array();

        if (!empty($automotive_group)) {
            $automotive_group = $automotive_group[0];
            $automotive_group_sid = $automotive_group['sid'];
            $this->db->select('automotive_group_companies.company_sid');
            $this->db->where('automotive_group_companies.automotive_group_sid', $automotive_group_sid);
            $this->db->select('users.has_job_approval_rights');
            $this->db->join('users', 'users.sid = automotive_group_companies.company_sid', 'left');
            $automotive_group_companies = $this->db->get('automotive_group_companies')->result_array();

            if (!empty($automotive_group_companies)) {
                foreach ($automotive_group_companies as $company) {
                    if ($company['company_sid'] > 0) {
                        $this->db->select('*');
                        $this->db->where('active', 1);
                        $this->db->where('published_on_career_page', 1);
                        $this->db->order_by('sid', 'desc');
                        $this->db->where('user_sid', $company['company_sid']);

                        if ($company['has_job_approval_rights'] == 1) {
                            $this->db->where('approval_status', 'approved');
                        }

                        if (strtoupper($country) != 'ALL' && intval($country) > 0) {
                            $this->db->where('Location_Country', $country);
                        }

                        if (strtoupper($state) != 'ALL' && intval($state) > 0) {
                            $this->db->where('Location_State', $state);
                        }

                        if (strtoupper($city) != 'ALL') {
                            $this->db->like('Location_City', $city);
                        }

                        if (strtoupper($category) != 'ALL') {
                            $this->db->like('JobCategory', $category);
                        }

                        if (strtoupper($keyword) != 'ALL') {
                            $this->db->like('Title', $keyword);
                        }

                        $this->db->from('portal_job_listings');
                        $company_jobs = $this->db->get()->result_array();
                        $jobs_list = array_merge($jobs_list, $company_jobs);
                    }
                }
            }
        }

        $this->db->select('has_job_approval_rights');
        $this->db->where('sid', $company_sid);
        $approval_status = $this->db->get('users')->result_array();

        if (!empty($approval_status)) {
            $approval_status = $approval_status[0]['has_job_approval_rights'];
        } else {
            $approval_status = 0;
        }

        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('published_on_career_page', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->where('user_sid', $company_sid);

        if ($approval_status == 1) {
            $this->db->where('approval_status', 'approved');
        }

        if (strtoupper($country) != 'ALL' && intval($country) > 0) {
            $this->db->where('Location_Country', $country);
        }

        if (strtoupper($state) != 'ALL' && intval($state) > 0) {
            $this->db->where('Location_State', $state);
        }

        if (strtoupper($city) != 'ALL') {
            $this->db->like('Location_City', $city);
        }

        if (strtoupper($category) != 'ALL') {
            $this->db->like('JobCategory', $category);
        }

        if (strtoupper($keyword) != 'ALL') {
            $this->db->like('Title', $keyword);
        }

        $this->db->from('portal_job_listings');
        $company_jobs = $this->db->get()->result_array();
        $jobs_list = array_merge($jobs_list, $company_jobs);

        usort($jobs_list, function($a1, $a2) { //Sort array by date
            $v1 = strtotime($a1['activation_date']);
            $v2 = strtotime($a2['activation_date']);
            return $v2 - $v1; //Order DESC
        });

        return $jobs_list;
    }

    function fetch_all_active_jobs($company_sid) {
        $jobs_list = array();
        $this->db->select('*');
        $this->db->where('corporate_company_sid', $company_sid);
        $this->db->limit(1);
        $automotive_group = $this->db->get('automotive_groups')->result_array();

        if (!empty($automotive_group)) {
            $automotive_group = $automotive_group[0];
            $automotive_group_sid = $automotive_group['sid'];
            $this->db->select('automotive_group_companies.company_sid');
            $this->db->where('automotive_group_companies.automotive_group_sid', $automotive_group_sid);
            $this->db->select('users.has_job_approval_rights');
            $this->db->join('users', 'users.sid = automotive_group_companies.company_sid', 'left');
            $automotive_group_companies = $this->db->get('automotive_group_companies')->result_array();

            if (!empty($automotive_group_companies)) {
                foreach ($automotive_group_companies as $company) {
                    if ($company['company_sid'] > 0) {
                        $this->db->select('*');
                        $this->db->where('active', 1);
                        $this->db->where('published_on_career_page', 1);
                        $this->db->order_by('sid', 'desc');
                        $this->db->where('user_sid', $company['company_sid']);

                        if ($company['has_job_approval_rights'] == 1) {
                            $this->db->where('approval_status', 'approved');
                        }

                        $this->db->from('portal_job_listings');
                        $company_jobs = $this->db->get()->result_array();
                        $jobs_list = array_merge($jobs_list, $company_jobs);
                    }
                }
            }
        }

        $this->db->select('has_job_approval_rights');
        $this->db->where('sid', $company_sid);
        $approval_status = $this->db->get('users')->result_array();

        if (!empty($approval_status)) {
            $approval_status = $approval_status[0]['has_job_approval_rights'];
        } else {
            $approval_status = 0;
        }

        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('published_on_career_page', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->where('user_sid', $company_sid);

        if ($approval_status == 1) {
            $this->db->where('approval_status', 'approved');
        }

        $this->db->from('portal_job_listings');
        $company_jobs = $this->db->get()->result_array();
        $jobs_list = array_merge($jobs_list, $company_jobs);

        usort($jobs_list, function($a1, $a2) { //Sort array by date
            $v1 = strtotime($a1['activation_date']);
            $v2 = strtotime($a2['activation_date']);
            return $v2 - $v1; //Order DESC
        });

        return $jobs_list;
    }

    function fetch_jobs_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('active', 1);
        $this->db->where('published_on_career_page', 1);
        $this->db->from('portal_job_listings');
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function fetch_company_jobs_details($sid, $user_sid = NULL) {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        if($user_sid != NULL){
            $this->db->where('user_sid', $user_sid);
        }

        $this->db->where('active', 1);
        //$this->db->where('published_on_career_page', 1);
        $this->db->from('portal_job_listings');
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_statename_by_id($sid) {
            $this->db->select('state_name');
            $this->db->where('sid', $sid);
//            $this->db->where('active', '1');
            $this->db->from('states');
        return $this->db->get()->result_array();
    }

    function get_countryname_by_id($sid) {
            $this->db->select('country_name');
            $this->db->where('sid', $sid);
            $this->db->from('countries');
        return $this->db->get()->result_array();
    }

    function get_job_category_name_by_id($sid) {
            $this->db->select('value');
            $this->db->where('sid', $sid);
            $this->db->where('field_sid', '198');
            $this->db->from('listing_field_list');
        return $this->db->get()->result_array();
    }

    function get_screening_questionnaire_by_id($sid) {
            $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
            $this->db->where('sid', $sid);
            $this->db->from('portal_screening_questionnaires');
        return $this->db->get()->result_array();
    }

    function get_screenings_count_by_id($sid) {
            $this->db->select('*');
            $this->db->where('questionnaire_sid', $sid);
            $this->db->from('portal_questions');
        return $this->db->get()->num_rows();
    }

    function get_screening_questions_by_id($sid) {
            $this->db->select('*');
            $this->db->where('questionnaire_sid', $sid);
            $this->db->from('portal_questions');
        return $this->db->get()->result_array();
    }

    function get_screening_answer_count_by_id($sid) {
            $this->db->select('*');
            $this->db->where('questions_sid', $sid);
            $this->db->from('portal_question_option');
        return $this->db->get()->num_rows();
    }

    function get_screening_answers_by_id($sid) {
            $this->db->select('*');
            $this->db->where('questions_sid', $sid);
            $this->db->from('portal_question_option');
        return $this->db->get()->result_array();
    }

    function get_company_details($sid) {
        $this->db->select('users.*, portal_employer.sub_domain', false);
        $this->db->where('users.sid', $sid);
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $result = $this->db->get('users')->result_array();

        if(!empty($result)){
            $result = $result[0];
        }

        return $result;
        // $this->db->where('sid', $sid);
        // $result = $this->db->get('users')->result_array();

        // if(!empty($result)){
        //     $result = $result[0];
        // }

        // return $result;
    }

    function increment_job_views($sid) {
            $this->db->where('sid', $sid);
            $this->db->set('views', 'views+1', FALSE);
            $this->db->update('portal_job_listings');
        return true;
    }

    function apply_for_job($data) {
        $this->db->insert('portal_job_applications', $data);

        if ($this->db->affected_rows() != 1) {
            $this->session->set_flashdata('message', 'Job application failed, Please try Again!');
            $result[1] = 0;
            return $result;
        } else {
            $this->session->set_flashdata('message', '<b>Success!</b> You have successfully applied for the job!');
            $result[0] = $this->db->insert_id();
            $result[1] = 1;
            return $result;
        }
    }

    function add_applicant_job_details($applicant_info) {
        $this->db->insert('portal_applicant_jobs_list', $applicant_info);

        if ($this->db->affected_rows() != 1) {
            $result[0] = 0;
            $result[1] = 0;
            return $result;
        } else {
            $result[0] = $this->db->insert_id();
            $result[1] = 1;
            return $result;
        }
    }

    function update_applicant_applied_date($sid, $update_array) {
        $this->db->where('sid', $sid);
        $this->db->update('portal_job_applications', $update_array);
    }

    function get_company_primary_admin($sid) {
        $this->db->select('email');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_primary_admin', 1);
        $result = $this->db->get('users')->result_array();
        $email = '';

        if (!empty($result)) {
            $email = $result['0']['email'];
        }

        return $email;
    }

    function update_applicant_status_sid($company_sid) {
        $this->db->select('sid, name');   // if records for this company are added, then set the status_sid column also in applications table
        $this->db->where('company_sid', $company_sid);
        $this->db->where('css_class', 'not_contacted');
        $status = $this->db->get('application_status')->result_array();
        $result = array();
        $result['status_sid'] = 1;
        $result['status_name'] = 'Not Contacted Yet';

        if ((sizeof($status) > 0) && isset($status[0]['sid'])) {
            $result['status_sid'] = $status[0]['sid'];
            $result['status_name'] = $status[0]['name'];
        }

        return $result;
    }

    function check_job_applicant($job_sid, $email, $company_sid = NULL) {
        if ($job_sid == 'company_check') { // It checks whether this applicant has applied for any job in this company
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->order_by('sid', 'desc');
            $this->db->limit(1);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if (sizeof($result) > 0) {
                $output = $result[0]['sid'];
            } else {
                $output = 'no_record_found';
            }

            return $output;
        } else { // It checks whether this applicant has applied for this particular job
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if (empty($result)) {
                return 0;
            } else {
                $applicant_sid = $result[0]['sid'];
                $this->db->select('sid');
                $this->db->where('job_sid', $job_sid);
                $this->db->where('portal_job_applications_sid', $applicant_sid);
                $this->db->from('portal_applicant_jobs_list');
                return $this->db->get()->num_rows();
            }
        }
    }

    function friend_share_job($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $data) { // Share job with Friend
        $sid = $data['company_details']['sid'];
        $email = $data['company_details']['email'];
        $CompanyName = $data['company_details']['CompanyName'];
        $job_id = $data['job_details']['sid'];
        $job_title = $data['job_details']['Title'];
        $subject = $sender_name . ' Recommends You a Job';
        $sub_domain_url = db_get_sub_domain($sid);
        $links = '';
        $jobAd = '';

        $email_header_footer['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#000;"><h2 style="color:#fff;">' . ucwords($CompanyName) . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">';
        $email_header_footer['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#000; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_PROTOCOL . $sub_domain_url . '">&copy; ' . date('Y') . ' ' . $sub_domain_url . '. All Rights Reserved.</a></p></div></div></div>';
        $portal_job_url = STORE_PROTOCOL . $sub_domain_url . '/job_details/' . $job_id;
        $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($portal_job_url) . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-2.png"></a>';
        $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($job_title) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-3.png"></a>';
        $btn_google = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-1.png"></a>';
        $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($job_title) . '&amp;summary=' . urlencode($job_title) . '&amp;source=' . urlencode(base_url('/job_details/' . $job_id)) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-4.png"></a>';
        $btn_job_link = '<a style="'.DEF_EMAIL_BTN_STYLE_SUCCESS.'" target="_blank" href="' . $portal_job_url . '">' . ucwords($job_title) . '</a>';
        $jobAd .= '<div style="float:left; width: 100%; margin-bottom:20px; border-radius: 4px; border: 1px solid #d8d8d8; background-color: white; padding: 20px; opacity: 0.75;">';
        $jobAd .= '<h3><strong>' . ucwords($job_title) . '</strong></h3>';
        $jobAd .= '<h3>' . 'Job Description' . '</h3>';
        $jobAd .= '<p style="word-wrap: break-word;">' . ucwords($data['job_details']['JobDescription']) . '</p>';
        $jobAd .= '<h3>' . 'Job Requirements' . '</h3>';
        $jobAd .= '<p style="word-wrap: break-word;">' . ucwords($data['job_details']['JobRequirements']) . '</p>';
        $jobAd .= '</div><hr />';
        $links .= '<ul style="float:left; width:100%; padding:0; list-style: none">';
        $links .= '<li style="float: left; margin-right: 10px;">' . $btn_google . '</li>';
        $links .= '<li style="float: left; margin-right: 10px;">' . $btn_facebook . '</li>';
        $links .= '<li style="float: left; margin-right: 10px;">' . $btn_linkedin . '</li>';
        $links .= '<li style="float: left; margin-right: 10px;">' . $btn_twitter . '</li>';
        $links .= '</ul><hr />';
        $replacement_array = array();
//        $replacement_array['employee-name'] = ucwords($receiver_name);
        $replacement_array['company-name'] = ucwords($CompanyName);
        $replacement_array['job-link'] = $btn_job_link;
        $replacement_array['job-ad'] = $jobAd;
        $replacement_array['share-links'] = $links;
        $replacement_array['job-title'] = $job_title;
        $replacement_array['contact-name'] = ucwords($receiver_name);
        $replacement_array['contact-email'] = $receiver_email;
        $replacement_array['sender-name'] = ucwords($sender_name);
        $replacement_array['sender-email'] = $sender_email;
        $replacement_array['sender-comments'] = $comment;

        $result = $this->log_and_send_templated_email(TELL_A_FRIEND_JOB_SHARE, $receiver_email, $replacement_array, $email_header_footer);
//      $result = $this->log_and_send_templated_email(JOB_LISTING_SHARE_TO_EMAIL_ADDRESS, $receiver_email, $replacement_array, $email_header_footer);
        return (!$result) ? $this->session->set_flashdata('message', '<b>Failed:</b>Could not share this job with your friend, Please try Again!') : $this->session->set_flashdata('message', '<b>Success: </b>Your letter was sent.');
    }

    function next_job($job_id, $company_id) {
        $data = $this->db->query("SELECT `sid` FROM `portal_job_listings` WHERE sid > $job_id and `user_sid` = $company_id AND `active` = '1' ORDER BY `sid` LIMIT 1");

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function previous_job($job_id, $company_id) {
        $data = $this->db->query("SELECT `sid` FROM `portal_job_listings` WHERE sid < $job_id and `user_sid` = $company_id AND `active` = '1' ORDER BY `sid` DESC LIMIT 1");

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function save_eeo_form($data) {
        $this->db->insert('portal_eeo_form', $data);
    }

    function log_notifications_email($company_sid, $sender, $receiver, $subject, $message, $job_sid, $notification_type) {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['sender'] = $sender;
        $data_to_insert['receiver'] = $receiver;
        $data_to_insert['from_type'] = 'system_notification';
        $data_to_insert['to_type'] = 'notification_contacts';
        $data_to_insert['users_type'] = 'employee';
        $data_to_insert['sent_date'] = date('Y-m-d H:i:s');
        $data_to_insert['subject'] = $subject;
        $data_to_insert['message'] = $message;
        $data_to_insert['job_or_employee_id'] = $job_sid;
        $data_to_insert['notification_type'] = $notification_type;
        $this->db->insert('notifications_emails_log', $data_to_insert);
        return $this->db->insert_id();
    }

    function fetch_job_id_from_random_key($uid){
        $this->db->select('job_sid');
        $this->db->where('uid', $uid);
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['job_sid'];
        } else {
            return null;
        }
    }

    function log_and_send_templated_email($template_id, $to, $replacement_array = array(), $message_hf = array()) {
        $emailTemplateData = get_email_template($template_id);
        $emailTemplateBody = $emailTemplateData['text'];
        $emailTemplateSubject = $emailTemplateData['subject'];
        $emailTemplateFromName = $emailTemplateData['from_name'];

        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
            }
        }

        $from = $emailTemplateData['from_email'];
        $subject = $emailTemplateSubject;
        $from_name = $emailTemplateData['from_name'];
        $reply_to = $replacement_array['sender_email'];

        if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
            $from_name = $replacement_array['company_name'];
        }

        if (!empty($message_hf)) {
            $body = $message_hf['header']
                . $emailTemplateBody
                . $message_hf['footer'];
        } else {
            $body = EMAIL_HEADER
                . $emailTemplateBody
                . EMAIL_FOOTER;
        }

        $result = $this->log_and_sendEmail($from, $to, $subject, $body, $from_name, $reply_to);
        return $result;
    }

    function log_and_sendEmail($from, $to, $subject, $body, $senderName, $reply_to) {
        if (base_url() == 'http://localhost/automotoCI') {
            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $to,
                                'message' => $body,
                                'admin' => 'admin',
                                'username' => $senderName,
                            );

            save_email_log_common($emailData);
        } else {
            $emailData = array(
                                'date' => date('Y-m-d H:i:s'),
                                'subject' => $subject,
                                'email' => $to,
                                'message' => $body,
                                'username' => $senderName,
                            );

            $result = save_email_log_common($from, $to, $subject, $body,'admin');
            sendMail($from, $to, $subject, $body, $senderName, $reply_to);
            return $result;
        }
    }

    function update_questionnaire_result($sid, $questionnaire, $q_passing, $total_score, $questionnaire_result = NULL) {
        $update_data = array(   'questionnaire' => $questionnaire,
                                'score' => $total_score,
                                'passing_score' => $q_passing,
                                'questionnaire_ip_address' => getUserIP(),
                                'questionnaire_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                'questionnaire_result' => $questionnaire_result
                            );

        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
    }

    function insert_questionnaire_result($data) {
        $this->db->insert('screening_questionnaire_results', $data);
    }

    function get_possible_score_of_questions($sid, $type) {
        $result = 0;

        if($type == 'multilist') {
            $this->db->select_sum('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        } else {
            $this->db->select_max('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        }

        return $result;
    }

    function get_individual_question_details($sid) {
        $this->db->select('value, score, result_status');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if(!empty($records_arr)){
            $result = $records_arr[0];
        }

        return $result;
    }

    function get_all_inactive_companuies($active=0) {
        $this->db->select('sid');
        $this->db->where('active', $active);
        $this->db->where('parent_sid', 0);
        $this->db->from('users');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function get_all_company_jobs_ams($paid_jobs, $country = NULL, $state = NULL, $city = NULL, $categoryId = NULL, $keyword = NULL, $career_site_company_sid = array(), $limit = null, $offset = null, $count_record = false) {
//        echo $country.' - '.$state.' - '.$city.' - '.$categoryId.' - '.$keyword.'';
        if(!$count_record){
            $this->db->select('portal_job_listings.*, users.CompanyName, users.YouTubeVideo, users.Logo, users.ContactName, users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, portal_employer.domain_type, users.has_job_approval_rights');
        }
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        //$this->db->where('portal_job_listings.published_on_career_page', 1);

        if(!empty($paid_jobs)) {
            $this->db->where_not_in('portal_job_listings.sid', $paid_jobs);
        }

        if(!empty($career_site_company_sid)) {
            $this->db->where_not_in('portal_job_listings.user_sid', $career_site_company_sid);
        }

        if (strtoupper($country) != 'ALL' && intval($country) > 0) {
            $this->db->where('portal_job_listings.Location_Country', $country);
        }

        if (strtoupper($state) != 'ALL' && intval($state) > 0) {
            $this->db->where('portal_job_listings.Location_State', $state);
        }

        if (strtoupper($city) != 'ALL' && trim($city) != NULL) {
            $this->db->like('portal_job_listings.Location_City', $city);
        }

        if (strtoupper($categoryId) != 'ALL'  && intval($categoryId) > 0) {
            $this->db->like('JobCategory', $categoryId);
//            $this->db->where("FIND_IN_SET('portal_job_listings.JobCategory',$categoryId) !=", 0); // there is some issue with find in set. it does not returns the result
        }

        if (strtoupper($keyword) != 'ALL' && trim($keyword) != NULL) {
            $this->db->like('portal_job_listings.Title', $keyword);
        }
        if(!$count_record){
            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }
        }

        $this->db->where('users.active', 1);
        $this->db->where('users.is_paid', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        if(!$count_record) $this->db->group_by('portal_job_listings.sid');
        $this->db->from('portal_job_listings');

        if($count_record){
            $record_arr = $this->db->count_all_results();
        }
        else{
            $record_obj = $this->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        }

//        echo '<br>'.'<br>'.'<br>'.'<br>'.$this->db->last_query(); exit;
        return $record_arr;
    }

    function get_all_company_jobs_ams_cron(
        $numberOfPages,
        $ids
        ) {
        $this->db->select('portal_job_listings.*, users.CompanyName, users.YouTubeVideo, users.Logo, users.ContactName, users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, portal_employer.domain_type, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);
        $this->db->where('users.active', 1);
        $this->db->where('users.career_site_listings_only', 0);

        $this->db->where_not_in('portal_job_listings.sid', $ids);

        $this->db->limit($numberOfPages);

        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        $this->db->from('portal_job_listings');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function paid_job_details($paid_jobs, $country = NULL, $state = NULL, $city = NULL, $categoryId = NULL, $keyword = NULL, $limit = null, $offset = null, $count_record = false) {
        if(!$count_record){
            $this->db->select('portal_job_listings.*, users.CompanyName, users.YouTubeVideo, users.Logo, users.ContactName, users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, portal_employer.domain_type, users.has_job_approval_rights');
        }
        $this->db->where_in('portal_job_listings.sid', $paid_jobs);

        if (strtoupper($country) != 'ALL' && intval($country) > 0) {
            $this->db->where('portal_job_listings.Location_Country', $country);
        }

        if (strtoupper($state) != 'ALL' && intval($state) > 0) {
            $this->db->where('portal_job_listings.Location_State', $state);
        }

        if (strtoupper($city) != 'ALL' && trim($city) != NULL) {
            $this->db->like('portal_job_listings.Location_City', $city);
        }

        if (strtoupper($categoryId) != 'ALL'  && intval($categoryId) > 0) {
            $this->db->like('JobCategory', $categoryId);
        }

        if (strtoupper($keyword) != 'ALL' && trim($keyword) != NULL) {
            $this->db->like('portal_job_listings.Title', $keyword);
        }
        if(!$count_record){
            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }
        }

        $this->db->where('users.active', 1);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        $this->db->from('portal_job_listings');

        if($count_record){
            $record_arr = $this->db->count_all_results();
        }else{

            $record_obj = $this->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        }
        return $record_arr;
    }

    function get_all_company_jobs_ams_original() {
        $this->db->select('portal_job_listings.sid as jobId, expiry_date');
        $product_ids = array(3, 4);
        $this->db->where('active', 1);
        $this->db->where_in('product_sid', $product_ids);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function fetch_uid_from_job_sid($job_sid) {
        $this->db->select('uid, publish_date');
        $this->db->where('job_sid', $job_sid);
        $this->db->where('active', 1);
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return '';
        }
    }

    function get_all_company_jobs() {
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_active_companies($feedSid) {
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `active` = '1' AND `is_paid` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();

        if (count($result) > 0) {
            $data = array();

            foreach ($result as $r) {
                // Check if feed is allowed
                if($this->db
                    ->where('company_sid', $r['sid'])
                    ->where('feed_sid', $feedSid)
                    ->where('status', 0)
                    ->count_all_results('feed_restriction')
                ){
                    continue;
                }
                $data[] = $r['sid'];
            }

            return $data;
        } else {
            return 0;
        }
    }

    function get_portal_detail($company_id) {
        $this->db->where('user_sid', $company_id);
        $result = $this->db->get('portal_employer')->result_array();

        if(!empty($result)){
            return $result[0];
        } else {
            return array();
        }
    }

    function get_company_detail($sid) {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_all_organic_jobs($featuredArray) {
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->where_not_in('sid', $featuredArray);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_my_jobs($country=NULL, $state=NULL, $city=NULL, $categoryId=NULL, $keyword=NULL) {
        $this->db->select('portal_job_listings.*, users.CompanyName, users.YouTubeVideo, users.Logo, users.ContactName, users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, portal_employer.domain_type, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
//        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.user_sid', 57);

        if (strtoupper($country) != 'ALL' && intval($country) > 0) {
            $this->db->where('portal_job_listings.Location_Country', $country);
        }

        if (strtoupper($state) != 'ALL' && intval($state) > 0) {
            $this->db->where('portal_job_listings.Location_State', $state);
        }

        if (strtoupper($city) != 'ALL' && trim($city) != NULL) {
            $this->db->like('portal_job_listings.Location_City', $city);
        }

        if (strtoupper($categoryId) != 'ALL'  && intval($categoryId) > 0) {
            $this->db->like('JobCategory', $categoryId);
        }

        if (strtoupper($keyword) != 'ALL' && trim($keyword) != NULL) {
            $this->db->like('portal_job_listings.Title', $keyword);
        }

        $this->db->where('users.active', 1);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        $this->db->from('portal_job_listings');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

//        echo '<br>'.'<br>'.'<br>'.'<br>'.$this->db->last_query(); exit;
        return $record_arr;
    }

    function get_all_company_jobs_sids($paid_jobs) {
        $this->db->select('portal_job_listings.sid, portal_job_listings.approval_status, users.CompanyName, users.YouTubeVideo, users.Logo, users.ContactName, users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, portal_employer.domain_type, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);

        if(!empty($paid_jobs)) {
            $this->db->where_not_in('portal_job_listings.sid', $paid_jobs);
        }

        $this->db->where('users.active', 1);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        $this->db->from('portal_job_listings');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

//        echo '<br>'.'<br>'.'<br>'.'<br>'.$this->db->last_query(); exit;
        return $record_arr;
    }

    function get_all_paid_jobs($career_site_company_sid) {
        $this->db->select('portal_job_listings.sid as jobId, expiry_date');
        $product_ids = array(1, 2, 3, 4, 5, 21, 38);
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where_in('product_sid', $product_ids);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.job_sid');

        if(!empty($career_site_company_sid)) {
            $this->db->where_not_in('portal_job_listings.user_sid', $career_site_company_sid);
        }

        $record_obj = $this->db->get('jobs_to_feed');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_career_site_only_companies() {
        $this->db->select('sid, per_job_listing_charge, career_site_listings_only');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_site_listings_only', 1);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function check_company_status($sid) {
        $this->db->select('career_site_listings_only');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr[0]['career_site_listings_only'];
    }

    function filters_of_active_jobs($career_site_company_sid = array()) {
        $this->db->select('portal_job_listings.Location_Country, portal_job_listings.Location_State, portal_job_listings.JobCategory, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);

        if(!empty($career_site_company_sid)) {
            $this->db->where_not_in('portal_job_listings.user_sid', $career_site_company_sid);
        }

        $this->db->where('users.active', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        $this->db->from('portal_job_listings');

//_e($this->db->get_compiled_select());

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    public function save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, $email_status = 'not-sent'){
        $insert_data = array(
            'sender_name' => $sender_name,
            'sender_email' => $sender_email,
            'receiver_name' => $receiver_name,
            'receiver_email' => $receiver_email,
            'sender_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'sender_ip' => getUserIP(),
            'job_url' => base_url('job_details/'.$sid),
            'job_sid'     => $sid,
            'created_date' => date('Y-m-d H:i:s'),
            'comments' => $comment,
            'email_status' => $email_status
        );
        $this->db->insert('tell_a_friend_history',$insert_data);
        return $this->db->insert_id();
    }

    public function check_if_applied_already($sid){
        $this->db->from('tell_a_friend_history');
        $this->db->where('sender_ip',getUserIP());
        $this->db->where('job_sid',$sid);
        $this->db->where('DATE_FORMAT(created_date, "%Y-%m-%d") = "'.date('Y-m-d').'"');
        $count = $this->db->count_all_results();
        return $count;
    }

     /**
    * Check if IP used in a sepecifc job
    * for a specific company
    * Created on: 05-08-2019
    *
    * @param $ip
    * @param $job_sid
    * @param $company_sid
    *
    * @return Array|Bool
    */
    function checkUserAppliedForJobByIP($ip, $job_sid, $company_sid){
        $result =  $this->db
        ->select('sid')
        ->from('portal_job_restriction')
        ->where('ip_address', $ip)
        ->where('company_sid', $company_sid)
        ->where('job_sid', $job_sid)
        ->where('expire_at >= ', date('Y-m-d H:i:s', strtotime('now')))
        ->limit(1)
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();

        return !sizeof($result_arr) ? 0 : $result_arr['sid'];
    }


    /**
    * Add job restriction
    * Created on: 05-08-2019
    *
    * @param $insert_array
    *  'job_sid'
    *  'company_sid'
    *  'ip_address'
    *  'expire_at'
    *
    * @return Array|Bool
    */
    function addJobRestrictionRow($insert_array){
        $inserted =  $this->db->insert('portal_job_restriction', $insert_array);
        return $this->db->insert_id();
    }

    /**
    * Update job restriction
    * Created on: 05-08-2019
    *
    * @param $sid
    *
    * @return VOID
    */
    function updateJobRestrictionStatus($sid){
        $this->db->where('sid', $sid)->update('portal_job_restriction', array( 'status' => 'rejected' ));
    }

    function insert_resume_log($resume_log_data){
        $this->db->insert('resume_request_logs', $resume_log_data);
    }

    function get_old_resume ($sid) {
        $this->db->select('resume');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return array();
        }
    }


    // TOBE deleted after testing
    function getTestingCompanyJobs() {
        $this->db->select('portal_job_listings.sid as jobId,  "2019-12-12" as expiry_date');
        $this->db->select('
            "0" as has_job_approval_rights,
            "38" as Location_Country,
            "1" as Location_State,
            NULL as JobCategory,
            "13" as questionnaire_sid,
            "58" as user_sid,
            "1" as job_title_location,
            "1" as sub_domain,
            "Ipsum aperiam et mod" as Location_City,
            portal_job_listings.sid as sid,
            Title
        ', false);
        $this->db->where('active', 1);
        $this->db->limit( 5);
        return $this->db->get('portal_job_listings')->result_array();
    }


    /**
     * Get last IDs sent to Goole Job API
     * Created on: 10-24-2019
     *
     * @param Int $type
     * '0' AutomotoHR
     * '1' AutomotoSocial
     * @return Array
     **/
    function getLastProcessedIds($type = 1){
        $result = $this->db
        ->select('job_sid')
        ->from('cron_google_hire_jobs')
        ->where('site', 1)
        ->where('status', 1)
        ->order_by('created_at', 'DESC')
        ->get();
        //
        $ids = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($ids)) return array();
        $returnArray = array();
        foreach ($ids as $k0 => $v0) $returnArray[] = $v0['job_sid'];
        //
        return $returnArray;
    }


    /**
     * Insert last ID sent to Goole Job API
     * Created on: 10-24-2019
     *
     * @param String $id
     * @return Integer
     **/
    function addProccessedId($id){
        $this->db->insert('cron_google_hire_jobs', array(
            'job_sid' => $id,
            'site' => 1,
            'status' => 1
        ));
        return $this->db->insert_id();
    }


    function check_for_slug($slug){
        $this->db->select('sid');
        $this->db->where('status', 1);
        $this->db->where('slug', $slug);
        $result = $this->db->get('job_feeds_management')->result_array();
        if(sizeof($result)){
            return $result[0]['sid'];
        }else{
            return false;
        }
    }
}