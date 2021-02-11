<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
                        <?php $this->load->view('main/manage_ems_left_view'); ?>
                    <?php } else { ?>
                        <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                    <?php } ?> 
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('department_management'); ?>"><i class="fa fa-chevron-left"></i>Department Management</a>
                                    <?php echo !isset($department) ? 'Add Department' : 'Edit Department'; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-university"></i>
                                            <?php echo $title; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily create departments for your Company</p>
                                    <div class="form-wrp">
                                        <form id="form_add_edit_department_info" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="<?php echo $perform_action; ?>" />

                                            <div class="form-group autoheight">
                                                <label for="name">Department Name<span class="staric">*</span></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo isset($department['name']) ? $department['name'] : ''; ?>">
                                                <?php echo form_error('name'); ?>
                                            </div>
                                            <div class="form-group autoheight">
                                                <?php $description = isset($department['description']) ? html_entity_decode($department['description']) : ''; ?>
                                                <label for="description">Department Description</label>
                                                <textarea name="description" cols="40" rows="10" class="form-control ckeditor" style="visibility: hidden; display: none;"><?php echo $description; ?></textarea>
                                                <?php echo form_error('description'); ?>
                                            </div>  
                                            <!-- <div class="form-group autoheight">
                                                <div class="row">
                                                    <?php $status = isset($department['status']) ? $department['status'] : 1; ?>
                                                    <div class="col-lg-12 mb-2"><label>Status</label></div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Active
                                                            <input type="radio" name="status" value="1" <?php echo $status == 1 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Inactive
                                                            <input type="radio" name="status" value="0" <?php echo $status == 0 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group autoheight">
                                                <label for="name">Select Supervisor(s)<span class="staric">*</span></label>
                                                <div class="">
                                                    <select name="supervisor[]" class="invoice-fields" id="supervisor_id" multiple="true">
                                                        <option value="0">Please Select Supervisor</option>
                                                        <?php foreach ($employees as $key => $employee): ?>
                                                            <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['supervisor']) && in_array($employee['sid'], explode(',', $department['supervisor']))  ? 'selected="selected"' : ''; ?>>
                                                                <?php echo $employee['first_name'].' '.$employee['last_name'].' ('.( remakeAccessLevel($employee) ).')'; ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <span id="add_supervisor_error" class="text-danger person_error"></span>
                                                </div>
                                            </div>    
                                            <div class="form-group autoheight">
                                                <label>Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="<?php echo isset($department['sort_order']) ? $department['sort_order'] : ''; ?>">
                                            </div>
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" class="btn btn-success" onclick="validate_form();"><?php echo $submit_button_text; ?></button>
                                                        <a href="<?php echo base_url('department_management'); ?>" class="btn black-btn">Cancel</a>
                                                    </div>
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

<script>
    $(function(){
        $('#supervisor_id').select2({ closeOnSelect: false });
    })
</script>

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function validate_form() {
        var supervisor = $('#supervisor_id').val();
        if(supervisor == 0) {
            $('#add_supervisor_error').text('Supervisor name is required');
        }

        $("#form_add_edit_department_info").validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                },
                supervisor: {
                   required: true
                }
            },
            messages: {
                name: {
                    required: 'Department name is required',
                },
                supervisor: {
                    required: 'Supervisor name is required',
                }
            },
            submitHandler: function (form) {
                var supervisor = $('#supervisor_id').val();
                if(supervisor != 0) {
                    form.submit();
                }    
            }
        });
    }
</script>