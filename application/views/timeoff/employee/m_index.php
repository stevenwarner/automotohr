<style>
.csSelect2ErrorLi,
.csSelect2ErrorLi:hover,
.csSelect2ErrorLi[aria-selected="true"] {
    background-color: #f2dede !important;
    color: #000 !important;
}

.bg-danger {
    background-color: #f2dede !important;
}
</style>
<?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) $this->load->view('timeoff/popups/policies'); ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div>
                        <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');?>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <div>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a href="<?=base_url('employee_profile/'.( $sid ).'');?>" class="dashboard-link-btn">
                                    <i class="fa fa-chevron-left"></i>Employee Profile
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!--  -->
                    <div class="cs-main-page">
                        <div class="main pto-main-wrp">
                            <div class="row">
                                <!-- Content Area -->
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <a href="#" class="btn btn-success jsMobileBTN jsHolidays"> <i class="fa fa-vacation"></i>
                                            &nbsp;View Holidays</a>
                                        <a href="#" class="btn btn-success jsMobileBTN jsViewPolicies"> <i class="fa fa-file"></i>
                                            &nbsp;View Policies</a>
                                        <a href="#" class="btn btn-success jsMobileBTN jsCalendarView" data-id="1"> <i
                                                class="fa fa-calendar"></i> &nbsp;View Calendar</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="csPIPage">
                                        <!-- Loader -->
                                        <div class="csIPLoader jsIPLoader" data-page="graph"><i
                                                class="fa fa-circle-o-notch fa-spin"></i>
                                        </div>
                                        <?php $this->load->view('timeoff/partials/employee/m_off_today'); ?>
                                        <?php $this->load->view('timeoff/partials/employee/m_policies'); ?>
                                        <!-- View  -->
                                        <?php $this->load->view('timeoff/partials/employee/m_graph'); ?>

                                        <!-- Employee on off for mobile -->
                                        <div id="js-employee-off-box-mobile"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="csPIPage">
                                        <!-- Loader -->
                                        <div class="csIPLoader jsIPLoader" data-page="requests"><i
                                                class="fa fa-circle-o-notch fa-spin"></i>
                                        </div>

                                        <!-- View  -->
                                        <?php $this->load->view('timeoff/partials/employee/m_view'); ?>

                                        <!-- Employee on off for mobile -->
                                        <div id="js-employee-off-box-mobile"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>