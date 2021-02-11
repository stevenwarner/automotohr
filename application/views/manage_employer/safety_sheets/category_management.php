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
                        <span class="page-heading down-arrow">
                            <?php if($default_flag) {?>
                                <a href="<?php echo base_url('safety_sheets/manage_safety_sheets')?>" class="dashboard-link-btn"><i class="fa fa-arrow-left"></i> Back</a>
                            <?php } else{ ?>
                                <a class="dashboard-link-btn" href="<?php echo base_url('manage_ems'); ?>">
                                    <i class="fa fa-chevron-left"></i>Employee Management System
                                </a>
                            <?php } echo $title; ?>
                        </span>
                    </div>
                    <div class="row">
                        <?php if (check_access_permissions_for_view($security_details, 'add_safety_sheet')) { ?>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <a id="search_btn" href="<?php echo base_url('safety_sheets/add_new_sheet/')?>" class="btn btn-success btn-block mb-2"><i class="fa fa-plus-square"></i> Safety Sheet</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'add_safety_category')) { ?>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <a id="search_btn" href="<?php echo base_url('safety_sheets/add_new_category')?>" class="btn btn-success btn-block mb-2"><i class="fa fa-plus-square"></i> Safety Category</a>
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
                        <h1 class="section-ttile"><?php echo $default_flag ? 'Default Categories' : 'Safety Category Management'; ?></h1>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Category Name</th>
                                <th class="last-col" width="1%" colspan="3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(sizeof($safety_category)>0) {
                                foreach ($safety_category as $type) { ?>
                                    <tr>
                                        <td>
                                            <?= $type['name']?>
                                        </td>

                                        <?php if($type['company_sid'] > 0) { ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'view_sheets')) { ?>
                                            <td>
                                                <a href="<?= base_url('safety_sheets/view_company_sheets/' . $type['sid']); ?>"
                                                   class="btn btn-primary btn-sm"
                                                   title="View Sheets">View Sheets</a>
                                            </td>
                                            <?php } if (check_access_permissions_for_view($security_details, 'edit_safety_category')) { ?>
                                                <td>
                                                    <a href="<?= base_url('safety_sheets/edit_category/' . $type['sid']); ?>"
                                                       class="btn btn-success btn-sm"
                                                       title="Edit Category" <?= $type['company_sid'] == 0 ? 'disabled="disabled"' : ''; ?>>Edit</a>
                                                </td>
                                            <?php } ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'enable_disable_category')) {
                                                $check = $type['company_sid'] == 0 ? 'disabled="disabled"' : ''; ?>
                                                <td>
                                                    <?= $type['status'] == 1 ? '<a href="javascript:;" class="btn btn-sm btn-danger type" id="' . $type['sid'] . '" title="Disable Category" ' . $check . '>Disable</a>'
                                                        :
                                                        '<a href="javascript:;"
                                                               class="btn btn-sm btn-primary type" id="' . $type['sid'] . '"
                                                               title="Enable Category" ' . $check . ' >Enable</a>' ?>
                                                </td>
                                            <?php }
                                        } else {?>
                                            <td>
                                                <a href="<?= base_url('safety_sheets/add_to_company/' . $type['sid'] . '/' . rawurlencode($type['name'])); ?>"
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
                                        <span class="no-data">No Category Found</span>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
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
            var status = $(this).html();
            if(status == 'Disable'){
                $.ajax({
                    type: 'GET',
                    data:{
                        status:0,
                        flag: 'cat'
                    },
                    url: '<?= base_url('safety_sheets/enable_disable_type')?>/' + id,
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
                        flag: 'cat'
                    },
                    url: '<?= base_url('safety_sheets/enable_disable_type')?>/' + id,
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
