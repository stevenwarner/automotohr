<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }


public function testattachement(){
    error_reporting(E_ALL);
	ini_set('display_errors', 1);

    $dir = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'csv_reports';
    $temp_file_path = $dir . '/' . '1f6Vl8_Tyrone%20Allred.Resume.2019.pdf';

    require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
    $email = new PHPMailer;
    $email->AddAttachment($temp_file_path, str_replace(' ', '_', $subject) . '.csv');

            $tom = array('aleemshaukat87@gmail.com','nahmad@egenienext.com');

               foreach($tom as $tomail){
                $to = $tomail;
                $name = 'Testing name ';
                $message_date = date('Y-m-d H:i:s');
                $from = 'dev@automotohr.com';
                $subject="Testing subject";


                $hf = message_header_footer_domain(8578, 'Demo Dev AutomotoHR');

                $body = $hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                    . '<br><br>'
                    . '</b> has sent you a private message.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . $message_date
                    . '<br><br><b>'
                    . 'Subject:</b> '
                    . $subject
                    . '<br><hr> eewrwerw qwe rw we rwrwqer wrw rwr'
                    . $hf['footer'];
                $email->CharSet = 'UTF-8';
                $email->isHTML(true);
                $email->From = $from; //Name is optional
                $email->Subject   = $subject;
                $email->Body      = $body;
                $email->AddAddress($to);
                $email->Send();






                $email->FromName = "AutoMotoHR";
                $email->addReplyTo(NULL);
               $email->CharSet = 'UTF-8';
                               $email->isHTML(true);
                               $email->From =  'events@automotohr.com'; //Name is optional
                               $email->Subject   = $subject;
                               $email->Body      = $body;
                               $email->addAddress($to);
                               $email->send();



                
               }



}



}