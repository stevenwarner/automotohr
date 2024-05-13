<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set("memory_limit", "512M");

class Calendar extends Public_Controller
{
    private $limit;
    private $list_size;
    //
    public function __construct()
    {
        parent::__construct();
        $this->load->config('calendar_config');
        //
        $this->limit     = $this->config->item('calendar_opt')['calendar_history_limit'];
        $this->list_size = $this->config->item('calendar_opt')['calendar_history_list_size'];
        $this->load->model('calendar_model');
        $this->load->model('employee_model');
        $this->load->model('timeoff_model');
        $this->load->model("v1/Shift_model", "shift_model");

        require_once(APPPATH . 'libraries/aws/aws.php');
    }


    /**
     * Calendar, my events
     *
     * @param $event_sid Optional
     *
     * @return Void
     */
    public function my_events($event_sid = false)
    {
        //
        if ($this->call_old_event()) {
            // if($this->call_old_event() && get_employer_access_level($this->session->userdata('logged_in')['employer_detail']['sid']) != 'Employee'){
            $this->my_events_new($event_sid);
            return false;
        }

        $this->my_events_old();
    }

    public function tasks()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            redirect('calendar/my_events', 'refresh');
        }

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $this->load->model('users_model');
            $company_info = $data['session']['company_detail'];
            $action = $this->input->post('action'); //Check Which Action to Perform

            // added on: 27-03-2019
            if ($action != 'save_event') {
                // check if event exists in db
                $result = $this->db
                    ->select('sid')
                    ->where('sid', $this->input->post('event_id') ? $this->input->post('event_id') : ($this->input->post('sid') ? $this->input->post('sid') : $this->input->post('event_sid')))
                    ->get('portal_schedule_event');
                $result_arr = $result->row_array();
                $result = $result->free_result();

                if (!sizeof($result_arr)) {
                    echo 'Unable to update/delete event as Event already Deleted';
                    return false;
                }
            }

            switch ($action) { //Unified Tasks for Save Update
                case 'save_event':
                case 'update_event':
                case 'drop_update_event':
                    $event_sid = 0;
                    $eventaddpost = $_POST;
                    // echo '<pre>'; print_r($eventaddpost); exit;
                    $event_data = array();
                    // avaid null value to database
                    $event_data['interviewer_show_email'] = '';
                    $users_type = $eventaddpost['users_type']; //Get Applicant Information

                    if ($users_type == 'applicant') {
                        $users_sid = $eventaddpost['applicant_sid'];
                        $user_info = $this->calendar_model->get_applicant_detail($users_sid);
                    } else if ($users_type == 'employee') {
                        $users_sid = $eventaddpost['employee_sid'];
                        $user_info = $this->calendar_model->get_employee_detail($users_sid);
                    }

                    $event_data['applicant_job_sid'] = $users_sid;
                    $event_data['users_type'] = $users_type;
                    $event_data['applicant_email'] = $user_info['email'];
                    $email = $user_info['email'];
                    $verify_event_session = $this->calendar_model->verify_event_session($users_sid, $users_type, $company_id);
                    $attachment = ''; //Upload Attachment

                    if (isset($_FILES['messageFile']) && $_FILES['messageFile']['name'] != '') {
                        $file = explode(".", $_FILES["messageFile"]["name"]);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $attachment = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                        if ($_FILES['messageFile']['size'] == 0) {
                            $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                            break;
                        }

                        $aws = new AwsSdk();
                        $aws->putToBucket($attachment, $_FILES["messageFile"]["tmp_name"], AWS_S3_BUCKET_NAME);
                        $event_data['messageFile'] = $attachment;
                    }

                    if (isset($eventaddpost['address']) && !empty($eventaddpost['address']) && !is_null($eventaddpost['address'])) {
                        $this->calendar_model->save_company_address($company_id, $eventaddpost['address']);
                    }
                    break;
            }

            switch ($action) { //Perform Actual Action
                case 'cancel_event':
                    $event_sid = $this->input->post('event_id');
                    $this->calendar_model->cancel_event($event_sid);
                    $message = 'success';
                    break;
                case 'save_event':
                    foreach ($eventaddpost as $key => $value) {
                        if (
                            $key != 'action' &&
                            $key != 'date' &&
                            $key != 'applicant_sid' &&
                            $key != 'redirect_to' &&
                            $key != 'redirect_to_user_sid' &&
                            $key != 'redirect_to_job_list_sid' &&
                            $key != 'show_email' &&
                            $key != 'address_type' &&
                            $key != 'employee_sid' &&
                            $key != 'external_participants'
                        ) { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }

                            $event_data[$key] = $value;
                        } else if ($key == 'show_email' && is_array($value)) {
                            $event_data['interviewer_show_email'] = implode(',', $value);
                        }
                    }

                    $event_data['created_on'] = date('Y-m-d H:i:s');
                    $event_data['employers_sid'] = $employer_id;
                    $event_data['companys_sid'] = $company_id;
                    $event_date = DateTime::createFromFormat('m-d-Y', $eventaddpost['date'])->format('Y-m-d');
                    $event_data['date'] = $event_date;
                    $filePath = null;

                    if (isset($event_data['title'])) { //removing break from string
                        $event_data['title'] = remove_breaks_from_string($event_data['title']);
                    }

                    if (isset($event_data['subject'])) {
                        $event_data['subject'] = remove_breaks_from_string($event_data['subject']);
                    }

                    if (isset($event_data['message'])) {
                        $event_data['message'] = remove_breaks_from_string($event_data['message']);
                    }

                    if (isset($event_data['comment'])) {
                        $event_data['comment'] = remove_breaks_from_string($event_data['comment']);
                    }

                    if ($verify_event_session > 0) {
                        if ($last_id = $this->calendar_model->save_event($event_data)) {
                            $message = 'Event Added successfully, scheduled for ' . DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                            $event_sid = $this->db->insert_id();
                            $message = 'Event Added successfully, scheduled for ' . DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                            if ($this->call_old_event()) {
                                if ($this->input->is_ajax_request()) {
                                    $message = array('Message' => 'Event Added successfully, scheduled for ' . DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y'), 'EventId' => $last_id);
                                }
                            }
                        } else {
                            $message = 'Error: Sorry! there was an error, Please try again';
                        }
                    } else {
                        $message = 'Error: Sorry! The event cannot be added as it does not belong to your company.';
                    }

                    break;
                case 'update_event':
                case 'drop_update_event':
                    foreach ($eventaddpost as $key => $value) {
                        if (
                            $key != 'action' &&
                            $key != 'date' &&
                            $key != 'sid' &&
                            $key != 'redirect_to' &&
                            $key != 'redirect_to_user_sid' &&
                            $key != 'redirect_to_job_list_sid' &&
                            $key != 'show_email' &&
                            $key != 'applicant_sid' &&
                            $key != 'address_type' &&
                            $key != 'employee_sid' &&
                            $key != 'external_participants'
                        ) { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }

                            $event_data[$key] = $value;
                        } else if ($key == 'show_email' && is_array($value)) {
                            $event_data['interviewer_show_email'] = implode(',', $value);
                        }
                    }

                    $event_sid = $eventaddpost['sid'];
                    $timestamp = explode('-', $eventaddpost['date']);
                    $month = $timestamp[0];
                    $day = $timestamp[1];
                    $year = $timestamp[2];
                    $event_data['date'] = $year . '-' . $month . '-' . $day;
                    $event_data['applicant_email'] = $email;
                    $event_data['applicant_job_sid'] = $users_sid;

                    if (isset($event_data['title'])) { //removing break from string
                        $event_data['title'] = remove_breaks_from_string($event_data['title']);
                    }

                    if (isset($event_data['subject'])) {
                        $event_data['subject'] = remove_breaks_from_string($event_data['subject']);
                    }

                    if (isset($event_data['message'])) {
                        $event_data['message'] = remove_breaks_from_string($event_data['message']);
                    }

                    if (isset($event_data['comment'])) {
                        $event_data['comment'] = remove_breaks_from_string($event_data['comment']);
                    }

                    if (isset($event_data['messageFile'])) {
                        $event_data['messageFile'] = $attachment;
                    }

                    $event_data['event_status'] = 'confirmed';

                    // Check for date and reminder flag
                    // and set sent flag to 0
                    if (!$this->call_old_event()) {
                        if ($event_data['date'] >= date('Y-m-d') && (isset($event_data['reminder_flag']) ? $event_data['reminder_flag'] : true)) $event_data['sent_flag'] = 0;
                    }

                    if ($this->calendar_model->update_event($event_sid, $event_data)) {
                        $event_date = DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                        $message = 'Event updated successfully, it is scheduled on ' . $event_date;
                    } else {
                        $message = 'Error: Sorry! there was an error, Please try again';
                    }

                    break;
                case 'delete_event':
                    $event_sid = $event_id = $this->input->post('event_sid');
                    $this->calendar_model->deleteEvent($event_id);
                    $message = 'Event Successfully Deleted!';
                    break;
                    // added on: 02-04-2019
                    // handle drag & resize
                case 'drag_update_event':
                    $this->calendar_model->update_event_by_id(
                        $this->input->post('sid'),
                        $this->input->post('date'),
                        $this->input->post('eventstarttime'),
                        $this->input->post('eventendtime')
                    );
                    //
                    $message = "Event updated successfully!.";
                    // set event_id for ICS
                    $event_sid = $this->input->post('sid');
                    break;
            }

            // echo $message;

            // added on: 02-04-2019
            // don't delete the participents
            // for drag and resize events
            if ($action != 'drag_update_event') {

                $redirect_to = $this->input->post('redirect_to');

                $this->calendar_model->remove_all_external_participants($company_id, $employer_id, $event_sid); //Save External Participants - Start

                if (empty($redirect_to)) {
                    $external_participants = $this->input->post('external_participants');

                    if (!is_null($external_participants) && !empty($external_participants)) {
                        $external_participants = json_decode($external_participants, true);
                    }
                } else {
                    $external_participants = $this->input->post('external_participants');
                }

                if (!is_null($external_participants) && !empty($external_participants)) {
                    foreach ($external_participants as $external_participant) {
                        $name = trim($external_participant['name']);
                        $email = trim($external_participant['email']);
                        $show_email = isset($external_participant['show_email']) ? $external_participant['show_email'] : 0;

                        if (!empty($name) && !empty($email)) {
                            $employee_data = $this->calendar_model->check_if_email_is_of_an_employee($company_id, $email);

                            if (empty($employee_data) || $employee_data == false) {
                                $this->calendar_model->add_event_external_participants($company_id, $employer_id, $event_sid, $name, $email, $show_email);
                            } else {
                                $employee_sid = $employee_data['sid'];
                                $this->calendar_model->append_participant_to_event($event_sid, $employee_sid);
                            }
                        }
                    }
                } //Save External Participants - End
            }

            //
            if ($action != 'delete_event') { //Generate Updated .ics file
                $destination = APPPATH . '../assets/ics_files/';
                $ics_file = generate_ics_file_for_event($destination, $event_sid);
                $event_details = $this->calendar_model->get_event_details($event_sid); //Get Event Details

                if ($event_details['users_type'] == 'applicant') { //Get User Information
                    $user_info = $this->calendar_model->get_applicant_detail($event_details['applicant_job_sid']);
                } else if ($event_details['users_type'] == 'employee') {
                    $user_info = $this->calendar_model->get_employee_detail($event_details['applicant_job_sid']);
                }

                $employers = array(); //Get Participants

                if (!empty($event_details['interviewer'])) {
                    $employers = $this->calendar_model->get_user_information($company_id, explode(',', $event_details['interviewer']));
                }

                if ($event_details['category'] == 'personal') {
                    $event_details['category'] = 'Personal Appointment';
                } else if ($event_details['category'] == 'other') {
                    $event_details['category'] = 'Appointment';
                }

                $user_tile = ''; //Applicant Tile

                if ($event_details['users_type'] == 'applicant') {
                    $user_tile .= '<p><b>' . ucwords($event_details['users_type']) . ' Name:</b> ' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . '</p>';
                    if (!empty($user_info['email'])) {
                        $user_tile .= '<p><b>Email:</b> ' . $user_info['email'] . '</p>';
                    }

                    if (!empty($user_info['phone_number'])) {
                        $user_tile .= '<p><b>Phone:</b> ' . $event_details['users_phone'] . '</p>';
                    }

                    if (!empty($user_info['city'])) {
                        $user_tile .= '<p><b>City:</b> ' . $user_info['city'] . '</p>';
                    }

                    $applicant_job_list_sid = 0;

                    if (isset($user_info['job_applications']) && !empty($user_info['job_applications'])) {
                        $applicant_job_list_sid = $user_info['job_applications'][0]['sid'];
                        $applicant_jobs_list = $event_details['applicant_jobs_list'];

                        if ($applicant_jobs_list != '' && $applicant_jobs_list != null) {
                            $applicant_jobs_array = explode(',', $applicant_jobs_list);
                        }

                        $user_tile .= '<p><b>Job(s) Applied:</b></p>';
                        $user_tile .= '<ol>';

                        if (!empty($applicant_jobs_array)) {
                            foreach ($user_info['job_applications'] as $job_application) {
                                $applicant_sid = $job_application['sid'];

                                if (in_array($applicant_sid, $applicant_jobs_array)) {
                                    $job_title = !empty($job_application['job_title']) ? $job_application['job_title'] : '';
                                    $desired_job_title = !empty($job_application['desired_job_title']) ? $job_application['desired_job_title'] : '';

                                    if (!empty($job_title)) {
                                        $title = $job_title;
                                    } else if (!empty($desired_job_title)) {
                                        $title = $desired_job_title;
                                    } else {
                                        continue;
                                    }

                                    $user_tile .= '<li>' . $title . '</li>';
                                    $applicant_job_list_sid = $job_application['sid'];
                                }
                            }
                        } else {
                            $job_application = $user_info['job_applications'];
                            $job_application_last_index = count($job_application) - 1;

                            for ($i = 0; $i < count($job_application); $i++) {
                                $applicant_sid = $job_application[$i]['sid'];

                                $job_title = !empty($job_application[$i]['job_title']) ? $job_application[$i]['job_title'] : '';
                                $desired_job_title = !empty($job_application[$i]['desired_job_title']) ? $job_application[$i]['desired_job_title'] : '';

                                if (!empty($job_title)) {
                                    $title = $job_title;
                                } else if (!empty($desired_job_title)) {
                                    $title = $desired_job_title;
                                } else {
                                    continue;
                                }

                                $user_tile .= '<li>' . $title . '</li>';
                                $applicant_job_list_sid = $job_application[$job_application_last_index]['sid'];
                            }
                        }

                        $user_tile .= '</ol>';
                    }

                    $resume_btn = '';

                    if (isset($user_info['resume']) && !empty($user_info['resume'])) {
                        $resume_btn = '<a href="' . AWS_S3_BUCKET_URL . $user_info['resume'] . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '">Download Resume</a>';
                    }

                    $profile_btn = '<a href="' . base_url('applicant_profile') . '/' . $user_info['sid'] . '/' . $applicant_job_list_sid . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" >View Profile</a>';
                    $user_tile .= '<p>' . $resume_btn . ' ' . $profile_btn . '</p>';
                    $user_tile .= '<hr />';
                }

                $event_category = '';

                switch ($event_details['category']) {
                    case 'interview':
                        $event_category = 'In Person Interview';
                        break;
                    case 'interview-phone':
                        $event_category = 'Phone Interview';
                        break;
                    case 'interview-voip':
                        $event_category = 'Voip Interview';
                        break;
                    case 'call':
                        $event_category = 'Call';
                        break;
                    case 'email':
                        $event_category = 'Email';
                        break;
                    case 'meeting':
                        $event_category = 'Meeting';
                        break;
                    case 'personal':
                        $event_category = 'Personal Appointment';
                        break;
                    case 'other':
                        $event_category = 'Other Appointment';
                        break;
                }

                $from = FROM_EMAIL_EVENTS; //Create Email Notification
                $from_name = ucwords($company_info["CompanyName"]);
                $message_hf = message_header_footer($company_id, ucwords($company_info["CompanyName"]));
                $applicant_name = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                $email_subject = ucwords($event_category) . ' - Has Been ' . ucwords($event_details['event_status']) . ' ' . ucwords($company_info['CompanyName']);
                $email_message = $message_hf['header'];
                $email_message .= '<div>';
                $email_message .= '<p><b>Dear {{user_name}},</b></p>';

                if ($action == 'update_event' || $action == 'drop_update_event') {
                    $email_message .= '<p><b>Your Event Details Have been Changed Please update your calendar as per below information. </b></p>';
                }

                $email_message .= '<p>' . ucwords($event_category) . ' has been ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                $email_message .= '<br />';
                $email_message .= ' '; //Applicant Tile
                $email_message .= '{{user_tile}}';
                $email_message .= ' ';
                $email_message .= '<p><b>Event Details are as follows:</b></p>'; //event information
                $email_message .= '<p><b>Event :</b> ' . ucwords($event_category) . ' With "' . ucwords($company_info['CompanyName']) . '"</p>';
                $email_message .= '<p><b>Date :</b> ' . date_with_time($event_details['date']) . '</p>';
                $email_message .= '<p><b>Time :</b> From ' . $event_details['eventstarttime'] . ' To ' . $event_details['eventendtime'] . '</p>';
                $email_message .= '<hr />';
                $comment_tile = ''; //Comment Check

                if ($event_details['commentCheck'] == 1 && !empty($event_details['comment'])) {
                    $comment_tile .= '<p><b>Comment From Employer</b></p>';
                    $comment_tile .= '<p><b>Comment:</b> ' . $event_details['comment'] . '</p>';
                    $comment_tile .= '<hr />';
                }

                $email_message .= ' '; //Comment Tile
                $email_message .= '{{comment_tile}}';
                $email_message .= ' ';

                if ($event_details['messageCheck'] == 1 && !empty($event_details['subject']) && !empty($event_details['message'])) { //Message Check
                    $email_message .= '<p><b>Message From Employer</b></p>';
                    $email_message .= '<p><b>Subject:</b> ' . $event_details['subject'] . '</p>';
                    $email_message .= '<p><b>Message:</b> ' . $event_details['message'] . '</p>';

                    if (!empty($event_details['messageFile']) && $event_details['messageFile'] != 'undefined') {
                        $email_message .= '<p><b>Attachment:</b> <a href="' . AWS_S3_BUCKET_URL . $event_details['messageFile'] . '" target="_blank">' . $event_details['messageFile'] . '</a></p>';
                    }

                    $email_message .= '<hr />';
                }

                //Meeting Call Details
                if ($event_details['goToMeetingCheck'] == 1 && (!empty($event_details['meetingId']) || !empty($event_details['meetingCallNumber']) || !empty($event_details['meetingURL']))) {
                    $email_message .= '<p><b>Meeting Call Details Are As Follows:</b></p>';

                    if (!empty($event_details['meetingId'])) {
                        $email_message .= '<p><b>Meeting ID:</b> ' . $event_details['meetingId'] . '</p>';
                    }

                    if (!empty($event_details['meetingCallNumber'])) {
                        $email_message .= '<p><b>Meeting Call Number:</b> ' . $event_details['meetingCallNumber'] . '</p>';
                    }

                    if (!empty($event_details['meetingURL'])) {
                        $email_message .= '<p><b>Meeting Call Url:</b> <a href="' . $event_details['meetingURL'] . '" target="_blank">' . $event_details['meetingURL'] . '</a></p>';
                    }

                    $email_message .= '<hr />';
                }

                $show_emails = array(); //Interviewers

                if (!empty($event_details['interviewer_show_email']) && !is_null($event_details['interviewer_show_email'])) {
                    $show_emails = explode(',', $event_details['interviewer_show_email']);
                }

                if (!empty($employers)) {
                    $email_message .= '<p><b>Your ' . $event_category . ' is ' . ucwords($event_details['event_status']) . ' with:</b></p>';
                    $email_message .= '<ul>';

                    if ($event_details['users_type'] == 'employee') {
                        $email_message .= '<li>' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . ' ( <a href="mailto:' . $user_info['email'] . '">' . $user_info['email'] . '</a> )' . '</li>';
                    }

                    foreach ($employers as $employer) {
                        $email = in_array($employer['sid'], $show_emails) ? ' ( <a href="mailto:' . $employer['email'] . '">' . $employer['email'] . '</a> )' : '';
                        $email_message .= '<li>' . ucwords($employer['first_name'] . ' ' . $employer['last_name']) . $email . '</li>';
                    }

                    $event_external_participants = $this->calendar_model->get_event_external_participants($event_sid); //Get External Participants
                    $ext_participants = '';

                    if (!empty($event_external_participants)) {
                        foreach ($event_external_participants as $event_external_participant) {
                            $show_email = $event_external_participant['show_email'];

                            if ($show_email == 1) {
                                $email_link = '<a href="mailto:' . $event_external_participant['email'] . '">' . $event_external_participant['email'] . '</a>';
                                $participant = $event_external_participant['name'] . ' ( ' . $email_link . ' ) ';
                            } else {
                                $participant = $event_external_participant['name'];
                            }

                            $ext_participants .= '<li>' . $participant . '</li>';
                        }

                        $email_message .= $ext_participants;
                    }

                    $email_message .= '</ul>';
                    $email_message .= '<hr />';
                }


                if (!empty($event_details['address']) && $event_details['category'] != 'interview-phone' && $event_details['category'] != 'interview-voip') {
                    $map_url = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($event_details['address']) . "&zoom=13&size=400x400&key=" . GOOGLE_API_KEY . "&markers=color:blue|label:|" . urlencode($event_details['address']);
                    $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($event_details['address']) . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
                    $email_message .= '<p><b>Address:</b> ' . $event_details['address'] . ' </p>';
                    $email_message .= '<p> ' . $map_anchor . ' </p>';
                    $email_message .= '<hr />';
                }

                $email_message .= '</div>';
                $email_message .= $message_hf['footer'];
                $user_message = $email_message; //Send Email to Applicant
                $user_message = str_replace('{{user_name}}', $applicant_name, $user_message);
                $user_message = str_replace('{{user_tile}}', ' ', $user_message);
                $user_message = str_replace('{{comment_tile}}', ' ', $user_message);
                $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);
                $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $user_info['email'], $email_subject, $user_message, $from_name, $ics_file);

                foreach ($employers as $employer) { //Send Email To Employers
                    $user_message = $email_message;
                    $employer_name = ucwords($employer['first_name'] . ' ' . $employer['last_name']);
                    $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                    $user_message = str_replace('{{user_tile}}', $user_tile, $user_message);
                    $user_message = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                    $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);
                    $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $employer['email'], $email_subject, $user_message, $from_name, $ics_file);
                }

                if (!empty($event_external_participants)) { //Send Email To External Participants
                    foreach ($event_external_participants as $event_external_participant) {
                        $user_message = $email_message;
                        $employer_name = ucwords($event_external_participant['name']);
                        $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                        $user_message = str_replace('{{user_tile}}', $user_tile, $user_message);
                        $user_message = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                        $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);
                        $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $event_external_participant['email'], $email_subject, $user_message, $from_name, $ics_file);
                    }
                }
            }

            $redirect_to = $this->input->post('redirect_to');
            $redirect_to_user_sid = $this->input->post('redirect_to_user_sid');
            $redirect_to_job_list_sid = $this->input->post('redirect_to_job_list_sid');

            if (!empty($redirect_to)) {
                switch ($redirect_to) {
                    case 'applicant_profile':
                        $this->session->set_flashdata('message', $message);
                        redirect('applicant_profile/' . $redirect_to_user_sid . '/' . $redirect_to_job_list_sid, 'refresh');
                        break;
                    case 'employee_profile':
                        $this->session->set_flashdata('message', $message);
                        redirect('employee_profile/' . $redirect_to_user_sid, 'refresh');
                        break;
                    default:
                        echo $message;
                        break;
                }
            } else {
                if ($this->call_old_event()) {
                    if (is_array($message)) {
                        header('Content-Type: application/json');
                        echo json_encode($message);
                        exit(0);
                    }
                }
                echo $message;
            }
            exit;
        }
    }

    public function deleteEvent()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // get input id
        $event_id = $this->input->get('sid');
        $event_category = $this->calendar_model->get_event_column_by_event_id($event_id, 'category');
        // Check for training session
        if ($event_category == 'training-session') {
            $learning_center_training_sessions = $this->calendar_model->get_event_column_by_event_id($event_id, 'learning_center_training_sessions');
            $this->calendar_model->delete_training_session(
                $learning_center_training_sessions
            );
        }
        $this->calendar_model->deleteEvent($event_id);
        echo 'Event Deleted';
    }

    private function log_and_send_email_with_attachment($from, $to, $subject, $body, $senderName, $file_path)
    {
        // edited on: 27-03-2019
        $emailData = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $senderName
        );
        save_email_log_common($emailData);
        if (base_url() != STAGING_SERVER_URL) {
            sendMailWithAttachmentRealPath($from, $to, $subject, $body, $senderName, $file_path);
        }
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
            $view_data = array();
            $view_data['company_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
            if (!empty($data['session']['employer_detail']['timezone']))
                $view_data['employer_timezone'] =   $data['session']['employer_detail']['timezone'];
            else
                $view_data['employer_timezone'] = $view_data['company_timezone'];

            if ($this->form_validation->run() == false) {
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'get_event_edit_form':
                        $event_sid = $this->input->post('event_sid');
                        $user_sid = $this->input->post('user_sid');
                        $redirect_to = $this->input->post('redirect_to');
                        $redirect_to_user_sid = $this->input->post('redirect_to_user_sid');
                        $redirect_to_job_list_sid = $this->input->post('redirect_to_job_list_sid');
                        $event = $this->calendar_model->get_event_details($event_sid);

                        if (!empty($event)) {
                            $employees = $this->calendar_model->getCompanyAccounts($event['companys_sid']);
                            $view_data['event'] = $event;
                            $view_data['employees'] = $employees;
                            $view_data['employer_sid'] = $employer_sid;
                            $view_data['user_sid'] = $user_sid;
                            $view_data['redirect_to'] = $redirect_to;
                            $view_data['redirect_to_user_sid'] = $redirect_to_user_sid;
                            $view_data['redirect_to_job_list_sid'] = $redirect_to_job_list_sid;
                            //Get External Participants // hassan working
                            $users_type = $event['users_type'];
                            $applicant_jobs = array();

                            if ($users_type == 'applicant') {
                                $app_sid = $event['applicant_job_sid'];
                                $applicant_jobs = $this->calendar_model->get_all_applicant_jobs($app_sid, $event['companys_sid']);
                            }

                            $view_data['applicant_jobs'] = $applicant_jobs;
                            $event_external_participants = $this->calendar_model->get_event_external_participants($event_sid);
                            $view_data['external_participants'] = $event_external_participants;
                            $addresses = $this->calendar_model->get_company_addresses($event['companys_sid']);
                            $view_data['addresses'] = $addresses;
                            $this->load->view('calendar/edit_event_form_partial.php', $view_data);
                        }
                        break;
                    case 'get_past_events':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $events = $this->calendar_model->get_employee_events($company_sid, $user_sid, 'past');
                        $view_data['events'] = $events;
                        $employees = $this->calendar_model->getCompanyAccounts($company_sid);
                        $view_data['employees'] = $employees;
                        $view_data['employer_id'] = $user_sid;
                        $this->load->view('calendar/' . ($this->call_old_event() ? 'list_events_partial_ajax' : 'list_events_partial'), $view_data);
                        break;
                    case 'get_event_external_participants':
                        $event_sid = $this->input->post('event_sid');
                        $event_external_participants = $this->calendar_model->get_event_external_participants($event_sid);
                        $view_data['external_participants'] = $event_external_participants;
                        $this->load->view('calendar/external_participants_partial', $view_data);
                        break;
                }
            }
        }
    }

    public function event_reminder_cron($vf_key = null)
    {
        if ($vf_key != 'dwwbtQzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk5HV7ZNCbu2ZfkjFVLHWINnM5uzMkUfIiINdZ19NJj') return false;
        // $start = microtime(true);

        $today_events = $this->calendar_model->fetch_all_today_events();
        if (!$today_events) return false;
        // echo '<pre>';
        // echo microtime(true) - $start.'<br />';
        foreach ($today_events as $event) {
            if ($event['event_status'] == 'cancelled') continue;
            // echo date('H:i', strtotime('+'.$event['duration'].' minutes', strtotime(date('H:i')))) . ' ' . $event['duration'] . ' ' . date('H:i') . ' ';
            // echo date('H:i ', strtotime($event['eventstarttime'])) . '<br>';
            //date_default_timezone_set('America/Los_Angeles');
            $cur_time_duration = date('H:i', strtotime('+' . $event['duration'] . ' minutes', strtotime(date('H:i'))));
            $event_start_time = date('H:i', strtotime($event['eventstarttime']));
            // echo '<br>'.$cur_time_duration . ' ' . date('H:i') . ' ';
            // echo $event_start_time. '<br>';

            if (strtotime($cur_time_duration) == strtotime($event_start_time)) {
                // Updated on: 23-04-2019
                // Fetch interviews, non-employee interviewers, applicant || employee
                // data
                $email_list = $this->calendar_model->get_event_email_list(
                    $event['sid'],
                    $event['users_type'],
                    $event['interviewer'],
                    $event['applicant_job_sid']
                );
                //
                if (!sizeof($email_list)) continue;
                //
                ics_files(
                    $event['sid'],
                    $event['companys_sid'],
                    array('CompanyName' => $event['CompanyName']),
                    'send_cron_reminder_emails',
                    $email_list
                );
                sendMail(
                    'notifications@automotohr.com',
                    'dev@automotohr.com',
                    'Auto Calendar Event Reminder executed',
                    'it is auto executed at ' . date('Y-m-d H:i:s') . '/n<br>Reminder sent to: ' . $event['applicant_email'],
                    'AutomotoHR',
                    'dev@automotohr.com'
                );
                $this->calendar_model->update_event_reminder_sent_status($event['sid']);
                echo 'Done';
                // $event_category = '';

                // switch ($event['category']) {
                //     case 'interview':
                //         $event_category = 'In Person Interview';
                //         break;
                //     case 'interview-phone':
                //         $event_category = 'Phone Interview';
                //         break;
                //     case 'interview-voip':
                //         $event_category = 'Voip Interview';
                //         break;
                //     case 'call':
                //         $event_category = 'Call';
                //         break;
                //     case 'email':
                //         $event_category = 'Email';
                //         break;
                //     case 'meeting':
                //         $event_category = 'Meeting';
                //         break;
                //     case 'personal':
                //         $event_category = 'Personal Appointment';
                //         break;
                //     case 'other':
                //         $event_category = 'Other Appointment';
                //         break;
                // }
                // $employers = array(); //Get Participants

                // if (!empty($event['interviewer'])) {
                //     $employers = $this->calendar_model->get_user_information($event['companys_sid'], explode(',', $event['interviewer']));
                // }
                // $replacement_array = array();
                // $replacement_array['company_name'] = ucwords($event['CompanyName']);
                // $replacement_array['event_type'] = $event_category;
                // $replacement_array['event_title'] = $event['title'];
                // $replacement_array['duration'] = $event['duration'];
                // $replacement_array['participant_name'] = $event['name'];
                // $replacement_array['start_time'] = $event['eventstarttime'];

                // log_and_send_templated_email(EVENT_REMINDER_NOTIFICATION, $event['applicant_email'], $replacement_array);
                // sendMail('dev@automotohr.com', 'dev@automotohr.com', 'Auto Calendar Event Reminder executed', 'it is auto executed at '.date('Y-m-d H:i:s').'/n<br>Reminder sent to: '.$event['applicant_email'], 'AutomotoHR', 'dev@automotohr.com');
                // if (!empty($employers)) {
                //     foreach($employers as $employee){
                //         $replacement_array['participant_name'] = $employee['first_name'].' '.$employee['last_name'];

                //         log_and_send_templated_email(EVENT_REMINDER_NOTIFICATION, $employee['email'], $replacement_array);
                //     }
                // }
            }
        }
    }

    /**
     * get events from events
     * added at: 28-03-2019
     *
     * accepts POST
     * @post type String (month, week, day)
     * @post year String
     * @post month String
     * @post day String
     * @post week_start String Optional (m-d)
     * @post week_end String  Optional (m-d)
     * @post company_id Integer
     * @post employer_id Integer
     *
     * @return JSON
     *
     */
    function get_events()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'POST' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        $employer_id = $data['session']['employer_detail']['sid'];
        $access_level = strtolower(get_employer_access_level($employer_id));
        $event_type = ($access_level == 'admin' || $access_level == 'hiring manager') ? 'all' : 'employee';
        // check for view type
        $events = $this->calendar_model->get_events(
            $this->input->post('type'),
            $this->input->post('year'),
            $this->input->post('month'),
            $this->input->post('day'),
            $this->input->post('week_start'),
            $this->input->post('week_end'),
            $company_id,
            $employer_id,
            $event_type,
            $access_level
        );
        $pto_user_access = get_pto_user_access($company_id, $employer_id);
        if (checkIfAppIsEnabled('timeoff')) {
            $timeoffs = $this->timeoff_model->getIncomingRequestByPermForCalendar(
                $this->input->post('type'),
                $this->input->post('year'),
                $this->input->post('month'),
                $this->input->post('day'),
                $this->input->post('week_start'),
                $this->input->post('week_end'),
                $company_id,
                $employer_id,
                $event_type,
                $access_level,
                $data['session']['employer_detail'],
                $this->input->post('start_date'),
                $this->input->post('end_date')
            );
            $events = array_merge(!is_array($events) ? array() : $events, $timeoffs);
        }

        //
        $shifts = $this->shift_model->getShiftsForCalendar(
            $company_id,
            $data['session']['employer_detail'],
            $this->input->post('start_date'),
            $this->input->post('end_date')
        );

        $events = array_merge(!is_array($events) ? array() : $events, $shifts);



        if (checkIfAppIsEnabled('performance_management')) {
            $this->load->model('performance_management_model', 'pmm');
            // $goals = $this->pmm->getGoalsByPerm(
            //     $this->input->post('type'),
            //     $this->input->post('year'),
            //     $this->input->post('month'),
            //     $this->input->post('day'),
            //     $this->input->post('week_start'),
            //     $this->input->post('week_end'),
            //     $company_id,
            //     $employer_id,
            //     $event_type,
            //     $access_level,
            //     $data['session']['employer_detail']
            // );
            // $events = array_merge(!is_array($events) ? array() : $events ,$goals);
        }
        // Get companys public holidays
        $publicHolidays = $this->timeoff_model->getCompanyPublicHolidays(
            $this->input->post('type'),
            $this->input->post('year'),
            $this->input->post('month'),
            $this->input->post('day'),
            $this->input->post('week_start'),
            $this->input->post('week_end'),
            $company_id
        );
        $events = array_merge(!is_array($events) ? array() : $events, $publicHolidays);
        $return_array['Redirect'] = FALSE;
        //





        if (!$events) {
            $return_array['Response'] = 'No events found';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Success';
        $return_array['Events'] = $events;
        $this->response($return_array);
    }

    /**
     * get company address
     * added at: 28-03-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function get_address()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $address = $this->calendar_model->fetch_company_addresses($company_id);
        if (!$address) {
            $return_array['Response'] = 'no addresses found.';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Address'] = $address;
        $this->response($return_array);
    }

    /**
     * Get applicants
     * Added on: 28-03-2019
     * Updated on: 06-05-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function get_applicants($query)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $query = urldecode($query);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $applicants = $this->calendar_model->get_applicants_by_query($company_id, $query);
        // $company_timezone = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
        // foreach($applicants as $key => $applicant){
        //     $applicants[$key]['value'] = $applicant['value']. " (".$company_timezone.")";
        // }
        $this->response($applicants);
    }

    /**
     * get employees
     * added at: 01-04-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function get_employees($query)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        $employer_id = $data['session']['employer_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $this->response($this->calendar_model->get_employees_by_query($company_id, $query));
    }

    /**
     * get interviewers
     * added at: 01-04-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function get_interviewers($query)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'POST' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $this->response($this->calendar_model->get_employees_by_query($company_id, $query, $this->input->post('ids'), true));
    }

    /**
     * get companies
     * added at: 28-03-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function get_employers()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $employers = $this->calendar_model->get_company_accounts($company_id);
        if (!$employers) {
            $return_array['Response'] = 'no employers found.';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Employers'] = $employers;
        $this->response($return_array);
    }

    /**
     * get events from events
     * added at: 28-03-2019
     *
     * @post type event_id Integer
     *
     * @return JSON
     *
     */
    function get_event_detail($event_id)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $company_id = $data['session']['company_detail']['sid'];
        $employer_id = $data['session']['employer_detail']['sid'];
        $access_level = strtolower(get_employer_access_level($employer_id));
        // check for view type
        $event = $this->calendar_model->get_event_detail($event_id);
        $company_timezone = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;

        foreach ($event['interviewers_details'] as $key => $interviewer) {
            $timezone = !empty($interviewer['timezone']) ? $interviewer['timezone'] : $company_timezone;
            $event['interviewers_details'][$key]['value'] = $interviewer['full_name'] . " (" . $timezone . ") (" . $interviewer['employee_type'] . ")";
        }
        //
        $return_array['Redirect'] = FALSE;
        //
        if (!$event) {
            $return_array['Response'] = 'No event found';
            $this->response($return_array);
        }
        //
        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Success';
        $return_array['Event'] = $event;
        //
        $this->response($return_array);
    }

    /**
     *
     * OLD events dashboard
     * tobo deleted
     *
     */
    private function my_events_old()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['title'] = 'My Events';
            $access_level = get_employer_access_level($employer_id); //checking access level to show all events starts
            $applicantEvents =  array();

            if ($access_level == 'Admin') {
                $applicantEvents = $this->calendar_model->get_events_applicants($company_id);
                $employeeEvents = $this->calendar_model->get_events_employee($employer_id);
                $events = array_merge($applicantEvents, $employeeEvents);
            } else {
                $employeeEvents = $this->calendar_model->get_events_employee_for_employeer($employer_id);
                $events = $employeeEvents;
            }

            $data['applicantEvents'] = $applicantEvents;
            $data['events'] = $events;
            $addresses = $this->calendar_model->get_company_addresses($company_id);
            $data['addresses'] = $addresses;
            $data['applicants'] = $this->calendar_model->get_applicants($company_id); //checking access level to show all events ends
            $data['applicant_jobs'] = $this->calendar_model->get_all_company_applicants($company_id);
            // $result = $this->calendar_model->get_applicant_jobs('68062');
            // echo '<pre>'; print_r($result); echo '</pre>';
            $data['employer_id'] = $employer_id;
            $data['company_accounts'] = $this->calendar_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
            $data['employee'] = $data['session']['employer_detail'];
            $load_view = false;
            // echo '<pre>';
            // print_r($data['events']);
            // $interviewers_name = '';
            // foreach ($events as $event) {
            //     $interviewers = explode(',', $event['interviewer']);
            //     if (sizeof($interviewers) > 0) {
            //         foreach ($interviewers as $interviewer) {
            //             $key = array_search($interviewer, array_column($data['company_accounts'], 'sid'));
            //             $viewer_data = $data['company_accounts'][$key];
            //             $interviewers_name = $interviewers_name . $viewer_data['first_name'] .' '. $viewer_data['last_name'];
            //         }
            //     }
            // }
            // echo $interviewers_name;
            // die();
            if (strtolower($access_level) == 'employee') {
                $load_view                                                      = check_blue_panel_status(false, 'self');
            }

            // Check and set the company sms module
            // phone number
            company_sms_phonenumber(
                $data['session']['company_detail']['sms_module_status'],
                $company_id,
                $data,
                $this
            );
            //
            $data['access_level'] = $access_level;
            $data['load_view'] = $load_view;
            $this->load->view('main/header', $data);
            $this->load->view('calendar/my_events_new');
            // $this->load->view('onboarding/calendar');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /**
     * Events calendar
     * called from events
     *
     * @param $event_token Optional
     *
     * @return Void
     *
     */
    private function my_events_new($event_token)
    {
        // Check for login session
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), "refresh");
        //3497

        // Set default array
        $data['show_event'] = array();
        // Check for event sid
        if ($event_token) {
            // Load encrypt class
            $this->load->library('encrypt');
            // Decode event_token
            $event_token = $this->encrypt->decode(str_replace('$eb$eb$', '/', $event_token));
            // Explode event token
            $event_token_array = explode(':', $event_token);
            // Check for sid and company id
            if (is_array($event_token_array) && isset($event_token_array[0], $event_token_array[1])) {
                // Set event sid and company id
                $event_sid = trim($event_token_array[0]);
                $company_id = trim($event_token_array[1]);
                // Fetch the event date
                $event_date = $this->calendar_model->get_event_column_by_event_id($event_sid, 'date', array('companys_sid' => $company_id));
                if ($event_date) $data['show_event'] = array('event_sid' => $event_sid, 'event_date' => $event_date);
            }
        }
        //
        $data['title'] = 'My Events';
        $data['session'] = $this->session->userdata('logged_in');
        $data['company_id'] = $company_id = $data['session']['company_detail']['sid'];
        $security_sid = $data['session']['employer_detail']['sid'];
        $data['security_details'] = $security_details = db_get_access_level_details($security_sid);
        //
        $data['employer_id'] = $employer_id = $data['session']['employer_detail']['sid'];
        $data['employee'] = $data['session']['employer_detail'];
        check_access_permissions($security_details, 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        $data['access_level'] = $access_level = get_employer_access_level($employer_id); //checking access level to show all events starts
        $load_view = false;
        $data['employer_timezone'] = $this->calendar_model->get_employee_detail($employer_id)['timezone'];
        // get employees and company account
        $employees = $this->calendar_model->get_company_accounts($company_id); //fetching list of all sub-accounts

        $data['company_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
        if (empty($data['employer_timezone']))
            $data['employer_timezone'] = $data['company_timezone'];

        foreach ($employees as $key => $employee) {
            $employees[$key]['full_name'] = remakeEmployeeName($employee);
        }
        $data['employees'] = $data['interviewers'] = $employees;
        //
        if (strtolower($access_level) == 'employee') $load_view = check_blue_panel_status(false, 'self');

        // TODO
        // For on-boarding event

        $data['load_view'] = $load_view;

        $this->load->view('main/header', $data)
            ->view('calendar/my_events_new_ajax')
            ->view('main/footer');
    }

    /**
     * Check for old event check
     *
     * @return Bool
     */
    private function call_old_event()
    {
        $calendar_opt = $this->config->item('calendar_opt');
        if ($calendar_opt['show_new_calendar_to_all'])
            return true;
        if (
            ($calendar_opt['old_event_check'] && !$calendar_opt['ids_check'] && in_array($this->input->ip_address(), $calendar_opt['remote_ips'])) ||
            ($calendar_opt['old_event_check'] && $calendar_opt['ids_check'] && in_array($this->session->userdata('logged_in')['company_detail']['sid'], $calendar_opt['allowed_ids']))
        ) {
            return true;
        }

        return false;
    }

    /**
     * print data
     *
     * @param $e Array|String...
     * @param $print Bool Optional
     * @param $die Bool Optional
     */
    private function _e($e, $print = FALSE, $die = FAlSE)
    {
        echo '<pre>';
        if ($print) echo '<br />*****************************<br />';
        if (is_array($e)) print_r($e);
        else if (is_object($e)) var_dump($e);
        else echo ($e);
        if ($print) echo '<br />*****************************<br />';
        echo '</pre>';
        if ($die) exit(0);
    }

    /**
     * Send back json
     *
     * @param $array Array
     */
    private function response($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
        exit(0);
    }

    /**
     * Handles event triggers
     * save, update, delete,
     * cancel, reschedule
     *
     * Accepts POST
     *
     * @return JSON
     */
    function event_handler()
    {
        // Check if direct access made
        if ($this->input->server('REQUEST_METHOD') == 'GET') redirect('calendar/my_events', 'refresh');
        // Set default array
        $res = array();
        $res['Status'] = FALSE;
        $res['Redirect'] = TRUE;
        $res['Response'] = 'User session has expired. Please, login again';
        // Check if user session is expired
        if (!$this->session->userdata('logged_in')) $this->response($res);
        $res['Redirect'] = FALSE;
        // Get session details
        $data['session'] = $this->session->userdata('logged_in');
        $company_info = $data['session']['company_detail'];
        $company_id   = $company_info['sid'];
        $employer_id  = $data['session']['employer_detail']['sid'];
        // Check Which Action to Perform
        $action = $this->input->post('action');
        // Loads users model
        $this->load->model('users_model');

        // Difference array reference
        $difference_array = $this->input->post('diff', true);


        // Send reminder emails
        if ($action == 'send_reminder_emails') {
            ics_files($this->input->post('event_id'), $company_id, $company_info, $action, $this->input->post('emails'));
            // $this->send_reminder_emails($res, $company_info);
            exit(0);
        }

        // Check if event is already deleted
        if ($action != 'save_event' && $action != 'save_personal_event') {
            $result = $this->db
                ->select('sid')
                ->where('sid', $this->input->post('event_id') ? $this->input->post('event_id') : $this->input->post('sid'))
                ->get('portal_schedule_event');
            $result_arr = $result->row_array();
            $result     = $result->free_result();
            //
            if (!sizeof($result_arr)) {
                $res['Response'] = 'Unable to update/delete event as Event already Deleted';
                $this->response($res);
            }
        }
        // Unified Tasks for Save & Update
        switch ($action) {
            case 'save_event':
            case 'update_event':
            case 'drop_update_event':
            case 'reschedule_event':
                // Set default values
                $event_sid = 0;
                $attachment = '';
                $event_data = array();
                $event_add_post = $_POST;
                unset($event_add_post['diff']);
                // Avoid null value to database
                $event_data['interviewer_show_email'] = '';
                // Get Applicant Information
                $users_type = $event_add_post['users_type'];
                // Skip for Personal type
                if ($event_add_post['users_type'] != 'personal') {
                    // Set user sid
                    $users_sid = $event_add_post['applicant_sid'];
                    if ($event_add_post['users_type'] == 'applicant' && $event_add_post['applicant_sid'] == $event_add_post['employee_sid']) {
                        $event_add_post['employee_sid'] = $employer_id;
                        $event_add_post['employers_sid'] = $employer_id;
                    }
                    // $users_sid = $event_add_post[$users_type == 'applicant' ? 'applicant_sid' : 'employee_sid'];
                    // Fetch info
                    $user_info = $users_type == 'applicant'
                        ? $this->calendar_model->get_applicant_detail($users_sid)
                        : $this->calendar_model->get_employee_detail($users_sid);

                    // Set event data
                    $event_data['applicant_job_sid'] = $users_sid;
                    $event_data['employers_sid'] = $event_add_post['employee_sid'];
                    $event_data['users_type'] = $users_type;
                    $event_data['applicant_email'] = $user_info['email'];
                    $email = $user_info['email'];
                    // Verify event details
                    $verify_event_session = $this->calendar_model->verify_event_details($users_sid, $users_type, $company_id);
                }
                // Check if message check is on
                // only then check for attached file
                if ($event_add_post['messageCheck'] == 1) {
                    if (isset($_FILES['messageFile']) && $_FILES['messageFile']['name'] != '') {
                        $file = explode(".", $_FILES["messageFile"]["name"]);
                        if (!isset($file[1])) $file[1] = '.txt';
                        $file_name = str_replace(" ", "-", $file[0]);
                        $attachment = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                        if ($_FILES['messageFile']['size'] == 0) {
                            $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                            break;
                        }

                        $aws = new AwsSdk();
                        $aws->putToBucket($attachment, $_FILES["messageFile"]["tmp_name"], AWS_S3_BUCKET_NAME);
                        $event_data['messageFile'] = $attachment;
                    }
                }
                // Check and save company address
                if (isset($event_add_post['address']) && !empty($event_add_post['address']) && !is_null($event_add_post['address'])) {
                    $this->calendar_model->save_company_address($company_id, $event_add_post['address']);
                }
                break;
        }
        $old_event_date = null;
        $old_event_start_time = null;
        $old_event_end_time = null;
        $old_event_status = null;
        if ($action == 'update_event' || $action == 'drop_update_event' || $action == 'drag_update_event' || $action == 'reschedule_event') {
            $old_event_details = $this->calendar_model->get_event_details($this->input->post('sid'));
            if (sizeof($old_event_details)) {
                $old_event_date = $old_event_details['date'];
                $old_event_start_time =  $old_event_details['eventstarttime'];
                $old_event_end_time =  $old_event_details['eventendtime'];
            }
        }
        // Perform Actual Action
        switch ($action) {
            case 'cancel_event':
                $event_sid = $this->input->post('event_id');
                $old_event_details = $this->calendar_model->get_event_details($event_sid);
                if (sizeof($old_event_details)) {
                    $old_event_status =  $old_event_details['event_status'];
                }

                $this->calendar_model->cancel_event($event_sid);
                $event_category = $this->calendar_model->get_event_column_by_event_id($event_sid, 'category');
                // Check for training session
                if ($event_category == 'training-session') {
                    $learning_center_training_sessions = $this->calendar_model->get_event_column_by_event_id($event_sid, 'learning_center_training_sessions');
                    $this->calendar_model->cancel_training_session(
                        $learning_center_training_sessions
                    );
                }

                $res['Response'] = 'Event is canceled.';
                $res['Status'] = TRUE;
                break;
            case 'save_event':
                //
                $this->reset_event_data($event_add_post, $event_data, $attachment);
                // $event_data['created_on'] = date('Y-m-d H:i:s');
                // $event_data['employers_sid'] = $employer_id;
                $event_data['companys_sid'] = $company_id;
                if ($event_data['users_type'] == 'applicant' && isset($event_add_post['event_timezone'])) {
                    $event_data['event_timezone'] = $event_add_post['event_timezone'];
                }
                if ($event_data['users_type'] == 'personal') {
                    if ($event_data['category'] == 'call') {
                        $event_data['users_name'] = $event_add_post['users_name'];
                        $event_data['users_phone'] = $event_add_post['users_phone'];
                    }
                    if ($event_data['category'] == 'email') {
                        $event_data['users_name'] = $event_add_post['users_name'];
                        $event_data['applicant_email'] = $event_add_post['users_email'];
                    }

                    unset($event_data['users_email']);
                    if (isset($event_add_post['users_email'])) $event_data['applicant_email'] = $event_add_post['users_email'];
                }

                $filePath = null;

                if ($event_data['users_type'] != 'personal') {
                    if ($verify_event_session == 0) {
                        $res['Response'] = 'Error: Sorry! The event cannot be added as it does not belong to your company.';
                        $this->response($res);
                    }
                }
                // Added on: 02-05-2019
                $event_data['event_status'] = 'pending';
                unset(
                    $event_data['video_ids'],
                    $event_data['interviewer_type'],
                    $event_data['lcts']
                );
                // Reset date/times from session zone to server zone
                //reset_event_datetime($event_data, $this, true);
                // Insert event record
                $last_id = $this->calendar_model->save_event($event_data);
                // Failed to save event
                if (!$last_id) {
                    $res['Response'] = 'Error: Sorry! there was an error, Please try again';
                    $this->response($res);
                }
                // Get last inserted id
                $event_sid = $this->db->insert_id();
                // Check for training session
                if ($event_data['category'] == 'training-session') {
                    $event_add_post_updated = $event_add_post;
                    $event_add_post_updated['date'] = $event_data['date'];
                    $event_add_post_updated['eventstarttime'] = $event_data['eventstarttime'];
                    $event_add_post_updated['eventendtime'] = $event_data['eventendtime'];
                    $this->set_training_session(
                        $company_id,
                        $event_sid,
                        $event_add_post_updated
                    );
                }
                $res['Response'] = 'Event Added successfully, scheduled for ' . DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                $res['EventId'] = $last_id;
                $res['Status'] = TRUE;
                break;
            case 'update_event':
            case 'drop_update_event':
                //
                $this->reset_event_data($event_add_post, $event_data, $attachment);

                $event_sid = $event_add_post['sid'];

                // $event_data['event_status'] = 'confirmed';
                if ($event_data['users_type'] == 'personal') {
                    if ($event_data['category'] == 'call') {
                        $event_data['users_name'] = $event_add_post['users_name'];
                        $event_data['users_phone'] = $event_add_post['users_phone'];
                    }
                    if ($event_data['category'] == 'email') {
                        $event_data['users_name'] = $event_add_post['users_name'];
                        $event_data['applicant_email'] = $event_add_post['users_email'];
                    }
                    unset($event_data['users_email']);
                    if (isset($event_add_post['users_email'])) $event_data['applicant_email'] = $event_add_post['users_email'];
                } else {
                    $event_data['applicant_email'] = $email;
                    $event_data['applicant_job_sid'] = $users_sid;
                }

                // Check for training session
                if ($event_data['category'] == 'training-session') {
                    $event_add_post_updated = $event_add_post;
                    $event_add_post_updated['date'] = $event_data['date'];
                    $event_add_post_updated['eventstarttime'] = $event_data['eventstarttime'];
                    $event_add_post_updated['eventendtime'] = $event_data['eventendtime'];
                    $this->set_training_session(
                        $company_id,
                        $event_sid,
                        $event_add_post_updated,
                        'update'
                    );
                } else {
                    // Check for LCTS and remove it
                    $lcts = $this->calendar_model
                        ->get_event_column_by_event_id($event_sid, 'learning_center_training_sessions');
                    if ($lcts != '') $this->calendar_model->delete_training_session($lcts);
                }

                unset(
                    $event_data['video_ids'],
                    $event_data['interviewer_type'],
                    $event_data['lcts']
                );

                // Check for date and reminder flag
                // and set sent flag to 0
                if ($event_data['date'] >= date('Y-m-d') && (isset($event_data['reminder_flag']) ? $event_data['reminder_flag'] : true)) $event_data['sent_flag'] = 0;
                // Reset date/times from UTC to server time
                //reset_event_datetime($event_data, $this, true);
                // $this->_e($event_data, true, true);
                $res['Response'] = 'Error: Sorry! there was an error, Please try again';
                if ($this->calendar_model->update_event($event_sid, $event_data)) {
                    $event_date = DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                    $res['Status'] = TRUE;
                    $res['Response'] = 'Event updated successfully, it is scheduled on ' . $event_date;
                }
                break;
            case 'delete_event':
                $event_id = $this->input->post('event_sid');
                $this->calendar_model->deleteEvent($event_id);
                $res['Response'] = 'Event Successfully Deleted!';
                $res['Status'] = TRUE;
                break;
                // added on: 02-04-2019
                // handle drag & resize
            case 'drag_update_event':
                $event_data_array = array();
                $event_data_array['date'] = $this->input->post('date');
                $event_data_array['eventstarttime'] = $this->input->post('eventstarttime');
                $event_data_array['eventendtime']   = $this->input->post('eventendtime');
                // Reset date/times from UTC to server time
                //reset_event_datetime($event_data_array, $this, true);

                $this->calendar_model->update_event_by_id(
                    $this->input->post('sid'),
                    $event_data_array['date'],
                    $event_data_array['eventstarttime'],
                    $event_data_array['eventendtime']
                );

                // update in LC
                $learning_center_training_sessions = $this->calendar_model->get_event_column_by_event_id(
                    $this->input->post('sid'),
                    'learning_center_training_sessions'
                );

                //
                if ($learning_center_training_sessions != '') {
                    $array = array();
                    $array['session_date']        = $event_data_array['date'];
                    $array['session_start_time']  = DateTime::createFromFormat('H:iA', $event_data_array['eventstarttime'])->format('H:i:s');
                    $array['session_end_time']    = DateTime::createFromFormat('H:iA', $event_data_array['eventendtime'])->format('H:i:s');
                    $this->calendar_model->update_lc_by_id(
                        $learning_center_training_sessions,
                        $array
                    );
                }
                //
                $res['Response'] = "Event updated successfully!.";
                // set event_id for ICS
                $event_sid = $this->input->post('sid');
                break;
                // added on: 08-04-2019
                // Handle reschedule
            case 'reschedule_event':
                // Fetch event date
                $old_event_details = $this->calendar_model->get_event_details($event_add_post['sid']);
                if (sizeof($old_event_details)) {
                    $old_event_status =  $old_event_details['event_status'];
                }
                $event_prev_date = $this->calendar_model->get_event_column_by_event_id($event_add_post['sid'], 'date', true);
                if (!$event_prev_date) {
                    $res['Response'] = 'Error: Sorry! there was an error, Please try again';
                    $this->response($res);
                }
                $this->reset_event_data($event_add_post, $event_data, $attachment);
                $lcts = $event_data['lcts'];

                unset($event_data['video_ids'], $event_data['lcts']);
                unset($event_data['interviewer_type']);
                // Check if
                if ($event_prev_date <= strtotime('now')) {
                    // $event_data['created_on'] = date('Y-m-d H:i:s');
                    $event_data['employers_sid'] = $employer_id;
                    $event_data['companys_sid'] = $company_id;
                    $event_data['parent_sid'] = $this->input->post('sid');

                    if ($event_data['users_type'] == 'personal') {
                        if ($event_data['category'] == 'call') {
                            $event_data['users_name'] = $event_add_post['users_name'];
                            $event_data['users_phone'] = $event_add_post['users_phone'];
                        }
                        if ($event_data['category'] == 'email') {
                            $event_data['users_name'] = $event_add_post['users_name'];
                            $event_data['applicant_email'] = $event_add_post['users_email'];
                        }
                        unset($event_data['users_email']);
                        if (isset($event_add_post['users_email'])) $event_data['applicant_email'] = $event_add_post['users_email'];
                    }

                    unset($event_data['sid']);
                    // Added on: 02-05-2019
                    $event_data['event_status'] = 'pending';

                    // Reset date/times from UTC to server time
                    //reset_event_datetime($event_data, $this, true);

                    $event_sid = $this->calendar_model->save_event($event_data);
                    // Failed to reschedule event
                    if (!$event_sid) {
                        $res['Response'] = 'Error: Sorry! there was an error, Please try again';
                        $this->response($res);
                    }
                    // Get last inserted id
                    // $event_sid = $this->db->insert_id();
                    // Check for training session
                    $res['EventId'] = $event_sid;
                    $res['Status'] = TRUE;
                    if ($event_data['category'] == 'training-session') {
                        $event_add_post_updated = $event_add_post;
                        $event_add_post_updated['date'] = $event_data['date'];
                        $event_add_post_updated['eventstarttime'] = $event_data['eventstarttime'];
                        $event_add_post_updated['eventendtime'] = $event_data['eventendtime'];
                        $this->set_training_session(
                            $company_id,
                            $event_sid,
                            $event_add_post_updated
                        );
                    }

                    //
                    $difference_array = array();
                    $res['Response'] = 'Event Added successfully, re-scheduled for ' . DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                    // set event_id for ICS
                    // $event_sid = $this->input->post('sid');
                } else {
                    //
                    if ($event_data['category'] == 'training-session')
                        $this->calendar_model->training_session_reset_status($lcts, 'pending');
                    $event_sid = $event_add_post['sid'];

                    $event_data['event_status'] = 'pending';

                    if ($event_data['users_type'] == 'personal') {
                        if ($event_data['category'] == 'call') {
                            $event_data['users_name'] = $event_add_post['users_name'];
                            $event_data['users_phone'] = $event_add_post['users_phone'];
                        }
                        if ($event_data['category'] == 'email') {
                            $event_data['users_name'] = $event_add_post['users_name'];
                            $event_data['applicant_email'] = $event_add_post['users_email'];
                        }
                        unset($event_data['users_email']);
                    } else {
                        $event_data['applicant_email'] = $email;
                        $event_data['applicant_job_sid'] = $users_sid;
                    }


                    // Check for date and reminder flag
                    // and set sent flag to 0
                    if ($event_data['date'] >= date('Y-m-d') && (isset($event_data['reminder_flag']) ? $event_data['reminder_flag'] : true)) $event_data['sent_flag'] = 0;

                    // Reset date/times from UTC to server time
                    //reset_event_datetime($event_data, $this, true);
                    $res['Response'] = 'Error: Sorry! there was an error, Please try again';
                    if ($this->calendar_model->update_event($event_sid, $event_data)) {
                        $event_date = DateTime::createFromFormat('Y-m-d', $event_data['date'])->format('F j, Y');
                        $res['Status'] = TRUE;
                        $res['Response'] = 'Event updated successfully, it is scheduled on ' . $event_date;
                    }
                }
                break;
        }

        // Don't delete the participents
        // for drag and resize events
        if ($action != 'drag_update_event') {
            // Check if participent already exits
            // then update the record otherwise
            // add it and delete the diference
            // if employer email exists
            // then add it to interviewers
            // $this->set_external_participants(
            //     $company_id,
            //     $employer_id,
            //     $event_sid,
            //     $this->input->post('external_participants'),
            //     $this->input->post('redirect_to')
            // );
            $redirect_to = $this->input->post('redirect_to');
            $this->calendar_model->remove_all_external_participants($company_id, $employer_id, $event_sid); //Save External Participants - Start
            $external_participants = $this->input->post('external_participants');
            if (empty($redirect_to)) {
                if (!is_null($external_participants) && !empty($external_participants)) {
                    $external_participants = json_decode($external_participants, true);
                }
            }

            if (!is_null($external_participants) && !empty($external_participants)) {
                foreach ($external_participants as $external_participant) {
                    $name = $external_participant['name'];
                    $email = $external_participant['email'];
                    $show_email = isset($external_participant['show_email']) ? $external_participant['show_email'] : 0;

                    if (!empty($name) && !empty($email)) {
                        $employee_data = $this->calendar_model->check_if_email_is_of_an_employee($company_id, $email);

                        if (empty($employee_data) || $employee_data == false) {
                            $this->calendar_model->add_event_external_participants($company_id, $employer_id, $event_sid, $name, $email, $show_email);
                        } else {
                            $employee_sid = $employee_data['sid'];
                            $this->calendar_model->append_participant_to_event($event_sid, $employee_sid);
                        }
                    }
                }
            } // Save External Participants - End
        }
        $diff_array_json = @json_decode($difference_array, true);
        if ($action == 'update_event' && !empty($old_event_date) && !empty($old_event_start_time)) {
            if (isset($diff_array_json['old_event_start_time']))
                $diff_array_json['old_event_start_time'] = $old_event_start_time;
            if (isset($diff_array_json['old_event_end_time']))
                $diff_array_json['old_event_end_time'] = $old_event_end_time;
            if (isset($diff_array_json['old_date']))
                $diff_array_json['old_date'] = $old_event_date;
        }

        if ($action == 'update_event') {
            if (isset($diff_array_json['new_event_start_time']) && isset($event_data['eventstarttime']))
                $diff_array_json['new_event_start_time'] = $event_data['eventstarttime'];
            if (isset($diff_array_json['new_event_end_time']) && isset($event_data['eventendtime']))
                $diff_array_json['new_event_end_time'] = $event_data['eventendtime'];
            if (isset($diff_array_json['new_date']) && isset($event_data['date']))
                $diff_array_json['new_date'] = $event_data['date'];
        }
        if (isset($diff_array_json['new_date']) && isset($event_data['eventstarttime'])) {
            $diff_array_json['timezone_new_start_time'] = $event_data['eventstarttime'];
        }
        if (isset($diff_array_json['old_date']) && isset($old_event_start_time)) {
            $diff_array_json['timezone_old_start_time'] = $old_event_start_time;
        }
        if (sizeof($diff_array_json)) {
            $diff_array_json['users_type'] = $event_data['users_type'];
        }

        if (($action == "cancel_event") && !empty($old_event_status)) {
            $diff_array_json['old_event_status'] = $old_event_status;
            $diff_array_json['new_event_status'] = 'cancelled';
        }
        if ($action == 'reschedule_event') {
            if ($old_event_status == 'cancelled')
                $diff_array_json['old_event_status'] = 'cancelled';
            else
                $diff_array_json['old_event_status'] = 'expired';
            $diff_array_json['new_event_status'] = 'rescheduled';
        }

        // Handle event changes
        $this->handle_event_changes(
            $event_sid,
            $employer_id,
            $company_id,
            $diff_array_json
        );


        //
        ics_files($event_sid, $company_id, $company_info, $action, array(), false, $diff_array_json);

        $redirect_to = $this->input->post('redirect_to');
        $redirect_to_user_sid = $this->input->post('redirect_to_user_sid');
        $redirect_to_job_list_sid = $this->input->post('redirect_to_job_list_sid');

        if (!empty($redirect_to)) {
            switch ($redirect_to) {
                case 'applicant_profile':
                    $this->session->set_flashdata('message', $message);
                    redirect('applicant_profile/' . $redirect_to_user_sid . '/' . $redirect_to_job_list_sid, 'refresh');
                    break;
                case 'employee_profile':
                    $this->session->set_flashdata('message', $message);
                    redirect('employee_profile/' . $redirect_to_user_sid, 'refresh');
                    break;
                default:
                    echo $message;
                    break;
            }
        } else $this->response($res);
        exit;
    }


    /**
     * Reset event details
     * for save and update events
     * Added at: 08-04-2019
     *
     * @param $event_add_post Array
     * @param $event_data Reference
     * @param $attachment Null
     *
     * @return Void
     *
     */
    private function reset_event_data($event_add_post, &$event_data, $attachment)
    {
        foreach ($event_add_post as $key => $value) {
            if (($value == '<update_ev></update_ev>ent'  || $value == 'drop_update_event') && $key == 'sid') continue;
            if (
                $key != 'action' &&
                $key != 'date' &&
                $key != 'applicant_sid' &&
                $key != 'redirect_to' &&
                $key != 'redirect_to_user_sid' &&
                $key != 'redirect_to_job_list_sid' &&
                $key != 'show_email' &&
                $key != 'address_type' &&
                $key != 'employee_sid' &&
                $key != 'external_participants'
            ) { // exclude these values from array
                // Check for array and implode it
                if (is_array($value)) $value = implode(',', $value);
                $event_data[$key] = $value;
                continue;
            } else if ($key == 'show_email' && is_array($value))
                $event_data['interviewer_show_email'] = implode(',', $value);
        }

        if (isset($event_data['title'])) { //removing break from string
            $event_data['title'] = remove_breaks_from_string($event_data['title']);
        }

        if (isset($event_data['subject'])) {
            $event_data['subject'] = remove_breaks_from_string($event_data['subject']);
        }

        if (isset($event_data['message'])) {
            $event_data['message'] = remove_breaks_from_string($event_data['message']);
        }

        if (isset($event_data['comment'])) {
            $event_data['comment'] = remove_breaks_from_string($event_data['comment']);
        }

        if (isset($event_data['messageFile'])) {
            $event_data['messageFile'] = $attachment;
        }

        // Message convert
        if (isset($event_data['meetingId']) && $event_data['meetingId'] == 'null') {
            // unset(
            $event_data['meetingId'] = 0;
            $event_data['meetingCallNumber'] = '';
            $event_data['meetingURL'] = '';
            // );
        }

        if (isset($event_data['messageCheck']) && $event_data['messageCheck'] == 0) {
            // unset(
            $event_data['message'] = '';
            $event_data['subject'] = '';
            // );
        }

        if (isset($event_data['interviewer']) && $event_data['interviewer'] == 'null') {
            $event_data['interviewer'] = '';
            // unset( $event_data['interviewer']);
        }

        if (isset($event_data['address']) && $event_data['address'] == 'null') {
            $event_data['address'] = '';
        }

        if (isset($event_data['commentCheck']) && $event_data['commentCheck'] == '0') {
            $event_data['comment'] = '';
        }

        if (isset($event_data['employee_sid']) && $event_data['employee_sid'] == 'null') {
            $event_data['employee_sid'] = 0;
        }

        if (isset($event_data['applicant_sid']) && $event_data['applicant_sid'] == 'undefined') {
            $event_data['applicant_sid'] = 0;
        }

        // Decode the json object
        $recurr = json_decode($event_add_post['recur'], true);
        // Check if recurr event is set
        if (sizeof($recurr)) {
            $event_data['is_recur'] = 1;
            $event_data['recur_type'] = $recurr['recur_type'];
            $event_data['recur_start_date'] = $recurr['recur_start_date'];
            if ($recurr['recur_end_date'] != '')
                $event_data['recur_end_date'] = $recurr['recur_end_date'];
            $event_data['recur_list'] = @json_encode($recurr['list']);
        }
        //
        if (!isset($event_add_post['event_timezone'])) {
            $event_add_post['event_timezone'] = get_current_timezone($this)['key'];
        }
        // unset recur
        unset($event_data['recur'], $event_add_post['recur']);
        // Reset date to servers date
        // Reset date to server timezone
        // Added on: 28-06-2019
        //$event_date = reset_datetime(array('datetime' => $event_add_post['date'], '_this' => $this, 'from_format' => 'm-d-Y', 'format' => 'Y-m-d', 'from_timezone' => $event_add_post['event_timezone'], 'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR));
        // $event_date = DateTime::createFromFormat('m-d-Y', $event_add_post['date'])->format('Y-m-d');
        if (isset($event_add_post['eventstarttime'])) {
            $event_data['date'] = reset_datetime(array(
                'datetime' => $event_add_post['date'] . '' . $event_add_post['eventstarttime'],
                '_this' => $this,
                'from_format' => 'm-d-Y h:iA',
                'format' => 'Y-m-d',
                'from_timezone' => $event_add_post['event_timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
            ));
        }

        // Reset start time to server timezone
        // Added on: 28-06-2019
        if (isset($event_add_post['eventstarttime'])) {
            $event_data['eventstarttime'] = reset_datetime(array(
                'datetime' => $event_add_post['date'] . '' . $event_add_post['eventstarttime'],
                '_this' => $this,
                'from_format' => 'm-d-Y h:iA',
                'format' => 'h:iA',
                'from_timezone' => $event_add_post['event_timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
            ));
        }

        // Reset end time to server timezone
        // Added on: 28-06-2019
        if (isset($event_add_post['eventendtime'])) {
            $event_data['eventendtime'] = reset_datetime(array(
                'datetime' => $event_add_post['date'] . '' . $event_add_post['eventendtime'],
                '_this' => $this,
                'from_format' => 'm-d-Y h:iA',
                'format' => 'h:iA',
                'from_timezone' => $event_add_post['event_timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
            ));
        }

        //
        // $event_data['event_status'] = 'awaiting confirmation';
        // $event_data['event_status'] = 'awaiting confirmation';
    }


    /**
     * Generates event status rows for email
     * Added at: 08-04-2019
     *
     * @param $event_sid Integer
     * @param $user_sid Integer
     * @param $event_type String (applicant, employee, interviewer, extrainterviewer)
     * @param $user_name String
     * @param $user_email String
     * @param $event_category String
     * @param $learning_center_training_sessions Integer
     *
     * @return String
     */
    private function generate_event_status_rows($event_sid, $user_sid, $event_type, $user_name, $user_email, $event_category, $learning_center_training_sessions)
    {
        //
        // Load encryption class
        // to encrypt employee/applicant id
        // and email
        $this->load->library('encrypt');
        $base_url = base_url() . 'event/';
        // Set event code string
        $string_conf = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=confirmed:name=' . $user_name . ':email=' . $user_email;
        $string_notconf = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=notconfirmed:name=' . $user_name . ':email=' . $user_email;
        $string_reschedule = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=reschedule:name=' . $user_name . ':email=' . $user_email;
        if ($event_category == 'training-session')
            $string_attended = 'id=' . $user_sid . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=attended:name=' . $user_name . ':email=' . $user_email;
        // Set encoded string
        $enc_string_conf = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_conf));
        if ($event_category == 'training-session')
            $enc_string_attended = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_attended));
        $enc_string_notconf  = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_notconf));
        $enc_string_reschedule = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_reschedule));
        // Set button rows
        $button_rows = '<div>';
        $button_rows .= '   <p>Please, select one of the options.</p>';
        if ($event_category == 'training-session') {
            $button_rows .= '   <a href="' . $enc_string_notconf . '" target="_blank" style="background-color: #cc1100; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Unable To Attend</a>';
            $button_rows .= '   <a href="' . $enc_string_conf . '" target="_blank" style="background-color: #f0ad4e ; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Will Attend</a>';
            $button_rows .= '   <a href="' . $enc_string_attended . '" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Attended</a>';
            $button_rows .= '   <a href="' . $enc_string_reschedule . '" target="_blank" style="background-color: #006699; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Reschedule</a>';
            // Only show trainig session link
            // when blue panel is active
            if (check_blue_panel_status() && $event_type != 'extrainterviewer') {
                $button_rows .= '   <br />';
                $button_rows .= '   <br />';
                $button_rows .= '   <p>Below is the link for the training session.</p>';
                $button_rows .= '   <a href="' . base_url('learning_center/view_training_session') . '/' . $learning_center_training_sessions . '" target="_blank" style="background-color: none; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; margin-right: 10px;">' . base_url('learning_center/view_training_session') . '/' . $learning_center_training_sessions . '</a>';
            }
        } else {
            $button_rows .= '   <a href="' . $enc_string_conf . '" target="_blank" style="background-color: #009966; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Confirm</a>';
            $button_rows .= '   <a href="' . $enc_string_notconf . '" target="_blank" style="background-color: #cc1100; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Cannot attend</a>';
            $button_rows .= '   <a href="' . $enc_string_reschedule . '" target="_blank" style="background-color: #006699; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block; margin-right: 10px;">Reschedule</a>';
        }
        $button_rows .= '</div>';

        return $button_rows;
    }

    /**
     * Generates ICS files and send email
     * Added at: 09-04-2019
     * Deprecated: to be removed after testing
     *
     *
     * @param $event_sid Integer
     * @param $company_id Integer
     * @param $employer_id Integer
     * @param $email_list Array Optional
     *
     * @return VOID
     */
    private function ics_files($event_sid, $company_id, $company_info, $action, $email_list = array())
    {
        if ($action == 'delete_event') return false;
        // Generate Updated .ics file
        $destination = APPPATH . '../assets/ics_files/';
        $ics_file = generate_ics_file_for_event($destination, $event_sid);
        $event_details = $this->calendar_model->get_event_details($event_sid); //Get Event Details
        // Set defaults
        $send_to_interviewers = $send_to_extraparticipants = $send_to_ae = true;
        //
        $employer_info = $this->session->userdata('logged_in')['employer_detail'];
        // Set default user info array
        $user_info['first_name'] = $employer_info['first_name'];
        $user_info['last_name'] = $employer_info['last_name'];
        $user_info['email'] = $employer_info['email'];
        $user_info['sid']   = $employer_info['sid'];
        //
        $employers = array();
        // Applicant Tile
        $event_category = $user_tile = '';
        $event_category = $event_details['category'];
        // Set applicant/employee details
        // if user type is not 'Personal'
        if ($event_details['users_type'] != 'personal') {
            $detail_type = $event_details['users_type'] == 'applicant' ? 'get_applicant_detail' : 'get_employee_detail';
            $user_info = $this->calendar_model->$detail_type($event_details['applicant_job_sid']);
        }

        // Get Participants
        if (!empty($event_details['interviewer'])) {
            $employers = $this->calendar_model->get_user_information($company_id, explode(',', $event_details['interviewer']));
        }

        // Reset categories
        switch ($event_details['category']) {
            case 'interview':
                $event_category = 'In-Person Interview';
                break;
            case 'interview-phone':
                $event_category = 'Phone Interview';
                break;
            case 'interview-voip':
                $event_category = 'Voip Interview';
                break;
            case 'training-session':
                $event_category = 'Training Session';
                break;
            case 'other':
                $event_category = 'Other Appointment';
                break;
            default:
                $event_category = ucwords($event_category);
                break;
        }

        $interviewers_rows = $this->generate_interviewers_rows(
            $employers,
            $event_details,
            $event_category,
            $event_sid,
            $user_info
        );

        //
        if ($event_details['category'] == 'other') $event_details['category'] = 'Appointment';

        if ($event_details['users_type'] == 'applicant') {
            $user_tile .= '<p><b>' . ucwords($event_details['users_type']) . ' Name:</b> ' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . '</p>';
            if (!empty($user_info['email'])) {
                $user_tile .= '<p><b>Email:</b> ' . $user_info['email'] . '</p>';
            }

            if (!empty($user_info['phone_number'])) {
                $user_tile .= '<p><b>Phone:</b> ' . $event_details['users_phone'] . '</p>';
            }

            if (!empty($user_info['city'])) {
                $user_tile .= '<p><b>City:</b> ' . $user_info['city'] . '</p>';
            }

            $applicant_job_list_sid = 0;

            if (isset($user_info['job_applications']) && !empty($user_info['job_applications'])) {
                $applicant_job_list_sid = $user_info['job_applications'][0]['sid'];
                $applicant_jobs_list = $event_details['applicant_jobs_list'];

                if ($applicant_jobs_list != '' && $applicant_jobs_list != null) {
                    $applicant_jobs_array = explode(',', $applicant_jobs_list);
                }

                $user_tile .= '<p><b>Job(s) Applied:</b></p>';
                $user_tile .= '<ol>';

                if (!empty($applicant_jobs_array)) {
                    foreach ($user_info['job_applications'] as $job_application) {
                        $applicant_sid = $job_application['sid'];

                        if (in_array($applicant_sid, $applicant_jobs_array)) {
                            $job_title = !empty($job_application['job_title']) ? $job_application['job_title'] : '';
                            $desired_job_title = !empty($job_application['desired_job_title']) ? $job_application['desired_job_title'] : '';

                            if (!empty($job_title)) {
                                $title = $job_title;
                            } else if (!empty($desired_job_title)) {
                                $title = $desired_job_title;
                            } else {
                                continue;
                            }

                            $user_tile .= '<li>' . $title . '</li>';
                            $applicant_job_list_sid = $job_application['sid'];
                        }
                    }
                } else {
                    $job_application = $user_info['job_applications'];
                    $job_application_last_index = count($job_application) - 1;

                    for ($i = 0; $i < count($job_application); $i++) {
                        $applicant_sid = $job_application[$i]['sid'];

                        $job_title = !empty($job_application[$i]['job_title']) ? $job_application[$i]['job_title'] : '';
                        $desired_job_title = !empty($job_application[$i]['desired_job_title']) ? $job_application[$i]['desired_job_title'] : '';

                        if (!empty($job_title)) {
                            $title = $job_title;
                        } else if (!empty($desired_job_title)) {
                            $title = $desired_job_title;
                        } else {
                            continue;
                        }

                        $user_tile .= '<li>' . $title . '</li>';
                        $applicant_job_list_sid = $job_application[$job_application_last_index]['sid'];
                    }
                }

                $user_tile .= '</ol>';
            }

            $resume_btn = '';

            if (isset($user_info['resume']) && !empty($user_info['resume'])) {
                $resume_btn = '<a href="' . AWS_S3_BUCKET_URL . $user_info['resume'] . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '">Download Resume</a>';
            }

            $profile_btn = '<a href="' . base_url('applicant_profile') . '/' . $user_info['sid'] . '/' . $applicant_job_list_sid . '" target="_blank" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" >View Profile</a>';
            $user_tile .= '<p>' . $resume_btn . ' ' . $profile_btn . '</p>';
            $user_tile .= '<hr />';
        }

        $from = FROM_EMAIL_EVENTS; //Create Email Notification
        $from_name = ucwords($company_info["CompanyName"]);
        $message_hf = message_header_footer($company_id, ucwords($company_info["CompanyName"]));
        $applicant_name = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
        $email_subject = ucwords($event_category) . ' - Has Been ' . ucwords($event_details['event_status']) . ' ' . ucwords($company_info['CompanyName']);
        // Set subject for 'Personal' type
        // and categories 'Call, Email'
        if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'call' || $event_details['category'] == 'email'))
            $email_subject = ucwords($event_details['category']) . ' - Has been schduled with ' . ucwords($event_details['users_name']);
        else if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'training-session' || $event_details['category'] == 'Appointment'))
            $email_subject = ucwords($event_details['category']) . ' - Has been schduled';
        $email_message = $message_hf['header'];
        $email_message .= '<div>';
        $email_message .= '<p><b>Dear {{user_name}},</b></p>';


        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $email_message .= '<p><b>This is a reminder email regarding an upcoming event. Please, find the details below.</b></p>';
        } else if ($action == 'update_event' || $action == 'drop_update_event' || $action == 'drag_update_event') {
            // $email_message .= '<p><b>Your Event Details Have been Changed Please update your calendar as per below information. </b></p>';
            $email_message .= '<p><b>Your Event details have been Changed. Please update your calendar with the new information. </b></p>';
        } else {
            // Set subject for 'Personal' type
            // and categories 'Call, Email'
            if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'call')
                $email_message .= '<p>You have scheduled an event regarding making a ' . ucwords($event_category) . ' to <b>' . $event_details['users_name'] . '</b></p>';
            else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'email')
                $email_message .= '<p>You have scheduled an event regarding sending an ' . ucwords($event_category) . ' to <b>' . $event_details['users_name'] . '</b></p>';
            else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'training-session') {
                $email_message .= '<p>You have scheduled a ' . ucwords($event_category) . ' for </p>';
                $email_message .= $interviewers_rows;
            } else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'Appointment') {
                $email_message .= '<p>You have scheduled an Appointment for </p>';
                $email_message .= $interviewers_rows;
            } else {
                if ($event_details['event_status'] == 'pending')
                    $email_message .= '<p>' . ucwords($event_category) . ' has been planned with status of ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
                else
                    $email_message .= '<p>' . ucwords($event_category) . ' has been ' . ucwords($event_details['event_status']) . ' for you with <b>"{{target_user}}"</b></p>';
            }
        }


        $email_message .= '<br />';
        $email_message .= ' '; //Applicant Tile
        $email_message .= '{{user_tile}}';
        $email_message .= ' ';
        $email_message .= '<p><b>Event Details are as follows:</b></p>'; //event information

        if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'call')
            $email_message .= '<p><b>Event :</b> ' . ucwords($event_category) . '  "' . ucwords($event_details['users_name']) . '" on ' . $event_details['users_phone'] . '</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'email')
            $email_message .= '<p><b>Event :</b> Send an ' . ucwords($event_category) . ' to "' . ucwords($event_details['users_name']) . '"</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'training-session')
            $email_message .= '<p><b>Event :</b> Training Session</p>';
        else if ($event_details['users_type'] == 'personal' && $event_details['category'] == 'Appointment')
            $email_message .= '<p><b>Event :</b> Appointment</p>';
        else
            // $email_message .= '<p><b>Event :</b> ' . ucwords($event_category) . ' With "{{target_user}}"</p>';

            $email_message .= '<p><b>Date :</b> ' . date_with_time($event_details['date']) . '</p>';
        $email_message .= '<p><b>Time :</b> From ' . $event_details['eventstarttime'] . ' To ' . $event_details['eventendtime'] . '</p>';
        $email_message .= '<hr />';
        $comment_tile = ''; //Comment Check

        if ($event_details['commentCheck'] == 1 && !empty($event_details['comment'])) {
            $comment_tile .= '<p><b>Comment From Employer</b></p>';
            $comment_tile .= '<p><b>Comment:</b> ' . $event_details['comment'] . '</p>';
            $comment_tile .= '<hr />';
        }

        $email_message .= ' '; //Comment Tile
        $email_message .= '{{comment_tile}}';
        $email_message .= ' ';

        if ($event_details['messageCheck'] == 1 && !empty($event_details['subject']) && !empty($event_details['message'])) { //Message Check
            $email_message .= '<p><b>Message From Employer</b></p>';
            $email_message .= '<p><b>Subject:</b> ' . $event_details['subject'] . '</p>';
            $email_message .= '<p><b>Message:</b> ' . $event_details['message'] . '</p>';

            if (!empty($event_details['messageFile']) && $event_details['messageFile'] != 'undefined') {
                $email_message .= '<p><b>Attachment:</b> <a href="' . AWS_S3_BUCKET_URL . $event_details['messageFile'] . '" target="_blank">' . $event_details['messageFile'] . '</a></p>';
            }

            $email_message .= '<hr />';
        }

        //Meeting Call Details
        if ($event_details['goToMeetingCheck'] == 1 && (!empty($event_details['meetingId']) || !empty($event_details['meetingCallNumber']) || !empty($event_details['meetingURL']))) {
            $email_message .= '<p><b>Meeting Call Details Are As Follows:</b></p>';

            if (!empty($event_details['meetingId'])) {
                $email_message .= '<p><b>Meeting ID:</b> ' . $event_details['meetingId'] . '</p>';
            }

            if (!empty($event_details['meetingCallNumber'])) {
                $email_message .= '<p><b>Meeting Call Number:</b> ' . $event_details['meetingCallNumber'] . '</p>';
            }

            if (!empty($event_details['meetingURL'])) {
                $email_message .= '<p><b>Meeting Call Url:</b> <a href="' . $event_details['meetingURL'] . '" target="_blank">' . $event_details['meetingURL'] . '</a></p>';
            }

            $email_message .= '<hr />';
        }

        if ($event_details['users_type'] != 'personal') $email_message .= $interviewers_rows;

        if (!empty($event_details['address'])) {
            // if (!empty($event_details['address']) && $event_details['category'] != 'interview-phone' && $event_details['category'] != 'interview-voip' && $event_details['category'] != 'call' && $event_details['category'] != 'email') {
            $map_url = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($event_details['address']) . "&zoom=13&size=400x400&key=" . GOOGLE_API_KEY . "&markers=color:blue|label:|" . urlencode($event_details['address']);
            $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . urlencode($event_details['address']) . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
            $email_message .= '<p><b>Address:</b> ' . $event_details['address'] . ' </p>';
            $email_message .= '<p> ' . $map_anchor . ' </p>';
            $email_message .= '<hr />';
        }

        $email_message .= '{{EMAIL_STATUS_BUTTONS}}';
        $email_message .= '</div>';
        $email_message .= $message_hf['footer'];
        $user_message = $email_message; //Send Email to Applicant

        // For Send Reminder Emails
        if ($action == 'send_reminder_emails' && !sizeof($email_list)) {
            header('content-type: application/json');
            echo json_encode(array('Response' => 'Error! this event is no longer available.', 'Redirect' => FALSE, 'Status' => FALSE));
            exit(0);
        }

        if ($action == 'send_reminder_emails' || $action == 'send_cron_reminder_emails') {
            $date = date('Y-m-d H:i:s');
            foreach ($email_list as $k0 => $v0) {
                $user_message = $email_message; //Send Email to Applicant
                $user_message = str_replace('{{user_name}}', $v0['value'], $user_message);
                $user_message = str_replace('{{user_tile}}', ' ', $user_message);
                $user_message = str_replace('{{comment_tile}}', ($v0['type'] == 'employee' || $v0['type'] == 'applicant' ? '' : $event_details['comment']), $user_message);
                $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);
                //
                if ($action != 'send_cron_reminder_emails') {
                    // Set data array
                    $data_array = array();
                    $data_array['event_sid']     = $event_sid;
                    $data_array['email_address'] = $v0['email_address'];
                    $data_array['user_id']       = $v0['id'];
                    $data_array['user_name']     = $v0['value'];
                    $data_array['user_type']     = $v0['type'] != '' ? $v0['type'] : 'employee';
                    $data_array['timezone']     = $v0['timezone'];
                    $data_array['created_at']    = $date;
                    // Create record in db
                    $this->calendar_model->save_event_sent_email_reminder_history($data_array);
                }

                // // Add event status buttons
                $user_email_status_button_rows =
                    $this->generate_event_status_rows(
                        $event_details['sid'],
                        $v0['id'],
                        $v0['type'],
                        $v0['value'],
                        $v0['email_address'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions']
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
                $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $v0['email_address'], $email_subject, $user_message, $from_name, $ics_file);
            }
            if ($action != 'send_cron_reminder_emails') {
                header('content-type: application/json');
                echo json_encode(array('Response' => 'Reminder emails are sent to the selected emails.', 'Redirect' => FALSE, 'Status' => TRUE));
                exit(0);
            } else {
                // $this->_e($user_message, true, true);
                exit(0);
            };
        }

        // Don't send email to interviewers
        // and to non-employee interviewers in case of
        // type 'Personal'
        if ($event_details['users_type'] == 'personal') $send_to_extraparticipants = $send_to_interviewers = false;
        // Send email to applicant/employee/person
        if ($send_to_ae) {
            $user_message = str_replace('{{user_name}}', $applicant_name, $user_message);
            $user_message = str_replace('{{user_tile}}', ' ', $user_message);
            $user_message = str_replace('{{comment_tile}}', ' ', $user_message);
            $user_message = str_replace('{{target_user}}', ucwords($company_info['CompanyName']), $user_message);
            //
            if ($event_details['users_type'] != 'personal') {
                // Add event status buttons
                $user_email_status_button_rows =
                    $this->generate_event_status_rows(
                        $event_details['sid'],
                        $user_info['sid'],
                        $event_details['users_type'],
                        $user_info['first_name'] . ' ' . $user_info['last_name'],
                        $user_info['email'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions']

                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $user_email_status_button_rows, $user_message);
            } else
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', '', $user_message);
            $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $user_info['email'], $email_subject, $user_message, $from_name, $ics_file);

            // $this->_e($email_subject, true);
            // $this->_e($user_message, true);
        }
        // Send emails to Interviewers
        if ($send_to_interviewers) {
            //
            foreach ($employers as $employer) { //Send Email To Employers
                $user_message = $email_message;
                $employer_name = ucwords($employer['first_name'] . ' ' . $employer['last_name']);
                $user_message = str_replace('{{user_name}}', $employer_name, $user_message);
                $user_message = str_replace('{{user_tile}}', $user_tile, $user_message);
                $user_message = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                $user_message = str_replace('{{target_user}}', $applicant_name, $user_message);


                // Add event status buttons
                $interviewer_email_status_button_rows =
                    $this->generate_event_status_rows(
                        $event_details['sid'],
                        $employer['sid'],
                        'interviewer',
                        $employer_name,
                        $employer['email'],
                        $event_details['category'],
                        $event_details['learning_center_training_sessions']
                    );
                $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $interviewer_email_status_button_rows, $user_message);
                // $this->_e($user_message, true);

                $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $employer['email'], $email_subject, $user_message, $from_name, $ics_file);
            }
        }
        // Send emails to non-employee Interviewers
        if ($send_to_extraparticipants) {
            if (!empty($event_external_participants)) { //Send Email To External Participants
                foreach ($event_external_participants as $event_external_participant) {
                    // $this->_e($event_external_participant, true, true);
                    $user_message = $email_message;
                    $employer_name = ucwords($event_external_participant['name']);
                    $user_message  = str_replace('{{user_name}}', $employer_name, $user_message);
                    $user_message  = str_replace('{{user_tile}}', $user_tile, $user_message);
                    $user_message  = str_replace('{{comment_tile}}', $comment_tile, $user_message);
                    $user_message  = str_replace('{{target_user}}', $applicant_name, $user_message);
                    // Add event status buttons
                    $extrainterviewer_email_status_button_rows =
                        $this->generate_event_status_rows(
                            $event_details['sid'],
                            $event_external_participant['sid'],
                            'extrainterviewer',
                            $employer_name,
                            $event_external_participant['email'],
                            $event_details['category'],
                            $event_details['learning_center_training_sessions']
                        );
                    $user_message = str_replace('{{EMAIL_STATUS_BUTTONS}}', $extrainterviewer_email_status_button_rows, $user_message);
                    // $this->_e($user_message, true);
                    $this->log_and_send_email_with_attachment(FROM_EMAIL_NOTIFICATIONS, $event_external_participant['email'], $email_subject, $user_message, $from_name, $ics_file);
                }
            }
            // $this->_e('end', true, true);
        }
    }

    /**
     * Handles request for reminder emails
     * Added on: 10-04-2019
     *
     * @param $res Array
     * @param $company_info Array
     *
     * @return JSON
     */
    private function send_reminder_emails($res, $company_info)
    {
        if (
            !$this->input->post('emails') ||
            !is_array($this->input->post('emails')) ||
            !sizeof($this->input->post('emails')) ||
            !$this->input->post('event_id')
        ) {
            $res['Response'] = 'Error! Invalid request made.';
            $this->response($res);
        }
        $date = date('Y-m-d H:i:s');
        $event_sid = $this->input->post('event_id');
        // Fetch event details
        $event_detail = $this->calendar_model->get_event_detail_by_event_id(
            $event_sid
        );
        //
        if (!sizeof($event_detail)) {
            $res['Response'] = 'Error! this event is no longer available.';
            $this->response($res);
        }
        // Loop throught email array
        foreach ($this->input->post('emails') as $k0 => $v0) {
            // Set data array
            $data_array = array();
            $data_array['event_sid'] = $event_sid;
            $data_array['email_address'] = $v0['email_address'];
            $data_array['user_id']       = $v0['id'];
            $data_array['user_name']     = $v0['value'];
            $data_array['user_type']     = $v0['type'] != '' ? $v0['type'] : 'employee';
            $data_array['created_at']    = $date;

            // Create record in db
            $this->calendar_model->save_event_sent_email_reminder_history($data_array);
            // Send email
            log_and_send_templated_email(
                EVENT_REMINDER_EMAIL_NOTIFICATION,
                $v0['email_address'],
                array(
                    'company_name' => $company_info['CompanyName'],
                    'event_type' => $event_detail['category_uc'],
                    'participant_name' => $v0['value'],
                    'event_date' => $event_detail['event_date'],
                    'start_time' => $event_detail['event_start_time'],
                    'event_title' => $event_detail['event_start_time']
                )
            );
        }
        //
        $res['Response'] = 'Sent reminder emails.';
        $this->response($res);
    }


    /**
     * Get event status change requests history
     * added at: 11-04-2019
     *
     * accepts GET
     * @param $event_sid Integer
     * @param $current_page Integer
     *
     * @return JSON
     *
     */
    function get_event_availablity_requests($event_sid, $current_page)
    {
        // Check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // Check if request method is not GET
        // and user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $history = $this->calendar_model->get_event_availablity_requests(
            $event_sid,
            $current_page,
            $this->limit
        );
        if (!$history) {
            $return_array['Response'] = 'no history found.';
            $this->response($return_array);
        }

        // update all requests status to
        $this->calendar_model->update_request_status($event_sid);

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Limit']    = $this->limit;
        $return_array['ListSize'] = $this->list_size;
        if (isset($history['Count'])) {
            $return_array['Total']    = $history['Count'];
            $return_array['History']  = $history['History'];
        } else
            $return_array['History']  = $history;
        $this->response($return_array);
    }

    /**
     * Generate rows for Interviewers
     * and non-employee Interviewers
     * added at: 12-04-2019
     *
     * @param $employers Array|Bool
     * @param $event_details Array
     * @param $event_category String
     * @param $event_sid Integer
     * @param $user_info Array
     *
     * @return Array
     *
     */
    private function generate_interviewers_rows($employers, $event_details, $event_category, $event_sid, $user_info)
    {
        if (!sizeof($employers)) return '';
        $show_emails = array(); //Interviewers

        if (!empty($event_details['interviewer_show_email']) && !is_null($event_details['interviewer_show_email'])) {
            $show_emails = explode(',', $event_details['interviewer_show_email']);
        }
        $interviewers_rows = '';
        if ($event_category == '') $event_details['category'];
        //
        if ($event_details['users_type'] == 'personal' && ($event_details['category'] == 'other' || $event_details['category'] == 'training-session')) {
            $interviewers_rows .= '';
            // $event_category = ;
        } else
            $interviewers_rows .= '<p><b>Your ' . $event_category . ' is ' . ucwords($event_details['event_status']) . ' with:</b></p>';

        $interviewers_rows .= '<ul>';

        if ($event_details['users_type'] == 'employee') {
            $interviewers_rows .= '<li>' . ucwords($user_info['first_name'] . ' ' . $user_info['last_name']) . ' ( <a href="mailto:' . $user_info['email'] . '">' . $user_info['email'] . '</a> )' . '</li>';
        }

        foreach ($employers as $employer) {
            $email = in_array($employer['sid'], $show_emails) ? ' ( <a href="mailto:' . $employer['email'] . '">' . $employer['email'] . '</a> )' : '';
            $interviewers_rows .= '<li>' . ucwords($employer['first_name'] . ' ' . $employer['last_name']) . $email . '</li>';
        }

        $event_external_participants = $this->calendar_model->get_event_external_participants($event_sid); //Get External Participants
        $ext_participants = '';

        if (!empty($event_external_participants)) {
            foreach ($event_external_participants as $event_external_participant) {
                $show_email = $event_external_participant['show_email'];

                if ($show_email == 1) {
                    $email_link = '<a href="mailto:' . $event_external_participant['email'] . '">' . $event_external_participant['email'] . '</a>';
                    $participant = $event_external_participant['name'] . ' ( ' . $email_link . ' ) ';
                } else {
                    $participant = $event_external_participant['name'];
                }

                $ext_participants .= '<li>' . $participant . '</li>';
            }

            $interviewers_rows .= $ext_participants;
        }

        $interviewers_rows .= '</ul>';
        $interviewers_rows .= '<hr />';
        return $interviewers_rows;
    }

    /**
     * Get sent reminder emails history
     * added at: 12-04-2019
     *
     * accepts GET
     * @param $event_sid Integer
     * @param $current_page Integer
     *
     * @return JSON
     *
     */
    function get_reminder_email_history($event_sid, $current_page)
    {
        // Check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // Check if request method is not GET
        // and user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $history = $this->calendar_model->get_reminder_email_history(
            $event_sid,
            $current_page,
            $this->limit
        );
        if (!$history) {
            $return_array['Response'] = 'no history found.';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Limit']    = $this->limit;
        $return_array['ListSize'] = $this->list_size;
        if (isset($history['Count'])) {
            $return_array['Total']    = $history['Count'];
            $return_array['History']  = $history['History'];
        } else
            $return_array['History']  = $history;
        $this->response($return_array);
    }

    /**
     * Get event change history
     * added at: 18-10-2019
     *
     * accepts GET
     * @param $event_sid Integer
     * @param $current_page Integer
     *
     * @return JSON
     *
     */
    function get_event_change_history($event_sid, $current_page)
    {

        // Check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // Check if request method is not GET
        // and user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;

        // fetch company employers
        $history = $this->calendar_model->get_event_change_history(
            $event_sid,
            $current_page,
            $this->limit
        );

        if (!$history) {
            $return_array['Response'] = 'no history found.';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Limit']    = $this->limit;
        $return_array['ListSize'] = $this->list_size;
        if (isset($history['Count'])) {
            $return_array['Total']    = $history['Count'];
            $return_array['History']  = $history['History'];
        } else
            $return_array['History']  = $history;
        $this->response($return_array);
    }

    /**
     * Get videos
     * Added at: 06-05-2019
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function fetch_online_videos()
    {
        // Check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('calendar/my_events', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // Check if request method is not GET
        // and user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'dashboard', 'my_events'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        //
        $company_id = $data['session']['company_detail']['sid'];
        $employee_id = $data['session']['employer_detail']['sid'];
        // fetch company employers
        $videos = $this->calendar_model->fetch_online_videos(
            $company_id,
            $employee_id
        );
        if (!$videos) {
            $return_array['Response'] = 'no videos found.';
            $this->response($return_array);
        }

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'success';
        $return_array['Videos']  = $videos;
        $this->response($return_array);
    }

    /**
     * Set training center in LC
     * Added at: 07-05-2019
     *
     * @param $company_id Interger
     * @param $event_sid Interger
     * @param $event_add_post Array
     * @param $mode String
     *
     * @return VOID
     */
    private function set_training_session(
        $company_sid,
        $event_sid,
        $event_add_post,
        $mode = 'save'
    ) {

        // Reset date/times from UTC to server time
        //reset_event_datetime($event_add_post, $this, true);
        $array = array();
        $array['company_sid'] = $company_sid;
        $array['created_by']  = $event_add_post['employee_sid'];
        $array['session_topic'] = $event_add_post['title'];
        if (isset($event_add_post['description']))
            $array['session_description'] = $event_add_post['description'];
        $array['session_status'] = 'pending';
        $array['portal_schedule_event_details'] = serialize(array($event_sid => 'training-session'));
        $array['session_location']    = $event_add_post['address'];
        $array['session_date']        = DateTime::createFromFormat('Y-m-d', $event_add_post['date'])->format('Y-m-d');
        $array['session_start_time']  = DateTime::createFromFormat('H:iA', $event_add_post['eventstarttime'])->format('H:i:s');
        $array['session_end_time']    = DateTime::createFromFormat('H:iA', $event_add_post['eventendtime'])->format('H:i:s');
        $array['employees_assigned_to'] = isset($event_add_post['interviewer_type']) ? $event_add_post['interviewer_type'] : 'all';
        $array['applicants_assigned_to'] = 'specific';

        $array['online_video_sid'] = NULL;
        $video_sid_arr = array();
        if (isset($event_add_post['video_ids']) && !is_null($event_add_post['video_ids']) && $event_add_post['video_ids'] != '') {
            $video_sid_arr = explode(',', $event_add_post['video_ids']);
            $array['online_video_sid'] = $event_add_post['video_ids'];
        }

        //
        $interviewer_list = explode(',', $event_add_post['interviewer']);
        //
        $external_participants = @json_decode($event_add_post['external_participants'], true);
        //
        if ($mode != 'update')
            $session_sid = $this->calendar_model->insert_learning_center_training_session($array);
        else {
            $session_sid = $event_add_post['lcts'];
            if ($session_sid == '')
                $session_sid = $this->calendar_model->insert_learning_center_training_session($array);
            $this->calendar_model->update_learning_center_training_session($session_sid, $array);
            $last_active_assignments = $this->calendar_model->get_last_active_training_session_assignments($session_sid);
            $this->calendar_model->set_training_session_assignment_status($session_sid);
        }

        // echo sizeof($interviewer_list);
        // echo '<pre>';
        // print_r($event_add_post);
        // die('love');

        // For employees
        if (sizeof($interviewer_list) && $event_add_post['training_session_type'] != 'none') {
            // Check for current login employee id
            if (!in_array($event_add_post['employee_sid'], $interviewer_list)) {
                $interviewer_list[] = $event_add_post['employee_sid'];
            }

            foreach ($interviewer_list as $sid) {
                if ($mode == 'update')
                    $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'employee', $sid);
                $array = array();
                $array['training_session_sid'] = $session_sid;
                $array['user_type'] = 'employee';
                $array['user_sid'] = $sid;
                $array['date_assigned'] = date('Y-m-d H:i:s');
                $array['status'] = 1;

                if (isset($last_active_assignment) && sizeof($last_active_assignment)) {
                    $array['attended'] = $last_active_assignment['attended'];
                    $array['date_attended'] = $last_active_assignment['date_attended'];
                    $array['attend_status'] = $last_active_assignment['attend_status'];
                }

                $this->calendar_model->check_and_assigned_video_record($video_sid_arr, 'employee', $sid, $company_sid); //Add Videos Record for this training session (if video assigned)

                $this->calendar_model->insert_learning_center_training_sessions_assignments($array);
            }
        }

        // Drop all non-employee
        $this->calendar_model->drop_all_non_employee_from_lc($session_sid);
        if ($external_participants[0]['name'] != '') {
            foreach ($external_participants as $key => $value) {
                $array = array();
                $array['training_session_sid'] = $session_sid;
                $array['user_type'] = 'non-employee';
                $array['user_sid'] = 0;
                $array['date_assigned'] = date('Y-m-d H:i:s');
                $array['status'] = 1;
                $array['txt_name'] = $value['name'];
                $array['email_address'] = $value['email'];
                $this->calendar_model->insert_learning_center_training_sessions_assignments($array);
            }
        }

        // Update session id in event
        $this->calendar_model->update_event_columns_by_id(
            $event_sid,
            array('learning_center_training_sessions' => $session_sid)
        );
    }


    /**
     * Get assignement by employee id
     * Added at: 07-05-2019
     *
     * @param $assignments Array
     * @param $user_type String
     * @param $user_sid Integer
     *
     * @return Array
     */
    private function get_assignment_record($assignments, $user_type, $user_sid)
    {
        if (!sizeof($assignments)) return array();
        foreach ($assignments as $assignment) {
            if ($assignment['user_type'] == $user_type && $assignment['user_sid'] == $user_sid)
                return $assignment;
        }

        return array();
    }

    /**
     * Reschedule training session
     * Created on: 10-05-2019
     *
     * @accept POST
     *
     * @return JSON
     */
    function reschedule_training_session()
    {
        if (!$this->input->is_ajax_request()) redirect();
        //
        $return_array = array();
        $return_array['Redirect'] = TRUE;
        $return_array['Status'] = FALSE;
        //
        if ($this->input->method() != 'post') {
            $return_array['Response'] = 'Invalid request.';
            $this->send_response($return_array);
        }

        //
        if (
            !$this->input->post('lcid') ||
            !$this->input->post('event_date') ||
            !$this->input->post('event_start_time') ||
            !$this->input->post('event_end_time')
        ) {
            $return_array['Response'] = 'Key is missing from request.';
            $this->send_response($return_array);
        }

        $return_array['Redirect'] = FALSE;

        // Fetch the event
        $event_row = $this->calendar_model->fetch_ts_event_row_by_lcid($this->input->post('lcid'));

        if (!$event_row) {
            $return_array['Response'] = 'Oops! something went wrong.';
            $this->send_response($return_array);
        }
        //
        $event_date = DateTime::createFromFormat('m-d-Y', $this->input->post('event_date'))->format('Y-m-d');
        $event_start_time = $this->input->post('event_start_time');
        $event_end_time   = $this->input->post('event_end_time');

        unset($event_row['learning_center_training_sessions']['sid']);
        $event_row['learning_center_training_sessions']['session_status'] = 'pending';
        $event_row['learning_center_training_sessions']['session_date']   = $event_date;
        $event_row['learning_center_training_sessions']['session_start_time'] = DateTime::createFromFormat('H:iA', $event_start_time)->format('H:i:s');
        $event_row['learning_center_training_sessions']['session_end_time']   = DateTime::createFromFormat('H:iA', $event_end_time)->format('H:i:s');
        // Start the transaction
        $this->calendar_model->trans('trans_begin');
        // Add the LC
        $training_session_sid = $this->calendar_model->_insert(
            'learning_center_training_sessions',
            $event_row['learning_center_training_sessions']
        );

        if (!$training_session_sid) {
            $this->calendar_model->trans('trans_rollback');
            $return_array['Response'] = 'Oops! something went wrong.';
            $this->send_response($return_array);
        }
        // Reset assignments
        foreach ($event_row['learning_center_training_sessions_assignments'] as $k0 => $v0) {
            $v0['training_session_sid'] = $training_session_sid;
            $v0['attended'] = 0;
            $v0['date_attended'] = NULL;
            $v0['attend_status'] = 'pending';
            $v0['attend_status_date'] = NULL;
            unset($v0['sid']);

            // Add the LC assignments
            $this->calendar_model->_insert(
                'learning_center_training_sessions_assignments',
                $v0
            );
        }

        // Reset event
        $event_row['event_details']['parent_sid'] = $event_row['event_details']['sid'];
        $company_name = $event_row['event_details']['company_name'];
        unset($event_row['event_details']['sid'], $event_row['event_details']['company_name']);
        $event_row['event_details']['event_status'] = 'pending';
        $event_row['event_details']['date'] = $event_date;
        $event_row['event_details']['eventstarttime'] = $event_start_time;
        $event_row['event_details']['eventendtime'] = $event_end_time;
        $event_row['event_details']['learning_center_training_sessions'] = $training_session_sid;

        // Add it to event
        $event_sid =
            $this->calendar_model->_insert(
                'portal_schedule_event',
                $event_row['event_details']
            );

        //
        if (!$event_sid) {
            $this->calendar_model->trans('trans_rollback');
            $return_array['Response'] = 'Oops! something went wrong.';
            $this->send_response($return_array);
        }

        // Add external particpants
        if (sizeof($event_row['event_external_participants'])) {
            foreach ($event_row['event_external_participants'] as $k0 => $v0) {
                unset($v0['sid']);
                $v0['event_sid'] = $event_sid;
                $v0['company_sid'] = $event_row['event_details']['companys_sid'];
                $v0['employer_sid'] = $event_row['event_details']['employers_sid'];
                $this->calendar_model->_insert(
                    'portal_schedule_event_external_participants',
                    $v0
                );
            }
        }

        $this->calendar_model->trans('trans_commit');
        //

        ics_files(
            $event_sid,
            $event_row['event_details']['companys_sid'],
            array('CompanyName' => $company_name),
            'save_event'
        );

        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Event is rescheduled.';
        $this->send_response($return_array);
    }

    /**
     * Send JSON
     *
     */
    private function send_response($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
        exit(0);
    }

    /**
     * Records event changes
     * Created on: 17-06-2019
     *
     * @param $event_sid Integer
     * @param $user_id Integer
     * @param $company_sid Integer
     * @param $difference_array Array Optional
     *
     * @return Bool|Integer
     *
     */
    private function handle_event_changes(
        $event_sid,
        $user_id,
        $company_sid,
        $difference_array = array()
    ) {
        // Create an Insert array
        $data_array = array(
            'event_sid' => $event_sid,
            'user_id' => $user_id,
            'company_sid' => $company_sid,
            'ip_address' => $this->input->ip_address(),
            'details' => @json_encode($difference_array)
        );
        // Insert data
        return $this->calendar_model->_insert('portal_schedule_event_history', $data_array);
    }

    public function insert_event_timezones($key)
    {
        if ($key = "wercxviuweijdiwerskmckjwirm24234354jksfirsdfq3497qr4jsdkfhasdfq8widruqwiqejfqkhswer") {
            $this->db->select('sid');
            $this->db->select('timezone');
            $this->db->where('parent_sid', 0);
            $this->db->order_by('sid', 'DESC');

            $records_obj = $this->db->get('users');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            foreach ($records_arr as $company) {
                $timezone = STORE_DEFAULT_TIMEZONE_ABBR;
                if (!empty($company['timezone']))
                    $timezone = $company['timezone'];
                $this->db->update(
                    'portal_schedule_event',
                    ['event_timezone' => $timezone],
                    ['companys_sid' => $company['sid']]
                );
            }
            echo "Companies Timezones updated successfully in table 'portal_schedule_event'";
        }
    }

    public function handle_short_link($short_link)
    {
        if (!empty($short_link) && $short_link != NULL) {
            $this->db->where('short_link', $short_link);
            $result = $this->db->get('short_links')->result_array();
            if (sizeof($result)) {
                redirect($result[0]['redirect_link']);
            } else {
                redirect(base_url('login'));
            }
        } else {
            redirect(base_url('login'));
        }
    }
}
