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
                            <a href="<?php echo base_url('incident_reporting_system') ?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Incident Reporting System</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="btn-panel">
                                <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-success"><i class="fa fa-heartbeat"></i> <?= $this->lang->line('tab_my_incidents', false) ?></a>
                                <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-success"><i class="fa fa-stethoscope "></i> <?= $this->lang->line('tab_assigned_incidents', false) ?></a></a>
                                <a href="<?php echo base_url('incident_reporting_system/view_general_guide') ?>" class="btn btn-success"><i class="fa fa-book"></i> Incident Guide </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <form action="" method="POST" id="xml_form">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-4">Report Date</th>
                                            <th class="col-xs-4">Report Name</th>
                                            <th class="col-xs-4">Report Type</th>
                                            <th class="col-xs-4">Status</th>
                                            <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions 1</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (sizeof($assigned_incidents) > 0) {
                                            foreach ($assigned_incidents as $incident) { ?>
                                                <tr <?php echo $incident['status'] == 'RequireFeedback' ? 'style="background-color: bisque;"' : '' ?>>
                                                    <td><?php echo my_date_format($incident['current_date']); ?></td>
                                                    <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                                    <td><?php echo ucfirst($incident['report_type']); ?></td>
                                                    <td <?php echo $incident['status'] == 'RequireFeedback' ? 'style="color: red;"' : '' ?>><?php echo $incident['status'] == 'RequireFeedback' ? '<b>Require Feed Back</b>' : $incident['status']; ?></td>
                                                    <td class="text-center">
                                                        <?php if (isSafetyIncident($incident['incident_type_id'])) { ?>
                                                            <a class="btn btn-info btn-block" href="<?php echo base_url('incident_reporting_system/view_safety_incident/' . $incident['id']) ?>">Respond</a>
                                                        <?php } else { ?>
                                                            <a class="btn btn-info" href="<?php echo base_url('incident_reporting_system/view_single_assign/' . $incident['id']) ?>">Respond</a>
                                                        <?php } ?>  
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <span class="no-data">No Incident Assigned To You</span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



<?php } else { ?>
    <?php $this->load->view('manage_employer/incident_reporting/assigned_incidents_ems'); ?>
<?php } ?>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>