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
                                        
                        <?php           if(check_access_permissions_for_view($security_details, 'manage_executive_admins')) { ?>
                                            <a class="btn btn-success pull-right" href="<?php echo base_url() . 'manage_admin/companies/add_executive_administrator/' ?>">Add New Executive Administrator</a>
                        <?php           } ?>
                                    </div>
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <?php $name = $this->uri->segment(4) == 'all' ? '' : $this->uri->segment(4); ?>
                                                    <label>Contact Name</label>
                                                    <input type="text" name="name" id="name" value="<?php echo urldecode($name); ?>" class="invoice-fields">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <?php $email = $this->uri->segment(5) == 'all' ? '' : $this->uri->segment(5); ?>
                                                    <label>Email</label>
                                                    <input type="email" name="email" id="email" value="<?php echo urldecode($email); ?>" class="invoice-fields">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                    <label>&nbsp;</label>
                                                    <a id="search_btn" href="#" class="btn btn-success btn-block" style="padding: 9px;">Search</a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                    <label>&nbsp;</label>
                                                    <a id="clear" href="<?= base_url('manage_admin/companies/executive_administrators')?>" class="btn btn-success btn-block" style="padding: 9px;">Clear</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Contact Name</th>
                                                            <th>Username / Email</th>
                                                            <th>Job Title</th>
                                                            <?php $function_names = array('manage_executive_admins', 'login_executive_admin'); ?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                <th colspan="4" class="text-center">Actions</th>
                                                            <?php } ?>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php if(sizeof($administrators) > 0) { ?>
                                                        <?php foreach ($administrators as $admin) { ?>
                                                            <tr id='<?php echo $admin->sid; ?>'>
                                                                <td>
                                                                    <div class="employee-profile-info">
                                                                        <figure>
                                                                            <?php if (!empty($admin->profile_picture)) { ?>
                                                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $admin->profile_picture; ?>">
                                                                            <?php } else { ?>
                                                                                <img class="img-responsive" src="<?= base_url('assets/images/img-applicant.jpg') ?>">
                                                                            <?php } ?>
                                                                        </figure>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        echo $admin->first_name . ' ' . $admin->last_name; ?>
                                                                        <br> 
                                                                        <b>Reg Date:</b> <?php echo date_with_time($admin->created_on);?> 
                                                                        <br> 
                                                                        <b>Gender:</b> <?php echo !empty($admin->gender) ? ucfirst($admin->gender) : "N/A";
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $admin->username; ?><br><b><?php echo $admin->email; ?></b><br> <b>Access Level:</b> <?php echo $admin->access_level;?></td>
                                                                <td><?php echo $admin->job_title; ?>
                                                                    <?php   if($admin->password == '' || is_null($admin->password)) { ?>
                                                                                <img class="img-responsive" src="<?= base_url('assets/manage_admin/images/bulb-red.png') ?>">
                                                                                <?php       echo '<br><a href="javascript:;" class="btn btn-success btn-sm send_credentials" title="Send Login Credentials" data-attr="'.$admin->sid.'">Send Login Email</a>'; ?>
                                                                    <?php   } else { ?>
                                                                                <img class="img-responsive" src="<?= base_url('assets/manage_admin/images/bulb-green.png') ?>">
                                                                    <?php   } ?>
                                                                </td>

                                                                <?php if(check_access_permissions_for_view($security_details, 'manage_executive_admins')) { ?>
                                                                    <td>
                                                                        <a class="btn btn-success btn-sm" href="<?php echo base_url() . 'manage_admin/companies/manage_executive_administrators/' . $admin->sid; ?>">
                                                                            Manage
                                                                        </a>
                                                                    </td>
                                                                <?php } ?>

                                                                <?php if(check_access_permissions_for_view($security_details, 'manage_executive_admins')) { ?>
                                                                    <td>
                                                                        <a class="btn btn-danger btn-sm" href="javascript:;" id='delete' name='delete' onclick='delete_administrator(<?php echo $admin->sid; ?>);'>
                                                                            <i class="fa fa-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                <?php } ?>

                                                                <?php if(check_access_permissions_for_view($security_details, 'manage_executive_admins')) { ?>
                                                                <td>
                                                                    <a <?php if ($admin->active == '1') { ?> class="btn btn-danger btn-sm" <?php } else { ?> class="btn btn-success btn-sm" <?php } ?> href="javascript:;" id='activate_<?php echo $admin->sid; ?>' name='activate' onclick='activate_admin(<?php echo $admin->sid; ?>);'>
                                                                        <?php   if ($admin->active == '1') {
                                                                                    echo 'Deactivate';
                                                                                } else {
                                                                                    echo 'Activate';
                                                                                } ?>
                                                                    </a>
                                                                </td>
                                                                <?php } ?>
                                                                <?php if(check_access_permissions_for_view($security_details, 'login_executive_admin')) { ?>
                                                                <td>
                                                                    <?php if($admin->active == '1') { ?>
                                                                        <form id="form_login_executive_admin_<?php echo $admin->sid; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url();?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="login_executive_admin" />
                                                                            <input type="hidden" id="executive_admin_sid" name="executive_admin_sid" value="<?php echo $admin->sid; ?>" />
                                                                            <button type="button" onclick="f_login_executive_admin(<?php echo $admin->sid; ?>);" class="btn btn-success btn-sm">Login</button>
                                                                        </form>
                                                                    <?php } else { ?>
                                                                        <button type="button" onclick="" class="btn btn-success btn-sm disabled">Login</button>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan='6'>No administrator found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
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
</div>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).ready(function(){
        $('#name').on('keyup', update_url);
        $('#name').on('blur', update_url);
        $('#email').on('keyup', update_url);
        $('#email').on('blur', update_url);
        $('#search_btn').on('click', function(e){
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url(){
        var url = '<?php echo base_url('manage_admin/companies/executive_administrators/'); ?>';
        var name = $('#name').val().replace(/\s+/, '_');
        var email = $('#email').val();
        name = name == '' ? 'all' : name;
        email = email == '' ? 'all' : email;
        url = url + '/' + encodeURIComponent(name) + '/' + encodeURIComponent(email);
        $('#search_btn').attr('href', url);
    }

    function f_login_executive_admin(executive_admin_sid){
        var my_request;

        my_request = $.ajax({
            url: '<?php echo current_url();?>',
            method: 'POST',
            data: { 'executive_admin_sid': executive_admin_sid }
        });

        my_request.done(function (response) {
            if(response == 'session_created'){
                window.open(window.location.protocol+'//<?php echo $_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']).'executive_admin/dashboard'; ?>');
            } else {
                alertify.error('Something Went Wrong!');
            }
        });
    }

    function delete_administrator(administrator_sid) {
        alertify.confirm("Delete Administrator", "Are you sure you want to delete this administrator?",
                function () {
                    var myUrl = "<?= base_url('manage_admin/companies/executive_admin_delete_ajax') ?>";
                    var myRequest;
                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'post',
                        data: {'administrator_sid': administrator_sid}
                    });

                    myRequest.done(function (response) {
                        if (response) {
                            $('#'+administrator_sid).hide();
                            alertify.notify('Administrator deleted successfully.', 'success');
                        } else {
                            alertify.error('An unknown error occurred. Please try again.');
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }

    function activate_admin(administrator_sid) {
        alertify.confirm("Change Administrator Status", "Are you sure you want to change status for this administrator?",
        function () {
            var myUrl = "<?= base_url('manage_admin/companies/executive_admin_activation_ajax') ?>";
            var myRequest;
            myRequest = $.ajax({
                url: myUrl,
                type: 'post',
                data: { 'administrator_sid': administrator_sid }
            });

            myRequest.done(function (response) {
                if (response) {
                    status = ($('#status_' + administrator_sid).text()).trim();

                    if (status == 'Active') {
                        $('#status_' + administrator_sid).text('Inactive');
                        $('#activate_' + administrator_sid).text('Activate');
                        $('#activate_' + administrator_sid).removeClass("hr-delete-btn");
                        $('#activate_' + administrator_sid).addClass("hr-edit-btn");
                    } else {
                        $('#status_' + administrator_sid).text('Active');
                        $('#activate_' + administrator_sid).text('Deactivate');
                        $('#activate_' + administrator_sid).removeClass("hr-edit-btn");
                        $('#activate_' + administrator_sid).addClass("hr-delete-btn");
                    }

                    alertify.notify('Administrator status changed successfully.', 'success');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('An unknown error occurred. Please try again.');
                }
            });
        },
        function () {
            alertify.error('Cancelled');
        });
    }
    
    $(document).on('click','.send_credentials',function(e) {
        var sid = $(this).attr('data-attr');
        var url = "<?= base_url('manage_admin/companies/ajax_responder') ?>";
        alertify.confirm('Confirmation', "Are you sure you want to send login credentials?",
            function () {
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        perform_action: 'send_executive_admin_login_email',
                        sid: sid
                    },
                    success: function(data) {
                        if(data == 'success') {
                            alertify.success('Email with Login credentials is sent.');
                        } else {
                            alerty.error('there was error, please try again!');
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
</script>