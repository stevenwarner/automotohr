<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a class="dashboard-link-btn" href="<?php echo base_url('task_management')?>"><i class="fa fa-chevron-left"></i>Task Management</a>
                            <?php echo $title; ?></span>
                    </div>
                    <div class="hr-box">
                        <div class="hr-innerpadding">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <th class="col-xs-4">Applicant Name</th>
                                    <td class="col-xs-8"><?php echo $applicant_details['first_name'] .' '. $applicant_details['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-4">Applicant Email</th>
                                    <td class="col-xs-8"><?php echo $applicant_details['email']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-4">Applicant Phone</th>
                                    <td class="col-xs-8"><?php echo $applicant_details['phone_number']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-4">Assigned By</th>
                                    <td class="col-xs-8"><?php echo $assignment_details[0]['notes_by_name']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-4">Assignment Date</th>
                                    <td class="col-xs-8"><?=reset_datetime(array('datetime' => $assignment_details[0]['note_datetime'], '_this' => $this)); ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-4">Applicant Profile</th>
                                    <td class="col-xs-8"><a href="<?php echo base_url('applicant_profile/' . $assignment_details[0]['applicant_sid']);?>" title="View <?php echo $applicant_details['first_name'] .' '. $applicant_details['last_name']; ?>" class="btn btn-success">View Applicant Profile</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="hr-box">
                        <div class="hr-box-header">
                            <strong>All Notes: </strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if(!empty($assignment_details)) { ?>
                                        <div class="list-group">
                                            <?php foreach ($assignment_details as $message) { ?>
                                                <div class="list-group-item text-left">
                                                    <strong>
                                                        <?php echo $message['notes_by_name']; ?>
                                                        <!-- <small>( <?php //echo date('m/d/Y h:i A', strtotime($message['note_datetime'])); ?> )</small> -->
                                                        <small>( <?=reset_datetime(array(
                                                            'datetime' => $message['note_datetime'],
                                                            '_this' => $this
                                                        ));?> )</small>
                                                    </strong>
                                                    <p><?php echo $message['note_txt']; ?></p>
                                                </div>
                                            <?php } ?>
                                        </div>
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
                        <div class="hr-box-header">
                            <strong>Add New Note </strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="universal-form-style-v2">
                                    <form id="form_new_note" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_new_note" />
                                        <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $assignment_details[0]['applicant_sid']; ?>" />
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <label for="note_txt">Note Text</label>
                                                <textarea name="note_txt" data-rule-required="true" rows="5" id="note_txt" class="invoice-fields-textarea"></textarea>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <button type="submit" class="btn btn-success">Add Note</button>
                                            </li>
                                        </ul>
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
<script>
$(document).ready(function(){
    $('#form_new_note').validate();
});
</script>