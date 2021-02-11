<?php
if ($_SERVER['SERVER_NAME'] == 'localhost') { // check server and connect to database
    $base_url = "http://" . $_SERVER['SERVER_NAME'] . '/automotoCI';
    $username = "root";
    $password = "";
    $hostname = "localhost";
//    $conn = mysql_connect($servername, $username, $password);
//    mysql_select_db('automotoci',$conn);
//    echo 'here'; exit;
    $connection = mysqli_connect($hostname, $username, $password, 'autoamr1') or die('Unable to Connect to MySql');
} else {
    $hostname = "localhost";
    $username = "automoto_super";
    $password = '6Q?lgxvOo9u_EZpgVM';
    $connection = mysqli_connect($hostname, $username, $password, 'automoto_hr') or die('Unable to Connect to MySql');
}

$myArray = array();
$user_sid = $_REQUEST['id'];
$country_name = '';
$state_name = '';

if (!empty($user_sid)) {
    $sub_domain_query = mysqli_query($connection, "SELECT `sub_domain` FROM `portal_employer` where `user_sid` = '" . $user_sid . "' LIMIT 1");
    $sub_domain = $sub_domain_query->fetch_assoc();
//        echo '<pre>'; print_r($sub_domain); exit;
    $query = mysqli_query($connection, "SELECT `has_job_approval_rights`, `sid` FROM `users` WHERE `sid` = " . $user_sid);

    $job_approval_module_status = 0;
    if ($query->num_rows > 0) {
        while ($result = $query->fetch_assoc()) {
            $job_approval_module_status = $result['has_job_approval_rights'];
            break;
        }
    }

    if ($job_approval_module_status == 0) {
        $query = mysqli_query($connection, "SELECT `sid`, `JobCategory`, `Title`, `Location_City`, `Location_Country`, `Location_State`, `JobType` FROM `portal_job_listings` where user_sid = '" . $user_sid . "' and active = 1 ORDER BY `sid` DESC");
    } else {
        $query = mysqli_query($connection, "SELECT `sid`, `JobCategory`, `Title`, `Location_City`, `Location_Country`, `Location_State`, `JobType`, `approval_status` FROM `portal_job_listings` where user_sid = '" . $user_sid . "' and active = 1 AND approval_status = 'approved' ORDER BY `sid` DESC ");
    }

    if ($query->num_rows > 0) {
        while ($result = $query->fetch_assoc()) { //echo '<pre>'; print_r($result); exit;
            $country_id = $result['Location_Country'];

            if (!empty($country_id)) {
                $country_query = mysqli_query($connection, "SELECT country_name FROM `countries` where sid = $country_id");
                $country_array = $country_query->fetch_assoc();
                $country_name = $country_array['country_name'];
            }

            $state_id = $result['Location_State'];

            if (!empty($country_id)) {
                $state_query = mysqli_query($connection, "SELECT state_name FROM `states` where sid = $state_id");
                $state_array = $state_query->fetch_assoc();
                $state_name = $state_array['state_name'];
            }

            $job_category = array();
            if ($result['JobCategory'] != null ) {
                $categories_query = mysqli_query($connection, "SELECT `value` FROM `listing_field_list` WHERE field_sid = '198' and  sid IN(" . $result['JobCategory'] . ")");
                if ($query->num_rows > 0) {
                    while ($categories = $categories_query->fetch_assoc()) {
                        $job_category[] = ucwords($categories['value']);
                    }
                }

            }

            if(!empty($job_category)){
                $job_category = implode(',', $job_category);
            } else {
                $job_category = '';
            }

            $has_records = 'true';
            $subdomain = $sub_domain['sub_domain'];
            $title = ucwords($result['Title']);
            $JobDescription = $result['JobDescription'];

            $myArray[] = array(
                "sid" => $result['sid'],
                "has_records" => $has_records,
                "sub_domain" => $subdomain,
                "JobCategory" => $job_category,
                "Title" => $title,
                "JobDescription" => '',
                "country_name" => $country_name,
                "state_name" => $state_name,
                "JobType" => $result['JobType'],
                "Location_City" => $result['Location_City']
            );

//array('has_records'=>'true',"sid"=>$result['sid'],"sub_domain"=>$sub_domain['sub_domain'],"JobCategory"=>$job_category,"Title"=>$result['Title'],"JobDescription"=>$result['JobDescription'],"country_name"=>$country_name,"state_name"=>$state_name,"JobType"=>$result['JobType']);
        }
    } else {
        $myArray[0] = array('has_records' => 'false', 'Title' => "Sorry! No Active jobs found!");
    }
} else {
    $myArray[0] = array('has_records' => 'false', 'Title' => "Sorry! No record found!");
}
//    echo '<pre>'; print_r($myArray); exit;

finish($myArray);

function finish($myArray)
{
    header("content-type:application/json");
    if (isset($_GET['callback'])) {
        echo $_GET['callback'] . "(";
    }
    echo json_encode($myArray);
    if (isset($_GET['callback'])) {
        echo ")";
    }
    exit;
}

?>