<?php
$importColumnsArray = [];
$importColumnsArray[] = 'Employee ID';
$importColumnsArray[] = 'Employee Number';
$importColumnsArray[] = 'Employee SSN';
$importColumnsArray[] = 'Employee Email';
$importColumnsArray[] = 'Employee Phone Number';
$importColumnsArray[] = 'Course Title';
$importColumnsArray[] = 'lesson Status';
$importColumnsArray[] = 'Course Status';
$importColumnsArray[] = 'Course Type';
$importColumnsArray[] = 'Course Taken Count';
$importColumnsArray[] = 'Course Start Date';
$importColumnsArray[] = 'Course End Date';


//
$importValueArray = '';
$importValueArray .= '1234, E1234, 219-09-9999, email@abc.com, +1234567892, EHS Training, completed, passed, manual, 3, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= '1234, , , , , Respiratory Management, completed, passed, scorm, 2, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= ' , E1234, , , , Sales & Finance Training, incomplete, failed, scorm, 1, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= ' ,  , 219-09-9999, , , Schaller Auto Body, incomplete, failed, scorm, 1, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= ', , , email@abc.com, , EHS Training, completed, passed, scorm, 4, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= ' , , , , +1234567892, Respiratory Management, completed, passed, scorm, 3, 5/8/2024, 6/9/2024,<br/>';
$importValueArray .= ',  E15708, , , , Sales & Finance Training, incomplete, failed, scorm, 1, 5/8/2024, 6/9/2024,<br/>';
?> 
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view($left_navigation); ?>
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
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" value="Import Courses" disabled="true" />
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
    var baseURI = "<?=base_url()?>";
</script>