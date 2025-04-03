<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Marketplace Invoices
                                        </h1>
                                    </div>
                                    <?php if (in_array('full_access', $security_details) || in_array('add_new_invoice', $security_details)) { ?>
                                        <!--<div class="add-new-promotions">
                                            <a class="site-btn" href="<?php /*echo base_url('manage_admin/invoice/add_new_invoice'); */ ?>">Add New Invoice</a>
                                        </div>-->
                                    <?php } ?>
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <!-- Search Table Start -->
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <form>
                                            <ul>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Date From</label>
                                                    <?php $start_date = $this->uri->segment(4); ?>
                                                    <input type="text" name="start"
                                                        value="<?= empty($start_date) || $start_date == 'all' ? "" : $start_date; ?>"
                                                        class="invoice-fields" id="startdate" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Date To</label>
                                                    <?php $end_date = $this->uri->segment(5); ?>
                                                    <input type="text" name="end"
                                                        value="<?= empty($end_date) || $end_date == 'all' ? "" : $end_date; ?>"
                                                        class="invoice-fields" id="enddate" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Customer Name</label>
                                                    <?php $name = urldecode($this->uri->segment(6)); ?>
                                                    <input type="text" name="username"
                                                        value="<?= empty($name) || $name == 'all' ? "" : $name; ?>"
                                                        id="username" class="invoice-fields">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Invoice#</label>
                                                    <?php $sid = $this->uri->segment(7); ?>
                                                    <input type="text" name="sid"
                                                        value="<?= empty($sid) || $sid == 'all' ? "" : $sid; ?>"
                                                        id="inv_sid" class="invoice-fields">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Payment Method</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="payment_method"
                                                            id="payment_method">
                                                            <option value="">Any Payment method</option>
                                                            <?php $method = $this->uri->segment(8); ?>
                                                            <option value="background_check_refund" <?php if (isset($method) && $method == 'background_check_refund') { ?> selected="selected" <?php } ?>>Background Check
                                                                Refund</option>
                                                            <option value="Free_checkout" <?php if (isset($method) && $method == 'Free_checkout') { ?> selected="selected" <?php } ?>>Free Checkout</option>
                                                            <option value="Paypal" <?php if (isset($method) && $method == 'Paypal') { ?> selected="selected" <?php } ?>>
                                                                Paypal Standard</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Status</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="status" id="status">
                                                            <option value="">Any Invoice Status</option>
                                                            <?php $status = $this->uri->segment(9); ?>
                                                            <option value="Paid" <?php if (isset($status) && $status == 'Paid') { ?> selected="selected" <?php } ?>>
                                                                Paid</option>
                                                            <option value="Unpaid" <?php if (isset($status) && $status == 'Unpaid') { ?> selected="selected" <?php } ?>>
                                                                Unpaid</option>
                                                            <option value="Pending" <?php if (isset($status) && $status == 'Pending') { ?> selected="selected" <?php } ?>>
                                                                Pending</option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Company Name</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="company" id="jscompany">
                                                            <option value="all">All</option>
                                                            <?php if (!empty($companies)) {
                                                                foreach ($companies as $company) {
                                                                    ?>
                                                                    <option value="<?php echo $company['sid']; ?>" <?php if (isset($companyid) && $companyid == $company['sid']) { ?>
                                                                            selected="selected" <?php } ?>>
                                                                        <?php echo $company['CompanyName']; ?>
                                                                    </option>


                                                                <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 text-right field-row field-row-autoheight">
                                                    <a href="javascript:;" id="search"
                                                        class="btn btn-success">Search</a>
                                                    <a href="<?php echo base_url('manage_admin/invoice'); ?>"
                                                        class="btn btn-success">Clear</a>
                                                </div>
                                            </ul>
                                        </form>
                                    </div>
                                    <!-- Search Table End -->
                                    <!-- Search Result table Start -->
                                    <form name="users_form" method="post">
                                        <div class="hr-box">
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to
                                                                <?php echo $to_records; ?> out of
                                                                <?php echo $applicants_count ?>
                                                            </p>
                                                        </span>
                                                        <span class="pull-right">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <!--<th><input type="checkbox"></th>-->
                                                            <th>Invoice #</th>
                                                            <th>Customer Name</th>
                                                            <th>Company Name</th>
                                                            <th>Date</th>
                                                            <th>Payment Method</th>
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Status</th>
                                                            <?php $function_names = array('edit_invoice', 'mark_paid_unpaid', 'resend_invoice', 'delete_invoice'); ?>
                                                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                <th width="1%" colspan="4" class="text-center">Actions</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($invoices as $invoice) { ?>
                                                            <tr id="parent_<?= $invoice["invoice_number"] ?>">
                                                                <!--<td><input type="checkbox" ></td>-->
                                                                <td><?= $invoice["invoice_number"] ?></td>
                                                                <td><?= $invoice["username"] ?></td>
                                                                <td><?= $invoice["CompanyName"] ?></td>
                                                                <td><?= date_with_time($invoice["date"]); ?></td>
                                                                <td><?= $invoice["payment_method"] ?></td>
                                                                <td class="text-center">$<?= $invoice["total"] ?></td>
                                                                <td class="text-center">
                                                                    <?php if ($invoice['has_refund_notes'] == 1) { ?>
                                                                        <span class="text-warning">Refunded</span>
                                                                    <?php } else { ?>
                                                                        <span
                                                                            class="<?= $invoice["status"] ?>"><?= $invoice["status"] ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if (check_access_permissions_for_view($security_details, 'edit_invoice')) { ?>
                                                                    <td><a class="btn btn-success btn-sm"
                                                                            href="<?php echo base_url(); ?>manage_admin/invoice/edit_invoice/<?php echo $invoice['invoice_number']; ?> "
                                                                            title="View/Edit"><i class="fa fa-pencil"></i></a>
                                                                    <?php } ?>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'delete_invoice')) { ?>
                                                                    <td>
                                                                        <a href="javascipt:;" class="btn btn-danger btn-sm"
                                                                            title="Delete"
                                                                            id="<?= $invoice['invoice_number'] ?>"
                                                                            onclick="return todo('delete', this.id)"><i
                                                                                class="fa fa-times"></i></a>
                                                                        <!--                                                                        <input type="button" class="btn btn-danger btn-block" value="Delete" id="<?= $invoice['invoice_number'] ?>" onclick="return todo('delete', this.id)">-->
                                                                    </td>
                                                                <?php } ?>
                                                                <?php if (check_access_permissions_for_view($security_details, 'mark_paid_unpaid')) { ?>
                                                                    <?php if ($invoice["status"] == "Unpaid") { ?>
                                                                        <td><input type="button" class="btn btn-success btn-block"
                                                                                value="Mark Paid"
                                                                                id="<?= $invoice['invoice_number'] ?>"
                                                                                onclick="return todo('mark paid', this.id)"></td>
                                                                    <?php } else { ?>
                                                                        <td><input type="button" class="btn btn-success btn-block"
                                                                                value="Mark Unpaid"
                                                                                id="<?= $invoice['invoice_number'] ?>"
                                                                                onclick="return todo('mark unpaid', this.id)"></td>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php if (check_access_permissions_for_view($security_details, 'resend_invoice')) { ?>
                                                                    <!--                                                                    <td><input type="button" class="btn btn-success btn-block" value="Resend" id="--><? //= $invoice['invoice_number'] 
                                                                            ?>
                                                                    <!--" onclick="return todo('resend', this.id)" ></td>-->
                                                                <?php } ?>
                                                            </tr>
                                                        <?php }

                                                        if (count($email_invoices) > 0) { ?>
                                                            <tr>
                                                                <th colspan="10">Emailed Invoices</th>
                                                            </tr>
                                                            <?php foreach ($email_invoices as $invoice) { ?>
                                                                <tr id="parent_<?= $invoice["invoice_number"] ?>">
                                                                    <!--<td><input type="checkbox" ></td>-->
                                                                    <td><?= $invoice["invoice_number"] ?></td>
                                                                    <td><?= $invoice["to_name"] ?></td>
                                                                    <td><?= $invoice["date"] ?></td>
                                                                    <td><?= $invoice["payment_method"] ?></td>
                                                                    <td>$<?= $invoice["total"] ?></td>
                                                                    <td><?= $invoice["status"] ?></td>
                                                                    <?php if ($invoice["status"] == "Unpaid") { ?>
                                                                        <td><input type="button" class="btn btn-success"
                                                                                value="Mark Paid"
                                                                                id="<?= $invoice['invoice_number'] ?>"
                                                                                onclick="return todo('mark paid', this.id)"></td>
                                                                    <?php } else { ?>
                                                                        <td><input type="button" class="btn btn-success"
                                                                                value="Mark Unpaid"
                                                                                id="<?= $invoice['invoice_number'] ?>"
                                                                                onclick="return todo('mark unpaid', this.id)"></td>
                                                                    <?php } ?>
                                                                    <td><input type="button" class="btn btn-success"
                                                                            value="Resend"
                                                                            id="<?= $invoice['invoice_number'] ?>"
                                                                            onclick="return todo('resend', this.id)"></td>
                                                                    <td><a class="hr-edit-btn"
                                                                            href="<?php base_url(); ?>invoice/edit_invoice/<?php echo $invoice['invoice_number']; ?> "
                                                                            title="Edit">View/Edit</a>
                                                                    <td><input type="button" class="btn btn-danger"
                                                                            value="Delete"
                                                                            id="<?= $invoice['invoice_number'] ?>"
                                                                            onclick="return todo('delete', this.id)"></td>
                                                                </tr>
                                                            <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Search Result Table End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/invoice/invoice_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Invoice?",
            function () {
                $.post(url, {
                    action: action,
                    sid: id
                })
                    .done(function (data) {

                        if (action == "delete") {
                            alertify.success('Selected promotion have been ' + action + 'd.');
                            invoiceCounter = parseInt($("#invoiceCount").html());
                            invoiceCounter--;
                            $("#invoiceCount").html(invoiceCounter);
                            $("#parent_" + id).remove();
                        } else {
                            location.reload();
                        }
                    });

            },
            function () {
                alertify.error('Canceled');
            });
    }

    function generate_search_url() {
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
        var username = $('#username').val();
        var inv_sid = $('#inv_sid').val();
        var payment_method = $('#payment_method').val();
        var status = $('#status').val();
        var company = $('#jscompany').val();


        startdate = startdate != '' && startdate != null && startdate != undefined && startdate != 0 ? encodeURIComponent(startdate) : 'all';
        enddate = enddate != '' && enddate != null && enddate != undefined && enddate != 0 ? encodeURIComponent(enddate) : 'all';
        username = username != '' && username != null && username != undefined && username != 0 ? encodeURIComponent(username) : 'all';
        inv_sid = inv_sid != '' && inv_sid != null && inv_sid != undefined && inv_sid != 0 ? encodeURIComponent(inv_sid) : 'all';
        payment_method = payment_method != '' && payment_method != null && payment_method != undefined && payment_method != 0 ? encodeURIComponent(payment_method) : 'all';
        status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';

        company = company != '' && company != null && company != undefined && company != 0 ? encodeURIComponent(company) : 'all';



        var url = '<?php echo base_url('manage_admin/invoice/index'); ?>' + '/' + startdate + '/' + enddate + '/' + username + '/' + inv_sid + '/' + payment_method + '/' + status + '/' + company + '/';

        $('#search').attr('href', url);
    }

    $('select').on('change', function () {
        generate_search_url();
    });

    $('.datepicker').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();

    $('#startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        onSelect: function (value) { //console.log(value);
            $('#enddate').datepicker('option', 'minDate', value);
            generate_search_url();
        }
    }).datepicker('option', 'maxDate', $('#enddate').val());

    $('#enddate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>",
        onSelect: function (value) { //console.log(value);
            $('#startdate').datepicker('option', 'maxDate', value);
            generate_search_url();
        }
    }).datepicker('option', 'minDate', $('#startdate').val());

    $(document).on('click', '#search', function (e) {
        e.preventDefault();
        generate_search_url();
        window.location = $(this).attr('href').toString();
    });
</script>