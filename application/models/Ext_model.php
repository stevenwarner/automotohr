<?php
class Ext_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function cc_future_store($carddata, $company_id, $employer_id)
    { // first check if the card is already stored or not.
        $this->db->select('*');
        $this->db->where('company_sid', $company_id);
        $this->db->where('number', $carddata['number']);
        $this->db->where('type', $carddata['type']);

        $record_obj = $this->db->get('emp_cards');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($result)) { // it does not exitst
            $now = date('Y-m-d H:i:s');
            $args = array('company_sid' => $company_id, 'employer_sid' => $employer_id, 'date_added' => $now);
            $final_data = array_merge($carddata, $args);
            $this->db->insert('emp_cards', $final_data);
        } else {
            $sid = $result[0]['sid'];
            $now = date('Y-m-d H:i:s');
            $args = array('date_modified' => $now);
            $final_data = array_merge($carddata, $args);
            $this->db->where('sid', $sid);
            $this->db->update('emp_cards', $final_data);
        }
    }

    function cc_add_order($data)
    { // first check if the card is already stored or not.
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }

    function cc_add_product($order_sid, $data)
    { // first check if the card is already stored or not.
        $order_sid = array('order_sid' => $order_sid);
        $final_data = array_merge($order_sid, $data);
        $this->db->insert('order_product', $final_data);
    }

    function get_product_cost_price($product_sid)
    {
        $this->db->select('cost_price');
        $this->db->where('sid', $product_sid);
        $this->db->from('products');
        $records_obj = $this->db->get();
        $cost_price = $records_obj->result_array();
        $records_obj->free_result();

        if (sizeof($cost_price) > 0 && isset($cost_price[0]['cost_price'])) {
            return $cost_price[0]['cost_price'];
        } else {
            return 0.00;
        }
    }

    function empty_cart($id)
    {
        $this->db->where('company_sid', $id);
        $this->db->delete('shopping_cart');
        $this->session->unset_userdata('coupon_data');
        $userdata = $this->session->userdata('logged_in');
        $sess_array = array(
            'company_detail' => $userdata["company_detail"],
            'employer_detail' => $userdata["employer_detail"],
            'cart' => array(),
            'portal_detail' => $userdata["portal_detail"],
            'clocked_status' => $userdata["clocked_status"]
        );

        if (isset($userdata['is_super']) && intval($userdata['is_super']) == 1) {
            $sess_array['is_super'] = 1;
        } else {
            $sess_array['is_super'] = 0;
        }

        $this->session->set_userdata('logged_in', $sess_array);
    }

    function cc_add_invoice($data)
    {
        $this->db->insert('invoices', $data);
        return $this->db->insert_id();
    }

    function insertJobFeed($jobData)
    {
        $this->db->insert('jobs_to_feed', $jobData);
    }

    function reset_all_cards($company_id)
    {
        $args = array('is_default' => 0);
        $this->db->where('company_sid', $company_id);
        $this->db->update('emp_cards', $args);
    }

    function delete_credit_card($id)
    {
        $this->db->where('sid', $id);
        $this->db->delete('emp_cards');
    }

    function update_card($card_id, $dataToUpdate)
    {
        $this->db->where('sid', $card_id);
        $this->db->update('emp_cards', $dataToUpdate);
    }

    public function get_admin_invoices($sids = array())
    {
        $this->db->select('*');
        $this->db->where_in('sid', $sids);
        $this->db->from('admin_invoices');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function insert_invoice_track_initial_record($data_to_insert)
    {
        $this->db->insert('invoice_items_track', $data_to_insert);
    }

    function get_all_company_cards($company_sid, $active_status = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($active_status !== null) {
            $this->db->where('active', $active_status);
        }

        $this->db->from('emp_cards');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_card_active_status($card_sid, $active_status = 0)
    {
        $this->db->where('sid', $card_sid);
        $this->db->set('active', $active_status);
        $this->db->update('emp_cards');
    }

    function fetch_details($id)
    {
        $this->db->select('myci, mycs');
        $this->db->where('myid', $id);
        $record_obj = $this->db->get('portal_themes_ext');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($result)) {
            $data = $result[0];
            $key = $data['myci'];
            $value = $data['mycs'];
            return array('id' => $key, 'pass' => $value);
        } else {
            return array();
        }
    }




    //
    function get_company_bank_account($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('company_bank_accounts');
        $records = $this->db->get()->row_array();
        return $records;
    }



    //
    public function update_company_bank_account($data)
    {

        if ($data['sid'] == 0) {
            unset($data['sid']);
            $this->db->insert('company_bank_accounts', $data);
            //

            if (isCompanyOnBoard($data['company_sid'])) {

                $this->load->model('v1/payroll_model');

                $accountInfo = [];
                $accountInfo['routing_number'] = $data['routing_number'];
                $accountInfo['account_number'] = $data['account_number'];
                $accountInfo['account_type'] = $data['account_type'];
                $response = $this->payroll_model->addCompanyBankAccount($data['company_sid'], $accountInfo);
            }
        } else {

            $this->db->where('sid', $data['sid']);
            $this->db->where('company_sid', $data['company_sid']);
            $this->db->update('company_bank_accounts', $data);
            //           
            if (isCompanyOnBoard($data['company_sid'])) {
            }
        }
    }

    function get_company_federal_tax_informarion($company_sid)
    {
        $this->db->select('companies_federal_tax.*,users.ssn');
        $this->db->where('company_sid', $company_sid);
        $this->db->join('users', 'users.sid=companies_federal_tax.company_sid');

        $this->db->from('companies_federal_tax');
        $records = $this->db->get()->row_array();
        return $records;
    }


    //
    public function update_company_federal_tax_information($data)
    {

        if ($data['sid'] == 0) {
            unset($data['sid']);
            $this->db->where('sid', $data['company_sid']);
            $this->db->update('users', ['ssn' => $data['ssn']]);
            //
            unset($data['ssn']);
            $this->db->insert('companies_federal_tax', $data);
            //
            if (isCompanyOnBoard($data['company_sid'])) {
            }
        } else {
            //
            $this->db->where('sid', $data['company_sid']);
            $this->db->update('users', ['ssn' => $data['ssn']]);

            $ein = $data['ssn'];

            unset($data['ssn']);
            $this->db->where('sid', $data['sid']);
            $this->db->where('company_sid', $data['company_sid']);
            $this->db->update('companies_federal_tax', $data);
            //
            if (isCompanyOnBoard($data['company_sid'])) {

                $this->load->model('v1/payroll_model');

                $accountInfo = [];
                $accountInfo['legal_name'] = $data['legal_name'];
                $accountInfo['ein'] = $ein;
                $accountInfo['account_type'] = $data['tax_payer_type'];
                $accountInfo['filing_form'] = $data['filing_form'];

                $response = $this->payroll_model->updateCompanyFederalTaxInformarion($data['company_sid'], $accountInfo);
            }
        }
    }
}
