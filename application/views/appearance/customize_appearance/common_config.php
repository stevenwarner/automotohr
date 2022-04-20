<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#siteConfiguration1">Site Configuration <span class="glyphicon glyphicon-plus"></span></a>
        </h4>
    </div>
    <div id="siteConfiguration1" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" id="form_config_section_01">
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_site_configuration"/>
                                    <input type="hidden" id="page_name" name="page_name" value="site_settings" />

                                    <li class="form-col-100 autoheight">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="enable_header_bg" <?=isset($site_settings['enable_header_bg']) && $site_settings['enable_header_bg'] == 1 ? 'checked' : '';?> /> Use black color for header
                                            <div class="control__indicator"></div>
                                        </label>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="enable_header_overlay" <?=!isset($site_settings['enable_header_overlay']) || $site_settings['enable_header_overlay'] == 1 ? 'checked' : '';?> /> Use header banner overlay
                                            <div class="control__indicator"></div>
                                        </label>
                                    </li>

                                    <div class="btn-panel">
                                        <ul>
                                            <li><button type="submit" class="delete-all-btn active-btn"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save Configuration</button></li>
                                        </ul>
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>