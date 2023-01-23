<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                                </div>
                            </div>
                            <div class="btn-panel">
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 pull-right">
                                    <a class="btn btn-success btn-block" href="<?php echo base_url('security_access_level'); ?>" >Back to Security Manager</a>
                                </div>
                            </div>
                        <div class="dashboard-conetnt-wrp">               
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                            <?php foreach ($security_details as $details){ ?>  
                                                <div class="form-col-100 box-view">                                     
                                                    <div class="sub-domain-header">
                                                        <h3 style="color: #055bba;"><?php echo $details['access_level']; ?></h3>
                                                    </div>
                                                    <div class="info-text">
                                                        <?php echo $details['description']; ?>                                                    
                                                    </div>
                                                </div>
                                            <?php }?>
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