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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php if(in_array('full_access', $security_details) || in_array('add_subaccount', $security_details)){ ?>
                                        <div class="add-new-promotions">
                                            <a href="<?php echo site_url('manage_admin/users/add_subaccount'); ?>" class="site-btn">Add Sub-Account</a>
                                        </div>
                                    <?php } ?>
                                    <div class="hr-promotions table-responsive">
                                        <table class="table table-bordered table-stripped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-2">Name</th>
                                                    <th class="col-xs-2">User Name</th>
                                                    <th class="col-xs-3">Email</th>
                                                    <th class="col-xs-3">Last Login</th>
                                                    <?php if(check_access_permissions_for_view($security_details, 'add_subaccount')){ ?>
                                                        <th class="col-xs-2 text-center" colspan="6">Actions</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($users)) { ?>
                                                    <?php foreach($users as $user) { 
                                                        $no_password = (!$user->password || $user->password == '' || is_null($user->password)) ? false: false;
                                                    ?>
                                                        <tr id="<?php echo 'users_' . $user->id; ?>">
                                                            <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                                            <td><?php echo $user->username; ?></td>
                                                            <td><?php echo $user->email; ?></td>
                                                            <td><?php echo $user->last_login != NULL && !empty($user->last_login) ? date('M d Y, D H:i:s', $user->last_login) : 'N/A'; ?></td>

                                                            <?php if(check_access_permissions_for_view($security_details, 'add_subaccount')) { ?>
                                                                <?php if($current_user->id != $user->id && $user->id != 1) { ?>
                                                                    <td><?php if (!$user->password || $user->password == '' || is_null($user->password)) { ?>
                                                                            <img
                                                                                src="<?= base_url('assets/manage_admin/images/bulb-red.png') ?>">
                                                                        <?php } else { ?>
                                                                            <img
                                                                                src="<?= base_url('assets/manage_admin/images/bulb-green.png') ?>">
                                                                        <?php } ?></td>
                                                                    <td class="col-xs-1 text-center">
                                                                    <?php if(check_access_permissions_for_view($security_details, 'send_user_login_request')) {?>
                                                                        <!-- Updated on: 30-04-2019 -->
                                                                        <?php //if (!$user->password || $user->password == '' || is_null($user->password)) { ?>
                                                                            <a href="javascript:;"
                                                                               class="send_credentials"
                                                                               title="Send Login Request"
                                                                               data-attr="<?php echo $user->id; ?>"><span
                                                                                    class="btn btn-sm btn-<?=$no_password ? 'warning' : 'success';?>"><i
                                                                                        class="fa fa-envelope"></i></span></a>
                                                                        <?php //}
                                                                    }?>
                                                                    </td>
                                                                    <td class="col-xs-1 text-center">
                                                                        <?php echo anchor('manage_admin/users/edit_profile/' . $user->id, '<span class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></span>', array('title' => 'Edit Employee')); ?>
                                                                    </td>
                                                                    <td class="col-xs-1 text-center">
                                                                        <form
                                                                            id="form_delete_user_<?php echo $user->id; ?>"
                                                                            enctype="multipart/form-data" method="post"
                                                                            action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action"
                                                                                   name="perform_action"
                                                                                   value="delete_user"/>
                                                                            <input type="hidden" id="user_id"
                                                                                   name="user_id"
                                                                                   value="<?php echo $user->id; ?>"/>
                                                                            <button type="button"
                                                                                    onclick="func_delete_user(<?php echo $user->id; ?>);"
                                                                                    title="Delete Employee"
                                                                                    class="btn btn-sm btn-danger"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </form>
                                                                    </td>
                                                                    <td class="col-xs-1 text-center">
                                                                        <?php if ($user->active == 1) { ?>
                                                                            <form
                                                                                id="form_deactivate_user_<?php echo $user->id; ?>"
                                                                                enctype="multipart/form-data"
                                                                                method="post"
                                                                                action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action"
                                                                                       name="perform_action"
                                                                                       value="deactivate_user"/>
                                                                                <input type="hidden" id="user_id"
                                                                                       name="user_id"
                                                                                       value="<?php echo $user->id; ?>"/>
                                                                                <button type="button"
                                                                                        onclick="func_deactivate_user(<?php echo $user->id; ?>);"
                                                                                        title="Enable Employee"
                                                                                        class="btn btn-sm btn-danger"><i
                                                                                        class="fa fa-ban"></i></button>
                                                                            </form>
                                                                        <?php } elseif ($user->active == 0) { ?>
                                                                            <form
                                                                                id="form_activate_user_<?php echo $user->id; ?>"
                                                                                enctype="multipart/form-data"
                                                                                method="post"
                                                                                action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action"
                                                                                       name="perform_action"
                                                                                       value="activate_user"/>
                                                                                <input type="hidden" id="user_id"
                                                                                       name="user_id"
                                                                                       value="<?php echo $user->id; ?>"/>
                                                                                <button type="button"
                                                                                        onclick="func_activate_user(<?php echo $user->id; ?>);"
                                                                                        title="Disable Employee"
                                                                                        class="btn btn-sm btn-success">
                                                                                    <i class="fa fa-shield"></i>
                                                                                </button>
                                                                            </form>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if (in_array('full_access', $security_details) || in_array('admin_user_login', $security_details)) { ?>
                                                                        <td><input class="btn btn-success btn-sm"
                                                                                   type="button" id="<?= $user->id ?>"
                                                                                   onclick="return employerLogin(this.id,'<?= $user->username ?>')"
                                                                                   value="Login"></td>
                                                                    <?php }
                                                                }else {  ?>
                                                                    <td colspan="5"></td>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
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
    </div>
</div>
<script>
    function func_delete_user(user_id) {
        alertify.confirm('Confirmation', "Are you sure you want to delete this User ?",
        function () {
           $('#form_delete_user_' + user_id).submit();
        },
        function () {
            alertify.error('Canceled');
        });
    }

    function func_activate_user(user_id) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to activate this user?',
            function () {
                $('#form_activate_user_' + user_id).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function func_deactivate_user(user_id) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to deactivate this user?',
            function () {
                $('#form_deactivate_user_' + user_id).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    $(document).on('click','.send_credentials',function(e) {
        var sid = $(this).attr('data-attr');
        var url = "<?= base_url() ?>manage_admin/users/send_login_credentials";
        alertify.confirm('Confirmation', "Are you sure you want to send Login Email?",
            function () {
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        sid: sid
                    },
                    success: function(data) {
                        if(data == 'success') {
                            alertify.success('Email with generate password link is sent.');
                        } else {
                            alertify.error('there was error');
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
    
    function employerLogin(userId,username) {
//        alertify.success('in-progress');
        url_to = "<?= base_url()?>manage_admin/users/employer_login";
        $.post(url_to, {sid: userId, username: username})
        .done(function(){
                window.location.href = "<?= base_url('manage_admin/') ?>";
//            window.open("<?//= base_url('manage_admin/') ?>//", '_blank');
        });
    }
</script>
