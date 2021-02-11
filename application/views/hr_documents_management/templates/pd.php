<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>General Information Document - <?=ucwords(preg_replace('/_/', ' ', $documentType));?></title>
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css');?>">
    
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
    
    <style>
        @font-face {
            font-family: 'Conv_SCRIPTIN';
            src: url('../fonts/SCRIPTIN.eot');
            src: local('?'), url('../fonts/SCRIPTIN.woff') format('woff'),
            url('../fonts/SCRIPTIN.ttf') format('truetype'),
            url('../fonts/SCRIPTIN.svg') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            margin: 0
        }

        body {
            margin: 0
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
            font-size: 14px;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        }

        /** Paper sizes **/
        body.A3 .sheet {
            width: 297mm;
            height: 419mm
        }

        body.A3.landscape .sheet {
            width: 420mm;
            height: 296mm
        }

        body.A4 .sheet {
            width: 210mm; /*height: 296mm;*/
            margin: 5mm;
        }

        body.A4.landscape .sheet {
            width: 297mm;
            height: 209mm
        }

        body.A5 .sheet {
            width: 148mm;
            height: 209mm
        }

        body.A5.landscape .sheet {
            width: 210mm;
            height: 147mm
        }

        /** Padding area **/
        .sheet.padding-10mm {
            padding: 10mm
        }

        .sheet.padding-15mm {
            padding: 15mm
        }

        .sheet.padding-20mm {
            padding: 20mm
        }

        .sheet.padding-25mm {
            padding: 25mm
        }

        /** For screen preview **/
        @media screen {
            body {
                /*background: #e0e0e0*/
            }

            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
                margin: 5mm;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A3.landscape {
                width: 420mm
            }

            body.A3, body.A4.landscape {
                width: 297mm
            }

            body.A4, body.A5.landscape {
                width: 210mm
            }

            body.A5 {
                width: 148mm
            }
        }

        .sheet-header {
            float: left;
            width: 100%;
            padding: 0 0 2px 0;
            margin: 0 0 5px 0;
            border-bottom: 5px solid #000;
        }

        .header-logo {
            float: left;
            width: 20%;
        }

        .center-col {
            float: left;
            width: 60%;
            text-align: center;
        }

        .center-col h2,
        .center-col p {
            margin: 0 0 5px 0;
        }

        .right-header {
            float: left;
            width: 20%;
            text-align: center;
        }

        .right-header h3,
        .right-header p {
            margin: 0 0 5px 0;
        }

        .right-header p {
            font-size: 14px;
        }
    </style>

    <style>
        #jsGeneralPDF input{ border: 0 !important; border-bottom: 1px solid #000000 !important; width: 80%; font-size: 14px; font-weight: bold}
        #jsGeneralPDF p{ font-size: 16px;}
        #jsGeneralPDF .row{ margin-top: 10px;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

    <!--  -->
    <div id="jsGeneralPDF" style="width: 780px; padding-left: 20px; margin-top: 20px;">
        <?php if($documentType != 'direct_deposit') { ?>
        <div class="col-xs-8">
            <p style="font-size: 12px;"><strong>General Document Type:</strong> <?=ucwords(str_replace('_', ' ', $documentType));?></p>
            <p style="font-size: 12px;"><strong><?=ucwords($userType);?> Name:</strong> <?=$userType == 'applicant' ? ucwords($userData['first_name'].' '.$userData['last_name']) : remakeEmployeeName($userData);?></p>
        </div>
        <div class="col-xs-4">
            <p><strong><?=$userData['CompanyName'];?></strong></p>
        </div>
        <br />
        <br />
        <?php } ?>
        <div>
            <?=$template;?>
        </div>
    </div>
  
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?=base_url('assets/js/jquery.validate.min.js');?>"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    
    <script type="text/javascript">
    // Manage all image sizes
    if( $('#jsGeneralPDF').find('img').length > 0 ){
        $('#jsGeneralPDF').find('img').map(function(){
            $(this).css({
                display: "block",
                "max-width": "100%",
                margin: "auto"
            });
        });
    }

    <?php if($action == 'download') { ?>
        $( window ).on( "load", function() {
            var draw = kendo.drawing;
            draw.drawDOM($("#jsGeneralPDF"), {
                avoidLinks: false,
                paperSize: "A4",
                multiPage: true,
                margin: { bottom: "2"},
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                kendo.saveAs({
                    dataURI: data,
                    fileName: "<?=$documentType.'_'.date('Y-m-d', strtotime('now'));?>.pdf"
                });
                //
                setTimeout(() => {
                    window.close();   
                }, 5000);
            });
        });
    <?php } else if($action == 'print') { ?>
        window.print();
        window.onafterprint = function() { window.close(); }
    <?php }?>
    </script>
</body>
</html>
