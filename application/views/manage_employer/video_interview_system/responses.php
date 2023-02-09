<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area margin-top">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile') . '/' . $applicant_sid ; ?>">
                                <i class="fa fa-chevron-left"></i>
                                Applicant Profile
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row">
                            <!-- responses -->
                            <div class="table-responsive <?php echo (empty($questions)) ? 'col-lg-12' : 'col-lg-6'; ?>">
                                <?php if (!empty($questions)) { ?>
                                        <div class="table-wrp mylistings-wrp border-none">
                                            <table class="table table-bordered" id="response_table">
                                                <thead>
                                                    <tr>
                                                        <th id="heading">Questions</th>
                                                        <th style="display:none;">Response</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($questions as $question) { ?>
                                                    <tr id="tr_<?php echo  $question['question_sid']; ?>">
                                                        <td>
                                                            <div class="white">
                                                                <?php
                                                                    if($question['question_type'] == 'video'){
                                                                        echo $question['video_title'];
                                                                    } else {
                                                                        echo $question['question_text'];
                                                                    }
                                                                ?>
                                                            </div>
                                                        </td>
                                                        <td id="td_<?php echo  $question['question_sid']; ?>" style="display:none;">
                                                            <p>
                                                                <?php if($question['question_type'] == 'video'){ ?>
                                                                    <video style="width:100%;" controls>
                                                                        <source src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $question['video_response']; ?>" type="video/webm">
                                                                            Your browser does not support HTML5 video.
                                                                    </video>
                                                                <?php } else { ?>
                                                                    <?php echo $question['text_response']; ?>
                                                                <?php } ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>
                                <?php } else { ?>
                                    <div class="no-data">No Video Question Responses found!</div>
                                <?php } ?>                        
                            </div>
                            <!-- responses -->
                            <div <?php echo (empty($questions)) ? 'style="display:none;"' : ''; ?>>
                                <!-- response div -->
                                <p class="text-center"><b>Applicant Response</b></p><br/>
                                <div class="table-responsive table-outer col-lg-6" id="response_div"></div>
                                <!-- response div -->
                                <br/><hr/>
                                <!-- ratings div -->
                                <div class="table-responsive table-outer col-lg-6" id="ratings_div">
                                    <br/><p><b>Submit A Review</b></p><br/>
                                    <div class="start-rating">
                                        <form action="<?php echo base_url('video_interview_system/rating'); ?>" method="post" >
                                            <input type="hidden" name="applicant_sid" id="applicant_sid" value="<?php echo $applicant_sid; ?>" />
                                            <input id="input-21b" value="" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                            <div class="rating-comment">
                                                <h4>comment<samp class="red"> * </samp></h4>
                                                <textarea name="comment" id="comment" required></textarea>
                                                <?php echo form_error('comment'); ?>
                                                <input type="submit" value="submit">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- ratings div -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('manage_employer/profile_right_menu_applicant'); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('tr').css('cursor', 'pointer');
        
        var content = $('#response_table tr:eq(1) td:eq(1)').html();
        $('#response_div').html(content);
        $('#response_table tr:eq(1) td div').removeClass("white");
        $('#response_table tr:eq(1) td div').addClass("grey");
        
        $('tr').click(function () { 
            if($(this).attr('id') !== undefined) {  
                change_color();
                var row_id = $(this).attr('id');
                var col_content = $('#response_table #' + row_id + ' td:eq(1)').html();
                $('#response_table #' + row_id + ' td div').removeClass("white");
                $('#response_table #' + row_id + ' td div').addClass("grey");
                $('#response_div').html(col_content);
            }
        }); 
        
        function change_color(){
            $('.grey').each(function (i, value) {
                $(this).removeClass("grey");
                $(this).addClass("white");
            });
        }
    });
</script>

<style>
    .grey{
        background-color: #999 !important;
        padding: 10px;
    }
    .white{
        background-color: white !important;
        padding: 10px;
    }
    td{
        padding: 0px !important;
    }
    #heading{
        background-color: #81b431;
        color:white;
        cursor: auto !important;
    }
    #response_div, #ratings_div{
/*        min-height: 400px;*/
    }
    #ratings_div{
        float:right;
    }
</style>