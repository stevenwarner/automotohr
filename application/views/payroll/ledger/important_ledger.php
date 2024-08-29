<?php
$importColumnsArray = [];
$importColumnsArray[] = 'Employee ID';
$importColumnsArray[] = 'Employee Number';
$importColumnsArray[] = 'Employee SSN';
$importColumnsArray[] = 'Employee Email';
$importColumnsArray[] = 'Employee Phone Number';
$importColumnsArray[] = 'Debit';
$importColumnsArray[] = 'Credit';
$importColumnsArray[] = 'Taxes';
$importColumnsArray[] = 'Description';

$importColumnsArray[] = 'Start Period';
$importColumnsArray[] = 'End Period';
$importColumnsArray[] = 'Transaction Date';

//
$importColumnsArray[] = 'Account Name';
$importColumnsArray[] = 'Account Number';
$importColumnsArray[] = 'Reference Number';
$importColumnsArray[] = 'Journal Entry Number';

$importColumnsArray[] = 'First Name';
$importColumnsArray[] = 'Last name';
$importColumnsArray[] = 'Department';
$importColumnsArray[] = 'Job Title';
$importColumnsArray[] = 'Gross Pay';
$importColumnsArray[] = 'Net Pay';



//
$importValueArray = '';

$importValueArray .= ', , , , , 3000, 0, ,,1/7/2024, 31/7/2024, 5/8/2024, Jon Doe,1232659875555,25415878,9874889, John, Doe, Sales, Sales Representative, 5000, 1000<br/>';
$importValueArray .= '1234, E1234, 219-09-9999, email@abc.com, +1234567892, 3000, 0, ,12,1/7/2024, 31/7/2024, 5/8/2024, Jon Doe,1232659875555,25415878,9874889, John, Doe, Sales, Sales Representative, 5000, 1000<br/>';
$importValueArray .= '123452, E12345, 219-09-9999, jonemail@abc.com, +12345670892, 3000, 0, 100,,1/7/2024, 31/7/2024, 5/8/2024,  ,123265987553558,2541587885,1258, , Jason, Sales, General Manager, 8000, 1000<br/>';
$importValueArray .= '123489, E12345255, 219-09-9999, nathan@abc.com, +12345678092, 3000, 0,, ,1/7/2024, 31/7/2024, 5/8/2024,  ,12326593875555,25415874888,9874889788, , Nathan, Sales, Technician, 5000, 5000<br/>';
$importValueArray .= '123489433, , 219-09-9999, Jef@abc.com, +12345678902, 3000, 0,,, 1/7/2024, 31/7/2024, 5/8/2024,  ,123265987554455,25415874888,9874889788,Jef , Jo, Sales, Technician, 5000, 5000<br/>';
$importValueArray .= ', , , jan@abc.com, +12345678902, 3000, 0, ,,1/7/2024, 31/7/2024, 5/8/2024,  ,123265987554455,25415874888,9874889788,jan , Jo, Sales, Technician, 8000, 5000<br/>';


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

                                        <form method="post" id="export" name="export" action="<?php echo base_url('payrolls/ledger/downloadtemplate') ?>">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class=" col-xs-6">
                                                        <h4>The Provided CSV File must be in Following Format </h4>
                                                    </div>
                                                    <div class=" col-xs-6">
                                                        <input type="submit" name="submit" class="submit-btn pull-right" value="Download Template">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="panel-body">
                                        <pre>
<strong><?= implode(', ', $importColumnsArray); ?></strong><br>
<?= $importValueArray; ?>
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
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" value="Import Payroll" disabled="true" />
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

<script>
    var baseURI = "<?= base_url() ?>";
</script>