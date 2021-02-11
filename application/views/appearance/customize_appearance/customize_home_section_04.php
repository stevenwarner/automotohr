<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Section Four <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">


                    <div>
                        <h2>Video</h2>
                        <div class="well well-sm">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo ( isset($section_04_meta['video']) && $section_04_meta['video'] != '' ? $section_04_meta['video'] : '');?>"></iframe>
                            </div>
                        </div>
                        <br />
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_video_section_04" enctype="multipart/form-data" method="post">
                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_section_04_video"/>
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->

                                    <li class="form-col-100 autoheight">
                                        <label for="video_section_04">Video Url <span class="staric">*</span></label>
                                        <input class="invoice-fields" type="text" id="video_section_04" name="video_section_04" placeholder="Video Url" value="<?php echo ( isset($section_04_meta['video']) && $section_04_meta['video'] != '' ? 'https://www.youtube.com/watch?v=' . $section_04_meta['video'] : '');?>" />
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div>
                                            <label for="status_section_04">Status</label>
                                            <input type="hidden" id="status_section_04" name="status_section_04" value=""  />
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_04" id="status_section_04_enabled"  value="1" <?php echo ( isset($section_04_meta['status']) && intval($section_04_meta['status']) == 1 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_04_enabled">
                                                Enabled
                                            </label>
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_04" id="status_section_04_disabled"  value="0" <?php echo ( isset($section_04_meta['status']) && intval($section_04_meta['status']) == 0 ? 'checked="checked"' : '');?> />
                                            <label for="status_section_04_disabled">
                                                Disabled
                                            </label>
                                        </div>

                                    </li>
                                </form>
                            </ul>
                        </div>
                        <div class="btn-panel">
                            <button type="button" class="delete-all-btn active-btn" onclick="fSaveVideoSection04();" ><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function fValidateVideoSection04(){
        $('#form_video_section_04').validate({
            rules: {
                video_section_04: {
                    required: true,
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            }
        });
    }
    function fSaveVideoSection04(){
        fValidateVideoSection04();

        if($('#form_video_section_04').valid()){
            $('#form_video_section_04').submit();
        }
    }

</script>