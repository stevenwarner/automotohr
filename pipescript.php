<?php

require_once "vendor/autoload.php";

use ZBateson\MailMimeParser\Message;

//
if (!function_exists('getCreds')) {
    function getCreds($index = false)
    {
        //
        $file = '../creds.json';
        //
        $h = fopen($file, 'r');
        //
        $data = json_decode(fread($h, filesize($file)));
        //
        fclose($h);
        //
        return $index ? $data->$index : $data;
    }
}

function brToNl(string $text)
{
    $breaks = array("<br />", "<br>", "<br/>");  
    return str_ireplace($breaks, "\n", $text);
}

function extractBody($text)
{
    //
    $message = '';
    //
    $splits = preg_split("/^On(.*?)+(.|\n)(.*?)wrote:/im", $text);
    //
    if (count($splits) > 1) {
        $text = $splits[0];
    }

    //
    $splits = preg_split("/(\r\n)|(\n)/im", $text);
    //
    foreach ($splits as $split) {
        //
        $split = trim($split);
        //
        if (
            preg_match("/^On(.*?)+(.|\n)(.*?)wrote:/im", $split) ||
            preg_match('/________________________________/i', $split) ||
            preg_match('/(>)\s+On/i', $split) || 
            preg_match('/From:\s[a-zA-Z]/i', $split) ||
            preg_match('/>\s+/i', $split)
        ) {
            break;
        }  
        //
        $message .= "\n" . $split;
    }
    //
    return trim($message);
}
//
$creds = getCreds('AHR');
//
$hostname = $creds->DB->Host;
$username = $creds->DB->User;
$password = $creds->DB->Password;
$dbhandle = mysqli_connect($hostname, $username, $password, $creds->DB->Database) or die('Unable to Connect to MySql');
//
$query_string = "select full_info from incoming_mails where sid = 98";
$result = mysqli_query($dbhandle, $query_string);
$messageData = mysqli_fetch_assoc($result)['full_info'];
//
$data = file_get_contents("message_full.txt");
// print_r($messageData);

$secret_key = substr($messageData, strpos($messageData, 'message_id:') + 11, 50);
$secret_key = trim(preg_replace('/\s+/', '', $secret_key));
$secret_key = str_replace('=', "", $secret_key);
$secret_key = str_replace(' ', '', $secret_key);
$secret_key = str_replace('_', '', $secret_key);

$message = Message::from($messageData, true);
//
$subject = $message->getHeaderValue('Subject');
$text = $message->getTextContent();
$html = $message->getHtmlContent();
$from = $message->getHeader('From');
$fromName = $from->getPersonName();
$fromEmail = $from->getEmail();
//
$emailBody = $text ?? brToNl(strip_tags($html,["<br />", "<br>", "<br/>"]));
//
echo "<br />Message Body:<br /><br />";
echo "<pre>";
print_r($emailBody);
echo "<br /><br />";
echo "<br /><br />";
echo "<br />Actual Message:<br /><br />";
$messageBody = extractBody($emailBody);
print_r($messageBody);
echo "<br /><br />";
die();



$to = $message->getHeader('To');
// first email address can be accessed directly
$firstToName = $to->getPersonName();
$firstToEmail = $to->getEmail();

// foreach ($to->getAllAddresses() as $addr) {
//     $toName = $to->getPersonName();
//     $toEmail = $to->getEmail();

//     echo $toName . "<br />";
//     echo $toEmail . "<br />";
// }

// $attachment = $message->getAttachmentPart(0);
// $fname = $attachment->getFileName();
// $stream = $attachment->getContentStream();
// $attachment->saveContent('destination-file.ext');

// $text = strip_tags($html);

// $text = preg_split('/On\s+/ wrote:', $text)[0];

// print_r($text);
// die;

// $lines = preg_split('/(Sent from))/i', $text);


// echo "Email Subject: ".$subject."<br /><br />";
// echo "Email From: ".$from."<br /><br />";
// echo "Email Name: ".$fromName."<br /><br />";
// echo "Email Email: ".$fromEmail."<br /><br />";
// echo "Secret Key: ".$secret_key."<br /><br />";

// echo $messageData;


// https://mail-mime-parser.org/