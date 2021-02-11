
    <label class="resume-label">Share a video of Yourself</label>
    <label class="resume-label-withradio" for="upload">
        <input type="radio" id="upload" name="share_url" value="upload" class="share-field">
        <span>Upload</span>
    </label>
    <label class="resume-label-withradio" for="yt_url">
        <input type="radio" id="yt_url" name="share_url" value="url" class="share-field" checked="checked">
        <span>URL</span>
    </label>


    <div id="url-div">
        <input class="form-fields" type="text" name="YouTube_Video" id="YouTube_Video" placeholder="Youtube Video Link" onblur="return youtube_check()" value="<?php
        if (isset($formpost['YouTube_Video'])) {
            echo $formpost['YouTube_Video'];
        }
        ?>">
        <p id="video_link"></p>
        <?php echo form_error('YouTube_Video'); ?>
    </div>


    <div id="upload-div">
        <div class="form-fields choose-file">
            <div class="file-name" id="name_uploaded_file">Please Select</div>
            <input class="choose-file-filed" type="file" name="uploaded_file" id="uploaded_file" onchange="check_upload_document('uploaded_file')">
            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $('#upload-div').hide();

            $('.share-field').each(function () {
                $(this).on('click, change', function () {
                    var selected = $(this).val();

                    if (selected == 'url') {
                        $('#upload-div').hide();
                        $('#url-div').show();
                    } else {
                        $('#YouTube_Video').val('');
                        $('#url-div').hide();
                        $('#upload-div').show();
                    }
                });
            });

        });

        function check_upload_document(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 35));
                var ext = fileName.split('.').pop();

                if(ext == "mp4" || ext == "m4a" || ext == "m4v" || ext == "f4v" || ext == "f4a" || ext == "m4b" || ext == "m4r" || ext == "f4b" || ext == "mov" || ext == 'mp3'){
                    var file_size = Number((document.getElementById(val).files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    }
                }else{
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.mp4 .m4a .f4v .f4a .m4b .m4r .f4b .mov .mp3) allowed!</p>');
                }

            } else {
                $('#name_' + val).html('No video selected');
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
            }

        }
    </script>