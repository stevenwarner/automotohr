<?php
$importColumnsArray = [];
$importColumnsArray[] = 'Employee ID';
$importColumnsArray[] = 'Employee Number';
$importColumnsArray[] = 'Employee SSN';
$importColumnsArray[] = 'Employee Email';
$importColumnsArray[] = 'Employee Phone Number';
$importColumnsArray[] = 'Debit';
$importColumnsArray[] = 'Credit';
$importColumnsArray[] = 'Start Period';
$importColumnsArray[] = 'End Period';
$importColumnsArray[] = 'Transaction Date';
$importColumnsArray[] = 'First Name';
$importColumnsArray[] = 'Last name';
$importColumnsArray[] = 'Department';
$importColumnsArray[] = 'Position';
$importColumnsArray[] = 'Gross Pay';
$importColumnsArray[] = 'Net Pay';

//
$importColumnsArray[] = 'Account Name';
$importColumnsArray[] = 'Account Number';
$importColumnsArray[] = 'Reference Number';
$importColumnsArray[] = 'General Entry Number';

//
$importValueArray = '';
$importValueArray .= ', , , , ,25000, 0, 1/7/2024, 31/7/2024, 5/8/2024,  31/7/2024, , , , , , , JON,1255556666,54877770012,235687<br/>';
$importValueArray .= '1234, E1234, 219-09-9999, email@abc.com, +1234567892, 3000, 0, 1/7/2024, 31/7/2024, 5/8/2024, John, Doe, Sales, Sales Representative, 5000, 1000, 4000,JONJo,1255556666,54877770012,235687<br/>';
$importValueArray .= '1234, , , , , 3000, 0, 1/7/2024, 31/7/2024, 5/8/2024, John, Doe, Sales, Sales Representative, 5000, 1000, 4000,,1255556666,54877770012,235687<br/>';
$importValueArray .= ' , E1234, , , , 2400, 0 ,1/7/2024, 31/7/2024, 5/8/2024, Jason, Snow, Sales, General Manager, 7000, 1500, 5500,Ryan,1255556666,54877770012,235687<br/>';
$importValueArray .= ' ,  , 219-09-9999, , , 2900, 0, 1/7/2024, 31/7/2024, 5/8/2024, Nathan, Quite, Sales, Technician, 4000, 800, 3200,Ryan,1255556666,54877770012,235687<br/>';
$importValueArray .= ', , , email@abc.com, , 2400, 0 ,1/7/2024, 31/7/2024, 5/8/2024, Jason, Snow, Sales, General Manager, 7000, 1500, 5500,Jana,1255556666,54877770012,235687<br/>';
$importValueArray .= ' , , , , +1234567892, 2900, 0, 1/7/2024, 31/7/2024, 5/8/2024, Nathan, Quite, Sales, Technician, 4000, 800, 3200,JO,1255556666,54877770012,235687<br/>';
$importValueArray .= ', , , , , 30000, 0, 1/7/2024, 31/7/2024, 5/8/2024, , , , , , ,Jef,1255556666,54877770012,235687 <br/>';
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
                                                    <div class="form-group">
                                                        <h4>The Provided CSV File must be in Following Format <input type="submit" name="submit" class="submit-btn pull-right" value="Download Template"></h4>
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