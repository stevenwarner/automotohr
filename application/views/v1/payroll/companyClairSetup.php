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

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <h1 class="text-medium">
                                Clair Status: <strong class="text-<?=$isClairActive ? "success" : "danger";?>"><?= $isClairActive ? "ACTIVE" : "DISABLED";?></strong>
                            </h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="text-large">
                                        <strong>
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            Set up Clair for Company
                                        </strong>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <iframe src="<?= $flow; ?>" style="border: 0; width: 100%; height: 800px;" title="Clair Integration"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>