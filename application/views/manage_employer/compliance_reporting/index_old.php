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
                            <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Dashboard</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <hr>
                    <div class="dashboard-conetnt-wrp">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="btn-panel">
                                    <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-success"><i class="fa fa-heartbeat"></i>
                                        <?= $this->lang->line('tab_my_incidents', false) ?></a>
                                    <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-success"><i class="fa fa-stethoscope "></i>
                                        <?= $this->lang->line('tab_assigned_incidents', false) ?></a>
                                    <a href="<?php echo base_url('incident_reporting_system/view_general_guide') ?>" class="btn btn-success"><i class="fa fa-book"></i> Incident Guide </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-xs-6">Type Name</th>
                                        <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (sizeof($types) > 0) {
                                        if (sizeof($types) == 1 && $types[0]['count'] == 0) {
                                            echo '<tr>
                                                  <td colspan="8" class="text-center">
                                                      <span class="no-data">No Incident Type Found</span>
                                                  </td>
                                              </tr>';
                                        }
                                        foreach ($types as $type) {
                                            if ($type['count'] > 0) { ?>
                                                <tr>
                                                    <td><?php echo $type['incident_name']; ?></td>
                                                    <!--                                        <td class="text-center">-->
                                                    <!--                                            <a id="btn_apply_filters" class="btn btn-primary" href="--><?php //echo base_url('incident_reporting_system/view_guide/'.$type['id'])
                                                                                                                                                                ?><!--">User Guide</a>-->
                                                    <!--                                        </td>-->
                                                    <td class="text-center">
                                                        <a class="btn btn-success" href="<?php echo base_url('incident_reporting_system/view_guide/' . $type['id']) ?>">Report</a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span class="no-data">No Incident Type Found</span>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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

    <script type="text/javascript"></script>
<?php } else { ?>
    <?php $this->load->view('manage_employer/compliance_reporting/index'); ?>
<?php } ?>