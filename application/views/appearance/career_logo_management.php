<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_logo_image" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="update_logo" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                        <?php $logo_image = isset($company_logo_data['logo_image']) && !empty($company_logo_data['logo_image']) ? AWS_S3_BUCKET_URL . $company_logo_data['logo_image'] : base_url('assets/images/NoLogo.jpg'); ?>
                                        <?php $aspect_ratio = isset($company_logo_data['logo_aspect_ratio']) && !empty($company_logo_data['logo_aspect_ratio']) ? $company_logo_data['logo_aspect_ratio'] : 'none'; ?>
                                        <?php $logo_status = isset($company_logo_data['logo_status']) ? $company_logo_data['logo_status'] : '1'; ?>
                                        <?php $logo_location = isset($company_logo_data['logo_location']) && !empty($company_logo_data['logo_location']) ? $company_logo_data['logo_location'] : 'left'; ?>

                                        <div class="row">
                                            <div class="col-xs-2">
                                                <label>Logo Aspect Ratio</label>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="row">
                                                    <div class="col-xs-3 text-center">
                                                        <label for="aspect_ratio_none">
                                                            None
                                                            <div class="img-thumbnail">
                                                                <img class="img-responsive logo-aspect-none" src="<?php echo $logo_image; ?>" alt="none" />
                                                            </div>
                                                        </label>
                                                        <br/>
                                                        <?php $default_selected = $aspect_ratio == 'none' ? true : false ; ?>
                                                        <input <?php echo set_radio('aspect_ratio', 'none', $default_selected); ?> type="radio" id="aspect_ratio_none" name="aspect_ratio" value="none"/>
                                                    </div>
                                                    <div class="col-xs-3 text-center">
                                                        <label for="aspect_ratio_square">
                                                            Square
                                                            <div class="img-thumbnail">
                                                                <img class="logo-aspect-square" src="<?php echo  $logo_image; ?>" alt="square" />
                                                            </div>
                                                        </label>
                                                        <br/>
                                                        <?php $default_selected = $aspect_ratio == 'square' ? true : false ; ?>
                                                        <input <?php echo set_radio('aspect_ratio', 'square', $default_selected); ?> type="radio" id="aspect_ratio_square" name="aspect_ratio" value="square"/>
                                                    </div>
                                                    <div class="col-xs-3 text-center">
                                                        <label for="aspect_ratio_horizontal">
                                                            Horizontal
                                                            <div class="img-thumbnail">
                                                                <img class="logo-aspect-horizontal" src="<?php echo  $logo_image; ?>" alt="horizontal" />
                                                            </div>
                                                        </label>
                                                        <br/>
                                                        <?php $default_selected = $aspect_ratio == 'horizontal' ? true : false ; ?>
                                                        <input <?php echo set_radio('aspect_ratio', 'horizontal', $default_selected); ?> type="radio" id="aspect_ratio_horizontal" name="aspect_ratio" value="horizontal"/>
                                                    </div>
                                                    <div class="col-xs-3 text-center">
                                                        <label for="aspect_ratio_vertical">
                                                            Vertical
                                                            <div class="img-thumbnail">
                                                                <img class="logo-aspect-vertical" src="<?php echo  $logo_image; ?>" alt="vertical" />
                                                            </div>
                                                        </label>
                                                        <br/>
                                                        <?php $default_selected = $aspect_ratio == 'vertical' ? true : false ; ?>
                                                        <input <?php echo set_radio('aspect_ratio', 'horizontal', $default_selected); ?> type="radio" id="aspect_ratio_vertical" name="aspect_ratio" value="vertical"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label>Logo Location</label>
                                                </div>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <?php $default_selected = $logo_location == 'left' ? true : false ; ?>
                                                        <div class="radio">
                                                            <label for="logo_location_left">
                                                                <input <?php echo set_radio('logo_location', 'left', $default_selected); ?> type="radio" id="logo_location_left" name="logo_location" value="left" />
                                                                Left
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $default_selected = $logo_location == 'center' ? true : false ; ?>
                                                        <div class="radio">
                                                            <label for="logo_location_center">
                                                                <input <?php echo set_radio('logo_location', 'center', $default_selected); ?> type="radio" id="logo_location_center" name="logo_location" value="center" />
                                                                Center
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-3">
                                                        <?php $default_selected = $logo_location == 'right' ? true : false ; ?>
                                                        <div class="radio">
                                                            <label for="logo_location_right">
                                                                <input <?php echo set_radio('logo_location', 'right', $default_selected); ?> type="radio" id="logo_location_right" name="logo_location" value="right" />
                                                                Right
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label>Logo Status</label>
                                                </div>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <?php $default_selected = $logo_status == '1' ? true : false ; ?>
                                                        <div class="radio">
                                                            <label for="logo_status_enabled">
                                                                <input <?php echo set_radio('logo_status', '1', $default_selected); ?> type="radio" id="logo_status_enabled" name="logo_status" value="1" />
                                                                Enabled
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $default_selected = $logo_status == '0' ? true : false ; ?>
                                                        <div class="radio">
                                                            <label for="logo_status_disabled">
                                                                <input <?php echo set_radio('logo_status', '0', $default_selected); ?> type="radio" id="logo_status_disabled" name="logo_status" value="0" />
                                                                Disabled
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <label for="logo_image">Logo Image</label>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="form-group">
                                                    <input type="file" class="" id="logo_image" name="logo_image" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-4"></div>
                                            <div class="col-xs-4">
                                                <button type="submit" class="btn btn-success btn-block">Update Logo</button>
                                            </div>
                                            <div class="col-xs-4"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#form_logo_image').validate({
            rules: {
                logo_image: {
                    accept: 'image/*'
                }
            },
            messages: {
                logo_image: {
                    accept: 'You can upload Images Only.'
                }
            }
        });
    });
</script>