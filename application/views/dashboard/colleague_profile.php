<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}

?>
<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_employee'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <?php $extra_info = isset($employer['extra_info']) && !empty($employer['extra_info']) ? unserialize($employer['extra_info']) : array(); ?>

                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <?php if(!empty($extra_info['title'])) { ?>
                                                                <h2 style="margin: 0px 0px;"> <small><?php echo $extra_info['title']; ?></small></h2>
                                                            <?php } ?>
                                                            <h2 style="margin-top: 0; white-space: nowrap;"><?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>&nbsp;<small>( <?php echo ucwords($employer['access_level']);?> )</small></h2>
                                                            <?php if(!empty($employer['job_title'])) { ?>
                                                                <h2 style="margin-top: -15px;"> <small><?php echo $employer['job_title']; ?></small></h2>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($extra_info['division'])) { ?>
                                                        <tr>
                                                            <td class="col-xs-2">Division:</td><td class="col-xs-10"><?php echo ucwords($extra_info['division']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if(!empty($extra_info['department'])) { ?>
                                                        <tr>
                                                            <td class="col-xs-2">Department:</td><td class="col-xs-10"><?php echo ucwords($extra_info['department']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if(!empty($extra_info['office_location'])) { ?>
                                                        <tr>
                                                            <td class="col-xs-2">Office Location:</td><td class="col-xs-10"><?php echo ucwords($extra_info['office_location']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <?php if(isset($extra_info['interests']) && !empty($extra_info['interests'])) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h3>Interests</h3>
                                                    <p class="text-justify"><?php echo $extra_info['interests']; ?></p>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if(isset($extra_info['short_bio']) && !empty($extra_info['short_bio'])) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h3>About Me</h3>
                                                    <p class="text-justify"><?php echo $extra_info['short_bio']; ?></p>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php if(isset($employer['profile_picture']) && !empty($employer['profile_picture'])) { ?>
                                                    <img class="img-responsive img-thumbnail" style="width: 100%;" src="<?php echo AWS_S3_BUCKET_URL . $employer['profile_picture']; ?>">
                                                <?php } else { ?>
                                                    <span><?php echo substr($employer['first_name'], 0, 1) . substr($employer['last_name'], 0, 1); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2"><h2 style="margin: 0px 0px;"> <small>Contact Details</small></h2></td>
                                                    </tr>
                                                    <?php if(!empty($employer['email'])) { ?>
                                                        <tr>
                                                            <td style="width: 5%;"><i class="fa fa-envelope" style="font-size: 20px;"></i></td><td><a href="mailto:<?php echo strtolower($employer['email']); ?>"><?php echo strtolower($employer['email']); ?></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if(!empty($employer['cell_number'])) { ?>
                                                        <tr>
                                                            <td style="width: 5%;"><i class="fa fa-mobile-phone" style="font-size: 20px;"></i></td><td><a href="tel:<?php echo $employer['cell_number']; ?>"><?php echo $employer['cell_number']; ?></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if(!empty($employer['PhoneNumber'])) { ?>
                                                        <tr>
                                                            <td style="width: 5%;"><i class="fa fa-phone" style="font-size: 20px;"></i></td><td><a href="tel:<?php echo $employer['PhoneNumber']; ?>"><?php echo $employer['PhoneNumber']; ?></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if(!empty($employer['linkedin_profile_url'])) { ?>
                                                        <tr>
                                                            <td style="width: 5%;"><i class="fa fa-linkedin-square" style="font-size: 20px;"></i></td><td><a target="_blank" href="<?php echo strtolower($employer['linkedin_profile_url']); ?>">LinkedIn Profile</a></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <?php if(!empty($employer['YouTubeVideo'])) { ?>
                                    <hr />
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $employer['YouTubeVideo']; ?>" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }  else if ($load_view == 'new') { ?>
    <?php $this->load->view('onboarding/colleague_profile'); ?>
<?php } ?>