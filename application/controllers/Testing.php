<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");

    }

    function testMcrypt()
    {
        //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
        $plaintext = "message to be encrypted 123 If you want to go encryption/decryption";
        $cipher = "aes-256-gcm";
        $key = '#&$sdfdadasdsaderfvrfgbty78hnmuik263uifs5634d9ec9tc8n3n845394395342mc54x205c4935c7 0234';

        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
        //store $cipher, $iv, and $tag for decryption later
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
        echo 'Original Text = '.$original_plaintext."<br>";
        echo 'Encrypted Text = '.$ciphertext."<br>";


        /*$text = 'If you want to go encryption/decryption';
        $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $text, MCRYPT_MODE_CBC, md5(md5($key)) ));

        echo $encoded;*/
    }

    function getDataTest(){
        $a = $this
            ->db
            ->select('sid, stage, is_disabled, is_ems_module')
            ->where('module_slug', 'employers')
            ->limit(1)
            ->get('modules');
        //
        $b = $a->row_array();
        if (isset($b))
        {
            echo 'value => '.$b['stage'];
            echo 'value => '.$b['is_disabled'];
            echo 'value => '.$b['is_ems_module'];
        }
        $a->free_result();

        echo "<pre>";
        print_r($b);
        echo "------------<br>";
        print_r($a);
        echo "============<br>";
        die();
    }

    function text($id)
    {
        //
        $returnArray = [];
        $returnArray['Count'] = [];
        $returnArray['Records'] = [];
        //
        $records = $this->cpp->GetRecords($id);
        //
        if(!empty($records)){
            return $returnArray;
        }
        //
        foreach($records as $record){
            //
            $returnArray['Count']++;
        }
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

}