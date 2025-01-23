<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i>
                            <?= $this->lang->line('tab_my_incidents', false) ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i>
                            <?= $this->lang->line('tab_assigned_incidents', false) ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>

                <?php if (sizeof($types) > 0) {
                    $first_heading = true;
                    $first_content = true; ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs btn-tabs">
                                <?php foreach ($types as $type) {
                                    $class_name = strtolower(clean(trim($type['incident_name'])));
                                    if ($first_heading) {
                                        $is_active = ' active';
                                        $first_heading = false;
                                    } else {
                                        $is_active = '';
                                    } ?>

                                    <li class="<?php echo $class_name . $is_active; ?>"><a href="#tab<?php echo $type['id']; ?>" data-toggle="tab"><?php echo $type['incident_name']; ?></a></li>
                                <?php                   } ?>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <?php foreach ($types as $type) {
                                    if ($first_content) {
                                        $class = 'in active';
                                        $first_content = false;
                                    } else {
                                        $class = '';
                                    } ?>

                                    <div class="tab-pane fade  <?php echo $class; ?>" id="tab<?php echo $type['id']; ?>">
                                        <div class="panel panel-blue">
                                            <div class="panel-heading">
                                                <strong>Instructions</strong>
                                            </div>
                                            <div class="panel-body">
                                                <?php echo $type['instructions']; ?>
                                            </div>
                                        </div>
                                        <div class="panel panel-blue">
                                            <div class="panel-heading">
                                                <strong>Reasons</strong>
                                            </div>
                                            <div class="panel-body">
                                                <p><?php echo $type['reasons']; ?></p>
                                            </div>
                                        </div>
                                        <div class="panel panel-blue">
                                            <div class="panel-heading">
                                                <strong>File an Incident Report</strong>
                                            </div>
                                            <div class="panel-body">
                                                <p>This company takes its obligation to provide a good working environment very seriously. We do not tolerate inappropriate or unprofessional conduct in the workplace. Your report to us is essential in enforcing this workplace standard and we appreciate your report. When we receive a report from our employees we undertake to investigate the report and take appropriate action to solve any problems and to prevent future problems.</p>
                                            </div>
                                        </div>
                                        <div class="panel panel-blue">
                                            <div class="panel-heading">
                                                <strong>File an Confidential Report</strong>
                                            </div>
                                            <div class="panel-body">
                                                <p>Providing your identity allows us to obtain further information, if necessary, to continue our investigation if questions arise. It allows us to conduct you to discuss our findings and our proposed course of action. If you provide your identity, your report will remain as confidential as possible. We will only share the information as needed during our investigation and to prevent further problems. In addition, your report will not subject you to any adverse consequences, as retaliation by managers or co-workers is strictly prohibited.</p>
                                                <div class="btn-wrp full-width mrg-top-20 text-right">
                                                    <?php if ($type['is_safety_incident'] == 1) { ?>
                                                        <a href="<?php echo base_url('compliance_report/c/' . $type['id']) ?>" class="btn btn-info"> File Confidential </a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url('incident_reporting_system/report/c/' . $type['id']) ?>" class="btn btn-info"> File Confidential </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-blue">
                                            <div class="panel-heading">
                                                <strong>File an Anonymous Report</strong>
                                            </div>
                                            <div class="panel-body">
                                                <p>Employees can file a report of complaint without being identified beyond being an employee of the company. No other information is collected for or shared with other third parties except as may be required by law wherein we have a good-faith belief that such action is necessary to comply with a current judicial proceeding, a court order or legal process. No information is collected about your report or response that would identify you as an individual beyond being an employee of your company</p>
                                                <div class="btn-wrp full-width mrg-top-20 text-right">
                                                    
                                                    <?php if ($type['is_safety_incident'] == 1) { ?>
                                                        <a href="<?php echo base_url('compliance_report/a/' . $type['id']) ?>" class="btn btn-info"> File Anonymous </a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url('incident_reporting_system/report/a/' . $type['id']) ?>" class="btn btn-info"> File Anonymous </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php                   } ?>
                            </div>
                        </div>
                    </div>
                <?php   } else { ?>
                    <div class="panel panel-blue">
                        <div class="panel-heading text-center">
                            <strong>No Incident Configured Against Your Company</strong>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>