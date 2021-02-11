<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="btn-panel top-btn">
    <a href="javascript:void(0);" class="site-btn-lg" data-toggle="modal" data-target="#myModal">apply now</a>
</div>	
<?php $country_id = 227; ?>
<div class="main">
    <div class="container">
        <div class="main-content detail-job">
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
                            $image = base_url('assets/theme-1/images/new_logo.JPG');
                        } else {
                            $image = AWS_S3_BUCKET_URL . $job_details['pictures'];
                        }
                        ?>

                        <div class="btn-panel">
                            <!--<a href="javascript:void(0);" class="site-btn-v2 st_sharethis_large" >SHARE THIS AD</a>-->
                            <a href="javascript:void(0);" class="site-btn-v2" data-toggle="modal" data-target="#myModalFriend">TELL A FRIEND</a>
                            <?php //echo anchor("", "TELL A FRIEND", array('class' => 'site-btn-v2'));  ?> 
                            <?php echo anchor(base_url() . "print_ad/" . $job_details["sid"], "PRINT THIS AD", array('class' => 'site-btn-v2', 'target' => '_blank')); ?> 
                        </div>
                        <div class="social-media job-detail">
                            <?php if(isset($value['share_links'])){ ?>
                                <?php echo $value['share_links']; ?>
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
                            <a href="javascript:;" class="site-btn-lg" data-toggle="modal" data-target="#myModal">apply now</a>
                        </div>
                        <div class="btn-panel">
                            <?php echo anchor("/", "MORE CAREER Opportunities WITH THIS COMPANY", array('class' => 'site-btn-v2')); ?> 
                        </div> 
                    </div>					
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('common/apply_now_modal_for_job_details'); ?>
<?php $this->load->view('common/tell_a_friend_modal_for_job_details'); ?>
<script type="text/javascript" src="<?php echo base_url('assets/theme-2/js/all.js'); ?>"></script>
<!--<script src = "http://connect.facebook.net/en_US/all.js" ></script>-->