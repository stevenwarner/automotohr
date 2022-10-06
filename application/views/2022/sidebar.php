
<div class="emp-info-strip">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="emp-info-box">
                    <div class="figure">
                        <?php if (isset($employee['profile_picture']) && !empty($employee['profile_picture'])) { ?>
                            <div class="container-fig">
                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $employee['profile_picture']; ?>" alt="" />
                            </div>
                        <?php } else { ?>
                            <span><?php echo substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1); ?></span>
                        <?php } ?>
                    </div>
                    <div class="text text-white">
                        <h3>
                            <?php echo remakeEmployeeName($employee, true, true); ?>
                            <span><?php echo $employee['access_level']; ?></span>
                        </h3>
                        <ul class="contact-info">
                            <?php if (!empty($employee['PhoneNumber'])) { ?>
                                <li><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $employee['PhoneNumber']; ?></li>
                            <?php } ?>
                            <?php if (!empty($employee['email'])) { ?>
                                <li><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $employee['email']; ?></li>
                            <?php } ?>
                            <li><?php echo $session['company_detail']['CompanyName']; ?></li>
                        </ul>
                    </div>
                    <?php $this->load->view('attendance/2022/clock_header_blue'); ?>
                    <?php if ($employee['is_executive_admin'] == 0) { ?>
                        <br>
                        <br>
                        <div class="btn-link-wrp">
                            <a href="<?php echo base_url('my_profile'); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> my profile</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>