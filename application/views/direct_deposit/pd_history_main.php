<?php
//
$newDataArray = [];
//  
foreach ($data as $k => $v) :
    //
    if (!isset($newDataArray[$v['logged_at']])) {
        $newDataArray[$v['logged_at']] = [];
    }
    $newDataArray[$v['logged_at']][] = $v;
endforeach;

$newDataArray = array_values($newDataArray);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Direct Deposit Form</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">

    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />

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
            width: 210mm;
            /*height: 296mm;*/
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

            body.A3,
            body.A4.landscape {
                width: 297mm
            }

            body.A4,
            body.A5.landscape {
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
        #jsPDF input {
            border: 0 !important;
            border-bottom: 1px solid #000000 !important;
            width: 80%;
            font-size: 14px;
            font-weight: bold
        }

        #jsPDF p {
            font-size: 16px;
        }

        #jsPDF .row {
            margin-top: 10px;
        }


        .panel-success .panel-heading {
            background-color: #81b431;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body>
    <?php foreach ($newDataArray as $k => $v) : ?>
        <!--  -->
        <!-- Row 1 -->
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-plus" style="color: #fff;"> </span> <a data-toggle="collapse" href="#collapse<?= $k; ?>" style="color: #fff;"><?= reset_datetime(array('datetime' => $v[0]['logged_at'], 'format' => 'M d Y, D H:i:s', '_this' => $this)); ?></a>
                    </div>
                    <div class="panel-body" style="padding-top: 15px;padding-right: 15px;padding-bottom: 0px;padding-left: 15px;">
                        <div id="collapse<?= $k; ?>" class="panel-collapse collapse">
                            <?php $this->load->view('direct_deposit/pd_history', ['data' => $v]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


</body>

</html>