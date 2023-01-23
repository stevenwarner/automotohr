<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('organizational_hierarchy'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>

                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php if(check_access_permissions_for_view($security_details, 'add_position')) { ?>
                    <div class="">
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/add_position'); ?>">Add New Position</a>
                    </div>
                    <?php } ?>
                    <hr />
                    <div class="hr-box">
                        <div class="hr-box-header bg-header-green">
                            <h1 class="hr-registered pull-left">
                                <span class="">Company Positions</span>
                            </h1>
                        </div>
                        <div class="hr-box-body hr-innerpadding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-3 text-center">Name</th>
                                            <th class="col-xs-3 text-center">Description</th>
                                            <th class="col-xs-3 text-center">Parent</th>
                                            <th class="col-xs-2 text-center">Department</th>
                                            <?php if(check_access_permissions_for_view($security_details, array('edit_position', 'delete_position'))) { ?>
                                            <th class="col-xs-1 text-center" colspan="2">Actions</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($positions)) { ?>
                                            <?php foreach($positions as $position) { ?>
                                                <tr>
                                                    <td><?php echo $position['position_name']; ?></td>
                                                    <td><?php echo ($position['position_description'] != '' ? $position['position_description'] : 'N/A'); ?></td>
                                                    <td><?php echo ($position['parent_name'] != '' ? $position['parent_name'] : 'N/A'); ?></td>
                                                    <td><?php echo ($position['department_name'] != '' ? $position['department_name'] : 'N/A'); ?></td>
                                                    <?php if(check_access_permissions_for_view($security_details, 'edit_position')) { ?>
                                                    <td><a class="btn btn-success btn-sm" href="<?php echo base_url('organizational_hierarchy/edit_position/' . $position['sid']); ?>"><i class="fa fa-pencil"></i> Edit</a></td>
                                                    <?php } ?>
                                                    <?php if(check_access_permissions_for_view($security_details, 'delete_position')) { ?>
                                                    <td>
                                                        <form id="form_delete_position_<?php echo $position['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_position" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $position['company_sid']; ?>" />
                                                            <input type="hidden" id="position_sid" name="position_sid" value="<?php echo $position['sid']; ?>" />
                                                            <input type="hidden" id="department_sid" name="department_sid" value="<?php echo $position['department_sid']; ?>" />
                                                            <button onclick="func_confirm_and_delete(<?php echo $position['sid']; ?>);" type="button" class="btn btn-danger btn-sm"><i class="fa fa-pencil"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <span class="no-data">No Positions</span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>


<script>
    function func_confirm_and_delete(position_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Position?',
            function () {
                $('#form_delete_position_' + position_sid).submit();
            }, function () {
                alertify.error('Cancelled!');
            })
    }

    function func_show_delete_message(){
        alertify.alert('You can not delete this department!', 'You have to remove all sub departments in order to delete this Department.');
    }
</script>
