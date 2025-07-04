<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">Home Page Customize Buttons <span class="glyphicon glyphicon-plus"></span></a>
        </h4>
    </div>
    <div id="collapseNine" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="universal-form-style-v2">
                        <form id="home_job_opportunity" method="post" name="home_job_opportunity" enctype="multipart/form-data">
                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                            <input type="hidden" name="page_name" id="page_name" value="home"/>
                            <input type="hidden" name="section" id="section" value="collapseNine"/>
                            <input type="hidden" name="perform_action" id="perform_action" value="save_home_job_opportunity"/>
                            
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <label for="job_button_customization">Home Page Job Opportunities Button </label>
                                    <input type="hidden" id="job_button_customization" name="job_button_customization" value=""  /><!--this is for error reporting-->
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                Enable Button
                                                <input type="radio" name="job_button_customization" id="job_button_customization_enabled"  value="1" <?php echo ( isset($theme['theme4_enable_home_job_opportunity']) && intval($theme['theme4_enable_home_job_opportunity']) == 1 ? 'checked="checked"' : '');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                Disable Button
                                                <input type="radio" name="job_button_customization" id="job_button_customization_disabled"  value="0" <?php echo ( isset($theme['theme4_enable_home_job_opportunity']) && intval($theme['theme4_enable_home_job_opportunity']) == 0 ? 'checked="checked"' : '');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label for="job_opportunities_text">Job Opportunities button text <span class="staric">*</span></label>
                                            <input type="text" name="job_opportunities_text" id="job_opportunities_text" value="<?php echo (isset($theme['theme4_home_job_opportunity_text']) && $theme['theme4_home_job_opportunity_text'] != '') ? $theme['theme4_home_job_opportunity_text'] : 'View Job Opportunities'; ?>" class="invoice-fields" />
                                        </div>
                                    </div>
                                    <?php //echo '<pre>'; print_r($job_fair_data); echo '</pre>'; ?>
                            <?php   if($job_fair_configuration == 1) { 
                                $jobFairsMultip = explode(',', $theme['job_fair_homepage_page_url']);
                                ?>
                                        <div class="row"><br>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label for="job_fair">Job Fair Forms</label>
                                                <div class="">
                                                    <select class="jsSelect2" name="job_fair_homepage_page_url[]" multiple>
                                <?php                   if(empty($job_fair_multiple_forms)) { echo '<option value="">Default</option>'; } ?>
                                <?php                       foreach($job_fair_multiple_forms as $jfmf) { ?>
                                                                <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $jobFairsMultip )? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                <?php                       } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label class="empty">&nbsp;</label>
                                                <label for="job_fair">
                                                    <input type="checkbox" id="job_fair" name="job_fair" <?php echo ($theme['theme4_enable_job_fair_homepage'] == 1 ? 'checked="checked"' : ''); ?>  />
                                                    Enable Job Fair
                                                </label>
                                            </div>
                                        </div>
                            <?php   } else { ?>
                                        <input type="hidden" name="job_fair_homepage_page_url" value="">
                            <?php   } ?>
                                </div>                            
                                <div class="btn-panel text-center">
                                    <input type="submit" id="home_job_form_settings" class="btn btn-success" value="Save Settings" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#home_job_form_settings').click(function () {
        $("#home_job_opportunity").validate({
            ignore: ":hidden:not(select)",
            rules: {
                job_opportunities_text: {
                    required: true,
                }
            },
            messages: {
                job_opportunities_text: {
                    required: 'Job Opportunities button text is required',
                }
            }
        });     
    });
</script>