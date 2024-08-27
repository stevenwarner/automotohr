<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <span class="page-title"><i class="fa fa-users"></i>Manage Executive Administrator</span>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/executive_administrators'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Executive Administrators</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <span class="page-title"><?php echo $page_title; ?></span>
                                            <a href="<?php echo base_url('manage_admin/companies/edit_executive_administrator') . '/' . $administrator['sid']; ?>" class="btn btn-success pull-right">Edit Executive Administrator</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <strong>Executive Admin Details</strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-stripped table-hover table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th class="col-xs-3">First Name</th>
                                                                                <td><?php echo $administrator['first_name']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Last Name</th>
                                                                                <td><?php echo $administrator['last_name']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Username</th>
                                                                                <td><?php echo $administrator['username']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Email</th>
                                                                                <td><?php echo $administrator['email']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Access Level</th>
                                                                                <td><span class="Paid"><?php echo $administrator['access_level']; ?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Alternative Email</th>
                                                                                <td><?php echo $administrator['alternative_email']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Job Title</th>
                                                                                <td><?php echo $administrator['job_title']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Direct Business Number</th>
                                                                                <td><?php echo $administrator['direct_business_number']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Cell Number</th>
                                                                                <td><?= phonenumber_format($administrator['cell_number']); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Gender</th>
                                                                                <td><?php echo ucfirst($administrator['gender']); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Password Generated</th>
                                                                                <td><?php if ($administrator['password'] != NULL) echo 'Generated';
                                                                                    else echo 'Not Generated'; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Timezone</th>
                                                                                <td><?= get_timezones($administrator['timezone'], 'name'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Status</th>
                                                                                <td><?php echo ($administrator['active'] == '1' ? '<span class="Paid">Active</span>' : '<span class="Unpaid">Inactive</span>'); ?></td>
                                                                            </tr>
                                                                            <!--                                                                        <tr>
                                                                            <th class="col-xs-3">Corporate Group</th>
                                                                            <td>
                                                                                <?php //echo (empty($automotive_group_details) ? 'No Group Assigned' : ucwords($automotive_group_details['group_name'])); 
                                                                                ?>
                                                                            </td>
                                                                        </tr>-->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <span class="page-title">Manage Executive ComplyNet</span>
                                            <a href="javascript:;" id="change-status" class="btn <?php echo $administrator['complynet_status'] ? 'btn-danger' : 'btn-success'; ?> pull-right" data-attr="<?php echo $administrator['complynet_status']; ?>" data-key="<?php echo $administrator['sid']; ?>"><?php echo $administrator['complynet_status'] ? 'Disable' : 'Enable'; ?></a>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" style="padding-bottom: 25px">
                                                        <strong>ComplyNet Details</strong>
                                                        <input type="button" name="save" id="<?= $administrator['sid']; ?>" value="Save" class="btn btn-success save_cred pull-right">
                                                    </div>
                                                    <?php
                                                    $username = '';
                                                    $password = '';
                                                    //                                                    if(!empty($administrator['complynet_credentials']) && $administrator['complynet_credentials'] != NULL) {
                                                    $complynet_link = $administrator['complynet_dashboard_link'];
                                                    $complynet_data = unserialize($administrator['complynet_credentials']);
                                                    $username = $complynet_data['username'];
                                                    $password = $complynet_data['password'];
                                                    //                                                    }
                                                    ?>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-stripped table-hover table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th class="col-xs-3">Username</th>
                                                                                <td><input type="text" id="user_<?= $administrator['sid']; ?>" name="username" value="<?= $username ?>" class="form-control"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Password</th>
                                                                                <td><input type="text" id="pass_<?= $administrator['sid']; ?>" name="pass" value="<?= $password ?>" class="form-control"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">ComplyNet Dashboard Link</th>
                                                                                <td><input type="text" id="dashboard-link" name="pass" value="<?= $complynet_link ?>" class="form-control"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-3">Status</th>
                                                                                <td id="tbl-status" class="<?php echo $administrator['complynet_status'] ? 'text-success' : 'text-danger'; ?>"><b><?php echo $administrator['complynet_status'] ? 'Enabled' : 'Disabled'; ?></b></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <?php if (!empty($corporate_companies)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="heading-title">
                                                        <span class="page-title">Corporate Company Access Configuration</span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left col-xs-4">Corporate Company Name</th>
                                                                    <th class="text-left col-xs-4">Corporate Company URL</th>
                                                                    <th class="text-center col-xs-2">Has Access</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($corporate_companies as $corporate_company) { ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($corporate_company['CompanyName']); ?></td>
                                                                        <td><?php echo $corporate_company['sub_domain']; ?></td>
                                                                        <td class="exec-admin-access text-center">
                                                                            <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                                                <?php $corporate_company_sid = $corporate_company['sid'];
                                                                                $exec_admin_has_corp_co_access = FALSE;

                                                                                if (in_array($corporate_company_sid, $access_companies)) {
                                                                                    $exec_admin_has_corp_co_access = TRUE;
                                                                                } ?>
                                                                                <input <?php echo set_checkbox('has_corporate_company_access', 1, $exec_admin_has_corp_co_access); ?> class="has_access" id="has_corporate_company_access_<?php echo $corporate_company_sid; ?>" data-company-sid="<?php echo $corporate_company_sid; ?>" data-executive-admin-sid="<?php echo $administrator['sid']; ?>" name="has_corporate_company_access" value="1" type="checkbox">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($standard_companies)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="heading-title">
                                                        <h1 class="page-title">Regular Company Access Configuration</h1>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped table-hover table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left col-xs-4">Corporate Company Name</th>
                                                                    <th class="text-left col-xs-4">Corporate Company URL</th>
                                                                    <th class="text-center col-xs-2">Has Access</th>
                                                                    <th class="text-center col-xs-2">Admin Plus</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                foreach ($standard_companies as $company) {


                                                                    $adminPlusData = get_executive_administrator_admin_plus_status($exec_admin_id, $company['sid']);
                                                                    $execAdminAccessLevelPlus = FALSE;
                                                                    if (!empty($adminPlusData)) {
                                                                        $execAdminAccessLevelPlus =  $adminPlusData['access_level_plus'] ? TRUE : FALSE;
                                                                    }

                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($company['CompanyName']); ?> <?php if ($company['company_status'] == 0) { ?> <label class="label label-danger" title="The store is closed." placement="top">
                                                                                    Closed
                                                                                </label><?php } ?></td>
                                                                        <td><?php echo $company['sub_domain']; ?></td>
                                                                        <td class="exec-admin-access text-center">

                                                                            <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                                                <?php $standard_company_sid = $company['sid'];
                                                                                $exec_admin_has_standard_co_access = FALSE;

                                                                                if (in_array($standard_company_sid, $access_companies)) {
                                                                                    $exec_admin_has_standard_co_access = TRUE;
                                                                                } ?>
                                                                                <input <?php echo set_checkbox('is_registered_in_ahr', 1, $exec_admin_has_standard_co_access); ?> class="has_access" id="has_access_<?php echo $corporate_company_sid; ?>" data-company-sid="<?php echo $standard_company_sid; ?>" data-executive-admin-sid="<?php echo $administrator['sid']; ?>" name="has_access" value="1" type="checkbox">
                                                                                <div class="control__indicator"></div>
                                                                            </label>



                                                                            <!--                                                                            <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                                                <?php //$is_default_checked = ( empty($company['executive_user_company']) ? false : true ) ; 
                                                                                ?>

                                                                                <?php //$registered_company_sid = $company['registered_company_info']['sid'] ; 
                                                                                ?>
                                                                                <input <?php //echo set_checkbox('is_registered_in_ahr', 1, $is_default_checked)
                                                                                        ?> class="has_access" id="has_access_<?php //echo $registered_company_sid; 
                                                                                                                                ?>" data-company-sid="<?php //echo $registered_company_sid; 
                                                                                                                                                        ?>" data-executive-admin-sid="<?php //echo $administrator['sid']; 
                                                                                                                                                                                        ?>" name="has_access" value="1" type="checkbox">
                                                                                <div class="control__indicator"></div>
                                                                            </label>-->
                                                                        </td>


                                                                        <td class="exec-admin-access text-center">
                                                                            <?php if ($exec_admin_has_standard_co_access == TRUE) { ?>
                                                                                <label id="lbl_is_admin_plus" class="control control--checkbox">
                                                                                    <?php $standard_company_sid = $company['sid'];
                                                                                    ?>
                                                                                    <input <?php echo set_checkbox('is_admin_plus', 1, $execAdminAccessLevelPlus); ?> class="is_admin_plus" id="is_admin_plus<?php echo $corporate_company_sid; ?>" data-company-sid="<?php echo $standard_company_sid; ?>" data-executive-admin-sid="<?php echo $administrator['sid']; ?>" name="access_level_plus" value="1" type="checkbox">
                                                                                    <div class="control__indicator"></div>
                                                                                </label>

                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
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
</div>
<script type="text/javascript">
    function func_validate_and_submit() {
        $('#form_update_corporate_company_access').validate();

        if ($('#form_update_corporate_company_access').valid()) {
            $('#form_update_corporate_company_access').submit();
        }
    }


    $(document).ready(function() {

        $('#change-status').click(function() {
            var status = $(this).attr('data-attr');
            var id = $(this).attr('data-key');
            $.ajax({
                type: 'POST',
                data: {
                    status: status,
                    id: id
                },
                url: '<?= base_url('manage_admin/companies/update_exec_comply_status') ?>',
                success: function(data) {
                    if (data == 1) {
                        $('#change-status').removeClass('btn-danger');
                        $('#change-status').addClass('btn-success');
                        $('#change-status').html('Enable');
                        $('#change-status').attr('data-attr', 0);

                        $('#tbl-status').removeClass('text-success');
                        $('#tbl-status').addClass('text-danger');
                        $('#tbl-status').html('<b>Disabled</b>');
                        alertify.success('Updated Successfully');
                    } else if (data == 0) {
                        $('#change-status').removeClass('btn-success');
                        $('#change-status').addClass('btn-danger');
                        $('#change-status').html('Disable');
                        $('#change-status').attr('data-attr', 1);


                        $('#tbl-status').removeClass('text-danger');
                        $('#tbl-status').addClass('text-success');
                        $('#tbl-status').html('<b>Enabled</b>');
                        alertify.success('Updated Successfully');
                    } else {
                        alertify.error('Something went wrong');
                    }
                },
                error: function() {

                }
            });
        });

        $('.save_cred').click(function() {
            var emp_id = $(this).attr('id');
            if ($('#user_' + emp_id).val() == '' || $('#pass_' + emp_id).val() == '') {
                alertify.error('Please Provide Username and Password');
                return false;
            }
            //            $.ajax({
            //                url: '<?php //echo base_url('manage_admin/companies/save_exec_complynet_cred');
                                    ?>//',
            //                type: 'POST',
            //                data:{
            //                    user: $('#user_'+emp_id).val(),
            //                    key:  $('#pass_'+emp_id).val(),
            //                    action:  credential,
            //                    emp:  emp_id
            //                },
            //                success: function(response){
            //                    window.location.href = window.location.href;
            //                },
            //                error: function(){
            //
            //                }
            //            });
            if ($('#dashboard-link').val() != '') {
                url = $('#dashboard-link').val();
                url_validate = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                if (!url_validate.test(url)) {
                    alertify.error('Please Enter a Valid Link');
                    return false;
                }
            }
            $.ajax({
                url: '<?php echo base_url('manage_admin/companies/save_exec_complynet_cred'); ?>',
                type: 'POST',
                data: {
                    link: $('#dashboard-link').val(),
                    user: $('#user_' + emp_id).val(),
                    key: $('#pass_' + emp_id).val(),
                    action: 'credential',
                    emp: emp_id
                },
                success: function(response) {
                    window.location.href = window.location.href;
                },
                error: function() {

                }
            });

        });

        $('#corporate_password').on('keyup', function() {
            var password_val = $(this).val();

            if (password_val != '') {

            } else {

            }
        });

        $('#access_corp_web_yes').on('click', function() {
            console.log($(this).val());
            if ($(this).prop('checked')) {
                $('#corporate_username').prop('disabled', false);
                $('#corporate_password').prop('disabled', false);
            }

        });

        $('#access_corp_web_no').on('click', function() {
            console.log($(this).val());
            if ($(this).prop('checked')) {
                $('#corporate_username').attr('disabled', 'disabled');
                $('#corporate_password').attr('disabled', 'disabled');
            }
        });


        $('.has_access').each(function() {
            $(this).on('click', function() {
                var is_checked = $(this).prop('checked');
                var company_sid = $(this).attr('data-company-sid');
                var executive_admin_sid = $(this).attr('data-executive-admin-sid');
                //console.log(company_sid);
                var myUrl = '<?php echo current_url(); ?>';
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: {
                        is_ajax_request: 1,
                        'company_sid': company_sid,
                        'executive_admin_sid': executive_admin_sid,
                        'perform_action': (is_checked == true ? 'enable_company_access' : 'disable_company_access')
                    }
                });

                myRequest.done(function(response) {
                    if (response == 'success') {
                        alertify.success('Success: Access Rights Successfully Updated!');
                    } else {
                        alertify.success('Error: something went wrong!');
                    }
                });
            });
        });



        $('input[type=radio]:checked').trigger('click');
    });


    function remove_company(sid, logged_in_sid) {
        alertify.confirm("Remove Admin Company", "Are you sure you want to remove this company from administrator?",
            function() {
                var myUrl = "<?= base_url('manage_admin/companies/executive_admin_company_remove_ajax') ?>";
                var myRequest;
                myRequest = $.ajax({
                    url: myUrl,
                    type: 'post',
                    data: {
                        sid: sid,
                        logged_in_sid: logged_in_sid
                    }
                });

                myRequest.done(function(response) {
                    if (response) {
                        $('#' + sid).hide();
                        alertify.notify('Administrator company removed successfully.', 'success');
                    } else {
                        alertify.error('An unknown error occurred. Please try again.');
                    }
                });
            },
            function() {
                alertify.error('Cancelled');
            });
    }




    //
    $('.is_admin_plus').each(function() {
        $(this).on('click', function() {
            var is_checked = $(this).prop('checked');
            var company_sid = $(this).attr('data-company-sid');
            var executive_admin_sid = $(this).attr('data-executive-admin-sid');

            var myUrl = '<?php echo current_url(); ?>';
            var myRequest = $.ajax({
                url: myUrl,
                type: 'POST',
                data: {
                    is_ajax_request: 1,
                    'company_sid': company_sid,
                    'executive_admin_sid': executive_admin_sid,
                    'perform_action': (is_checked == true ? 'mark_admin_plus' : 'unmark_admin_plus')
                }
            });

            myRequest.done(function(response) {
                if (response == 'success') {
                    alertify.success('Success: Admin Plus Status Successfully Updated!');
                } else {
                    alertify.success('Error: something went wrong!');
                }
            });
        });
    });
</script>