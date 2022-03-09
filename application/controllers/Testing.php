<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");
        $this->load->model("test_model", "tm");

    }
    
    //
    function sendEmailNotifications($id){
        //
        $record = $this->ccp->GetReviewByIdByReviewers($id)[0];
        //
        $hf = message_header_footer($record['company_sid'], $record['CompanyName']);
        //
        if(empty($record['Reviewees'])){
            return;
        }
        //
        $template = get_email_template(REVIEW_ADDED);

        foreach($record['Reviewees'] as $row){
            //
            $replaceArray = [];
            $replaceArray['{{first_name}}'] = ucwords($row[0]['reviewer_first_name']);
            $replaceArray['{{last_name}}'] = ucwords($row[0]['reviewer_last_name']);
            $replaceArray['{{review_title}}'] = $record['review_title'];
            
            $replaceArray['{{table}}'] = $this->load->view('table', ['records' => $row, 'id' => $record['sid']], true);
            //
            $body = $hf['header'].str_replace(array_keys($replaceArray), $replaceArray, $template['text']).$hf['footer'];

            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $row[0]['reviewer_email'],
                $template['subject'],
                $body,
                $record['CompanyName']
            );
        }
    }

    function get_invoices($company_sid){
        //
        $productIds =array();
        $productArray = array();
        $productIDsInArray = array();
        //
        $products = $this->db->get_where('products', array('product_type' => "background-checks"))->result_array();
        //
        foreach ($products as $key => $product) {
            $productIds[$key] = $product['sid'];
        }
        //
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'Paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        $orders = $record_obj->result_array();
        $record_obj->free_result();
        //
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);
            //
            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) {
                        //
                        $productArray[$product]['remaining_qty'] = $productArray[$product]['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                    } else {
                        //
                        array_push($productIDsInArray, $product);
                        $productArray[$product]['product_sid'] = $product;
                        $productArray[$product]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                        //
                        if (isset($dataArray['no_of_days']))
                            $productArray[$product]['no_of_days'] = $dataArray['no_of_days'][$key];   
                    }    
                }    
            }    
        }
        //
        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        //
        foreach ($productArray as $key => $pro) {
            foreach ($products as $myKey => $product) {
                if ($pro['product_sid'] == $product['sid']) {
                    $pro['product_image'] = $product['product_image'];
                    $pro['name'] = $product['name'];
                    $productArray[$key] = $pro;
                }
            }
        }
        //
        echo "<pre>";
        print_r($productIDsInArray);
        print_r($productArray);
        die();
    }
}