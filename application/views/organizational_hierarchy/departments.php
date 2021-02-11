<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('organizational_hierarchy'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                    <br />

                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <?php if(check_access_permissions_for_view($security_details, 'add_department')) { ?>
                    <div class="">
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/add_department'); ?>">Add Department</a>
                    </div>
                    <?php } ?>
                    <hr />
                    <div class="hr-box">
                        <div class="hr-box-header bg-header-green">
                            <h1 class="hr-registered pull-left">
                                <span class="">Company Departments</span>
                            </h1>
                        </div>
                        <div class="hr-box-body hr-innerpadding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center col-xs-3">Department Name</th>
                                            <th class="text-center col-xs-3">Parent</th>
                                            <th class="text-center col-xs-5">Description</th>
                                            <?php if(check_access_permissions_for_view($security_details, array('edit_department', 'delete_department'))) { ?>
                                            <th class="text-center col-xs-1" colspan="2">Actions</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($departments)) { ?>
                                            <?php foreach($departments as $department) { ?>
                                                <tr>
                                                    <td><?php echo $department['dept_name'];?></td>
                                                    <td><?php echo ($department['parent_name'] != '' ? $department['parent_name'] : 'N/A');?></td>
                                                    <td><?php echo ($department['dept_description'] != '' ? $department['dept_description'] : 'N/A');?></td>

                                                    <?php if(check_access_permissions_for_view($security_details, 'edit_department')) { ?>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url('organizational_hierarchy/edit_department/' . $department['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                                    </td>
                                                    <?php } ?>
                                                    <?php if(check_access_permissions_for_view($security_details, 'delete_department')) { ?>
                                                    <td class="text-center">
                                                        <?php if($department['sub_departments_count'] == 0) { ?>
                                                        <form id="form_delete_department_<?php echo $department['sid']; ?>" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_department" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $department['company_sid']; ?>" />
                                                            <input type="hidden" id="department_sid" name="department_sid" value="<?php echo $department['sid']; ?>" />

                                                            <button onclick="func_confirm_and_delete(<?php echo $department['sid']; ?>);" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                                        </form>
                                                        <?php } else { ?>
                                                            <button onclick="func_show_delete_message();" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <span class="no-data">No Departments Added</span>
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
    function func_confirm_and_delete(department_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Department?',
            function () {
                $('#form_delete_department_' + department_sid).submit();
            }, function () {
                alertify.error('Cancelled!');
            })
    }

    function func_show_delete_message(){
        alertify.alert('You can not delete this department!', 'You have to remove all sub departments in order to delete this Department.');
    }
</script>