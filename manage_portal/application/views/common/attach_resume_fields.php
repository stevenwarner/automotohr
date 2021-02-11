    <label class="resume-label" >Attach Resume <?php echo (intval($is_resume_mandatory) == 1 ? '<span class="staric">*</span>' : ''); ?></label>


    <label class="resume-label-withradio" for="attachment_from_google">
        <input type="radio" id="attachment_from_google" name="attachment_from"
               value="google" class="attach_from"> <span>Cloud Storage</span>
    </label>
    <label class="resume-label-withradio" for="attachment_from_computer">
        <input type="radio" id="attachment_from_computer" name="attachment_from"
               value="computer" class="attach_from" checked="checked">
        <span>Computer</span>
    </label>

    <div id="attach_from_computer">
        <div class="form-fields choose-file" name="resume" required="required">
            <div class="file-name" id="name_resume">Please Select</div>
            <input <?php echo (intval($is_resume_mandatory) == 1 ? ' data-rule-required="true" data-msg-required="Resume is Required" ' : ''); ?>  class="choose-file-filed" type="file" name="resume" id="resume" onchange="check_file('resume')">
            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
        </div>
        <?php echo form_error('resume'); ?>
    </div>
    <div id="attach_from_google">
        <?php $unique_key = generateRandomString(30); ?>
        <input type="hidden" id="unique_key" name="unique_key"
               value="<?php echo $unique_key; ?>"/>
        <input type="hidden" id="resume_from_google_drive" name="resume_from_google_drive"
               value=""/>

        <?php if (base_url() == 'http://intranet.dev/') { ?>
            <a id="get_resume_from_google" class="google-btn site-btn bg-color" target="_blank"
               href="http://localhost/automotoCI/google/<?php echo $unique_key; ?>">
                <i class="fa fa-cloud" aria-hidden="true"></i> Choose Cloud Storage</a>
        <?php } else { ?>
            <a id="get_resume_from_google" class="google-btn site-btn bg-color" target="_blank"
               href="<?php echo STORE_FULL_URL_SSL; ?>google/<?php echo $unique_key; ?>">
                <i class="fa fa-cloud" aria-hidden="true"></i> Choose Cloud Storage</a>
        <?php } ?>

        <script>
            $(document).ready(function () {
                $('#get_resume_from_google').on('click', function () {
                    $('#resume_from_google_drive').val(1);
                });

                $('#resume').on('click', function () {
                    $('#resume_from_google_drive').val(0);
                });

                $('#attach_from_google').hide();
                $('.attach_from').each(function () {
                    $(this).on('click, change', function () {
                        var selected = $(this).val();

                        if (selected == 'computer') {
                            $('#attach_from_google').hide();
                            $('#attach_from_computer').show();
                        } else {
                            $('#attach_from_computer').hide();
                            $('#attach_from_google').show();
                        }
                    });
                });

            });




        </script>
    </div>