
<?php if (!$load_view) { ?>

<?php 
function getAnswer($answers_given, $question, $doReturn = FALSE, $compareValue = '', $isSelect = false){
    //
    if(!isset($answers_given[$question])){ return ''; }
    //
    if($doReturn){
        return $answers_given[$question]['answer'];
    }
    //
    $rt = 'checked="checked"';
    //
    if(is_array($answers_given[$question]['answer'])){
        if(in_array((int) trim($compareValue), array_values($answers_given[$question]['answer']))){
            return $rt;
        } else{
            return '';
        }
    } else if(trim($answers_given[$question]['answer']) == trim($compareValue)){
        return $isSelect ? 'selected="true"' : $rt;
    }
}
    
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php if($top_view) {
                        $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');
                    }
                    else { ?>

                        <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo AWS_S3_BUCKET_URL;
                                        if (isset($applicant_info['pictures']) && $applicant_info['pictures'] != "") {
                                            echo $applicant_info['pictures'];
                                        } else {
                                            ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $applicant_info["first_name"]; ?> <?= $applicant_info["last_name"] ?></h2>

                                        <div class="start-rating">
                                            <input readonly="readonly"
                                                   id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                                   type="number" name="rating" class="rating" min=0 max=5
                                                   step=0.2
                                                   data-size="xs">
                                        </div>
                                        <?php if (check_blue_panel_status() && $applicant_info['is_onboarding'] == 1) { ?>
                                            <span class="badge" style="padding:8px; background-color: red;">Onboarding Request Sent</span>
                                        <?php } else { ?>
                                            <span class=""
                                                  style="padding:8px;"><?php echo $applicant_info["applicant_type"]; ?></span>
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                    <?php }?>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $back_url; ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i>Online Videos</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <strong style="font-size: 20px;"><?php echo $video['video_title']; ?><span class="pull-right">
                                        <button class="btn btn-danger" id="jsRevokeVideo"><i class="fa fa-times-circle" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;Revoke Video</button>
                                    </span></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <strong>Description:</strong>
                                    <p><?php echo $video['video_description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well well-sm">
                                <?php if($video['video_source'] == 'youtube') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $video['video_id']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else if($video['video_source'] == 'vimeo') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $video['video_id']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else { ?>
                                    <video controls style="width:100%; height:auto;">
                                        <source src="<?php echo base_url('assets/uploaded_videos/').$video['video_id']; ?>" type='video/mp4'>
                                    </video>
                                <?php } ?>    
                            </div>
                        </div>
                    </div>
                    <?php 
                    if(!empty($job_details)) {
                        ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="pull-left">
                                            <strong style="font-size: 20px;">Video watch status: </strong><span style="font-size: 20px;" class=" <?php echo empty($assignment['watched']) || $assignment['watched'] == 0 ? "text-danger" : "text-success"; ?>"> <?php echo empty($assignment['watched']) || $assignment['watched'] == 0 ? "Pending" : "Watched"; ?></span>
                                            <?php if (empty($assignment['date_watched']) && $assignment['watched'] != 0) { ?>
                                                <button type="button" class="btn btn-success btn-block mb-2 disabled" disabled="disabled">Watched on <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $assignment['date_watched'])->format('m-d-Y h:i A'); ?></button>
                                            <?php } ?>
                                            
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="pull-right">
                                            <strong style="font-size: 20px;">Questionnaire Result: </strong><span style="font-size: 20px;" class="<?= isset($questionnaire_result) && $questionnaire_result == 'Pass' ? 'text-success' : 'text-danger'; ?>"> <?php echo isset($questionnaire_result) && !empty($questionnaire_result) ? $questionnaire_result : 'Pending'; ?></span>
                                            <?php if (isset($questionnaire_result) && !empty($questionnaire_result)) { ?>
                                                <button type="button" class="btn btn-success btn-block" disabled="disabled">Attempted On <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $attempted_questionnaire_timestamp)->format('m-d-Y h:i A'); ?></button>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if(!empty($supported_documents)) { ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="panel panel-default ems-documents">
                                        <div class="panel-heading">
                                            <strong>Supported Documents</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-plane">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-3">Document Name</th>
                                                            <th class="col-lg-3 text-center">Type</th>
                                                            <th class="col-lg-3 text-center">Attached Date</th>
                                                            <th class="col-lg-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php foreach($supported_documents as $document) { ?>
                                                            <tr>
                                                                <td class="col-lg-3"><?php echo $document['upload_file_title']; ?></td>
                                                                <td class="col-lg-1 text-center">
                                                                    <?php $doc_type = $document['upload_file_extension']; ?>
                                                                    <?php if($doc_type == 'pdf'){ ?>
                                                                        <i class="fa fa-2x fa-file-pdf-o"></i>
                                                                    <?php } else if(in_array($doc_type, ['ppt', 'pptx'])){ ?>
                                                                       <i class="fa fa-2x fa-file-powerpoint-o"></i>                          
                                                                    <?php } else if(in_array($doc_type, ['doc', 'docx'])){ ?>
                                                                       <i class="fa fa-2x fa-file-o"></i>
                                                                    <?php } else if(in_array($doc_type, ['xlsx'])){ ?> 
                                                                        <i class="fa fa-2x fa-file-excel-o"></i>
                                                                    <?php } else if($doc_type == ''){ ?> 
                                                                        <i class="fa fa-2x fa-file-text"></i>         
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['attached_date'])) { ?>
                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['attached_date']), 'M d Y h:i a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <button class="btn btn-success"
                                                                        onclick="preview_latest_generic_function(this);"
                                                                        data-title="<?php echo $document['upload_file_title']; ?>"
                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['upload_file_name']; ?>"
                                                                        data-s3-name="<?php echo $document['upload_file_name']; ?>">View</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <strong style="font-size: 20px;">Questionnaire</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        //
                        $questionId = $job_details['my_id'];
                        if (isset($job_details[$questionId]) && !empty($job_details[$questionId])) {
                        foreach($job_details[$questionId] as $question){
                            //
                            $answer = isset($job_details['q_answer_'.$question['questions_sid']]) ? $job_details['q_answer_'.$question['questions_sid']] : [];
                        ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <label><?=$question['caption'];?></label> <br />
                                            <?php if($question['question_type'] == 'boolean') {
                                                foreach($answer as $a){ ?>
                                                <label class="control control--radio">
                                                    <?php if (isset($answers_given[0]) && !empty($answers_given[0])) { ?>
                                                        <input type="radio" disabled <?=getAnswer($answers_given[0], $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <?php } else { ?>
                                                        <input type="radio" disabled <?=getAnswer(array(), $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <?php } ?>
                                                    
                                                    <div class="control__indicator"></div>
                                                </label> &nbsp;
                                            <?php 
                                                }
                                            } else if($question['question_type'] == 'string') { ?>
                                                <?php if (isset($answers_given[0]) && !empty($answers_given[0])) { ?>
                                                    <textarea class="form-control" disabled><?=getAnswer($answers_given[0], $question['caption'], true);?></textarea>
                                                <?php } else { ?>
                                                    <textarea class="form-control" disabled><?=getAnswer(array(), $question['caption'], true);?></textarea>
                                                <?php } ?>
                                            
                                            <?php } else if($question['question_type'] == 'list') { ?>
                                            <select class="form-control" disabled>
                                                <option value="">Please Select</option>
                                                <?php 
                                                 foreach($answer as $a){ ?>
                                                    <?php if (isset($answers_given[0]) && !empty($answers_given[0])) { ?>
                                                        <option value="<?=$a['value'];?>" <?=getAnswer($answers_given[0], $question['caption'], false, $a['value'], true);?>><?=$a['value'];?></option>
                                                    <?php } else { ?>
                                                        <option value="<?=$a['value'];?>" <?=getAnswer(array(), $question['caption'], false, $a['value'], true);?>><?=$a['value'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <?php } else if($question['question_type'] == 'multilist'){
                                                foreach($answer as $a){ 
                                                ?>
                                                <label class="control control--checkbox">
                                                    <?php if (isset($answers_given[0]) && !empty($answers_given[0])) { ?>
                                                        <input type="checkbox" disabled <?=getAnswer($answers_given[0], $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <?php } else { ?>
                                                        <input type="checkbox" disabled <?=getAnswer(array(), $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <?php } ?>
                                                    
                                                    <div class="control__indicator"></div>
                                                </label> 
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } }?>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                <input type="hidden" id="user_type" name="user_type" value=<?= $user_type?> />
                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $employer_sid; ?>" />
                            </form>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                    </div>
                </div>
                <?php if(isset($left_navigation)){
                    $this->load->view($left_navigation);
                }?>
            </div>
        </div>
    </div>
</div>

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div> 
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">
                
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<script>
    function func_mark_video_as_watched() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to mark this video as watched?',
            function () {
                $('#form_mark_video_as_watched').submit();
            },
            function () {
                alertify.error('Cancelled!');
            }
        );
    }

    $(function(){
        $('#jsRevokeVideo').click(function(event){
            //
            event.preventDefault();
            //
            alertify.confirm(
                "This action will remove the <?=$user_type;?> from this video and remove the saved data.",
                function(){
                    revokeVideoAccess();
                }
            ).setHeader('Confirm!').set('label', {
                ok: "Yes",
                cancel: "No"
            });
        });

        //
        function revokeVideoAccess(){
            $.post("<?=base_url("learning_center/video_access");?>", {
                action: 'revoke',
                userId: <?=$employer_sid;?>,
                userType: "<?=$user_type;?>",
                videoId: "<?=$video['sid'];?>"
            }).done(function(resp){
                //
                if(resp == 'success'){
                    alertify.alert('Success!', "You have successfully removed this <?=$user_type;?> from video.", function(){
                        window.location.href = "<?=base_url('/learning_center/my_learning_center/'.($employer_sid).'');?>"
                    });
                } else{
                    alertify.alert('Warning!', "Something went wrong. Please, try again in a few moments.");
                }
            });
        }
    });

    function preview_latest_generic_function (source) {
        var document_title = $(source).attr('data-title');
        
        var preview_document        = 1;
        var model_contant           = '';
        var preview_iframe_url      = '';
        var preview_image_url       = '';
        var document_print_url      = '';
        var document_download_url   = '';


        var file_s3_path            = $(source).attr('data-preview-url');
        var file_s3_name            = $(source).attr('data-s3-name');

        var file_extension          = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
        var document_file_name      = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
        var document_extension      = file_extension.toLowerCase();
        

        switch (file_extension.toLowerCase()) {
            case 'pdf':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pdf';
                break;
            case 'csv':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.csv';
                break;
            case 'doc':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edoc&wdAccPdf=0';
                break;
            case 'docx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edocx&wdAccPdf=0';
                break;
            case 'ppt':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.ppt';
                break;
            case 'pptx':
                dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pptx';
                break;
            case 'xls':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exls';
                break;
            case 'xlsx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exlsx';
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
                preview_image_url = file_s3_path;
                document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>'+'/'+file_s3_name;
                break;
            default : //using google docs
                preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                break;
        }

        document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>'+'/'+file_s3_name;

        $('#show_latest_preview_document_modal').modal('show');
        $("#latest_document_modal_title").html(document_title);
        $('#latest-iframe-container').show();

        if (preview_document == 1) {
            model_contant = $("<iframe />")
                .attr("id", "latest_document_iframe")
                .attr("class", "uploaded-file-preview")
                .attr("src", preview_iframe_url);
        } else {
            model_contant = $("<img />")
                .attr("id", "latest_image_tag")
                .attr("class", "img-responsive")
                .css("margin-left", "auto")
                .css("margin-right", "auto")
                .attr("src", preview_image_url);
        }
        

        $("#latest-iframe-holder").append(model_contant);
        //
        if (preview_document == 1) {
            loadIframe(
                    preview_iframe_url,
                    '#latest_document_iframe',
                    true
                );
        }

        footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
        footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        $("#latest_document_modal_footer").html(footer_content);
    }
</script>

<?php } else { ?>
    <?php $this->load->view('learning_center/watch_video_new'); ?>
<?php } ?>