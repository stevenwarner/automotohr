<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('organizational_hierarchy/vacancies'); ?>" class="dashboard-link-btn">
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
                                        <form method="post" enctype="multipart/form-data" id="form_add_edit_vacancies" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <label>Position <span class="hr-required">*</span></label>
                                                    <div class="hr-select-dropdown">
                                                        <?php $temp = (isset($vacancy['position_sid']) ? $vacancy['position_sid'] : 0); ?>
                                                        <select data-rule-required="true" class="invoice-fields" name="position_sid" id="position_sid">
                                                            <option value="">Please Select</option>
                                                            <?php if(!empty($positions)) { ?>
                                                                <?php foreach($positions as $position) { ?>
                                                                    <?php $default_selected = ( $temp == $position['sid'] ? true : false ); ?>
                                                                    <option <?php echo set_select('position_sid', $position['sid'], $default_selected); ?> value="<?php echo $position['sid']?>"><?php echo $position['position_name']; ?>&nbsp;(<?php echo $position['department_name'] != '' ? $position['department_name'] : 'General'; ?>)</option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Number of Vacancies Available <span class="hr-required">*</span></label>
                                                    <div class="hr-select-dropdown">
                                                        <?php $temp = (isset($vacancy['vacancies_count']) ? $vacancy['vacancies_count'] : 0); ?>
                                                        <select data-rule-required="true" class="invoice-fields" name="vacancies_count" id="vacancies_count">
                                                            <option value="">Please Select</option>
                                                            <?php for($count = 1; $count <= 10; $count++) { ?>
                                                                <?php $default_selected = ( $temp == $count ? true : false ); ?>
                                                                <option <?php echo set_select('vacancies_count', $count, $default_selected); ?> value="<?php echo $count?>"><?php echo str_pad($count, 2, 0, STR_PAD_LEFT); ?></option>
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
    function func_validate_and_submit_form(){
        $('#form_add_edit_vacancies').validate({
            rules: {
                position_sid: {
                    required: true
                },
                vacancies_count: {
                    required: true
                }
            }
        });

        if ($('#form_add_edit_vacancies').valid()) {
            $('#form_add_edit_vacancies').submit();
        }
    }
</script>