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
<!--                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="search_btn" href="<?php //echo base_url('manage_admin/reports/incident_reporting/add_new_type')?>" class="btn btn-success"><i class="fa fa-plus-square"> </i> Add New Type</a>
                                            <a id="back_btn" href="<?php //echo base_url('manage_admin/reports/incident_reporting/reported_incidents')?>" class="btn btn-success"><i class="fa fa-eye"> </i> View Incidents</a>
                                        </div>
                                    </div>-->
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="incident_types" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th class="last-col" width="1%" colspan="2">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(sizeof($documents_library_types) > 0) {
                                                            foreach ($documents_library_types as $type) { ?>
                                                                <tr>
                                                                    <td><?php echo $type['name']; ?></td>
                                                                    <td id="status-<?=$type['sid']?>"><?php echo $type['status']==1 ? 'Active':'In Active'; ?></td>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'disable_category')) { ?>
                                                                        <td><?php echo $type['status']==1 ? '<a href="javascipt:;" class="btn btn-sm btn-danger change_status" id="'.$type['sid'].'" title="Disable"><i class="fa fa-ban"></i></a>': '<a href="javascipt:;" class="btn btn-sm btn-primary change_status" id="'.$type['sid'].'" title="Enable"><i class="fa fa-shield"></i></a>'; ?></td>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'view_detail')) { ?>
                                                                        <td><a href="<?php echo base_url('manage_admin/documents_library/view_details/'.$type['sid'])?>" class="btn btn-success btn-sm" title="View Details"><i class="fa fa-eye"></i></a></td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center"><span class="no-data">No Documents Menu found!</span></td>
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
                url: '<?= base_url('manage_admin/documents_library/enable_disable_type')?>/' + id,
                success: function(data){
                    data = JSON.parse(data);
                    if(data.message == 'updated'){
                        $('#status-'+id).html('In Active');
                        $('#'+id).removeClass('btn-danger');
                        $('#'+id).addClass('btn-primary');
                        $('#'+id).find('i').removeClass('fa-ban');
                        $('#'+id).find('i').addClass('fa-shield');
                        $('#'+id).attr('title','Enable Type');
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
                url: '<?= base_url('manage_admin/documents_library/enable_disable_type')?>/' + id,
                success: function(data){
                    data = JSON.parse(data);
                    if(data.message == 'updated'){
                        $('#status-'+id).html('Active');
                        $('#'+id).removeClass('btn-primary');
                        $('#'+id).addClass('btn-danger');
                        $('#'+id).find('i').removeClass('fa-shield');
                        $('#'+id).find('i').addClass('fa-ban');
                        $('#'+id).attr('title','Disable Type');
                    }
                },
                error: function(){

                }
            });
        });
</script>