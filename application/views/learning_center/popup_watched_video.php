<div id="popup1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" id="myModal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-header-green">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Training Session Video</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="well well-sm">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <div id="youtube-section" style="display:none;">
                                            <div id="youtube-video-placeholder" class="embed-responsive-item">
                                            </div>
                                        </div>
                                        <div id="vimeo-section" style="display:none;">
                                            <div id="vimeo-video-placeholder"></div>
                                        </div>
                                        <div id="video-section" style="display:none;">
                                            <video id="my-video" controls></video>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <strong style="font-size: 20px;" id="popup_video-title"></strong>
                                        <p id="popup_video_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div id="docDiv">

                        </div>
                        <div class="row" id="que-div" style="display : none">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="form-wrp">
                                            <form method="POST" id="register-form" action="">
                                                <input type='hidden' name="q_name" id="q_name">
                                                <input type='hidden' name="q_passing" id="q_passing">
                                                <input type='hidden' name="q_send_pass" id="q_send_pass">
                                                <input type='hidden' name="q_pass_text" id="q_pass_text">
                                                <input type='hidden' name="q_send_fail" id="q_send_fail">
                                                <input type='hidden' name="q_fail_text" id="q_fail_text">
                                                <input type='hidden' name="session_sid" id="session_sid" value="<?= $session_id?>">
                                                <input type='hidden' name="my_id" id="my_id">
                                                <div id="qDiv">

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div >
                        <hr />
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12"></div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                    <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                    <input type="hidden" id="popup_video_sid" name="video_sid" value="" />
                                    <!-- add hidden field for video id -->
                                    <input type="hidden" id="popup_video_id" name="video_id" value="" />
                                    <input type="hidden" id="popup_user_type" name="user_type" value="" />
                                    <input type="hidden" id="popup_user_sid" name="user_sid" value="" />
        
                                </form>
                                <div id="not_watched_video" style="display:none;">
                                   <button type="button" class="btn btn-success btn-block" onclick="func_mark_video_as_watched();" id="btn_disable">Mark as Watched</button>
                                </div>
                                <div id="watched_video" style="display:none;">
                                    <button type="button" class="btn btn-success btn-block disabled" disabled="disabled">
                                        <p id="dutton_watched_video" style="margin-bottom: 0px;"></p>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6"></div>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
</div>
<!-- Including Youtube player javascript API -->
<script src="https://www.youtube.com/iframe_api"></script>
<!-- Including Vimeo player javascript API -->
<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
