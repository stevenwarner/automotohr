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
                                            <div id="download_report">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <span class="hr-registered">Unpaid Admin Invoices For The Month Of <?php echo $months[$month]; ?>, <?php echo $year; ?></span>
                                                    </div>
                                                    <div class="hr-box-body hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center col-xs-1">Date</th>
                                                                    <th class="text-center col-xs-2">Invoice Number</th>
                                                                    <th class="text-center col-xs-3">Company</th>
                                                                    <th class="text-center col-xs-2">Subtotal</th>
                                                                    <th class="text-center col-xs-2">Discount</th>
                                                                    <th class="text-center col-xs-2">Total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(!empty($invoices_super_admin)) { ?>
                                                                        <?php foreach($invoices_super_admin as $invoice) { ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['created']); ?></td>
                                                                                <td class="text-center"><a target="_blank"  href="<?php echo base_url('manage_admin/invoice/view_admin_invoice/' . $invoice['sid']); ?>"><?php echo ($invoice['invoice_number'])?></a></td>
                                                                                <td class="text-left"><?php echo ($invoice['company_name']); ?></td>
                                                                                <td class="text-right"><?php echo number_format($invoice['value'], 2); ?></td>
                                                                                <td class="text-right">
                                                                                    <span>$<?php echo number_format($invoice['discount_amount'], 2); ?></span>
                                                                                    <br />
                                                                                    <small>(<?php echo number_format($invoice['total_discount_percentage'], 2); ?>%)</small>
                                                                                </td>
                                                                                <!--
                                                                                <?php /*if($invoice['is_discounted'] == 1) { */?>
                                                                                    <td class="text-right"><?php /*echo number_format($invoice['total_after_discount'], 2); */?></td>
                                                                                <?php /*} else { */?>
                                                                                    <td class="text-right"><?php /*echo number_format($invoice['value'], 2); */?></td>
                                                                                <?php /*} */?>
                                                                                -->
                                                                                <td class="text-right"><?php echo number_format($invoice['total_after_discount'], 2); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">
                                                                                <span  class="no-data">No Invoices Found</span>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <span class="hr-registered">Unpaid Marketplace Invoices For The Month Of <?php echo $months[$month]; ?>, <?php echo $year; ?></span>
                                                    </div>
                                                    <div class="hr-box-body hr-innerpadding">

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center col-xs-1">Date</th>
                                                                    <th class="text-center col-xs-2">Invoice Number</th>
                                                                    <th class="text-center col-xs-3">Company</th>
                                                                    <th class="text-center col-xs-2">Subtotal</th>
                                                                    <th class="text-center col-xs-2">Discount</th>
                                                                    <th class="text-center col-xs-2">Total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if(!empty($invoices_employer_portal)) { ?>
                                                                    <?php foreach($invoices_employer_portal as $invoice) { ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['date']); ?></td>
                                                                            <td class="text-center"><a target="_blank" href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $invoice['sid']); ?>"><?php echo 'MP-' . str_pad($invoice['sid'],6,0,STR_PAD_LEFT);?></a></td>
                                                                            <td class="text-left"><?php echo ($invoice['company_name']); ?></td>
                                                                            <td class="text-right"><?php echo number_format($invoice['sub_total'], 2); ?></td>
                                                                            <td class="text-right">
                                                                                <span>$<?php echo number_format($invoice['total_discount'], 2); ?></span>
                                                                                <br />
                                                                                <small>(<?php echo number_format($invoice['total_discount_percentage'], 2); ?>%)</small>
                                                                            </td>
                                                                            <td class="text-right"><?php echo number_format($invoice['total'], 2); ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">
                                                                            <span class="no-data">No Invoices Found</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>



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
    });
</script>