<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-2 text-left">
                <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-black"><i class="fa fa-arrow-left"> </i> Dashboard</a>
            </div>
            <div class="col-lg-10 text-right">
                <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-blue"><i class="fa fa-heartbeat"></i>
                    <?= $this->lang->line('tab_my_incidents', false) ?></a>
                <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-blue"><i class="fa fa-stethoscope "></i>
                    <?= $this->lang->line('tab_assigned_incidents', false) ?></a>
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
                                if ($first_heading) {
                                    $is_active = ' active';
                                    $first_heading = false;
                                } else {
                                    $is_active = '';
                                } ?>

                                <li class="<?php echo $is_active; ?>"><a href="#tab<?php echo $type['id']; ?>" data-toggle="tab" class="btn csRadius5" style="background-color: <?= $type["color_code"]; ?>"><?php echo $type['compliance_report_name']; ?></a></li>
                            <?php } ?>
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
                                        <div class="panel-footer text-center">
                                            <a href="" class="btn btn-blue">
                                                Initiate Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php   } else { ?>
                <div class="panel panel-blue">
                    <div class="panel-heading text-center">
                        <strong>No Compliance Safety Types Found.</strong>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>