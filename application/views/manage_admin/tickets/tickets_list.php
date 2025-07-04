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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/support_tickets'); ?>">
                                            <i class="fa fa-long-arrow-left"></i> 
                                            Back to Support Tickets
                                        </a>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
                                    </div>
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-1">ID</th>
                                                            <th class="col-xs-3">Date</th>
                                                            <th class="col-xs-3">Ticket Subject</th>
                                                            <th class="col-xs-3">Category</th>
                                                            <th class="col-xs-2">Company Name</th>
                                                            <th class="col-xs-1">Status</th>
                                                            <th class="col-xs-2">Actions</th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php if($tickets_count > 0) { ?>
                                                        <?php foreach ($tickets as $ticket) { ?>
                                                            <tr>
                                                                <td><?php echo str_pad($ticket['sid'], 5, '0', STR_PAD_LEFT); ?></td>
                                                                <td><?php echo date_with_time($ticket['updated_date']); ?></td>
                                                                <td><?php echo $ticket['subject']; ?></td>
                                                                <td><?php echo $ticket['ticket_category']; ?></td>
                                                                <td><?php echo $ticket['company_name']; ?></td>
                                                                <td <?php if($ticket['status'] == 'Awaiting Response'){?>class="Unpaid"<?php } else if($ticket['status'] == 'Answered' || $ticket['status'] == 'Closed') { ?>class="Paid"<?php } else if($ticket['status'] == 'Feedback Required') { ?>class="custom-warning-text"<?php } ?>><?php echo $ticket['status']; ?></td>
                                                                <td><a class="hr-edit-btn" href="<?php echo base_url('manage_admin/support_tickets/view_ticket').'/'.$ticket['sid']; ?>">View</a></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan='7'>No tickets found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
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