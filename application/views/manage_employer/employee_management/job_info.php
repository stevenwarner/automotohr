<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                    <div class="form-title-section"><br>
                        <h2>Job Information</h2>
                        <div class="text-right">
                            <a href="<?php echo base_url('job_info_add') . '/employee/' . $employer["sid"]; ?>" class="btn btn-success ">Add New Job</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ems-documents js-search-header">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#primary_job_title" aria-expanded="true">
                                            <span class="glyphicon glyphicon-minus"></span>
                                            Primary
                                        </a>
                                    </h4>
                                </div>
                                <div id="primary_job_title" class="panel-collapse collapse in" aria-expanded="true">

                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">

                                                <h4 style="margin-top: 0">
                                                </h4>
                                                <p class="csF16">
                                                    <strong>Job Title: Manager</strong>
                                                </p>

                                                <p class="csF16">
                                                    <strong>Rate: $20 (Per hour)</strong>
                                                </p>

                                                <p class="csF16">
                                                    <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;
                                                    Overtime Allowed?
                                                </p>

                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">

                                                <p class="csF16">
                                                    <strong>Overtime: 9:00AM - 6:00PM</strong>
                                                </p>
                                                <p class="csF16">
                                                    <strong>Over Time: $25</strong>
                                                </p>

                                                <p class="csF16">
                                                    <strong>Double Overtime: 9:00AM - 6:00PM</strong>
                                                </p>
                                                <p class="csF16">
                                                    <strong>Over Time: $50</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ems-documents js-search-header">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#active_job_title" aria-expanded="false">
                                            <span class="glyphicon glyphicon-minus"></span>
                                            Active
                                            <div class="pull-right total-records"><b>Total: 5</b></div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="active_job_title" class="panel-collapse collapse " aria-expanded="false">
                                    <div class="table-responsive">

                                        sdfsdf

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default ems-documents js-search-header">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#inactive_job_title" aria-expanded="false">
                                            <span class="glyphicon glyphicon-minus"></span>
                                            In-Active
                                            <div class="pull-right total-records"><b>Total: 5</b></div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="inactive_job_title" class="panel-collapse collapse " aria-expanded="false">
                                    <div class="table-responsive">
                                        sdfsdfs
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
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js">
</script>
<!--file opener modal starts-->