#!/usr/bin/php -q
<?php
/* Read the message from STDIN */
$fd = fopen("php://stdin", "r");
$email = ""; // This will be the variable holding the data.
while (!feof($fd)) {
    $email .= fread($fd, 1024);
}
fclose($fd);

/* Saves the data into DB */
$hostname = "localhost";
$username = "autoamr1_super";
$password = '~fV}vXo=%wA$x=w&aZ';

// Create connection
$dbhandle = mysql_connect($hostname, $username, $password)
        or die("Unable to connect to MySQL");


mysql_select_db("autoamr1_hr", $dbhandle)
        or die("Could not select examples");
$currentDate = date('Y-m-d H:i:s');
mysql_query("insert into incoming_mails (`full_info`,`date_received`) VALUES ('" . $email . "','" . $currentDate . "')") or die('Error');

//parsing email to save data in message table
require_once('PlancakeEmailParser.php');
//$emails = glob('C:/xampp/htdocs/automotoCI/assets/mails/*.txt');
//foreach ($emails as $email) {
$emailData = $email;
$emailParser = new PlancakeEmailParser($emailData);
$from = ($emailFrom = $emailParser->getFrom());
$body = $emailParser->getHTMLBody();
$bodyEnd = strpos($body, '<div class="gmail_extra">') + 25;

$to = $emailParser->getTo();
$to = $to[0];
$from = $emailParser->extract_email_address($from[0]);
$subject = $emailParser->getSubject();
$secret_key = substr($emailData, strpos($emailData, 'message_id:') + 11, 16);
$body = substr($body, 0, $bodyEnd);
//getting preious message data VIA secret key
$result = mysql_query("select * from private_message where identity_key = '$secret_key' and outbox = 1");
$messageData = mysql_fetch_assoc($result);

//saving message in private message table
$jobId=  $messageData['job_id'];
mysql_query("insert into private_message (from_id,to_id,from_type,to_type,date,subject,message,job_id) VALUES ('".$messageData['to_id']."','".$messageData['from_id']."','".$messageData['to_type']."','".$messageData['from_type']."','".$currentDate."','".$subject."','".$body."',$jobId)");




//}
//exit;


/* Saves the data into a file */
//$fdw = fopen("/home/autoamr1/public_html/www/assets/mails/pipemail.txt", "w+");
//fwrite($fdw, $email);
//fclose($fdw);
///* Script End */
?>