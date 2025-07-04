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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-files-o"></i>Hr Documents Content</h1>
                                    </div>

                                    <?php if (check_access_permissions_for_view($security_details, 'hr_doc_add')) { ?>
                                        <div class="add-new-promotions">
                                            <a href="<?php echo base_url('manage_admin/hr_documents_content/add_section')?>" class="btn btn-success">Add Section</a>
                                        </div>
                                    <?php } ?>
                                    <br />
                                    <br />
                                    <br />

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-8">Section Title</th>
                                                            <th class="col-xs-1">Status</th>
                                                            <th class="col-xs-1">Sort Order</th>
                                                            <th class="col-xs-2" colspan="2">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($sections)) { ?>
                                                            <?php foreach($sections as $section) { ?>
                                                                <tr>
                                                                    <td><?php echo $section['title']; ?></td>
                                                                    <td><?php echo $section['status'] == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">In-Active</span>'; ?></td>
                                                                    <td><?php echo $section['sort_order']; ?></td>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'hr_doc_edit')) { ?>
                                                                        <td>
                                                                            <a href="<?php echo base_url('manage_admin/hr_documents_content/edit_section/' . $section['sid']);?>" class="btn btn-success btn-sm btn-block">Edit</a>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'hr_doc_delete')) { ?>
                                                                        <td>
                                                                            <form id="form_delete_section_<?php echo $section['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_section" />
                                                                                <input type="hidden" id="section_sid" name="section_sid" value="<?php echo $section['sid']; ?>" />
                                                                            </form>
                                                                            <button onclick="func_delete_section(<?php echo $section['sid']; ?>);" type="button" class="btn btn-danger btn-sm btn-block">Delete</button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="4">
                                                                    <span class="no-data">No Sections</span>
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

<script>
    function func_delete_section(section_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to Delete this section?',
            function () {
                $('#form_delete_section_' + section_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>
