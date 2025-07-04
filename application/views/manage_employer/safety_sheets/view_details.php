<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('employee_management_system')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('safety_sheets')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Safety Sheets</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h2 class="section-ttile"><?php echo $title; ?></h2>
                    </div>

                    <?php if (sizeof($category_sheets)>0) {
                        foreach ($category_sheets as $sheet) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="tab">
                                            <div class="panel panel-blue">
                                                <div class="panel-heading">
                                                    <strong class="title-font"><?php echo $sheet['title']; ?></strong>
                                                    <a href="<?php echo base_url('safety_sheets/examine/' . $sheet['sid'].'/'.$cat_id) ?>"
                                                       class="btn btn-info pull-right"> Examine </a>
                                                </div>
                                                <div class="panel-body">
                                                    <?php echo $sheet['notes'];?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                                                        <a id="search_btn"-->
<!--                                                           href="--><?php //echo base_url('safety_sheets/examine/' . $sheet['sid'].'/'.$cat_id) ?><!--"-->
<!--                                                           class="btn btn-info pull-right"> Examine </a>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php }
                    }else { ?>
                        <div id="show_no_jobs" class="table-wrp">
                            <span class="applicant-not-found">No Sheet Presented Yet!</span>
                        </div>
                    <?php } ?>


            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
