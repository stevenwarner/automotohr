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
                                    </div>
                                    <div class="row">
                                        <?php if($this->uri->segment(4) == '') { ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <?php if (check_access_permissions_for_view($security_details, 'add_new_type')) { ?>
                                                    <a id="search_btn" href="<?php echo base_url('manage_admin/reports/compliance_reporting/add_new_type')?>" class="btn btn-success"><i class="fa fa-plus-square"> </i> Add New Type</a>
                                                <?php } ?>
                                                <?php if (check_access_permissions_for_view($security_details, 'view_incidents')) { ?>
                                                    <a id="back_btn" href="<?php echo base_url('manage_admin/reports/compliance_reporting/reported_incidents')?>" class="btn btn-success"><i class="fa fa-eye"> </i> View Compliance</a>
                                                <?php } ?>
                                            </div>
                                        <?php } elseif ($this->uri->segment(4) == 'checklists') { ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <a id="back_btn" href="<?php echo base_url('manage_admin/reports/compliance_reporting')?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                            </div>    
                                        <?php } ?>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="incident_types" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Status</th>                                                      
                                                            <th class="last-col" width="1%" colspan="3">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <!--All records-->

                                                        <?php if(sizeof($compliance_types)>0) {
                                                            foreach ($compliance_types as $type) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= $type['compliance_name']?>
                                                                    </td>

                                                                    <td id="status-<?=$type['id']?>">
                                                                        <?= $type['status']==1 ? 'Active':'In Active'?>
                                                                    </td>                                                               
                                                                    
                                                                    <?php if (check_access_permissions_for_view($security_details, 'edit_type')) { ?>
                                                                        <td>
                                                                            <a title="Edit Type" href="<?php echo base_url()?>manage_admin/reports/compliance_reporting/add_new_type/<?php echo $type['id']; ?>" class="btn btn-info btn-sm pencil_useful_link">
                                                                                <i class="fa fa-pencil"></i>
                                                                            </a>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'disable_type')) { ?>
                                                                        <td>
                                                                            <?php if ($type['status']==1) { ?>
                                                                               <a href="javascipt:;"
                                                                               class="btn btn-sm btn-danger type" id="<?php echo $type['id']; ?>"
                                                                               title="Disable Type" src="Disable"><i class="fa fa-toggle-off"></i></a>   
                                                                            <?php } else { ?>
                                                                                <a href="javascipt:;"
                                                                               class="btn btn-sm btn-primary type" id="<?php echo $type['id']; ?>"
                                                                               title="Enable Type" src="Enable"><i class="fa fa-toggle-on"></i></a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'view_question')) { ?>
                                                                        <td><a href="<?php echo base_url('manage_admin/reports/compliance_reporting/view_compliance_questions/'.$type['id'])?>"
                                                                               class="btn btn-warning btn-sm"
                                                                               title="Delete Employer">View Questions</a>
                                                                        </td>
                                                                    <?php } ?>

                                                                </tr>
                                                            <?php }
                                                        }
                                                        else{ ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Compliance Type Found</span>
                                                                </td>
                                                            </tr>
                                                        <?php }?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="execute" value="multiple_action">
                                                    <input type="hidden" id="type" name="type" value="employer">
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
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.type').click(function(){
            var id = $(this).attr('id');
            var status = $(this).attr('src');
            if(status == 'Disable'){
                $.ajax({
                    type: 'GET',
                    data:{
                        status:0
                    },
                    url: '<?= base_url('manage_admin/reports/compliance_reporting/enable_disable_type')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#status-'+id).html('In Active');
                            $('#'+id).removeClass('btn-danger');
                            $('#'+id).addClass('btn-primary');
                            $('#'+id).html('<i class="fa fa-toggle-on"></i>');
                            $('#'+id).attr('src', 'Enable');
                            $('#'+id).attr('title','Enable Type');
                        }
                    },
                    error: function(){

                    }
                });
            }
            else if(status == 'Enable'){

                $.ajax({
                    type: 'GET',
                    data:{
                        status:1
                    },
                    url: '<?= base_url('manage_admin/reports/compliance_reporting/enable_disable_type')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#status-'+id).html('Active');
                            $('#'+id).removeClass('btn-primary');
                            $('#'+id).addClass('btn-danger');
                            $('#'+id).html('<i class="fa fa-toggle-off"></i>');
                            $('#'+id).attr('src', 'Disable');
                            $('#'+id).attr('title','Disable Type');
                        }
                    },
                    error: function(){

                    }
                });
            }
        });

    });


</script>