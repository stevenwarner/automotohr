$(function () {
    //
    var uploadFile = null;
    var courseURL = baseURI+'manage_admin/courses/handler';
    console.log(baseURI)
    console.log(courseURL)
    //
    //
     function uploadZip(zipFile, courseDetails) {
        var fd = new FormData();
        fd.append('upload_zip', zipFile);
        fd.append('action', 'upload_zip');
        //
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: fd,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    courseDetails.upoadFile = resp.Path;
                    saveCourseDetails(courseDetails);
                }
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to upload zip");
                $('.jsLMSLoader').hide();
            }
        });
    }

    //
    function saveCourseDetails (courseDetails) {
        console.log(courseDetails);
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: courseDetails,
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            window.location.href = baseURI+'manage_admin/courses';
                        }
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
            },
            error: function() {
                alertify.alert("Notice", "Unable to save course detail</b>");
                $('.jsLMSLoader').hide();
            }
        });
    }
    //
    $(".jsScormFields").hide(); 
    $('#jsJobTitle')
    .select2({
        closeOnSelect: false
    });

    $('#jsUploadScormFile').mFileUploader({
        allowedTypes: ['zip'],
        fileLimit: -1,
        onSuccess: function(o) {
            uploadFile = o;
        },
        onClear: function(e) {
            uploadFile = null;
        },
    });

    $('.jsCourseType').on('change', function() {
        switch ($(this).val()) {
            case 'manual':
                $(".jsScormFields").hide();
                break;
            case 'scorm':
                $(".jsScormFields").show();
                break;
        }
    }); 

    $(document).on('click', '#jsSaveCourseInfo', function(event) {
        //
        event.preventDefault();
        //
        var courseTitle = $("#jsCourseTitle").val();
        var courseStatus = $("#jsCourseStatus").val();
        var jobTitles = $("#jsJobTitle").val();
        var courseDescription = $("#jsCourseDescription").val();
        var courseType = $('input[name="jsCourseChoice"]:checked').val();
        //
        if (courseTitle == '') {
            alertify.alert("Notice", "Please Enter course Title");
            return false;
        }

        var courseDetails = {
            'action': "add_course",
            'title': courseTitle,
            'status': courseStatus,
            'job_titles': jobTitles,
            'description': courseDescription,
            'course_type': courseType
        };
        //
        if (courseType == "scorm") {
            if (uploadFile == null || Object.keys(uploadFile).length === 0 || uploadFile.error) {
                alertify.alert("WARNING!", "Please upload a zip file.");
                return;
            }
            //
            uploadZip(uploadFile, courseDetails);   
        } else {
            saveCourseDetails(courseDetails);
        }
    });

   
}); 


