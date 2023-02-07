<?php $this->load->view('timeoff/includes/header'); ?>
<!-- StyleSheets -->
<link rel="stylesheet" href="<?= base_url('assets/alertifyjs/css/alertify.min.css'); ?>">
<!-- Main Area -->
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                           
                            <div class="dashboard-conetnt-wrp">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4>The Provided CSV File must be in Following Format</h4>
                                    </div>
                                    <div class="panel-body">
                                        <pre><b>First Name, Last Name, Approved By, Leave Type, Requested Hours, Leave From, Leave To, Status, Submitted Date, Approved/Declined Date, Employee Comments, Manager Comments</b><br/><br/>Jhon, Doe, Steven, Marks, PTO, 8, <?=date('m/d/Y', strtotime('now'));?>, <?=date('m/d/Y', strtotime('now'));?>, <?=date('m/d/Y', strtotime('now'));?>, Approved, <?=date('m/d/Y', strtotime('now'));?>, <?=date('m/d/Y', strtotime('now'));?>, Sons High school bowling match, Make sure to submit tickets</pre>
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
                                        <input type="file" id="jsFileInput" style="display: none;" />
                                    </div>
                                    <br />
                                    <div>
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" id="jsImportBtn" value="Import Time Offs" disabled="true" />
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
<?php $this->load->view('loader', [
    'props' => 'id="jsImportHistoricLoader"'
]); ?>