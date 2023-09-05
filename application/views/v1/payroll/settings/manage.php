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
                                    Settings
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Payment Configs</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <p class="csF16 text-danger">
                                <strong>
                                    <em>
                                        Fields marked with "*" are mandatory.
                                    </em>
                                </strong>
                            </p>
                            <br />
                            <form action="">
                                <div class="form-group">
                                    <label>Payment Speed <strong class="text-danger">*</strong></label>
                                    <select id="jsPayrollSettingPaymentSpeed" class="form-control">
                                        <option value="1-day" <?= $settings['payment_speed'] === '1-day' ? 'selected' : ''; ?>>1-day</option>
                                        <option value="2-day" <?= $settings['payment_speed'] === '2-day' ? 'selected' : ''; ?>>2-day</option>
                                        <option value="4-day" <?= $settings['payment_speed'] === '4-day' ? 'selected' : ''; ?>>4-day</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="panel-footer text-right">
                            <button class="btn csW csBG3 csF16 jsPayrollSettingUpdateBtn">
                                <i class="fa fa-edit csF16"></i>
                                &nbsp;Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>