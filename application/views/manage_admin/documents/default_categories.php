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
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="search_btn" href="<?php echo base_url('manage_admin/default_categories/add')?>" class="btn btn-success"><i class="fa fa-plus-square"> </i> Add New Default Category</a>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                        <th>Sort Order</th>
                                                        <th>Created Date</th>
                                                        <th class="last-col" width="1%" colspan="3">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--All categories records-->
                                                        <?php if(!empty($default_categories)) { ?>
                                                            <?php foreach ($default_categories as $category) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $category['name']; ?>
                                                                    </td>
                                                                    <td id="status-<?=$category['sid']?>">
                                                                        <?php echo $category['status'] == 1 ? 'Active':'In Active'; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $category['sort_order']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo date_with_time($category['created_date']); ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?php echo base_url('manage_admin/default_categories/edit').'/'.$category['sid']; ?>" class="btn btn-success btn-sm" title="Edit Sheet">
                                                                            Edit
                                                                        </a>   
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($category['status'] == 1) { ?>
                                                                            <a href="javascipt:;" class="btn btn-sm btn-danger change_status" id="<?php echo $category['sid']; ?>" title="Disable Sheet" data-status="0">
                                                                                Disable
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="javascipt:;" class="btn btn-sm btn-primary change_status" id="<?php echo $category['sid']; ?>" title="Enable Sheet" data-status="1">
                                                                                Enable
                                                                            </a>
                                                                        <?php } ?>
                                                                    </td>
                                                                    
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else{ ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Sheet Found</span>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.change_status').on("click", function(){
            
            var id = $(this).attr('id');
            
            var change_status = $(this).data('status');
            console.log(id , change_status)
            $.ajax({
                type: 'GET',
                data:{
                    status:change_status
                },
                url: '<?= base_url('manage_admin/default_categories/change_status')?>/' + id,
                success: function(data){
                    data = JSON.parse(data);
                    if(data.message == 'updated'){
                        if (change_status == 0){
                            $('#status-'+id).html('In Active');
                            $('#'+id).removeClass('btn-danger');
                            $('#'+id).addClass('btn-primary');
                            $('#'+id).html('Enable');
                            $('#'+id).attr('title','Enable Type');
                        } else {
                            $('#status-'+id).html('Active');
                            $('#'+id).removeClass('btn-primary');
                            $('#'+id).addClass('btn-danger');
                            $('#'+id).html('Disable');
                            $('#'+id).attr('title','Disable Type');
                        }
                        
                    }
                },
                error: function(){

                }
            });
        });

    });


</script>