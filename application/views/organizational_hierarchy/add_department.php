<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('organizational_hierarchy/departments'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <h1 class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </h1>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="universal-form-style-v2">
                                        <form method="post" enctype="multipart/form-data" id="form_add_edit_department" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                            <input type="hidden" id="old_department_name" name="old_department_name" value="<?php echo (isset($department['dept_name']) ? $department['dept_name'] : ''); ?>" />
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <?php $temp = (isset($department['dept_name']) ? $department['dept_name'] : ''); ?>
                                                    <?php echo form_label('Name <span class="hr-required">*</span>', 'dept_name'); ?>
                                                    <?php echo form_input('dept_name', set_value('dept_name', $temp), 'class="invoice-fields"'); ?>
                                                    <?php echo form_error('dept_name'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <?php $temp = (isset($department['dept_description']) ? $department['dept_description'] : ''); ?>
                                                    <?php echo form_label('Description', 'dept_description'); ?>
                                                    <?php echo form_textarea('dept_description', set_value('dept_description', $temp), 'class="invoice-fields-textarea"'); ?>
                                                    <?php echo form_error('dept_description'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">

                                                    <label>Parent:</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php $temp = (isset($department['dept_parent_sid']) ? $department['dept_parent_sid'] : 0); ?>
                                                        <select class="invoice-fields" name="dept_parent_sid" id="dept_parent_sid">
                                                            <option value="0">Please Select</option>
                                                            <?php if(!empty($departments)) { ?>
                                                                <?php foreach($departments as $mydepartment) { ?>
                                                                    <?php $default_selected = ( $temp == $mydepartment['sid'] ? true : false ); ?>
                                                                    <option <?php echo set_select('dept_parent_sid', $mydepartment['sid'], $default_selected); ?> value="<?php echo $mydepartment['sid']?>"><?php echo $mydepartment['dept_name']; ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                </li>
                                            </ul>

                                        </form>
                                    </div>
                                </div>
                                <div class="hr-box-footer hr-innerpadding">
                                    <button type="button" class="btn btn-success" onclick="func_validate_and_submit_form();"><?php echo $submit_btn_text; ?></button>
                                    <a class="btn btn-default" href="<?php echo base_url('organizational_hierarchy/departments'); ?>" >Cancel</a>
                                </div>
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
    function func_validate_and_submit_form(){
        $('#form_add_edit_department').validate({
            rules:{
                dept_name: {
                    required : true
                }
            }

        });

        if($('#form_add_edit_department').valid()){
            $('#form_add_edit_department').submit();
        }
    }
</script>