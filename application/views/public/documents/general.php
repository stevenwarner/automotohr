<!-- Styles -->
<link rel="stylesheet" href="<?=base_url('assets/employee_panel/css/jquery.datetimepicker.css');?>">

<!-- Scripts -->
<script src="<?=base_url('assets/employee_panel/js/jquery.validate.js');?>"></script>
<script src="<?=base_url('assets/js/select2.js');?>"></script>
<script src="<?=base_url('assets/employee_panel/js/jquery.datetimepicker.js');?>"></script>
<script src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<style>
    #jstopdf p{
        word-break: break-all;
    }
    .cs-required{
        font-size: 14px;
        color: #cc1100;
        font-weight: bold;
    }
    .modal-footer{
        position: relative;
    }
    .csModalFooterLoader{
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        background: rgba(255,255,255,.5);
    }
    .csModalFooterLoader i{
        font-size: 20px;
        position: relative;
        float: left;
        top: 30%;
        left: 50%;
    }
</style>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile"><?=ucwords(preg_replace('/_/', ' ', $type));?>
                    <span class="pull-right">
                        <a href="<?=base_url('hr_documents_management/gpd/print/'.($type).'/'.($users_type).'/'.($users_sid).'/');?>" target="_blank" class="btn btn-info">Print</a>
                        <a href="<?=base_url('hr_documents_management/gpd/download/'.($type).'/'.($users_type).'/'.($users_sid).'/');?>" target="_blank" class="btn btn-info">Download</a>
                    </span>
                </h1>
                </div>

                <?php if(!empty($document['note'])) { ?>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">Note</div>
                            <div class="panel-body"><?=$document['note'];?></div>
                        </div>
                    </div>
                </div>

                <?php } ?>

                <!--  -->
             
                <?php if($type == 'direct_deposit') { ?>
                    <style>
                        .panel-heading{ background-color: #ff0000; }
                    </style>
                    <?php $this->load->view('direct_deposit/form', [
                        'dd_user_type' => $users_type,
                        'dd_user_sid' => $users_sid,
                        'company_id' => $company_sid,
                        'send_email_notification' => 'yes',
                    ]); ?>
                <?php } else { ?>
                    <?php $this->load->view('public/documents/'.( $type ).''); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>