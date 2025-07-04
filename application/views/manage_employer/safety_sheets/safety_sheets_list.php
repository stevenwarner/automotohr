<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('safety_sheets/manage_safety_sheets')?>" class="dashboard-link-btn"><i class="fa fa-arrow-left"></i> Back</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                        <div class="row">
                            <?php if (check_access_permissions_for_view($security_details, 'add_safety_sheet')) { ?>
                                <?php if(isset($cat_sid)) { ?>
                                    <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                        <a id="search_btn" href="<?php echo base_url('safety_sheets/add_new_sheet/'.$cat_sid)?>" class="btn btn-success btn-block mb-2"> <i class="fa fa-plus-square"></i> Safety Sheet</a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if (check_access_permissions_for_view($security_details, 'add_safety_category')) { ?>
                                <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                    <a id="search_btn" href="<?php echo base_url('safety_sheets/add_new_category')?>" class="btn btn-success btn-block mb-2"> <i class="fa fa-plus-square"></i> Safety Category</a>
                                </div>
                            <?php } ?>
                            <?php if (check_access_permissions_for_view($security_details, 'add_default_category')) { ?>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <a id="search_btn" href="<?php echo base_url('safety_sheets/manage_default_categories')?>" class="btn btn-success btn-block mb-2"> Default Safety Categories</a>
                            </div>
                            <?php } ?>
                            <?php if (check_access_permissions_for_view($security_details, 'add_default_sheet')) { ?>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <a id="search_btn" href="<?php echo base_url('safety_sheets/manage_default_sheets')?>" class="btn btn-success btn-block mb-2"> Default Safety Sheets</a>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="page-header">
                            <h1 class="section-ttile"><?php echo $cat_name != 'Default' ? $cat_name . ' -' : 'Default';?> safety sheets</h1>
                        </div>

                        <div class="table-responsive">
                            <form name="multiple_actions" id="incident_types" method="POST">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sheet Name</th>
                                        <th class="last-col" width="1%" colspan="3">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--All records-->

                                    <?php if(sizeof($sheet_data)>0) {
                                        foreach ($sheet_data as $type) {
                                            $check = $type['company_sid']==0 ? 'disabled="disabled"' : '';
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $type['title']?>
                                                </td>
                                            <?php if($type['company_sid']>0) { ?>
                                                <?php if (check_access_permissions_for_view($security_details, 'enable_disable_sheet')) { ?>
                                                    <td><?php echo anchor('safety_sheets/edit_safety_sheet/'.$cat_sid.'/'.$type['sid'], 'Edit', 'class="btn btn-success btn-sm" title="Edit Sheet" '.$check); ?></td>
                                                <?php }?>
                                                <?php if (check_access_permissions_for_view($security_details, 'edit_safety_sheet')) { ?>
                                                    <td><input type="button" class="btn btn-danger btn-sm delete-sheet" data-key = <?php echo $cat_sid;?> value="Delete" <?php echo $check;?> data-attr = <?php echo $type['sid'];?>></td>
                                                <?php }?>
                                            <?php
                                                } else{?>
                                                    <td>
                                                        <a href="<?= base_url('safety_sheets/clone_to_company/' . $type['sid'] . '/' . rawurlencode($type['title'])); ?>"
                                                           class="btn btn-success btn-sm"
                                                           title="Add To Company">Add To Company</a>
                                                    </td>
                                                <?php }?>
                                            </tr>
                                        <?php }
                                    }
                                    else{ ?>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span class="no-data">No Sheet Found</span>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-sheet').click(function(){
            var id = $(this).attr('data-attr');
            var cat_id = $(this).attr('data-key');
            alertify.confirm('Confirmation', "Are you sure you want to delete this sheet?",
                function () {
                    $.ajax({
                        url: '<?= base_url('safety_sheets/delete_sheet_ajax'); ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            cat_id: cat_id,
                            action: 'delete'
                        },
                        success: function(data){
                            alertify.success('Safety Sheet Deleted Successfully');
                            window.location.href = window.location.href;
                        },
                        error: function(){
                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
        $('.type').click(function(){
            var id = $(this).attr('id');
            var status = $(this).html();
            if(status == 'Disable'){
                $.ajax({
                    type: 'GET',
                    data:{
                        status:0,
                        flag: 'sheet'
                    },
                    url: '<?= base_url('manage_admin/safety_data_sheet/enable_disable_type')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#status-'+id).html('In Active');
                            $('#'+id).removeClass('btn-danger');
                            $('#'+id).addClass('btn-primary');
                            $('#'+id).html('Enable');
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
                        status:1,
                        flag: 'sheet'
                    },
                    url: '<?= base_url('manage_admin/safety_data_sheet/enable_disable_type')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#status-'+id).html('Active');
                            $('#'+id).removeClass('btn-primary');
                            $('#'+id).addClass('btn-danger');
                            $('#'+id).html('Disable');
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