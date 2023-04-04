<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>">
    <title><?php echo $page_title; ?></title>
    <style>
        .content {
            font-size: 100%;
            line-height: 1.6em;
            display: block;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0;
            position: relative;
        }

        .header {
            width: 100%;
            float: left;
            padding: 5px 20px;
            text-align: center;
            box-sizing: border-box;
            background-color: #000;
        }

        .body-content {
            width: 100%;
            float: left;
            padding: 20px 12;
            /* margin-top: 90px; */
            box-sizing: padding-box;
        }

        .header h2 {
            color: #fff;
        }

        .footer {
            width: 100%;
            float: left;
            background-color: #000;
            padding: 20px 30px;
            box-sizing: border-box;
        }

        .footer_contant {
            float: left;
            width: 100%;
        }

        .footer_text {
            color: #fff;
            float: left;
            text-align: center;
            font-style: italic;
            line-height: normal;
            font-family: "Open Sans", sans-serif;
            font-weight: 600;
            font-size: 14px;
        }

        .footer_text a {
            color: #fff;
            text-decoration: none;
        }

        .employee-info figure {
            width: 50px !important;
            height: 50px !important;
        }

        .employee-info figure {
            float: left;
            width: 50px;
            height: 50px;
            border-radius: 100%;
            border: 1px solid #ddd;
        }

        .employee-info figure img {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            border-radius: 3px !important;
        }

        .employee-info .text {
            /* margin: 0 0 0 60px; */
        }

        .employee-info .text h4 {
            font-weight: 600;
            font-size: 18px !important;
            margin: 0;
        }

        #js-data-area .text p {
            color: #000 !important;
        }

        .employee-info .text p {
            font-weight: 400;
            font-size: 14px;
            margin: 0;
        }

        .upcoming-time-info .icon-image {
            float: left;
            width: 40px;
            height: 40px;
            display: inline-block;
        }

        .upcoming-time-info .icon-image img {
            width: 100%;
            height: 100%;
        }

        .upcoming-time-info .text {
            margin: 5px 0 0 50px;
        }

        .upcoming-time-info .text h4 {
            font-weight: 600;
            font-size: 16px;
            margin: 0;
        }

        .upcoming-time-info .text p {
            font-weight: 400;
            font-size: 14px;
            margin: 0;
        }

        .upcoming-time-info .text p span {
            font-weight: 700;
        }

        .section_heading {
            font-weight: 700;
        }

        .approvers_panel {
            margin-top: 18px;
        }

        .approver_row:nth-child(odd) {
            background-color: #F5F5F5;
        }
    </style>
</head>

<?php
//
$employees = [];
//
if (count($balances['Employees'])) {
    foreach ($balances['Employees'] as $his) {
        $employees[$his['userId']] = $his;
    }
}

?>

<body cz-shortcut-listen="true">
    <div class="content" id="download_timeoff_action">
        <div class="body-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Balance: <?= date('M d Y, D H:i:s', strtotime('now')); ?></strong></div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Work Anniversary</th>
                                        <th>Allowed Time</th>
                                        <th>Consumed Time</th>
                                        <th>Remaining Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($balances['Balances'])) : ?>
                                        <?php foreach ($balances['Balances'] as $balance) :
                                            $userId = $balance['total']['UserId']; ?>
                                            <tr>
                                                <td>
                                                    <div class="employee-info">
                                                        <div class="text">
                                                            <h4><?= $employees[$userId]['first_name']; ?> <?= $employees[$userId]['last_name']; ?></h4>
                                                            <p><?= remakeEmployeeName($employees[$userId]); ?></p>
                                                            <p>
                                                                <a>
                                                                    Id:
                                                                    <?= !empty($employees[$userId]['employee_number']) ? $employees[$userId]['employee_number'] : $employees[$userId]['userId']; ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= empty($employees[$userId]['joined_at']) ? '-' : formatDate(
                                                        $employees[$userId]['joined_at'],
                                                        'Y-m-d',
                                                        'M d Y, D'
                                                    ); ?>
                                                </td>
                                                <td><?= $balance['AllowedTime']['text'] == '' ? '0 hours' : $balance['AllowedTime']['text']; ?>
                                                </td>
                                                <td>
                                                    <span>
                                                        <strong>Paid:</strong>
                                                        <?= $balance['ConsumedTime']['text'] == '' ? '0 hours' : $balance['ConsumedTime']['text']; ?>
                                                    </span><br />
                                                    <span>
                                                        <strong>Unpaid:</strong>
                                                        <?= $balance['UnpaidConsumedTime']['text'] == '' ? '0 hours' : $balance['UnpaidConsumedTime']['text']; ?>
                                                    </span>
                                                </td>
                                                <td><?= $balance['RemainingTime']['text'] == '' ? '0 hours' : $balance['RemainingTime']['text']; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    <script id="script">
        if ("<?php echo $download; ?>" == 'yes') {
            download_document();
        } else {
            $(window).on("load", function() {
                setTimeout(function() {
                    window.print();
                }, 2000);
            });

            window.onafterprint = function() {
                window.close();
            }
        }

        function download_document() {
            var draw = kendo.drawing;
            draw.drawDOM($("#download_timeoff_action"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: {
                        bottom: "2cm"
                    },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    var pdf = data;
                    kendo.saveAs({
                        dataURI: pdf,
                        fileName: "Balance for <?= date('M d Y D H:i:s', strtotime('now')); ?>.pdf",
                    });
                    window.close();
                });
        }
    </script>
</body>

</html>