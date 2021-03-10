#!/usr/bin/php -q
<?php error_reporting(E_ALL);
//$devEmail = 'ahassan.egenie@gmail.com';
// $devEmail = 'dev@automotohr.com';
$devEmail = 'mubashir.saleemi123@gmail.com';
/* Read the message from STDIN */
$fd = fopen("php://stdin", "r");
$email = ""; // This will be the variable holding the data.

while (!feof($fd)) {
    $email .= fread($fd, 1024);
}

fclose($fd);
$email = str_replace("'", '"', $email);
mail($devEmail, 'PipeScript: ' . date('Y-m-d H:i:s'), $email);
$hostname = "172.31.18.37";
$username = "ahrdbadmin";
$password = '8E*QrG)M5nw6g';
$dbhandle = mysqli_connect($hostname, $username, $password, 'automoto_hr') or die('Unable to Connect to MySql');
$currentDate = date('Y-m-d H:i:s');
$query_string = "insert into incoming_mails (`full_info`,`date_received`) VALUES ('" . $email . "','" . $currentDate . "')";
$result = mysqli_query($dbhandle, $query_string);
require_once('PlancakeEmailParser.php');
$emailData = $email;
$emailParser = new PlancakeEmailParser($emailData);

if (strpos($emailData, 'message_id:')) {
    $secret_key = substr($emailData, strpos($emailData, 'message_id:') + 11, 50);
    $secret_key = trim(preg_replace('/\s+/', '', $secret_key));
    $secret_key = str_replace('=', "", $secret_key);
    $secret_key = str_replace(' ', '', $secret_key);
    $secret_key = str_replace('_', '', $secret_key);
    $query_string = "select * from private_message where identity_key = '$secret_key' and outbox = 1";
    $result = mysqli_query($dbhandle, $query_string);
    $messageData = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) > 0) {
        $from = ($emailFrom = $emailParser->getFrom());
        $to = $emailParser->getTo();
        $to = $to[0];
        $from = $emailParser->extract_email_address($from[0]);
        $subject = $emailParser->getSubject();
        $body = $emailParser->getHTMLBody();
        $attachment_name = NULL;
        $bodyEnd = strpos($body, 'notifications@applybuz.com');
        $range = substr($body, 0, $bodyEnd - 11);
        $condition_executed = '';

        if (strpos($body, '<div class="gmail_extra">')) {//solution for Gmail
            $bodyEnd = strpos($body, '<div class="gmail_extra">') + 25;
            $newBody = strip_tags(substr($body, 0, $bodyEnd), "<br>");
            $condition_executed = 'Primary IF Condition Lno: 69';
        } elseif (strpos($body, 'wrote:')) {//solution for yahoo
            $newBody = "";
            $body_array = explode("<br>", $range);
            
            if(!empty($body_array)) {
                for ($i = 0; $i < count($body_array) - 1; $i++) {
                    $newBody .= strip_tags($body_array[$i], "<br>");
                }
            } else {
                $strposition = strrpos($range, '<blockquote');

                if($strposition) {
                    $newBody = substr($range, 0, $strposition);
                }
            }
            
            $strposition_virus = strrpos($newBody, '<https://www.avast.com/sig-email?');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus);
            }
            
            $strposition_virus1 = strrpos($newBody, 'Virus-free.');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus1);
            }

            $condition_executed = 'Else IF Condition Lno: 73';
        } elseif (strpos($body, '<hr id="stopSpelling">')) {//solution for Hotmail
            $range = strpos($body, '<hr id="stopSpelling">');
            $newBody = strip_tags(substr($body, 0, $range), "<br>");
            $condition_executed = 'Else IF Condition Lno: 90';
        } else { //general solution, If all fails
            $newBody = strip_tags(substr($body, 0, $bodyEnd), "<p>");
            $explodedArray = explode('From:', $newBody);
            $newBody = $explodedArray[0];
            $condition_executed = 'Else IF Condition Lno: 94';
        }

        $getbody = $emailParser->getBody();           
        $getplainbody = $emailParser->getPlainBody();

        if($newBody == '' || $newBody == NULL) {
            $condition_executed = 'I am inside 104';
            $strposition = strrpos($range, '<blockquote');

            if($strposition) {
                $condition_executed = 'I am inside 108';
                $newBody = substr($range, 0, $strposition);
            } else {
                $condition_executed = 'I am inside 112';
                $bodyendpoint = strpos($getbody, 'notifications@applybuz.com');
                $newBody = substr($getbody, 0, $bodyendpoint);
                $strposition = strrpos($newBody, 'On');
                
                if($strposition) {
                    $newBody = substr($newBody, 0, $strposition - 2);
                }
            } 
            
            $strposition_virus1 = strrpos($newBody, 'Virus-free.');

            if($strposition_virus) {
                $newBody = substr($newBody, 0, $strposition_virus1);
            }
            
        }

    $email_parser_query = "insert into email_parser_tracking (getbody,getplainbody,gethtmlbody,newbody,secret_key,condition_executed,range_var,messageData) VALUES ('" . $getbody . "','" . $getplainbody . "','" . $body . "','" . $newBody . "','" . $secret_key . "','" . $condition_executed . "','" . $range . "','" . $messageData . "')";
    mysqli_query($dbhandle, $email_parser_query);
    
    $strposition_virus2 = strrpos($newBody, 'Virus-free.');
    
    if($strposition_virus) {
        $newBody = substr($newBody, 0, $strposition_virus2);
    }
        
    /* Check for attachments in the email and attach it to PM --- START --- */
    $headers = iconv_mime_decode_headers($emailData, 0, "ISO-8859-1");
    $headers = array_combine(array_map("strtolower", array_keys($headers)), array_values($headers));
    preg_match('/boundary=(.*)/', $headers['content-type'], $match);
    $boundary = trim($match[1], '"');
    $content_types = explode('Content-Type:', $emailData);
    $has_attachments = array();
    require_once('/home/automotohr/public_html/application/libraries/aws/aws.php');
    // To get the latest attachments
    $content_types = array_reverse($content_types);
    
    foreach ($content_types as $k => $v) {
        $content_type_parts = explode('name=', $v);
        $type = array();
        if(sizeof($content_type_parts) > 1) {
            $size = sizeof($content_type_parts);
            $getname = str_replace('"', '', $content_type_parts[1]);
            $name = trim(explode('Content-Disposition:', $getname)[0]);
            // For yahoo client
            if(preg_match('/yahoo|ymail/i', htmlentities($headers['message-id']))){
              $type = trim(preg_split('/\n/', $content_type_parts[0])[0]);
              $getname = iconv_mime_decode($getname, 0, "UTF-8" );
              $content = explode('Content-ID:', $content_type_parts[$size-1])[1];
              $name = trim($getname);
            } else if(preg_match('/(outlook|hotmail|gmail)/i', htmlentities($headers['message-id']))){
              // For outlook, gmail
              $type = trim(explode(';', $content_type_parts[0])[0]);
              $content = explode('base64', $content_type_parts[$size-1])[1];
            } else {
              $type = trim(explode(';', $content_type_parts[0])[0]);
              // Reset the name for ease
              $name = $getname = trim(ltrim(explode('|',preg_replace('/("\s+)/i', '|',  $content_type_parts[1]))[0], '"'));
              $content = "\n\n".trim(preg_split('/(\n\n)/', $content_type_parts[2])[1]);
            }

            $attachment_id_array = explode('X-Attachment-Id:', $content);
            // Check if there an attachment id set // Fix for outlook
            if(isset($attachment_id_array[1])){
              $attachment_id = $attachment_id_array[1];
              $attachment = preg_split('/(\n\n)/', $attachment_id)[1];
              $remove_boundary = explode('--'.$boundary, $attachment)[0];
            } else {
              $attachment = preg_split('/(\n\n)/', $attachment_id_array[0])[1];
              $remove_boundary = explode('--'.$boundary, $attachment)[0];
            }

            $name_array = preg_split('/\n+/', $name);
            $name = trim($name_array[0]);
            $has_attachments[] = array('size' =>$size, 'type'=>trim($type), 'name'=>$name, 'attachment'=>$remove_boundary);

            if (!empty($remove_boundary)) { //Store attachment and upload on AWS
                $attachment_decoded = base64_decode($remove_boundary);
                $filePath = "/home/automotohr/public_html/assets/mail_files/"; //making Directory to store

                if (isset($name)) {
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777);
                    }
                
                    $attachment_name = rand().'_'.$name;
                    $save_attachment = fopen($filePath . $attachment_name, 'w'); //Write data back to pdf file
                    fwrite($save_attachment, $attachment_decoded);
                    fclose($save_attachment); //close output file

                    $aws = new AwsSdk();
                    $aws->putToBucket($attachment_name, $filePath . $attachment_name, 'automotohrattachments'); //uploading file to AWS
                    unlink('/home/automotohr/public_html/assets/mail_files/' . $attachment_name);
                }
            }
        }
    }
    /* Check for attachments in the email and attach it to PM ---  END  --- */
        //saving message in private message table
        $jobId = $messageData['job_id'];
        
        if (isset($messageData['job_id']) && !empty($messageData['job_id']) && $messageData['job_id'] != "") {
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,job_id, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "',$jobId,'" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,users_type, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "','employee','" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
        } else {
            $query_string = "insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,users_type, attachment) VALUES ('" . $messageData['to_id'] . "','" . $messageData['from_id'] . "','" . $messageData['to_type'] . "','" . $messageData['from_type'] . "','" . $currentDate . "','" . $subject . "','" . $newBody . "','employee','" . $attachment_name . "')";
            mysqli_query($dbhandle, $query_string);
        }
    }
}

/* Saves the data into a file */
$fdw = fopen("/home/automotohr/public_html/assets/mails/pipemail.txt", "w+");
fwrite($fdw, $email);
fclose($fdw);
///* Script End */