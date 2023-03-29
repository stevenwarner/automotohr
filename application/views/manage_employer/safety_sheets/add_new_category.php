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
                            <?php echo isset($category) ? 'Update Safety Sheet Category' : 'Add New Category'; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="add-new-company">
                            <form action="" method="POST" id="add_cat">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="field-group">
                                            <label for="category_name">Category Name <span class="hr-required">*</span></label>
                                            <?php $cat_name = isset($category) ? $category['name'] : ''; ?>
                                            <?php echo form_input('category_name', set_value('category_name',$cat_name), 'class="form-control"'); ?>
                                            <?php echo form_error('category_name'); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="field-group ">
                                            <label for="sort_order">Sort Order <span class="hr-required">*</span></label>
                                            <?php $cat_order = isset($category) ? $category['sort_order'] : ''; ?>
                                            <input type="number" class="form-control" name="sort_order" value="<?= $cat_order?>">
                                            <?php echo form_error('sort_order'); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="field-group">
                                            <label for="status">Status <span class="hr-required">*</span></label>
                                            <select name="status" class="form-control">
                                                <option value="1" <?php echo isset($category) && $category['status'] ? 'selected="selected"' : ''?>>Active</option>
                                                <option value="0" <?php echo isset($category) && !$category['status'] ? 'selected="selected"' : ''?>>In Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="field-group">
                                            <label></label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center btn-panel">
                                        <input type="submit" class="btn btn-success" value="<?php echo isset($category) ? 'Update Category' : 'Add Category'; ?>" name="form-submit">
                                        <a href="<?php echo base_url('safety_sheets/manage_safety_sheets')?>" class="btn btn-default"> Cancel</a>

                                    </div>
                                </div>
                            </form>
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

    $(function () {
        $("#add_cat").validate({
            ignore: ":hidden:not(select)",
            debug: false,
            rules: {
                category_name: {
                    required: true
                },
                sort_order: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                category_name: {
                    required: 'Category name is required'
                },
                sort_order: {
                    required: 'Please provide sort order'
                },
                status: {
                    required: 'Please provide status'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });

</script>
