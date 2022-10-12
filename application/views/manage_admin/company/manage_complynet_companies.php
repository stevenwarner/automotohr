<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .tab-content>.tab-pane {
        display: block !important;
    }
</style>
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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid) ?>"><i class="fa fa-long-arrow-left"></i> Go Back </a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title">Company Name: <?= $company_name ?> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tabs-outer">
                                        <ul class="nav nav-tabs">
                                            <li class="cards-tabs " data-attr="inactive_cards"><a href="<?= base_url('manage_admin/companies/manage_complynet_new/' . $company_sid); ?>">Add New</a></li>
                                            <li class="cards-tabs active" data-attr="active_cards"><a href="<?= base_url('manage_admin/companies/manage_complynet/' . $company_sid); ?>">Old</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="add-new-company">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <span class="page-title">ComplyNet Dashboard Link: </span>
                                                        <form id="complynet-dashboard" method="POST">
                                                            <div class="row">
                                                                <div class="col-lg-7 col-md-12 col-xs-7 col-sm-7">
                                                                    <input type="text" required placeholder="ComplyNet Dashboard Link" id="complynet_link" name="complynet_link" value="<?= $company_info['complynet_dashboard_link'] ?>" class="form-control">
                                                                </div>
                                                                <div class="col-lg-1 col-md-12 col-xs- col-sm-1 text-right">
                                                                    <input type="submit" value="Save" class="btn btn-success">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (sizeof($all_employees) > 0) { ?>

                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                                <table class="table table-bordered table-hover table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center">Access Level</th>
                                                                            <th>Email / Name</th>
                                                                            <th>Username</th>
                                                                            <th>Password</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!--All records-->
                                                                        <?php if (!empty($all_employees)) { ?>
                                                                            <?php foreach ($all_employees as $key => $value) {
                                                                                $username = '';
                                                                                $password = '' ?>
                                                                                <?php if (!empty($value['complynet_credentials']) && $value['complynet_credentials'] != NULL) {
                                                                                    $complynet_data = unserialize($value['complynet_credentials']);
                                                                                    $username = $complynet_data['username'];
                                                                                    $password = $complynet_data['password'];
                                                                                } ?>

                                                                                <tr id="parent_<?= $value['sid'] ?>">
                                                                                    <td class="text-center">
                                                                                        <div class="employee-profile-info profile-img">
                                                                                            <figure>
                                                                                                <?php if (!empty($value['profile_picture'])) { ?>
                                                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $value['profile_picture']; ?>">
                                                                                                <?php } else { ?>
                                                                                                    <img class="img-responsive" src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                                                                <?php } ?>
                                                                                            </figure>
                                                                                        </div>
                                                                                        <b><?php if ($value['is_executive_admin']) echo 'Executive Admin';
                                                                                            else echo ucwords($value['access_level']) ?></b>
                                                                                        <!--                                                                                --><?php //if(!empty($value['complynet_credentials']) && $value['complynet_credentials'] != NULL){ 
                                                                                                                                                                                ?>
                                                                                        <!--                                                                                    <div class="text-success"><i class="fa fa-check"></i></div>-->
                                                                                        <!--                                                                                --><?php //} else{ 
                                                                                                                                                                                ?>
                                                                                        <!--                                                                                    <div class="text-danger"><i class="fa fa-times"></i></div>-->
                                                                                        <!--                                                                                --><?php //} 
                                                                                                                                                                                ?>
                                                                                    </td>
                                                                                    <td><?php echo $value['email'] ?> /<br> <b><?php echo $value['first_name'] . $value['last_name'] ?></b></td>
                                                                                    <td><input type="text" id="user_<?= $value['sid']; ?>" name="username" value="<?= $username ?>" class="form-control"></td>
                                                                                    <td><input type="text" id="pass_<?= $value['sid']; ?>" name="pass" value="<?= $password ?>" class="form-control"></td>
                                                                                    <td><input type="button" name="save" id="<?= $value['sid']; ?>" value="Save" class="btn btn-success save_cred"></td>

                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else {  ?>
                                                                            <tr>
                                                                                <td colspan="8" class="text-center">
                                                                                    <span class="no-data">No Employers Found</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } else { ?>
                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                                <table class="table table-bordered table-hover table-striped">
                                                                    <tbody>
                                                                        <!--All records-->
                                                                        <tr>
                                                                            <td colspan="8" class="text-center">
                                                                                <span class="no-data">No Employee Found</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
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
    </div>
</div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function() {
        $('#complynet-dashboard').validate({
            submitHandler: function(form) {
                var url = $('#complynet_link').val();
                if (url != '') {
                    url_validate = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                    if (!url_validate.test(url)) {
                        alertify.error('Please Enter a Valid Link');
                        return false;
                    }
                }
                form.submit();
            }
        });
        $('.save_cred').click(function() {
            var emp_id = $(this).attr('id');
            if ($('#user_' + emp_id).val() == '' || $('#pass_' + emp_id).val() == '') {
                alertify.error('Please Provide Username and Password');
                return false;
            }
            $.ajax({
                url: '<?php echo base_url('manage_admin/companies/save_complynet_cred'); ?>',
                type: 'POST',
                data: {
                    user: $('#user_' + emp_id).val(),
                    key: $('#pass_' + emp_id).val(),
                    emp: emp_id
                },
                success: function(response) {
                    window.location.href = window.location.href;
                },
                error: function() {

                }
            });

        });
    });
</script>