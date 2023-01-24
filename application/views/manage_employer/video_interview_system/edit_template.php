<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a class="dashboard-link-btn" href="<?php echo base_url('video_interview_system/templates'); ?>">
                                <i class="fa fa-chevron-left"></i>
                                Back
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                </div>      
                <div class="row">
                    <div class="col-lg-12">
                        <div class="multistep-progress-form">
                            <form id="edit_template" name='edit_template' class="msform" method="post">
                                <fieldset id="create_div">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li>
                                                <div class="description-editor">
                                                    <label>Template title : <span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" name="title" id="title" value="<?php echo (isset($template['title'])) ? $template['title'] : ''; ?>"/>
                                                    <?php echo form_error('title'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="description-editor">
                                                    <label>Description : <span class="staric">*</span></label>
                                                    <textarea class="invoice-fields-textarea" name="description" id="description" cols="60" rows="10">
                                                        <?php echo (isset($template['description'])) ? $template['description'] : ''; ?>
                                                    </textarea>
                                                    <?php echo form_error('description'); ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="text-right">
                                        <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo base_url('video_interview_system/templates'); ?>'" />
                                        <input type="submit" value="Submit" class="submit-btn" id="edit_template_submit" name="edit_template_submit">
                                    </div>
                                </fieldset>
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
<script>
    $(document).ready(function(){
        var descp = ($('#description').val()).trim();
        $('#description').val(descp);
    });
    
    $('#edit_template_submit').click(function () { 
        $("#edit_template").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Template title is required'
                },
                description: {
                    required: 'Template description is required'
                }
            }
        });
    });
</script>