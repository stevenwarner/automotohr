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