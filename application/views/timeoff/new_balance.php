<?php $this->load->view('timeoff/includes/header'); ?>
<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap csRadius5 csShadow">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="balance"><i class="fa fa-circle-o-notch fa-spin"></i></div>
            <!-- Page Header -->
            <div class="csPageHeader <?php echo $this->agent->is_mobile() ? 'csMobileWrap' : ''; ?>">
                <div class="row">
                    <div class="col-sm-6">
                        <h4><strong>Balances (As Of Today)</strong></h4>
                    </div>
                    <div class="col-sm-6">
                        <span class="pull-right">
                            <a href="<?= base_url('timeoff/print/balance/0'); ?>" target="_blank" class="btn btn-orange"><i class="fa fa-print"></i> Print</a>
                            <a href="<?= base_url('timeoff/download/balance/0'); ?>" target="_blank" class="btn btn-orange"><i class="fa fa-download"></i> Download</a>
                            <a href="<?= base_url('timeoff/export/balance/0'); ?>" target="_blank" class="btn btn-orange"><i class="fa fa-file"></i> Export</a>
                            <button class="btn btn-orange manage_my_team"><i class="fa fa-users"></i> Manage Teams</button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Page Nav -->
            <div class="csPageNav">
                <!-- Filter -->
                <div class="row">
                    <div class="col-sm-12">
                        <span class="pull-right">
                            <button class="btn btn-success btn-theme jsFilterBtn" data-target="jsFilterBoxBalance"><i class="fa fa-filter"></i> Filter</button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="csPageBody">

                <!-- Filter -->
                <div class="csBalanceBox csShadow csRadius5 jsFilterBoxBalance dn">
                    <div class="col-sm-6">
                        <label>Select employee</label>
                        <select id="js-filter-employee"></select>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Employee Status</label>
                            <div>
                                <?= showEmployeeStatusSelect([
                                    $this->input->get("employee_status", true) ?? 0
                                ], 'class="jsFilterEmployeeStatus" name="employee_status"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <button id="btn_apply" type="button" class="btn btn-success btn-theme js-apply-filter-btn">APPLY</button>
                        <button id="btn_reset" type="button" class="btn btn-black btn-theme js-reset-filter-btn">RESET</button>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- Main -->
                <div class="csBalanceBox csShadow csRadius5">
                    <!--  -->
                    <div class="csBalanceBoxInner"></div>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('timeoff/popups/policies'); ?>
<?php $this->load->view('timeoff/popups/balance'); ?>
<?php $this->load->view('timeoff/partials/popups/team_management'); ?>