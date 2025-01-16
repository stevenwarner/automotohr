<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <a href="<?php echo base_url('incident_reporting_system') ?>" class="btn btn-info btn-block"><i class="fa fa-arrow-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-info btn-block"><i class="fa fa-heartbeat"></i> <?= $this->lang->line('tab_my_incidents', false) ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block"><i class="fa fa-stethoscope "></i> <?= $this->lang->line('tab_assigned_incidents', false) ?></a>
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
                <hr>
                <div class="table-responsive table-outer">
                    <form action="" method="POST" id="xml_form">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-xs-4">Report Name</th>
                                    <th class="col-xs-4">Reported On</th>
                                    <th class="col-xs-2 text-center last-col" width="1%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (sizeof($incidents) > 0) {
                                    foreach ($incidents as $incident) { ?>
                                        <tr>
                                            <td>
                                                <p>
                                                    <?php echo ucfirst($incident['incident_name']); ?>
                                                    <?php if ($incident['pending'] > 0) { ?>
                                                        <img src="<?= base_url('assets/images/new_msg.gif') ?>">
                                                    <?php } ?>
                                                </p>
                                                <?php if ($incident['on_behalf_employee_sid'] !== $incident['employer_sid'] && $incident['on_behalf_employee_sid'] != 0) {
                                                    $user_info_incident = db_get_employee_profile($incident['on_behalf_employee_sid']);
                                                ?>
                                                    <p class="text-danger">Created By: <?= remakeEmployeeName($user_info_incident[0]); ?></p>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo my_date_format($incident['current_date']); ?></td>
                                            <td class="text-center">
                                                <?php if (isSafetyIncident($incident['incident_type_id'])) { ?>
                                                    <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_safety_incident/' . $incident['id']) ?>">View</a>
                                                <?php } else { ?>
                                                    <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_incident/' . $incident['id']) ?>">View</a>
                                                <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <span class="no-data">No Incident Reported Yet</span>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
</script>