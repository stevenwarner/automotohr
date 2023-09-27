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
                                <strong>Company benefits</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button class="btn csW csBG3 csF16 jsAddBenefit">
                                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                        &nbsp;Add benefit
                                    </button>
                                </div>
                            </div>
                            <br>
                            <!--  -->
                            <div id="jsBenefitArea"></div>
                        </div>
                    </div>

                    <!--  -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>