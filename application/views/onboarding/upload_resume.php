<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-wrp">
                    <form id="form_upload_resume" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="upload_resume" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Upload Resume:</label>
                                    <div class="upload-file form-control">
                                        <span class="selected-file" id="name_upload_resume">No Resume Selected</span>
                                        <input name="upload_resume" id="upload_resume" onchange="check_resume_format('upload_resume')"  type="file">
                                        <a href="javascript:;">Choose</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <input type="file" name="file" id="drop_box_file">
            
                                    <!-- Drag and Drop container-->
                                    <div class="upload-area"  id="uploadfile">
                                        <h1>To Upload Resume Click Here</br>&<br/>Drag and Drop Resume Here</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button onclick="upload_user_resume('upload_resume');" type="button" class="btn btn-info" >
                                        Upload Resume
                                    </button>
                                </div>
                            </div>        
                        </div>
                    </form> 
                </div>
            </div>        
        </div>
    </div>
</div>
<style type="text/css">
    .upload-area{
        width: 100%;
        height: 200px;
        border: 2px solid lightgray;
        border-radius: 3px;
        margin: 0 auto;
        margin-top: 100px;
        text-align: center;
        overflow: auto;
    }

    .upload-area:hover{
        cursor: pointer;
    }

    .upload-area h1{
        text-align: center;
        font-weight: normal;
        font-family: sans-serif;
        line-height: 50px;
        color: darkslategray;
    }

    #drop_box_file{
        display: none;
    }
</style>
<script>
     
    var format_error = '<p style="color: red; font-weight: 600;">Only (.pdf .docx .doc) allowed!</p>';
    var empty_error = '<p style="color: red; font-weight: 600;">Please Select Resume</p>';
    $(function() {

        // Open file selector on div click
        $("#uploadfile").click(function(){
            $("#upload_resume").click();
        });

        uploadfile.ondragover = uploadfile.ondragenter = function(evt) {
            evt.preventDefault();
        };

        uploadfile.ondrop = function(evt) {
            upload_resume.files = evt.dataTransfer.files;
            evt.preventDefault();
            uploadDragBoxData();
        };

        // preventing page from redirecting
        $("html").on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("h1").text("Drag Resume Here");
        });


        //Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop Resume");
        });
    });

    function check_resume_format(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            
            if (val == 'upload_resume') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC") {
                    $("#" + val).val(null);
                    $('#name_' + val).html(format_error);
                }
            }
        }
    }

    function upload_user_resume (val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            
            if (val == 'upload_resume') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC") {
                    $("#" + val).val(null);
                    $('#name_' + val).html(format_error);
                } else {
                    submit_upload_file();
                }

            }
        } else {
            $('#name_' + val).html(empty_error);
        }
    }

    // Sending AJAX request and upload file
    function uploadDragBoxData(){
        var fileName = $("#upload_resume").val();

        if (fileName.length > 0) {
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "rtf" && ext != "RTF") {
                $("#drop_box_file").val(null);
                $("h1").text('Only (.pdf .docx .doc .rtf) allowed!');
            } else {
                submit_upload_file();
            }
        } else {
            $("h1").text('Please Select Resume');
        }
    }

    function submit_upload_file () {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to Upload this Resume?',
            function () {
                $('#form_upload_resume').submit();
            },
            function () {
                alertify.error('Cancelled!');
            }).set('labels', {ok: 'Yes!', cancel: 'Cancel'});
    }
</script>