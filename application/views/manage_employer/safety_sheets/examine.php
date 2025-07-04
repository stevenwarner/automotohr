<div class="main">
    <div class="container">
        <div class="row">
<!--            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">-->
<!--                --><?php //$this->load->view('main/employer_column_left_view'); ?>
<!--            </div>-->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('employee_management_system')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('safety_sheets/view_sheets/'.$cat_id)?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Safety Sheets</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>

<!--                <div class="form-wrp">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="reference_network_table">-->
<!--                            <div class="form-group autoheight">-->
<!--                                <label>Title</label>-->
<!--                                <div class="well well-sm bg-white">-->
<!--                                    --><?//= ucfirst($sheets_details[0]['title']); ?>
<!--                                </div>-->
<!--                            </div>                            -->
<!--                        </div>-->
<!--                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="reference_network_table">-->
<!--                            <div class="form-group autoheight">-->
<!--                                <label>Notes</label>-->
<!--                                <div class="well well-sm bg-white">-->
<!--                                    --><?//= ucfirst($sheets_details[0]['notes']); ?>
<!--                                </div>-->
<!--                            </div>                            -->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <div class="form-wrp">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="tab">
                                            <div class="panel panel-blue">
                                                <div class="panel-heading">
                                                    <strong class="title-font"><?= ucfirst($sheets_details[0]['title']); ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <?= ucfirst($sheets_details[0]['notes']); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="full-width">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Related Documents</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php if(!empty($pdf) || !empty($png)) {?>
                                    <?php foreach($pdf as $file){ ?>
                                        <div class="col-lg-12 text-center">
                                            <div class="well well-sm">
<!--                                                <a style="display: inline-block;" href="--><?php //echo AWS_S3_BUCKET_URL . $file['file_code']?><!--" download="docs">--><?php //echo $file['file_name']; ?><!--</a>-->
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $file['file_code']) . '&embedded=true'; ?>"
                                                        id="preview_iframe" class="uploaded-file-preview"
                                                        style="width:100%; height:80em;" frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    <?php } foreach($png as $file){ ?>
                                        <div class="col-lg-12 text-center">
                                            <div class="well well-sm">
                                                <img class="img-responsive" style="display: inline-block;" src="<?php echo AWS_S3_BUCKET_URL . $file['file_code']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                } else{ ?>
                                    <div class="full-width text-center">
                                        <span class="no-data">No Documents Found</span>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#form_new_note').submit(function(){
            var response = $('.response').val().trim();
            if(response.length === 0){
                alertify.error('Please provide your response');
                return false;
            }
        });
    });
</script>