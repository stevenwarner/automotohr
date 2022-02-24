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
    

    function get_invoices($company_sid){
        $i = 0;
        $productArray = array();
        $productIDsInArray = array();

        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'Paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        $orders = $record_obj->result_array();
        $record_obj->free_result();

        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);
            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) { //if the product is already added in the array.
                        foreach ($productArray as $myKey => $pro) {
                            if ($pro['product_sid'] == $product && $pro['no_of_days'] == $dataArray['no_of_days'][$key]) {
                                $pro['remaining_qty'] = $pro['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                            } else if (!in_array($product, $productIDsInArray)) {
                                $productArray[$i]['product_sid'] = $product;
                                $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            }

                            $productArray[$myKey] = $pro;
                        }
                    } else { //if the product is not already added in the array.
                        if ($dataArray['item_remaining_qty'][$key] > 0) {
                            $productIDsInArray[$i] = $product;
                            $productArray[$i]['product_sid'] = $product;
                            $productArray[$i]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];

                            if (isset($dataArray['no_of_days']))
                                $productArray[$i]['no_of_days'] = $dataArray['no_of_days'][$key];
                            $i++;
                        }
                    }
                }
            }
        }
        echo "<pre>";
        print_r($orders);
        //

        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        // $this->db->where('product_sid', "10");
        $this->db->where('status', 'paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        //
        $invoices = $record_obj->result_array();
        $record_obj->free_result();
        //
        echo "<pre>";
        foreach ($invoices as $invoice) {
            if ($invoice["product_sid"] == 10) {
                $invoice_info = unserialize($invoice['serialized_items_info']);
                //
                echo "Row sid: ".$invoice["sid"]." | Created On: ".$invoice["date"]. " | product sid: ".$invoice["product_sid"]." | Item Remaining Qty: ". $invoice_info['item_remaining_qty'][0]."<br><br>";
            }
            
        }
        
        die("process_end");
    }
}