<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php if ($user_type == 'applicant') { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_sid . '/' . $job_list_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Employee Profile</a>
                                    <?php }
                                    echo $title; ?>
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-success pull-right" target="_blank" href="<?= base_url("hr_documents_management/pd_direct_deposit_history_detail/".$assignedId)."/download"; ?>">Download</a>
                                    <a class="btn btn-success pull-right margin-right" target="_blank" href="<?= base_url("hr_documents_management/pd_direct_deposit_history_detail/".$assignedId)."/print"; ?>">Print</a>
                                </div>
                            </div>        

                            <!-- main table view start -->
                            <div class="dashboard-conetnt-wrp" id="jsDDHistorySection">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <h1 class="hr-registered pull-left">
                                                    <span class="">History Detail</span>
                                                </h1>
                                            </div>

                                            <div class="hr-box-body hr-innerpadding">
                                                <?php if ($historyDetail) { ?>
                                                    <?php foreach ($historyDetail as $detail) { ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <strong>Updated at: <?= formatDateToDB($detail['account1']['date_modified'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-xs-6">Bank Account 1</th>
                                                                                <th class="col-xs-6">Bank Account 2</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <table class="table table-bordered table-condensed table-hover">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th class="col-xs-4">Account Title</th>
                                                                                                <td class="col-xs-8"><?= $detail['account1']['account_title']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Account Type</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account1']['account_type']) { ?>
                                                                                                        <?= $detail['account1']['account_type'] == 'checking' ? 'Checking' : 'Savings'; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Financial Institution (Bank) Name </th>
                                                                                                <td><?= $detail['account1']['financial_institution_name']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Bank routing number (ABA number)</th>
                                                                                                <td><?= $detail['account1']['routing_transaction_number']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Account Number</th>
                                                                                                <td><?= $detail['account1']['account_number']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Deposit Type</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account1']['deposit_type']) { ?>
                                                                                                        <?= isset($detail['account1']['deposit_type']) && $detail['account1']['deposit_type'] == 'percentage' ? 'Percentage' : 'Amount'; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th><?= isset($detail['account1']['deposit_type']) && $detail['account1']['deposit_type'] == 'percentage' ? 'Percentage value to be deposited to this account' : 'Dollar amount to be deposited to this account'; ?></th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account1']['account_percentage']) { ?>
                                                                                                        <?= isset($detail['account1']['deposit_type']) && $detail['account1']['deposit_type'] == 'percentage' ? $detail['account1']['account_percentage'] . '%' : '$' . $detail['account1']['account_percentage']; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Updated By</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account1']['updated_by']) { ?>
                                                                                                        <?php if (getUserNameBySID($detail['account1']['updated_by'])) { ?>
                                                                                                            <?= getUserNameBySID($detail['account1']['updated_by']); ?>
                                                                                                        <?php } else { ?>
                                                                                                            <?= getApplicantNameBySID($detail['account1']['updated_by']); ?>
                                                                                                        <?php } ?>    
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                                <td>
                                                                                    <table class="table table-bordered table-condensed table-hover">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th class="col-xs-4">Account Title</th>
                                                                                                <td class="col-xs-8"><?= $detail['account2']['account_title']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Account Type</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account2']['account_type']) { ?>
                                                                                                        <?= $detail['account2']['account_type'] == 'checking' ? 'Checking' : 'Savings'; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Financial Institution (Bank) Name </th>
                                                                                                <td><?= $detail['account2']['financial_institution_name']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Bank routing number (ABA number)</th>
                                                                                                <td><?= $detail['account2']['routing_transaction_number']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Account Number</th>
                                                                                                <td><?= $detail['account2']['account_number']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Deposit Type</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account2']['deposit_type']) { ?>
                                                                                                        <?= isset($detail['account2']['deposit_type']) && $detail['account2']['deposit_type'] == 'percentage' ? 'Percentage' : 'Amount'; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th><?= isset($detail['account2']['deposit_type']) && $detail['account2']['deposit_type'] == 'percentage' ? 'Percentage value to be deposited to this account' : 'Dollar amount to be deposited to this account'; ?></th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account2']['account_percentage']) { ?>
                                                                                                        <?= isset($detail['account2']['deposit_type']) && $detail['account2']['deposit_type'] == 'percentage' ? $detail['account2']['account_percentage'] . '%' : '$' . $detail['account2']['account_percentage']; ?>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Updated By</th>
                                                                                                <td>
                                                                                                    <?php if ($detail['account2']['updated_by']) { ?>
                                                                                                        <?php if (getUserNameBySID($detail['account2']['updated_by'])) { ?>
                                                                                                            <?= getUserNameBySID($detail['account2']['updated_by']); ?>
                                                                                                        <?php } else { ?>
                                                                                                            <?= getApplicantNameBySID($detail['account2']['updated_by']); ?>
                                                                                                        <?php } ?>  
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <table class="table table-bordered table-condensed table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center"><strong>No History found</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- main table view end -->

                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    
<script type="text/javascript">
    //
    $(document).on('click', '#jsDownloadDDHistory', function(e) {
        var draw = kendo.drawing;
        draw.drawDOM($("#jsDDHistorySection"), {
            avoidLinks: false,
            paperSize: "A4",
            multiPage: true,
            margin: { bottom: "2"},
            scale: 0.8
        })
        .then(function(root) {
            return draw.exportPDF(root);
        })
        .done(function(data) {
            kendo.saveAs({
                dataURI: data,
                fileName: "direct_deposit_form.pdf"
            });
            //
            setTimeout(() => {
                window.close();   
            }, 5000);
        });
    });
    //
    $(document).on('click', '#jsPrintDDHistory', function(e) {
        window.print();
    });
</script>