<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/view_general_guide/'.$id)?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Incident Guide </a>
                    </div>
                </div>
<!--                <div class="btn-panel">
                    
                    <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="btn btn-info"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a></a>
                    <a href="<?php echo base_url('incident_reporting_system/view_general_guide/'.$id)?>" class="btn btn-info"><i class="fa fa-book"></i> Incident Guide </a>
                </div>-->
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h2 class="section-ttile"><?php echo $title; ?></h2>
                    </div>

                    <?php if ($guide[0]['instructions'] != '' || $guide[0]['reasons'] != '') { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>File an Incident Report</strong>
                            </div>
                            <div class="panel-body">
                                <p>This company takes its obligation to provide a good working environment very seriously. We do not tolerate inappropriate or unprofessional conduct in the workplace. Your report to us is essential in enforcing this workplace standard and we appreciate your report. When we receive a report from our employees we undertake to investigate the report and take appropriate action to solve any problems and to prevent future problems.</p>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>File an Confidential Report</strong>
                            </div>
                            <div class="panel-body">
                                <p>Providing your identity allows us to obtain further information, if necessary, to continue our investigation if questions arise. It allows us to conduct you to discuss our findings and our proposed course of action. If you provide your identity, your report will remain as confidential as possible. We will only share the information as needed during our investigation and to prevent further problems. In addition, your report will not subject you to any adverse consequences, as retaliation by managers or co-workers is strictly prohibited.</p>
                                <div class="btn-wrp full-width mrg-top-20 text-right">
                                    <a href="<?php echo base_url('incident_reporting_system/report/c/'.$id)?>" class="btn btn-info"> File Confidential </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>File an Anonymous Report</strong>
                            </div>
                            <div class="panel-body">
                                <p>Employees can file a report of complaint without being identified beyond being an employee of the company. No other information is collected for or shared with other third parties except as may be required by law wherein we have a good-faith belief that such action is necessary to comply with a current judicial proceeding, a court order or legal process. No information is collected about your report or response that would identify you as an individual beyond being an employee of your company</p>
                                <div class="btn-wrp full-width mrg-top-20 text-right">
                                    <a href="<?php echo base_url('incident_reporting_system/report/a/'.$id)?>" class="btn btn-info"> File Anonymous </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Not sure!</strong>
                            </div>
                            <div class="panel-body">
                                <p>If you are not sure whether you want to <b>File an Confidential Report</b> or you want to <b>File an Anonymous Report</b> than please think over it and you can come back anytime to File the Report.</p>
                                <div class="btn-wrp full-width mrg-top-20 text-right">
                                    <a href="<?php echo base_url('incident_reporting_system/')?>" class="btn btn-info"> Come Back Later </a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="table-wrp full-width text-center">
                            <span class="no-data">No Guide Presented Yet!</span>
                        </div>
                    <?php } ?>


            </div>
<!--            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            </div>-->
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
