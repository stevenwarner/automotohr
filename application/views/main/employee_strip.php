<?php $loggedInPersonDetails = $this->session->userdata("logged_in")["employer_detail"]; ?>

<!-- Header End -->


<div class="emp-info-strip  ">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-sm-2">
                <div class="emp-info-box">
                    <div class="figure">
                        <div class="container-fig">

                            <?php if (isset($loggedInPersonDetails['profile_picture']) && !empty($loggedInPersonDetails['profile_picture'])) { ?>
                                <div class="contasiner-fig">
                                    <img class="img-responsive  cs-radius" src="<?= getImageURL($loggedInPersonDetails['profile_picture']);
                                                                                ?>" alt="profile picture">

                                </div>
                            <?php } else { ?>
                                <span><?php echo substr($loggedInPersonDetails['first_name'], 0, 1) . substr($loggedInPersonDetails['last_name'], 0, 1); ?></span>
                            <?php } ?>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-6">
                <div class="text text-white">
                    <h3>
                        <?php echo $loggedInPersonDetails['first_name'] . ' ' . $loggedInPersonDetails['last_name']; ?>
                        <span class="label label-danger"><?php echo $loggedInPersonDetails['access_level']; ?></span>
                    </h3>
                    <span>
                        <?= get_user_anniversary_date(
                            $loggedInPersonDetails['joined_at'],
                            $loggedInPersonDetails['registration_date'],
                            $loggedInPersonDetails['rehire_date']
                        );
                        ?>
                    </span>
                    <ul class="contact-info">
                        <?php if (!empty($loggedInPersonDetails['PhoneNumber'])) { ?>
                            <li><i class="fa fa-phone"></i> <?php echo $loggedInPersonDetails['PhoneNumber']; ?></li>
                        <?php } ?>
                        <?php if (!empty($loggedInPersonDetails['email'])) { ?>
                            <li><i class="fa fa-envelope"></i> <?php echo $loggedInPersonDetails['email']; ?></li>
                        <?php } ?>
                        <li><?php echo $session['company_detail']['CompanyName']; ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <?php $this->load->view('attendance/2022/clock_header_blue'); ?>
            </div>

        </div>

        <?php if ($loggedInPersonDetails['is_executive_admin'] == 0) { ?>
            <div class="row">
                <div class="col-sm-12 text-right">

                    <?php if ($loggedInPersonDetails['access_level'] != 'Employee') { ?>
                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-orange btn-orange-hover" style="-webkit-border-radius: 5px !important;"> Management Dashboard </a>
                    <?php } ?>
                    <?php if ($this->uri->segment(1) == 'employee_management_system' || $this->uri->segment(1) == 'dashboard') { ?>
                        <a href="<?php echo base_url('my_profile'); ?>" class="btn btn-orange btn-orange-hover" style="-webkit-border-radius: 5px !important;"><i class="fa fa-pencil"></i> My profile</a>
                    <?php } else { ?>
                        <a href="<?php echo base_url('employee_management_system'); ?>" class="btn btn-orange btn-orange-hover" style="-webkit-border-radius: 5px !important;">EMS Dashboard</a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>