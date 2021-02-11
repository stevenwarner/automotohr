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
                                        <a id="back_btn" href="<?php echo base_url('manage_admin/documents_library/view_details/'.$lib_id)?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title">
                                                <?php  if(sizeof($parent_name)>0) { ?>
                                                            <span class="text-success"><?= $parent_name[0]['name']?></span> > <span class="text-success"><?= $parent_name[0]['title']?></span>
                                                <?php  } ?>
                                            </h1>
                                            <?php if (check_access_permissions_for_view($security_details, 'add_sub_heading')) { ?>
                                                <a id="search_btn" href="<?php echo base_url('manage_admin/documents_library/add_new_heading/'.$lib_id . '/' .$menu_id)?>" class="btn btn-success pull-right"><i class="fa fa-plus-square"> </i> Add Sub Heading</a>
                                            <?php  } ?>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Menu Name</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                            <th class="last-col" width="1%" colspan="3">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(sizeof($document_sub_heading_details)>0) {
                                                            foreach ($document_sub_heading_details as $type) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= $type['title']?>
                                                                    </td>

                                                                    <td>
                                                                        <?= $type['type']=='content' ? 'Content Type':'Anchor Type'?>
                                                                    </td>

                                                                    <td id="status-<?=$type['sid']?>">
                                                                        <?= $type['status']==1 ? 'Active':'In Active'?>
                                                                    </td>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'view_sub_heading')) { ?>
                                                                        <td><?php echo anchor('manage_admin/documents_library/edit_sub_heading/'. $lib_id . '/' . $menu_id . '/' .$type['sid'], '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm" title="Edit"'); ?></td>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'disable_heading')) { ?>
                                                                        <td>
                                                                            <?= $type['status']==1 ? '<a href="javascipt:;"
                                                                               class="btn btn-sm btn-danger type" id="'.$type['sid'].'"
                                                                               title="Disable"><i class="fa fa-ban"></i></a>'
                                                                                :
                                                                                '<a href="javascipt:;"
                                                                               class="btn btn-sm btn-primary type" id="'.$type['sid'].'"
                                                                               title="Enable"><i class="fa fa-shield"></i></a>'?>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Sub Headings Found</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
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
    $(document).on('click','.btn-danger',function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'GET',
            data:{
                status:0
            },
            url: '<?= base_url('manage_admin/documents_library/enable_disable_sub_menu')?>/' + id,
            success: function(data){
                data = JSON.parse(data);
                if(data.message == 'updated'){
                    $('#status-'+id).html('In Active');
                    $('#'+id).removeClass('btn-danger');
                    $('#'+id).addClass('btn-primary');
                    $('#'+id).find('i').removeClass('fa-ban');
                    $('#'+id).find('i').addClass('fa-shield');
                }
            },
            error: function(){
            }
        });
    });
    $(document).on('click','.btn-primary',function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'GET',
            data:{
                status:1
            },
            url: '<?= base_url('manage_admin/documents_library/enable_disable_sub_menu')?>/' + id,
            success: function(data){
                data = JSON.parse(data);
                if(data.message == 'updated'){
                    $('#status-'+id).html('Active');
                    $('#'+id).removeClass('btn-primary');
                    $('#'+id).addClass('btn-danger');
                    $('#'+id).find('i').addClass('fa-ban');
                    $('#'+id).find('i').removeClass('fa-shield');
                }
            },
            error: function(){
            }
        });
    });
</script>