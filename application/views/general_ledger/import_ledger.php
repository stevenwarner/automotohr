<?php 
$header_row = ',,,,,,,Employee Benefits Deductions,Deferred Payroll Taxes,Other Deductions';

    $importColumnsArray = [];
    $importColumnsArray[] = 'Payroll Date';
    $importColumnsArray[] = 'Company Debit';
    $importColumnsArray[] = 'Net Pay Debit';
    $importColumnsArray[] = 'Tax Debit';
    $importColumnsArray[] = 'Reimbursement Debit';
    $importColumnsArray[] = 'Child Support Debit';
    $importColumnsArray[] = 'Reimbursements';
    $importColumnsArray[] = 'Net Pay';
    $importColumnsArray[] = 'Gross Pay';
    $importColumnsArray[] = 'Employee Bonuses';
    $importColumnsArray[] = 'Employee Commissions';
    $importColumnsArray[] = 'Employee Cash Tips';
    $importColumnsArray[] = 'Additional Earnings';
    $importColumnsArray[] = 'Owners Draw';
    $importColumnsArray[] = 'Check Amount';
    $importColumnsArray[] = 'Employer Taxes';
    $importColumnsArray[] = 'Employee taxes';
    $importColumnsArray[] = 'Benefits';
    $importColumnsArray[] = 'Employee Benefits Deductions';
    $importColumnsArray[] = 'Deferred Payroll Taxes';
    $importColumnsArray[] = 'Other Deductions';
    //
    $importValueArray = '';
    $importValueArray .= '121747.71, 79283.80, 42463.91, 0.00, 0.00, 0.00, 81752.94, 130635.89, 0.00, 18536.37, 0.00, 0.00, 0.00, 2469.14, 6917.19, 35546.72, 0.00, 13336.23, 0.00,<br/>';
    $importValueArray .= '121747.71, 79283.80, 42463.91, 0.00, 0.00, 0.00, 81752.94, 130635.89, 0.00, 18536.37, 0.00, 0.00, 0.00, 2469.14, 6917.19, 35546.72, 0.00, 13336.23, 0.00,<br/>';
    $importValueArray .= '121747.71, 79283.80, 42463.91, 0.00, 0.00, 0.00, 81752.94, 130635.89, 0.00, 18536.37, 0.00, 0.00, 0.00, 2469.14, 6917.19, 35546.72, 0.00, 13336.23, 0.00,<br/>';
    $importValueArray .= '121747.71, 79283.80, 42463.91, 0.00, 0.00, 0.00, 81752.94, 130635.89, 0.00, 18536.37, 0.00, 0.00, 0.00, 2469.14, 6917.19, 35546.72, 0.00, 13336.23, 0.00,<br/>';
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4>The Provided CSV File must be in Following Format</h4>
                                    </div>
                                    <div class="panel-body">
                                        <pre>
                                            <strong><?=implode(', ', $importColumnsArray);?></strong><br>
                                            <?=$importValueArray;?>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" action="javascript:void(0)" id="js-import-form" enctype="multipart/form-data">
                                    <input type="hidden" value="upload_file" name="action" id="action" />
                                    <div>
                                        <label>Upload CSV File</label>
                                        <input type="file" id="userfile" style="display: none;" />
                                    </div>
                                    <br />
                                    <div>
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" value="Import Employees" disabled="true" />
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loader -->
<div id="my_loader" class="text-center js-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
        <div class="loader-icon-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;" aria-hidden="true"></i>
            <div class="loader-text js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>