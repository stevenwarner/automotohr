<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assurehire extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('assurehire_model');
        //
        $this->load->helper('assurehire');
    }

    // Get Packages
    function getPackages(){
        //
        $list = getPackagesList();
        //
        if(!sizeof($list)) {
            exit(0);
        }
        //
        $ids = [];
        //
        foreach($list as $package){
            $ids[] = $package['id'];
            // Check if package already exists
            $exists = $this->assurehire_model->packageExists($package['id']);
            //
            if(!$exists){
                // Add the package
                $this->assurehire_model->addProduct([
                    'name' => $package['name'].' Package - AssureHire',
                    'package_code' => $package['id'],
                    'short_description' => implode(", ", $package['descriptions']),
                    'detailed_description' => implode(", ", $package['descriptions']),
                    'active' => 0,
                    'number_of_postings' => 10,
                    'price' => 0,
                    'sort_order' => 1,
                    'cost_price' => 0,
                    'expiry_days' => 7,
                    'in_market' => 1,
                    'product_brand' => 'assurehire',
                    'product_type' => 'background-checks',
                    'product_image' => 'AssureHire-512-JXglz.png'
                ]);
            } else {
                // Check the changes and update the package
                // Add the package
                $this->assurehire_model->updateProduct([
                    'name' => $package['name'].' Package - AssureHire',
                    'product_brand' => 'assurehire',
                    'short_description' => implode(", ", $package['descriptions']),
                    'detailed_description' => implode(", ", $package['descriptions'])
                    ], 
                    ['package_code' => $package['id']]
                );
            }
    
        }
        //
        exit(0);
    }

    public function cb ($orderReference = null) {
        //
        $r = ['status' => 'FAILED'];
        //
    	if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
            $r['error'] = 'Invalid request type.';
            $this->resp($r);
        }
        //
        $json_params = file_get_contents("php://input");
        //
    	if (strlen($json_params) == 0 || !$this->isValidJSON($json_params)) {
            $r['error'] = 'Invalid JSON.';
            $this->resp($r);
        }
        //
        $order_status = json_decode($json_params, true, 512, JSON_BIGINT_AS_STRING);
        // Let's save the response for later
        $path = APPPATH.'../../AHR_DATA/assurehire';
        //
        if(!is_dir($path)){
            mkdir($path, DIR_WRITE_MODE, true);
        }
        //
        $file_name = $path.'/'.$order_status['orderId'].'.json';
        //
        $file = fopen($file_name, 'w');
        //
        fwrite($file, $json_params);
        //
        fclose($file);
        //
        $t = $this->assurehire_model->validateId($orderReference, $order_status['orderId']);
        //
        if(!$t){
            $r['error'] = 'Invalid order Id / order reference.';
            $this->resp($r);
        }
        //
        $this->assurehire_model->update_order_status($order_status['orderId'], serialize($order_status));
        //
        $r['status'] = 'RECEIVED';
        //
        $this->resp($r);
    }

    public function isValidJSON($str) {
	   json_decode($str);
	   return json_last_error() == JSON_ERROR_NONE;
	}

	protected function resp($array)
    {
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }
}





