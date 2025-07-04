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
                    <hr>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="btn-panel">
                                <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-success"><i class="fa fa-heartbeat"></i> <?= $this->lang->line('tab_my_incidents', false) ?></a>
                                <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-success"><i class="fa fa-stethoscope "></i> <?= $this->lang->line('tab_assigned_incidents', false) ?></a></a>
                                <a href="<?php echo base_url('incident_reporting_system/view_general_guide') ?>" class="btn btn-success"><i class="fa fa-book"></i> Incident Guide </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-outer">
                        <form action="" method="POST" id="xml_form">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-xs-4">Incident Name</th>
                                        <th class="col-xs-4">Reported On</th>
                                        <th class="col-xs-4">Incident Type</th>
                                        <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (sizeof($incidents) > 0) {
                                        foreach ($incidents as $incident) { ?>
                                            <tr>
                                                <td><?php echo ucfirst($incident['incident_name']); ?></td>
                                                <td><?php echo my_date_format($incident['current_date']); ?></td>
                                                <td><?php echo ucfirst($incident['report_type']); ?></td>
                                                <td class="text-center">
                                                    <a class="btn btn-success" href="<?php echo base_url('incident_reporting_system/view_incident/' . $incident['id']) ?>">View</a>
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

<?php } else { ?>
    <?php $this->load->view('manage_employer/incident_reporting/list_employee_incidents'); ?>
<?php } ?>