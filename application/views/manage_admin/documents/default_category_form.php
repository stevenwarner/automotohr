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
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/default_categories')?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" id="add_cat">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">Add New Category</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row ">
                                                        <label for="category_name">Category Name <span class="hr-required">*</span></label>
                                                        <?php $cat_name = isset($category) ? $category['name'] : ''; ?>
                                                        <?php echo form_input('category_name', set_value('category_name',$cat_name), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('category_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="category_name">Category Description</label>
                                                        <?php $cat_discription = isset($category) ? $category['description'] : ''; ?>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" name="description">
                                                            <?php echo $cat_discription; ?>
                                                        </textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row ">
                                                        <label for="sort_order">Sort Order <span class="hr-required">*</span></label>
                                                        <?php $cat_order = isset($category) ? $category['sort_order'] : ''; ?>
                                                        <input type="number" class="hr-form-fileds" name="sort_order" value="<?= $cat_order?>">
                                                        <?php echo form_error('sort_order'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds">
                                                            <option value="1" <?php echo isset($category) && $category['status'] ? 'selected="selected"' : ''?>>Active</option>
                                                            <option value="0" <?php echo isset($category) && !$category['status'] ? 'selected="selected"' : ''?>>In Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label></label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" value="<?php echo isset($category["sid"]) ? 'Update Category' : 'Add Category'; ?>" name="form-submit">
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
