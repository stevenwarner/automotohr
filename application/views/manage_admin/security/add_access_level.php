<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                        <a href="<?php echo base_url('manage_admin/security_settings')?>" class="btn black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Security Settings</a>
                                                    </div>
                            <form action="" method="POST" enctype="multipart/form-data" id="add_question">  
                            <div class="row">
                                 <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                      <label>Title: <samp style="color:red;">*</samp></label>
                                      <input class="hr-form-fileds" type="text" name="access_level" id="access_level" value="" required="">
                                    </div>
                                </div> <br>

                                <div class="row">
                                 <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                      <label>Description:</label>
                                      <textarea style="padding:10px; height:200px; background: #fff;" class="hr-form-fileds" name="description"></textarea>
                                    </div>
                              </div><br>
                            
                                <div class="row">
                                     <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                         <input class="btn btn-success" type="submit" name="add_question_submit" value="Submit">
                                         <a href="http://automotohr.local/manage_admin/security_settings" class="btn black-btn">Cancel</a>                                      </div>
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
            </div>
        </div>
    </div>
</div>

<script>

