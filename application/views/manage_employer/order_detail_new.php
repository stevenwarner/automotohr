<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp" >
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php echo base_url('order_history'); ?>"><i class="fa fa-chevron-left"></i>Orders History</a>
                            <?php echo $title; ?>
                        </span>
                    </div>

                    <!-- Edit Invoice Start -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="message-action-btn">
                                <input type="button" class="submit-btn" id="pdf" value="Download PDF" />
                                <a target="_blank" href="javascript:;"  onclick="PrintElem('#mydiv')" class="submit-btn">Print</a>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-format" id="mydiv">
                        <div class="table-responsive">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th colspan="5" style="width:100%;vertical-align: top; background: #eeeeee; border-collapse: collapse; border:thin solid #000; padding: 5px; text-align: center;">Invoice # <?php echo $order_id; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">Client</th>
                                        <td colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">
                                            <strong>
                                                <?php echo ucwords($company_details['CompanyName'])?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">Phone</th>
                                        <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">
                                            <?php echo $company_details['PhoneNumber']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">Invoice Date</th>
                                        <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;"><?=reset_datetime(array( 'datetime' => $invoiceData['date'], '_this' => $this, 'from_format' => 'm-d-Y')); ?></td>
                                    </tr>
                                    <tr>
                                        <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">Payment Method</th>
                                        <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;"><?= $invoiceData['payment_method']?></td>
                                    </tr>
                                    <tr>
                                        <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">Payment Status</th>
                                        <td class="<?=set_value('date', $invoiceData['status']) ?>" colspan="4" style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><?= set_value('date', $invoiceData['status']) ?></td>
                                    </tr>
                                </tbody>
                            </table>

                                    <table width="100%">
                                        <thead>
                                        <tr>
                                        <th style="width:30%;vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">Item</th>
                                        <th style="width:20%;vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; text-align: center;">Item Quantity</th>
                                        <th style="width:20%;vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">Item Price</th>
                                        <th style="width:30%;vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">Total</th>
                                        </tr>
                                        </thead>
                                    <tbody>
<!--                                    <tr style="width:100%;">-->
<!--                                        <th style="width:30%; vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">Item</th>-->
<!--                                        <th style="width:30%;vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px; text-align: center;">Item Quantity</th>-->
<!--                                        <th style="width:10%; vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">Item Price</th>-->
<!--                                        <th style="width:30%;vertical-align: top; background: #eeeeee; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">Total</th>-->
<!--                                    </tr>-->
                                    <?php $counter = 0; ?>
                                    <?php if (!empty($invoiceData['description'])) { ?>
                                        <ul class="list-style-pdf">
                                            <li style="width:100%">
                                                <label>Invoice Description:</label>
                                                <p><?php echo $invoiceData['description']; ?></p>
                                            </li>
                                        </ul>
                                        <?php
                                    }
                                    $serialized_products = $invoiceData['serialized_products'];
                                        for ($i = 0; $i < count($serialized_products['custom_text']); $i++) {
                                        // if($serialized_products['custom_text'][$i]!=''){continue;} 
                                    ?>
                                    <tr id="item-<?= $counter ?>">
                                        <td style="vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><?php echo db_get_product_name($serialized_products['products'][$i]); ?>
                                        </td>
                                        <td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;"> <?php echo $serialized_products['item_qty'][$i]; ?></td>
                                        <td style="vertical-align: top; text-align: center; border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo $serialized_products['item_price'][$i]; ?></td>
                                        <td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo $serialized_products['item_price'][$i]; ?></td>
                                    </tr>
                                    <?php
                                        $counter = $counter + 1;
                                    }
                                    ?>

                                    <tr>
                                        <td style="border-left: thin solid #000;"> </td>
                                        <th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">Subtotal</th>
                                        <td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo $invoiceData['sub_total']; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-left: thin solid #000;"> </td>
                                        <th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">Discount Amount</th>
                                        <td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo $invoiceData['total_discount']; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-left: thin solid #000;"> </td>
                                        <th style="vertical-align: top; background: #eeeeee; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;" colspan="2">Total</th>
                                        <td style="vertical-align: top; text-align: right; border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo $invoiceData['total']; ?></td>
                                    </tr>

                                    </tbody></table>

                            <table width="100%" style="margin-bottom: 10px">

                                <tr>
                                    <td style="vertical-align: top; background: #eeeeee; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;">US Dollars</td>
                                    <td style="font-size: 16px; vertical-align: top; text-align: left; border-collapse: collapse; border:thin solid #000; padding: 5px;"><?= ucwords(convert_number_to_words($invoiceData['total']))?> Only</td>
                                </tr>

                            </table>

                        </div>


                        <?php if(!empty($credit_notes)) { ?>
                            <?php foreach($credit_notes as $credit_note) { ?>
                                <div class="table-responsive">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <th colspan="5" style="vertical-align: top; background: #eeeeee; border-collapse: collapse; border:thin solid #000; padding: 5px; text-align: center;">Refund Note # <?php echo $credit_note['id']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">Dated</th>
                                                <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;"><?=reset_datetime(array( 'datetime' => $credit_note['refund_date'], '_this' => $this)); ?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">Amount</th>
                                                <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">$<?php echo number_format($credit_note['credit_amount'], 2); ?></td>
                                            </tr>
                                            <tr>
                                                <th style="width:25%; vertical-align: top; background: #eeeeee; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;">Notes</th>
                                                <td colspan="4" style="vertical-align: top; text-align: left;  border-collapse: collapse; border:thin solid #000; padding: 5px;"><?php echo $credit_note['notes']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr />
                            <?php } ?>
                        <?php } ?>
                    </div>
<!--                    <div class="row">-->
<!--                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
<!--                            Accounts Department,-->
<!--                        </div>-->
<!--                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
<!--                            The AutomotoHR Team-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <!-- Edit Invoice End -->
                </div>
            </div>          
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js" integrity="sha384-CchuzHs077vGtfhGYl9Qtc7Vx64rXBXdIAZIPbItbNyWIRTdG0oYAqki3Ry13Yzu" crossorigin="anonymous"></script>

<script type="text/javascript">
    function PrintElem(elem)
    {
        $(".bottom-buttons").hide();
        Popup($(elem).html());
        $(".bottom-buttons").show();
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'Print Invoice', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Invoice # <?php echo $invoiceData['sid'] ?></title>');
        /*optional stylesheet*/
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10


        return true;
    }


    $('#pdf').click(function () {

        var _data = $("#mydiv").html();
        var style = '<style> table{width:100%;}.Paid{color: #81b431;font-size: 16px;font-weight: 700;}.Unpaid{color: red;font-size: 16px;font-weight: 700;}.thead-bar{width:100%; background:#333;}.list-style-pdf{list-style:none;padding:0;}.list-style-pdf li label{font-weight:bold;}</style>';

        var final = style+_data;

        /********** Send HTML and Print PDF START ************/
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            var a;
            if (xhttp.readyState === 4 && xhttp.status === 200) {

//                // Trick for making downloadable link
                a = document.createElement('a');
                a.href = window.URL.createObjectURL(xhttp.response);
//                // Give filename you wish to download
                a.download = "Invoice.pdf";
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
            }
        };
        // Post data to URL which handles post request
        xhttp.open("POST", '<?= base_url() ?>' + 'get_pdf');
        xhttp.setRequestHeader("Content-Type", "application/json");
//        xhttp.setRequestHeader("X-CSRF-TOKEN", $('input[name="_token"]').val());
        // You should set responseType as blob for binary responses
        xhttp.responseType = 'blob';
        xhttp.send(final);
        /********** Send HTML and Print PDF END ************/
    });


</script>
