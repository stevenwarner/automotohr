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
                                        <h1 class="page-title"><i class="glyphicon glyphicon-user"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form method="post">
                                                   <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Contact Status</b></th>
                                                            <td>
                                                                <div class="candidate-status applicat-status-edit">
                                                                    <div class="label-wrapper-outer">
                                                                        <?php if($status_name != 'No Status Found!') {
                                                                            foreach ($application_status as $status_code) { 
                                                                        ?>
                                                                            <?php if ($status_code['css_class'] == $status) { ?>
                                                                                <div class="selected <?php echo $status ?>">
                                                                                    <?= $status_name ?>
                                                                                </div>
                                                                            <?php 
                                                                                } 
                                                                            ?>
                                                                        <?php
                                                                            }
                                                                       
                                                                        } else { ?>
                                                                            <div class="selected not_contacted">
                                                                                    <?= $status_name ?>
                                                                                </div>
                                                                        <?php  }
                                                                             ?>
                                                                        <div class="show-status-box" title="Edit Contact Status">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </div>
                                                                        <div class="lable-wrapper">
                                                                            <div id="id" style="display:none;"></div>
                                                                            <div style="height:20px;">
                                                                                <i class="fa fa-times cross"></i>
                                                                            </div>

                                                                            <?php 
                                                                                foreach ($application_status as $status) { 
                                                                            ?>
                                                                                    <div data-status_sid="<?php echo $status['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['css_class']; ?>" class="label applicant <?php echo $status['css_class']; ?>">
                                                                                        <div id="status"><?php echo $status['name']; ?></div>
                                                                                        <i class="fa fa-check-square check"></i>
                                                                                    </div>
                                                                            <?php
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php //echo '<pre>'; print_r($affiliation); echo '</pre>'; ?>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Contact Name</b></th>
                                                            <td><?php echo ucfirst($affiliation['first_name']); ?> <?php echo ucfirst($affiliation['last_name']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Contact Number</b></th>
                                                            <td><?=phonenumber_format($affiliation['contact_number']);?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Email Address</b></th>
                                                            <td><?php echo $affiliation['email']; ?></td>
                                                        </tr>
                                                        <?php  if($uri_segment == "affiliates") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Paypal Email</b></th>
                                                                <td><?php echo $affiliation['paypal_email']; ?></td>
                                                            </tr>
                                                        <?php  } ?> 
                                                        <tr>
                                                            <th class="col-xs-3"><b>Company</b></th>
                                                            <td><?php echo $affiliation['company'] != NULL && !empty($affiliation['company']) ? $affiliation['company'] : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Street Address</b></th>
                                                            <td><?php echo $affiliation['street']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>City</b></th>
                                                            <td><?php echo $affiliation['city']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>State</b></th>
                                                            <td><?php echo $affiliation['state']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Zip Code</b></th>
                                                            <td><?php echo $affiliation['zip_code']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Country</b></th>
                                                            <td><?php echo $affiliation['country'];?></td>
                                                        </tr>
                                                        <?php  if($uri_segment == "affiliates") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Method Of Promotion</b></th>
                                                                <td><?php echo $affiliation['method_of_promotion'];?></td>
                                                            </tr>
                                                        <?php  } ?>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Website</b></th>
                                                            <td><?php echo $affiliation['website'];?></td>
                                                        </tr>
                                                        <?php  if($uri_segment == "affiliates") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Email List</b></th>
                                                                <td><?php echo $affiliation['email_list'];?></td>
                                                            </tr>
                                                        <?php  } ?>
                                                        <?php  if($uri_segment == "affiliates") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Date Applied</b></th>
                                                                <td><?php echo date_with_time($affiliation['request_date']);?></td>
                                                            </tr>
                                                        <?php  } else { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Date Referred</b></th>
                                                                <td><?php echo date_with_time($affiliation['request_date']);?></td>
                                                            </tr>
                                                        <?php  } ?>
                                                        <?php  if($uri_segment == "affiliates") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Anything else you want us to know</b></th>
                                                                <td><?php echo $affiliation['special_notes'];?></td>
                                                            </tr>
                                                        <?php  } ?>
                                                        <?php  if($uri_segment == "referred") { ?>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Referred By</b></th>
                                                                <td><?php echo $affiliation['full_name'];?></td>
                                                            </tr>
                                                        <?php  } ?>
                                                        <tr>
                                                            <th class="col-xs-3"><b>W9-Form</b></th>
                                                            <td><?php   if($affiliation['w9_form'] != NULL && !empty($affiliation['w9_form'])) { ?>
                                                                            <a data-toggle="modal" onclick="fLaunchModalForms(this);" data-preview-url="<?php echo $affiliation['w9_form']; ?>" data-download-url="<?php echo $affiliation['w9_form']; ?>" data-document-title="<?php echo 'W9 Form for '.ucfirst($affiliation['first_name']).' '.ucfirst($affiliation['last_name']); ?>" href="javascript:void(0);">View W9-Form</a>
                                                                <?php   } else {
                                                                            echo 'N/A';
                                                                        } ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>W8-Form</b></th>
                                                            <td><?php   if($affiliation['w8_form'] != NULL && !empty($affiliation['w8_form'])) { ?>
                                                                            <a data-toggle="modal" onclick="fLaunchModalForms(this);" data-preview-url="<?php echo $affiliation['w8_form']; ?>" data-download-url="<?php echo $affiliation['w8_form']; ?>" data-document-title="<?php echo 'W8 Form for '.ucfirst($affiliation['first_name']).' '.ucfirst($affiliation['last_name']); ?>" href="javascript:void(0);">View W8-Form</a>
                                                                <?php   } else {
                                                                            echo 'N/A';
                                                                        } ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>IP Address</b></th>
                                                            <td><?php echo $affiliation['ip_address'] != NULL ?  $affiliation['ip_address'] : 'N/A';?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>Timezone</b></th>
                                                            <td><?php echo $affiliation['timezone'] != NULL ?  get_timezones($affiliation['timezone'], 'name') : 'N/A';?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3"><b>User Agent</b></th>
                                                            <td><?php echo $affiliation['user_agent'] != NULL ? $affiliation['user_agent'] : 'N/A';?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <a class="btn btn-success" href="<?php echo site_url('manage_admin/'.$uri_segment);?>">< Back</a>
                                                
                                                <?php if($affiliation['status'] == 2 || $affiliation['status'] == 0) { ?>
                                                    <?php if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_accept')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_accept'))) { ?>
                                                <a href="javascipt:;" id="<?= $affiliation['sid']?>" class="btn btn-success accept" title="Accept">Accept</a>
                                                    <?php } if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_reject')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_reject'))) { ?>
                                                <a href="javascipt:;" id="<?= $affiliation['sid']?>" class="btn btn-danger reject" title="Reject">Reject</a>

                                                <?php } } ?>

                                                <?php if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_edit')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_edit'))) { ?>
                                                    <a class="btn btn-primary " href="<?php echo site_url('manage_admin/'.$uri_segment.'/edit_details/'.$affiliation['sid']);?>">Edit Details</a>
                                                <?php } if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_send_reply')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_send_reply'))) { ?>
                                                    <a class="btn btn-success " href="<?php echo site_url('manage_admin/'.$uri_segment.'/send_reply/'.$affiliation['sid']);?>">Send Reply</a>
                                                <?php } ?>
                                                    <button class="btn btn-danger jsDeleteAffiliate" data-id="<?=$affiliation['sid'];?>">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <?php  if($uri_segment == "affiliates") { ?>
                                                <strong>Affiliation Reply:</strong>
                                            <?php  } else { ?>
                                                <strong>Referred Reply:</strong>
                                            <?php  } ?>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <form method="post" class="private-msg">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">Replied On</th>
                                                                <th class="text-center">Subject</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(sizeof($affiliate_reply)>0){
                                                                foreach($affiliate_reply as $reply){?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo !empty($reply["reply_date"]) && $reply["reply_date"] != '0000-00-00 00:00:00' ? my_date_format($reply["reply_date"]) : 'N/A'; ?></td>
                                                                        <td class="text-center"><?php echo $reply["subject"]; ?></td>
                                                                        <td class="text-center"><a href="<?php echo base_url('manage_admin/'.$uri_segment.'/view_reply'); ?>/<?= $reply["sid"] ?>"><input type="button" class="site-btn" value="View Message"></a></td>
                                                                    </tr>
                                                                <?php }
                                                            }else{?>
                                                                <tr>
                                                                    <td class="text-center" colspan="3">Not Replied Yet!</td>
                                                                </tr>
                                                            <?php }?>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Saved Notes:</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php if(!empty($notes)) { ?>
                                                        <ul class="list-group">
                                                            <?php foreach($notes as $note) { ?>
                                                                <li class="list-group-item">
                                                                    <div class="row">
                                                                        <div class="col-xs-10">
                                                                            <p><strong><?php echo $note['first_name'] . ' ' . $note['last_name']; ?></strong>&nbsp;<small>( <?php echo date_with_time($note['created_date'], true); ?> )</small></p>
                                                                            <div id="<?php echo 'note_'.$note['sid']; ?>"><?php echo $note['note_text']; ?></div>
                                                                        </div>
                                                                        <div class="col-xs-1">
                                                                            <a href="javascript:void(0);" id="edit-note" onclick="fLaunchModal(this);" data-id="<?php echo $note['sid']; ?>" data-attr="<?php echo '#note_'.$note['sid']; ?>" class="btn btn-success btn-block btn-sm"><i class="fa fa-pencil"></i></a>
                                                                        </div>
                                                                        <div class="col-xs-1">
                                                                            <form id="form_delete_note_<?php echo $note['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_note" />
                                                                                <input type="hidden" id="note_sid" name="note_sid" value="<?php echo $note['sid']; ?>" />
                                                                                <input type="hidden" id="demo_sid" name="affiliate_sid" value="<?php echo $note['affiliate_sid']; ?>" />

                                                                                <button onclick="func_delete_note(<?php echo $note['sid']; ?>);" type="button" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } else { ?>
                                                        <div class="text-center">
                                                            <span class="no-data">No Notes</span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <form id="form_add_new_note" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="add_new_note" />
                                                        <input type="hidden" id="affiliate_sid" name="affiliate_sid" value="<?php echo $message["sid"];?>" />
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Add New Note</label>
                                                            <textarea data-rule-required="true" id="note_text" name="note_text" cols="40" rows="10" class="hr-form-fileds field-row-autoheight enquiry-ckedit"></textarea>
                                                        </div>
                                                        <div class="">
                                                            <button type="submit" class="btn btn-success">Save Note</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Scheduled Reminders</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <?php if(!empty($scheduled_tasks))  { ?>
                                                <ul class="list-group">
                                                    <?php foreach($scheduled_tasks as $task) { ?>
                                                        <li class="list-group-item">
                                                            <div class="row">

                                                                    <?php if($task['schedule_type'] == 'call') { ?>
                                                                        <div class="col-xs-1 text-center">
                                                                            <h5><i style="font-size: 40px;" class="fa fa-phone"></i></h5>
                                                                        </div>
                                                                    <?php } else if($task['schedule_type'] == 'email') { ?>
                                                                        <div class="col-xs-1 text-center">
                                                                            <h5><i style="font-size: 40px;" class="fa fa-envelope"></i></h5>
                                                                        </div>
                                                                    <?php } else if($task['schedule_type'] == 'meeting') { ?>
                                                                        <div class="col-xs-1 text-center">
                                                                            <h5><i style="font-size: 40px;" class="fa fa-group"></i></h5>
                                                                        </div>
                                                                    <?php } else if($task['schedule_type'] == 'demo') { ?>
                                                                        <div class="col-xs-1 text-center">
                                                                            <h5><i style="font-size: 40px;" class="fa fa-laptop"></i></h5>
                                                                        </div>
                                                                    <?php } ?>

                                                                <div class="col-xs-9">
                                                                    <h5><strong><?php echo ucwords($task['schedule_type']); ?> ( <?php echo date('m/d/Y @ h:i A', strtotime($task['schedule_datetime'])); ?> )</strong></h5>
                                                                    <p><?php echo $task['schedule_description']; ?></p>
                                                                    <p class="<?php echo ($task['schedule_status'] == 'pending' ? 'text-danger' : 'text-success'); ?>">( <?php echo ucwords($task['schedule_status']); ?> )</p>
                                                                </div>
                                                                <div class="col-xs-2">
                                                                    <?php if($task['schedule_status'] == 'pending') { ?>
                                                                        <span style="margin-left: 5px;" class="pull-right">
                                                                            <form id="form_complete_schedule_record_<?php echo $task['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="complete_schedule_record" />
                                                                                <input type="hidden" id="schedule_sid" name="schedule_sid" value="<?php echo $task['sid']; ?>" />
                                                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $task['user_sid']; ?>" />
                                                                                <button type="button" onclick="func_complete_schedule_record(<?php echo $task['sid']; ?>); " class="btn btn-success btn-sm"><i class="fa fa-check-square"></i></button>
                                                                            </form>
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <span style="margin-left: 5px;" class="pull-right">
                                                                            <button type="button" onclick="javascript:void(0);" class="btn btn-success btn-sm disabled"><i class="fa fa-check-square"></i></button>
                                                                        </span>
                                                                    <?php } ?>
                                                                    &nbsp;
                                                                    <span style="margin-left: 5px;" class="pull-right">
                                                                        <form id="form_delete_schedule_record_<?php echo $task['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_schedule_record" />
                                                                            <input type="hidden" id="schedule_sid" name="schedule_sid" value="<?php echo $task['sid']; ?>" />
                                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $task['user_sid']; ?>" />
                                                                            <button type="button" onclick="func_delete_schedule_record(<?php echo $task['sid']; ?>); " class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                        </form>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } else { ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if (($uri_segment == "affiliates" && check_access_permissions_for_view($security_details, 'affiliate_request_create_schedule')) || ($uri_segment == "referred_affiliates" && check_access_permissions_for_view($security_details, 'referred_affiliate_create_schedule'))) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <strong>New Schedule</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="add-new-company">
                                                    <form id="form_add_new_scheduled_task" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="schedule_a_task" />
                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $message["sid"];?>" />
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label for="country">Schedule Type <span class="hr-required">*</span></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select data-rule-required="true" class="invoice-fields" id="schedule_type" name="schedule_type" >
                                                                            <option value="">Please Select</option>
                                                                            <option value="call">Call</option>
                                                                            <option value="demo">Demo</option>
                                                                            <option value="email">Email</option>
                                                                            <option value="meeting">Meeting</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label  for="schedule_datetime">Date and Time <span class="hr-required">*</span></label>
                                                                    <input data-rule-required="true" name="schedule_datetime" id="schedule_datetime" value="<?php echo date('m/d/Y H:00'); ?>" class="hr-form-fileds" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="field-row field-row-autoheight">
                                                                    <label for="schedule_description">Description</label>
                                                                    <textarea id="schedule_description" name="schedule_description" cols="40" rows="6" class="hr-form-fileds field-row-autoheight enquiry-ckedit"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <button type="submit" class="btn btn-success">Create</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
     function fLaunchModalForms(source) {
         var document_preview_url = $(source).attr('data-preview-url');
         var document_download_url = $(source).attr('data-download-url');
         var document_title = $(source).attr('data-document-title');
         var type = document_preview_url.split(".");
         var file_type = type[type.length - 1];
         var modal_content = '';
         var footer_content = '';
         var iframe_url = 'https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '&embedded=true';
         var is_document = false;

         if (document_preview_url != '') {
             if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                 modal_content = '<img src="<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '" style="width:100%; height:500px;" />';
             } else {
                 is_document = true;
                 modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
             }
            
             footer_content = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Download</a>';
         } else {
             modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
             footer_content = '';
         }

         $('#document_modal_body').html(modal_content);
         $('#document_modal_footer').html(footer_content);
         $('#document_modal_title').html(document_title);
         $('#file_preview_modal').modal("toggle");

         if (is_document) {
             document.getElementById('preview_iframe').contentWindow.location = iframe_url;
         }
     }

    function fLaunchModal(source) {
        var element_id = $(source).attr('data-attr');
        var get_element = $(element_id).html();
        var modal_content = '<label>Edit Notes</label><form id="form_edit_note" method="post" action="<?php echo current_url(); ?>"><input type="hidden"name="perform_action" value="edit_new_note"/><input type="hidden" name="affiliate_sid" value="'+$(source).attr('data-id')+'"/><textarea id="edit-modal" name="note_text" cols="40" rows="10" class="hr-form-fileds field-row-autoheight edit-modal">'+get_element+'</textarea><div class="btn-enquiry-note"><button type="submit" class="btn btn-success">Save Note</button></div></form>';
        var footer_content = '';
        $('#document_modal_body').html(modal_content);
//        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html('Edit Enquiry Notes');
        $('#file_preview_modal').modal("toggle");
        CKEDITOR.replace('edit-modal');

    }

    function func_delete_note(note_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this note?',
            function () {
                $('#form_delete_note_' + note_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_complete_schedule_record(schedule_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Mark Schedule Task as Completed?',
            function () {
                $('#form_complete_schedule_record_' + schedule_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_schedule_record(schedule_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete Schedule request?',
            function () {
                $('#form_delete_schedule_record_' + schedule_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }
    
    $(document).ready(function () {
        $('#form_add_new_scheduled_task').validate();
        $('#schedule_datetime').datetimepicker({
                format: 'm/d/Y H:i',
                minDate: new Date()
            });
        CKEDITOR.replace('schedule_description');

        $('#form_add_new_note').validate();
        CKEDITOR.replace('note_text');

        $('.show-status-box').click(function () {
            $(this).next().show();
        });

        $('.selected').click(function () {
            $(this).next().next().css("display", "block");
        });

        $('.candidate').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            //var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();
            var status_name = $(this).attr('data-status_name');
            var message_sid = '<?php echo $affiliation['sid']?>';
            var my_url = "<?= base_url('manage_admin/'.$uri_segment.'/ajax_handler')?>";
            var my_request;

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: {
                    "id": id,
                    "status": status_name,
                    "message_sid": message_sid,
                    "action": "ajax_update_status"}
            });

            my_request.done(function (response) {
                if (response == 'success') {
                    alertify.success("Contact status updated successfully.");
                } else {
                    alertify.error("Could not update Contact Status.");
                }
            });
        });

        $('.applicant').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });
        
        $('.accept').click(function(){
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Accept this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:1,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/'.$uri_segment.'/accept_reject')?>',
                        success: function(data){
                            if(data == 'exist'){
                                window.location.href = '<?php echo base_url('manage_admin/'.$uri_segment.'/view_details/')?>/' + id;
                            }else{
                                window.location.href = '<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/')?>/' + data;
                            }
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
        
        $('.reject').click(function(){ 
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Reject this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:2,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/'.$uri_segment.'/accept_reject')?>',
                        success: function(data){
                            location.reload();
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });

    }); 
</script>
<script src="<?php echo base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.datetimepicker.css') ?>"/>

<script>
    $(function(){
        // Deletes an affiliate request
        $('.jsDeleteAffiliate').click(function() {
            //
            var affiliateDeleteId = $(this).data('id');
            //
            alertify.confirm('Confirmation', "This action is non-revertible. <br> Are you sure you want to delete this affiliate?",
                function() {
                    //
                    DeleteAffiliateRequest(affiliateDeleteId);
                }, function(){});
        });

        //
        function MarkDone() {
            //
            $('#loader_text_div').text('');
            //
            $('#document_loader').hide();
            //
            return alertify.alert('Affiliate is successfully deleted', function() {
                location.reload();
            });
        }

        //
        function DeleteAffiliateRequest(id) {
            //
            $('#loader_text_div').text('Please wait, while we process your request.');
            $('#document_loader').show();
            //
            $.ajax({
                type: 'POST',
                data: {
                    id: id
                },
                url: '<?= base_url('manage_admin/' . $uri_segment . '/delete_affiliate') ?>',
                success: function() {
                    MarkDone();
                }
            });
        }
    });
</script>