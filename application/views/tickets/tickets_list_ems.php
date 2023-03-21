<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="main jsmaincontent">
    <div class="container-fluid">
    
        <div class="row">

        <div class="col-lg-1">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                </a>
            </div>

            <div class="col-lg-2">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a style="margin:0 0 10px;" href="<?php echo base_url('support_tickets/add'); ?>" class="btn btn-info">+ Create New Support Ticket</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h1 class="section-ttile"><b><?php echo $tickets_count; ?></b> support tickets found.</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php echo $links; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <?php if ($tickets) { ?>
                                    <form action="" method="POST" id="ticket_form">
                                        <div class="table-wrp mylistings-wrp border-none">
                                            <table class="table table-bordered table-stripped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="col-xs-2 text-center">ID</th>
                                                    <th class="col-xs-3 text-center">Date</th>
                                                    <th class="col-xs-3 text-center">Category</th>
                                                    <th class="col-xs-6 text-center">Ticket Subject</th>
                                                    <th class="col-xs-2 text-center">Status</th>
                                                    <th class="col-xs-2 text-center">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($tickets as $ticket) { ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo str_pad($ticket['sid'], 5, '0', STR_PAD_LEFT); ?></td>
                                                        <td class="text-center"><?=reset_datetime(array( 'datetime' => $ticket['updated_date'], '_this' => $this)); ?></td>
                                                        <td class="text-center"><?php echo $ticket['ticket_category']; ?></td>
                                                        <td class="text-center"><?php echo $ticket['subject']; ?></td>
                                                        <td class="text-center <?php if($ticket['status'] == 'Awaiting Response'){?>red<?php } else if($ticket['status'] == 'Answered') { ?>green<?php } else if($ticket['status'] == 'Feedback Required') { ?>orange<?php } ?>"><?php echo ucwords($ticket['status']); ?></td>
                                                        <td class="text-center">
                                                            <a class="btn btn-info" href="<?php echo base_url('support_tickets/view').'/'.$ticket['sid']; ?>">View</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="no-job-found">
                                        <ul>
                                            <li>
                                                <h3 style="text-align: center;">No Support Tickets found! </h3>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php echo $links; ?>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>

