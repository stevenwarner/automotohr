<div class="application-header">
    <article>
        <div class="row">
            <div class="col-sm-8">
                <figure><img src="<?php echo AWS_S3_BUCKET_URL; 
                    if (isset($employer['profile_picture']) && $employer['profile_picture'] != "") {
                        echo $employer['profile_picture'];
                    } elseif(isset($applicant_info['pictures']) && $applicant_info['pictures'] != "") {
                        echo $applicant_info['pictures'];
                    } else {
                        ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture"></figure>
                <div class="text">
                    <h2><?= $employer["first_name"] ?> <?= $employer["last_name"] ?> <br><span><?=remakeEmployeeName( $employer, false );?></span></h2>


                    <div class="start-rating">
                        <?php if (isset($employer['user_type']) && $employer['user_type'] == 'Applicant') { ?>
                            <input readonly="readonly" id="input-21b" value="<?php echo isset($applicant_average_rating) ? $applicant_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2  data-size="xs" />
                        <?php } else { ?>
                            <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus'] && !$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']) { ?>
                                <a class="btn-employee-status btn-warning" href="<?php echo base_url('employee_status/' . $employer['sid']); ?>">Employee Status</a>
                            <?php } ?>   
                        <?php } ?>
                    </div>
                    <?php if(isset($employee_terminate_status) && !empty($employee_terminate_status)){
                        echo '<h4>'.$employee_terminate_status.'</h4>';
                    } else if (isset($employer['active'])) { ?>
                        <h4>
                            <?php if ($employer['active']) { ?>
                                Active Employee
                            <?php } else { ?>
                                <?php if($employer['archived']!='1') { ?>
                                    Onboarding or Deactivated Employee
                                <?php } else { ?>
                                    Archived Employee
                                <?php } ?>
                            <?php } ?>
                        </h4>
                    <?php } else { ?>
                        <span> <?php echo 'Applicant'; ?></span>
                    <?php } ?>
                </div>
            </div>
            <?php 
                if (isset($employer['active']) && checkForNewModuleAccess($session['company_detail']['sid'], 'timeoff') && ($this->session->userdata('logged_in')['employer_detail']['pay_plan_flag'] == 1 || $this->session->userdata('logged_in')['employer_detail']['access_level_plus'] == 1)){
                    $this->load->view('timeoff/employee/header-box');  
                }
            ?>
        </div>
        <!--  -->
    </article>
</div>
