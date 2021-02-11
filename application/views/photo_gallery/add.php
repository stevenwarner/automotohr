<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('photo_gallery'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                </div>
                <div class="page-header-area">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <!-- new image form -->
                    <div class="col-lg-12">
                        <div class="multistep-progress-form">
                            <form action="<?php echo base_url('photo_gallery/add'); ?>" method="POST" enctype="multipart/form-data" id='insert_photo_form' onsubmit='return validate();'>
                                <fieldset>
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="col-md-6">
                                                <label>Title :<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" name="title" id="title">
                                                <?php echo form_error('title'); ?>
                                            </li>
                                            <li class="col-md-6">
                                                <label>Upload a photo :<span class="staric">*</span></label>
                                                <div class="upload-file invoice-fields" id="pic">
                                                    <span class="selected-file" id="name_pictures">No file selected</span>
                                                    <input type="file" name="pictures" id="pictures" onchange="check_file('pictures');">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                                <p id="name_pictures_new"></p>
                                            </li>
                                            <li class="col-md-6">
                                                <input type="submit" value="Upload" onclick='return insert_photo_form_validate();' class="submit-btn">
                                                <a href="<?php echo base_url('photo_gallery'); ?>" class="submit-btn">Cancel</a>
                                            </li>
                                        </ul>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <!-- new image form -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 35));
            var ext = fileName.split('.').pop();
            
            $('#pic').css('border-color', '#aaa');
            $('#name_pictures_new').html('');

            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
            else if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function insert_photo_form_validate() {

        $("#insert_photo_form").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Photo title is required'
                }
            }
        });
    }

    function validate() {
        var file_name = $("#pictures").val();

        if (file_name.length > 0) {
            $('#pic').css('border-color', '#aaa');
            $('#name_pictures_new').html('');
        } else {
            $('#name_pictures_new').html('<span style="color:red;font-size:16px;">File photo is required</span>');
            $('#pic').css('border-color', 'red');
            return false;
        }
    }
</script>
