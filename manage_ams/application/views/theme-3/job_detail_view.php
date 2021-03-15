<?php

$acDate = $job_details['activation_date'];

//echo $acDate;
if(!preg_match('/[0-9]/',$acDate)) $acDate = date('m-d-Y');

if(preg_replace('/[^0-9]/', '', $job_details['activation_date']) == '' && $job_details['approval_status_change_datetime'] != ''){
    $acDate = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $job_details['approval_status_change_datetime']
    )->format('m-d-Y');
}

$googleJobOBJ = [];
// Basic job details
$googleJobOBJ['@type'] = 'JobPosting';
$googleJobOBJ['title'] = $job_details['Title'];
$googleJobOBJ['employmentType'] = strtoupper(str_replace(' ', '_', $job_details['JobType'])); // FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER [FULL_TIME,PART_TIME]
$googleJobOBJ['datePosted'] = DateTime::createFromFormat('m-d-Y', $acDate)->format('Y-m-d');
$googleJobOBJ['validThrough'] = DateTime::createFromFormat('m-d-Y', $acDate)->add(new DateInterval('P15D'))->format('Y-m-dT00:00'); // Add interval of one month
$googleJobOBJ['description'] = $job_details['JobDescription'].' '.$job_details['JobRequirements'];
// $googleJobOBJ['logo'] = $job_details['JobDescription'].' '.$job_details['JobRequirements'];
// Organization details
$googleJobOBJ['hiringOrganization'] = [];
$googleJobOBJ['hiringOrganization']['@type'] = 'Organization';
$googleJobOBJ['hiringOrganization']['name'] = $company_details['CompanyName'];
$googleJobOBJ['hiringOrganization']['sameAs'] = $company_details['sub_domain'];
$googleJobOBJ['hiringOrganization']['logo'] = AWS_S3_BUCKET_URL.$company_details['Logo'];
// Job location details
$googleJobOBJ['jobLocation']['@type'] = 'Place';
$googleJobOBJ['jobLocation']['address'] = [];
$googleJobOBJ['jobLocation']['address']['@type'] = 'PostalAddress';
$googleJobOBJ['jobLocation']['address']['streetAddress'] = $job_details['Location'] != '' ? $job_details['Location'] : $job_details['Location_City'];
// $googleJobOBJ['jobLocation']['addressLocality'] = '';
// $googleJobOBJ['jobLocation']['addressRegion'] = '';
$googleJobOBJ['jobLocation']['address']['postalCode'] = !empty($job_details['Location_ZipCode']) ? $job_details['Location_ZipCode'] : 'N/A';
$googleJobOBJ['jobLocation']['address']['addressCountry'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CA" : "US";
// Applicant location details
$googleJobOBJ['applicantLocationRequirements']['@type'] = 'Country';
$googleJobOBJ['applicantLocationRequirements']['name'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CAN" : "USA";
$googleJobOBJ['jobLocationType']['@type'] = 'JobPosting';
$googleJobOBJ['jobLocationType']['name'] = 'TELECOMMUTE';
// Salary
$googleJobOBJ['baseSalary'] = [];
$googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
$googleJobOBJ['baseSalary']['currency'] = 'USD';
$googleJobOBJ['baseSalary']['value'] = [];
$googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';
$googleJobOBJ['baseSalary']['value']['currency'] = 'USD';
$googleJobOBJ['baseSalary']['value']['value'] = '20';
$googleJobOBJ['baseSalary']['value']['unitText'] = 'HOUR';

if($job_details['Salary'] != ''){
    $googleJobOBJ['baseSalary'] = [];
    $googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
    $googleJobOBJ['baseSalary']['currency'] = 'USD';
    $googleJobOBJ['baseSalary']['value'] = [];
    $googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';
    //
    $tmp = explode(' ', strtolower($job_details['Salary']));
    if(sizeof($tmp) > 1){
        $googleJobOBJ['baseSalary']['value']['minValue'] = str_replace('$', '', trim($tmp[0]));
        $googleJobOBJ['baseSalary']['value']['maxValue'] = str_replace('$', '', trim($tmp[2] ? $tmp[2] : $tmp[1]));
    } else $googleJobOBJ['baseSalary']['value']['value'] = $job_details['Salary'];
    if($job_details['SalaryType'] != ''){
        $salaryType = 'MONTH';
        switch ($job_details['SalaryType']) {
            case 'per_hour': $salaryType = 'HOUR'; break;
            case 'per_week': $salaryType = 'WEEK'; break;
            case 'per_year': $salaryType = 'YEAR'; break;
        }
        $googleJobOBJ['baseSalary']['value']['unitText'] = $salaryType; // HOUR, DAY, WEEK, MONTH, YEAR
    }
}
// Company identifier
$googleJobOBJ['identifier'] = [];
$googleJobOBJ['identifier']['@type'] = 'PropertyValue';
$googleJobOBJ['identifier']['name'] = $company_details['CompanyName'];
$googleJobOBJ['identifier']['value'] = $job_details['user_sid'];
// Industry
$googleJobOBJ['industry']['@type'] = 'JobPosting';
$googleJobOBJ['industry']['value'] = $job_details['JobCategory'];

// _e($company_details, true);
// _e($job_details, true);
// _e($googleJobOBJ, true, true);

echo '<script type="application/ld+json">';
echo json_encode($googleJobOBJ);
echo '</script>';
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="btn-panel top-btn">
    <a href="javascript:void(0);" class="site-btn-lg custom-theme" data-toggle="modal" data-target="#myModal">apply now</a>
</div>	
<?php $country_id = 227; ?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>					
            <div class="col-md-12">
                <div class="job-dtails">
                    <h1><?php echo $job_details['Title']; ?></h1>
                    <div class="job-info">
                        <ul>
                            <li><span>Job Type:</span><?php echo $job_details['JobType']; ?></li>
                            <li><span>Category:</span><?php echo $job_details['JobCategory']; ?></li>
                            <li><span>Published:</span><?php echo $job_details['activation_date']; ?></li>
                            <?php if(!empty($job_details['Salary'])) {?>
                                    <li><span>Salary: </span><?php   echo $job_details['Salary'];                                 
                                                        if(!empty($job_details['SalaryType'])) {
                                                            echo '&nbsp;'.  ucwords(str_replace('_', ' ', $job_details['SalaryType']));
                                                        } ?>
                                    </li>
                        <?php } ?>
                            <!--<li><span>Job Views:</span><?php //echo $job_details['views']; ?></li>-->
                            <?php   if (!empty($job_details['Location_City']) || !empty($job_details['Location_State']) || !empty($job_details['Location_Country'])) { ?>
                                        <li><span>Job Location:</span>
                                        <?php   if(!empty($job_details['Location_City'])) {
                                                    echo $job_details['Location_City'].', ';
                                                }
                                                if(!empty($job_details['Location_State'])) {
                                                    echo $job_details['Location_State'].', ';
                                                }  echo $job_details['Location_Country']; ?>
                                        </li>
                            <?php   } ?>
                        </ul>
                    </div>
                    <?php
                    if (empty($job_details['pictures'])) {
                        $image = base_url('assets/' . $theme_name . '/images/new_logo.JPG');
                    } else {
                        $image = AWS_S3_BUCKET_URL . $job_details['pictures'];
                    }
                    ?>

                    <div class="btn-panel">
                        <!--<a href="javascript:void(0);" class="site-btn-v2 st_sharethis_large" >SHARE THIS AD</a>-->
                        <a href="javascript:void(0);" class="site-btn-v2 custom-theme" data-toggle="modal" data-target="#myModalFriend">TELL A FRIEND</a>
                        <?php //echo anchor("", "TELL A FRIEND", array('class' => 'site-btn-v2'));   ?> 
                        <?php echo anchor(base_url() . "print_ad/" . $job_details["sid"], "PRINT THIS AD", array('class' => 'site-btn-v2 custom-theme', 'target' => '_blank')); ?> 
                    </div>
                    <div class="social-media job-detail">
                        <?php if(isset($job_details['share_links'])){ ?>
                            <?php echo $job_details['share_links']; ?>
                        <?php } ?>
                    </div>
                    <?php if (!empty($job_details['YouTube_Video'])) { ?>
                        <div class="row">
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
                                <?php $this->load->view('common/video_player_partial');?>
                            </div>
                        </div>
                        <br />
                        <br />
                    <?php } ?>
                    <div class="job-description-text">
                        <h2>Job Description</h2>
                        <?php echo $job_details['JobDescription']; ?>
                    </div>
                    <?php if (!empty($job_details['JobRequirements'])) { ?>
                        <div class="job-description-text job-requirement">
                            <h2>Job Requirements:</h2>                                
                            <?php echo $job_details['JobRequirements']; ?>                              
                        </div>
                    <?php } ?>
                    <div class="btn-panel">
                        <a href="javascript:;" class="site-btn-lg custom-theme"  data-toggle="modal" data-target="#myModal">apply now</a>
                    </div>
                    <div class="btn-panel">
                        <?php echo anchor("/", "MORE CAREER Opportunities WITH THIS COMPANY", array('class' => 'site-btn-v2 lg-btn-v2 custom-theme-text')); ?> 
                    </div> 
                </div>					
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('common/apply_now_modal_for_job_details'); ?>
<?php $this->load->view('common/tell_a_friend_modal_for_job_details'); ?>
<script type="text/javascript" src="<?php echo base_url('assets/theme-3/js/all.js'); ?>"></script>
<!--<script src = "http://connect.facebook.net/en_US/all.js" ></script>-->