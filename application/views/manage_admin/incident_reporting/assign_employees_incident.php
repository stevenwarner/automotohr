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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_incident_configuration/'.$cid)?>"><i class="fa fa-long-arrow-left"></i> Go Back </a>
                                    </div>
                                            <div class="add-new-company">
                                                <div class="heading-title">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <span class="page-title">Company Name : <?php echo $company_incident_name['company']?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <span class="page-title"> Incident Name &nbsp;&nbsp;: <?php echo $company_incident_name['inc']?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php if(sizeof($all_employees)>0) {?>

                                            <div class="hr-box">
<!--                                                <div class="hr-box-header">-->
<!--                                                    <div class="hr-items-count">-->
<!--                                                        <strong class="employerCount">--><?php //echo sizeof($all_employees); ?><!--</strong> Employers-->
<!--                                                    </div>-->
<!--                                                    --><?php //if(check_access_permissions_for_view($security_details, 'show_employer_multiple_actions')){ ?>
<!--                                                        --><?php //$this->load->view('templates/_parts/admin_manage_multiple_actions'); ?>
<!--                                                    --><?php //} ?>
<!--                                                </div>-->
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" id="check_all"></th>
                                                                    <th class="text-center">ID</th>
                                                                    <th>Access Level</th>
                                                                    <th>Email</th>
                                                                    <th>Registration Date</th>
                                                                    <th>Contact Name</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <!--All records-->
                                                                <?php if(!empty($all_employees)){?>
                                                                    <?php foreach ($all_employees as $key => $value){ ?>
                                                                        <tr id="parent_<?= $value['sid'] ?>">
                                                                            <td><input type="checkbox" name="checklist[]" value="<?php echo $value['sid']; ?>" class="my_checkbox" <?php if(in_array($value['sid'],$configured_employees)) echo 'checked="checked"';?>></td>
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
                                                                                <b><?php echo $value['sid']; ?></b>
                                                                            </td>
                                                                            <td><?php if($value['is_executive_admin']) echo 'Executive Admin'; else echo ucwords($value['access_level'])?></td>
                                                                            <td><?php echo $value['email']?></td>
                                                                            <td><?php echo date('m-d-Y',strtotime($value['registration_date'])); ?></td>
                                                                            <td><?php echo ucwords($value['first_name'] . ' ' . $value['last_name']); ?></td>

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
                                                            <input type="submit" class="btn btn-success pull-right" name="submit" value="Save">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php } else{ ?>
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

                                            <?php }?>
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
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $('#multiple_actions_employer').submit(function(){
            // if($('[name="checklist[]"]:checked').length == 0){
            //     alertify.error('Please configure at least one employee');
            //     return false;
            // }
        });
        $('#check_all').click(function () {
            if ($('#check_all').is(":checked")) {
                $('.my_checkbox:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.my_checkbox:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    });
</script>