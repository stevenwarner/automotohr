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

        <title>Financial Reports - Yearly Sales</title>
        
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
                                                    <span class="hr-registered">Sales Summary for Year <?php echo $year; ?></span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="hidden-xs">
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <canvas id="bar-chart"></canvas>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                            <span class="btn btn-success btn-lg btn-block">
                                                                Total Sales: <span>$<?php echo number_format($total_super_admin_sales + $total_employer_portal_sales, 2); ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <hr />

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th class="col-xs-6 text-center" rowspan="2">Month</th>
                                                                <th class="col-xs-6 text-center" colspan="3">Sales</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3 text-center">Super Admin</th>
                                                                <th class="col-xs-3 text-center">Employer Portal</th>
                                                                <th class="col-xs-3 text-center">Sub Total</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php for($m = 1; $m <= 12; $m++) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $months[$m]; ?>&nbsp;
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $<?php echo number_format($yearly_sale_super_admin[$m], 2); ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $<?php echo number_format($yearly_sale_employer_portal[$m], 2); ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $<?php echo number_format($yearly_sale_super_admin[$m] + $yearly_sale_employer_portal[$m],2); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class="text-left"><strong>Total</strong></td>
                                                                    <td class="text-right"><strong>$<?php echo number_format($total_super_admin_sales, 2);?></strong></td>
                                                                    <td class="text-right"><strong>$<?php echo number_format($total_employer_portal_sales, 2);?></strong></td>
                                                                    <td class="text-right"><strong>$<?php echo number_format($total_super_admin_sales + $total_employer_portal_sales,2); ?></strong></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 col-lg-offset-4 col-sm-offset-3 col-md-offset-4">
                                                            <span class="btn btn-success btn-lg btn-block">
                                                                Total Sales: <span>$<?php echo number_format($total_super_admin_sales + $total_employer_portal_sales, 2); ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <hr />

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


    var ctx = document.getElementById("bar-chart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $chart_months; ?>,
            datasets: [
                {
                    label: 'Super Admin Sales',
                    data: <?php echo $chart_data_super_admin; ?>,
                    backgroundColor: 'rgba(81, 132, 1, 0.5)',
                    borderColor:'#518401'
                },
                {
                    label: 'Employer Portal Sales',
                    data: <?php echo $chart_data_employer_portal; ?>,
                    backgroundColor: 'rgba(0, 153, 255, 0.5)',
                    borderColor:'#09f'
                }

            ]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'US Dollars ($)'
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Months'
                    }
                }]
            }
        }
    });
</script>