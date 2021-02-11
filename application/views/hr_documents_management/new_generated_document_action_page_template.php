<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
        <link rel="stylesheet" type="https://printjs-4de6.kxcdn.com/print.min.css" href="print.css">
        <title>Generated Document</title>
        <style>
            .center-col{
                float: left;
                width: 100%;
                text-align: center;
                margin-top: 14px;
            }
            .center-col h2,
            .center-col p{
                margin: 0 0 5px 0;
            }
            .sheet-header {
                float: left;
                width: 100%;
                padding: 0 0 2px 0;
                margin: 0 0 5px 0;
                border-bottom: 5px solid #000;
            }
            .sheet.padding-10mm { padding: 10mm }
            .header-logo{
                float: left;
                width: 100%;
            }
            input[type='checkbox'].user_checkbox {
	margin-top: -30px;
}

input[type='checkbox'].user_checkbox {
	-webkit-font-smoothing: antialiased;
	text-rendering: optimizeSpeed;
	width: 25px;
	height: 25px;
	margin: 0;
	margin-right: 10px !important;
	display: block;
	float: left;
	position: relative;
	cursor: pointer;
}

input[type='checkbox'].user_checkbox:after {
	content: "";
	vertical-align: middle;
	text-align: center;
	line-height: 25px;
	position: absolute;
	cursor: pointer;
	height: 25px;
	width: 25px;
	left: 0;
	top: 0;
	font-size: 14px;
	background: #999999;
}

input[type='checkbox'].user_checkbox:hover:after, input[type='checkbox'].user_checkbox:checked:hover:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}

input[type='checkbox'].user_checkbox:checked:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}
        </style>
    </head>

    <body cz-shortcut-listen="true">
        <main role="main" class="container" style="border:5px solid #000;">
            <section class="sheet padding-10mm" id="download_generated_document">
                <!-- <article class="sheet-header">
                    <div class="header-logo">
                        <h2 style="margin: 0;"><?php //echo $company_name; ?></h2>
                        <small>
                            <?php //echo $action_date; ?>: <b><?php //echo reset_datetime(array('datetime' => date('Y-m-d'), '_this' => $this)); ?></b><br>
                            <?php //echo $action_by; ?>: <b><?php //echo $employee_name; ?></b>
                        </small>
                    </div>
                    <div class="center-col">
                        <h2><?php //echo $document_title; ?></h2>
                    </div>
                </article> -->
                    
                <div class="main-content">
                    <div class="dashboard-wrp">
                        <div class="container-fluid">
                            <div class="row" id="jsContentArea" style="word-break: break-all;">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php if ($request_type == 'submitted' && $is_iframe_preview == 1) { ?>
                                        <canvas id="the-canvas" style="border:1px  solid black"></canvas>
                                    <?php } else { ?>
                                        <?php echo html_entity_decode($document_contant); ?>
                                    <?php } ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>    
            
        </main>

        <!-- Document loader -->
        <div id="document_loader" class="text-center my_loader" style="display: none;">
            <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
            <div class="loader-icon-box">
                <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate document view...
                </div>
            </div>
        </div>
       
        <style>
            #download_generated_document ul,
            #download_generated_document ol{
                padding-left:20px;
            }
        </style>
    </body>
</html>