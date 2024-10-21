<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>">
    <title>Direct Deposit History</title>
    <style>
    .content {
        font-size: 100%;
        line-height: 1.6em;
        display: block;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
        position: relative;
    }

    .header {
        width: 100%;
        float: left;
        padding: 5px 10px;
        text-align: center;
        box-sizing: border-box;
        background-color: #000;
    }

    .body-content {
        width: 100%;
        float: left;
        padding: 20px 12;
        /* margin-top: 90px; */
        box-sizing: padding-box;
    }

    .header h2 {
        color: #fff;
    }

    .footer {
        width: 100%;
        float: left;
        background-color: #000;
        padding: 20px 30px;
        box-sizing: border-box;
    }

    .footer_contant {
        float: left;
        width: 100%;
    }

    .footer_text {
        color: #fff;
        float: left;
        text-align: center;
        font-style: italic;
        line-height: normal;
        font-family: "Open Sans", sans-serif;
        font-weight: 600;
        font-size: 14px;
    }

    .footer_text a {
        color: #fff;
        text-decoration: none;
    }

    .employee-info figure {
        width: 50px !important;
        height: 50px !important;
    }

    .employee-info figure {
        float: left;
        width: 50px;
        height: 50px;
        border-radius: 100%;
        border: 1px solid #ddd;
    }

    .employee-info figure img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        border-radius: 3px !important;
    }

    .employee-info .text {
        /* margin: 0 0 0 60px; */
    }

    .employee-info .text h4 {
        font-weight: 600;
        font-size: 18px !important;
        margin: 0;
    }

    #js-data-area .text p {
        color: #000 !important;
    }

    .employee-info .text p {
        font-weight: 400;
        font-size: 14px;
        margin: 0;
    }

    .upcoming-time-info .icon-image {
        float: left;
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .upcoming-time-info .icon-image img {
        width: 100%;
        height: 100%;
    }

    .upcoming-time-info .text {
        margin: 5px 0 0 0;
    }

    .upcoming-time-info .text h4 {
        font-weight: 600;
        font-size: 16px;
        margin: 0;
    }

    .upcoming-time-info .text p {
        font-weight: 400;
        font-size: 14px;
        margin: 0;
    }

    .upcoming-time-info .text p span {
        font-weight: 700;
    }

    .section_heading {
        font-weight: 700;
    }

    .approvers_panel {
        margin-top: 18px;
    }

    .approver_row:nth-child(odd) {
        background-color: #F5F5F5;
    }
    </style>
</head>

<body cz-shortcut-listen="true">
    <div class="content" id="jsDDHistorySection">
        <div class="body-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>
                                Direct Deposit History
                            </strong> 

                            <strong style="float: right;">
                                Created Date: <?php echo formatDate(
                                    date('Y-m-d H:i:s'),
                                    'Y-m-d H:i:s',
                                    'M d Y, D H:i:s'
                                );?>
                            </strong>
                        </div>
                        <div class="panel-body">
                           <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <p style="margin-left: 10px;"><strong>User Type: <?= $user_type == "employee" ? "Employee" : "Applicant"; ?></strong></p>
                                        <p style="margin-left: 10px;"><strong>User Name: <?= $user_type == "employee" ? getUserNameBySID($user_sid) : getApplicantNameBySID($user_sid); ?></strong></p>
                                    </div>
                                </div>        
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="hr-box-body hr-innerpadding">
                                            <?php if ($historyDetail) { ?>
                                                <?php foreach ($historyDetail as $detail) { ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">
                                                                        <strong>Updated at: <?= formatDateToDB($detail['account1']['date_modified'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></strong>
                                                                    </th>    
                                                                </tr>
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
                                                    <br>
                                                    <br>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-condensed table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center"><strong>No History found</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>    
                                            <?php } ?>
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

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    <script id="script">
        if ("<?= $action; ?>" == 'download') {
            download_document();
        } else {
            $(window).on("load", function() {
                setTimeout(function() {
                    window.print();
                }, 2000);
            });

            window.onafterprint = function() {
                window.close();
            }
        }

        function download_document() {
            var draw = kendo.drawing;
            draw.drawDOM($("#jsDDHistorySection"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: {
                        bottom: "2cm"
                    },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    var pdf = data;
                    kendo.saveAs({
                        dataURI: pdf,
                        fileName: "direct_deposit_detail_history.pdf",
                    });
                    window.close();
                });
        }
    </script>
</body>

</html>