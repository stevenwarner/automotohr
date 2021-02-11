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
                                        <h1 class="page-title"><i class="fa fa-group"></i> Corporate Groups</h1>
                                        <a href="<?php echo base_url('manage_admin/corporate_groups'); ?>" class="black-btn pull-right">
                                            <i class="fa fa-long-arrow-left"></i> Corporate Groups
                                        </a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        </div>
                                        <form id="form_add_edit_automotive_group" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <?php $temp = ( isset($automotive_group['group_name']) ? $automotive_group['group_name'] : '');?>
                                                        <label for="group_name">Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('group_name', set_value('group_name', $temp), 'class="hr-form-fileds" id="group_name"'); ?>
                                                        <?php echo form_error('group_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="country">Country <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="group_country" name="group_country">
                                                                <option value="">Select Country</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <option value="<?php echo $active_country["sid"]; ?>"
                                                                        <?php if (isset($automotive_group['group_country_sid']) && $active_country["sid"] == $automotive_group['group_country_sid']) { ?>
                                                                            selected
                                                                        <?php } ?>>
                                                                        <?php echo $active_country["country_name"]; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('group_country'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row autoheight form-group">
                                                        <?php $temp = ( isset($automotive_group['group_description']) ? $automotive_group['group_description'] : '');?>
                                                        <label for="group_description">Description</label>
                                                        <?php echo form_textarea('group_description', set_value('group_description', $temp), 'class="hr-form-fileds field-row-autoheight" id="group_description"'); ?>
                                                        <?php echo form_error('group_description'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php if(!empty($automotive_group) && isset($automotive_group['sid'])) { ?>
                                                        <input onclick="func_confirm_and_submit();" type="button" value="Update Corporate Group" class="btn btn-success full-on-small" />
                                                    <?php } else { ?>
                                                        <input type="submit" value="Add Corporate Group" class="btn btn-success full-on-small" />
                                                    <?php } ?>
                                                    <a class="black-btn full-on-small btn" href="<?php echo base_url('manage_admin/corporate_groups'); ?>">Cancel</a>
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

<?php if(!empty($automotive_group)) { ?>
<script>
    function func_confirm_and_submit(){
        $('#form_add_edit_automotive_group').submit();
    }


</script>
<?php } ?>