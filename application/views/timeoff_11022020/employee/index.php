<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">
<link href="<?php echo base_url("assets/employee_panel/css/owl.carousel.min.css"); ?>" rel="stylesheet">
<link href="<?php echo base_url("assets/css/timeoffstyle.css"); ?>" rel="stylesheet">
<link href="<?php echo base_url("assets/employee_panel/css/style-pto.css"); ?>" rel="stylesheet">
<link href="<?php echo base_url("assets/employee_panel/css/style-pto-overwrite.css"); ?>" rel="stylesheet">


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
                    <!--  -->
                    <div class="cs-main-page">

                        <div class="main pto-main-wrp">
                            <div class="containers">
                                <div class="row">
                                    <!-- Sidebar -->
                                    <?php $this->load->view('timeoff/lms/sidebar');?>
                                    <!-- Content Area -->
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                        <!-- Slider -->
                                        <div class="pto-policies owl-carousel owl-theme js-policy-slider"></div>
                                        <!-- Timeoff requests -->
                                        <div class="right-content">
                                            <div class="border-top-section border-bottom bg-white">
                                                <div class="row">
                                                    <div class="col-xs-6 col-lg-6">
                                                        <div class="pto-top-heading-left">
                                                            <p>Time Off Requests</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6 col-lg-6">
                                                        <div class="pto-top-heading-right text-right">
                                                            <a id="js-create-pto" href="javascript:void(0);" class="dashboard-link-btn2 js-add-btn js-create-pto">
                                                                <span><i class="fa fa-plus"></i></span>&nbsp; Create Request
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--Table Content--->
                                            <div class="modal-body full-width modal-body-custom">
                                                <div class="pto-tabs">
                                                    <ul class="nav nav-tabs btn-apply-filter-custom js-request-titles">
                                                        <li><button id="js-apply-filter-btn" type="button" class="btn btn-apply-filter">FILTER</button></li>
                                                    </ul>
                                                </div>
                                                <div class="pto-tabs">
                                                    <div class="filter-content" id="js-filter">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label class="">Date from</label>
                                                                        <div>
                                                                            <input type="text" readonly="true" class="form-control form-control-custom-height" id="js-filter-from-date" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Date to</label>
                                                                        <div>
                                                                            <input type="text" readonly="true" class="form-control form-control-custom-height" id="js-filter-to-date" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Status</label>
                                                                        <div>
                                                                            <select class="form-control form-control-custom-height" id="js-filter-status">
                                                                                <option value="all">All</option>
                                                                                <option value="approved">Approved</option>
                                                                                <option value="cancelled">Cancelled</option>
                                                                                <option value="pending">Pending</option>
                                                                                <option value="rejected">Rejected</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="btn-filter-reset-custom">
                                                                    <button id="js-filter-apply-btn" type="button" class="btn btn-apply-custom">APPLY</button>
                                                                    <button id="js-filter-reset-btn" type="button" class="btn btn-reset-custom">RESET</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-content">
                                                    <div id="vocation_policy" class="tab-pane fade in active">
                                                        <div class="active-content pto-tab-content current" data-tab="active-tab-content">
                                                            <div class="row pto-pagination pto-pagination-borders bg-white">
                                                                <!-- Pagination -->
                                                            </div>
                                                            <div class="row">
                                                                <div class="table-responsive custom-table-responsive-blue bg-white">
                                                                    <table class="table table-bordered pto-plan-table pto-plan-table-blue">
                                                                        <thead class="heading-grey js-data-head">
                                                                            <tr>
                                                                                <th scope="col">Upcoming Time Off</th>
                                                                                <th scope="col">Status</th>
                                                                                <th scope="col">Comment</th>
                                                                                <th scope="col">Progress</th>
                                                                                <th scope="col">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="js-data-load-area"></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row pto-pagination">
                                                                <!-- Pagination -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Add Page--->
                                        <div class="back-content">
                                            <div class="">
                                                <div class="row">
                                                    <div class="border-top-section border-bottom">
                                                        <div class="col-xs-6 col-lg-6">
                                                            <div class="pto-top-heading-left">
                                                                <p>Employee PTO Request</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6 col-lg-6">
                                                            <div class="pto-top-heading-right">
                                                                <button id="btn-pto-back" type="button" class="btn btn-back-custom">BACK</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-lg-4">
                                                            <div class="form-group fields-decoration-custom img-dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Select a Policy<span class="hr-select-dropdown"></span></a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0);">
                                                                        <div class="employee-info"><figure><img src="images/applican-img-uUicnV.jpg" class="img-circle emp-image" alt="emp-1"/></figure></div><h5>Faisal Awan</h5></a>
                                                                    </li>
                                                                    <li><a href="javascript:void(0);">
                                                                        <div class="employee-info"><figure><img src="images/download-1.jpg" class="img-circle emp-image" alt="emp-1"/></figure></div><h5>Arshad Khurram</h5></a>
                                                                    </li>
                                                                    <li><a href="javascript:void(0);">
                                                                        <div class="employee-info"><figure><img src="images/download-2.jpg" class="img-circle emp-image" alt="emp-1"/></figure></div><h5>Hassan Ali</h5></a>
                                                                    </li>
                                                                    <li><a href="javascript:void(0);">
                                                                        <div class="employee-info"><figure><img src="images/download-3.jpg" class="img-circle emp-image" alt="emp-1"/></figure></div><h5>Mubashir Ahmed</h5></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="pto-tabs">
                                                    <div class="row">
                                                        <div class="col-lg-12 line-position container-repeat">
                                                            <div class="line-div"></div>
                                                            <div class="partial-field-heading">
                                                                <div class="col-lg-7">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <div class="employee-add-heading">
                                                                                <h4>Date</h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="employee-add-heading">
                                                                                <h4>Request Type</h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="employee-add-heading">
                                                                                <h4>Hours</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5">
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <div class="employee-add-heading">
                                                                                <h4>Status</h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="employee-add-heading">
                                                                                <h4>Partial Leave</h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="btn-add-request">
                                                                                <button  data-repeater-create id="btn_request" type="button" class="btn btn-request repeater-add-btn">+</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div data-repeater-list="group-a">
                                                                <div class="section-repeat" data-repeater-item>
                                                                    <div class="clearfix partial-field-content">
                                                                        <div class="col-lg-7">
                                                                            <div class="row">
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group margin-top-fields">
                                                                                        <div class="pto-plan-date hr-select-dropdown">
                                                                                            <input type="text" readonly="" class="form-control form-control-custom-height" id="pto-plan-date-from" name="" value="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group margin-top-fields">
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select class="form-control form-control-custom-heigh" name="template" id="template">
                                                                                                <option value="">Select the policy</option>
                                                                                                <option value="742">Casual Leave</option>
                                                                                                <option value="750">Sick Leave</option>
                                                                                                <option value="742">Custom Leave</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group margin-bottom-custom">
                                                                                        <div class="form-group margin-top-fields">
                                                                                            <input type="text" class="form-control form-control-custom-height" id="usr">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-5">
                                                                            <div class="row">
                                                                                <div class="col-md-5">
                                                                                    <div class="hr-select-dropdown margin-top-fields">
                                                                                        <select class="form-control form-control-custom-heigh" name="template" id="template">
                                                                                            <option value="">All</option>
                                                                                            <option value="742">Active</option>
                                                                                            <option value="750">In-Active</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <div class="radio radio-custom-partial">
                                                                                        <label><input type="radio" name="optradio" value="no" checked>No</label>
                                                                                        <label><input type="radio" name="optradio" value="yes">Yes</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <a href="javascript:;" class="action-edit" data-repeater-delete><i class="fa fa-trash fa-color"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="partial-leave-options" style="display: none">
                                                                        <div class="col-lg-12">
                                                                            <div class="employee-add-heading">
                                                                                <p>I will be absent during these hours</p>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control form-control-custom-height" id="usr">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12">
                                                                            <div class="employee-add-heading">
                                                                                <p>PTO Note</p>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control form-control-custom-height" id="usr">
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
                                            <div class="col-lg-12">
                                                <div class="btn-filter-reset-custom">
                                                    <button id="btn_apply-custom" type="button" class="btn btn-apply-custom">SAVE</button>
                                                    <button id="btn_reset-custom" type="button" class="btn btn-reset-custom">CANCEL</button>
                                                </div>
                                            </div>
                                            <div class="employee-bottom-section">
                                                <div class="row">
                                                    <div class="employee-bottom-section-border full-width">
                                                        <div class="col-md-12">
                                                            <h4>Employee Section</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p>Instructions: To ensure that your information remains completely confidential, and to ensure that Family or Medical leave is properly designated, there are three forms available for you to choose from if you are requesting time off from work. </p>
                                                    </div>
                                                    <div class="col-md-12 pto-description">
                                                        <ul>
                                                            <li>
                                                                To request time off for Vacation, Personal Time, Jury day, or any other reason not related to your health, use the Non-Medical Time-Off Request.
                                                            </li>
                                                            <li>
                                                                To request time off for a health-related reason such as visits to a health-care provider, Workers Compensation, etc. use the Medical Time-Off Request form. The confidential medical information reported on
                                                                that form will be securely filed.
                                                            </li>
                                                            <li>
                                                                To request Family or Medical leave, first review the family & Medical Leave policy. Then use a Family & Medical Leave Request form. The confidential medical information reported on that form will be securely filed.
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $this->load->view('timeoff/employee/scripts/common'); ?>
                        <?php $this->load->view('timeoff/employee/scripts/create-modal'); ?>
                        <?php $this->load->view('timeoff/employee/scripts/index'); ?>

                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div class="text-center cs-loader js-ad-loader" style="display: block;">
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we process your request.</div>
    </div>
</div><!-- Loader -->


<style>
    
/*By M*/
/*loader*/
.cs-loader{ position: fixed; top: 0; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1; background: rgba(0,0,0,.5);}
.cs-loader-box{ position: absolute; top: 50%; bottom: 0; left: 0; right: 0; width: 300px; margin: auto; margin-top: -190px;}
.cs-loader-box i{ font-size: 14em; color: #81b431; }
.cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
.cs-calendar{ margin-top: 10px; }
/**/
.ajs-button{ background-color: #81b431 !important; color: #ffffff !important; padding-left: 5px !important; padding-right: 5px !important; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -o-border-radius: 4px; border-color: #4cae4c !important; }
.ajs-header{ background-color: #81b431 !important; color: #ffffff !important; }
/**/
</style>