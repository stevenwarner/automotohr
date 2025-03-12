<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('manage_admin/invoice_model');
        $this->load->model('manage_admin/admin_invoices_model');
        $this->load->model('manage_admin/receipts_model');
        $this->load->model('manage_admin/marketing_agencies_model');
        $this->load->model('dashboard_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($startdate = "all", $enddate = "all", $username = "all", $inv_sid = "all", $payment_method = "all", $status = "all", $company = "all", $page_no = 1)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'invoices_panel';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //--------------------Search section Start---------------//
        $search = array();
        $search_data = $this->input->get(NULL, True);
        $this->data["search"] = $search_data;
        $this->data["flag"] = false;
        $username = urldecode($username);
        $payment_method = urldecode($payment_method);
        $status = urldecode($status);
        $startdate = urldecode($startdate);
        $enddate = urldecode($enddate);
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;

        $companies = $this->invoice_model->getAllCompanies(1);
        $this->data['companies'] = $companies;

        if ($page_no > 1) {
            $offset = ($page_no - 1) * $per_page;
        }

        if (isset($startdate) && $startdate != "" && $startdate != "all") {
            $start_date = explode('-', $startdate);
            $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
        } else {
            $start_date = '01-01-1970 00:00:00';
        }

        if (isset($enddate) && $enddate != "" && $enddate != "all") {
            $end_date = explode('-', $enddate);
            $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
        } else {
            $end_date = date('Y-m-d H:i:s');
        }

        $between = "invoices.date between '" . $start_date . "' and '" . $end_date . "'";
        $total_records = $this->invoice_model->get_invoices_date($username, $inv_sid, $status, $payment_method, $between, $company, true, null, null);
        $this->load->library('pagination');
        $pagination_base = base_url('manage_admin/invoice/index') . '/' . urlencode($startdate) . '/' . urlencode($enddate) . '/' . urlencode($username) . '/' . $inv_sid . '/' . urlencode($payment_method) . '/' . urlencode($status). '/' . urlencode($company);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 11;
        $config["num_links"] = 9;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $this->data['page_links'] = $this->pagination->create_links();
        $this->data['current_page'] = $page_no;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;
        $this->data['applicants_count'] = $total_records;

        if (isset($startdate) || isset($enddate)) {
            $db_invoices = $this->invoice_model->get_invoices_date($username, $inv_sid, $status, $payment_method, $between,$company, false, $per_page, $offset);
            $db_email_invoices = $this->invoice_model->get_email_invoices_date($username, $inv_sid, $status, $payment_method, $between);
            $this->data['email_invoices'] = $db_email_invoices;
            $this->data["flag"] = true;
        } else {
            $db_invoices = $this->invoice_model->get_invoices($username, $inv_sid, $status, $payment_method, false, $per_page, $offset);
            $db_email_invoices = $this->invoice_model->get_email_invoices($username, $inv_sid, $status, $payment_method);
            $this->data['email_invoices'] = $db_email_invoices;
        }

        foreach ($db_invoices as $key => $invoice) {
            $credit_notes = $this->invoice_model->get_invoice_credit_notes($invoice['sid'], 'Marketplace', true);
            $invoice['has_refund_notes'] = $credit_notes > 0 ? 1 : 0;
            $db_invoices[$key] = $invoice;
        }

        $this->data['invoiceCount'] = count($db_invoices) + count($db_email_invoices);
        $this->data['invoices'] = $db_invoices;
        $this->data['page_title'] = 'Invoice';
        $this->render('manage_admin/invoice/listings_view', 'admin_master');
    }

    public function index_bak()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'invoices_panel';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $search = array();
        $search_data = $this->input->get(NULL, True);
        $this->data["search"] = $search_data;
        $this->data["flag"] = false;

        foreach ($search_data as $key => $value) {
            if ($key != 'start' && $key != 'end' && $key != 'sid' && $key != 'username') {
                if ($value != '') { // exclude these values from array
                    $search[$key] = $value;
                    $this->data["flag"] = true;
                }
            }
        }

        if (isset($search_data['sid']) && $search_data['sid'] != "") {
            $search['invoices.sid'] = $search_data['sid'];
            $this->data["flag"] = true;
        }

        if (isset($search_data['start']) || isset($search_data['end'])) {
            if (isset($search_data['start']) && $search_data['start'] != "") {
                $start_date = explode('-', $search_data['start']);
                $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
            } else {
                $start_date = '01-01-1970 00:00:00';
            }

            if (isset($search_data['end']) && $search_data['end'] != "") {
                $end_date = explode('-', $search_data['end']);
                $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
            } else {
                $end_date = date('Y-m-d H:i:s');
            }

            $between = "date between '" . $start_date . "' and '" . $end_date . "'";
            $db_invoices = $this->invoice_model->get_invoices_date($search, $between);
            $db_email_invoices = $this->invoice_model->get_email_invoices_date($search, $between);
            $this->data['email_invoices'] = $db_email_invoices;
            $this->data["flag"] = true;
        } else {
            $db_invoices = $this->invoice_model->get_invoices($search);
            $db_email_invoices = $this->invoice_model->get_email_invoices($search);
            $this->data['email_invoices'] = $db_email_invoices;
        }

        foreach ($db_invoices as $key => $invoice) {
            $credit_notes = $this->invoice_model->get_invoice_credit_notes($invoice['sid'], 'Marketplace', true);
            $invoice['has_refund_notes'] = $credit_notes > 0 ? 1 : 0;
            $db_invoices[$key] = $invoice;
        }


        $this->data['invoiceCount'] = count($db_invoices) + count($db_email_invoices);
        $this->data['invoices'] = $db_invoices;
        $this->data['page_title'] = 'Invoice';
        $this->render('manage_admin/invoice/listings_view', 'admin_master');
    }

    private function add_new_invoice()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Add New Invoice';
        $this->form_validation->set_rules('user_sid', 'Employer', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->load->model('manage_admin/products_model');
            $this->data["products"] = $this->products_model->get_products(); //getting products list           
            $this->load->model('manage_admin/company_model');
            $this->data["employers"] = $this->company_model->get_employers(); //getting employer list
            $this->render('manage_admin/invoice/add_new_invoice');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $custom_data = array();

            foreach ($formpost as $key => $value) {
                if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'custom_text' && $key != 'item_qty' && $key != 'item_remaining_qty' && $key != 'no_of_days' && $key != 'flag' && $key != 'item_price' && $key != 'to_name') { // exclude these values from array
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }

                    $invoice_data[$key] = $value;
                } else {
                    if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'to_name')
                        $custom_data[$key] = $value;
                }
            }

            $custom_data['products'] = $formpost['product_sid'];
            $custom_data['item_remaining_qty'] = $formpost['item_remaining_qty'];
            $custom_data['flag'] = $formpost['flag'];
            $invoice_data["serialized_items_info"] = serialize($custom_data);

            if ($formpost['send_invoice'] == 'to_employer') {
                $invoice_data['user_sid'] = $formpost['user_sid'];
                $emp_data = $this->invoice_model->get_employer_email($formpost['user_sid']);
                $to = $emp_data["email"];
                $emp_detail = $this->dashboard_model->getEmployerDetail($formpost['user_sid']);
                $invoice_data['company_sid'] = $emp_detail['parent_sid'];

                if (empty($emp_data["first_name"]) && $emp_data["first_name"] == NULL) {
                    $receiverName = $emp_data["username"];
                } else {
                    $receiverName = $emp_data["first_name"] . " " . $emp_data["last_name"];
                }
            } else {
                $invoice_data['to_email'] = $formpost['email'];
                $invoice_data['to_name'] = $formpost['to_name'];
                $to = $formpost["email"];
                $receiverName = $formpost["to_name"];
            }

            if ($formpost['date'] != "" && !empty($formpost['date'])) {
                $timestamp = explode('-', $formpost['date']);
                $month = $timestamp[0];
                $day = $timestamp[1];
                $year = $timestamp[2];
                $date = $year . '-' . $month . '-' . $day;
                $invoice_data['date'] = $date;
            }

            $invoiceNo = $this->invoice_model->save_invoice($invoice_data);
            $invoiceData = $this->invoice_model->get_invoice_detail($invoiceNo);
            $products = "";
            $this->load->model('manage_admin/products_model');

            for ($i = 0; $i < count($custom_data['products']); $i++) {
                $custom_product_id = $custom_data['products'][$i];
                if (strpos($custom_product_id, 'custom') === false) {
                    $products_name = db_get_product_name($custom_product_id);
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                } else {
                    $products_name = $custom_data["custom_text"][$i];
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                }
            }

            $emailTemplateData = get_email_template(INVOICE_NOTIFICATION); //Fetching email template
            $emailTemplateBody = $emailTemplateData['text'];

            if (!empty($emailTemplateBody)) {
                $emailTemplateBody = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                $emailTemplateBody = str_replace('{{firstname}}', $receiverName, $emailTemplateBody);
                $emailTemplateBody = str_replace('{{invoice_id}}', $invoiceNo, $emailTemplateBody);
                $emailTemplateBody = str_replace('{{product_list}}', $products, $emailTemplateBody);
                $emailTemplateBody = str_replace('{{invoice_subtotal}}', '$' . $invoiceData["sub_total"], $emailTemplateBody);
                $emailTemplateBody = str_replace('{{discount}}', '$' . $invoiceData["total_discount"], $emailTemplateBody);
                $emailTemplateBody = str_replace('{{invoice_total}}', '$' . $invoiceData["total"], $emailTemplateBody);

                if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . $custom_data["special_discount"], $emailTemplateBody);
                } else {
                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . '0.00', $emailTemplateBody);
                }

                if (!empty($invoiceData["invoice_description"])) {
                    $emailTemplateBody = str_replace('{{invoice_description}}', $invoiceData["invoice_description"], $emailTemplateBody);
                } else {
                    $emailTemplateBody = str_replace('{{invoice_description}}', 'No Description', $emailTemplateBody);
                }
            }

            $from = $emailTemplateData['from_email'];
            $subject = $emailTemplateData['subject'];
            $from_name = $emailTemplateData['from_name'];
            $body = EMAIL_HEADER
                . $emailTemplateBody
                . EMAIL_FOOTER;

            $emailLog['subject'] = $subject;
            $emailLog['email'] = $to;
            $emailLog['message'] = $body;
            $emailLog['date'] = date('Y-m-d H:i:s');
            $emailLog['admin'] = 'admin';
            $emailLog['status'] = 'Delivered';
            save_email_log_common($emailLog);
            redirect('manage_admin/invoice', 'refresh');
        }
    }

    public function edit_invoice_back($invoice_id = NULL)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'edit_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($invoice_id == NULL) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        }

        $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
        $this->data['invoice_id'] = $invoice_id;

        if (empty($invoiceData)) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        } else {
            $this->data['page_title'] = 'Edit Invoice';
            $this->form_validation->set_rules('user_sid', 'Employer', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->load->model('manage_admin/products_model');
                $this->data["products"] = $this->products_model->get_products();
                $this->data["employers"] = $this->invoice_model->get_company_employerd($invoiceData['company_sid']);
                $this->data["invoiceData"] = $invoiceData;

                if ($this->data["invoiceData"]["date"] != NULL) {
                    $date = $this->data["invoiceData"]["date"];
                    $dateArray = explode("-", $date);
                    $mydate = explode(" ", $dateArray[2]);
                    $this->data["invoiceData"]["date"] = $dateArray[1] . "-" . $mydate[0] . "-" . $dateArray[0];
                }

                $market_notes = $this->admin_invoices_model->get_market_invoice_notes($invoice_id);
                $this->data['notes'] = $market_notes;
                $this->data["invoiceData"]["product_sid"] = explode(',', $this->data["invoiceData"]["product_sid"]);
                $this->data["invoiceData"]["serialized_products"] = unserialize($this->data["invoiceData"]["serialized_items_info"]);
                $this->data["invoiceData"]["serialized_items_info"] = json_encode(unserialize($this->data["invoiceData"]["serialized_items_info"]));
                $this->render('manage_admin/invoice/edit_invoice');
            } else {
                if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'Add_refund_note') {
                    $invoice_sid = $invoice_id;
                    $refund_notes = $this->input->post('refund_notes');
                    $refund_date = $this->input->post('rfd_date');
                    $cr_amnt = $this->input->post('cr_amnt');
                    $insert_data = array();
                    $insert_data['invoice_sid'] = $invoice_sid;
                    $insert_data['created_by'] = $admin_id;
                    $insert_data['invoice_type'] = 'Marketplace';
                    $insert_data['credit_amount'] = $cr_amnt;
                    $insert_data['notes'] = $refund_notes;
                    $insert_data['created_date'] = date('Y-m-d H:i:s');
                    $insert_data['refund_date'] = DateTime::createFromFormat('m-d-Y', $refund_date)->format('Y-m-d 23:59:59');
                    $this->admin_invoices_model->insert_invoice_credit_notes($insert_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Note Updated!');
                    redirect('manage_admin/invoice/edit_invoice/' . $invoice_sid, 'refresh');
                }

                $formpost = $this->input->post(NULL, TRUE);
                $custom_data = array();

                foreach ($formpost as $key => $value) {
                    if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'custom_text' && $key != 'item_qty' && $key != 'item_price' && $key != 'item_remaining_qty' && $key != 'no_of_days' && $key != 'flag' && $key != 'action' && $key != 'to_name') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $invoice_data[$key] = $value;
                    } else {
                        if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'action' && $key != 'to_name')
                            $custom_data[$key] = $value;
                    }
                }

                $custom_data['products'] = $formpost['product_sid'];
                $custom_data['item_remaining_qty'] = $formpost['item_remaining_qty'];
                $custom_data['flag'] = $formpost['flag'];
                $invoice_data["serialized_items_info"] = serialize($custom_data);

                if ($formpost['action'] == 'Send') {
                    if ($formpost['send_invoice'] == 'to_employer') {
                        $invoice_data['user_sid'] = $formpost['user_sid'];
                        $invoice_data['to_email'] = NULL;
                        $invoice_data['to_name'] = NULL;
                        $emp_detail = $this->dashboard_model->getEmployerDetail($formpost['user_sid']);
                        $invoice_data['company_sid'] = $emp_detail['parent_sid'];
                        $emp_data = $this->invoice_model->get_employer_email($formpost['user_sid']);

                        if (empty($emp_data["first_name"]) && $emp_data["first_name"] == NULL) {
                            $receiverName = $emp_data["username"];
                        } else {
                            $receiverName = $emp_data["first_name"] . " " . $emp_data["last_name"];
                        }

                        $to = $emp_data["email"];
                    } else {
                        $invoice_data['user_sid'] = NULL;
                        $invoice_data['to_email'] = $formpost['email'];
                        $invoice_data['to_name'] = $formpost['to_name'];
                        $to = $formpost["email"];
                        $receiverName = $formpost['to_name'];
                    }

                    $products = "";
                    $this->load->model('manage_admin/products_model');

                    for ($i = 0; $i < count($custom_data['products']); $i++) {
                        $custom_product_id = $custom_data['products'][$i];

                        if (strpos($custom_product_id, 'custom') === false) {
                            $products_name = db_get_product_name($custom_product_id);
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        } else {
                            $products_name = $custom_data["custom_text"][$i];
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        }
                    }

                    $replacement_array = array();
                    $replacement_array['site_url'] = base_url();
                    $replacement_array['date'] = month_date_year($invoiceData['date']);
                    $replacement_array['firstname'] = $receiverName;
                    $replacement_array['invoice_id'] = $invoice_id;
                    $replacement_array['product_list'] = $products;
                    $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                    $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                    $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                    if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                        $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                    } else {
                        $replacement_array['special_discount'] = '$' . '0.00';
                    }

                    if (!empty($invoiceData["invoice_description"])) {
                        $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                    } else {
                        $replacement_array['invoice_description'] = 'No Description';
                    }

                    log_and_send_templated_email(INVOICE_NOTIFICATION, $to, $replacement_array);
                    $this->session->set_flashdata('message', 'Invoice sent Successfully!');
                }

                if ($formpost['action'] == 'Save') { //save invoice without send email
                    if ($formpost['date'] != "" && !empty($formpost['date'])) { //Converting date time
                        $timestamp = explode('-', $formpost['date']);
                        $month = $timestamp[0];
                        $day = $timestamp[1];
                        $year = $timestamp[2];
                        $date = $year . '-' . $month . '-' . $day;
                        $invoice_data['date'] = $date . ' ' . date('H:i:s');
                    }

                    $this->invoice_model->update_invoice($invoice_id, $invoice_data);
                    $this->session->set_flashdata('message', 'Invoice saved Successfully!');
                }
                redirect('manage_admin/invoice/edit_invoice/' . $invoice_id, 'refresh');
            }
        }
    }

    public function edit_invoice($invoice_id = NULL)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'edit_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($invoice_id == NULL) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        }

        $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
        $this->data['invoice_id'] = $invoice_id;

        if (empty($invoiceData)) {
            $this->session->set_flashdata('message', 'Invoice not found!');
            redirect('manage_admin/invoice', 'refresh');
        } else {
            $this->data['page_title'] = 'Edit Invoice';
            $this->form_validation->set_rules('user_sid', 'Employer', 'trim');

            if ($this->form_validation->run() === FALSE) {
                //getting products list
                $this->load->model('manage_admin/products_model');
                $this->data["products"] = $this->products_model->get_products();
                //getting employer list
                $this->data["employers"] = $this->invoice_model->get_company_employerd($invoiceData['company_sid']);
                $this->data["invoiceData"] = $invoiceData;

                if ($this->data["invoiceData"]["date"] != NULL) {
                    $date = $this->data["invoiceData"]["date"];
                    $dateArray = explode("-", $date);
                    $mydate = explode(" ", $dateArray[2]);
                    $this->data["invoiceData"]["date"] = $dateArray[1] . "-" . $mydate[0] . "-" . $dateArray[0];
                }

                $market_notes = $this->admin_invoices_model->get_market_invoice_notes($invoice_id);
                $this->data['notes'] = $market_notes;
                $this->data["invoiceData"]["product_sid"] = explode(',', $this->data["invoiceData"]["product_sid"]);
                $this->data["invoiceData"]["serialized_products"] = unserialize($this->data["invoiceData"]["serialized_items_info"]);
                $this->data["invoiceData"]["serialized_items_info"] = json_encode(unserialize($this->data["invoiceData"]["serialized_items_info"]));
                $this->render('manage_admin/invoice/edit_invoice');
            } else {
                if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'Add_refund_note') {
                    $invoice_sid = $invoice_id;
                    $refund_notes = $this->input->post('refund_notes');
                    $refund_date = $this->input->post('rfd_date');
                    $cr_amnt = $this->input->post('cr_amnt');
                    $insert_data = array();
                    $insert_data['invoice_sid'] = $invoice_sid;
                    $insert_data['created_by'] = $admin_id;
                    $insert_data['invoice_type'] = 'Marketplace';
                    $insert_data['credit_amount'] = $cr_amnt;
                    $insert_data['notes'] = $refund_notes;
                    $insert_data['created_date'] = date('Y-m-d H:i:s');
                    $insert_data['refund_date'] = DateTime::createFromFormat('m-d-Y', $refund_date)->format('Y-m-d 23:59:59');
                    $this->admin_invoices_model->insert_invoice_credit_notes($insert_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Note Updated!');
                    redirect('manage_admin/invoice/edit_invoice/' . $invoice_sid, 'refresh');
                }

                $formpost = $this->input->post(NULL, TRUE);
                $custom_data = array();

                foreach ($formpost as $key => $value) {
                    if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'custom_text' && $key != 'item_qty' && $key != 'item_price' && $key != 'item_remaining_qty' && $key != 'no_of_days' && $key != 'flag' && $key != 'action' && $key != 'to_name') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $invoice_data[$key] = $value;
                    } else {
                        if ($key != 'date' && $key != 'send_invoice' && $key != 'user_sid' && $key != 'email' && $key != 'action' && $key != 'to_name')
                            $custom_data[$key] = $value;
                    }
                }

                $custom_data['products'] = $formpost['product_sid'];
                $custom_data['item_remaining_qty'] = $formpost['item_remaining_qty'];
                $custom_data['flag'] = $formpost['flag'];
                $invoice_data["serialized_items_info"] = serialize($custom_data);

                if ($formpost['action'] == 'Send') {
                    if ($formpost['send_invoice'] == 'to_employer') {
                        $invoice_data['user_sid'] = $formpost['user_sid'];
                        $invoice_data['to_email'] = NULL;
                        $invoice_data['to_name'] = NULL;
                        $emp_detail = $this->dashboard_model->getEmployerDetail($formpost['user_sid']);
                        $invoice_data['company_sid'] = $emp_detail['parent_sid'];
                        $emp_data = $this->invoice_model->get_employer_email($formpost['user_sid']);

                        if (empty($emp_data["first_name"]) && $emp_data["first_name"] == NULL) {
                            $receiverName = $emp_data["username"];
                        } else {
                            $receiverName = $emp_data["first_name"] . " " . $emp_data["last_name"];
                        }

                        $to = $emp_data["email"];
                    } else {
                        $invoice_data['user_sid'] = NULL;
                        $invoice_data['to_email'] = $formpost['email'];
                        $invoice_data['to_name'] = $formpost['to_name'];
                        $to = $formpost["email"];
                        $receiverName = $formpost['to_name'];
                    }

                    $products = "";
                    $this->load->model('manage_admin/products_model');

                    for ($i = 0; $i < count($custom_data['products']); $i++) {
                        $custom_product_id = $custom_data['products'][$i];

                        if (strpos($custom_product_id, 'custom') === false) {
                            $products_name = db_get_product_name($custom_product_id);
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        } else {
                            $products_name = $custom_data["custom_text"][$i];
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        }
                    }

                    $company_admin_user = db_get_first_admin_user($invoiceData['company_sid']);
                    $company_info = $this->invoice_model->Get_company_information($invoiceData['company_sid']);
                    $replacement_array = array(); //Send Payment Notification to admin.
                    $replacement_array['invoice_number'] = $invoiceData['sid'];
                    $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                    $replacement_array['payment_method'] = $invoiceData['payment_method'];
                    $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                    $invoiceData["product_sid"] = explode(',', $invoiceData["product_sid"]);
                    $invoiceData["serialized_products"] = unserialize($invoiceData["serialized_items_info"]);
                    $invoiceData["serialized_items_info"] = json_encode(unserialize($invoiceData["serialized_items_info"]));
                    $replacement_array['invoice'] = generate_marketplace_invoice_html($invoiceData, $company_info);
                    log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $to, $replacement_array);
                    $this->session->set_flashdata('message', 'Invoice sent Successfully!');
                }

                if ($formpost['action'] == 'Save') { //save invoice without send email
                    if ($formpost['date'] != "" && !empty($formpost['date'])) { //Converting date time
                        $timestamp = explode('-', $formpost['date']);
                        $month = $timestamp[0];
                        $day = $timestamp[1];
                        $year = $timestamp[2];
                        $date = $year . '-' . $month . '-' . $day;
                        $invoice_data['date'] = $date . ' ' . date('H:i:s');
                    }

                    $this->invoice_model->update_invoice($invoice_id, $invoice_data);
                    $this->session->set_flashdata('message', 'Invoice saved Successfully!');
                }
                redirect('manage_admin/invoice/edit_invoice/' . $invoice_id, 'refresh');
            }
        }
    }

    function invoice_task()
    {
        $action = $this->input->post("action");
        $invoice_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->invoice_model->delete_product($invoice_id);
        } else if ($action == 'mark paid') {
            $this->invoice_model->mark_paid($invoice_id);
            $this->session->set_flashdata('message', 'Invoice Marked Paid Successfully!');
        } else if ($action == 'mark unpaid') {
            $this->invoice_model->mark_unpaid($invoice_id);
            $this->session->set_flashdata('message', 'Invoice Marked Unpaid Successfully!');
        } else if ($action == 'resend') {
            $products = "";
            $this->load->model('manage_admin/products_model');
            $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
            $custom_data = unserialize($invoiceData["serialized_items_info"]);
            $productsArray = explode(',', $invoiceData["product_sid"]);

            for ($i = 0; $i < count($custom_data['products']); $i++) {
                $custom_product_id = $custom_data['products'][$i];

                if (strpos($custom_product_id, 'custom') === false) {
                    $products_name = db_get_product_name($custom_product_id);
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                } else {
                    $products_name = $custom_data["custom_text"][$i];
                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                }
            }

            if ($invoiceData["user_sid"] == NULL) {
                $to = $invoiceData["to_email"];
                $receiverName = $invoiceData["to_name"];
            } else {
                $emp_data = $this->invoice_model->get_employer_email($invoiceData['user_sid']);

                if (empty($emp_data["first_name"]) && $emp_data["first_name"] == NULL) {
                    $receiverName = $emp_data["username"];
                } else {
                    $receiverName = $emp_data["first_name"] . " " . $emp_data["last_name"];
                }

                $to = $emp_data["email"];
            }

            //Send Invoice through Notifications Module - Start
            $replacement_array = array();
            $replacement_array['site_url'] = base_url();
            $replacement_array['date'] = month_date_year(date('Y-m-d'));
            $replacement_array['firstname'] = $receiverName;
            $replacement_array['invoice_id'] = $invoice_id;
            $replacement_array['product_list'] = $products;
            $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
            $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
            $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

            if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
            } else {
                $replacement_array['special_discount'] = '$' . '0.00';
            }

            if (!empty($invoiceData["invoice_description"])) {
                $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
            } else {
                $replacement_array['invoice_description'] = 'No Description';
            }

            $company_sid = $invoiceData['company_sid'];
            send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
            $this->session->set_flashdata('message', 'Invoice resent Successfully!');
            exit;
        }
    }

    public function list_admin_invoices($year = null, $month = null, $company_sid = 'all', $payment_status = 'all', $payment_method = 'all')
    {
        $redirect_url = 'manage_admin';
        $function_name = 'list_admin_invoices';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('company_sid', 'company sid', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $year = $year !== null && is_numeric($year) ? $year : date('Y');
            $month = $month !== null && is_numeric($month) ? $month : date('m');
            $my_date = new DateTime();
            $my_date->setDate($year, $month, 1);
            $from_date = $my_date->format('Y-m-1 00:00:00');
            $to_date = $my_date->format('Y-m-t 23:59:59');
            $company_sid = $company_sid == 'all' ? null : $company_sid; //Check Company
            $payment_status = $payment_status == 'all' ? null : $payment_status; //Check Payment Status
            $payment_method = $payment_method == 'all' ? null : $payment_method; //Check Payment Method
            $invoices = $this->admin_invoices_model->Get_all_admin_invoices(1, 10000, $company_sid, 'active', $from_date, $to_date, $payment_status, $payment_method);
            $months = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $this->data['months'] = $months;
            $payment_statuses = array();
            $payment_statuses['paid'] = 'Paid';
            $payment_statuses['unpaid'] = 'Unpaid';
            $this->data['payment_statuses'] = $payment_statuses;
            $payment_methods = array();
            $payment_methods['cash'] = 'Cash';
            $payment_methods['credit-card'] = 'Paypal';
            $this->data['payment_methods'] = $payment_methods;
            $companies = $this->admin_invoices_model->get_all_companies();
            $this->data['companies'] = $companies;
            $this->data['invoices'] = $invoices;
            $this->render('manage_admin/invoice/list_admin_invoices', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            $invoice_sid = $this->input->post('invoice_sid');

            switch ($perform_action) {
                case 'delete_admin_invoice':
                    $this->admin_invoices_model->Update_admin_invoice_status($invoice_sid, 'deleted');
                    break;
            }
            $this->session->set_flashdata('message', '<strong>Success: </strong> Invoice Deleted!');
            redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
        }
    }

    public function apply_discount_admin_invoice($invoice_sid = 0)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'apply_discount_admin_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($invoice_sid > 0) {
            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);

            if (!empty($invoice_data)) {
                $company_sid = $invoice_data['company_sid'];
                $this->form_validation->set_rules('discount_percentage', 'Discount Percentage', 'numeric|required');
                $this->form_validation->set_rules('discount_amount', 'Discount Amount', 'numeric|required');
                $this->form_validation->set_rules('total_after_discount', 'Total Amount', 'numeric|required');

                if ($this->form_validation->run() === FALSE) {
                    $this->data['invoice'] = $invoice_data;
                    $this->render('manage_admin/invoice/apply_discount_admin_invoices', 'admin_master');
                } else {
                    $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);
                    $company_sid = $invoice_data['company_sid'];
                    $discountPercentage = $this->input->post('discount_percentage');
                    $discountAmount = $this->input->post('discount_amount');
                    $totalAfterDiscount = $this->input->post('total_after_discount');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $commission_invoice_sid = $this->input->post('commission_invoice_sid');
                    $this->admin_invoices_model->Update_admin_invoice_discount($invoice_sid, $discountPercentage, $discountAmount, $totalAfterDiscount);

                    if (intval($commission_invoice_sid) > 0) {
                        $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid, $discountPercentage, $discountAmount, $totalAfterDiscount);
                        $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid);
                        $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid);
                    }

                    redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
                redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
            redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
        }
    }

    public function view_admin_invoice($invoice_sid = 0)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'view_admin_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($invoice_sid > 0) {
            $company_data = array();
            $this->form_validation->set_rules('invoice_sid', 'invoice sid', 'numeric|required');
            $notes = $this->admin_invoices_model->get_invoice_notes($invoice_sid);
            $this->data['notes'] = $notes;

            if ($_POST) {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'update_invoice_status':
                        break;
                    case 'send_invoice_to_client_by_email':
                        $this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email');
                        break;
                }
            }

            if ($this->form_validation->run() == false) {
            } else {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'update_invoice_status':
                        $invoice_status = $this->input->post('invoice_status');
                        $invoice_sid = $this->input->post('invoice_sid');
                        $this->admin_invoices_model->Update_admin_invoice_status($invoice_sid, $invoice_status);
                        break;
                    case 'change_invoice_date':
                        $invoice_date = $this->input->post('invoice_date');
                        $invoice_sid = $this->input->post('invoice_sid');
                        $previous = $this->input->post('previous_date');
                        $pre_time = explode(' ', $previous);
                        $data['created'] = DateTime::createFromFormat('m-d-Y', $invoice_date)->format('Y-m-d') . ' ' . $pre_time[1];
                        $this->admin_invoices_model->update_admin_invoice($invoice_sid, $data);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Date Updated Successfully!');
                        break;
                    case 'send_invoice_to_client_by_email':
                        //Not going through Notifications Module
                        $invoice_sid = $_POST['invoice_sid'];
                        $email_address = $_POST['email_address'];
                        $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, false);
                        $invoice = generate_invoice_html($invoice_sid);
                        $to = $email_address;
                        $from = FROM_EMAIL_ACCOUNTS;
                        $subject = STORE_NAME . ' Invoice # ' . $invoice_data['invoice_number'];
                        //$body = $invoice;
                        $body = EMAIL_HEADER
                            . $invoice
                            . EMAIL_FOOTER;

                        log_and_sendEmail($from, $to, $subject, $body, STORE_NAME);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Sent Via Email!');
                        redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
                        break;
                    case 'update_invoice_description':
                        $invoice_sid = $this->input->post('invoice_sid');
                        $invoice_description = $this->input->post('invoice_description');
                        $this->admin_invoices_model->Update_invoice_description($invoice_sid, $invoice_description);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Note Updated!');
                        redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
                        break;
                    case 'update_company_notes':
                        $invoice_sid = $this->input->post('invoice_sid');
                        $company_notes = $this->input->post('company_notes');
                        $this->admin_invoices_model->update_admin_invoice($invoice_sid, array('company_notes' => $company_notes));
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Note for Company is Updated!');
                        redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
                        break;

                    case 'Add_refund_note':
                        $invoice_sid = $this->input->post('invoice_sid');
                        $refund_notes = $this->input->post('refund_notes');
                        $refund_date = $this->input->post('rfd_date');
                        $cr_amnt = $this->input->post('cr_amnt');
                        $insert_data = array();
                        $insert_data['invoice_sid'] = $invoice_sid;
                        $insert_data['created_by'] = $admin_id;
                        $insert_data['invoice_type'] = 'Admin';
                        $insert_data['credit_amount'] = $cr_amnt;
                        $insert_data['notes'] = $refund_notes;
                        $insert_data['created_date'] = date('Y-m-d H:i:s');
                        $insert_data['refund_date'] = DateTime::createFromFormat('m-d-Y', $refund_date)->format('Y-m-d 23:59:59');
                        $this->admin_invoices_model->insert_invoice_credit_notes($insert_data);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Note Updated!');
                        redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
                        break;
                }
            }

            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, true);

            if (!empty($invoice_data)) {
                $created_by = get_administrator_user_info($invoice_data['created_by']);
                $payment_processed_by = get_administrator_user_info($invoice_data['payment_processed_by']);
                $admin_name = ucwords($created_by['first_name'] . ' ' . $created_by['last_name']);

                if (!empty($payment_processed_by)) {
                    $processed_by = ucwords($payment_processed_by['first_name'] . ' ' . $payment_processed_by['last_name']);
                } else {
                    $processed_by = '';
                }

                $invoice_data['created_by_name'] = $admin_name;
                $invoice_data['payment_processed_by'] = $processed_by;
                $company_data = $this->admin_invoices_model->Get_company_information($invoice_data['company_sid']);

                if (!empty($company_data)) {
                    $company_data = $company_data[0];
                    $company_admin = $this->admin_invoices_model->Get_company_admin_information($invoice_data['company_sid']);

                    if (!empty($company_admin)) {
                        $company_data['email'] = $company_admin[0]['email'];
                    }

                    $country_name = db_get_country_name($company_data['Location_Country']);
                    $country_name = $country_name['country_name'];
                    $state_name = db_get_state_name_only($company_data['Location_State']);
                    $company_data['state_name'] = $state_name;
                    $company_data['country_name'] = $country_name;
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
                redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
            }

            $this->data['company_info'] = $company_data;
            $this->data['invoice'] = $invoice_data;
            $this->render('manage_admin/invoice/view_admin_invoice', 'admin_master');
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
            redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
        }
    }

    public function print_admin_invoice($invoice_sid = 0)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'view_admin_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($invoice_sid > 0) {
            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, false);
            $invoice_html = generate_invoice_html($invoice_sid);
            $this->data['title'] = 'Invoice # ' . $invoice_data['invoice_number'];
            $this->data['invoice'] = $invoice_html;
            $this->render('manage_admin/invoice/print_admin_invoice', 'admin_master_print');
        }
    }

    public function pending_invoices()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'unpaid_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $companies = $this->invoice_model->get_companies_with_unpaid_admin_invoices();
        $this->data['companies'] = $companies;
        $this->render('manage_admin/invoice/pending_invoices', 'admin_master');
    }

    public function pending_commissions()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'pending_commissions';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $unpaid_commissions = $this->invoice_model->get_all_unpaid_commissions();

        foreach ($unpaid_commissions as $key => $invoice_record) {
            $agency_check = $this->invoice_model->check_marketing_agency($invoice_record['marketing_agency_sid']);
            if ($agency_check == false) {
                unset($unpaid_commissions[$key]);
            }
        }

        $this->data['unpaid_commissions'] = $unpaid_commissions;
        $this->render('manage_admin/invoice/pending_commissions', 'admin_master');
    }

    public function view_pending_invoices($company_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'pending_invoices';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $invoices = $this->invoice_model->get_unpaid_invoices($company_sid);
            $this->data['invoices'] = $invoices;
            $this->data['company_sid'] = $company_sid;
            $grand_total = 0;

            foreach ($invoices as $invoice) {
                if ($invoice['exclusion_status'] == 0) {
                    $grand_total += $invoice['total_after_discount'];
                }
            }

            $this->data['grand_total'] = $grand_total;
            $company_info = $this->admin_invoices_model->Get_company_information($company_sid);

            if (!empty($company_info)) {
                $company_info = $company_info[0];
                $company_admin = $this->admin_invoices_model->Get_company_admin_information($company_sid);

                if (!empty($company_admin)) {
                    $company_info['email'] = $company_admin[0]['email'];
                }
            }

            $this->data['company_info'] = $company_info;
            $this->render('manage_admin/invoice/view_pending_invoices', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'send_pending_invoices':
                    $company_sid = $this->input->post('company_sid');
                    $email_address = $this->input->post('email_address');
                    $invoices = $this->invoice_model->get_unpaid_invoices($company_sid, 0);
                    $company_info = get_company_details($company_sid);
                    $grand_total = 0;

                    foreach ($invoices as $invoice) {
                        if ($invoice['exclusion_status'] == 0) {
                            $grand_total += $invoice['total_after_discount'];
                        }
                    }

                    $view_data = array();
                    $view_data['invoices'] = $invoices;
                    $view_data['company_info'] = $company_info;
                    $view_data['grand_total'] = $grand_total;
                    $pending_invoices_detail = $this->load->view('manage_admin/invoice/pending_invoices_partial', $view_data, true);
                    $replacement_array = array();
                    $replacement_array['company_name'] = $company_info['CompanyName'];
                    $replacement_array['client_name'] = 'Accounts Manager / Department';
                    $replacement_array['invoices_detail'] = $pending_invoices_detail;
                    $replacement_array['payment_page_link'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url('settings/pending_invoices') . '" target="_blank">Click here</a>';
                    log_and_send_templated_email(DUE_INVOICES_DETAIL, $email_address, $replacement_array);
                    $this->session->set_flashdata('message', 'Pending Invoices List Successfully Sent to Client!');
                    redirect('manage_admin/invoice/pending_invoices', 'refresh');
                    break;
                case 'set_exclusion_status':
                    $company_sid = $this->input->post('company_sid');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $status = $this->input->post('exclusion_status');
                    $this->invoice_model->set_exclusion_status($company_sid, $invoice_sid, $status);

                    if ($status == 1) {
                        $this->session->set_flashdata('message', 'Invoice Excluded!');
                    } else {
                        $this->session->set_flashdata('message', 'Invoice Included!');
                    }

                    redirect('manage_admin/invoice/view_pending_invoices/' . $company_sid, 'refresh');
                    break;
                case 'delete_invoice':
                    $company_sid = $this->input->post('company_sid');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $this->invoice_model->set_invoice_status($company_sid, $invoice_sid, 'deleted');
                    $this->session->set_flashdata('message', 'Invoice Deleted!');
                    redirect('manage_admin/invoice/view_pending_invoices/' . $company_sid, 'refresh');
                    break;
            }
        }
    }

    public function view_pending_commissions($commission_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'view_pending_commission';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $commission = $this->invoice_model->get_unpaid_commissions($commission_sid);

            if (!empty($commission)) {
                $this->data['commission'] = $commission[0];
                $this->data['commission_sid'] = $commission_sid;
                $this->invoice_model->update_view_pending_commission_status($commission_sid);
                $this->render('manage_admin/invoice/view_pending_commissions', 'admin_master');
            } else {
                $this->session->set_flashdata('message', 'Commission invoice not found!');
                redirect('manage_admin/invoice/pending_commissions', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'send_pending_invoices':
                    $company_sid = $this->input->post('company_sid');
                    $email_address = $this->input->post('email_address');
                    $invoices = $this->invoice_model->get_unpaid_invoices($company_sid, 0);
                    $company_info = get_company_details($company_sid);
                    $grand_total = 0;

                    foreach ($invoices as $invoice) {
                        if ($invoice['exclusion_status'] == 0) {
                            $grand_total += $invoice['total_after_discount'];
                        }
                    }

                    $view_data = array();
                    $view_data['invoices'] = $invoices;
                    $view_data['company_info'] = $company_info;
                    $view_data['grand_total'] = $grand_total;
                    $pending_invoices_detail = $this->load->view('manage_admin/invoice/pending_invoices_partial', $view_data, true);
                    $replacement_array = array();
                    $replacement_array['company_name'] = $company_info['CompanyName'];
                    $replacement_array['client_name'] = 'Accounts Manager / Department';
                    $replacement_array['invoices_detail'] = $pending_invoices_detail;
                    $replacement_array['payment_page_link'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url('settings/pending_invoices') . '" target="_blank">Click here</a>';
                    log_and_send_templated_email(DUE_INVOICES_DETAIL, $email_address, $replacement_array);
                    $this->session->set_flashdata('message', 'Pending Invoices List Successfully Sent to Client!');
                    redirect('manage_admin/invoice/pending_invoices', 'refresh');
                    break;
                case 'set_exclusion_status':
                    $company_sid = $this->input->post('company_sid');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $status = $this->input->post('exclusion_status');
                    $this->invoice_model->set_exclusion_status($company_sid, $invoice_sid, $status);

                    if ($status == 1) {
                        $this->session->set_flashdata('message', 'Invoice Excluded!');
                    } else {
                        $this->session->set_flashdata('message', 'Invoice Included!');
                    }

                    redirect('manage_admin/invoice/view_pending_invoices/' . $company_sid, 'refresh');
                    break;
                case 'delete_invoice':
                    $company_sid = $this->input->post('company_sid');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $this->invoice_model->set_invoice_status($company_sid, $invoice_sid, 'deleted');
                    $this->session->set_flashdata('message', 'Invoice Deleted!');
                    redirect('manage_admin/invoice/view_pending_invoices/' . $company_sid, 'refresh');
                    break;
            }
        }
    }

    public function print_pending_invoices($company_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'pending_invoices';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $invoices = $this->invoice_model->get_unpaid_invoices($company_sid, 0);
            $this->data['invoices'] = $invoices;
            $this->data['company_sid'] = $company_sid;
            $grand_total = 0;

            foreach ($invoices as $invoice) {
                if ($invoice['exclusion_status'] == 0) {
                    $grand_total += $invoice['total_after_discount'];
                }
            }

            $this->data['grand_total'] = $grand_total;
            $company_info = get_company_details($company_sid);
            $this->data['company_info'] = $company_info;
            $this->data['show_header_footer'] = true;
            $this->data['title'] = 'Overdue Invoices';
            $this->render('manage_admin/invoice/pending_invoices_partial', 'admin_master_print');
        }
    }

    public function ajax_responder()
    {
        $admin_user_id = $this->ion_auth->user()->row()->id;

        if ($_POST) {
            if (isset($_POST['perform_action'])) {
                $perform_action = $_POST['perform_action'];

                switch ($perform_action) {
                    case 'activate_invoice_features':
                        $company_sid = $_POST['company_sid'];
                        $invoice_sid = $_POST['invoice_sid'];
                        activate_invoice_features($company_sid, $invoice_sid);
                        echo 'success';
                        break;
                    case 'send_invoice_to_client_by_email':
                        $invoice_sid = $_POST['invoice_sid'];
                        $email_address = $_POST['email_address'];
                        $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, false);
                        $invoice = generate_invoice_html($invoice_sid);
                        $to = $email_address;
                        $from = FROM_EMAIL_ACCOUNTS;
                        $subject = STORE_NAME . ' Invoice # ' . $invoice_data['invoice_number'];
                        $body = EMAIL_HEADER
                            . $invoice
                            . EMAIL_FOOTER;

                        log_and_sendEmail($from, $to, $subject, $body, STORE_NAME);
                        echo 'success';
                        break;
                    case 'process_cash_payment':
                        $invoice_sid = $this->input->post('invoice_sid');
                        $company_sid = $this->input->post('company_sid');
                        $invoice_description = $this->input->post('payment_description');
                        $invoice_amount = $this->input->post('invoice_amount');
                        $processed_by = $this->input->post('admin_user_id');
                        $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);
                        $company_admin_user = db_get_first_admin_user($company_sid);
                        $this->load->model('notification_emails_model');
                        $company_billing_notification_status = $this->notification_emails_model->get_notifications_status($company_sid, 'billing_invoice_notifications');
                        $billing_notification_status = 0;

                        if (!empty($company_billing_notification_status)) {
                            $billing_notification_status = $company_billing_notification_status['billing_invoice_notifications'];
                        }

                        $company_billing_contacts = array();

                        if ($billing_notification_status == 1) {
                            $company_billing_contacts = getNotificationContacts($company_sid, 'billing_invoice', 'billing_invoice_notifications');
                        }

                        $this->admin_invoices_model->update_admin_invoice_payment_table($invoice_sid, $processed_by, 'paid', 'cash', $invoice_description);
                        $this->receipts_model->generate_new_receipt($company_sid, $invoice_sid, $invoice_amount, 'Cash', $admin_user_id, 'super_admin', 'admin_invoice'); // Generate Receipt - Start                        
                        //Todo Handle Cron Job table entries and feature activation                        
                        $this->admin_invoices_model->Mark_automatic_invoice_as_processed_on_manual_payment($invoice_sid, 'cash_payment', 'cash_payment'); // Update Automatic Invoice Processed Status                        
                        $this->session->set_flashdata('message', 'Payment Successful!'); // Redirect to Invoices                        
                        activate_invoice_features($company_sid, $invoice_sid); // activate features against invoice.

                        $replacement_array = array(); //Send Payment Notification to admin.
                        $replacement_array['invoice_number'] = $invoice_data['invoice_number'];
                        $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                        $replacement_array['payment_method'] = 'Cash';
                        $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                        $replacement_array['invoice'] = generate_invoice_html($invoice_sid);
                        //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $company_admin_user['email'], $replacement_array);                        
                        $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                        if (!empty($system_notification_emails)) { // Send Emails Through System Notifications Email - Start
                            foreach ($system_notification_emails as $system_notification_email) {
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                            }
                        } // Send Emails Through System Notifications Email - End

                        if (!empty($company_billing_contacts)) {
                            foreach ($company_billing_contacts as $company_billing_contact) {
                                $email_address = $company_billing_contact['email'];
                                $replacement_array['company_admin'] = $company_billing_contact['contact_name'];
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $email_address, $replacement_array);
                            }
                        }

                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'process_check_payment':
                        $invoice_sid = $this->input->post('invoice_sid');
                        $company_sid = $this->input->post('company_sid');
                        $invoice_description = $this->input->post('payment_description');
                        $check_number = $this->input->post('check_number');
                        $invoice_amount = $this->input->post('invoice_amount');
                        $processed_by = $this->input->post('admin_user_id');
                        $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);
                        $company_admin_user = db_get_first_admin_user($company_sid);
                        $this->load->model('notification_emails_model');
                        $company_billing_notification_status = $this->notification_emails_model->get_notifications_status($company_sid, 'billing_invoice_notifications');
                        $billing_notification_status = 0;

                        if (!empty($company_billing_notification_status)) {
                            $billing_notification_status = $company_billing_notification_status['billing_invoice_notifications'];
                        }

                        $company_billing_contacts = array();

                        if ($billing_notification_status == 1) {
                            $company_billing_contacts = getNotificationContacts($company_sid, 'billing_invoice', 'billing_invoice_notifications');
                        }

                        $this->admin_invoices_model->update_admin_invoice_payment_table($invoice_sid, $processed_by, 'paid', 'Check', $invoice_description, $check_number);
                        $this->receipts_model->generate_new_receipt($company_sid, $invoice_sid, $invoice_amount, 'Check', $admin_user_id, 'super_admin', 'admin_invoice'); // Generate Receipt - Start
                        //Todo Handle Cron Job table entries and feature activation                        
                        $this->admin_invoices_model->Mark_automatic_invoice_as_processed_on_manual_payment($invoice_sid, 'check_payment', 'check_payment'); // Update Automatic Invoice Processed Status
                        $this->session->set_flashdata('message', 'Payment Successful!'); // Redirect to Invoices                        
                        activate_invoice_features($company_sid, $invoice_sid); // activate features against invoice.
                        $replacement_array = array(); //Send Payment Notification to admin.
                        $replacement_array['invoice_number'] = $invoice_data['invoice_number'];
                        $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                        $replacement_array['payment_method'] = 'Check';
                        $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                        $replacement_array['invoice'] = generate_invoice_html($invoice_sid);
                        //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $company_admin_user['email'], $replacement_array);                        
                        $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                        if (!empty($system_notification_emails)) { // Send Emails Through System Notifications Email - Start
                            foreach ($system_notification_emails as $system_notification_email) {
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                            }
                        } // Send Emails Through System Notifications Email - End

                        if (!empty($company_billing_contacts)) {
                            foreach ($company_billing_contacts as $company_billing_contact) {
                                $email_address = $company_billing_contact['email'];
                                $replacement_array['company_admin'] = $company_billing_contact['contact_name'];
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $email_address, $replacement_array);
                            }
                        } else {
                            //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $company_admin_user['email'], $replacement_array);
                        }

                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'send_update_cc_request_email':
                        $company_sid = $this->input->post('company_sid');
                        $invoice_sid = $this->input->post('invoice_sid');
                        $email_address = $this->input->post('email_address');
                        $contact_name = $this->input->post('contact_name');
                        $company_name = $this->input->post('company_name');
                        $email_template = $this->input->post('email_template');
                        $card_no = $this->input->post('card_no');
                        $credit_card = db_get_cc_detail($card_no); //{{contact_name}}

                        if ($email_template == 'UPDATE_CREDIT_CARD_REQUEST') {
                            $replacement_array = array();
                            $replacement_array['contact_name'] = ucwords($contact_name);
                            $replacement_array['company_name'] = '<strong>' . ucwords(strtolower($company_name)) . '</strong>';
                            $replacement_array['card_number'] = $credit_card['number'];
                            $replacement_array['name_on_card'] = $credit_card['name_on_card'];
                            $replacement_array['expiration_month'] = $credit_card['expire_month'];
                            $replacement_array['expiration_year'] = $credit_card['expire_year'];
                            $replacement_array['cc_management_link'] = anchor('cc_management', 'Credit Card Management');
                            log_and_send_templated_email(UPDATE_CREDIT_CARD_REQUEST, $email_address, $replacement_array);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Credit Card Update Request Successfully Sent!');
                        } else {
                            $replacement_array = array();
                            $replacement_array['billing_contact_name'] = ucwords($contact_name);
                            $replacement_array['company_name'] = ucwords($company_name);
                            $replacement_array['card_number'] = $credit_card['number'];
                            $replacement_array['name_on_card'] = $credit_card['name_on_card'];
                            $replacement_array['expiration_month'] = $credit_card['expire_month'];
                            $replacement_array['expiration_year'] = $credit_card['expire_year'];
                            log_and_send_templated_email(CREDIT_CARD_EXPIRATION_NOTIFICATION, $email_address, $replacement_array);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Credit Card Expiration Notification Successfully Sent!');
                        }
                        // $replacement_array = array();
                        // $replacement_array['contact_name'] = 'Account Manager';
                        // $replacement_array['company_name'] = '<strong>' . ucwords(strtolower($company_name)) . '</strong>';
                        // $replacement_array['cc_management_link'] = anchor('cc_management', 'Credit Card Management');
                        // log_and_send_templated_email(UPDATE_CREDIT_CARD_REQUEST, $email_address, $replacement_array);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Credit Card Update Request Successfully Sent!');
                        redirect('manage_admin/misc/process_payment_admin_invoice/' . $invoice_sid, 'refresh');
                        break;
                }
            }
        }
    }

    public function edit_admin_invoice($invoice_sid = 0)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'view_admin_invoice';
        $admin_id = $this->ion_auth->user()->row()->id;

        if ($invoice_sid > 0) {
            if ($admin_id != 1) { // Only S
                $this->session->set_flashdata('message', '<strong>Error: </strong>You are not authorised. Please contact administrator!');
                redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
            }

            $company_data = array();
            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, true);

            if (!empty($invoice_data)) {
                $created_by = get_administrator_user_info($invoice_data['created_by']);
                $payment_processed_by = get_administrator_user_info($invoice_data['payment_processed_by']);
                $admin_name = ucwords($created_by['first_name'] . ' ' . $created_by['last_name']);

                if (!empty($payment_processed_by)) {
                    $processed_by = ucwords($payment_processed_by['first_name'] . ' ' . $payment_processed_by['last_name']);
                } else {
                    $processed_by = '';
                }

                $invoice_data['created_by_name'] = $admin_name;
                $invoice_data['payment_processed_by'] = $processed_by;
                $company_data = $this->admin_invoices_model->Get_company_information($invoice_data['company_sid']);

                if (!empty($company_data)) {
                    $company_data = $company_data[0];
                    $country_name = db_get_country_name($company_data['Location_Country']);
                    $country_name = $country_name['country_name'];
                    $state_name = db_get_state_name_only($company_data['Location_State']);
                    $company_data['state_name'] = $state_name;
                    $company_data['country_name'] = $country_name;
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
                redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
            }

            $this->data['company_info'] = $company_data;
            $this->data['invoice'] = $invoice_data;
            // echo '<pre>'; print_r($company_data); exit;
            $this->data['page_title'] = 'Edit Invoice #' . $invoice_data['invoice_number'];
            $this->form_validation->set_rules('sid', 'sid', 'numeric|required');

            if ($this->form_validation->run() == false) {
                $this->render('manage_admin/invoice/edit_admin_invoice', 'admin_master');
            } else {
                $data_to_update = array();
                $created = $this->input->post('created');

                if (empty($created) || is_null($created) || $created == '') {
                    $data_to_update['created'] = NULL;
                } else {
                    $data_to_update['created'] = DateTime::createFromFormat('m-d-Y', $created)->format('Y-m-d');
                }

                $data_to_update['payment_date'] = $this->input->post('payment_status');

                if (isset($_POST['payment_date'])) {
                    $payment_date = $this->input->post('payment_date');

                    if (empty($payment_date) || is_null($payment_date) || $payment_date == '') {
                        $data_to_update['payment_date'] = NULL;
                    } else {
                        $data_to_update['payment_date'] = DateTime::createFromFormat('m-d-Y', $payment_date)->format('Y-m-d');
                    }
                }

                if (isset($_POST['payment_method'])) {
                    $data_to_update['payment_method'] = $this->input->post('payment_method');
                }

                if (isset($_POST['check_number'])) {
                    $data_to_update['check_number'] = $this->input->post('check_number');
                }

                if (isset($_POST['payment_description'])) {
                    $data_to_update['payment_description'] = $this->input->post('payment_description');
                }

                if (isset($_POST['credit_card_number'])) {
                    $data_to_update['credit_card_number'] = $this->input->post('credit_card_number');
                }

                if (isset($_POST['credit_card_type'])) {
                    $data_to_update['credit_card_type'] = $this->input->post('credit_card_type');
                }

                $this->admin_invoices_model->update_admin_invoice($invoice_sid, $data_to_update);
                $this->session->set_flashdata('message', '<strong>Success: </strong>Invoice Updated Successfully!');
                redirect('manage_admin/invoice/view_admin_invoice/' . $invoice_sid, 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
            redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
        }
    }

    public function delete_commission($invoice_sid = 0)
    {

        if ($invoice_sid > 0) {
            $payment_voucher_id = $this->admin_invoices_model->get_payment_voucher_id($invoice_sid);
            if ($payment_voucher_id > 0) {
                $this->admin_invoices_model->delete_payment_voucher($payment_voucher_id);
            }
            $this->admin_invoices_model->delete_commission_invoice($invoice_sid);
            echo 'success';
        }
    }
}
