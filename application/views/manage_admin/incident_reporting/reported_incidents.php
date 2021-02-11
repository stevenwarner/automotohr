<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/reports/incident_reporting')?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Date Reported</th>
                                                            <th>Company Name</th>
                                                            <th>Reported By</th>
                                                            <th class="last-col" width="1%" colspan="3">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <!--All records-->

                                                        <?php if(sizeof($incidents)>0) {
                                                            foreach ($incidents as $incident) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= date_with_time($incident['current_date']);?>
                                                                    </td>

                                                                    <td>
                                                                        <?= ucfirst($incident['CompanyName']);?>
                                                                    </td>

                                                                    <td>
                                                                        <?= $incident['report_type'] == 'confidential' ? ucfirst($incident['first_name'] . " " . $incident['last_name']) : 'Anonymous';?>
                                                                    </td>

                                                                    <td><?php echo anchor('manage_admin/reports/incident_reporting/view_incident/'.$incident['id'], 'View Incident', 'class="btn btn-success btn-sm" title="View Incident"'); ?></td>


                                                                </tr>
                                                            <?php }
                                                        }
                                                        else{ ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Incident Reported Yet</span>
                                                                </td>
                                                            </tr>
                                                        <?php }?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="execute" value="multiple_action">
                                                    <input type="hidden" id="type" name="type" value="employer">
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>