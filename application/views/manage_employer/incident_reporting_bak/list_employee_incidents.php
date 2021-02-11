<div class="main">
    <div class="container">
        <div class="row">
<!--            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">-->
<!--                --><?php //$this->load->view('main/employer_column_left_view'); ?>
<!--            </div>-->
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="btn btn-info btn-block"><i class="fa fa-arrow-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="btn btn-info btn-block"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a>
                    </div>
                    <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php //echo base_url('incident_reporting_system/view_general_guide')?>" class="btn btn-info btn-block"><i class="fa fa-book"></i> Incident Guide </a>
                    </div> -->
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
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
<!--                                    <th class="col-xs-4">Response Date</th>-->
                                    <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($incidents)>0){
                                foreach($incidents as $incident){?>
                                <tr>
                                    <td>
                                        <?php echo ucfirst($incident['incident_name']); ?>
                                        <?php if($incident['pending'] > 0){?>
                                            <img src="<?= base_url('assets/images/new_msg.gif')?>">
                                        <?php }?>
                                    </td>
                                    <td><?php echo my_date_format($incident['current_date']); ?></td>
<!--                                    <td>--><?php //echo ucfirst($incident['report_type']); ?><!--</td>-->
                                    <td class="text-center">
                                        <a class="btn btn-info" href="<?php echo base_url('incident_reporting_system/view_incident/'.$incident['id'])?>">View</a>

                                    </td>
                                </tr>
                            <?php }
                            } else{?>
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
<!--            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">-->
<!--                --><?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
<!--            </div>-->
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
</script>