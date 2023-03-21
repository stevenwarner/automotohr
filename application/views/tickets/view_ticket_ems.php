<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="main jsmaincontent">
    <div class="container-fluid">
    <div class="row">
            <div class="col-sm-12">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h1 class="section-ttile">View Support Ticket :</h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="panel-group ticket-group" id="accordion" role="tablist" aria-multiselectable="false">
                            <?php if ($messages_count > 0) { ?>
                                <?php foreach ($messages as $message) { ?>
                                    <div class="panel panel-default ticket-panel">
                                        <div class="panel-heading" data-toggle="collapse" data-parent="" href="#collapse<?php echo $message['sid']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $message['sid']; ?>">
                                            <h3 class="panel-title"><?php echo $message['employee_name']; ?>
                                                <small class="pull-right"><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this)); ?></small>
                                            </h3>
                                        </div>
                                        <div id="collapse<?php echo $message['sid']; ?>" class="panel-collapse collapse" role="tabpanel">
                                            <div class="panel-body"><?php echo $message['message_body']; ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">No messages found.</h3>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="panel panel-default" id="reply">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-lock"></i> This ticket was Answered. If the problem persists or was not remedied, please <a href="javascript:;" id="reopen"><u>Reopen the Ticket</u></a>.</h3>
                                </div>
                            </div>
                            <div class="panel panel-default" id="post_reply">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-uppercase">Post a Reply
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <form id="new_message" action="<?php echo base_url('support_tickets/add_new_ticket_message') . '/' . $ticket['sid']; ?>" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Message : <span class="staric">*</span></label>
                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <textarea class="ckeditor" name="message_body" id="message_body" cols="60" rows="10"><?php echo set_value('message_body'); ?></textarea>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Attachment : </label>
                                            <input class="" type="file" name="document" id="document" />
                                            <?php echo form_error('document'); ?>
                                            <script>
                                                $(document).ready(function(){
                                                    $('#document').filestyle({
                                                        btnClass: 'btn-info'
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input type="submit" value="Submit" class="btn btn-info" id="new_message_submit" />
                                                <a class="btn btn-default" href="<?php echo base_url('support_tickets'); ?>">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if(!empty($files)) { ?>
                            <div class="table-responsive">
                                <h3>Attached Ticket Files</h3>
                                <div class="hr-document-list">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Attachment File Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($files as $file) { ?>
                                            <tr>
                                                <td><?php echo $file['saved_file_name']; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:;"
                                                       data-toggle="modal"
                                                       data-target="#document_<?php echo $file['sid'] ?>"
                                                       class="btn btn-info btn-sm action-btn enable-bs-tooltip"
                                                       title="View and Download">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>
