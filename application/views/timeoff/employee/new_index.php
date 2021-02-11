<?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) $this->load->view('timeoff/popups/policies'); ?>


<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div>
                        <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');?>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <div>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <a href="<?=base_url('employee_profile/'.( $sid ).'');?>" class="dashboard-link-btn">
                                    <i class="fa fa-chevron-left"></i>Employee Profile
                                </a>
                                <span>Time Off</span>

                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="csPageMain">
                        <div class="container-fluid">
                            <div class="csPageWrap csRadius5 csShadow">
                                <div class="csPageHeader">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <span class="pull-right">
                                                <a href="#" class="btn btn-orange jsHolidays"> <i
                                                        class="fa fa-vacation"></i> &nbsp;View Holidays</a>
                                                <a href="#" class="btn btn-orange jsViewPolicies"> <i
                                                        class="fa fa-file"></i> &nbsp;View Policies</a>
                                                <a href="#" class="btn btn-orange jsCalendarView" data-id="1"> <i
                                                        class="fa fa-calendar"></i> &nbsp;View Calendar</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="csPageBody">
                                    <!-- Loader -->
                                    <div class="csIPLoader jsIPLoader" data-page="graph"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                    <!-- Policies  -->
                                    <!-- Policy Box -->
                                    <div class="csPolicyBox"></div>
                                    <!-- View  -->
                                    <?php $this->load->view('timeoff/partials/employee/graph'); ?>
                                </div>
                                <div class="csPageBody">
                                    <!-- Loader -->
                                    <div class="csIPLoader jsIPLoader" data-page="requests"><i
                                            class="fa fa-circle-o-notch fa-spin"></i></div>
                                    <!-- View  -->
                                    <?php $this->load->view('timeoff/partials/employee/new_view'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>