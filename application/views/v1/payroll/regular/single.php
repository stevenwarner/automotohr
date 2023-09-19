<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('v1/payroll/sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Regular Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!-- Steps -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="progress mb0">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="csF16 text-center" style="margin-top: 5px;">Hours and earnings</p>
                                </div>

                                <div class="col-sm-3">
                                    <div class="progress mb0">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $step2; ?>%">
                                        </div>
                                    </div>
                                    <p class="csF16 text-center" style="margin-top: 5px;">Time offs</p>
                                </div>

                                <div class="col-sm-3">
                                    <div class="progress mb0">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $step3; ?>%">
                                        </div>
                                    </div>
                                    <p class="csF16 text-center" style="margin-top: 5px;">Review and submit</p>
                                </div>

                                <div class="col-sm-3">
                                    <div class="progress mb0">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $step4; ?>%">
                                        </div>
                                    </div>
                                    <p class="csF16 text-center" style="margin-top: 5px;">Confirmation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>