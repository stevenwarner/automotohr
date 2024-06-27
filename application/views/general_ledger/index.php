<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="applicant-reg-date">
                                <form method="get" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label class="">Start Date</label>
                                                <?php $start_date = $this->uri->segment(2) != 'all' && $this->uri->segment(2) != '' ? urldecode($this->uri->segment(2)) : date('m-d-Y'); ?>
                                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="jsStartDate" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label class="">End Date</label>
                                                <?php $end_date = $this->uri->segment(3) != 'all' && $this->uri->segment(3) != '' ? urldecode($this->uri->segment(3)) : date('m-d-Y'); ?>
                                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="jsEndDate" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="field-row autoheight text-right">

                                                <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('general_ledger'); ?>">Reset Filters</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php if (isset($generalLedger) && sizeof($generalLedger) > 0) { ?>
                                <div class="box-view reports-filtering">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <form method="post" id="export" name="export">
                                                    <label class="control control--checkbox pull-left">
                                                        Pull Applicant Source In Export
                                                        <input type="checkbox" value="1" name="embed-source" class="pull-right" checked>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                </form>
                                                <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    Print
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            <!-- table -->
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">General Ledger</h1>
                                    </span>
                                    <span class="pull-right">
                                        <h1 class="hr-registered">Total Records Found : <?php echo $generalLedgerCount; ?></h1>
                                    </span>
                                </div>
                                <div class="hr-innerpadding">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive" id="print_div">
                                                <table class="table table-bordered" id="example">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Account Type</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (isset($generalLedger) && sizeof($generalLedger) > 0) { ?>
                                                            <?php foreach ($generalLedger as $ledger) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $ledger['date']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $ledger['type']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $ledger['debit']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $ledger['creedit']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="4">
                                                                    <div class="no-data">No general ledger found.</div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <span class="pull-right">
                                                <?php echo $page_links ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var baseURL = "<?php echo base_url(); ?>";
</script>