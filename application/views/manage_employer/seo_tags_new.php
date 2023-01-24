<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?><!-- profile_left_menu_company -->
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open('', array('id'=>'loginform')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <div class="form-col-50-left">
                                                <?php echo form_label('Meta Title','meta_title'); ?>
                                                <?php $data_meta_title = array( 'name'        => 'meta_title',
                                                                                'id'          => 'meta_title',
                                                                                'value'       => $company['meta_title'],
                                                                                'rows'        => '5',
                                                                                'cols'        => '10',
                                                                                'class'       => 'invoice-fields-textarea',
                                                                              );
                                                        echo form_textarea($data_meta_title); ?>
                                                <?php   echo form_error('meta_title'); ?>
                                                </div>
                                                <div class="form-col-50-right">
                                                    <span class="help_text">The title tag, likely the most important tag still in use, 
                                                        appears above the browser toolbar when the site is displayed. 
                                                        Keep the title tag to no more than 6 to ten words, or fewer than 70 characters, 
                                                        including one or two of your terms for that page
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div class="form-col-50-left">
                                                <?php echo form_label('Meta Keywords','meta_keywords'); ?>
                                                <?php $data_meta_keywords = array( 'name'     => 'meta_keywords',
                                                                                   'id'          => 'meta_keywords',
                                                                                   'value'       => $company['meta_keywords'],
                                                                                   'rows'        => '5',
                                                                                   'cols'        => '10',
                                                                                   'class'       => 'invoice-fields-textarea',
                                                                                );
                                                        echo form_textarea($data_meta_keywords); ?>
                                                <?php   echo form_error('meta_keywords'); ?>
                                                </div>
                                                <div class="form-col-50-right">
                                                    <span class="help_text">Although not as important as it used to be, the keyword tag is a 
                                                        helpful way to organize your optimization work. Again, different search engines 
                                                        truncate this tag at different lengths. Place at the beginning of the tag the four 
                                                        keywords you elect to optimize; put your company name and least important terms at the end. 
                                                        This list describes a few other guidelines to keep in mind
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div class="form-col-50-left">
                                                <?php echo form_label('Meta Description','meta_description'); ?>
                                                <?php $data_meta_description = array( 'name'        => 'meta_description',
                                                                                      'id'          => 'meta_description',
                                                                                      'value'       => $company['meta_description'],
                                                                                      'rows'        => '5',
                                                                                      'cols'        => '10',
                                                                                      'class'       => 'invoice-fields-textarea',
                                                                                      );
                                                        echo form_textarea($data_meta_description); ?>
                                                <?php   echo form_error('meta_description'); ?>
                                                </div>
                                                <div class="form-col-50-right">
                                                    <span class="help_text">Search engines use the Meta description tag in their results, so this is an 
                                                        important tag to get right in your Web pages. Write your Meta description like a sentence, 
                                                        describing what visitors can expect to find on the page after they click through from the search engine.
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="form-col-100">
                                                <div class="btn-wrp">
                                                    <input type="hidden" name="id" value="<?php echo $company['user_sid'];?>">
                                                    <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                    <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
        function validate_form() {
            $("#seotags").validate({
                ignore: ":hidden:not(select)",
                 rules: {
                    meta_title: {
                                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                                },
                    meta_keywords: {
                                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                                },
                    meta_description: {
                                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                                },
                    },
                messages: {
                    meta_title: {
                                    pattern:  'Invalid character used, not allowed.'
                                },
                    meta_keywords: {
                                    pattern:  'Invalid character used, not allowed.'
                                },
                    meta_description: {
                                    pattern:  'Invalid character used, not allowed.'
                                },
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }    
</script>