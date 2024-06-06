<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="card_div">
                        <div class="dashboard-conetnt-wrp">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('notification_emails'); ?>"><i class="fa fa-chevron-left"></i>Notification Email Management</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="pull-left">
                                        <h4>
                                            <?php echo PAYROLL_NOTIFICATIONS_MESSAGE; ?>
                                        </h4>
                                    </div>    
                                </div>    
                            </div>
                            <div class="btn-wrp">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                        <div class="panel panel-success">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                        <div class="pull-left">
                                                            <h4 style="margin-top: 6px; margin-bottom: 0;" class="text-success" style="font-size: 18px;"><strong>Send Notification Emails: </strong> <span class="<?php echo ($current_notification_status == 1 ? 'Paid' : 'Unpaid'); ?>" style="font-size: 18px;"><?php echo ($current_notification_status == 1 ? 'Enabled' : 'Disabled'); ?></span></h4>                                                          
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <form action="<?php echo current_url(); ?>" id="form_set_notifications_status" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="set_notifications_status" />
                                                            <input type="hidden" id="notifications_status" name="notifications_status" value="<?php echo ($current_notification_status == 1 ? 0 : 1); ?>" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                            <?php if($current_notification_status == 1) { ?>
                                                                <button type="button" onclick="func_set_notifications_status('Disable', '<?php echo $title_for_js_dialog; ?>'); " class="btn btn-danger btn-block">Disable Notifications</button>
                                                            <?php } else { ?>
                                                                <button type="button" onclick="func_set_notifications_status('Enable', '<?php echo $title_for_js_dialog; ?>');" class="btn btn-success btn-block">Enable Notifications</button>
                                                            <?php } ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <div class="panel panel-success">
                                            <div class="panel-body">
                                                <?php if($notification_type != 'approval_management') { ?>
                                                        <a href="javascript:;" id="add_new_notification" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Non Employee Notification Email</a>
                                                <?php } ?>
                                                <a href="javascript:;" id="add_new_notification_employee" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Employee Notification Email</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="table-responsive table-outer">
                                <table class="table table-bordered table-stripped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-2">Contact Name</th>
                                            <!-- <th class="col-xs-2">Short Description</th>-->
                                            <th class="col-xs-3">Email Address</th>
                                            <!--<th class="col-xs-2">Phone Number</th>-->
                                            <th class="col-xs-1 text-center">Is Employee</th>
                                            <th class="col-xs-1 text-center">Status</th>
                                            <th class="col-xs-1 text-center" colspan="2">Action</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <?php   if(empty($notifications_emails)) { ?>4
                                                <tr>
                                                    <td colspan="6">
                                                        <h3 class="text-center">No Email Record Found!</h3>
                                                    </td>
                                                </tr>
                                    <?php   } else { ?>
                                    <?php       foreach ($notifications_emails as $notifications_email) { ?>
                                                    <tr id="tr_row_<?php echo $notifications_email['sid'] ?>">
                                                        <td><?php echo $notifications_email["contact_name"]; ?><?php if($notifications_email['employer_sid'] != 0 || $notifications_email['employer_sid'] == ''){?><br/><?php echo remakeEmployeeName(array("access_level_plus"=>$notifications_email['access_level_plus'],"pay_plan_flag"=>$notifications_email['pay_plan_flag'],"access_level"=>$notifications_email['access_level'],"is_executive_admin"=>$notifications_email['is_executive_admin'],"job_title"=>$notifications_email['job_title']), false)?><?php } ?> </td>
                                                        <!--<td><?php echo $notifications_email["short_description"]; ?> </td>-->
                                                        <td><?php echo $notifications_email["email"]; ?> </td>
                                                        <!--<td><?php echo $notifications_email["contact_no"]; ?></td>-->
                                                        <td><?php echo ($notifications_email['employer_sid'] == 0) ? 'No' : 'Yes'; ?></td>
                                                        <td class="text-center">
                                                            <?php echo ($notifications_email['status'] == 'active' ? '<span class="Paid">Active</span>' : '<span class="Unpaid">In-Active</span>'); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo base_url('notification_emails/edit_contact') . '/' . $notifications_email['sid'] . '/' . $notification_type; ?>" class="btn btn-success btn-sm" title="Edit Contact"><i class="fa fa-pencil"></i></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <a data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm" title="Remove Contact" onclick="remove_contact(<?php echo $notifications_email['sid'] ?>)"><i class="fa fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                    <?php       } ?>
                                    <?php   } ?>                                           
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="row">                        
                        <div class="add-credit-card-container payment-area-wrp">
                            <?php echo form_open('', array('id' => 'notifications_form')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $sub_title; ?></span>
                                </div>
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-50-left">
                                                <label for="contact_name">Contact Name <span class="staric">*</span></label>
                                                <input type="text" id="contact_name" name="contact_name" value="" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label for="contact_name">Contact Number</label>
                                                <input type="text" id="contact_no" name="contact_no" value="" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100">
                                                <label for="short_description">Short Description</label>
                                                <input type="text" name="short_description" value="" class="invoice-fields">
                                            </li> 
                                            <li class="form-col-100">
                                                <label for="email">E-Mail Address <span class="staric">*</span></label>
                                                <input type="text" name="email" value="" class="invoice-fields">								
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                    Enable Emails <small class="text-success">Check to enable email notifications to this contact.</small>
                                                    <input class="status" id="status" name="status" value="1" type="checkbox" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" id="perform_action" name="perform_action" value="add_notification_email" />
                                                <input type="hidden" name="notifications_type" value="<?php echo $notification_type; ?>">
                                                <input type="submit" value="Save"  onclick="return validate_form()"class="submit-btn">
                                                <input type="button" value="Cancel" id="cancel_button_employee" class="submit-btn btn-cancel"/>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>



                    <div class="row">                        
                        <div class="add-credit-card-container employee-area">
                            <?php echo form_open('', array('id' => 'notifications_form_employee')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $sub_title; ?></span>
                                </div>
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100 autoheight" id="to_employees_div">
                                                <div class="row">
                                                    <div class="col-md-3"><b>Select Employee</b></div>
                                                    <div class="col-md-6">
                                                        <div class="field-row">
                                                            <?php if (sizeof($employees) > 0) { ?>
                                                                <select class='invoice-fields' name='employee' id='employee'>
                                                                    <?php foreach ($employees as $employee) { ?>
                                                                        <option value="<?php echo $employee['sid']; ?>" <?php echo (isset($emp_id) && ($emp_id == $employee['sid'])) ? 'selected' : ''; ?>>
                                                                            <?php echo $employee['employee_name'];  ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <p>No Employee Found.</p>
                                                            <?php } ?>
                                                        </div>
                                                        <!--<div class="col-sm-7 field-row" id='employee_error'>
                                                            <?php //echo form_error('employee'); ?>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="notifications_type" value="<?php echo $notification_type; ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="add_notification_employee" />
                                                <input type="submit" value="Save" class="submit-btn">
                                                <input type="button" value="Cancel" id="cancel_button" class="submit-btn btn-cancel"/>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>




                    
                </div>          
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function func_set_notifications_status(status, notifications) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to "' + status + '" "' + notifications + '" ?',
            function () {
                $('#form_set_notifications_status').submit();
            }, function () {
                alertify.warning('Canceled!');
            });
    }

    function remove_contact(id){
        url = "<?php echo base_url('notification_emails/ajax_responder') ?>";
        alertify.confirm('Confirmation', "Are you sure you want to remove this contact?",
                function () {
                    $.post(url, {perform_action: 'delete_contact', sid: id})
                            .done(function (data) {
                                $('#tr_row_' + id).hide();
                                alertify.success('Contact removed successfully');
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }
    
    function validate_form() {
        $("#notifications_form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                contact_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    checkExists: true
                }
            },
            messages: {
                contact_name: {
                    required: 'Contact Name is required!'
                },
                email: {
                    required: 'Email Address is required!'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    
    $("#add_new_notification").click(function () {
        $('.payment-area-wrp').fadeIn();
        $('.card_div').hide();
        $('.employee-area').hide();
    });
    
    $("#add_new_notification_employee").click(function () {
        $('.payment-area-wrp').hide();
        $('.card_div').hide();
        $('.employee-area').fadeIn();
    });

    $("#cancel_button, #cancel_button_employee").click(function () {
        $('.card_div').fadeIn();
        $('.payment-area-wrp').hide();
        $('.employee-area').hide();
    });
   
    $(document).ready(function(){         
        $.validator.addMethod("checkExists", function(value, element){
            var inputElem = $('#notifications_form :input[name="email"]');
            data = { "email" : inputElem.val(),
                     "perform_action": "checkuniqueemail",
                     "notifications_type": "<?php echo $notification_type;?>"
                   };

            var my_response =   $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('notification_emails/ajax_responder') ?>",
                                async: false,
                                data: data
                            });
                            //console.log(my_response);
                               if(my_response.responseText=='exists'){
                                   return false;
                               } else {
                                   return true;
                               }   
    }, 'The Email Address already exists in the module');
 });
 $(document).ready(function() { 
        $("#employee").select2();
        });
</script>
<style>
.select2-container--default .select2-selection--single {
    background-color: #fff !important;
    border: 1px solid #aaa !important;
    padding:5px !important;
    border-radius: 4px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 12px !important;
}
</style>