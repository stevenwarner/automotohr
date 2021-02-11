<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
    <div class="main" id="mydiv">
        <div class="container-fluid">
            <div class="row">
                <div class="inner-content">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 no-padding">
                        <div class="dashboard-content">
                            <div class="dash-inner-block">
                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        <div class="heading-title page-title text-center">
                                            <span class="page-title" style="float: none">Marketing Agency:</span>&nbsp;<span class="text-success"><?php echo ucwords($marketing_agency_info['full_name']); ?></span><!--&nbsp;<span class="text-default"><small><?php /*echo $marketing_agency_info['email'] != '' ? '( ' . $marketing_agency_info['email'] . ' )' : ''; */?></small></span>-->
                                        </div>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                                <?php if(!empty($marketing_agency_invoices_group)) {
                                                    $all_company_commission = 0; ?>
                                                    <?php foreach($marketing_agency_invoices_group as $company => $commission_invoices) {
                                                        $total_commission = 0; ?>
                                                        <div class="hr-box">
                                                            <div class="hr-box-header bg-header-green">
                                                                <span class="hr-registered pull-left"><span class="text-success"></span><?php echo ucwords($company); ?></span>
                                                                <span class="hr-registered pull-right" id="<?= str_replace(' ','-',$company);?>"></span>
                                                            </div>
                                                            <div class="hr-box-body hr-innerpadding">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hoverg table-stripped">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center col-xs-3">Date</th>
                                                                            <th class="col-xs-6">Invoice Number</th>
                                                                            <th class="text-center col-xs-3">Commission Payable</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(!empty($commission_invoices)) {
                                                                            foreach($commission_invoices as $invoice) {
                                                                                if ($invoice['payment_status'] == 'paid') {
                                                                                    $total_commission += $invoice['total_commission_after_discount']; ?>
                                                                                    <tr id="parent_<?= $invoice['sid'] ?>">
                                                                                        <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['created'], true); ?></td>
                                                                                        <td class="text-left">
                                                                                            <?php echo STORE_CODE . '-' . str_pad($invoice['invoice_sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                                            <br>
                                                                                            <?php echo $invoice['payment_status'] == 'paid' ? '<b>Payment Date:</b> ' . convert_date_to_frontend_format($invoice['payment_date']) : '<b>Un-Paid</b>'; ?>
                                                                                            <br>
                                                                                            <?php echo $invoice['invoice_number']; ?>
                                                                                        </td>
                                                                                        <td class="text-right" style="font-size: 16px">
                                                                                            <?php if ($invoice['discount_percentage'] > 0 && $invoice['discount_amount'] > 0) { ?>
                                                                                                <span<?php echo $invoice['payment_status'] == 'paid' ? ' style="color: #3c763d"' : ' style="color: red"';?>>
                                                                                                    <b>$<?php echo number_format($invoice['total_commission_after_discount'], 2); ?></b>
                                                                                                </span>
                                                                                            <?php } else { ?>
                                                                                                <span<?php echo $invoice['payment_status'] == 'paid' ? ' style="color: #3c763d"' : ' style="color: red"';?>>
                                                                                                    <b>$<?php echo number_format($invoice['commission_value'], 2); ?></b>
                                                                                                </span>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php }
                                                                            }?>
                                                                            <tr>
                                                                                <td class="text-right" colspan="2"><b>Total: </b></td>
                                                                                <td class="text-success text-right" colspan="3" style="font-size: 16px"><?= '<b>$' . $total_commission . '</b>'; $all_company_commission += $total_commission;?></td>
                                                                                <script type="text/javascript">$('#<?= str_replace(' ','-',$company);?>').html('<?= 'Total Paid: $' . $total_commission;?>');</script>
                                                                            </tr>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td class="text-center" colspan="8">
                                                                                    <span class="no-data">No Invoices</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="hr-box">
                                                        <div class="hr-box-header bg-header-green">
                                                            <span class="hr-registered pull-right" style="font-size: 18px"><span class="text-success"></span><?= ucwords($marketing_agency_info['full_name']); ?> - Grand Commission: <b>$<?= $all_company_commission; ?></b></span>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="hr-box">
                                                        <div class="hr-innerpadding text-center">
                                                            <span class="no-data">This Marketing Agency Has not Brought any Business Yet</span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
    </div>
<!--</html>-->

<script src="<?php echo base_url('assets/js/kendoUI.min.js'); ?>"></script>
<script>
    $( window ).on( "load", function() {
        var draw = kendo.drawing;
        draw.drawDOM($("#mydiv"), {
            avoidLinks: false,
            paperSize: "auto",
            multiPage: true,
            margin: {
                left: 10,
                right: "10pt",
                top: "10mm",
                bottom: "1in"
            },
            scale: 0.8
        })

        .then(function(root) {
            return draw.exportPDF(root);
    })

        .done(function(data) {
            kendo.saveAs({
                dataURI: data,
                fileName: '<?php echo ucwords($marketing_agency_info['full_name'])." Commissions.pdf"; ?>',
            });
        });
        setTimeout(function(){
            window.close();
        }, 800);
    });

</script>


