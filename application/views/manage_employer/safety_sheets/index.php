<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
               <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('manage_ems') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> <?php echo $employee['access_level'] == 'Employee' ? 'Employee Management System' : 'Dashboard'; ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
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
                                    <th class="col-xs-6">Safety Category</th>
                                    <th class="col-xs-2 text-center last-col" width="1%" colspan="3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($types)>0){
                                $flag=0;
                                foreach($types as $type){?>
                                <tr>
                                    <td><?php echo $type['name']; ?></td>
                                    <td class="text-center">
                                        <a class="btn btn-info" href="<?php echo base_url('safety_sheets/view_sheets/'.$type['sid'])?>">View Sheets</a>
                                    </td>
                                </tr>
                            <?php }
                            } else{ ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <span class="no-data">No Category Found</span>
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
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript"></script>