<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>&nbsp;Settings</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                            <div class="universal-form-style-v2">
                                <div class="autoheight">
                                    <label class="autoheight" for="select_template">Select a Template</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" id="template">
                                            <?php $temp = $this->uri->segment(3); ?>
                                            <?php if(!empty($templates)) { ?>
                                                <?php foreach($templates as $template) { ?>
                                                    <?php echo $default_selected = $template['sid'] == $temp ? true : false; ?>
                                                    <option <?php echo set_select('template', $template['sid'], $default_selected); ?> value="<?php echo $template['sid']; ?>"><?php echo $template['title']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>&nbsp;</label>
                            <a id="preview_btn" class="btn btn-success btn-block btn-equalizer" href="">Preview</a>
                        </div>
                    </div>
                    <hr />

                    <?php if(!empty($my_template)) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        <strong>Title: <?php echo $my_template['title']; ?></strong>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <h3>Description:</h3>
                                        <div class="col-lg-12">
                                            <?php echo html_entity_decode($my_template['description']); ?>
                                        </div>

                                        <hr />

                                        <h3>Requirements:</h3>
                                        <div class="col-lg-12">
                                            <?php echo html_entity_decode($my_template['requirements']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#template').on('change', function () {
            var template_sid = $(this).val();

            var url = '<?php echo base_url('settings/preview_job_listing_template')?>' + '/' + template_sid;

            $('#preview_btn').attr('href', url);
        }).trigger('change');

        $('#preview_btn').on('click', function () {
            $('#template').trigger('change');
        });
    });
</script>
