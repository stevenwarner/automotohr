<?php 
// Added on: 24-04-2019
$config['calendar_opt'] = array();
$config['calendar_opt']['event_types'] = array(
	'call'=>'Call',
	'email'=>'Email',
	'gotomeeting'=>'GoToMeeting',
	'interview'=>'In-person interview',
	'meeting'=>'Meeting',
	'other'=>'Other',
	'interview-phone'=>'Phone interview',
	'training-session'=>'Training Session',
	'interview-voip'=>'VOIP interview',
	'timeoff'=>'Time-off Approved',
	'timeoff_pending'=>'Time-off Pending',
	'holiday'=>'Holidays'
);
//
$config['calendar_opt']['event_status'] = array(
	'confirmed'=>'Confirmed',
	'cancelled'=>'Cancelled',
	'pending'=>'Pending',
	'rescheduled'=>'Rescheduled'
);

// $config['calendar_opt']['event_status'] = array('confirmed' => 'Confirmed', 'cancelled'=>'Cancelled','expired'=>'Expired');
$config['calendar_opt']['show_recur_btn'] = 0;
$config['calendar_opt']['recur_active'] = 0;

$config['calendar_opt']['show_new_calendar_to_all'] = TRUE;
$config['calendar_opt']['old_event_check'] = TRUE;
$config['calendar_opt']['ids_check'] = TRUE;
$config['calendar_opt']['remote_ips'] = array();
$config['calendar_opt']['allowed_ids'] = array(57, 51, 5089);
$config['calendar_opt']['calendar_history_limit'] = 20;
$config['calendar_opt']['calendar_history_list_size'] = 5;

$config['calendar_opt']['event_type_info'] = 
array(
    'applicant' => array('interview', 'interview-voip', 'interview-phone', 'call', 'email'),
    'employee' => array('meeting', 'training-session', 'other', 'call', 'email'),
    'personal' => array('call', 'training-session', 'other', 'email', 'meeting'),
    'superadmin' => array('meeting', 'training-session', 'other', 'call', 'email', 'gotomeeting'),
    'admin_personal' => array('call', 'training-session', 'other', 'email', 'meeting', 'gotomeeting'),
    'demo' => array('demo', 'gotomeeting'),
    'default_applicant' => 'interview',
    'default_employee' => 'meeting',
    'default_superadmin' => 'call',
    'default_demo' => 'demo',
    'default_personal' => 'call'
);
$config['calendar_opt']['recur_days'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$config['calendar_opt']['default_start_time'] = '08:00AM';
$config['calendar_opt']['default_end_time'] = '08:00PM';
$config['calendar_opt']['time_duration'] = 5;
$config['calendar_opt']['show_online_videos'] = 0;