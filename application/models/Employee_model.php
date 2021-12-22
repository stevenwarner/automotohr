<?php class employee_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_active_employees_detail($parent_sid, $sid, $keyword = null, $archive = 0, $order_by = 'sid', $order = 'DESC') {
        $keyword = trim($keyword);
        $this->db->select('*');
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('active', '1');
        $this->db->where('terminated_status', 0);
        $this->db->where('archived', $archive);
        //$this->db->where('is_executive_admin', 0);
        if ($keyword != null) {
            $tK = preg_replace('/\s+/', '|', strtolower(trim($keyword)));
            $this->db->where("(lower(first_name) regexp '".($tK)."' or lower(last_name) regexp '".( $tK )."' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ", false, false);
            // $this->db->where("(first_name LIKE '%" . $keyword . "%' or last_name LIKE '%" . $keyword . "%' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ");
        }

        $this->db->where('sid != ' . $sid);
        $this->db->order_by($order_by, $order);
        $all_employees = $this->db->get('users')->result_array();
        $all_employees = $this->verify_executive_admin_status($all_employees);
        return $all_employees;
    }

    function get_inactive_employees_detail($parent_sid, $sid, $keyword = null, $archive = 0, $order_by = 'sid', $order = 'DESC') {
        $keyword = trim($keyword);
        $this->db->select('*');
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('active', '0');
        $this->db->where('terminated_status', 0);
        //$this->db->where('is_executive_admin', 0);
        $this->db->where('archived', $archive);
        if ($keyword != null) {
            $this->db->where("(lower(concat(first_name,'',last_name)) LIKE '%".(preg_replace('/\s+/', '', strtolower($keyword)))."%' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ");
            // $this->db->where("(first_name LIKE '%" . $keyword . "%' or last_name LIKE '%" . $keyword . "%' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ");
        }
        $this->db->where('sid != ' . $sid);
        $this->db->order_by($order_by, $order);
        $all_employees = $this->db->get('users')->result_array();
        $all_employees = $this->verify_executive_admin_status($all_employees);
        return $all_employees;
    }

    function get_terminated_employees_detail($parent_sid, $sid, $keyword = null, $archive = 0, $orderType = 'users.sid', $order="DESC") {
        $keyword = trim($keyword);
        $this->db->select('users.*, terminated_employees.termination_date, terminated_employees.details as terminated_reason');
        $this->db->where('users.parent_sid', $parent_sid);
        $this->db->where('users.terminated_status', 1);
        if ($keyword != null) {
            $this->db->where("(lower(concat(first_name,'',last_name)) LIKE '%".(preg_replace('/\s+/', '', strtolower($keyword)))."%' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ");
        }
        $this->db->where('users.sid != ' . $sid);
        $this->db->order_by($orderType, $order);
        $this->db->join('terminated_employees', 'terminated_employees.employee_sid = users.sid', 'inner');
        $all_employees = $this->db->get('users')->result_array();
        $all_employees = $this->verify_executive_admin_status($all_employees);
        return $all_employees;
    }

    function get_all_company_employees_detail($parent_sid, $sid, $keyword = null, $archive = 0,  $order_by = 'sid', $order="DESC") {
        $keyword = trim($keyword);
        $this->db->select('users.*, terminated_employees.termination_date');
        $this->db->where('users.parent_sid', $parent_sid);
        if ($keyword != null) {
            $tK = preg_replace('/\s+/', '|', strtolower(trim($keyword)));
            $this->db->where("(lower(first_name) regexp '".($tK)."' or lower(last_name) regexp '".( $tK )."' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ", false, false);
            // $this->db->where("(first_name LIKE '%" . $keyword . "%' or last_name LIKE '%" . $keyword . "%' or username LIKE '%" . $keyword . "%' or email LIKE '" . $keyword . "')  ");
        }

        $this->db->where('users.sid != ' . $sid);
        $this->db->order_by($order_by, $order);
        $this->db->join('terminated_employees', 'terminated_employees.employee_sid = users.sid', 'left');
        $all_employees = $this->db->get('users')->result_array();
        $all_employees = $this->verify_executive_admin_status($all_employees);
        return $all_employees;
    }

    function verify_executive_admin_status($all_employees) {
        if(!empty($all_employees)){
            foreach($all_employees as $key => $data){
                //echo '<hr><br>In Key: '.$key.' => is_executive_admin: '.$data['is_executive_admin'];
                $is_executive_admin = $data['is_executive_admin'];

                if($is_executive_admin == 1){ // Check the status of Executive Admin.
                    $email = $data['email'];
                    $this->db->select('sid, active');
                    $this->db->where('email', $email);
                    $records_obj = $this->db->get('executive_users');
                    $records_arr = $records_obj->result_array();
                    $records_obj->free_result();

                    if(!empty($records_arr)) {
                        $active = $records_arr[0]['active'];

                        if($active == 0) { // ececutive admin is deactived. Remove it from employee list.
                            unset($all_employees[$key]);
                        }
                    }
                }
            //echo '<br>In Key: '.$key.' => is_executive_admin: '.$data['is_executive_admin'];
            }
            return $all_employees;
        }
        return array();
    }

    function delete_employee_by_id($id) {
        $this->db->select('applicant_sid');
        $this->db->where('sid', $id);
        $mydata = $this->db->get('users')->result_array();
        $applicant_sid = $mydata[0]['applicant_sid'];

        if (!empty($applicant_sid)) { // also delete employee info from applicant table
            // 1) clear emergency_contacts
            $this->db->where('users_sid', $applicant_sid)->where('users_type', 'employee')->delete('emergency_contacts');
            // 2) equipment_information
            $this->db->where('users_sid', $applicant_sid)->where('users_type', 'employee')->delete('equipment_information');
            // 3) dependant_information
            $this->db->where('users_sid', $applicant_sid)->where('users_type', 'employee')->delete('dependant_information');
            // 4) license_information
            $this->db->where('users_sid', $applicant_sid)->where('users_type', 'employee')->delete('license_information');
            // 5) background_check_orderss
            $this->db->where('users_sid', $applicant_sid)->where('users_type', 'employee')->delete('background_check_orders');
            // 6) portal_misc_notes
            $this->db->where('applicant_job_sid', $applicant_sid)->where('users_type', 'employee')->delete('portal_misc_notes');
            // 7) private_message
            $this->db->where('to_id', $applicant_sid)->where('users_type', 'employee')->delete('private_message');
            // 8) portal_applicant_rating
            $this->db->where('applicant_job_sid', $applicant_sid)->where('users_type', 'employee')->delete('portal_applicant_rating');
            // 9) calendar events - portal_schedule_event
            $this->db->where('applicant_job_sid', $applicant_sid)->where('users_type', 'employee')->delete('portal_schedule_event');
            // 10) portal_applicant_attachments
            $this->db->where('applicant_job_sid', $applicant_sid)->where('users_type', 'employee')->delete('portal_applicant_attachments');
            // 11) reference_checks
            $this->db->where('user_sid', $applicant_sid)->where('users_type', 'employee')->delete('reference_checks');
            // 12) background checks
            $this->db->where('user_sid', $applicant_sid)->where('users_type', 'employee')->delete('background_check_orders');
        }


        $this->db->where('sid', $id)->delete('users');
        $result = $this->db->affected_rows();

        if ($result > 0) { //$this->session->set_flashdata('message', '<b>Success: </b>Your colleage is permanently deleted from the system!');
            // 1) clear emergency_contacts
            $this->db->where('users_sid', $id)->where('users_type', 'employee')->delete('emergency_contacts');
            // 2) equipment_information
            $this->db->where('users_sid', $id)->where('users_type', 'employee')->delete('equipment_information');
            // 3) dependant_information
            $this->db->where('users_sid', $id)->where('users_type', 'employee')->delete('dependant_information');
            // 4) license_information
            $this->db->where('users_sid', $id)->where('users_type', 'employee')->delete('license_information');
            // 5) background_check_orderss
            $this->db->where('users_sid', $id)->where('users_type', 'employee')->delete('background_check_orders');
            // 6) portal_misc_notes
            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_misc_notes');
            // 7) private_message
            $this->db->where('to_id', $id)->where('users_type', 'employee')->delete('private_message');
            // 8) portal_applicant_rating
            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_applicant_rating');
            // 9) calendar events - portal_schedule_event
            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_schedule_event');
            // 10) portal_applicant_attachments
            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_applicant_attachments');
            // 11) reference_checks
            $this->db->where('user_sid', $id)->where('users_type', 'employee')->delete('reference_checks');
            // 12) background checks
            $this->db->where('user_sid', $id)->where('users_type', 'employee')->delete('background_check_orders');
//            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_schedule_event');
//            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_misc_notes');
//            $this->db->where('applicant_job_sid', $id)->where('users_type', 'employee')->delete('portal_applicant_rating');
            $data = 'Your Employee is permanently deleted from the system!';
        } else { //$this->session->set_flashdata('message', '<b>Failed: </b>Could not delete your colleague, Please try Again!');
            $data = 'Could not delete your Employee, Please try Again!';
        }

        return $data;
    }

    function revert_employee_back_to_applicant($user_sid,$applicant_sid) {
        $user_record = $this->db->select('*')->where('sid', $user_sid)->get('users')->result_array();
        if (sizeof($user_record) > 0) { //$this->session->set_flashdata('message', '<b>Success: </b>Your colleage is permanently deleted from the system!');


            $this->db->where('sid', $user_sid)->delete('users');
            $revert_record = array(
                'employee_sid' => $user_sid,
                'applicant_sid' => $applicant_sid,
                'employee_userTbl_row' => json_encode($user_record)
            );
            $this->db->insert('employee_revert_into_applicant',$revert_record);
            //Revert table is maintaining for the record of employees who got reverted

            // 1) clear emergency_contacts
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('emergency_contacts');
            // 2) equipment_information
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('equipment_information');
            // 3) dependant_information
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('dependant_information');
            // 4) license_information
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('license_information');
            // 5) background_check_orderss
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('background_check_orders');
            // 6) portal_misc_notes
//            $this->db->where('applicant_job_sid', $user_sid)->where('users_type', 'employee')->delete('portal_misc_notes');
            // 7) private_message
//            $this->db->where('to_id', $user_sid)->where('users_type', 'employee')->delete('private_message');
            // 8) portal_applicant_rating
//            $this->db->where('applicant_job_sid', $user_sid)->where('users_type', 'employee')->delete('portal_applicant_rating');
            // 9) calendar events - portal_schedule_event
//            $this->db->where('applicant_job_sid', $user_sid)->where('users_type', 'employee')->delete('portal_schedule_event');
            // 10) portal_applicant_attachments
//            $this->db->where('applicant_job_sid', $user_sid)->where('users_type', 'employee')->delete('portal_applicant_attachments');
            // 11) reference_checks
//            $this->db->where('user_sid', $user_sid)->where('users_type', 'employee')->delete('reference_checks');
            // 12) background checks
//            $this->db->where('users_sid', $user_sid)->where('users_type', 'employee')->delete('background_check_orders');
            $data = 'Your employee is reverted back to applicant!';
        } else { //$this->session->set_flashdata('message', '<b>Failed: </b>Could not delete your colleague, Please try Again!');
            $data = 'Could not revert your Employee, Please try Again!';
        }

        return $data;
    }

    function deactivate_employee_by_id($id) {
        $data_array = array('active' => 0, 'terminated_status' => 0);
        $this->db->where('sid', $id);
        $this->db->update('users', $data_array);
        $result = $this->db->affected_rows();
        if ($result > 0) { //$this->session->set_flashdata('message', '<b>Success: </b>Your colleage is deactivated!');
            $data = 'Your Employee / Team Member is deactivated!';
        } else { //$this->session->set_flashdata('message', '<b>Failed: </b>Could not deactivate your colleague, Please try Again!');
            $data = 'Could not deactivate your Employee / Team Member, Please try Again!';
        }
        return $data;
    }

    function activate_employee_by_id($id) {
        $data_array = array('active' => 1, 'terminated_status' => 0);
        $this->db->where('sid', $id);
        $this->db->update('users', $data_array);
        $result = $this->db->affected_rows();
        if ($result > 0) { //$this->session->set_flashdata('message', '<b>Success: </b>Your colleage is deactivated!');
            $data = 'Your Employee / Team Member is activated!';
        } else { //$this->session->set_flashdata('message', '<b>Failed: </b>Could not deactivate your colleague, Please try Again!');
            $data = 'Could not deactivate your Employee / Team Member, Please try Again!';
        }
        return $data;
    }

    function archive_employee_by_id($id, $data_array) {
        $this->db->where('sid', $id);
        $this->db->update('users', $data_array);
        $result = $this->db->affected_rows();
        if ($result > 0) { //$this->session->set_flashdata('message', '<b>Success: </b>Your colleage is deactivated!');
            $data = 'Success!';
        } else { //$this->session->set_flashdata('message', '<b>Failed: </b>Could not deactivate your colleague, Please try Again!');
            $data = 'Error, Please try Again!';
        }
        return $data;
    }

    function update_applicant_status($sid) {
        $data_array = array('hired_sid' => 'NULL', 'hired_status' => 0);
        $this->db->where('sid', $sid);
        $this->db->update('portal_job_applications', $data_array);
        $result = '';

        $this->db->select('hired_status');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $result = $records_arr[0]['hired_status'];
        }

        if ($result == 0) {
            $data = 'success';
        } else {
            $data = 'error';
        }
        return $data;
    }

    function add_subaccount($data) {
        $this->db->insert('users', $data);
        $result = $this->db->insert_id();
        $this->session->set_flashdata('message', '<b>Success: </b>Your Employee / Team Member has been added to the system. Please select HR Documents to send!.');
        return $result;
    }

    function get_user_detail($sid, $company_id) {
        return $this->db->get_where('users', array('sid' => $sid, 'parent_sid' => $company_id));
    }

    function get_hr_documents($company_id) {
        return $this->db->get_where('hr_documents', array('company_sid' => $company_id, 'onboarding' => 1, 'archive' => 1))->result_array();
    }

    function saveUserDocument($type, $dataToSave) { //checking if document already exist against particular Employee
//        $counter = $this->db->get_where('hr_user_document', array('receiver_sid' => $dataToSave['receiver_sid'], 'document_sid' => $dataToSave['document_sid'], 'document_type' => $type))->num_rows();

//        if ($counter == 0) {
//            $this->db->insert('hr_user_document', $dataToSave);
//        }

        $this->db->select('*');
        $this->db->select('sid as hr_user_document_sid');
        $this->db->where('receiver_sid', $dataToSave['receiver_sid']);
        $this->db->where('document_sid', $dataToSave['document_sid']);
        $this->db->where('document_type', $type);
        $records_obj = $this->db->get('hr_user_document');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(empty($records_arr)) {
            $this->db->insert('hr_user_document', $dataToSave);
        } else {
            $datatosaveinhistory = $records_arr['0'];
            $sidtodelete = $datatosaveinhistory['sid'];
            unset($datatosaveinhistory['sid']);

            $this->db->insert('hr_user_document_history', $datatosaveinhistory);
            $this->db->where('sid', $sidtodelete)->delete('hr_user_document');
            $result = $this->db->affected_rows();

            if($result) {
                $this->db->insert('hr_user_document', $dataToSave);
            }
        }

        $this->session->set_flashdata('message', '<b>Success: </b>HR Document(s) sent to the Employee!');
    }

    function getDocuments($documentsId) {
        return $this->db->where_in('sid', $documentsId)->get('hr_documents')->result_array();
    }

    function get_already_sent_documents($sid, $company_id) {
        return $this->db->select('document_sid')->get_where('hr_user_document', array('receiver_sid' => $sid, 'company_sid' => $company_id, 'document_type' => 'document'))->result_array();
    }

    function get_already_sent_offer_letters($sid, $company_id) {
        return $this->db->select('document_sid')->get_where('hr_user_document', array('receiver_sid' => $sid, 'company_sid' => $company_id, 'document_type' => 'offerletter'))->result_array();
    }

    function update_users($sid, $dataToUpdate) {
        $this->db->where('sid', $sid)->set($dataToUpdate)->update('users');
    }

    function get_offer_detail($offerLetterId) {
        return $this->db->get_where('offer_letter', array('sid' => $offerLetterId));
    }

    function check_random_string_exits($receiver_sid, $receiver_type) {
        $this->db->where('verification_key !=  ""');
        return $this->db->get_where('hr_user_document', array('receiver_sid' => $receiver_sid));
    }

    function getEmployeeNotes($employer_sid, $date = NULL) {
        $this->db->where('applicant_job_sid', $employer_sid);
        $this->db->where('users_type', 'employee');

        if($date != NULL){
            $this->db->where('insert_date > ', $date);
        }

        $this->db->order_by('sid', 'DESC');
        $result =  $this->db->get('portal_misc_notes')->result_array();
        return $result;
    }

    function employeeInsertNote($employers_sid, $applicant_job_sid, $applicant_email, $notes) {
        $now = date('Y-m-d H:i:s');
        $args = array('users_type' => 'employee', 'employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now);
        $this->db->insert('portal_misc_notes', $args);
    }

    function employeeUpdateNote($sid, $employers_sid, $applicant_job_sid, $applicant_email, $notes) {
        $now = date('Y-m-d H:i:s');
        $args = array('users_type' => 'employee', 'employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now);
        $this->db->where(array('sid' => $sid))->update('portal_misc_notes', $args);
    }

    function next_employee($employer_id, $company_id, $logged_in_employer_id) {
        $data = $this->db->query("SELECT `sid` FROM `users` WHERE sid > $employer_id and `parent_sid` = $company_id  and `sid` != $logged_in_employer_id   ORDER BY `sid` ASC LIMIT 1");
        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function previous_employee($employer_id, $company_id, $logged_in_employer_id) {
        $data = $this->db->query("SELECT `sid` FROM `users` WHERE sid < $employer_id and `parent_sid` = $company_id  and `sid` != $logged_in_employer_id ORDER BY `sid` DESC LIMIT 1");
        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function get_min_employee_id($company_id, $logged_in_employer_id) {
        $data = $this->db->query("SELECT MIN(sid) as sid FROM `users` where `parent_sid` = $company_id  and `sid` != $logged_in_employer_id ");
        $data = $data->result_array();
        return $data[0]['sid'];
    }

    function get_max_employee_id($company_id, $logged_in_employer_id) {
        $data = $this->db->query("SELECT MAX(sid) as sid FROM `users` where `parent_sid` = $company_id  and `sid` != $logged_in_employer_id  ");
        $data = $data->result_array();
        return $data[0]['sid'];
    }

    function get_employee_events($company_sid, $employee_sid, $events_date = null){
        $this->db->select('*');
        $this->db->select('eventstarttime as event_start_time');
        $this->db->select('date as event_date');
        $this->db->select('eventendtime as event_end_time');
        $this->db->where('companys_sid', $company_sid);
        //$this->db->where('employers_sid', $employee_sid);

        $today = date('Y-m-d');
        if($events_date == 'upcoming'){
            $this->db->where('date >=', $today);
        } else if ( $events_date == 'past') {
            $this->db->where('date <', $today);
        } else if( $events_date !== null) {
            $this->db->where('date', $today);
        }

        $this->db->group_start();
        $this->db->or_where('FIND_IN_SET(' . $employee_sid . ', interviewer)');
        $this->db->or_where('applicant_job_sid', $employee_sid);
        $this->db->or_where('employers_sid', $employee_sid);
        $this->db->group_end();

        $this->db->order_by('date', 'ASC');

        $this->db->where('applicant_job_sid >', 0);

        $records_obj = $this->db->get('portal_schedule_event');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach($records_arr as $key => $record){
            $external_participants = $this->get_event_external_participants($record['sid']);
            $record['external_participants'] = $external_participants;

            $records_arr[$key] = $record;
            reset_event_datetime($records_arr[$key], $this);
        }

        return $records_arr;
    }

    function add_employee($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function get_company_addresses( $company_sid ){
        $this->db->select('address');
        $this->db->group_by('address');
        $this->db->where('companys_sid', $company_sid);

        $records_obj = $this->db->get('portal_schedule_event');
        $records_events_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('Location_Address');
        $this->db->where('sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_users_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('address');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('company_addresses_locations');
        $records_addresses_arr = $records_obj->result_array();
        $records_obj->free_result();

        $addresses = array();

        /*
        foreach($records_events_arr as $key => $address){
            if(!empty($address['address'])){
                if(!in_array($address['address'], $addresses)) {
                    $addresses[] = $address['address'];
                }
            }
        }
        */

        foreach($records_users_arr as $key => $address){
            if(!empty($address['Location_Address'])){
                if(!in_array($address['Location_Address'], $addresses)) {
                    $addresses[] = $address['Location_Address'];
                }
            }
        }

        foreach($records_addresses_arr as $key => $address){
            if(!empty($address['address'])){
                if(!in_array($address['address'], $addresses)) {
                    $addresses[] = $address['address'];
                }
            }
        }


        return $addresses;
    }


    public function get_event_external_participants($event_sid) {
        $this->db->where('event_sid', $event_sid);
        $this->db->from('portal_schedule_event_external_participants');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr)){
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_employee_details($sid) {
        $this->db->select('first_name, last_name, username, access_level, salt, email, parent_sid');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if(!empty($records_arr)) {
            $company_sid = $records_arr[0]['parent_sid'];
            $return_data[] =  $records_arr[0];
            $this->db->select('CompanyName');
            $this->db->where('sid', $company_sid);
            $this->db->from('users');
            $records_obj = $this->db->get();
            $records_arr2 = $records_obj->result_array();
            $return_data[] = $records_arr2[0];
            $records_obj->free_result();
        }

        return $return_data;
    }

    function get_all_departments($company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_deleted', 0);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_department_related_teams($company_sid, $department_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('department_sid', $department_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_department_name($department_sid) {
        $this->db->select('name');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    function get_team_name($team_sid) {
        $this->db->select('name');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    function get_all_department_supervisor($department_sid) {
        $this->db->select('supervisor');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['supervisor'];
        } else {
            return array();
        }
    }

    function get_all_department_teamleads($company_sid, $department_sid) {
        $this->db->select('team_lead');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('department_sid', $department_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_employees_from_department($department_sid) {
        $this->db->select('employee_sid');
        $this->db->where('department_sid', $department_sid);
        $record_obj = $this->db->get('departments_employee_2_team');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_these_employees_detail($employees_list, $order_by = 'sid', $order= 'desc') {
        $this->db->select('*');
        // $this->db->where('active', '0');
         $this->db->where('terminated_status', '0');
        $this->db->where_in('sid', $employees_list);
        $this->db->order_by($order_by, $order);
        $all_employees = $this->db->get('users')->result_array();
        return $all_employees;
    }


    /**
     * Saves data into db
     * Created on: 18-07-2019
     *
     * @param $insert_array Array
     *
     * @return Integer|Bool
     */
    function save_sent_message($insert_array){
        $insert = $this->db->insert('portal_sms', $insert_array);
        return $insert ? $this->db->insert_id() : false;
    }


    /**
     * Get the sms
     * Created on: 18-07-2019
     *
     * @param $user_type    String
     * @param $user_id      Integer
     * @param $company_id   Integer
     * @param $lastId       Integer
     * @param $module       String   Optional
     * @param $limit        Integer  Optional
     *
     * @return Array|Bool
     */
    function fetch_sms($user_type, $user_id, $company_id, $lastId, $module = '', $limit = 100){
        $this
        ->db
        ->select('
            sid,
            message_body,
            sender_user_id,
            sender_user_type,
            IF(is_sent = "1", "sent", "received") as message_type,
            created_at
        ')
        ->from('portal_sms')
        ->group_start()
        ->where('receiver_user_id', $user_id)
        ->or_where('sender_user_id', $user_id)
        ->group_end()
        ->group_start()
        ->where('receiver_user_type', $user_type)
        ->or_where('sender_user_type', $user_type)
        ->group_end()
        ->where('company_id', $company_id)
        ->limit($limit)
        ->order_by('sid', 'DESC');

        if($lastId != 0) $this->db->where('sid <', $lastId);
        if($module != '') $this->db->where('module_slug', $module);
        //
        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        //
        if(!sizeof($result_arr)) return false;

        // $result_arr = array_reverse($result_arr);
        //
        // $lastFetchedId = $result_arr[0]['sid'];
        foreach ($result_arr as $k0 => $v0) {
            // Fetch user name
             $this->db
            ->from('users')
            ->select('CONCAT(first_name," ",last_name) as full_name')
            ->where('sid', $v0['sender_user_id']);
            //
            $result = $this->db->get();
            //
            $name = $result->row_array();
            $result = $result->free_result();
            $result_arr[$k0]['full_name'] = $name['full_name'];
            // Convert datetime to current timezone
            $result_arr[$k0]['created_at'] = reset_datetime(array(
                'datetime' => $v0['created_at'],
                '_this' => $this
            ));
            //
            unset(
                $result_arr[$k0]['sid'],
                $result_arr[$k0]['sender_user_id'],
                $result_arr[$k0]['sender_user_type']
            );
            $lastFetchedId = $v0['sid'];
        }

        //
        $unread = $this
        ->db
        ->from('portal_sms')
        ->group_start()
        ->where('receiver_user_id', $user_id)
        ->or_where('sender_user_id', $user_id)
        ->group_end()
        ->group_start()
        ->where('receiver_user_type', $user_type)
        ->or_where('sender_user_type', $user_type)
        ->group_end()
        ->where('company_id', $company_id)
        ->where('is_read', 0)
        ->count_all_results();

        return array( 'Records' => $result_arr, 'LastId' => $lastFetchedId, 'Unread' => $unread);
    }

     /**
     * Update employee phone number
     * Created on: 22-07-2019
     *
     * @param $phone_number String E164
     * @param $employee_sid Integer
     *
     * @return VOID
     */
    function employee_phone_number($phone_number, $employee_sid){
        $this->db->where('sid', $employee_sid)->update('users', array( 'PhoneNumber' => $phone_number));
    }

    function fetch_terminated_status($sid){

        $this->db->select('terminated_employees.employee_status');
        $this->db->where('users.sid',$sid);
        $this->db->join('users','users.sid = terminated_employees.employee_sid','left');
        $this->db->order_by('terminated_employees.sid','DESC');
        $terminated_status = $this->db->get('terminated_employees')->row_array();
        return $terminated_status;
    }

    function delete_file($id, $type) {
        $this->db->where('sid', $id);
        if ($type == 'file') $this->db->update('portal_applicant_attachments', array('status' => 'deleted'));
        else $this->db->update('users', array($type => NULL));
    }

    function fetch_department_teams($employeeId){
        //
        $a = $this->db
        ->select('department_sid, team_sid')
        ->where('employee_sid', $employeeId)
        ->get('departments_employee_2_team');
        //
        $b = $a->row_array();
        $a->free_result();
        return $b;
    }

    function fetch_employee_assign_teams ($employer_id) {
        $this->db->select('team_sid');
        $this->db->where('employee_sid', $employer_id);
        $records_obj = $this->db->get('departments_employee_2_team');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            //    
            $team_sids = array_column($records_arr, "team_sid");
            $team_names = '';
            //
            foreach ($team_sids as $key => $team_sid) {
             
                if (empty($team_names)) {
                    $team_names = $this->get_team_name($team_sid);
                } else {
                    $team_names = $team_names .', '. $this->get_team_name($team_sid);
                }
            }
            //
            $return_data['team_sids'] = $team_sids;
            $return_data['team_names'] = $team_names;
        }

        return $return_data;
    }

    function getAllAssignedTeams($employeeId){
        $this->db->select('team_sid');
        $this->db->where('employee_sid', $employeeId);
        $records_obj = $this->db->get('departments_employee_2_team');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $team_sids = array_column($records_arr, "team_sid");
            $return_data = $team_sids;
        }

        return $return_data;
    }

    function addEmployeeToTeam ($departmentId, $teamId, $employeeId) {
        $this->db->insert('departments_employee_2_team', array(
                        'department_sid' => $departmentId,
                        'team_sid' => $teamId,
                        'employee_sid' => $employeeId
                    ));
    }


    function removeEmployeeFromTeam($teamId, $employeeId){
        $this->db
            ->where('team_sid', $teamId)
            ->where('employee_sid', $employeeId)
            ->delete('departments_employee_2_team');
    }

    function manageEmployeeTeamHistory ($data_to_insert) {
        $this->db->insert('employee_team_history', $data_to_insert);
    }

    function departmentTeamExists(
        $departmentId,
        $teamId,
        $employeeId
    )
    {
        if($departmentId == null){
            $this->db
            ->where('employee_sid', $employeeId)
            ->delete('departments_employee_2_team');
        } else{
            if (
            !$this->db
                ->where('department_sid', $departmentId)
                ->where('team_sid', $teamId)
                ->where('employee_sid', $employeeId)
                ->count_all_results('departments_employee_2_team')
            ) {
                $this->db
                    ->insert('departments_employee_2_team', array(
                        'department_sid' => $departmentId,
                        'team_sid' => $teamId,
                        'employee_sid' => $employeeId
                    ));
            }
        }
        
    }
    
    function check_for_resume($employee_sid){
        $this->db->select("resume");
        $this->db->where("portal_job_applications_sid", $employee_sid);
        $this->db->where('resume <>', '');
        $this->db->where('resume <>', NULL);
        $this->db->order_by("last_update", 'DESC');
        $result = $this->db->get("portal_applicant_jobs_list")->result_array();

        if(sizeof($result) > 0){
                return $result[0]['resume'];
        }else{  //Check Applications Table for resume
            $this->db->select("resume");
            $this->db->where("sid", $employee_sid);
            $this->db->where('resume <>', '');
            $this->db->where('resume <>', NULL);
            $result = $this->db->get("portal_job_applications")->result_array();
            if(sizeof($result) > 0){
                return $result[0]['resume'];
            }
        }
        return 0;
    }

    //
    function GetEmployeeProfile($employeeId, $companyId){
        //
        $a = [];
        //
        $v =
        // Get basic profile
        $this->db
        ->select('
            sid,
            first_name,
            last_name,
            email,
            PhoneNumber,
            job_title,
            dob,
            registration_date,
            joined_at,
            rehire_date,
            profile_picture,
            employee_type,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin
        ')
        ->where('sid', $employeeId)
        ->where('parent_sid', $companyId)
        ->get('users')
        ->row_array();
        //
        if(empty($v)){
            return [];
        }
        //
        $JoinedDate = get_employee_latest_joined_date($v['registration_date'], $v['joined_at'], $v['rehire_date']);
        //
        $a = [
            'Id' => $v['sid'],
            'Name' => ucwords($v['first_name'].' '.$v['last_name']),
            'BasicRole' => $v['access_level'],
            'Role' => trim(remakeEmployeeName($v, false)),
            'Image' => AWS_S3_BUCKET_URL. (empty($v['profile_picture']) ? 'test.png' : $v['profile_picture']),
            'Email' => strtolower($v['email']),
            'EmploymentType' => strtolower($v['employee_type']),
            'JobTitle' => ucwords(strtolower($v['job_title'])),
            'Phone' => $v['PhoneNumber'],
            'DOB' => empty($v['dob']) || $v['dob'] == '0000-00-00' ? '' : DateTime::createfromformat('Y-m-d', $v['dob'])->format('Y-m-d'),
            'JoinedDate' => $JoinedDate
        ];
        //
        $a = array_merge(
            $a,
            $this->employeeDT($employeeId, $companyId)
        );
        //
        $a = array_merge(
            $a,
            $this->MyManagingDTs($employeeId, $companyId)
        );
        //
        $a = array_merge(
            $a,
            $this->MyCollegues($employeeId, $companyId, $a['Teams'])
        );
        // Get jobs Visibility
        $a = array_merge(
            $a,
            $this->JobsVisibility($employeeId, $companyId)
        );
        // Get department names
        if(!empty($a['Departments'])){
            $a['Departments'] = $this->GetDepartmentNamesByIds($a['Departments']);
        }
        // Get team names
        if(!empty($a['Teams'])){
            $a['Teams'] = $this->GetTeamNamesByIds($a['Teams']);
        }
        // Get supervisors
        if(!empty($a['Supervisors'])){
            $a['Supervisors'] = $this->GetEmployeeNamesByIds($a['Supervisors']);
        }
        // Get team leads
        if(!empty($a['TeamLeads'])){
            $a['TeamLeads'] = $this->GetEmployeeNamesByIds($a['TeamLeads']);
        }
        // Get reporing managers
        if(!empty($a['ReportingManagers'])){
            $a['ReportingManagers'] = $this->GetEmployeeNamesByIds($a['ReportingManagers']);
        }
        //
        return $a;
    }


     /**
     * 
     */
    private function employeeDT($employeeId, $companyId){
        $r = [];
        //
        $a =
        $this->db
        ->select("
            departments_team_management.sid as team_id,
            departments_team_management.team_lead,
            departments_team_management.reporting_managers,
            departments_management.sid as department_id,
            departments_management.reporting_managers as reporting_managers_2,
            departments_management.supervisor
        ")
        ->join("departments_team_management", "departments_team_management.sid = departments_employee_2_team.team_sid")
        ->join("departments_management", "departments_management.sid = departments_team_management.department_sid")
        ->where("departments_management.status", 1)
        ->where("departments_management.company_sid", $companyId)
        ->where("departments_team_management.company_sid", $companyId)
        ->where("departments_management.is_deleted", 0)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->where("departments_employee_2_team.employee_sid", $employeeId)
        ->get("departments_employee_2_team");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        if(!empty($b)){
            //
            $d = $t = $s = $l = $rm = [];
            //
            foreach($b as $v){
                //
                $d[] = $v['department_id'];
                $t[] = $v['team_id'];
                //
                $s = array_merge($s, explode(',', $v['supervisor']));
                $l = array_merge($l, explode(',', $v['team_id']));
                //
                $rm = array_merge($rm, !empty( $v['reporting_managers']) ? explode(',', $v['reporting_managers']) : []);
                $rm = array_merge($rm, !empty( $v['reporting_managers_2']) ? explode(',', $v['reporting_managers_2']) : []);
            }
            //
            $r['TeamLeads'] = $l;
            $r['Supervisors'] = $s;
            $r['Departments'] = $d;
            $r['Teams'] = $t;
            $r['ReportingManagers'] = $rm;
        } else{
            $r['TeamLeads'] = 
            $r['Supervisors'] =
            $r['Departments'] =
            $r['ReportingManagers'] =
            $r['Teams'] = [];
        }
        //
        $r['Approvers'] = $this->getEmployeeApprovers($companyId, $employeeId);
        return $r;
    }
     
    
    /**
     * 
     */
    private function MyManagingDTs($employeeId, $companyId){
        $r = [
            'Departments' => [],
            'Teams' => []
        ];
        //
        $a =
        $this->db
        ->select("name")
        ->where("departments_management.company_sid", $companyId)
        ->where("departments_management.status", 1)
        ->where("departments_management.is_deleted", 0)
        ->where("FIND_IN_SET({$employeeId}, departments_management.supervisor) > 0", NULL)
        ->get("departments_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        $r['Departments'] = array_column($b, 'name');
        //
        $a =
        $this->db
        ->select("
            departments_team_management.name
        ")
        ->join("departments_management", "departments_management.sid = departments_team_management.department_sid")
        ->where("departments_management.company_sid", $companyId)
        ->where("departments_management.status", 1)
        ->where("departments_management.is_deleted", 0)
        ->where("departments_team_management.company_sid", $companyId)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->where("FIND_IN_SET({$employeeId}, departments_team_management.team_lead) > 0", NULL)
        ->get("departments_team_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        $r['Teams'] = array_column($b, 'name');
       
        return ['Managing' => $r];
    }


    //
    function getEmployeeApprovers(
        $companyId,
        $employeeId
    ){
        // Get team leads and supervisors
        $n =
        $this->db
        ->select('
            departments_team_management.sid as team_sid,
            departments_management.sid
        ')
        ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
        ->where('departments_team_management.status', 1)
        ->where('departments_team_management.is_deleted', 0)
        ->where('departments_team_management.company_sid', $companyId)
        ->where('departments_management.status', 1)
        ->where('departments_management.is_deleted', 0)
        ->where('departments_management.company_sid', $companyId)
        ->where('departments_employee_2_team.employee_sid', $employeeId)
        ->get('departments_employee_2_team')
        ->result_array();
        //
        $teamIds = array_column($n, 'team_sid');
        $departmentIds = array_column($n, 'sid');
        //
        $tWhere = '';
        $dWhere = '';
        // Get approvers
        $this->db
        ->select('
            timeoff_approvers.employee_sid as userId,
            timeoff_approvers.approver_percentage,
            timeoff_approvers.department_sid,
            timeoff_approvers.is_department
        ')
        ->where('timeoff_approvers.company_sid', $companyId)
        ->where('timeoff_approvers.status', 1)
        ->where('timeoff_approvers.is_archived', 0);
        //
        if(!empty($teamIds)) {
            foreach($teamIds as $teamId) $tWhere .= "FIND_IN_SET($teamId, timeoff_approvers.department_sid) > 0 OR ";
            $tWhere = rtrim($tWhere, " OR ");
        }
        if(!empty($departmentIds)) {
            foreach($departmentIds as $departmentId) $dWhere .= "FIND_IN_SET($departmentId, timeoff_approvers.department_sid) > 0 OR ";
            $dWhere = rtrim($dWhere, " OR ");
        }
        $this->db->group_start();
        //
        if(!empty($tWhere) && !empty($dWhere)){
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where(rtrim($tWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 0);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where(rtrim($dWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 1);
            $this->db->group_end();
            $this->db->group_end();
        } else if(!empty($tWhere)){
            $this->db->group_start();
            $this->db->where(rtrim($tWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 0);
            $this->db->group_end();
        }  else if(!empty($dWhere)){
            $this->db->group_start();
            $this->db->where(rtrim($dWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 1);
            $this->db->group_end();
        }
        $this->db->or_where('timeoff_approvers.department_sid', 'all');
        $this->db->group_end();
        //
        $approvers = $this->db->get('timeoff_approvers')->result_array();
        //
        $d = [];
        //
        foreach($approvers as $k => $approver){
            //
            if(!isset($d[$approver['userId']])){
                //
                $d[$approver['userId']] = [
                    'user' => $this->GetEmployeeNamesByIds($approver['userId'])[0],
                    'departments' => [],
                    'teams' => []
                ];
            }
            //
            if($approver['is_department'] == 1){
                $d[$approver['userId']]['departments'][$approver['department_sid']] = [
                    'CanApprove' => $approver['approver_percentage'] ? '100%' : '50%',
                    'Id' => $approver['department_sid'],
                    'Names' => $this->GetDepartmentNamesByIds($approver['department_sid'])[0]
                ];
            }
            //
            if($approver['is_department'] == 0){
                $d[$approver['userId']]['teams'][$approver['department_sid']] = [
                    'CanApprove' => $approver['approver_percentage'] ? '100%' : '50%',
                    'Id' => $approver['department_sid'],
                    'Names' => $this->GetTeamNamesByIds($approver['department_sid'])[0]
                ];
            }
        }
        // Double Check department and team
        foreach($d as $index => $approver){
            //
            $total = count($approver['departments']) + count($approver['teams']);
            // 
            if(!empty($approver['departments'])){
                foreach($approver['departments'] as $dt){
                    //
                    if(!$this->IsActiveDepartment($dt['Id'])){
                        $total --;
                        unset($d[$index]['departments'][$dt['id']]);
                    }
                }
            }
            // 
            if(!empty($approver['teams'])){
                foreach($approver['teams'] as $dt){
                    //
                    if(!$this->IsActiveTeam($dt['Id'])){
                        $total --;
                        unset($d[$index]['teams'][$dt['id']]);
                    }
                }
            }
            //
            if($total == 0){
                unset($d[$index]);
            }
        }
        //
        return array_values($d);
    }

    /**
     * 
     */
    function GetDepartmentNamesByIds($ids){
        $a =
        $this->db
        ->select("
            name
        ")
        ->where_in("sid", $ids)
        ->get("departments_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        return array_column($b, 'name');
    }
    
    /**
     * 
     */
    function GetTeamNamesByIds($ids){
        $a =
        $this->db
        ->select("
            name
        ")
        ->where_in("sid", $ids)
        ->get("departments_team_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        return array_column($b, 'name');
    }
   
    /**
     * 
     */
    function GetEmployeeNamesByIds($ids){
        $a =
        $this->db
        ->select("
            first_name,
            last_name,
            access_level,
            access_level_plus,
            is_executive_admin,
            pay_plan_flag,
            job_title
        ")
        ->where_in("sid", $ids)
        ->get("users");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        $t = [];
        //
        foreach($b as $employee){
            $t[] = remakeEmployeeName($employee);
        }
        //
        $b = $t;
        //
        return $b;
    }


    /**
     * 
     */
    function IsActiveDepartment($id){
        return
        $this->db
        ->where("sid", $id)
        ->where("status", 1)
        ->where("is_deleted", 0)
        ->count_all_results("departments_management");
    }
    
    /**
     * 
     */
    function IsActiveTeam($id){
        return
        $this->db
        ->where("departments_team_management.sid", $id)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
        ->count_all_results("departments_team_management");
    }
   
   
    /**
     * 
     */
    function GetAllEmployees($companyId){
        //
        $records =
        // Get basic profile
        $this->db
        ->select('
            sid,
            first_name,
            last_name,
            email,
            PhoneNumber,
            job_title,
            dob,
            IF(joined_at = null, registration_date, joined_at) as joined_at,
            profile_picture,
            employee_type,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin
        ')
        ->where('active', 1)
        ->where('terminated_status', 0)
        ->where('parent_sid', $companyId)
        ->order_by('first_name', 'ASC')
        ->get('users')
        ->result_array();
        //
        if(empty($records)){
            return [];
        }
        //
        $a = [];
        //
        foreach($records as $v){
            //
            $a[] = [
                'Id' => $v['sid'],
                'Name' => ucwords($v['first_name'].' '.$v['last_name']),
                'Role' => trim(remakeEmployeeName($v, false))
            ];
        }

        return $a;
    }


    //
    function MyCollegues($employeeId, $companyId, $teamIds){
        //
        if(empty($teamIds)){
            return [];
        }
        //
        $query =
        $this->db
        ->select("
            users.sid,
            users.first_name,
            users.last_name,
            users.job_title,
            users.access_level,
            users.access_level_plus,
            users.pay_plan_flag,
            users.is_executive_admin
        ")
        ->from('departments_employee_2_team')
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid', 'inner')
        ->where('departments_employee_2_team.employee_sid <> ', $employeeId)
        ->where_in('departments_employee_2_team.team_sid', $teamIds)
        ->get();
        //
        $records = $query->result_array();
        //
        $query->free_result();
        //
        if(empty($records)){
            return [];
        }
        //
        $t = [];
        //
        foreach($records as $v){
            //
            $t[] = ucwords($v['first_name'].' '.$v['last_name']).' '.trim(remakeEmployeeName($v, false));
        }
        //
        unset($records);
        //
        return ['TeamMembers' => $t ];
    }
    
    //
    function JobsVisibility($employeeId, $companyId){
        //
        $query =
        $this->db
        ->select("
            portal_job_listings.Title,
            portal_job_listings.sid,
            portal_job_listings.active
        ")
        ->from('portal_job_listings_visibility')
        ->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'inner')
        ->where('portal_job_listings_visibility.employer_sid', $employeeId)
        ->where('portal_job_listings_visibility.company_sid', $companyId)
        ->order_by('portal_job_listings.active', 'DESC')
        ->get();
        //
        $records = $query->result_array();
        //
        $query->free_result();
        //
        if(empty($records)){
            return [];
        }
        //
        return ['Jobs' => $records ];
    }

    /**
     * Get all the merged employees data
     * 
     * @param number $employeeId
     * @return
     */
    function GetMergedEmployees($employeeId){
        //
        return 
        $this->db->select('
            secondary_employee_profile_data,
            merge_at
        ')
        ->where('primary_employee_sid', $employeeId)
        ->order_by('sid', 'ASC')
        ->get('employee_merge_history')
        ->result_array();
    }

    /**
     * Update rehire date in employee status
     * 
     * @param number $employeeId
     * @return
     */
    function updateEmployeeRehireDate($rehireDate, $employeeId, $changed_by){
        //

        $this->db->select('sid');
        $this->db->where('employee_status', 8);
        $this->db->where('employee_sid', $employeeId);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $row_sid = $record_arr['sid'];
            //
            $data_to_update = array();
            $data_to_update['status_change_date'] = $rehireDate;
            //            
            $this->db->where('sid', $row_sid);
            $this->db->update('terminated_employees', $data_to_update);
        } else {
            $data_to_insert = array();
            $data_to_insert['employee_status'] = 8;
            $data_to_insert['details'] = '';
            $data_to_insert['status_change_date'] = $rehireDate;
            $data_to_insert['termination_date'] = $rehireDate;
            $data_to_insert['employee_sid '] = $employeeId;
            $data_to_insert['changed_by'] = $changed_by;
            $data_to_insert['ip_address'] = getUserIP();
            $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data_to_insert['created_at'] = date('Y-m-d H:i:s', strtotime('now'));

            $this->db->insert('terminated_employees',$data_to_insert);
        }
    }

}
