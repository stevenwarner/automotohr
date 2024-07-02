<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class message_model extends CI_Model
{
    public function get_employer_messages($emp_id, $emp_email = NULL, $between = NULL, $company_id = NULL)
    {
        $this->db->select('from_id as username, to_id, status, subject, date, private_message.id as msg_id,outbox, from_type, contact_name, users_type, job_id');
        //$this->db->join('administrator_users', 'administrator_users.id = private_message.from_id');
        if ($emp_email != NULL) {
            $this->db->where("(to_id = '$emp_id' || to_id = '$emp_email')");
        } else {
            $this->db->where('to_id', $emp_id);
        }

        $this->db->where('to_type', 'employer');
        $this->db->group_start();
        $this->db->where('job_id', '');
        $this->db->or_where('job_id', NULL);
        $this->db->group_end();
        $this->db->where('outbox', 0);

        if ($between != '') {
            $this->db->where($between);
        }

        $this->db->order_by('date', 'desc');
        $records_obj = $this->db->get('private_message');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //echo $this->db->last_query();
        if ($company_id == NULL) {
            return $records_arr;
        } else {
            $retun_array = array();

            if (!empty($records_arr)) {
                foreach ($records_arr as $key => $msg_count) {
                    $to_id = $msg_count['to_id'];
                    $from_id = $msg_count['username'];

                    if (is_numeric($to_id)) {
                        $retun_array[] = $msg_count;
                    } else {
                        $this->db->select('sid');
                        $this->db->where('sid', $from_id);
                        $this->db->where('parent_sid', $company_id);
                        $this->db->from('users');
                        $result = $this->db->count_all_results();

                        if ($result > 0) {
                            $retun_array[] = $msg_count;
                        }
                    }
                }
            }

            return $retun_array;
        }
        //echo $this->db->last_query();
        //        return $result;
    }

    public function get_employer_messages_total($emp_id, $emp_email = NULL, $between = NULL, $company_id = NULL)
    {
        $this->db->select('*');

        if ($emp_email != NULL) {
            $this->db->where("(to_id = '$emp_id' || to_id = '$emp_email')");
        } else {
            $this->db->where('to_id', $emp_id);
        }

        $this->db->where('to_type', 'employer');
        $this->db->where('outbox', 0);
        $this->db->group_start();
        $this->db->where('job_id', "");
        $this->db->or_where('job_id', NULL);
        $this->db->group_end();
        $this->db->where('status', 0);

        if ($between != '') {
            $this->db->where($between);
        }

        $this->db->from('private_message');

        if ($company_id == NULL) {
            return $this->db->count_all_results();
        } else {
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            $count = 0;

            if (!empty($records_arr)) {
                foreach ($records_arr as $key => $msg_count) {
                    $to_id = $msg_count['to_id'];
                    $from_id = $msg_count['from_id'];

                    if (is_numeric($to_id)) {
                        $count++;
                    } else {
                        $this->db->select('sid');
                        $this->db->where('sid', $from_id);
                        $this->db->where('parent_sid', $company_id);
                        $this->db->from('users');
                        $result = $this->db->count_all_results();
                        $count = $count + $result;
                    }
                }
            }

            return $count;
        }
    }

    public function get_messages_total_inbox($emp_id, $emp_email = NULL, $between = NULL, $company_id)
    {
        $this->db->select('*');

        if ($emp_email != NULL) {
            $this->db->where("(to_id = '$emp_id' || to_id = '$emp_email')");
        } else {
            $this->db->where('to_id', $emp_id);
        }

        $this->db->where('to_type', 'employer');
        $this->db->group_start();
        $this->db->where('job_id', "");
        $this->db->or_where('job_id', NULL);
        $this->db->group_end();
        $this->db->where('outbox', 0);

        if ($between != '') {
            $this->db->where($between);
        }

        $this->db->from('private_message');

        if ($company_id == NULL) {
            return $this->db->count_all_results();
        } else {
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            $count = 0;

            if (!empty($records_arr)) {
                foreach ($records_arr as $key => $msg_count) {
                    $to_id = $msg_count['to_id'];
                    $from_id = $msg_count['from_id'];

                    if (is_numeric($to_id)) {
                        $count++;
                    } else {
                        $this->db->select('sid');
                        $this->db->where('sid', $from_id);
                        $this->db->where('parent_sid', $company_id);
                        $this->db->from('users');
                        $result = $this->db->count_all_results();
                        $count = $count + $result;
                    }
                }
            }

            return $count;
        }
    }

    public function get_messages_total_outbox($emp_id, $between = NULL)
    {
        $this->db->select('id');
        //$this->db->join('administrator_users', 'administrator_users.id = private_message.to_id');
        $this->db->where('from_id', $emp_id);
        $this->db->where('outbox', 1);
        $this->db->where('from_type', 'employer');

        if ($between != '') {
            $this->db->where($between);
        }

        return $this->db->get('private_message')->num_rows();
    }

    public function get_inbox_message_detail($message_id)
    {
        $this->db->select('from_type, to_type, from_id as username, attachment, to_id, subject, date, message, private_message.id as msg_id, contact_name, job_id');
        //$this->db->join('administrator_users', 'administrator_users.id = private_message.from_id');
        $this->db->where('private_message.id', $message_id);
        return $this->db->get('private_message')->result_array();
    }

    //ADMIN Outbox messages 
    public function get_outbox_message_detail($message_id)
    {
        $this->db->select('from_type, to_type, from_id as username, attachment, to_id, subject, date, message, private_message.id as msg_id, contact_name, users_type, job_id');
        //$this->db->join('administrator_users', 'administrator_users.id = private_message.to_id');
        $this->db->where('private_message.id', $message_id);
        return $this->db->get('private_message')->result_array();
    }

    public function get_employer_outbox_messages($emp_id, $between = NULL)
    {
        $this->db->select('from_id as username, to_id,subject,date,private_message.id as msg_id,outbox, from_type, contact_name, users_type, job_id');
        //$this->db->join('administrator_users', 'administrator_users.id = private_message.to_id');
        $this->db->where('from_id', $emp_id);
        $this->db->where('outbox', 1);
        $this->db->order_by('date', 'desc');
        $this->db->where('from_type', 'employer');

        if ($between != '') {
            $this->db->where($between);
        }

        $records_obj = $this->db->get('private_message');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    //Delete Message
    public function delete_message($message_id)
    {
        $this->db->where('id', $message_id);
        $this->db->delete('private_message');
    }

    //Compose Message 
    public function save_message($product_data)
    {
        $product_data['outbox'] = 0;
        $this->db->insert('private_message', $product_data);
        $product_data['outbox'] = 1;
        $this->db->insert('private_message', $product_data);
        return "Message Saved Successfully";
    }

    public function get_admins()
    {
        $this->db->where('active', 1);
        return $this->db->get('administrator_users')->result_array();
    }

    public function get_admin_email($id)
    {
        $this->db->where('id', $id);
        $this->db->where('active', 1);
        $result = $this->db->get('administrator_users')->result_array();
        return $result[0]['email'];
    }

    public function mark_read($message_id)
    {
        $data = array('status' => 1);
        $this->db->where('id', $message_id);
        $this->db->update('private_message', $data);
    }

    public function get_message_type($message_id)
    {
        $this->db->where('id', $message_id);
        $result = $this->db->get('private_message')->result_array();
        return $result[0]['from_id'];
    }

    public function get_message_subject($message_id)
    {
        $this->db->select('subject');
        $this->db->where('id', $message_id);
        $result = $this->db->get('private_message')->row_array();
        return $result['subject'];
    }

    public function get_message($message_id)
    {
        $this->db->where('id', $message_id);
        $result = $this->db->get('private_message')->result_array();
        return $result;
    }

    public function get_user($user_id)
    {
        $this->db->where('sid', $user_id);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    public function get_applicant($app_id)
    {
        $this->db->where('sid', $app_id);
        $result = $this->db->get('portal_job_applications')->result_array();
        return $result;
    }

    public function get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type, $companyId = 0)
    {
        //echo $msg_id.'<br>'.$to_id.'<br>'.$from_id.'<br>'.$from_type.'<br>'.$to_type; //exit;
        $to_name = $to_id;
        $to_email = $to_id;
        $from_name = $from_id;
        $from_email = $from_id;
        $to_profile_link = '';
        $from_profile_link = '';
        $message_type = 'applicant';
        $to_message_type = 'applicant';

        if ($to_type == 'admin' || $to_type == 'applicant') {
            if ($to_id == 1 || $to_id == '1') {
                $to_name = 'Admin';
                $to_email = 'support@automotohr.com';
            } else {
                if (!is_numeric($to_id)) {
                    $this->db->select('sid, first_name, last_name, email');
                    $this->db->where('email', $to_id);
                    if ($companyId != 0) {
                        $this->db->where('employer_sid', $companyId);
                    }
                    $records_obj = $this->db->get('portal_job_applications');
                    $records_arr = $records_obj->result_array();
                    $records_obj->free_result();

                    if (!empty($records_arr)) {
                        $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                        $to_email = $records_arr[0]['email'];
                        $portal_job_applications_sid = $records_arr[0]['sid'];
                        $this->db->select('sid');
                        $this->db->order_by('sid', 'desc');
                        $this->db->limit(1);
                        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
                        $obj = $this->db->get('portal_applicant_jobs_list');
                        $result_arr = $obj->result_array();
                        $obj->free_result();


                        if (!empty($result_arr)) {
                            $to_profile_link = base_url('applicant_profile') . '/' . $portal_job_applications_sid . '/' . $result_arr[0]['sid'];
                            $message_type = 'applicant';
                        }
                    }
                }
            }
        } else { // to type is employee
            $this->db->select('first_name, last_name, email');

            if (is_numeric($to_id) && ($to_id > 1 || $to_id != '1')) {
                $this->db->where('sid', $to_id);
            } else {
                $this->db->where('email', $to_id);
                if ($companyId != 0) {
                    $this->db->where('parent_sid', $companyId);
                }
            }
            $this->db->order_by(SORT_COLUMN, SORT_ORDER);
            $records_obj = $this->db->get('users');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                $to_email = $records_arr[0]['email'];
                $message_type = 'employee';
            }
        }

        if ($from_type == 'admin' || $from_type == 'applicant') {
            if ($from_id == 1 || $from_id == '1') {
                $from_name = 'Admin';
                $from_email = 'support@automotohr.com';
            } else {
                if (!is_numeric($from_id)) {
                    $this->db->select('sid, first_name, last_name, email');
                    $this->db->where('email', $from_id);
                    if ($companyId != 0) {
                        $this->db->where('employer_sid', $companyId);
                    }
                    $records_obj = $this->db->get('portal_job_applications');
                    $records_arr = $records_obj->result_array();
                    $records_obj->free_result();

                    if (!empty($records_arr)) {
                        $from_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                        $from_email = $records_arr[0]['email'];
                        $portal_job_applications_sid = $records_arr[0]['sid'];

                        $this->db->select('sid');
                        $this->db->order_by('sid', 'desc');
                        $this->db->limit(1);
                        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
                        $obj = $this->db->get('portal_applicant_jobs_list');
                        $result_arr = $obj->result_array();
                        $obj->free_result();

                        if (!empty($result_arr)) {
                            $from_profile_link = base_url('applicant_profile') . '/' . $portal_job_applications_sid . '/' . $result_arr[0]['sid'];
                            $message_type = 'applicant';
                        }
                    }
                }
            }
        } else { //from type is employee
            if ($from_id == 'notifications@automotohr.com') {
                $from_name = 'Notifications Auto Responder';
                $from_email = 'notifications@automotohr.com';
            } else {
                $this->db->select('sid, first_name, last_name, email');

                if (is_numeric($from_id) && ($from_id > 1 || $from_id != '1')) {
                    $this->db->where('sid', $from_id);
                } else {
                    $this->db->where('email', $from_id);
                    if ($companyId != 0) {
                        $this->db->where('parent_sid', $companyId);
                    }
                }
                $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                $records_obj = $this->db->get('users');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();

                if (!empty($records_arr)) {
                    $from_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                    $from_email = $records_arr[0]['email'];
                    $from_profile_link = base_url('employee_profile') . '/' . $records_arr[0]['sid'];
                    $message_type = 'employee';
                }
            }
        }

        $contact_details = array(
            'to_name' => $to_name,
            'to_email' => $to_email,
            'from_name' => $from_name,
            'from_email' => $from_email,
            'to_profile_link' => $to_profile_link,
            'from_profile_link' => $from_profile_link,
            'message_type' => $message_type
        );

        return $contact_details;
    }

    function get_name_from_email($email, $from_id, $company_sid)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('email', $email);
        $this->db->where('employer_sid', $company_sid);
        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $to_name = $email;
        $from_name = $from_id;

        if (empty($records_arr)) {
            $this->db->select('first_name, last_name');
            $this->db->where('email', $email);
            $this->db->where('parent_sid', $company_sid);
            $this->db->order_by(SORT_COLUMN, SORT_ORDER);
            $records_obj = $this->db->get('users');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
            }
        } else {
            $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
        }

        if ($from_id == 'notifications@automotohr.com') {
            $from_name = 'Notifications Auto Responder';
        } else {
            if (is_numeric($from_id) && ($from_id > 1 || $from_id != '1')) {
                $this->db->select('first_name, last_name');
                $this->db->where('sid', $from_id);
                $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                $records_obj = $this->db->get('users');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();

                if (!empty($records_arr)) {
                    $from_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                }
            }
        }

        $contact_details = array(
            'to_name' => $to_name,
            'from_name' => $from_name
        );

        return $contact_details;
    }

    function get_name_only($email, $company_sid = NULL)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('email', $email);

        if ($company_sid != NULL) {
            $this->db->where('parent_sid', $company_sid);
        }
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $to_name = $email;

        if (empty($records_arr)) {
            $this->db->select('first_name, last_name');

            if ($company_sid != NULL) {
                $this->db->where('employer_sid', $company_sid);
            }

            $this->db->where('email', $email);
            $records_obj = $this->db->get('portal_job_applications');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
            }
        } else {
            $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
        }

        return $to_name;
    }

    function fetch_name($email, $company_sid = NULL)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('email', $email);

        if ($company_sid != NULL) {
            $this->db->where('employer_sid', $company_sid);
        }

        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $to_name = $email;

        if (empty($records_arr)) {
            $this->db->select('first_name, last_name');

            if ($company_sid != NULL) {
                $this->db->where('parent_sid', $company_sid);
            }
            $this->db->order_by(SORT_COLUMN, SORT_ORDER);
            $this->db->where('email', $email);
            $records_obj = $this->db->get('users');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
            }
        } else {
            $to_name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
        }

        return $to_name;
    }

    function get_name_by_id($user_id, $user_type)
    {
        $name = 'Record Not Found';
        $email = 'Email Not Found';

        if ($user_id == 1) {
            $name = 'Admin';
            $email = 'support@automotohr.com';
        } else {
            if ($user_type == 'employee' || $user_type == 'employer') {
                $this->db->select('first_name, last_name, email');
                $this->db->where('sid', $user_id);
                $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                $records_obj = $this->db->get('users');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();
            } else { // applicant
                $this->db->select('first_name, last_name, email');
                $this->db->where('sid', $user_id);
                $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                $records_obj = $this->db->get('portal_job_applications');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();
            }

            if (!empty($records_arr)) {
                $name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
                $email = $records_arr[0]['email'];
            }
        }

        return array('name' => $name, 'email' => $email);
    }

    function get_email_for_record($email, $company_sid)
    {
        $this->db->select('sid, first_name, last_name,job_title, email');
        $this->db->where('email', $email);
        $this->db->where('parent_sid', $company_sid);
        $this->db->limit(1);
        $records_obj = $this->db->get('users');
        $employee_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('sid, first_name, last_name,desired_job_title as job_title, email');
        $this->db->where('email', $email);
        $this->db->where('employer_sid', $company_sid);
        $this->db->limit(1);
        $records_obj = $this->db->get('portal_job_applications');
        $applicant_arr = $records_obj->result_array();
        $records_obj->free_result();

        return array('employee' => $employee_arr, 'applicant' => $applicant_arr);
    }

    function user_data_by_id($sid)
    {
        $this->db->select('username, email, first_name, last_name');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $records_obj = $this->db->get('users');
        $employee_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_array =  array();

        if (!empty($employee_arr)) {
            $return_array = $employee_arr[0];
        }

        return $return_array;
    }

    function get_employer_id($username, $email, $company_id)
    {
        $this->db->select('sid');
        $this->db->where('username', $username);
        $this->db->where('parent_sid', $company_id);
        $records_obj = $this->db->get('users');
        $employee_arr = $records_obj->result_array();
        $records_obj->free_result();
        $employer_id = 0;

        if (!empty($employee_arr)) {
            $employer_id = $employee_arr[0]['sid'];
        } else {
            $this->db->select('sid');
            $this->db->where('email', $email);
            $this->db->where('parent_sid', $company_id);
            $this->db->where('is_executive_admin', 1);
            $records_obj = $this->db->get('users');
            $employee_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($employee_arr)) {
                $employer_id = $employee_arr[0]['sid'];
            }
        }

        return $employer_id;
    }

    function get_users_list($company_sid, $type, $all = '')
    {
        if ($type == 'employee') {
            $this->db->select('access_level, is_executive_admin');
            $where = array(
                'parent_sid' => $company_sid,
                'active' => 1,
            );
            $table = 'users';
        } else if ($type == 'applicant') {
            $where = array(
                'employer_sid' => $company_sid,
                'hired_status' => 0,
            );
            $table = 'portal_job_applications';
        }

        $this->db->select('email, sid, first_name, last_name');
        $this->db->group_by('email');

        if ($type == 'employee') {
            $this->db->where("username != ''");
            if ($all == '') {
                $this->db->where("password != ''");
            }
        }
        return $this->db->get_where($table, $where)->result_array();
    }



    //
    public function get_admin_details($id)
    {
        $this->db->where('id', $id);
        $this->db->where('active', 1);
        $result = $this->db->get('administrator_users')->row_array();
        return $result;
    }
}
