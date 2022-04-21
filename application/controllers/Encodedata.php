<?php defined('BASEPATH') || exit('No direct script access allowed');

class Encodedata extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");
        $this->load->model("test_model", "tm");

    }






    public function updateencodingdocument(){
  
       
        $this->db->select('sid,cc_type,cc_holder_name,cc_number,cc_expiration_month,cc_expiration_year');
        $this->db->from('form_document_credit_card_authorization');
        $query = $this->db->get();
    
        if ( $query->num_rows() > 0 )
        {
            foreach ($query->result()as $row){
           
                $sid = $row->sid;
                 $key = '#&$sdfdadasdsaderfvrfgbty78hnmuik263uifs5634d';
                 $cc_type = $row->cc_type ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->cc_type), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 $cc_holder_name = $row->cc_holder_name ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->cc_holder_name), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 $cc_holcc_number = $row->cc_number ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->cc_number), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 $cc_expiration_month = $row->cc_expiration_month ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->cc_expiration_month), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 $cc_expiration_year = $row->cc_expiration_year ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->cc_expiration_year), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 
                 $data_to_update=[
                     'cc_type'=>base64_encode(openssl_encrypt($cc_type, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                     'cc_holder_name'=>base64_encode(openssl_encrypt($cc_holder_name, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                     'cc_number'=>base64_encode(openssl_encrypt($cc_holcc_number, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                     'cc_expiration_month'=>base64_encode(openssl_encrypt($cc_expiration_month, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                     'cc_expiration_year'=>base64_encode(openssl_encrypt($cc_expiration_year, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                 ];
               
                 $this->db->where('sid', $sid);
                 $this->db->update('form_document_credit_card_authorization', $data_to_update);
                 echo  "<Done>"; 
             
            }

        }

    }



    public function updateencodinguser(){
    
        $this->db->select('sid , key');
        $this->db->from('users');
        $query = $this->db->get();
    
        if ( $query->num_rows() > 0 )
        {
            foreach ($query->result()as $row){
           
                $sid = $row->sid;
                 $key = '#&$sdfdadasdsaderfvrfgbty78hnmuik263uifs5634d';
                 $user_key = $row->key ? rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row->key), MCRYPT_MODE_CBC, md5(md5($key))), "\0") : '';
                 
                 $data_to_update=[
                     'key'=>base64_encode(openssl_encrypt($user_key, 'aes-128-ecb', $key, OPENSSL_RAW_DATA)),
                      ];

                 $this->db->where('sid', $sid);
                 $this->db->update('users', $data_to_update);
                 echo  "<Done>"; 
            }

        }

    }


}