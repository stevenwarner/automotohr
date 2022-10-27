<?php
if($load_view){

    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = '';
    $dependants_arr = array();
    $delete_post_url = '';
    $save_post_url = '';

    if (isset($applicant)) {
        $company_sid = $applicant['employer_sid'];
        $users_type = 'applicant';
        $users_sid = $applicant['sid'];
        $back_url = base_url('onboarding/learning_center/' . $unique_sid);
        $delete_post_url = current_url();
        $save_post_url = current_url();
    } else if (isset($employee)) {
        $company_sid = $employee['parent_sid'];
        $users_type = 'employee';
        $users_sid = $employee['sid'];
        $back_url = base_url('learning_center/my_learning_center');
        $delete_post_url = current_url();
        $save_post_url = current_url();
    } ?>

    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="btn-panel">
                        <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> My Learning Center</a>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile"><?php echo $title; ?></h1>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <strong>Topic - </strong><?php echo ucwords($assignment['session_topic']); ?>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th class="col-xs-4">Date</th>
                                                            <td class="col-xs-8"><?=reset_datetime(array( 'datetime' => $assignment['session_date'], '_this' => $this )); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Start Time</th>
                                                            <td class="col-xs-8"><?=reset_datetime(array('datetime' => $assignment['session_date'].$assignment['session_start_time'], 'from_format' => 'Y-m-dH:i:s', 'format' => 'h:iA', '_this' => $this)); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">End Time</th>
                                                            <td class="col-xs-8"><?=reset_datetime(array('datetime' => $assignment['session_date'].$assignment['session_end_time'], 'from_format' => 'Y-m-dH:i:s', 'format' => 'h:iA', '_this' => $this)); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Session Status</th>
                                                            <td class="col-xs-8"><?php echo ucwords(
                                                                    $assignment['session_status'] == 'pending' ? 'Scheduled' : $assignment['session_status']
                                                                ); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Attend Status</th>
                                                            <td class="col-xs-8"><?php echo ucwords(str_replace('_', ' ', $assignment['attend_status'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Description</th>
                                                            <td class="col-xs-8"><?php echo $assignment['session_description']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Location</th>
                                                            <td class="col-xs-8"><?php echo $assignment['session_location']; ?></td>
                                                        </tr>
                                                        <?php if(sizeof($assignment['online_video_sid'])) { ?>

                                                            <?php foreach ($assignment['online_video_sid'] as $key => $vid_tit) { ?>
                                                                <tr>
                                                                    <th class="col-xs-4"><?php echo $vid_tit;?></th><td class="col-xs-8">
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                            <a class="btn btn-block btn-info watched_video_now" src="<?= base_url('learning_center/training_session_watch_video/'.$key.'/'. $assignment['sid']);?>">Watch Video</a>
                                                                        </div>
                                                                        <?php $this->load->view('learning_center/popup_watched_video'); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <?php if($assignment['attend_status'] != 'attended' && $assignment['attend_status'] != 'unable_to_attend') {?>
                                        <div class="btn-wrp full-width">
                                            <?php if($assignment['attend_status'] != 'will_attend') {?>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <form id="form_will_attend_<?php echo $assignment['sid']; ?>" enctype="multipart/form-data" method="post">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $assignment['assigned_sid']; ?>" />
                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />
                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $assignment['user_sid']; ?>" />
                                                        <input type="hidden" id="attend_status" name="attend_status" value="will_attend" />
                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                    </form>
                                                    <button <?php echo $assignment['wa_btn_status']; ?> onclick="func_submit_form('form_will_attend_<?php echo $assignment['sid']; ?>', 'Are you sure you will attend?');" class="btn btn-block btn-warning btn-sm <?php echo $assignment['wa_btn_status']; ?>">Will Attend</button>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <form id="form_unable_to_attend_<?php echo $assignment['sid']; ?>" enctype="multipart/form-data" method="post">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $assignment['assigned_sid']; ?>" />
                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />
                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $assignment['user_sid']; ?>" />
                                                        <input type="hidden" id="attend_status" name="attend_status" value="unable_to_attend" />
                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                    </form>
                                                    <button <?php echo $assignment['uta_btn_status']; ?> onclick="func_submit_form('form_unable_to_attend_<?php echo $assignment['sid']; ?>', 'Are you sure you are unable to attend?');" class="btn btn-block btn-danger btn-sm <?php echo $assignment['uta_btn_status']; ?>">Unable To Attend</button>
                                                </div>
                                            <?php } ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <form id="form_attended_<?php echo $assignment['sid']; ?>" enctype="multipart/form-data" method="post">
                                                    <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                    <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $assignment['assigned_sid']; ?>" />
                                                    <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />
                                                    <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $assignment['user_sid']; ?>" />
                                                    <input type="hidden" id="attend_status" name="attend_status" value="attended" />
                                                    <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                </form>
                                                <button <?php echo $assignment['a_btn_status']; ?> onclick="func_submit_form('form_attended_<?php echo $assignment['sid']; ?>', 'Are you sure you want to mark this session as attended?');" class="btn btn-block btn-success btn-sm <?php echo $assignment['a_btn_status']; ?>">Attended</button>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php /*if($users_type != 'applicant') { ?>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                        <?php $this->load->view('manage_employer/employee_hub_right_menu'); ?>
                    </div>
                <?php } */?>
            </div>
        </div>
    </div>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.collapse').on('shown.bs.collapse', function () {
                $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hidden.bs.collapse', function () {
                $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });

            $('#popup1').on('hidden.bs.modal', function () {
                $('#youtube-section').hide();
                $('#vimeo-section').hide();
                $('#video-section').hide();
                $('#not_watched_video').hide();
                $('#watched_video').hide();
            });
        });

        function func_submit_form(form_id, alert_message) {
            alertify.confirm(
                'Are you sure?',
                alert_message,
                function() {
                    $('#' + form_id).submit();
                },
                function() {
                    alertify.error('Cancelled!');
                });
        }

        function unCheckAll(classID,obj){
            //  $('.'+classID).not(obj).attr('checked', false);
        }

        $('.watched_video_now').on('click', function () {
            var myurl = $(this).attr('src');

            $.ajax({
                type: "GET",
                url: myurl,
                success: function (response) {
                    var obj = jQuery.parseJSON(response);
                    var user_id = obj['employer_sid'];
                    var user_type = obj['user_type'];
                    var video_id = obj['video_id'];
                    var video_sid = obj['video_sid'];
                    var video_url = obj['video_url'];
                    var watched = obj['watched'];
                    var video_source = obj['video_source'];
                    var video_title = obj['video_title'];
                    var video_description = obj['video_description'];
                    var date_watched = obj['date_watched'];
                    var thisQuestionnaire = '';
                    var thisDocuments = '';
                    var base = '<?php echo base_url('learning_center/view_supported_attachment_document/'); ?>';

                    if(obj.job_details[obj.job_details.my_id] != undefined){
                        $('#q_name').val(obj.job_details.q_name);
                        $('#q_passing').val(obj.job_details.q_passing);
                        $('#q_send_pass').val(obj.job_details.q_send_pass);
                        $('#q_pass_text').val(obj.job_details.q_pass_text);
                        $('#q_send_fail').val(obj.job_details.q_send_fail);
                        $('#q_fail_text').val(obj.job_details.q_fail_text);
                        $('#my_id').val(obj.job_details.my_id);
                        var iterate = 0;
                        obj.job_details[obj.job_details.my_id].map( questions_list => {
                            answer_div = '';
                            if (questions_list.question_type == 'string') {
                                answer_div = '<input type="text" class="form-control" name="string'+ questions_list.questions_sid+'" placeholder="'+questions_list.caption+'" value=""  '+((questions_list.is_required == 1) ? 'required': '')+'>'+((questions_list.is_required == 1) ? '<span class="required"> * </span>': '');
                            }
                            if (questions_list.question_type == 'boolean') {
                                answer_key = 'q_answer_' + questions_list.questions_sid;
                                obj.job_details[answer_key].map(answer_list => {
                                    answer_div+= '<label class="control control--radio">';
                                    answer_div+= '<input type="radio" name="boolean'+questions_list.questions_sid+'" value="'+answer_list.value+' @#$ '+answer_list.score+'" > '+answer_list.value +'&nbsp';
                                    answer_div+= '<div class="control__indicator"></div>';
                                    answer_div+= '</label>';
                                })
                            }
                            if (questions_list.question_type == 'list') {
                                answer_key = 'q_answer_' + questions_list.questions_sid;
                                answer_div+= '<select name="list'+questions_list.questions_sid+'" class="form-control"'+ ((questions_list.is_required == 1) ? 'required="required"': '') +'>';
                                answer_div+= '<option value="">-- Please Select --</option>';
                                obj.job_details[answer_key].map(answer_list => {
                                    answer_div+= '<option value="'+answer_list.value+' @#$ '+answer_list.score+'"> '+answer_list.value+'</option>';
                                })
                                answer_div+= '</select>';
                            }
                            if (questions_list.question_type == 'multilist') {
                                answer_key = 'q_answer_' + questions_list.questions_sid;
                                answer_div += '<div class="row">';
                                obj.job_details[answer_key].map(answer_list => {
                                    answer_div += '<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
                                    answer_div += '<label for="squared'+iterate+'" class="control control--checkbox">';
                                    answer_div += answer_list.value;
                                    answer_div += '<input type="checkbox" class="checkbox-'+questions_list.questions_sid+'" onclick="unCheckAll(`checkbox-'+questions_list.questions_sid+'`,this)" name="multilist'+questions_list.questions_sid+'[]" id="squared'+(iterate)+'" value="'+answer_list.value+' @#$ '+answer_list.score+'">';
                                    answer_div += '<div class="control__indicator"></div>';
                                    answer_div += '</label>';
                                    answer_div += '</div>';
                                    iterate = iterate+1;
                                })
                                answer_div += '</div>';
                            }
                            thisQuestionnaire += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><input type="hidden" name="all_questions_ids[]" value="'+ questions_list.questions_sid +'"><input type="hidden" name="caption'+questions_list.questions_sid+'" value="'+questions_list.caption+'"><input type="hidden" name="type'+questions_list.questions_sid+'" value="'+questions_list.question_type+'"><input type="hidden" name="perform_action" value="questionnaire"><div class="form-group autoheight"><label>'+questions_list.caption+':'+ ((questions_list.is_required == 1) ? '<span class="required"> * </span>': '') +' </label>'+answer_div+'</div></div>';
                        });
                        if(obj['attempt_status'] == 1){
                            thisQuestionnaire = '';
                            thisQuestionnaire += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><label>Questionnaire Result: </label> <span>'+obj['questionnaire_result']+'</span></div>';
                        }
                        thisQuestionnaire += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> <input id="mySubmitBtn" class="btn btn-info" type="submit" value="'+(obj['attempt_status'] == 1 ? 'Questionnaire Already Submitted' : "Save")+'" '+ (obj['attempt_status'] == 1 ? ('disabled="disabled"') : '')+'></div>';
                        $('#que-div').show();
                        console.log(thisQuestionnaire);
                        $('#qDiv').html(thisQuestionnaire);
                        $('#register-form').attr('action','<?=base_url('learning_center/watch_video')?>/'+video_sid);
                    }

                    if(obj.supported_documents.length > 0){
                        var docDiv = '';
                        obj.supported_documents.map(document => {
                            docDiv +='<tr><td class="col-lg-3">'+document.upload_file_title+'</td><td class="col-lg-1 text-center">';
                                doc_type = document.upload_file_extension;
                                ppt = ['ppt', 'pptx'];
                                dox = ['doc', 'docx'];
                                xlx = ['xlsx'];
                                if(doc_type == 'pdf'){
                                    docDiv += '<i class="fa fa-2x fa-file-pdf-o"></i>';
                                } else if(ppt.indexOf(doc_type) != -1){
                                    docDiv += '<i class="fa fa-2x fa-file-powerpoint-o"></i>';
                                } else if(dox.indexOf(doc_type) != -1 ){
                                    docDiv += '<i class="fa fa-2x fa-file-o"></i>';
                                } else if(xlx.indexOf(doc_type) != -1){
                                    docDiv += '<i class="fa fa-2x fa-file-excel-o"></i>';
                                } else if(doc_type == ''){
                                    docDiv += '<i class="fa fa-2x fa-file-text"></i>';
                                }
                            // docDiv += '<td class="col-lg-1 text-center"><a href="'+base+document.sid+'" class="btn btn-info">View</a></td></tr>';
                            docDiv += '<td class="col-lg-1 text-center"><a href="javascript:;" class="btn btn-info jsShowSupportingDocument" data-doc_path="'+document.upload_file_name+'" data-doc_name="'+document.upload_file_title+'" data-doc_extension="'+document.upload_file_extension+'">View</a></td></tr>';
                        });
                        thisDocuments = '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="panel panel-default ems-documents"><div class="panel-heading"><strong>Supported Documents</strong></div><div class="panel-body"><div class="table-responsive"><div id="document_listing">';
                        thisDocuments += '<table class="table table-plane"><thead><tr><th class="col-lg-3">Document Name</th><th class="col-lg-3 text-center">Type</th><th class="col-lg-3 text-center">Actions</th></tr></thead><tbody>';
                        thisDocuments += docDiv;
                        thisDocuments += '</tbody></table></div><div id="document_section"></div></div></div></div></div></div>';
                        $('#docDiv').html(thisDocuments);
                    }
                    $("#popup_user_sid").val(user_id);
                    $("#popup_user_type").val(user_type);
                    $("#popup_video_id").val(video_id);
                    $("#popup_video_sid").val(video_sid);
                    $("#popup_video_title").html(video_title);
                    $("#popup_video_description").html(video_description);
                    // 
                    if (video_source == 'youtube') {
                        $('#popup1').modal('show');
                        $('#youtube-section').show();
                        var video = $("<iframe />")
                        .attr("id", "youtube_iframe")
                        .attr("src", "https://www.youtube.com/embed/"+video_id);
                        $("#youtube-video-placeholder").append(video);
                    } else if (video_source == 'vimeo') {
                        $('#popup1').modal('show');
                        $('#vimeo-section').show();
                        var video = $("<iframe />")
                        .attr("id", "vimeo_iframe")
                        .attr("src", "https://player.vimeo.com/video/"+video_id);
                        $("#vimeo-video-placeholder").append(video);
                    } else {
                        $('#popup1').modal('show');
                        $('#video-section').show();
                        var video = document.getElementById('my-video');
                        var source = document.createElement('source');
                        $("#my-video").first().attr('src',video_url);
                    }

                    if (watched != 0) {
                        $('#watched_video').show();
                        $("#dutton_watched_video").html('Watched on '+date_watched);
                    } else {
                        $('#not_watched_video').show();
                    }
                },
                error: function (data) {
                }
            });
        });

        // 
        $(document).on('click', '.jsShowSupportingDocument', function() {
            $('#document_listing').hide();
            $('#document_section').show();
            var preview_document = 1;
            var model_contant = '';
            var doc_path = $(this).data("doc_path");
            var doc_name = $(this).data("doc_name");
            var doc_extension = $(this).data("doc_extension");
            var document_file_name = doc_path.substr(0, doc_path.lastIndexOf('.'));

            switch (doc_extension.toLowerCase()) {
                case 'pdf':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + doc_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + doc_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(doc_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(doc_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + doc_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    dpreview_iframe_url = 'https://docs.google.com/gview?url=' + doc_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(doc_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(doc_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    preview_document = 0;
                    preview_image_url = doc_path;
                    document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>' + '/' + doc_path;
                    break;
                default: //using google docs
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + doc_path + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + doc_path;

            if (preview_document == 1) {
                documentContant = $("<iframe />")
                    .attr("id", "latest_document_iframe")
                    .attr("class", "uploaded-file-preview")
                    .attr("src", preview_iframe_url);
            } else {
                documentContant = $("<img />")
                    .attr("id", "latest_image_tag")
                    .attr("class", "img-responsive")
                    .css("margin-left", "auto")
                    .css("margin-right", "auto")
                    .attr("src", preview_image_url);
            }

            $("#document_section").append(documentContant);
        });


        var vid = document.getElementById("my-video");
        vid.onplay = function() {
            var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
            var completed = 0;
            updateRecord (duration,completed) ;
        };

        vid.onpause = function() {
            var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
            var completed = 0;
            updateRecord (duration,completed) ;
        };

        vid.onended = function() {
            var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
            var completed = 1;
            updateRecord (duration,completed) ;
        };

        function func_mark_video_as_watched() {
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to mark this video as watched?',
                function () {
                    var myurl = "<?= base_url() ?>learning_center/mark_as_watched";
                    var user_id = $('#popup_user_sid').val();
                    var user_type = $('#popup_user_type').val();
                    var video_id = $('#popup_video_sid').val();

                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {id: user_id, type: user_type, v_id: video_id},
                        async : false,
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            $("#btn_disable").html('Watched on '+obj);
                            $('#btn_disable').attr('disabled', 'disabled');
                        },
                        error: function (data) {

                        }
                    });
                },
                function () {
                    alertify.error('Cancelled!');
                }
            );
        }

        var youtube_player,
            playing = false;

        function onYouTubeIframeAPIReady(video_id) {
            var videoID = video_id;
            youtube_player = new YT.Player('youtube-video-placeholder', {
                width: 600,
                height: 400,
                videoId: videoID,
                host: 'https://www.youtube.com',
                playerVars: {
                    color: 'white',
                    // playlist: 'taJ60kskkns,FG0fTKAqZ5g',
                },
                events: {
                    onStateChange: onPlayerStateChange
                },
            });
        }

        function formatTime(time){
            time = Math.round(time);
            var minutes = Math.floor(time / 60),
                seconds = time - minutes * 60;

            seconds = seconds < 10 ? '0' + seconds : seconds;
            return minutes + ":" + seconds;
        }

        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
                var completed = 0;
                updateRecord (duration,completed) ;
                var time = "Watched On "+moment().format('MM-DD-YYYY hh:mm A');
                $("#btn_disable").html(time);
                $('#btn_disable').attr('disabled', 'disabled');

            } else if (event.data == YT.PlayerState.PAUSED) {
                var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
                var completed = 0;
                updateRecord (duration,completed) ;
            } else if (event.data === 0) {
                var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
                var completed = 1;
                updateRecord (duration,completed) ;
            }
        }

        var v_c_t,
            v_d;

        function onVimeoIframeAPIReady(options) {
            var player = new Vimeo.Player('vimeo-video-placeholder', options);

            player.on('play', function() {
            var currentPromise = player.getCurrentTime();
            var durationPromise = player.getDuration();
            currentPromise.then(function (val) {
              v_c_t = formatTime(val);
            });

            durationPromise.then(function (val) {
              v_d = formatTime(val);
            });

            setTimeout(function(){
                var duration = v_c_t + '/' +v_d;
                var completed = 0;
                updateRecord (duration,completed) ;
                var currentdate = new Date();
                var time = "Watched On "+moment().format('MM-DD-YYYY hh:mm A');
                $("#btn_disable").html(time);
                $('#btn_disable').attr('disabled', 'disabled');
            }, 50);
        });

        player.on('pause', function() {
            var currentPromise = player.getCurrentTime();
            var durationPromise = player.getDuration();

            currentPromise.then(function (val) {
              v_c_t = formatTime(val);
            });

            durationPromise.then(function (val) {
              v_d = formatTime(val);
            });

            setTimeout(function(){
                var duration = v_c_t + '/' +v_d;
                var completed = 0;
                updateRecord (duration,completed) ;
            }, 50);
        });

        player.on('ended', function() {
            var currentPromise = player.getCurrentTime();
            var durationPromise = player.getDuration();

            currentPromise.then(function (val) {
              v_c_t = formatTime(val);
            });

            durationPromise.then(function (val) {
              v_d = formatTime(val);
            });

            setTimeout(function(){
                var duration = v_c_t + '/' +v_d;
                var completed = 1;
                updateRecord (duration,completed) ;
            }, 50);
        });
        }

        function updateRecord (duration,completed) {
            var myurl = "<?= base_url() ?>learning_center/track_youtube";
            var user_id = $('#popup_user_sid').val();
            var user_type = $('#popup_user_type').val();
            var video_id = $('#popup_video_sid').val();

            $.ajax({
                type: "POST",
                url: myurl,
                data: {id: user_id, type: user_type, v_id: video_id, v_duration: duration, v_completed : completed},
                async : false,
                success: function (data) {
                    return 'datamydate';
                },
                error: function (data) {

                }
            });
        }
    </script>
<?php } else{
    $this->load->view('learning_center/view_training_old');
}?>