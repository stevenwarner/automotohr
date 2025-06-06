<?php defined('BASEPATH') or exit('No direct script access allowed');
//$memory_limit = ini_get('memory_limit');
//echo $memory_limit; 
ini_set("memory_limit", "1024M");
//$memory_limit = ini_get('memory_limit');
//echo '<hr>'. $memory_limit; exit;
class Calendar extends Admin_Controller
{
    private $limit;
    private $list_size;
    // Set default response array
    private $resp = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/dashboard_model');
        $this->load->model('manage_admin/users_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // Set default response array
        $this->resp = array('Status' => false, 'Response' => 'Invalid request');
        $this->config->load('calendar_config');
        $this->limit     = $this->config->item('calendar_opt')['calendar_history_limit'];
        $this->list_size = $this->config->item('calendar_opt')['calendar_history_list_size'];
    }




    /**
     * Calendar
     * Created on: 16-05-2019
     * 
     * @return VOID
     */
    function index()
    {
        // Sort array in ascendng order
        if (!function_exists('ascending_sort_by_fullname')) {
            function ascending_sort_by_fullname($a, $b)
            {
                return $a['full_name'] > $b['full_name'];
            }
        }
        // Set admin id
        $this->data['admin_id'] = $this->session->userdata('user_id');
        // Set scurity details
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->data['admin_id']);
        // Fetch administrator users
        $this->data['super_admins'] = $this->dashboard_model->get_admins();
        // Fetch users and merge them
        $this->data['users'] = array_merge(
            // Get demo users
            $this->dashboard_model->get_demo_users(),
            // Get affiliate users
            $this->dashboard_model->get_affiliate_users(),
            // Get affiliate referred users
            $this->dashboard_model->get_affiliate_users(1),
            // Get affiliate referred clients
            $this->dashboard_model->get_affiliate_clients(1)
        );
        // Sort data in ascending order
        usort($this->data['users'], 'ascending_sort_by_fullname');
        //
        $this->render('manage_admin/calendar/events');
    }



    /**
     * Process admin calendar events
     * Created on: 12-06-2019
     *
     * @accepts POST 
     *
     * @return JSON
     */
    function process_event()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request() || $this->input->method(FALSE) != 'post') {
            _e('Invalid request', true);
            exit(0);
        }
        // Set admin id
        $admin_id = $this->session->userdata('user_id');
        // Set default event sid 
        $event_sid = NULL;
        // Set insert array
        $ins = array();
        // Set post
        $post = $this->input->post(NULL, TRUE);
        // Check the fields
        // Set insert array
        // Filter and reset fields
        if ($post['action'] != 'expired_reschedule')
            $this->set_ins_array($post, $ins);
        // _e($post, true);
        // _e($ins, true, true);
        // Add data to db
        if ($post['action'] == 'send_reminder_emails') {
            //
            $event = $this->dashboard_model->event_detail($post['event_sid']);
            // Generate ICS file
            $event['ics_file'] = generate_admin_ics_file($event, false);
            // Genrate email and send it
            send_admin_calendar_email_template($event, 'reminder_email', $post['emails']);

            $this->resp['Status'] = true;
            $this->resp['Response'] = 'Reminder emails sent.';
            $this->response();
        } else if ($post['action'] == 'save') {
            $event_sid = $this->dashboard_model->_q('admin_events', $ins);
        } else if (($post['action'] == 'update' || $post['action'] == 'drag_update')) {
            $event_sid = $post['event_sid'];
            $this->dashboard_model->_q('admin_events', $ins, array('sid' => $event_sid), 'update');
        } else if ($post['action'] == 'expired_reschedule') {
            $event_sid = $post['event_sid'];
            $new_event_details = $old_event_details = $this->dashboard_model->get_old_event_details($event_sid);
            //
            unset($new_event_details['sid'], $new_event_details['created_at'], $new_event_details['updated_at'], $new_event_details['external_participants']);
            //
            $new_event_details['parent_event_sid'] = $event_sid;
            $new_event_details['event_date'] = $post['reschedule_date'];
            $new_event_details['event_start_time'] = $post['reschedule_start_time'];
            $new_event_details['event_end_time'] = $post['reschedule_end_time'];
            $new_event_details['creator_sid'] = $this->session->userdata('user_id');
            // Insert new row into database
            $event_sid = $this->dashboard_model->_q('admin_events', $new_event_details);

            // Check for participants
            if (isset($old_event_details['external_participants']) && sizeof($old_event_details['external_participants'])) {
                foreach ($old_event_details['external_participants'] as $k0 => $v0) {
                    //
                    $v0['event_sid'] = $event_sid;
                    // Insert external participants
                    $this->dashboard_model->_q('admin_event_extra_participants', $v0);
                }
            }
        } else if ($post['action'] == 'cancel') {
            $event_sid = $post['event_sid'];
            $this->dashboard_model->_q(
                'admin_events',
                array('event_status' => 'cancelled'),
                array('sid' => $event_sid),
                'update'
            );
            $this->resp['Response'] = 'Event cancelled!';
        } else if ($post['action'] == 'reschedule') {
            $event_sid = $post['event_sid'];
            $this->dashboard_model->_q(
                'admin_events',
                array('event_status' => $post['status']),
                array('sid' => $event_sid),
                'update'
            );
            $db_date = $post['reschedule_date'];
            // $bk_date = $post['reschedule_date'];
            $bk_date = DateTime::createFromFormat('Y-m-d', $post['reschedule_date'])->format('F j, Y');
            // Update email sent flag
            if (strtotime('now') >= strtotime($db_date . ' 23:59:59')) {
                $this->dashboard_model->_q(
                    'admin_events',
                    array('reminder_sent_flag' => 1),
                    array('sid' => $event_sid),
                    'update'
                );
            }
            $this->resp['Response'] = 'Event Rescheduled for ' . $bk_date . '.';
        } else if ($post['action'] == 'delete') {
            $event_sid = $post['event_sid'];
            // Generate email and send it
            if ($post['event_cancel_email'] == "yes") {
                $event = $this->dashboard_model->event_detail($event_sid);
                $event['event_status'] = 'cancelled';
                $event['ics_file'] = null;

                send_admin_calendar_email_template($event, "cancel");
            }

            $this->dashboard_model->_q(
                'admin_event_extra_participants',
                array('event_sid' => $event_sid),
                false,
                'delete'
            );
            $this->dashboard_model->_q(
                'admin_event_history',
                array('event_sid' => $event_sid),
                false,
                'delete'
            );
            $this->dashboard_model->_q(
                'admin_event_reminder_email_history',
                array('event_sid' => $event_sid),
                false,
                'delete'
            );
            $this->dashboard_model->_q(
                'admin_events',
                array('sid' => $event_sid),
                false,
                'delete'
            );
            $this->resp['Response'] = 'Event deleted!';
        } else if ($post['action'] == 'reminder_email_history') {
            // Fetch company employers
            $history = $this->dashboard_model->get_reminder_email_history(
                $post['event_sid'],
                $post['current_page'],
                $this->limit
            );
            if (!$history) {
                $this->resp['Response'] = 'no history found.';
                $this->response();
            }

            $this->resp['Status'] = TRUE;
            $this->resp['Response'] = 'success';
            $this->resp['Limit']    = $this->limit;
            $this->resp['ListSize'] = $this->list_size;
            if (isset($history['Count'])) {
                $this->resp['Total']    = $history['Count'];
                $this->resp['History']  = $history['History'];
            } else
                $this->resp['History']  = $history;
            $this->response();
        } else if ($post['action'] == 'status_history') {
            $event_sid = $post['event_sid'];
            $current_page = $post['current_page'];
            // fetch company employers
            $history = $this->dashboard_model->get_event_availablity_requests(
                $event_sid,
                $current_page,
                $this->limit
            );
            if (!$history) {
                $this->resp['Response'] = 'no history found.';
                $this->response();
            }

            // update all requests status to 
            $this->dashboard_model->_q('admin_event_history', array('status' => '1'),  array('event_sid' => $event_sid), 'update');

            $this->resp['Status'] = TRUE;
            $this->resp['Response'] = 'success';
            $this->resp['Limit']    = $this->limit;
            $this->resp['ListSize'] = $this->list_size;
            if (isset($history['Count'])) {
                $this->resp['Total']    = $history['Count'];
                $this->resp['History']  = $history['History'];
            } else
                $this->resp['History']  = $history;
            $this->response();
        }
        // Check for default event sid
        if ($event_sid == NULL) {
            $this->resp['Response'] = 'Oops! Something went wrong while processing event. Please, try again in a few moments.';
            $this->response();
        }

        // Only reset external users in case of
        // save, update
        if (in_array($post['action'], array('save', 'update'))) {
            // Delete previous external participants
            $this->dashboard_model->_q(
                'admin_event_extra_participants',
                array(
                    'event_sid' => $event_sid,
                    'external_type' => 'participant'
                ),
                false,
                'delete'
            );
            if (isset($post['external_participants'])) {
                // Set external participants
                $external_participants = is_array($post['external_participants']) ? $post['external_participants'] : @json_decode($post['external_participants'], true);
                if (sizeof($external_participants) && $external_participants[0]['name'] != '') {
                    foreach ($external_participants as $k0 => $v0) {
                        $this->dashboard_model->_q(
                            'admin_event_extra_participants',
                            array(
                                'event_sid' => $event_sid,
                                'external_type' => 'participant',
                                'external_participant_name' => ucwords($v0['name']),
                                'external_participant_email' => strtolower($v0['email']),
                                'show_email' => (int)$v0['show_email']
                            )
                        );
                    }
                }
            }

            // Check for external users
            if ($post['event_type'] == 'demo' || $post['event_type'] == 'super admin') {
                // Delete previous external users
                $this->dashboard_model->_q(
                    'admin_event_extra_participants',
                    array(
                        'event_sid' => $event_sid,
                        'external_type' => 'user'
                    ),
                    false,
                    'delete'
                );
                // Set external participants
                $external_user_array = is_array($post['external_user_array']) ? $post['external_user_array'] : @json_decode($post['external_user_array'], true);
                if (sizeof($external_user_array) && $external_user_array[0]['name'] != '') {
                    foreach ($external_user_array as $k0 => $v0) {
                        $this->dashboard_model->_q(
                            'admin_event_extra_participants',
                            array(
                                'event_sid' => $event_sid,
                                'external_type' => 'user',
                                'external_participant_name' => ucwords($v0['name']),
                                'external_participant_email' => strtolower($v0['email']),
                                'show_email' => isset($v0['show_email']) ? $v0['show_email'] : 0
                            )
                        );
                    }
                }
            }
        }

        //
        $this->resp['Status'] = true;

        if ($post['action'] == 'expired_reschedule')
            $this->resp['Response'] = 'Event is scheduled for ' . DateTime::createFromFormat('Y-m-d', $post['reschedule_date'])->format('F j, Y');
        else if ($post['action'] == 'save' || $post['action'] == 'update' || $post['action'] == 'drag_update')
            $this->resp['Response'] = 'Event is scheduled for ' . DateTime::createFromFormat('Y-m-d', $ins['event_date'])->format('F j, Y');

        $this->resp['EventCode'] = $event_sid;

        if ($post['action'] != 'delete') {
            $event = $this->dashboard_model->event_detail($event_sid);
            //
            $event['diff_array'] = isset($post['diff']) ? json_decode($post['diff'], true) : array();
            if (sizeof($event['diff_array'])) $this->handle_event_changes($event_sid, $admin_id, $event['diff_array']);
            // Generate ICS file
            $event['ics_file'] = generate_admin_ics_file($event, in_array($post['action'], array('save', 'expired_reschedule')) ? false : true);
            // Genrate email and send it
            send_admin_calendar_email_template($event, in_array($post['action'], array('update')) ? 'update' : $post['action']);
        }
        //
        $this->response();
    }


    /**
     * Sends back JSON
     * Created on: 12-06-2019
     * 
     * @param $resp Array
     *
     * @return VOID
     */
    private function response($resp = array())
    {
        // Set json header for response
        header('Content-Type: application/json');
        // Send JSON as response
        echo json_encode($this->resp);
        // KIll executionbs
        exit(0);
    }


    /**
     * Sets insert array
     * Created on: 12-06-2019
     *
     * @param $post Array
     * @param &$ins Array
     *
     * @return VOID
     */
    private function set_ins_array($post, &$ins)
    {
        // Check for user details
        if ($post['action'] == 'save') {
            $ins['creator_sid'] = $post['creator_sid'];
            $ins['event_status'] = 'pending';
        }
        // Set event title
        if (isset($post['event_title']))
            $ins['event_title'] = $post['event_title'];
        // Set event date;
        if (isset($post['event_date_bk']))
            $ins['event_date']  = $post['event_date_bk'];
        // Set event start time
        if (isset($post['event_start_time'])) {
            $ins['event_start_time'] = $post['event_start_time'];
        }
        // Set event end time
        if (isset($post['event_end_time']))
            $ins['event_end_time']   = $post['event_end_time'];
        // Set event type
        if (isset($post['event_type']))
            $ins['event_type']       = $post['event_type'];
        // Set event category
        if (isset($post['event_category']))
            $ins['event_category']   = $post['event_category'];

        // Set meeting details
        if (isset($post['meeting_id'], $post['meeting_phone'], $post['meeting_url']))
            if ($post['meeting_id'] != '' && $post['meeting_phone'] != '' && $post['meeting_url'] != '') {
                $ins['meeting_id'] = $post['meeting_id'];
                $ins['meeting_phone'] = $post['meeting_phone'];
                $ins['meeting_url']   = $post['meeting_url'];
            }
        // Set Participants
        if (isset($post['participants']))
            if ($post['participants'] != '') $ins['participants'] = $post['participants'];
        // Set Participants emails list
        if (isset($post['participants_show_email']))
            if ($post['participants_show_email'] != '') $ins['participants_show_email_list'] = $post['participants_show_email'];
        // Set comment
        if (isset($post['comment']))
            if ($post['comment'] != '') $ins['comment'] = $post['comment'];
        // Set reminder check & duration
        if (isset($post['reminder_check'], $post['reminder_duration']))
            if ($post['reminder_check'] != 0) {
                $ins['reminder_check'] = 1;
                $ins['reminder_duration'] = $post['reminder_duration'];
            }

        // Set user id
        if (isset($post['user_id']))
            if ($post['user_id'] != '') $ins['user_id'] = $post['user_id'];
        // Set user name
        if (isset($post['user_name']))
            if ($post['user_name'] != '') $ins['user_name'] = $post['user_name'];
        // Set user phone
        if (isset($post['user_phone']))
            if ($post['user_phone'] != '') $ins['user_phone'] = $post['user_phone'];
        // Set user email
        if (isset($post['user_email']))
            $ins['user_email'] = $post['user_email'];
        // Set user type
        if (isset($post['user_type']))
            if ($post['user_type'] != '') $ins['user_type']   = $post['user_type'];
        // Set address
        if (isset($post['event_address']))
            if ($post['event_address'] != '') $ins['event_address']   = $post['event_address'];

        // Set external users and system users
        if (isset($post['event_type']) && ($post['event_type'] == 'demo' || $post['event_type'] == 'super admin')) {
            $ins['user_type'] = 'external';
            $ins['user_id'] = @json_encode($post['user_ids']);
        }

        // $ins['parent_event_sid'] = 0;
        // $ins['reminder_sent_flag'] = 0;
        // $ins['external_link_sid'] = 0;
        // $ins['is_recur'] = 0;
        // $ins['recur_type'] = 0;
        // $ins['recur_start_date'] = 0;
        // $ins['recur_end_date'] = 0;
        // $ins['recur_list'] = 0;

        // // Decode the json object
        // $recurr = json_decode($event_add_post['recur'], true);
        // // Check if recurr event is set
        // if(sizeof($recurr)){
        //     $event_data['is_recur'] = 1;
        //     $event_data['recur_type'] = $recurr['recur_type'];
        //     $event_data['recur_start_date'] = $recurr['recur_start_date'];
        //     if($recurr['recur_end_date'] != '')
        //         $event_data['recur_end_date'] = $recurr['recur_end_date'];
        //     $event_data['recur_list'] = @json_encode($recurr['list']);
        // }

        // _e($ins, true);
    }


    /**
     * Get events for admin calendar
     * Created on: 12-06-2019
     * 
     * @return JSON 
     *
     */
    function get_events()
    {
        if (!$this->input->is_ajax_request() || !$this->input->method(false) == 'post') {
            _e('Invalid request');
            exit(0);
        }
        $post = $this->input->post(NULL, TRUE);

        $event_type = 'all';
        $event_type = 'own';
        // check for view type
        $events = $this->dashboard_model->get_events(
            $post['type'],
            $post['year'],
            $post['month'],
            $post['day'],
            $post['week_start'],
            $post['week_end'],
            $post['admin_id'],
            $event_type
        );


        //      
        $startdate = $post['start_date'];
        $enddate = $post['end_date'];
        $eventsScheduled = $this->dashboard_model->get_Scheduled_events(
            $startdate,
            $enddate
        );

        $events = array_merge(!is_array($events) ? array() : $events, $eventsScheduled);

        //
        if (!$events) {
            $this->resp['Response'] = 'No events found';
            $this->response();
        }

        $this->resp['Status'] = TRUE;
        $this->resp['Response'] = 'Success';
        $this->resp['Events'] = $events;
        $this->response();
    }


    /**
     * get events from events
     * added at: 28-03-2019
     *
     * @param $event_sid Integer
     *
     * @return JSON
     *
     */
    function event_detail($event_sid)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request() || !$this->input->method(false) == 'post') {
            _e('Invalid request');
            exit(0);
        }
        $post = $this->input->post(NULL, TRUE);

        // check for view type
        $event = $this->dashboard_model->event_detail($event_sid);
        //
        if (!$event) {
            $this->resp['Response'] = 'No event found';
            $this->response($this->resp);
        }
        //
        $this->resp['Status'] = TRUE;
        $this->resp['Response'] = 'Success';
        $this->resp['Event'] = $event;
        //
        $this->response();
    }


    /**
     * Records event changes
     * Created on: 24-06-2019
     *
     * @param $event_sid Integer
     * @param $admin_id Integer
     * @param $difference_array Array Optional
     *
     * @return Bool|Integer
     *
     */
    private function handle_event_changes(
        $event_sid,
        $admin_id,
        $difference_array = array()
    ) {
        if (!sizeof($difference_array)) return false;
        // Create an Insert array
        $data_array = array(
            'event_sid' => $event_sid,
            'admin_id' => $admin_id,
            'ip_address' => $this->input->ip_address(),
            'details' => @json_encode($difference_array)
        );
        // Insert data
        return $this->dashboard_model->_q('admin_event_change_history', $data_array);
    }
}
