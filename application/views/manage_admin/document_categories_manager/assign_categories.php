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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-tags"></i>INDUSTRY -  <?php echo strtoupper($industry['industry_name']); ?></h1>
                                        <a href="<?php echo base_url('manage_admin/document_categories_manager/document_category_industries') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Industries</a>
                                    </div>
                                    <form id="contactForm" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <div class="add-new-company">
                                            <input type="hidden" id="industry_sid" name="industry_sid" value="<?php echo $industry['sid']; ?>" />
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <label class="control control--checkbox pull-left">
                                                        <input type="checkbox" id="selectall">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <h4 class="hr-registered pull-left"> All </h4>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="row">
                                                        <?php if (!empty($categories)) { 
                                                                foreach ($categories as $key => $category) { 
                                                                    $cat_name = 'categories'; ?>
                                                                    <div class="col-xs-6">
                                                                        <label class="control control--checkbox font-normal">
                                                                            <?php echo $category['name']; ?>
                                                                            <?php $default_selected = in_array($category['sid'], $industry_specific_cat_sids) ? true : false; ?>
                                                                            <input <?php echo set_radio('categories', $category['sid'], $default_selected) ?> id="category_<?php echo $category['sid']; ?>" class="ej_checkbox" name="<?php echo $cat_name;?>[]" value="<?php echo $category['sid']; ?>" type="checkbox">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                        <?php   } ?>
                                                        <?php } else { ?>
                                                            <div class="col-xs-12 text-center">
                                                                <span class="no-data">No Categories</span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <a class="black-btn btn full-on-small" href="<?php echo base_url('manage_admin/document_categories_manager/document_category_industries/'); ?>">Cancel</a>
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

<script>
    //select all checkboxex on one click
    $('#selectall').click(function (event) {  //on click
        if (this.checked) { // check select status
            $('.ej_checkbox').each(function () { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        } else {
            $('.ej_checkbox').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
    
</script>
