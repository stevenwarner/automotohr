<div class="main jsmaincontent">
    <div class="container-fluid">
    <div class="row">
            <div class="col-sm-12">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                </a>
            </div>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h1 class="section-ttile">Add Support Ticket</h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <form id="add_new_ticket" class="msform" action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Select Ticket Category : </label>
                                        <select class="form-control" name="ticket_category" id="ticket_category">
                                            <option value="Technical Inquiry" <?php if (set_value('ticket_category') == "Technical Inquiry") { ?>selected<?php } ?>>Technical Inquiry</option>
                                            <option value="Sales Support" <?php if (set_value('ticket_category') == "Sales Support") { ?>selected<?php } ?>>Sales Support</option>
                                            <option value="Other" <?php if (set_value('ticket_category') == "Other") { ?>selected<?php } ?>>Other</option>
                                        </select>
                                        <?php echo form_error('ticket_category'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="custom_ticket_cat">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Please Specify Category : <span class="staric">*</span></label>
                                        <input type="text" class="form-control" name="custom_category" id="custom_category" value="<?php echo set_value('custom_category'); ?>">
                                        <?php echo form_error('custom_category'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Subject : <span class="staric">*</span></label>
                                        <input type="text" class="form-control" name="subject" id="subject" value="<?php echo set_value('subject'); ?>">
                                        <?php echo form_error('subject'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Message : <span class="staric">*</span></label>
                                        <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <textarea class="ckeditor" name="message_body" id="message_body" cols="60" rows="10"><?php echo set_value('message_body'); ?></textarea>
                                        <?php echo form_error('message_body'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="submit" value="Submit" onclick="" class="btn btn-info" id="add_ticket_submit">
                                    <a class="btn btn-default" href="<?php echo base_url('support_tickets'); ?>">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/employee_hub_right_menu'); ?>
            </div>
        </div>
    </div>
</div>


