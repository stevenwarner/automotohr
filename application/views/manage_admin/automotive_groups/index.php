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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-group"></i>Corporate Groups</h1>
                                        <a href="<?php echo base_url('manage_admin'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <h1 class="page-title text-center">All Corporate Groups</h1>
                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_automotive_groups')) { ?>
                                                        <a href="<?php echo base_url('manage_admin/corporate_groups/add'); ?>" class="btn btn-success pull-right full-on-small">Add New Corporate Group</a>
                                                    <?php } ?>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="table-responsive table-custom">
                                                    <table class="table table-stripped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-4">Group Name</th>
                                                                <th class="col-xs-5">Corporate Company Name</th>
                                                                <th class="col-xs-2">Group Country</th>
                                                                <?php $function_names = array('manage_automotive_groups', 'automotive_member_companies'); ?>
                                                                <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                    <th class="col-xs-1 text-center" colspan="3">Actions</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($automotive_groups)) { ?>
                                                                <?php foreach($automotive_groups as $automotive_group) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo ucwords($automotive_group['group_name']); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if(!empty($automotive_group['corporate_company'])) { ?>
                                                                                <span class="text-success"><?php echo ucfirst($automotive_group['corporate_company']['CompanyName']); ?></span>
                                                                            <?php } else { ?>
                                                                                <span class="">No Corporate Company Assigned</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo isset($automotive_group['country_name']) ? ucfirst($automotive_group['country_name']) : ''; ?>
                                                                        </td>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'manage_automotive_groups')) { ?>
                                                                            <td>
                                                                                <a data-toggle="tooltip" data-placement="top" title="Edit" href="<?php echo base_url('manage_admin/corporate_groups/edit/' . $automotive_group['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'automotive_member_companies')) { ?>
                                                                            <td>
                                                                                <a data-toggle="tooltip" data-placement="top" title="List Member Companies" href="<?php echo base_url('manage_admin/corporate_groups/member_companies/' . $automotive_group['sid'])?>" class="btn btn-default btn-sm">Companies</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'manage_automotive_groups')) { ?>
                                                                        <td>
                                                                            <form id="form_delete_automotive_group_<?php echo $automotive_group['sid']; ?>" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="perform_action" id="perform_action" value="delete_automotive_group" />
                                                                                <input type="hidden" name="automotive_group_sid" id="automotive_group_sid" value="<?php echo $automotive_group['sid']; ?>" />
                                                                                <?php if(count($automotive_group['member_companies']) > 0) { ?>
                                                                                    <button data-toggle="tooltip" data-placement="top" title="Delete Group"  onclick="fAlertToDeleteCompanies('<?php echo $automotive_group['group_name']; ?>');" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                                <?php } else { ?>
                                                                                    <button data-toggle="tooltip" data-placement="top" title="Delete Group" onclick="fDeleteAutomotiveGroup(<?php echo $automotive_group['sid']; ?>, '<?php echo $automotive_group['group_name']; ?>');" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                                <?php } ?>
                                                                            </form>
                                                                        </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="no-data text-center" colspan="3">No Groups Found</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="row"><?php echo $links; ?></div>
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
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    function fDeleteAutomotiveGroup(automotive_group_id, automotive_group_name){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete "' + automotive_group_name + '" ?',
            function () {
                $('#form_delete_automotive_group_' + automotive_group_id).submit();
            },
            function () {

            });
    }

    function fAlertToDeleteCompanies(automotive_group_name){
        alertify.alert('Corporate Group Has Member Companies', 'Please delete all Member companies of "' + automotive_group_name + '" in order to delete this group!');
    }
</script>