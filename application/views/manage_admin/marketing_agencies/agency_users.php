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
                                        <h1 class="page-title"><i class="fa fa-briefcase"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                    <div class="add-new-promotions">
                                        <a class="btn btn-success" href="<?php echo base_url('manage_admin/marketing_agencies/add_agency_user/'.$parent_agency_details['sid']); ?>">Add Sub Account</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left col-xs-2" rowspan="2">Name</th>
                                                    <?php   $function_names = array('add_edit_marketing_agency', 'manage_commissions'); ?>
                                                            
                                                    <?php   if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                <th class="text-center col-xs-1" rowspan="2" colspan="6">Actions</th>
                                                    <?php   } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php   if(!empty($marketing_agency_users)) { ?>
                                                    <?php       foreach($marketing_agency_users as $marketing_agency) { ?>
                                                                <tr>
                                                                    <td><?php echo ucwords($marketing_agency['full_name']); ?></td>
                                                                    
                                                    <?php           if(check_access_permissions_for_view($security_details, 'add_edit_marketing_agency')) { ?>

                                                                        <td class="text-center">
                                                                            <a href="javascript:;" class="btn btn-success btn-sm send_credentials" title="Send Login Credentials" data-attr="" data-name="" onclick="return send_login_email('<?php echo $marketing_agency['username']; ?>',  '<?php echo $marketing_agency['sid']; ?>')">
                                                                                Send Login Email
                                                                            </a>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <a class="btn btn-success btn-sm" href="javascript:;" id="<?php echo $marketing_agency['sid']; ?>" onclick="return affiliateLogin(this.id)" title="Login"><i class="fa fa-power-off"></i></a>
                                                                        </td>

                                                                        <td class="text-center">
                                                    <?php                   if($marketing_agency['status'] == 1) { ?>
                                                                                <form id="form_deactivate_marketing_agency_<?php echo $marketing_agency['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="deactivate_marketing_agency" />
                                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $marketing_agency['sid']; ?>" />
                                                                                    <button type="submit" class="btn btn-danger btn-sm" title="Deactivate"><i class="fa fa-ban"></i></button>
                                                                                </form>
                                                    <?php                   } else { ?>
                                                                                <form id="form_activate_marketing_agency_<?php echo $marketing_agency['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="activate_marketing_agency" />
                                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $marketing_agency['sid']; ?>" />
                                                                                    <button type="submit" class="btn btn-success btn-sm" title="Activate"><i class="fa fa-shield"></i></button>
                                                                                </form>
                                                    <?php                   } ?>
                                                                            
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <a href="<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency' . '/' . $marketing_agency['sid'] . '/' . $marketing_agency['parent_sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <form id="form_delete_marketing_agency_<?php echo $marketing_agency['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_marketing_agency" />
                                                                                <input type="hidden" id="sid" name="sid" value="<?php echo $marketing_agency['sid']; ?>" />
                                                                                <button onclick="func_delete_marketing_agency(<?php echo $marketing_agency['sid']; ?>);" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                            </form>
                                                                        </td>
                                                    <?php           } ?>
                                                                </tr>
                                                    <?php       } ?>
                                                    <?php   } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="8">
                                                                            <span class="no-data">Sub Account users not found!</span>
                                                                        </td>
                                                                    </tr>
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
    </div>
</div>
<script>
    function func_delete_marketing_agency(sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Marketing Agency?',
            function () {
                $('#form_delete_marketing_agency_' + sid).submit();
            }, function () {
                alertify.error('Cancelled');
            });
    }

    function affiliateLogin(affiliate_Id) {
        url_to = "<?=base_url()?>manage_admin/marketing_agencies/affiliate_login";
        $.post(url_to, {action: "login", sid: affiliate_Id})
        .done(function(){
            window.open("<?= base_url('affiliate_portal/dashboard') ?>", '_blank');
        });
    }

    function send_login_email(affiliate_username, affiliate_Id) {
        var dbname = affiliate_username;
        var marketing_agency_sid = affiliate_Id;

        alertify.confirm('Confirmation', "Are you sure you want to send Login Request?",function () {
            if ( dbname == '' || dbname == null ) {
                alertify.alert('Please Provide Username');
                return false;
            } else if (dbname != '' && dbname != null) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                    data: {
                        username: dbname,
                        id: marketing_agency_sid,
                        flag: 'db'
                    },
                    success: function (data) {
                        alertify.success('Login Request have been sent successfully');
                        window.location.href = window.location.href;
                    },
                    error: function () {
                    }
                });
            }
        },
        function () {
            alertify.error('Cancelled');
        });
    }

    $('#send-cred').click(function() {
        var dbname = $('#db-username').val();
        alertify.confirm('Confirmation', "Are you sure you want to send Login Request?",function () {
            if ( dbname == '' || dbname == null ) {
                alertify.alert('Please Provide Username');
                return false;
            } else if (dbname != '' && dbname != null) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                    data: {
                        username: dbname,
                        id: '<?= isset($marketing_agency) ? $marketing_agency['sid'] : '';?>',
                        flag: 'db'
                    },
                    success: function (data) {
                        alertify.success('Login Request have been sent successfully');
                        window.location.href = window.location.href;
                    },
                    error: function () {

                    }
                });
            }
        },
        function () {
            alertify.error('Cancelled');
        });
    });
</script>