<?php
   if($_SERVER['SERVER_NAME']=='localhost') { // check server and connect to database
    $base_url = "http://" . $_SERVER['SERVER_NAME'] .'/automotoCI';
    $username = "root";
    $password = "";
    $hostname = "localhost";
//    $conn = mysql_connect($servername, $username, $password);
//    mysql_select_db('automotoci',$conn);
//    echo 'here'; exit;
    $connection = mysqli_connect($hostname, $username, $password, 'autoamr1') or die('Unable to Connect to MySql');
}
else{
    $hostname = "localhost";
    $username = "automoto_super";
    $password = '6Q?lgxvOo9u_EZpgVM';
    $connection = mysqli_connect($hostname, $username, $password, 'automoto_hr') or die('Unable to Connect to MySql');
}

    $myArray = array();
    $user_sid = $_REQUEST['id'];
    $country_name = '';
    $state_name = '';
    
    if(!empty($user_sid)) {
        $sub_domain_query = mysqli_query($connection, "SELECT `sub_domain` FROM `portal_employer` where `user_sid` = '".$user_sid."'");
        $sub_domain = $sub_domain_query->fetch_assoc();
//        echo '<pre>'; print_r($sub_domain); exit;
        $query = mysqli_query($connection, "SELECT `sid`, `JobCategory`, `Title`, `Location_City`, `Location_Country`, `Location_State`, `JobType` FROM `portal_job_listings` where user_sid = '".$user_sid."' and active = 1 ORDER BY `sid` DESC");
        
            if($query->num_rows > 0) {
                while($result = $query->fetch_assoc()) { //echo '<pre>'; print_r($result); exit;
                    $country_id = $result['Location_Country'];
                    
                    if(!empty($country_id)) {
                        $country_query = mysqli_query($connection, "SELECT country_name FROM `countries` where sid = $country_id");
                        $country_array = $country_query->fetch_assoc();
                        $country_name = $country_array['country_name'];
                    }
                    
                    $state_id = $result['Location_State'];
                    
                    if(!empty($country_id)) {
                        $state_query = mysqli_query($connection, "SELECT state_name FROM `states` where sid = $state_id");
                        $state_array = $state_query->fetch_assoc();
                        $state_name = $state_array['state_name'];
                    }
                    
                    $job_category = '';
//                    if($result['JobCategory'] != null){
//                    $cat_id = explode(',' , $result['JobCategory']);
//                    $job_category = array();
//                        foreach($cat_id as $id){
//                                $result_data_list = mysql_fetch_assoc(mysql_query("SELECT value FROM `user_profile_field_list` WHERE field_sid = '129' and  sid = $id"));
//                                $job_category[] =$result_data_list['value'];
//                        }
//                        $job_category = implode(", ",$job_category);
//                    }
                    
                    $has_records = 'true';
                    $subdomain = $sub_domain['sub_domain'];
                    $title = $result['Title'];
                    $JobDescription = $result['JobDescription'];
                    
                    $myArray[] = array("sid"=>$result['sid'], "has_records"=>$has_records,"sub_domain"=>$subdomain, "JobCategory"=>$job_category, "Title"=>$title, "JobDescription"=>'',"country_name"=>$country_name,"state_name"=>$state_name,"JobType"=>$result['JobType'],"Location_City"=>$result['Location_City']);
//array('has_records'=>'true',"sid"=>$result['sid'],"sub_domain"=>$sub_domain['sub_domain'],"JobCategory"=>$job_category,"Title"=>$result['Title'],"JobDescription"=>$result['JobDescription'],"country_name"=>$country_name,"state_name"=>$state_name,"JobType"=>$result['JobType']);
                }
            } else {
                $myArray[0] = array('has_records'=>'false','Title'=>"Sorry! No Active jobs found!");
            }
    } else {
        $myArray[0] = array('has_records'=>'false','Title'=>"Sorry! No record found!");
    }
//    echo '<pre>'; print_r($myArray); exit;
    
finish($myArray);

function finish($myArray) {
    header("content-type:application/json");
    if (isset($_GET['callback'])) {
        echo $_GET['callback']."(";
    }
    echo json_encode($myArray);
    if (isset($_GET['callback'])) {
        echo ")";
    }
    exit; 
}
?>