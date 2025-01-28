<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"> </i> Dashboard</a>
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
                                    $class_name = strtolower(clean(trim($type['compliance_name'])));
                                    if ($first_heading) {
                                        $is_active = ' active';
                                        $first_heading = false;
                                    } else {
                                        $is_active = '';
                                    } ?>

                                    <li class="<?php echo $is_active; ?>"><a href="#tab<?php echo $type['id']; ?>" data-toggle="tab" style="background-color: <?php echo $type['tab_color']; ?>; color: #fff !important;"><?php echo $type['compliance_name']; ?></a></li>
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

                                        <?php
                                        if ($type['incidentsDetail']) {

                                            foreach ($type['incidentsDetail'] as $incidentRow) {
                                                $long_textbox = '<input type="text" data-type="text" data-required="no" class="form-control input-grey" name="" style="width:200px;"/>';
                                        ?>

                                                <div class="panel panel-blue">
                                                    <div class="panel-heading">
                                                        <strong><?php echo $incidentRow['incident_name']; ?></strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <?php echo  $my_return = str_replace("{{textbox}}", $long_textbox, $incidentRow['description']); ?>

                                                    </div>
                                                </div>

                                            <?php }
                                        } else { ?>

                                            <div class="panel-heading text-center">
                                                <strong>No Compliance Incident Found</strong>
                                            </div>

                                        <?php } ?>

                                    </div>
                                <?php                   } ?>
                            </div>
                        </div>
                    </div>
                <?php   } else { ?>
                    <div class="panel panel-blue">
                        <div class="panel-heading text-center">
                            <strong>No Compliance Incident Found</strong>
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