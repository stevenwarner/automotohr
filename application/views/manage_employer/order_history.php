<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back to Settings</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="table-responsive table-outer">
                        <strong id="invoiceCount">Placed Orders: <?= $invoiceCount ?></strong>
                        <div class="col-xs-12 col-sm-12 margin-top">
                            <div class="row"><?php echo $links;  ?></div>
                        </div>


							<!-- <table class="table table-bordered table-stripped table-hover">
								<thead>
									<tr>
										<th colspan="4" class="col-xs-4 text-center">Invoice Summary</th>
										<th rowspan="2" class="text-center" style="vertical-align:middle !important;">Actions</th>
									</tr>
									<tr>
										<th class="text-left">Description</th>
										<th class="col-xs-2 text-center">Payment Method</th>
										<th class="col-xs-2 text-center">Payment Status</th>
										<th class="col-xs-2 text-center">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($invoices as $invoice) { ?>
									<tr>
										<td>
											<div class="row">
												<div class="col-lg-12">
													<div class="dotted-border">
														<div class="row">
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Customer Name</strong></div>
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?= $invoice["username"] ?></div>
														</div>
													</div>
													<div class="dotted-border">
														<div class="row">
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Created Date</strong></div>
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?= date('m-d-Y', strtotime($invoice["date"])); ?></div>
														</div>
													</div>
													<div class="dotted-border">
														<div class="row">
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Payment Date</strong></div>
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?= convert_date_to_frontend_format($invoice["payment_date"], true); ?></div>
														</div>
													</div>
													<div class="dotted-border">
														<div class="row">
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><strong>Invoice #</strong></div>
															<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><?= $invoice["invoice_number"] ?></div>
														</div>
													</div>
												</div>
											</div>
											<p><strong>Items Summary</strong></p>
											<ul class="list-unstyled invoice-description-list">
							                    <?php foreach($invoice['product_details'] as $product_detail) { ?>
							                        <li class="invoice-description-list-item" ><?php echo ($product_detail[1]); ?></li>
							                    <?php } ?>
							                </ul>
										</td>
										<td class="text-center"><?= $invoice["payment_method"] ?></td>
										<td class="text-center <?= $invoice["status"] ?>"><?= $invoice["status"] ?></td>
										<td class="text-right">$<?= $invoice["total"] ?></td>
										<td class="col-xs-1 text-center">                    
											<a class="submit-btn" href="<?php echo base_url(); ?>order_detail/<?php echo $invoice['invoice_number']; ?> " title="View">View</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table> -->


                            <table class="table table-stripped table-hover table-bordered" id="example"  data-order='[[ 0, "desc" ]]'>
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer Name</th>
                                        <th class="col-xs-4">Description</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Payment Method</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice) { ?>
                                        <tr>
                                            <td><?= $invoice["invoice_number"] ?></td>
                                            <td><?= ucwords($invoice["first_name"]  . ' ' . $invoice["last_name"]); ?></td>
                                            <td>
                                                <p>Items Summary</p>
                                                <ul class="list-unstyled invoice-description-list">
                                                    <?php foreach($invoice['product_details'] as $product_detail) { ?>
                                                        <li class="invoice-description-list-item" ><?php echo ($product_detail[1]); ?></li>
                                                    <?php } ?>
                                                </ul>
                                            </td>
                                            <td><?= my_date_format($invoice["date"]); ?></td>
                                            <td><?= $invoice["payment_method"] ?></td>
                                            <td>$<?= $invoice["total"] ?></td>
                                            <td>
                                                <?php if(!empty($invoice['credit_notes'])) { ?>
                                                    <span class="text-warning">Refunded</span>
                                                <?php } else { ?>
                                                    <?php if(strtolower($invoice["status"]) == 'paid') { ?>
                                                        <span class="text-success"><?php echo $invoice["status"]; ?></span>
                                                    <?php } else { ?>
                                                        <span class="text-danger"><?php echo $invoice["status"]; ?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a class="submit-btn" href="<?php echo base_url(); ?>order_detail/<?php echo $invoice['invoice_number']; ?> " title="View">View</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                    </div>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row"><?php echo $links;  ?></div>
                    </div>

                    <div class="table-responsive table-outer  remaining-products">
                        <strong id="invoiceCount">Purchased Available Market Place Product(s)</strong>
                        <div class="product-detail-area table-wrp data-table">
                            <table class="table">
                                <thead>
                                    <tr>           
                                        <td>Product</td>
                                        <td width="60%">Name</td>
                                        <td class="text-align">Remaining Quantity</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $value) { ?>
                                        <tr>
                                            <td>
                                                <figure>
                                                    <?php if (!empty($value['product_image'])) { ?>
                                                        <img src="<?php echo AWS_S3_BUCKET_URL . $value['product_image']; ?>">
                                                    <?php } ?>
                                                </figure>
                                            </td>
                                            <td>
                                                <h3 class="details-title--polite"><?php echo $value['name']; ?></h3>
                                            </td>
                                            <td class="text-align"><?php echo $value['remaining_qty']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script>
//    $(document).ready(function () {
//        $('#example').DataTable({
//            paging: false,
//            info: false,
//            stateSave: true
//        });
//    });
</script>