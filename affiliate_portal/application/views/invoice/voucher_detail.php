<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left">Marketing Agency: <b><?php echo ucwords($name); ?></b></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>invoice" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <!-- <h5><strong>Company: <?php //echo ucwords($voucher['company_name']); ?></strong></h5> -->
                            <div class="text-right">
                                <input type="button" class="btn btn-primary btn-sm" id="pdf" value="Download PDF" />
                                <a target="_blank" href="javascript:;"  onclick="PrintElem('#mydiv')" class="btn btn-primary btn-sm">Print</a>
                            </div>
                        </div>
                        <div class="card-body" id="mydiv">
                            <div class="content" style="font-size: 100%; line-height: 1.6em; display: block; max-width: 1000px; margin: 0 auto; padding: 0; position:relative">
                                <div style="width: 100%; padding:20px; text-align:center; box-sizing:border-box; background-image:url(<?= base_url();?>/assets/images/bg-body.jpg); opacity:0.9;  top:0; left:0;">
                                    <img src="<?= base_url();?>/assets/images/281X58-bottom-black.png">
                                </div>
                                <div class="body-content" style="width: 100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background-image:url(<?= base_url();?>/assets/images/bg-body.jpg);">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-dark">
                                            <tr class="d-flex">
                                                <th class="col-12" colspan="2">
                                                    <h5 class="invoice_company_name">
                                                        <strong>Company: <?php echo ucwords($voucher['company_name']); ?></strong>
                                                    </h5>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(sizeof($voucher) > 0) { ?>
                                                <tr class="d-flex">
                                                    <td class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" colspan="2">Payment Voucher # <?= $voucher['voucher_number']; ?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Dated</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= date_with_time($voucher['created']); ?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Marketing Agency</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= ucwords($voucher['marketing_agency_name']);?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company Account</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= ucwords($voucher['company_name']);?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Invoice Number</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $voucher['invoice_number'];?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Total Invoice Amount</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= isset($voucher['total_after_discount']) ? '$' . ($voucher['total_after_discount']) : '<b>N/A</b>'; ?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Payment Date</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $voucher['payment_status'] == 'paid' ? date_with_time($voucher['created']) : '<b>N/A</b>'; ?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Payment Status</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" <?= $voucher['payment_status'] == 'paid' ? 'style="color:#81b431 !important;font-weight: 900;font-size: 26px;"' : 'style="color:red !important;font-weight: 700;font-size: 22px;"' ?>><?= ucwords($voucher['payment_status']);?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Amount In Words</td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $voucher['payment_status'] == 'paid' ? ucwords(convert_number_to_words(ceil($voucher['paid_amount']))).' Only' : '<b>N/A</b>'; ?></td>
                                                </tr>
                                                <tr class="d-flex">
                                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4"><b style="font-size: 20px; font-family: OpenSans-Regular;">Amount paid to Affiliate / Reseller</b></td>
                                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8"><b style="font-size: 20px; font-family: OpenSans-Regular;"><?= $voucher['payment_status'] == 'paid' ? '$' . ($voucher['paid_amount']) : 'N/A'; ?></b></td>
                                                </tr>
                                            <?php } else{ ?>
                                                <tr class="d-flex">
                                                    <td class="col-sm-12 text-center"><b>No Voucher Found</b></td>
                                                </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <table cellspacing="0" cellpadding="10" border="0" width="100%" background="<?= base_url();?>/assets/images/bg-body.jpg">
                                    <thead>
                                    <th style="text-align:center !important; color: #000; padding:10px 0;" colspan="2"><strong style="font-size:16px;">CONTACT ONE OF OUR TALENT NETWORK PARTNERS AT</strong></th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                        <div style="text-align:center !important; color: #000;">
                                                <div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Sales Executive</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;"><img src="<?= base_url();?>/assets/images/phone-icon.png">&nbsp;&nbsp;<strong><?= TALENT_NETWORK_SALE_CONTACTNO;?> </strong></a></div>
                                                </div>
                                            <div style="text-align:center !important; color: #000;">
                                            <span style=" font-weight:600;"><img src="<?= base_url();?>/assets/images/email-icon.png">&nbsp;&nbsp;<?= TALENT_NETWORK_SALES_EMAIL;?></span>
                                            </div>
                                            </td>
                                        <td>
                                            <div style="text-align:center !important; color: #000;">
                                                <div style="color: #0000FF; margin:0 0 15px 0; font-size:16px;"><strong>Technical Support</strong></div><div style="margin:0 0 10px 0;"><a style="color:#000; text-decoration:none;"><strong><img src="<?= base_url();?>/assets/images/phone-icon.png">&nbsp;&nbsp;<?= TALENT_NETWORK_SUPPORT_CONTACTNO; ?> </strong></a></div>
                                                    </div>
                                                    <div style="text-align:center !important; color: #000;">
                                                        <span style=" font-weight:600;"><img src="<?= base_url();?>/assets/images/email-icon.png">&nbsp;&nbsp;  <?= TALENT_NETOWRK_SUPPORT_EMAIL;?></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/kendoUI.min.js'); ?>"></script>
<script type="text/javascript">
    function PrintElem(elem) {
        $(".bottom-buttons").hide();
        Popup($(elem).html());
        $(".bottom-buttons").show();
    }

    function Popup(data) {
        var mywindow = window.open('', 'Print Invoice', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Payment Voucher # <?= $voucher['voucher_number']; ?></title>');
        /*optional stylesheet*/
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
        return true;
    }

    $(document).ready(function () {
        kendo.pdf.defineFont({
            "OpenSans-Regular": "<?php echo base_url('assets/fonts/OpenSans-Regular.ttf'); ?>",
            "OpenSans-Regular|Bold": "<?php echo base_url('assets/fonts/OpenSans-Bold.ttf'); ?>",
            "WebComponentsIcons": "<?php echo base_url('assets/fonts/WebComponentsIcons.ttf'); ?>"
        });
    });

    $('#pdf').click(function () {
        var draw = kendo.drawing;
        draw.drawDOM($("#mydiv"), {
            avoidLinks: true,
            paperSize: "A4",
            margin: { bottom: "1cm" },
            scale: 0.6
        })
        
        .then(function(root) {
            return draw.exportPDF(root);
        })
        
        .done(function(data) {
            kendo.saveAs({
                dataURI: data,
                fileName: '<?php echo ucwords($voucher['voucher_number']).".pdf"; ?>',
            });
        });     
    });
</script>