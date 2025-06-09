<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$referrerChartArray = array();
$referrerChartArray[] = array('Referral', 'Count');
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-bar-chart"></i>Applicant Resume Analysis</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">

                                    <!-- Products Start -->
                                    <div class="hr-promotions table-responsive">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td width="20%"><b>Date Applied</b></td>
                                                    <td><?php echo date_with_time($applicant['created_at']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%"><b>Applicant Name</b></td>
                                                    <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Job Title</b></td>
                                                    <td><?= $applicant['Title'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Job Location</b>
                                                    <td>
                                                        <?php
                                                            $city = '';
                                                            $state = '';
                                                            //
                                                            if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                $city = ucfirst($applicant['Location_City']);
                                                            }
                                                            //
                                                            if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                            }
                                                            //
                                                            echo $city . $state;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Company Name</b>
                                                    <td> <?= $applicant["CompanyName"] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Applicant Source</b>
                                                    <td> <?= $applicant["applicant_source"] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Match Score Percentage</b>
                                                    <td> <?= $applicant["match_score"]."%" ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Products End -->
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicant Skills</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["skills"]) ? 'style="min-height: 200px;"' : '';?>>
                                            <?php if ($applicant["skills"]) { ?>
                                                <?php 
                                                    $skills = json_decode($applicant["skills"], true);
                                                ?>
                                                <?php foreach ($skills as $skill) { ?>                                               
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                        <div class="checkbox cs_full_width">
                                                            <?=$skill?>
                                                        </div>
                                                    </div>
                                                <?php } ?>   
                                            <?php } else { ?> 
                                                <div class="no-data">No skill found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicant Education</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["education"]) ? 'style="min-height: 200px;"' : '';?> >
                                            
                                            <?php if ($applicant["education"]) { ?>
                                                <?php 
                                                    $education = json_decode($applicant["education"], true);
                                                ?>
                                                <table class="incident-table">
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-center">Institution</th>
                                                            <th class="text-center">Degree</th>
                                                            <th class="text-center">Year</th>
                                                        </tr>
                                                        <?php foreach ($education as $edu) { ?>                                               
                                                            <tr>
                                                                <td class="text-center"><?php echo $edu['institution']; ?></td>
                                                                <td class="text-center"><?php echo $edu['degree']; ?></td>
                                                                <td class="text-center"><?php echo $edu['year']; ?></td>
                                                            </tr>
                                                        <?php } ?> 
                                                    </tbody>
                                                </table>  
                                            <?php } else { ?> 
                                                <div class="no-data">No education found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicant Work Experience</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["work_experience"]) ? 'style="min-height: 200px;"' : '';?> >
                                            
                                            <?php if ($applicant["work_experience"]) { ?>
                                                <?php 
                                                    $work_experience = json_decode($applicant["work_experience"], true);
                                                ?>
                                                <table class="incident-table">
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-center">Company</th>
                                                            <th class="text-center">Job Title</th>
                                                            <th class="text-center">Duration</th>
                                                            <th class="text-center">Summary</th>
                                                        </tr>
                                                        <?php foreach ($work_experience as $work) { ?>                                               
                                                            <tr>
                                                                <td class="text-center"><?php echo $work['company']; ?></td>
                                                                <td class="text-center"><?php echo $work['title']; ?></td>
                                                                <td class="text-center"><?php echo $work['duration']; ?></td>
                                                                <td class="text-center"><?php echo $work['summary']; ?></td>
                                                            </tr>
                                                        <?php } ?> 
                                                    </tbody>
                                                </table>  
                                            <?php } else { ?> 
                                                <div class="no-data">No work experience found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicant Certifications</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["certifications"]) ? 'style="min-height: 200px;"' : '';?>>
                                            <?php if ($applicant["certifications"]) { ?>
                                                <?php 
                                                    $certifications = json_decode($applicant["certifications"], true);
                                                ?>
                                                <?php foreach ($certifications as $certificate) { ?>                                               
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs_adjust_margin">
                                                        <div class="checkbox cs_full_width">
                                                            <?=$certificate?>
                                                        </div>
                                                    </div>
                                                <?php } ?>   
                                            <?php } else { ?> 
                                                <div class="no-data">No certification found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicant Extra Content</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["certifications"]) ? 'style="min-height: 200px;"' : '';?>>
                                            <?php if ($applicant["extra_content"]) { ?>
                                                <?php 
                                                    echo $applicant["extra_content"];
                                                ?>  
                                            <?php } else { ?> 
                                                <div class="no-data">No extra content found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                               
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Screening Questions</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding text-center activity_container" <?php empty($applicant["screening_questions"]) ? 'style="min-height: 200px;"' : '';?>>
                                            <?php if ($applicant["screening_questions"]) { ?>
                                                <?php 
                                                    $questions = json_decode($applicant["screening_questions"], true);
                                                ?>
                                                <?php foreach ($questions as $key => $question) { ?>                                               
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs_adjust_margin">
                                                        <div class="checkbox cs_full_width">
                                                            <?=$key+1?> ) <?=$question?>
                                                        </div>
                                                    </div>
                                                <?php } ?>   
                                            <?php } else { ?> 
                                                <div class="no-data">No screening questions found</div> 
                                            <?php }  ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>