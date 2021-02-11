<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('my_referral_network/add'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-plus"></i> Add Referral </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>    
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile"><?php echo $title; ?></h1>
                </div>
                <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="dashboard-conetnt-wrp">
                                <div class="col-xs-12 col-sm-12">
                                    <?php echo $links;  ?>
                                </div>
                                <small>( <strong class="messagesCounter"><?php echo $references_count; ?></strong> Jobs Referred )</small>
                                <?php if (!empty($references)) {?>
                                <div class="table-responsive table-outer">
                                    <div class="table-wrp data-table">
                                        <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                            <thead>
                                            <tr>
                                                <th class="col-xs-2">Referred On</th>
                                                <th class="col-xs-4">Job Title</th>
                                                <th class="col-xs-2">Referred To</th>
                                                <th class="col-xs-4">Reference Email</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($references as $reference) { ?>
                                                <tr>
                                                    <td><?=reset_datetime(array('datetime' => $reference['referred_date'], '_this' => $this)); ?></td>
                                                    <td><?php echo $reference['Job_title']; ?></td>
                                                    <td><?php echo $reference['referred_to']; ?></td>
                                                    <td><?php echo $reference['reference_email']; ?></td>
                                                    <!--<td><a class="btn btn-default" href="<?php echo base_url('my_reference_network') . '/view/' . $reference['sid']; ?>"><i class="fa fa-eye"></i></a></td>-->
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp data-table">
                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                <thead>
                                                <tr>
                                                    <th class="col-xs-2">Referred On</th>
                                                    <th class="col-xs-4">Job Title</th>
                                                    <th class="col-xs-2">Referred To</th>
                                                    <th class="col-xs-4">Reference Email</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4" class="text-center">You haven't Referred any jobs!</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
<!--                                    <div id="show_no_jobs" class="table-wrp">-->
<!--                                        <span class="applicant-not-found">You haven't Referred any jobs!</span>-->
<!--                                    </div>-->
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <?php echo $links;  ?>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>