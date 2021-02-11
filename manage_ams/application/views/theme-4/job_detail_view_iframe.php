<?php

$googleJobOBJ = [];
// Basic job details
$googleJobOBJ['@type'] = 'JobPosting';
$googleJobOBJ['title'] = $job_details['Title'];
$googleJobOBJ['employmentType'] = strtoupper(str_replace(' ', '_', $job_details['JobType'])); // FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER [FULL_TIME,PART_TIME]
$googleJobOBJ['datePosted'] = DateTime::createFromFormat('m-d-Y', $job_details['activation_date'])->format('Y-m-d');
$googleJobOBJ['validThrough'] = DateTime::createFromFormat('m-d-Y', $job_details['activation_date'])->add(new DateInterval('P15D'))->format('Y-m-dT00:00'); // Add interval of one month
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
$googleJobOBJ['jobLocation']['address']['postalCode'] = $job_details['Location_ZipCode'];
$googleJobOBJ['jobLocation']['address']['addressCountry'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CA" : "US";
// Applicant location details
$googleJobOBJ['applicantLocationRequirements']['@type'] = 'Country';
$googleJobOBJ['applicantLocationRequirements']['name'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CAN" : "USA";
$googleJobOBJ['jobLocationType']['@type'] = 'JobPosting';
$googleJobOBJ['jobLocationType']['name'] = 'TELECOMMUTE';
// Salary
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

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
        </div>
    </div>
</div>

<div class="main">
<?php   if((!empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'default') || empty($jobs_detail_page_banner_data)) { ?>
            <div class="bottom-btn-row top-aplly-btn">
                <ul><li><a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a></li></ul>
            </div>
<?php   } ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="job-detail">
                <?php   if (!empty($job_details['YouTube_Video'])) {
                            $this->load->view('common/video_player_partial');
                        } ?>

                    <div class="job-description-text">
                        <header class="heading-title">
                            <span class="section-title color">Job Description:</span>
                        </header>
                        <?php echo $job_details['JobDescription']; ?>
                    </div>
                    <?php   if (!empty($job_details['JobRequirements'])) { ?>
                                <div class="job-description-text job-requirement">
                                    <header class="heading-title">
                                        <span class="section-title color">Job Requirements:</span>
                                    </header>
                                    <?php echo $job_details['JobRequirements']; ?>
                                </div>
                    <?php   }

                            if (empty($value['pictures']) && !empty($company_details['Logo'])) {
                                $image = AWS_S3_BUCKET_URL . $company_details['Logo'];
                            } elseif(!empty($value['pictures'])){
                                $image = AWS_S3_BUCKET_URL . $value['pictures'];
                            } else {
                                $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                            } ?>
                    <div class="social-media job-detail">
                        <?php   if(isset($job_details['share_links'])){
                                    echo $job_details['share_links'];
                                } ?>
                    </div>
                    <div class="bottom-btn-row">
                        <ul>
                            <li><a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a></li>
                            <li><a href="<?php echo $more_career_oppurtunatity; ?>" class="site-btn bg-color-v3">More Career Opportunities With This Company</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media only screen and (max-width: 600px){
        .job-description-text{
            width: max-width: 100%;
            overflow-x: hidden;
        }
        .job-description-text img{ max-width: 100%; }
        .job-detail li,
        .job-detail p{ font-size: 18px !important; }
        .job-detail .section-title,
        .apply-now-large{ font-size: 25px !important; }

        .job-info li{ font-size: 18px !important; }
        .job-info li span{ font-size: 16px !important; }

    }
</style>
