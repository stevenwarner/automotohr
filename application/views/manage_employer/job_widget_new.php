<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="job-title-text">                
                                <p>To display your career page widget on any Website, please copy the text from the box and paste it into the area on your website, you would like your jobs to display.</p>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <textarea class="invoice-fields-textarea" rows="4" cols="80" readonly="readonly"><?php echo htmlspecialchars($script_tag); ?></textarea>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>