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
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                            <div class="job-title-text">                
                                <p>To get your Careers Page jobs XML file, please copy the text from the text area and use it as Webservice. You can add/remove attributes from the link.</p>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <textarea class="invoice-fields-textarea" rows="4" cols="80" readonly="readonly"><?php echo htmlspecialchars($api_link); ?></textarea>
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