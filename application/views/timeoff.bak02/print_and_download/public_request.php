<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>">
        <title><?php echo $page_title; ?></title>
    </head>

    <body cz-shortcut-listen="true">
        <div class="content" id="download_timeoff_action">
            <?php $this->load->view('timeoff/print_and_download/header'); ?>
            <div class="body-content">
                <div class="row">
                    <?php foreach ($action_data as $record) { ?>
                        <?php 
                              
                        ?>
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Request <strong style="float: right;">Created Date: <?php echo $record['created_at']?></strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <h5 style="font-weight: 700">Name</h5>
                                            <div class="employee-info">
                                                <figure>
                                                    <img src="https://automotohrattachments.s3.amazonaws.com/download-cylw2M.jpg" img_path="download-cylw2M.jpg" class="emp-image" />
                                                </figure>
                                                <div class="text">
                                                    <h4>Paul Adams</h4>
                                                    <p>(IT Manager) [Employee]</p>
                                                    <p>
                                                        <a>
                                                            Id: 172
                                                        </a>
                                                    </p>
                                               </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <h5 style="font-weight: 700">Policy</h5>
                                            <div class="upcoming-time-info">
                                                <div class="icon-image">
                                                    <a href="">
                                                        <img src="<?=base_url('assets');?>/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">
                                                    </a>
                                                </div>
                                                <div class="text">
                                                    <h4>Dec 31 2020, Thu - Jan 04 2021, Mon</h4>
                                                    <p><span>FMLA PTO</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Status</h5>
                                            <p>Pending</p>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Total Time</h5>
                                            <p>16 hours</p>
                                        </div>
                                    </div>
                                    <div class="panel panel-default approvers_panel">
                                        <div class="panel-heading">Approvers</strong></div>
                                        <div class="panel-body"> 
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(1)-nZOpvg.jfif" img_path="images-(1)-nZOpvg.jfif" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div> 
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/HygqIW_auto_careers_TU7.jpg" img_path="HygqIW_auto_careers_TU7.jpg" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div>
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(4)-1pzGew.jfif" img_path="images-(4)-1pzGew.jfif" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>           
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>    
            <?php $this->load->view('timeoff/print_and_download/footer'); ?>
        </div>    
        
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
        <script>

        </script>
    </body>
</html>