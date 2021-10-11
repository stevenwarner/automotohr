<?php

$acDate = $job_details['activation_date'];
if(!preg_match('/[0-9]/',$acDate)) $acDate = date('m-d-Y');

if(preg_replace('/[^0-9]/', '', $job_details['activation_date']) == '' && $job_details['approval_status_change_datetime'] != ''){
    $acDate = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $job_details['approval_status_change_datetime']
    )->format('m-d-Y');
}
//
$job_details['Title'] = job_title_uri($job_details, true);

$googleJobOBJ = [];
// Basic job details
$googleJobOBJ['@context'] = 'http://schema.org';
$googleJobOBJ['@type'] = 'JobPosting';
$googleJobOBJ['title'] = $job_details['Title'];
$googleJobOBJ['description'] = ($job_details['JobDescription'].' '.$job_details['JobRequirements']);
$googleJobOBJ['employmentType'] = strtoupper(str_replace(' ', '_', $job_details['JobType'])); // FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER [FULL_TIME,PART_TIME]
$googleJobOBJ['industry'] = 'business';
$googleJobOBJ['datePosted'] = DateTime::createFromFormat('m-d-Y', $acDate)->format('Y-m-d\TH:i:s\Z');
$googleJobOBJ['validThrough'] = DateTime::createFromFormat('m-d-Y', $acDate)->add(new DateInterval('P30D'))->format('Y-m-d'); // Add interval of one month
$googleJobOBJ['url'] = 'https://www.automotosocial.com/display-job/'.(preg_replace('/\s+/', '-',preg_replace('/[^0-9a-zA-Z]/', ' ', strtolower($job_details['Title'])))).'-'.$job_details['sid']; // Add interval of one month
// Organization details
$googleJobOBJ['hiringOrganization'] = [];
$googleJobOBJ['hiringOrganization']['@type'] = 'Organization';
$googleJobOBJ['hiringOrganization']['name'] = $company_details['CompanyName'];
$googleJobOBJ['hiringOrganization']['sameAs'] = 'https://'.$company_details['sub_domain'];
$googleJobOBJ['hiringOrganization']['logo'] = AWS_S3_BUCKET_URL.$company_details['Logo'];
// Job location details
$googleJobOBJ['jobLocation']['@type'] = 'Place';
$googleJobOBJ['jobLocation']['address'] = [];
$googleJobOBJ['jobLocation']['address']['@type'] = 'PostalAddress';
$googleJobOBJ['jobLocation']['address']['streetAddress'] = $job_details['Location'] != '' ? $job_details['Location'] : $job_details['Location_City'];
$googleJobOBJ['jobLocation']['address']['postalCode'] = !empty($job_details['Location_ZipCode']) ? $job_details['Location_ZipCode'] : '';
$googleJobOBJ['jobLocation']['address']['addressCountry'] = preg_match('/canada/', strtolower($job_details['Location_Country'])) ? "CA" : "US";
$googleJobOBJ['jobLocation']['address']['addressRegion'] = !empty($job_details['Location_State']) ? $job_details['Location_State'] : '';
$googleJobOBJ['jobLocation']['address']['addressLocality'] = !empty($job_details['Location_City']) ? $job_details['Location_City'] : '';
// Applicant location details
// Salary

$googleJobOBJ['baseSalary'] = [];
$googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
$googleJobOBJ['baseSalary']['currency'] = 'USD';
$googleJobOBJ['baseSalary']['value'] = [];
$googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';
$googleJobOBJ['baseSalary']['value']['unitText'] = 'HOUR';
$googleJobOBJ['baseSalary']['value']['value'] = '20';

if(!empty($job_details['Salary'])){
    //
    $salary = preg_replace('/\s+/', ' ', str_replace('-',' ',trim($job_details['Salary'])));
    //
    $salary = preg_replace('/((\d\.?)\s)(?=\d[^>]*(<|$))/', '$2$3', $salary);
    //
    $salary = trim(preg_replace('/[^0-9\s]/', '', $salary));
    //
    if(!empty($salary)){
        //
        $salaryArray = explode(' ', $salary);
        //
        $salaryType = 'MONTH';
        //
        switch ($job_details['SalaryType']) {
            case 'per_hour': $salaryType = 'HOUR'; break;
            case 'per_week': $salaryType = 'WEEK'; break;
            case 'per_year': $salaryType = 'YEAR'; break;
        }
        $googleJobOBJ['baseSalary'] = [];
        $googleJobOBJ['baseSalary']['@type'] = 'MonetaryAmount';
        $googleJobOBJ['baseSalary']['currency'] = 'USD';
        $googleJobOBJ['baseSalary']['value'] = [];
        $googleJobOBJ['baseSalary']['value']['@type'] = 'QuantitativeValue';
        //
        $googleJobOBJ['baseSalary']['value']['unitText'] = $salaryType;
        //
        if(count($salaryArray) == 1){
            $googleJobOBJ['baseSalary']['value']['value'] = number_format($salaryArray[0], 2, '.', '');
        } else {
            $googleJobOBJ['baseSalary']['value']['minValue'] = number_format($salaryArray[0], 2, '.', '');
            $googleJobOBJ['baseSalary']['value']['maxValue'] = number_format($salaryArray[1], 2, '.', '');
        }
    }
}

// Company identifier
$googleJobOBJ['identifier'] = [];
$googleJobOBJ['identifier']['@type'] = 'PropertyValue';
$googleJobOBJ['identifier']['name'] = 'jid';
$googleJobOBJ['identifier']['value'] = $job_details['user_sid'];
$googleJobOBJ['directApply'] = true;
//
echo '<script type="application/ld+json">';
echo json_encode($googleJobOBJ, JSON_PRETTY_PRINT);
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