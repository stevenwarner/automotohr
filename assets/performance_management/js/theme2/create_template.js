// 
$(function() {

    var obj = {
        Id: pm.template !== undefined ? pm.template.sid : 0,
        Title: pm.template !== undefined ? pm.template.name : '',
        Questions: pm.template !== undefined && pm.template.questions != '' && pm.template.questions != undefined ? JSON.parse(pm.template.questions) : [],
    };

    loadQuestions();

    //
    var questionFile = null;

    window.questionFile = questionFile;

    window.REVIEW = obj;
    //
    $('#jsReviewQuestionAddQuestionType').select2({ minimumResultsForSearch: -1 });

    //
    var cp = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorder',
        previewPlayer: 'jsVideoPreview',
        recordButton: 'jsVideoRecordButton',
        playRecordedVideoBTN: 'jsVideoPlayVideo',
        removeRecordedVideoBTN: 'jsVideoRemoveButton',
        pauseRecordedVideoBTN: 'jsVideoPauseButton',
        resumeRecordedVideoBTN: 'jsVideoResumeButton',
    });

    // Events


    /**
     * 
     */
    $('#jsReviewQuestionAddBtn').click(function(event) {
        //
        event.preventDefault();
        //
        $('.jsPageSection[data-page="schedule"]').addClass('dn');
        $('#jsReviewQuestionSaveBtn').removeClass('dn');
        $('#jsReviewQuestionEditBtn').addClass('dn');
        //
        $('#jsReviewQuestionListBox').addClass('dn');
        $('#jsReviewQuestionAddBox').removeClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        $('#jsVideoPreviewBox').next('video').remove();
        //
        $('#jsReviewQuestionAddPreviewTitle').text('');
        $('#jsReviewQuestionAddPreviewDescription').text('');
        //
        $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').val('');
        $('#jsReviewQuestionAddQuestionType').select2('val', 'text');
        //
        $('.jsReviewQuestionAddVideoType[value="none"]').prop('checked', 'true').trigger('click');
        //
        questionFile = null;
        //
        $('#jsReviewQuestionAddVideoUploadInp').mFileUploader({
            allowedTypes: ['mp4', 'webm'],
            fileLimit: '2mb',
            onSuccess: function(o) {
                questionFile = o;
                updatePreview();
            },
            onClear: function(e) {
                questionFile = null;
                updatePreview();
            },
        });
        //
        cp.close();
    });

    /**
     * 
     */
    $(document).on('click', '.csReviewQuestionEdit', function(event) {
        //
        event.preventDefault();
        //
        var question = obj.Questions[$(this).closest('.jsReviewQuestionRow').data('index')];
        //
        $('#jsReviewQuestionSaveBtn').addClass('dn');
        $('#jsReviewQuestionEditBtn').removeClass('dn');
        //
        $('#jsReviewQuestionListBox').addClass('dn');
        $('#jsReviewQuestionAddBox').removeClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        $('#jsVideoPreviewBox').next('video').remove();
        //
        $('#jsReviewQuestionAddPreviewTitle').text('');
        $('#jsReviewQuestionAddPreviewDescription').text('');
        //
        $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').val('');
        $('#jsReviewQuestionAddQuestionType').select2('val', 'text');
        //
        $('.jsReviewQuestionAddVideoType[value="none"]').prop('checked', 'true').trigger('click');
        //
        questionFile = null;
        //
        $('#jsReviewQuestionAddVideoUploadInp').mFileUploader('clear');
        //
        cp.close();
        //
        $('#jsReviewQuestionAddTitle').val(question.title);
        $('#jsReviewQuestionAddDescription').val(question.description);
        $('.jsReviewQuestionAddVideoType[value="' + (question.video_help) + '"]').prop('checked', 'true').trigger('click');
        $('#jsReviewQuestionAddQuestionType').select2('val', question.question_type);
        //
        if (question.video_help == 'upload') {
            questionFile = question.video;
            $('#jsReviewQuestionAddVideoUploadInp').mFileUploader({
                allowedTypes: ['mp4', 'webm'],
                fileLimit: '2mb',
                onSuccess: function(o) {
                    questionFile = o;
                    updatePreview();
                },
                onClear: function(e) {
                    questionFile = null;
                    updatePreview();
                },
                path: false,
                placeholderImage: pm.urls.base + 'assets/performance_management/videos/' + obj.id + '/' + questionFile
            });
        }
        $('#jsReviewQuestionEditBtn').data('index', $(this).closest('.jsReviewQuestionRow').data('index'));
    });

    /**
     * 
     */
    $('#jsReviewQuestionToList').click(function(event) {
        //
        event.preventDefault();
        //
        $('#jsReviewQuestionListBox').removeClass('dn');
        $('#jsReviewQuestionAddBox').addClass('dn');
        $('.jsPageSection[data-page="schedule"]').removeClass('dn');
    });

    /**
     * 
     */
    $('.jsReviewQuestionAddVideoType').click(function() {
        //
        $('#jsReviewQuestionAddVideoRecord').addClass('dn');
        $('#jsReviewQuestionAddVideoUpload').addClass('dn');
        //
        $('.jsVideoPreviewBox').addClass('dn');
        //
        cp.close();
        //
        switch ($(this).val()) {
            case "record":
                $('#jsReviewQuestionAddVideoRecord').removeClass('dn');
                cp.init();
                break;
            case "upload":
                $('#jsReviewQuestionAddVideoUpload').removeClass('dn');
                break;
        }
        //
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').keyup(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionAddQuestionType').change(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var question = {
            id: getRandomId(),
            title: $('#jsReviewQuestionAddTitle').val().trim(),
            description: $('#jsReviewQuestionAddDescription').val().trim(),
            video_help: $('.jsReviewQuestionAddVideoType:checked').val(),
            video: "",
            sort_order: "1",
            not_applicable: "0",
            question_type: $('#jsReviewQuestionAddQuestionType').val()
        };
        //
        if (question.title == '') {
            handleError("Please add the question title");
            return;
        }

        // 
        if (question.video_help == 'record') { // Upload Recorded Video
            //
            uploadRecordedVideo(question);
        } else if (question.video_help == 'upload') { // Upload video
            //
            if (Object.keys(questionFile).length === 0 || questionFile.error) {
                handleError("Please upload a video.");
                return;
            }
            //
            ml(true, 'review', 'Please wait, while we are uploading the video.');
            //
            uploadVideo(question, questionFile);
        } else {
            saveQuestion(question);
        }
        //

    });

    /**
     * 
     */
    $('#jsReviewQuestionEditBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var question = obj.Questions[$(this).data('index')];
        //
        question.title = $('#jsReviewQuestionAddTitle').val().trim();
        question.description = $('#jsReviewQuestionAddDescription').val().trim();
        question.video_help = $('.jsReviewQuestionAddVideoType:checked').val();
        question.question_type = $('#jsReviewQuestionAddQuestionType').val();
        //
        if (question.title == '') {
            handleError("Please add the question title");
            return;
        }

        // 
        if (question.video_help == 'record' && questionFile != null) { // Upload Recorded Video
            //
            uploadRecordedVideo(question);
            return;
        } else if (question.video_help == 'upload' && questionFile != null) { // Upload video
            //
            if (Object.keys(questionFile).length === 0 || questionFile.error) {
                handleError("Please upload a video.");
                return;
            }
            //
            ml(true, 'review', 'Please wait, while we are uploading the video.');
            //
            uploadVideo(question, questionFile);
            return;
        } else if (question.video_help == 'record' || question.video_help == 'upload') {
            question.video = questionFile;
        }
        //
        updateQuestion(question);
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewRemoveQuestion', function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewQuestionRow').data('id');
        //
        alertify.confirm(
            "Are you sure you want to remove this question?",
            function() {
                //
                ml(true, 'review', 'Please wait, while we are removing the question.');
                //
                removeQuestion(id);
            }
        );
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewQuestionUp', function(event) {
        //
        event.preventDefault();
        //
        shiftQuestion($(this).closest('.jsReviewQuestionRow').data('id'), 'up');
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewQuestionDown', function(event) {
        //
        event.preventDefault();
        //
        shiftQuestion($(this).closest('.jsReviewQuestionRow').data('id'), 'down');
    });

    /**
     * 
     */
    $('#jsReviewQuestionsSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Id = obj.Id;
        o.name = $('#jsTemplateName').val().trim();
        //
        if (o.name == '') {
            handleError("Template name is required.");
            return;
        }
        //
        ml(true, 'template', 'Please wait, while we are saving the changes.');
        //
        $.post(
            pm.urls.pbase + 'save_template',
            o
        ).done(function(resp) {
            handleSuccess('You have successfully created a template.', function() {
                window.location.href = pm.urls.pbase + 'templates';
            });
        });
    });

    /**
     * 
     */
    $('.jsTemplateSave').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Id = obj.Id;
        o.name = $('#jsTemplateName').val().trim();
        //
        if (o.name == '') {
            handleError("Template name is required.");
            return;
        }
        //
        ml(true, 'template', 'Please wait, while we are saving the changes.');
        //
        $.post(
            pm.urls.pbase + 'save_template',
            o
        ).done(function(resp) {
            obj.Id = resp.Id;
            //
            window.location.href = pm.urls.pbase + 'template/create/' + resp.Id;
        });
    });

    //
    function uploadRecordedVideo(question) {

        cp.getVideo()
            .then(
                function(video) {
                    //
                    if (video == 'data:') {
                        handleError("Please record a video.");
                        return;
                    }
                    //
                    ml(true, 'review', 'Please wait, while we are uploading the video.');
                    //
                    var fd = new FormData();
                    fd.append('file', video);
                    fd.append('reviewId', obj.Id);
                    fd.append('type', 'record');
                    fd.append('step', 'SaveVideo');
                    //
                    $.ajax({
                        url: pm.urls.pbase + 'save_template_step',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                    }).done(function(resp) {
                        //
                        if (resp.Status === false) {
                            ml(false, 'review');
                            handleError('Failed to save video.');
                            return false;
                        }
                        //
                        cp.close();
                        //
                        question.video = resp.Id;
                        //
                        saveQuestion(question);
                    });
                },
                function(error) {
                    handleError("Please record the video first.");
                }
            );
    }

    //
    function uploadVideo(question, video) {
        //
        var fd = new FormData();
        fd.append('file', video);
        fd.append('reviewId', obj.Id);
        fd.append('type', 'upload');
        fd.append('step', 'SaveVideo');
        //
        $.ajax({
            url: pm.urls.pbase + 'save_template_step',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
        }).done(function(resp) {
            //
            if (resp.Status === false) {
                handleError('Failed to save video.');
                return false;
            }
            //
            question.video = resp.Id;
            //
            saveQuestion(question);
        });
    }

    //
    function saveQuestion(question) {
        //
        ml(true, 'review', 'Please wait, while we are saving the question.');
        //
        $.post(pm.urls.pbase + 'save_template_step', {
            step: 'InsertQuestion',
            data: question,
            id: obj.Id
        }).done(function(resp) {
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to update question.');
                return false;
            }
            //
            handleSuccess('You have successfully updated a question.', function() {
                //
                obj.Questions[obj.Questions.length] = question;
                //
                loadQuestions();
                //
                $('#jsReviewQuestionListBox').removeClass('dn');
                $('#jsReviewQuestionAddBox').addClass('dn');
                $('.jsPageSection[data-page="schedule"]').removeClass('dn');
            });
        });
    }

    //
    function updateQuestion(question) {
        //
        ml(true, 'review', 'Please wait, while we are updating the question.');
        //
        $.post(pm.urls.pbase + 'save_template_step', {
            step: 'UpdateQuestion',
            data: question,
            id: obj.Id
        }).done(function(resp) {
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to update question.');
                return false;
            }
            //
            handleSuccess('You have successfully updated a question.', function() {
                //
                cp.close();
                //
                obj.Questions[question.sort_order] = question;
                //
                loadQuestions();
                //
                $('#jsReviewQuestionListBox').removeClass('dn');
                $('#jsReviewQuestionAddBox').addClass('dn');
                $('.jsPageSection[data-page="schedule"]').removeClass('dn');
            });
        });
    }

    //
    function removeQuestion(questionId) {
        //
        $.post(pm.urls.pbase + 'save_template_step', {
            step: 'RemoveQuestion',
            question_id: questionId,
            id: obj.Id
        }).done(function(resp) {
            //
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to remove question.');
                return false;
            }
            //
            handleSuccess('You have successfully removed a question.', function() {
                //
                delete obj.Questions[resp.Index];
                //
                loadQuestions();
            });
        });
    }

    //
    function shiftQuestion(questionId, direction) {
        //
        ml(true, 'template');
        //
        var currentIndex, currentQuestion, tmp;
        //
        obj.Questions.map(function(question, index) {
            if (question.id == questionId) {
                currentIndex = index;
                currentQuestion = question;
            }
        });
        //
        if (direction == 'down') {
            //
            tmp = obj.Questions[currentIndex + 1]; // Get the next index
            tmp['sort_order']--; // Decrease it by 1
            currentQuestion['sort_order']++; // Increase it by 1
            obj.Questions[currentIndex + 1] = currentQuestion; // Move the question to the next index
            obj.Questions[currentIndex] = tmp; // Move the next index question to current index
        } else if (direction == 'up') {
            //
            tmp = obj.Questions[currentIndex - 1]; // Get the next index
            tmp['sort_order']++; // Decrease it by 1
            currentQuestion['sort_order']--; // Increase it by 1
            obj.Questions[currentIndex - 1] = currentQuestion; // Move the question to the next index
            obj.Questions[currentIndex] = tmp; // Move the next index question to current index
        }
        //
        $.post(pm.urls.pbase + 'save_template_step', {
            step: 'ReviewStep4',
            questions: obj.Questions,
            id: obj.Id
        }).done(function(resp) {
            //
            ml(false, 'template');
            //
            loadQuestions();
        });
    }



    //
    function updatePreview() {
        //
        var question = {
            title: $('#jsReviewQuestionAddTitle').val().trim(),
            description: $('#jsReviewQuestionAddDescription').val().trim(),
            video_help: $('.jsReviewQuestionAddVideoType:checked').val(),
            type: $('#jsReviewQuestionAddQuestionType').val(),
            file: questionFile
        };
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTitle').text(question.title);
        $('#jsReviewQuestionAddPreviewDescription').text(question.description);
        //
        if (question.file != null) {
            $('#jsReviewQuestionAddPreviewVideo').removeClass('dn');
            //
            var
                videoURL,
                videoType;
            //
            if (typeof(question.file) === 'object') {
                videoURL = URL.createObjectURL(question.file);
                videoType = question.type;
            } else {
                videoURL = pm.urls.base + 'assets/performance_management/videos/' + (obj.Id) + '/' + question.file;
                videoType = getVideoType(question.file);
            }
            //
            var video = '';
            video += '<video controls style="width: 100%">';
            video += '  <source src="' + (videoURL) + '" type="' + (videoType) + '"></source>';
            video += '</video>';
            $('#jsReviewQuestionAddPreviewVideo').append(video);
        }

        //
        if (question.type.match(/multiple/ig) !== null) {
            $('#jsReviewQuestionAddPreviewMultipleChoiceBox').removeClass('dn');
        }
        //
        if (question.type.match(/rating/ig) !== null) {
            $('#jsReviewQuestionAddPreviewRatingBox').removeClass('dn');
        }
        //
        if (question.type.match(/text/ig) !== null) {
            $('#jsReviewQuestionAddPreviewTextBox').removeClass('dn');
        }
    }


    //
    function loadQuestions(shift) {
        //
        if (!obj.Questions || obj.Questions.length === 0) {
            return;
        }
        //
        var html = '',
            il = obj.Questions.length;
        //
        obj.Questions.map(function(question, index) {
            //
            obj.Questions[index]['sort_order'] = index;
            //
            html += '<!-- Question Row -->';
            html += '<div class="' + (index % 2 === 0 ? 'csGB' : '') + ' jsReviewQuestionRow" data-id="' + (question.id) + '" data-index="' + (index) + '">';
            html += '    <div class="row">';
            html += '        <div class="col-xs-12">';
            html += '            <div class="p10">';
            html += '                <h5 class="csF14 csB7">';
            html += '                    Q' + (++index) + ': ' + question.title;
            html += '                    <span class="pull-right">';
            html += '                        <i class="fa fa-edit csF18 csB7 csCP csReviewQuestionEdit" title="Edit the question" placemment="top" aria-hidden="true"></i>&nbsp;&nbsp;';
            if (index !== 0) {
                html += '                        <i class="fa fa-arrow-circle-up csF18 csB7 csCP jsReviewQuestionUp" title="Move question one level up" placemment="top" aria-hidden="true"></i>';
            }
            if (index !== il) {
                html += '                        <i class="fa fa-arrow-circle-down csF18 csB7 csCP jsReviewQuestionDown" title="Move question one level down" placemment="top" aria-hidden="true"></i>';
            }
            html += '                        <i class="fa fa-times-circle csF18 csB7 csCP csInfo jsReviewRemoveQuestion" title="Remove this question from the list" placemment="top" aria-hidden="true"></i>';
            html += '                    </span>';
            html += '                </h5>';
            html += '                <!-- Description -->';
            html += '                <div class="row">';
            html += '                    <div class="col-md-8 col-xs-12">';
            html += '                        <p class="csF14">' + (question.description) + '</p>';
            html += '                    </div>';
            html += '                    <div class="col-md-4 col-xs-12">';
            if (question.video_help && question.video_help != 'none') {
                html += '                        <video controls style="width: 100%;">';
                html += '                           <source src="' + (pm.urls.base + 'assets/performance_management/videos/templates/' + (obj.Id) + '/' + question.video) + '"  type="' + (getVideoType(question.video)) + '"></source>';
                html += '                        </video>';
            }
            html += '                    </div>';
            html += '                </div>';
            //
            if (question.question_type.match(/multiple/ig) !== null) {
                html += '                <!-- Multiple Choice -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <div class="col-xs-12">';
                html += '                        <label class="control control--radio csF14">';
                html += '                            <input type="radio" name="1" /> Yes';
                html += '                            <span class="control__indicator"></span>';
                html += '                        </label> <br />';
                html += '                        <label class="control control--radio csF14">';
                html += '                            <input type="radio" name="1" /> No';
                html += '                            <span class="control__indicator"></span>';
                html += '                        </label>';
                html += '                    </div>';
                html += '                </div>';
            }

            //
            if (question.question_type.match(/rating/ig) !== null) {
                html += '                <!-- Rating -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <ul class="csRatingBar pl10 pr10">';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">1</p>';
                html += '                            <p class="csF14 csB6">Strongly Agree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">2</p>';
                html += '                            <p class="csF14 csB6">Agree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">3</p>';
                html += '                            <p class="csF14 csB6">Neutral</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">4</p>';
                html += '                            <p class="csF14 csB6">Disagree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">5</p>';
                html += '                            <p class="csF14 csB6">Strongly Disagree</p>';
                html += '                        </li>';
                html += '                    </ul>';
                html += '                </div>';
            }
            //
            if (question.question_type.match(/text/ig) !== null) {
                html += '                <!-- Text -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <div class="col-xs-12">';
                html += '                        <p class="csF14 csB7">Feedback (Elaborate)</p>';
                html += '                        <textarea rows="5" class="form-control"></textarea>';
                html += '                    </div>';
                html += '                </div>';
            }
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
        });
        //
        $('#jsReviewQuestionListArea').html(html);

    }

    //
    function getVideoType(video) {
        //
        if (!video) {
            return '';
        }
        //
        var extension = video.split('.');
        //
        return 'video/' + extension[extension.length - 1].toLowerCase();
    }

    //
    ml(false, 'template');
});