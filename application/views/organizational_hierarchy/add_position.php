<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('organizational_hierarchy/positions'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>

                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
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
                                        <form method="post" enctype="multipart/form-data" id="form_add_edit_position" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="position_sid" name="position_sid" value="<?php echo (isset($position['sid']) ? $position['sid'] : ''); ?>" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                            <input type="hidden" id="old_position_name" name="old_position_name" value="<?php echo (isset($position['position_name']) ? $position['position_name'] : ''); ?>" />
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <?php $temp = (isset($position['position_name']) ? $position['position_name'] : ''); ?>
                                                    <?php echo form_label('Name <span class="hr-required">*</span>', 'position_name'); ?>
                                                    <?php echo form_input('position_name', set_value('position_name', $temp), 'class="invoice-fields"'); ?>
                                                    <?php echo form_error('position_name'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <?php $temp = (isset($position['position_description']) ? $position['position_description'] : ''); ?>
                                                    <?php echo form_label('Description', 'position_description'); ?>
                                                    <?php echo form_textarea('position_description', set_value('position_description', $temp), 'class="invoice-fields-textarea"'); ?>
                                                    <?php echo form_error('position_description'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Department</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php $temp = (isset($position['department_sid']) ? $position['department_sid'] : 0); ?>
                                                        <select data-rule-required="true" class="invoice-fields" name="department_sid" id="department_sid">
                                                            <option value="">Please Select</option>
                                                            <?php if(!empty($departments)) { ?>
                                                                <?php foreach($departments as $department) { ?>
                                                                    <?php $default_selected = ( $temp == $department['sid'] ? true : false ); ?>
                                                                    <option <?php echo set_select('department_sid', $department['sid'], $default_selected); ?> value="<?php echo $department['sid']?>"><?php echo $department['dept_name']; ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li class="form-col-100 autoheight">
                                                    <label>Parent</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php $temp = (isset($position['parent_sid']) ? $position['parent_sid'] : 0); ?>
                                                        <select data-rule-required="true" class="invoice-fields" name="parent_sid" id="parent_sid">
                                                            <option value="">Please Select</option>
                                                            <?php if(!empty($positions)) { ?>
                                                                <?php foreach($positions as $myposition) { ?>
                                                                    <?php $default_selected = ( $temp == $myposition['sid'] ? true : false ); ?>
                                                                    <option <?php echo set_select('position_sid', $myposition['sid'], $default_selected); ?> value="<?php echo $myposition['sid']?>"><?php echo $myposition['position_name']; ?></option>
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
                                    <a class="btn btn-default" href="<?php echo base_url('organizational_hierarchy/positions'); ?>" >Cancel</a>
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
    function func_validate_and_submit_form() {
        $('#form_add_edit_position').validate({
            rules: {
                position_name: {
                    required: true
                }
            }

        });

        if ($('#form_add_edit_position').valid()) {
            $('#form_add_edit_position').submit();
        }
    }

    $('#department_sid').on('change', function () {
        var selected_value = $(this).val();
        var myUrl = '<?php echo base_url('organizational_hierarchy/ajax_responder'); ?>';

        var current_position_sid = $('#position_sid').val();

        if (selected_value != '' || selected_value != null || selected_value != undefined) {
            var data_to_send = {'department_sid': selected_value, 'perform_action': 'get_positions', 'current_position_sid' : current_position_sid };

            var myRequest;

            myRequest = $.ajax({
                url: myUrl,
                type: 'POST',
                data: data_to_send
            });

            myRequest.done(function (response) {
                $('#parent_sid').html(response);
            });
        }
    });
</script>