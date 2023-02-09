<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area margin-top">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Back</a>
                        <?php echo $title; ?>
                    </span>
                </div>
                    <?php if ($guide[0]['instructions'] != '' || $guide[0]['reasons'] != '') { ?>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>File an Incident Report</b>
                                    <tbody>
                                    <tr>
                                        <td>This company takes its obligation to provide a good working environment very seriously. We do not tolerate inappropriate or unprofessional conduct in the workplace. Your report to us is essential in enforcing this workplace standard and we appreciate your report. When we receive a report from our employees we undertake to investigate the report and take appropriate action to solve any problems and to prevent future problems.</td>
                                        
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>File an Confidential Report</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            Providing your identity allows us to obtain further information, if necessary, to continue our investigation if questions arise. It allows us to conduct you to discuss our findings and our proposed course of action. If you provide your identity, your report will remain as confidential as possible. We will only share the information as needed during our investigation and to prevent further problems. In addition, your report will not subject you to any adverse consequences, as retaliation by managers or co-workers is strictly prohibited.
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <a id="search_btn" href="<?php echo base_url('incident_reporting_system/report/c/'.$id)?>" class="btn btn-success pull-right"> File Confidential </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>File an Anonymous Report</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            Employees can file a report of complaint without being identified beyond being an employee of the company. No other information is collected for or shared with other third parties except as may be required by law wherein we have a good-faith belief that such action is necessary to comply with a current judicial proceeding, a court order or legal process. No information is collected about your report or response that would identify you as an individual beyond being an employee of your company
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <a id="search_btn" href="<?php echo base_url('incident_reporting_system/report/a/'.$id)?>" class="btn btn-success pull-right"> File Anonymous </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div id="show_no_jobs" class="table-wrp">
                            <span class="applicant-not-found">No Guide Presented Yet!</span>
                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<?php } else { ?>
    <?php $this->load->view('manage_employer/incident_reporting/guide'); ?>
<?php } ?>