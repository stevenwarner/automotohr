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
                                <span>Goals</span>
                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <!--  -->
                    <div class="">
                        <div class="pa10">
                            <!-- Header -->
                            <div class="csPageBoxHeader p10 bbn">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <select id="jsVGStatus">
                                            <option value="-1">All</option>
                                            <option value="1">Ongoing Goals</option>
                                            <option value="0">Closed Goals</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <span class="csBTNBox">
                                            <button class="btn btn-orange btn-lg mt0 jsCreateGoal ">
                                                <i class="fa fa-plus-circle"></i> Create a Goal
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="csPageBox">
                                <div class="csPageBoxHeader pa10 pl10 pr10">
                                    <div class="row">
                                        <div class="col-sm-12 col-sm-12">
                                            <ul>
                                                <li><a href="javascript:void(0)" data-id="1" class="jsVGType active">My Goals</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="csPageBoxBody p10 jsGoalWrap"></div>
                                <!--  -->
                                <div class="csPageBoxFooter p10 dn">
                                    <div class="row">
                                        <div class="col-sm-6"><strong class="mt10">Showing 10 of 100</strong></div>
                                        <div class="col-sm-6">
                                            <ul class="pagination csPagination">
                                                <li class="page-item"><a href="javascript:void(0)">First</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">&laquo;</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">1</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">2</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">3</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">4</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">&raquo;</a></li>
                                                <li class="page-item"><a href="javascript:void(0)">Last</a></li>
                                            </ul>
                                        </div>
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