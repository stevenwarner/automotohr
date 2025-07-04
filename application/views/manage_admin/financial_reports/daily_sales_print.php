<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/font-awesome-animation.min.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/responsive.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/jquery-ui-datepicker-custom.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/star-rating.css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>

        <script src="<?= base_url() ?>assets/manage_admin/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/jquery-ui.js"></script>
        <script src="<?= base_url() ?>assets/manage_admin/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/functions.js"></script>
        <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
        <script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>
        <link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />

        <!--select2-->
                    <link href="<?= base_url() ?>assets/manage_admin/css/select2.css" rel="stylesheet" />
            <script src="<?= base_url() ?>assets/manage_admin/js/select2.min.js"></script>
                <!-- Include MultiSelect -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/chosen.css">
        <script src="<?= base_url() ?>assets/manage_admin/js/chosen.jquery.js"></script>

        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/selectize.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/manage_admin/css/selectize.bootstrap3.css">
        <script src="<?= base_url() ?>assets/manage_admin/js/selectize.min.js"></script>

        <!-- Include Jquery Validate -->
        <script src="<?= base_url() ?>assets/manage_admin/js/jquery.validate.js"></script>
        <script src="<?= base_url() ?>assets/manage_admin/js/additional-methods.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/tableHeadFixer.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/star-rating.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/manage_admin/js/Chart.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/js/html2canvas.min.js'); ?>"></script>

        <title>Financial Reports - Daily Sales</title>
        
        <style type="text/css" media="print">
            @page{
                size: landscape;
            }
        </style>
    </head>
    <body>       
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="main">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="inner-content">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box" id="download_report">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="hr-registered pull-left">Employer Portal Sales Summary</span>
                                                    <span class="hr-registered pull-right"><?php echo date('l, jS F Y', mktime(0,0,0, $month, $day, $year)); ?></span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center col-xs-3">Invoice Number</th>
                                                                <th class="text-center col-xs-6">Company Name</th>
                                                                <th class="text-center col-xs-3">Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $total_sale = 0; ?>
                                                            <?php if(!empty($receipts_employer_portal)) { ?>
                                                                <?php foreach($receipts_employer_portal as $receipt) { ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <span>
                                                                                <a href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $receipt['invoice_sid']); ?>" target="_blank">
                                                                                    <?php echo 'MP-' . str_pad($receipt['invoice_sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                                </a>

                                                                            </span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <?php echo ucwords($receipt['CompanyName']); ?>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            $<?php echo number_format($receipt['amount'], 2); ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $total_sale = $total_sale + $receipt['amount']; ?>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="3">
                                                                        <span class="no-data">No Sale</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right">
                                                                    <strong>Total: <?php echo number_format($total_sale, 2); ?></strong>
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="print_section" style="display:none">
                                                <iframe src="" id="report_iframe" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
    </body>
</html>                    
<script>

    $(document).ready(function () {
        var useCanvas = "no";
        var isChromium = window.chrome;
        var winNav = window.navigator;
        var vendorName = winNav.vendor;
        var isOpera = typeof window.opr !== "undefined";
        var isIEedge = winNav.userAgent.indexOf("Edg") > -1;
        var isIOSChrome = winNav.userAgent.match("CriOS");

        if (isIOSChrome) {
           useCanvas = "yes";
        } else if(
          isChromium !== null &&
          typeof isChromium !== "undefined" &&
          vendorName === "Google Inc." &&
          isOpera === false &&
          isIEedge === false
        ) {
           useCanvas = "yes";
        }

        if (useCanvas == "yes") {
            setTimeout(function(){
                html2canvas(document.querySelector("#download_report")).then(canvas => {
                    $('#download_report').hide();
                    $('#print_section').show();
                    //
                    $('#print_section').html(canvas);
                    $('canvas').css("width","100%");
                    $('canvas').css("height","80em"); 
                    window.print();

                }); 
            },5000);
        } else {
            var draw = kendo.drawing;
            draw.drawDOM($("#download_report"), {
                avoidLinks: false,
                paperSize: "auto",
                multiPage: true,
                margin: { bottom: "2cm" },
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                $('#download_report').hide();
                $('#print_section').show();
                $('#report_iframe').attr("src",data);
            });
        }
    });


    //Pie Graph
    var pie_ctx = document.getElementById("pie-chart").getContext('2d');

    var myChart = new Chart(pie_ctx, {
        type: 'pie',
        data: {
            labels: ['Products Total Sale', 'Products Total Cost', 'Total Profit'],
            datasets: [
                {
                    label: ['Products Total Sale', 'Products Total Cost', 'Total Profit'],
                    data: [<?php echo $total_sale; ?>, <?php echo $total_cost; ?>, <?php echo $total_profit; ?>],
                    backgroundColor: ['rgba(0, 0, 195, 0.8)', 'rgba(255, 165, 0, 0.8)', 'rgba(0, 190, 0, 0.8)'],
                    borderColor: ['rgba(0, 0, 195, 0.6)', 'rgba(255, 165, 0, 0.6)', 'rgba(0, 190, 0, 0.6)']
                }
            ]
        }

    });
</script>