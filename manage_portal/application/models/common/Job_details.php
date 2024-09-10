<?php
class Job_details extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function fetch_all_active_jobs_filtered($company_sid, $country, $state, $city, $category, $keyword)
    {
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

        usort($jobs_list, function ($a1, $a2) { //Sort array by date
            $v1 = strtotime($a1['activation_date']);
            $v2 = strtotime($a2['activation_date']);
            return $v2 - $v1; //Order DESC
        });

        return $jobs_list;
    }

    function fetch_all_active_jobs($company_sid)
    {
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

        usort($jobs_list, function ($a1, $a2) { //Sort array by date
            $v1 = strtotime($a1['activation_date']);
            $v2 = strtotime($a2['activation_date']);
            return $v2 - $v1; //Order DESC
        });

        return $jobs_list;
    }

    function filters_of_active_jobs($company_sid, $approval_status = 0)
    {
        $this->db->select('Location_Country, Location_State, JobCategory');
        $this->db->where('active', 1);
        $this->db->where('published_on_career_page', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->where('user_sid', $company_sid);

        if ($approval_status == 1) {
            $this->db->where('approval_status', 'approved');
        }

        $record_obj = $this->db->get('portal_job_listings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function fetch_jobs_details($sid)
    {
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

    function fetch_company_jobs_details($sid, $user_sid = NULL, $is_preview = 0)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        if ($user_sid != NULL) {
            $this->db->where('user_sid', $user_sid);
        }

        if ($is_preview == 0) {
            $this->db->where('active', 1);
            $this->db->where('published_on_career_page', 1);
        }

        $this->db->from('portal_job_listings');
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function total_active_jobs($company_sid)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('published_on_career_page', 1);
        $this->db->from('portal_job_listings');
        return $this->db->count_all_results();
        return $this->db->get()->num_rows();
    }

    function get_statename_by_id($sid)
    {
        $this->db->select('state_name, state_code');
        $this->db->where('sid', $sid);
        //  $this->db->where('active', '1');
        $this->db->from('states');
        return $this->db->get()->result_array();
    }

    function get_countryname_by_id($sid)
    {
        $this->db->select('country_name');
        $this->db->where('sid', $sid);
        $this->db->from('countries');
        return $this->db->get()->result_array();
    }

    function get_job_category_name_by_id($sid)
    {
        $this->db->select('value');
        $this->db->where('sid', $sid);
        $this->db->where('field_sid', '198');
        $this->db->from('listing_field_list');
        return $this->db->get()->result_array();
    }

    function get_screening_questionnaire_by_id($sid)
    {
        $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
        $this->db->where('sid', $sid);
        $this->db->from('portal_screening_questionnaires');
        return $this->db->get()->result_array();
    }

    function get_screenings_count_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->count_all_results();
        return $this->db->get()->num_rows();
    }

    function get_screening_questions_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->get()->result_array();
    }

    function get_screening_answer_count_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->count_all_results();
        return $this->db->get()->num_rows();
    }

    function get_screening_answers_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->get()->result_array();
    }

    function get_company_details($sid)
    {
        $this->db->select('users.*, portal_employer.sub_domain', false);
        $this->db->where('users.sid', $sid);
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    function increment_job_views($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->set('views', 'views+1', FALSE);
        $this->db->update('portal_job_listings');
        return true;
    }

    function apply_for_job($data)
    {
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

    function add_applicant_job_details($applicant_info)
    {
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

    function update_applicant_applied_date($sid, $update_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_job_applications', $update_array);
    }

    function get_applicant_detail($job_id)
    {
        $this->db->select('sid,job_sid,employer_sid,applicant_type,email,resume');
        $this->db->where('sid', $job_id);
        $this->db->limit(1);
        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_resume_request_log($resume_log_data)
    {
        $this->db->insert('resume_request_logs', $resume_log_data);
    }

    function get_company_primary_admin($sid)
    {
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

    function update_applicant_status_sid($company_sid)
    {
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

    function check_job_applicant($job_sid, $email, $company_sid = NULL)
    {
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
                return $this->db->count_all_results();
                return $this->db->get()->num_rows();
            }
        }
    }

    function friend_share_job($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $data)
    { // Share job with Friend
        $sid = $data['company_details']['sid'];
        $email = $data['company_details']['email'];
        $CompanyName = $data['company_details']['CompanyName'];
        $job_id = $data['job_details']['sid'];
        $job_title = $data['job_details']['Title'];
        $subject = $sender_name . ' Recommends You a Job';
        $sub_domain_url = db_get_sub_domain($sid);
        $links = '';
        $jobAd = '';

        $email_header_footer['header'] = '<div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative"><div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing:border-box; background-color:#0000FF;"><h2 style="color:#fff;">' . ucwords($CompanyName) . '</h2></div>  <div class="body-content" style="width:100%; float:left; padding:20px 0; box-sizing:padding-box;">';
        $email_header_footer['footer'] = '</div><div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:100%; "><p style="color:#fff; float:left; text-align:center; font-style:italic; line-height:normal; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px;"><a style="color:#fff; text-decoration:none;" href="' . STORE_PROTOCOL . $sub_domain_url . '">&copy; ' . date('Y') . ' ' . $sub_domain_url . '. All Rights Reserved.</a></p></div></div></div>';
        $portal_job_url = STORE_PROTOCOL . $sub_domain_url . '/job_details/' . $job_id;
        $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($portal_job_url) . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-2.png"></a>';
        $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($job_title) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-3.png"></a>';
        $btn_google = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-1.png"></a>';
        $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($job_title) . '&amp;summary=' . urlencode($job_title) . '&amp;source=' . urlencode(base_url('/job_details/' . $job_id)) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-4.png"></a>';
        $btn_job_link = '<a style="' . DEF_EMAIL_BTN_STYLE_SUCCESS . '" target="_blank" href="' . $portal_job_url . '">' . ucwords($job_title) . '</a>';
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

    function next_job($job_id, $company_id)
    {
        $data = $this->db->query("SELECT `sid` FROM `portal_job_listings` WHERE sid > $job_id and `user_sid` = $company_id AND `active` = '1' ORDER BY `sid` LIMIT 1");

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function previous_job($job_id, $company_id)
    {
        $data = $this->db->query("SELECT `sid` FROM `portal_job_listings` WHERE sid < $job_id and `user_sid` = $company_id AND `active` = '1' ORDER BY `sid` DESC LIMIT 1");

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function save_eeo_form($data)
    {
        $this->db->insert('portal_eeo_form', $data);
    }

    function log_notifications_email($company_sid, $sender, $receiver, $subject, $message, $job_sid, $notification_type)
    {
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

    function fetch_job_id_from_random_key($uid)
    {
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

    function log_and_send_templated_email($template_id, $to, $replacement_array = array(), $message_hf = array())
    {
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

    function log_and_sendEmail($from, $to, $subject, $body, $senderName, $reply_to)
    {
        if (base_url() == 'http://localhost/ahr') {
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

            $result = save_email_log_common($from, $to, $subject, $body, 'admin');
            sendMail($from, $to, $subject, $body, $senderName, $reply_to);
            return $result;
        }
    }

    function update_questionnaire_result($sid, $questionnaire, $q_passing, $total_score, $questionnaire_result = NULL)
    {
        $update_data = array(
            'questionnaire' => $questionnaire,
            'score' => $total_score,
            'passing_score' => $q_passing,
            'questionnaire_ip_address' => getUserIP(),
            'questionnaire_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'questionnaire_result' => $questionnaire_result
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
    }

    function insert_questionnaire_result($data)
    {
        $this->db->insert('screening_questionnaire_results', $data);
    }

    function get_possible_score_of_questions($sid, $type)
    {
        $result = 0;

        if ($type == 'multilist') {
            $this->db->select_sum('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if (!empty($records_arr)) {
                $result = $records_arr[0]['score'];
            }
        } else {
            $this->db->select_max('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if (!empty($records_arr)) {
                $result = $records_arr[0]['score'];
            }
        }

        return $result;
    }

    function get_individual_question_details($sid)
    {
        $this->db->select('value, score, result_status');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if (!empty($records_arr)) {
            $result = $records_arr[0];
        }

        return $result;
    }


    /**
     * Fetch company jobs with pay per activation check
     * Created on: 17-05-2019
     *
     * @param $company_sid      Integer
     * @param $job_sid          Integer Optional
     * @param $payperjob_check  Integer Optional
     * @param $is_preview       Integer Optional
     * @param $filter_array     Array   Optional
     *
     * @return Bool|Array
     *
     **/
    function fetch_company_jobs_new($company_sid = NULL, $job_sid = NULL, $payperjob_check = TRUE, $is_preview = 0, $filter_array = array(), $approval_status = 0)
    {

        //
        $fetch_type = 'result_array';
        $payper_active = false; // $payperjob_check ? $this->get_pay_per_job_status($company_sid) : false;
        // Fetch a single record
        if ($job_sid != NULL) {

            // echo $approval_status;
            if ($approval_status == 1) $this->db->where('approval_status', 'approved');

            $this->db
                ->select('portal_job_listings.*')
                // ->where('approval_status','approved')
                ->from('portal_job_listings');

            // To fetch a single job
            if ($job_sid != NULL) $this->db->where('portal_job_listings.sid', $job_sid);

            // If in preview mode
            if ($is_preview == 0) {
                $this->db
                    ->where('portal_job_listings.active', 1)
                    ->where('portal_job_listings.published_on_career_page', 1);
            }
            // If company check is set
            if ($company_sid != NULL) $this->db->where('portal_job_listings.user_sid', $company_sid);

            // pay per check
            if ($payper_active) {
                $this->db
                    ->where('portal_job_listings.ppj_product_id <> 0', null)
                    ->where('portal_job_listings.approval_status_change_datetime >= CURDATE()', null);
            }
            $result = $this->db->get();
            //

            if ($job_sid != NULL) $fetch_type = 'row_array';

            $result_arr = $result->$fetch_type();
            $result = $result->free_result();

            return $result_arr;
        }
        // Set defaut jobs array
        $automotive_group_companies = $jobs_list = array();

        $result = $this->db
            ->select('sid')
            ->from('automotive_groups')
            ->where('corporate_company_sid', $company_sid)
            ->limit(1)
            ->get();

        $automotive_group_sid = $result->row_array();
        $result = $result->free_result();

        // Check for automotive group companies
        if (sizeof($automotive_group_sid)) {
            $automotive_group_sid = $automotive_group_sid['sid'];
            $result = $this->db
                ->select('
                automotive_group_companies.company_sid,
                users.has_job_approval_rights
            ')
                ->from('automotive_group_companies')
                ->join('users', 'users.sid = automotive_group_companies.company_sid', 'left')
                ->where('automotive_group_companies.automotive_group_sid', $automotive_group_sid)
                ->where('automotive_group_companies.company_sid <> 0', null)
                ->where('users.active', 1)

                ->get();
            $automotive_group_companies = $result->result_array();
            $result = $result->free_result();
        }

        /// Fetch automotive group companies jobs
        if (sizeof($automotive_group_companies)) {
            foreach ($automotive_group_companies as $company) {
                $this->db
                    ->select('*')
                    ->from('portal_job_listings')
                    ->where('active', 1)
                    ->where('published_on_career_page', 1)
                    ->order_by('sid', 'desc')
                    ->where('user_sid', $company['company_sid']);

                // pay per check
                if ($payper_active) {
                    $this->db
                        ->where('portal_job_listings.ppj_product_id <> 0', null)
                        ->where('portal_job_listings.approval_status_change_datetime >= CURDATE()', null);
                }
                //
                if ($company['has_job_approval_rights'] == 1) $this->db->where('approval_status', 'approved');
                // For filter records
                if (sizeof($filter_array)) {
                    if (strtolower($filter_array['country']) != 'all' && intval($filter_array['country']) != 0)
                        $this->db->where('Location_Country', $filter_array['country']);

                    if (strtolower($filter_array['state']) != 'all' && intval($filter_array['state']) != 0)
                        $this->db->where('Location_State', $filter_array['state']);

                    if (strtolower($filter_array['city']) != 'all')
                        $this->db->like('Location_City', $filter_array['city']);

                    if (strtolower($filter_array['categoryId']) != 'all' && intval($filter_array['categoryId']) != 0)
                        $this->db->like('JobCategory', $filter_array['categoryId']);

                    if (strtolower($filter_array['keyword']) != 'all')
                        $this->db->like('Title', $filter_array['keyword']);
                }
                //
                $result = $this->db->get();
                $company_jobs = $result->result_array();
                $result = $result->free_result();
                $jobs_list = array_merge($jobs_list, $company_jobs);
            }
        }

        // After
        $result = $this->db
            ->select('has_job_approval_rights')
            ->from('users')
            ->where('sid', $company_sid)
            ->get();
        $has_job_approval_rights = $result->row_array();
        $result = $result->free_result();
        //
        $approval_status = 0;
        if (sizeof($has_job_approval_rights))
            $approval_status = $has_job_approval_rights['has_job_approval_rights'];

        $this->db
            ->select('portal_job_listings.*')
            ->from('portal_job_listings')
            ->where('portal_job_listings.active', 1)
            ->where('portal_job_listings.published_on_career_page', 1)
            ->where('portal_job_listings.user_sid', $company_sid)
            ->where('users.active', 1)
            ->join('users', 'users.sid = portal_job_listings.user_sid', 'left')

            ->order_by('portal_job_listings.sid', 'desc');


        if ($approval_status == 1) $this->db->where('portal_job_listings.approval_status', 'approved');

        // For filter records
        if (sizeof($filter_array)) {
            if (strtolower($filter_array['country']) != 'all' && intval($filter_array['country']) != 0)
                $this->db->where('portal_job_listings.Location_Country', $filter_array['country']);

            if (strtolower($filter_array['state']) != 'all' && intval($filter_array['state']) != 0)
                $this->db->where('portal_job_listings.Location_State', $filter_array['state']);

            if (strtolower($filter_array['city']) != 'all')
                $this->db->like('portal_job_listings.Location_City', $filter_array['city']);

            if (strtolower($filter_array['categoryId']) != 'all'  && intval($filter_array['categoryId']) != 0)
                $this->db->like('portal_job_listings.JobCategory', $filter_array['categoryId']);

            if (strtolower($filter_array['keyword']) != 'all')
                $this->db->like('portal_job_listings.Title', $filter_array['keyword']);
        }

        if ($payper_active) {
            $this->db
                ->where('portal_job_listings.ppj_product_id <> 0', null)
                ->where('portal_job_listings.approval_status_change_datetime >= CURDATE()', null);
        }

        $result = $this->db->get();
        $company_jobs = $result->result_array();
        // return $company_jobs;
        if (sizeof($company_jobs)) $jobs_list = array_merge($jobs_list, $company_jobs);

        usort($jobs_list, function ($a1, $a2) { //Sort array by date
            $v1 = strtotime($a1['activation_date']);
            $v2 = strtotime($a2['activation_date']);
            return $v2 - $v1; //Order DESC
        });

        return $jobs_list;
    }

    /**
     * Fetch company jobs with pay per activation check
     * Created on: 17-05-2019
     *
     * @param $company_sid      Integer
     * @param $job_sid          Integer Optional
     * @param $payperjob_check  Integer Optional
     * @param $is_preview       Integer Optional
     * @param $filter_array     Array   Optional
     *
     * @return Bool|Array
     *
     **/
    function get_alternate_job_from_company($company_sid = NULL, $title = NULL, $city = NULL)
    {
        // echo $approval_status;
        if ($company_sid != NULL) {
            $this->db->select('*');
            $this->db->from('portal_job_listings');
            $this->db->where('approval_status', 'approved');
            $this->db->like('Title', trim($title));
            $this->db->where('active', 1);
            $this->db->where('published_on_career_page', 1);
            $this->db->where('user_sid', $company_sid);
            //
            $result = $this->db->get();
            $result_arr = $result->row_array();
            $result->free_result();
            //
            if (!empty($result_arr)) {
                return $result_arr;
            } else {
                $this->db->select('*');
                $this->db->from('portal_job_listings');
                $this->db->where('approval_status', 'approved');
                $this->db->where('active', 1);
                $this->db->where('published_on_career_page', 1);
                $this->db->where('user_sid', $company_sid);
                //
                if (!empty($city) || $city != NULL) {
                    $this->db->like('Location_City', trim($city));
                }
                //
                $result = $this->db->get();
                $result_arr = $result->row_array();
                $result->free_result();
                //
                if (!empty($result_arr)) {
                    return $result_arr;
                } else {
                    return array();
                }
            }
        } else {
            return array();
        }
    }

    /**
     * Fetch pay per status check
     * Created on: 17-05-2019
     *
     * @param $user_sid
     *
     * @return Bool
     *
     **/
    private function get_pay_per_job_status($sid)
    {
        $result =
            $this->db
            ->select('per_job_listing_charge, career_site_listings_only')
            ->where('sid', $sid)
            ->get('users');
        $result_arr = $result->row_array();
        $result->free_result();
        return $result_arr['per_job_listing_charge'] == 1 ? true : false;
    }

    public function save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, $email_status = 'not-sent')
    {
        $insert_data = array(
            'sender_name' => $sender_name,
            'sender_email' => $sender_email,
            'receiver_name' => $receiver_name,
            'receiver_email' => $receiver_email,
            'sender_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'sender_ip' => getUserIP(),
            'job_url' => base_url('job_details/' . $sid),
            'job_sid'     => $sid,
            'created_date' => date('Y-m-d H:i:s'),
            'comments' => $comment,
            'email_status' => $email_status
        );
        $this->db->insert('tell_a_friend_history', $insert_data);
        return $this->db->insert_id();
    }

    public function check_if_applied_already($sid)
    {
        $this->db->from('tell_a_friend_history');
        $this->db->where('sender_ip', getUserIP());
        $this->db->where('job_sid', $sid);
        $this->db->where('DATE_FORMAT(created_date, "%Y-%m-%d") = "' . date('Y-m-d') . '"');
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
    function checkUserAppliedForJobByIP($ip, $job_sid, $company_sid)
    {
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
    function addJobRestrictionRow($insert_array)
    {
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
    function updateJobRestrictionStatus($sid)
    {
        $this->db->where('sid', $sid)->update('portal_job_restriction', array('status' => 'rejected'));
    }

    function get_career_site_only_companies()
    {
        $this->db->select('sid, per_job_listing_charge, career_site_listings_only');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_site_listings_only', 1);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_paid_jobs($career_site_company_sid)
    {
        $this->db->select('portal_job_listings.sid as jobId, expiry_date');
        $product_ids = array(1, 2, 3, 4, 5, 21, 38);
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where_in('product_sid', $product_ids);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.job_sid');

        if (!empty($career_site_company_sid)) {
            $this->db->where_not_in('portal_job_listings.user_sid', $career_site_company_sid);
        }

        $record_obj = $this->db->get('jobs_to_feed');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }
    function get_all_company_jobs_ams($paid_jobs, $country = NULL, $state = NULL, $city = NULL, $categoryId = NULL, $keyword = NULL, $career_site_company_sid = array(), $limit = null, $offset = null, $count_record = false)
    {
        //        echo $country.' - '.$state.' - '.$city.' - '.$categoryId.' - '.$keyword.'';
        $this->db->select('portal_job_listings.*, 
                users.CompanyName, users.YouTubeVideo, 
                users.Logo, users.ContactName, 
                users.YouTubeVideo, portal_employer.sub_domain, portal_employer.job_title_location, 
                portal_employer.domain_type, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);

        if (!empty($paid_jobs)) {
            $this->db->where_not_in('portal_job_listings.sid', $paid_jobs);
        }

        if (!empty($career_site_company_sid)) {
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

        // if ($limit !== null && $offset !== null) {
        //     $this->db->limit($limit, $offset);
        // }

        $this->db->where('users.active', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('activation_date', 'DESC');
        $this->db->group_by('portal_job_listings.sid');
        $this->db->from('portal_job_listings');

        if ($count_record) {
            $record_arr = $this->db->count_all_results();
        } else {
            $record_obj = $this->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        }

        //        echo '<br>'.'<br>'.'<br>'.'<br>'.$this->db->last_query(); exit;
        return $record_arr;
    }

    function filters_of_active_jobs_of_companies($career_site_company_sid = array())
    {
        $this->db->select('portal_job_listings.Location_Country, portal_job_listings.Location_State, portal_job_listings.JobCategory, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);

        if (!empty($career_site_company_sid)) {
            $this->db->where_not_in('portal_job_listings.user_sid', $career_site_company_sid);
        }

        $this->db->where('users.active', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        $this->db->from('portal_job_listings');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function GetAllCategories($ids = [])
    {
        if (empty($ids)) {
            return [];
        }
        $this->db->select('sid,value');
        $this->db->where('field_sid', '198');
        $this->db->from('listing_field_list');
        if (!empty($ids)) {
            $this->db->where_in('sid', $ids);
        }
        $results = $this->db->get()->result_array();
        //
        if (empty($results)) {
            return [];
        }
        //
        $t = [];
        //
        foreach ($results as $value) {
            $t[$value['sid']] = $value['value'];
        }
        //
        return $t;
    }

    function GetStatesWithCountries($ids = [])
    {
        //
        if (empty($ids)) {
            return ['States' => [], 'CountryWithStates' => []];
        }
        $this->db->select('sid, country_sid, state_name');
        $this->db->order_by('state_name', 'ASC');
        $this->db->from('states');
        if (!empty($ids)) {
            $this->db->where_in('sid', $ids);
        }
        $results = $this->db->get()->result_array();
        //
        if (empty($results)) {
            return [];
        }
        //
        $t = [];
        $t2 = [];
        //
        foreach ($results as $value) {
            $t[$value['sid']] = $value['state_name'];
            //
            if (!isset($t2[$value['country_sid']])) {
                $t2[$value['country_sid']] = [];
            }
            $t2[$value['country_sid']][] = ['sid' => $value['sid'], 'state_name' => $value['state_name']];
        }
        //
        return ['States' => $t, 'CountryWithStates' => $t2];
    }

    //
    public function GetActiveJobsCatCSC($companyId)
    {
        //
        $q = $this->db
            ->select('
            JobCategory,
            Location_Country,
            Location_State
        ')
            ->from('portal_job_listings')
            ->where_in('user_sid', $companyId)
            ->where('portal_job_listings.active', 1);
        //
        $results = $q->get();
        //
        $data = $results->result_array();
        $results->free_result();
        //
        if (empty($data)) {
            return [];
        }
        //
        $r = [];
        $r['countryIds'] = [];
        $r['categoryIds'] = [];
        $r['stateIds'] = [];
        //
        foreach ($data as $row) {
            //
            $r['countryIds'][$row['Location_Country']] = 1;
            $r['stateIds'][$row['Location_State']] = 1;
            $r['categoryIds'] = array_merge($r['categoryIds'], explode(',', $row['JobCategory']));
        }
        //
        $r['categoryIds'] = array_unique($r['categoryIds'], SORT_STRING);
        $r['countryIds'] = array_keys($r['countryIds']);
        $r['stateIds'] = array_keys($r['stateIds']);
        //
        return $r;
    }

    //
    public function getStoreData($storeIds)
    {
        //
        $stores = [];
        //
        $portalEmployer =
            $this->db
            ->select('user_sid, job_title_location, sub_domain, enable_company_logo')
            ->where_in('user_sid', $storeIds)
            ->get('portal_employer')
            ->result_array();
        //
        if ($portalEmployer) {
            foreach ($portalEmployer as $store) {
                if (!isset($stores[$store['user_sid']])) {
                    $stores[$store['user_sid']] = [];
                }
                //
                $stores[$store['user_sid']] = $store;
            }
        }
        //
        $users =
            $this->db
            ->select('sid, Logo')
            ->where_in('sid', $storeIds)
            ->get('users')
            ->result_array();
        //
        if ($users) {
            foreach ($users as $store) {
                if (!isset($stores[$store['user_sid']])) {
                    $stores[$store['user_sid']] = [];
                }
                $stores[$store['sid']]['Logo'] = $store['Logo'];
            }
        }

        //
        return $stores;
    }

    //
    public function getScreeningQuestionares($screeningQuestionIds)
    {
        //
        $ra = [];
        //
        $sqa =
            $this->db
            ->select('sid, name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail')
            ->where_in('sid', $screeningQuestionIds)
            ->from('portal_screening_questionnaires')
            ->get()
            ->result_array();
        //
        if (!$sqa) {
            return [];
        }
        //
        $questions = $this->getQuestionnaireQuestions($screeningQuestionIds);
        //
        foreach ($sqa as $sq) {
            //
            if (!isset($ra[$sq['sid']])) {
                $ra[$sq['sid']] = $sq;
                $ra[$sq['sid']]['questions'] = [];
                $ra[$sq['sid']]['questions_count'] = 0;
                $ra[$sq['sid']]['answers'] = [];
            }
            //
            $ra[$sq['sid']]['questions_count'] = isset($questions[$sq['sid']]) ? count($questions[$sq['sid']]['questions']) : 0;
            //
            if ($ra[$sq['sid']]['questions_count'] == 0) {
                continue;
            }
            $ra[$sq['sid']]['questions'] = $questions[$sq['sid']]['questions'];
        }
        //
        return $ra;
    }

    public function getQuestionnaireQuestions($screeningQuestionIds)
    {
        $questions = $this->db
            ->where_in('questionnaire_sid', $screeningQuestionIds)
            ->get('portal_questions')
            ->result_array();
        //
        if (empty($questions)) {
            return [];
        }
        //
        $ra = [];
        //
        foreach ($questions as $question) {
            //
            if (!isset($ra[$question['questionnaire_sid']])) {
                $ra[$question['questionnaire_sid']] = [
                    'questions' => []
                ];
            }
            //
            $ra[$question['questionnaire_sid']]['questions'][$question['sid']] = $question;
        }
        //
        return $ra;
    }

    public function getScreeningAnswers($screeningQuestionIds)
    {
        $answers = $this->db
            ->where_in('questions_sid', $screeningQuestionIds)
            ->get('portal_question_option')
            ->result_array();
        //
        if (empty($answers)) {
            return [];
        }
        //
        $ra = [];
        //
        foreach ($answers as $answer) {
            //
            if (!isset($ra[$answer['questions_sid']])) {
                $ra[$answer['questions_sid']] = [];
            }
            $ra[$answer['questions_sid']][] = $answer;
        }
        //
        return $ra;
    }

    /**
     * get the indeed source posting id
     *
     * @param int|string $jobId
     * @return array
     */
    public function getIndeedApplyButtonDetails(string $jobId): array
    {
        return $this
            ->db
            ->select([
                "sid",
                "indeed_posting_id"
            ])
            ->where([
                "job_sid" => $jobId,
                "tracking_key is null" => null,
                "is_deleted" => 0
            ])
            ->limit(1)
            ->get("indeed_job_queue_tracking")
            ->row_array();
    }
}
