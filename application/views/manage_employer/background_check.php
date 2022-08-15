<?php
$hasAccess = checkIfAppIsEnabled(ASSUREHIRE_SLUG, false);
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="top-search-area">
                                <div class="tagline-area item-title">
                                    <h4><em>Run Background check against: </em>:&nbsp;<span style="color:#00a700;"><?php echo $employer['first_name'] ?> <?php echo $employer['last_name'] ?></span><br /><em>Target the right audience</em></h4>
                                </div>
                            </div>
                            <div class="multistep-progress-form">
                                <?php if (!empty($appliedProducts)) { ?>
                                    <fieldset id="advertise_div">
                                        <div class="produt-block">
                                            <header class="form-col-100">
                                                <h2 class="section-title">Already Performed Check(s)</h2>
                                            </header>
                                            <?php foreach ($appliedProducts as $product) {
                                                // Needs to be removed
                                                //$product['product_brand'] = "assurehire";
                                                $product['report_url'] = isset($product['package_response']['orderStatus']['report_url']) ? $product['package_response']['orderStatus']['report_url'] : 'javascript:;';
                                                if (!$hasAccess && $product['product_brand'] == 'assurehire') continue;

                                                if ($product['product_brand'] == 'assurehire') {

                                                    $product['order_response']['orderStatus']['percentageComplete'] = $product['order_response']['orderStatus']['percentageCompleted'];
                                                    if (strtolower($product['order_response']['orderStatus']['status']) == 'completed') {
                                                        $product['order_response']['orderStatus']['percentageComplete'] = 100;
                                                    }
                                                }
                                            ?>
                                                <div class="accurate-background-box">
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <article class="accurate-background-box">
                                                                <h2 class="post-title"><?php echo $product['product_name']; ?></h2>
                                                                <figure><img src="<?php echo AWS_S3_BUCKET_URL;
                                                                                    if ($product['product_image'] != NULL) {
                                                                                        echo $product['product_image'];
                                                                                    } else {
                                                                                    ?>default_pic-ySWxT.jpg<?php } ?>" alt="Category images"></figure>

                                                                <?php if ($product['package_id'] != null || $product['package_id'] != '') { ?>
                                                                    <?php if (!empty($product['order_response'])) { ?>
                                                                        <?php $percent_complete = isset($product['order_response']['orderStatus']['percentageComplete']) ? $product['order_response']['orderStatus']['percentageComplete'] : 0; ?>
                                                                    <?php } else { ?>
                                                                        <?php $percent_complete = 0; ?>
                                                                    <?php } ?>
                                                                    <?php if ($percent_complete < 100 && !isset($product['is_deleted_status'])) { ?>
                                                                        <span style="display: inline-block;">
                                                                            <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="perform_action" id="perform_action" value="get_order_status" />
                                                                                <input type="hidden" name="ab_order_sid" id="ab_order_sid" value="<?php echo $product['sid'] ?>" />
                                                                                <input type="hidden" name="users_type" id="users_type" value="<?php echo $product['users_type'] ?>" />
                                                                                <input type="hidden" name="users_sid" id="users_sid" value="<?php echo $product['users_sid'] ?>" />
                                                                                <input type="hidden" name="package_id" id="package_id" value="<?php echo $product['package_id'] ?>" />
                                                                                <?php if ($product['product_brand'] != 'assurehire') { ?>
                                                                                    <button type="submit" class="btn btn-success">Refresh Status</button>
                                                                                <?php } ?>
                                                                            </form>
                                                                        </span>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </article>
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <?php if (!empty($product['order_response']) && !isset($product['package_response']['errors'])) { ?>
                                                                <?php $percent_complete = isset($product['order_response']['orderStatus']['percentageComplete']) ? $product['order_response']['orderStatus']['percentageComplete'] : 0 ; ?>
                                                                <div class="accurate-background-box">
                                                                    <h2 class="post-title text-left">Current Saved Status
                                                                        <div class="pull-right">
                                                                            <?php if ($product['product_brand'] == 'assurehire') { ?>
                                                                                <a href="javascript:void(0)" class="btn btn-success js_view_report" data-url="<?= $product['report_url']; ?>">View Report </a>
                                                                            <?php } else { ?>
                                                                                <?php if ($percent_complete == 100) { // Get Report for Accurate Background 
                                                                                ?>
                                                                                    <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="perform_action" id="perform_action" value="get_report" />
                                                                                        <input type="hidden" name="ab_order_sid" id="ab_order_sid" value="<?php echo $product['sid']; ?>" />
                                                                                        <input type="hidden" name="users_type" id="users_type" value="<?php echo $product['users_type']; ?>" />
                                                                                        <input type="hidden" name="users_sid" id="users_sid" value="<?php echo $product['users_sid']; ?>" />
                                                                                        <input type="hidden" name="package_id" id="package_id" value="<?php echo $product['package_id']; ?>" />
                                                                                        <input type="hidden" name="search_id" id="search_id" value="<?php echo (isset($product['order_response']['orderInfo']['searchId'])) ? $product['order_response']['orderInfo']['searchId'] : $product['external_id']; ?>" />
                                                                                        <button class="btn btn-success btn-sm pull-left" type="submit">Download Report</button>
                                                                                    </form>
                                                                                <?php   } else if ($percent_complete > 1 && $percent_complete < 100) { ?>
                                                                                    <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="perform_action" id="perform_action" value="get_report" />
                                                                                        <input type="hidden" name="ab_order_sid" id="ab_order_sid" value="<?php echo $product['sid']; ?>" />
                                                                                        <input type="hidden" name="users_type" id="users_type" value="<?php echo $product['users_type']; ?>" />
                                                                                        <input type="hidden" name="users_sid" id="users_sid" value="<?php echo $product['users_sid']; ?>" />
                                                                                        <input type="hidden" name="package_id" id="package_id" value="<?php echo $product['package_id']; ?>" />
                                                                                        <input type="hidden" name="search_id" id="search_id" value="<?php echo (isset($product['order_response']['orderInfo']['searchId'])) ? $product['order_response']['orderInfo']['searchId'] : $product['external_id']; ?>" />
                                                                                        <button class="btn btn-warning btn-sm pull-left" type="submit">Download Partial Report</button>
                                                                                    </form>
                                                                                <?php   } ?>
                                                                            <?php }  ?>
                                                                        </div>
                                                                    </h2>
                                                                    <table class="table table-bordered table-striped table-hover">
                                                                        <tbody>
                                                                            <tr>
                                                                                <?php 
                                                                                    $current_status = isset($product['order_response']['orderStatus']['status']) ? $product['order_response']['orderStatus']['status'] : '';
                                                                                    $status = strtolower($current_status);
                                                                                ?>
                                                                                <tr>
                                                                                    <?php
                                                                                        $package_id = '';
                                                                                        $search_id = '';
                                                                                        $order_status = isset($product['order_response']['orderStatus']) ? $product['order_response']['orderStatus'] : '';
                                                                                        // 
                                                                                        $order_response_order_info = isset($product['order_response']['orderInfo']) ? $product['order_response']['orderInfo'] : '';
                                                                                        //
                                                                                        $package_response_order_info = isset($product['package_response']['orderInfo']) ? $product['package_response']['orderInfo'] : '';
                                                                                        //
                                                                                        if ($order_response_order_info != '') {
                                                                                            $package_id = $order_response_order_info['packageId']; 
                                                                                            $search_id = isset($order_response_order_info['searchId']) ? $order_response_order_info['searchId'] : $product['external_id']; 
                                                                                        } else if ($package_response_order_info != '') { 
                                                                                            $package_id = $package_response_order_info['packageId']; 
                                                                                            $search_id = isset($package_response_order_info['searchId']) ? $package_response_order_info['searchId'] : $product['external_id'];
                                                                                        } else {
                                                                                            $package_id = isset($product['package_id']) ? $product['package_id'] : '';
                                                                                        }
                                                                                    ?>
                                                                                    <th class="col-xs-4">Accu. Background Search Id</th>
                                                                                    <td class="text-left"><?php echo $search_id; ?></td>
                                                                                </tr>
                                                                                <th class="col-xs-4">Status</th>
                                                                                <td class="text-left">
                                                                                    <?php
                                                                                        //
                                                                                        if(isset($product['is_deleted_status'])){
                                                                                            ?>
                                                                                            Canceled & Credited
                                                                                            <?php
                                                                                        } else{
                                                                                            ?>
                                                                                            <?php $pos = strpos($status, 'draft');

                                                                                            if (strpos($status, 'draft') === false || ($product['product_brand'] == 'assurehire' && strpos($status, 'pending') === TRUE)) {
                                                                                                echo ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status));
                                                                                            } else {
                                                                                                echo 'Awaiting Candidate Input';
                                                                                            }
                                                                                            //                                                                                        echo (strtolower($status) == 'draft' ? 'Awaiting Candidate Input' : ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status))); 
                                                                                            ?>
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-4">Result</th>
                                                                                <td class="text-left"><?php echo isset($product['order_response']['orderStatus']['result']) ? $product['order_response']['orderStatus']['result'] : ''; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-4">Completed Date</th>
                                                                                <td class="text-left"><?php echo isset($product['order_response']['orderStatus']['completedDate']) ? $product['order_response']['orderStatus']['completedDate'] : ''; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-4">Percentage Complete</th>
                                                                                <td class="text-left">
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percent_complete; ?>%">
                                                                                            <?php echo $percent_complete; ?>% Complete
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="col-xs-4">Notes</th>
                                                                                <td class="text-left"><?php echo isset($product['order_response']['orderStatus']['notes']) ? $product['order_response']['orderStatus']['notes'] : ''; ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } else { ?>
                                                                <?php if (isset($product['package_response']['errors'])) { ?>
                                                                    <div class="accurate-background-box">
                                                                        <div class="alert alert-danger text-center">
                                                                            <p>Something went wrong while processing your request</p>
                                                                            <p>Kindly contact AutomotoHR Support!</p>
                                                                        </div>
                                                                        <div class="alert alert-success text-center">
                                                                            <p>We did not charge you for this background check.</p>
                                                                        </div>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="accurate-background-box">
                                                                        <div class="no-data">Status Not Found!</div>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($product['order_response'])) { ?>
                                                        <?php if (!empty($product['order_response']['orderStatus']) && $product['product_brand'] != "assurehire") { ?>
                                                            <div class="accurate-background-box">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <h2 class="post-title text-left">Report Summary</h2>
                                                                        <br />
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-striped table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-6">Search</th>
                                                                                        <th class="col-xs-2">Status</th>
                                                                                        <th class="col-xs-2">Result</th>
                                                                                        <th class="col-xs-2">Flagged</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php if (!empty($product['order_response']['orderStatus']['summary'])) { ?>
                                                                                        <?php $summary_items = $product['order_response']['orderStatus']['summary']; ?>
                                                                                        <?php foreach ($summary_items as $summary_item) { ?>
                                                                                            <tr>
                                                                                                <td><?php echo ucwords($summary_item['search']); ?></td>
                                                                                                <td>
                                                                                                    <?php $status = strtolower($summary_item['status']); ?>
                                                                                                    <?php echo (strtolower($status) == 'draft' ? 'Awaiting Candidate Input' : ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status))); ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php $status = strtolower($summary_item['result']); ?>
                                                                                                    <?php echo (strtolower($status) == 'draft' ? 'Awaiting Candidate Input' : ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status))); ?>
                                                                                                </td>
                                                                                                <td><?php echo ucwords($summary_item['flagged']); ?></td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td colspan="4"><span class="no-data">No Results Yet</span></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </fieldset>
                                <?php } ?>

                                <form id="run_product_form" class="msform" action="" method="POST">
                                    <input type="hidden" name="perform_action" id="perfrom_action" value="place_background_check_order" />
                                    <fieldset id="advertise_div">
                                        <div class="produt-block">
                                            <header class="form-col-100">
                                                <h2 class="section-title">Purchased Products</h2>
                                            </header>
                                            <div class="pre-purchased-products advertising-boxes">
                                                <?php if (!empty($purchasedProducts)) { ?>
                                                    <?php foreach ($purchasedProducts as $product) { ?>
                                                        <?php if (!$hasAccess && $product['product_brand'] == 'assurehire') continue; ?>
                                                        <?php if ($product['remaining_qty'] > 0) { ?>
                                                            <article class="purchased-product">
                                                                <input type="hidden" id="already_applied_<?php echo $product['product_sid'] ?>" value="<?php echo $product['appliedOn']; ?>" />
                                                                <input class="product-checkbox" name="productId" id="productId" value="<?php echo $product['product_sid']; ?>" type="radio">
                                                                <p class="remaining-qty">Remaining Qty: <?php echo $product['remaining_qty']; ?></p>
                                                                <h2 class="post-title"><?php echo $product['name']; ?></h2>
                                                                <figure>
                                                                    <img src="<?php echo !is_null($product['product_image']) ? AWS_S3_BUCKET_URL . $product['product_image'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg'; ?>" alt="Category images">
                                                                </figure>
                                                                <?php if ($product['appliedOn'] == 'true') { ?>
                                                                    <div class="already-incart disabled-products"><i class="fa fa-check-circle"></i>Done</div>
                                                                <?php } ?>
                                                            </article>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="tagline-area">
                                                        <h4><span style="color:#00a700;">No purchased product avaliable.</span></h4>
                                                    </div>
                                                <?php } ?>
                                                <?php echo form_error('productId[]'); ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($purchasedProducts)) { ?>
                                            <!--<input type="submit"  class="submit-btn" value="Run selected check(s)" />-->
                                            <button type="button" class="btn btn-success" onclick="func_apply_product();">Run Selected Check</button>
                                            <input type="button" value="Cancel" class="btn black-btn btn-cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'" />
                                        <?php } ?>
                                        <input type="button" value="Purchase Products" class="btn btn-success" onClick="document.location.href = '<?php echo base_url($market_place_url); ?>'" />
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<!-- Background Check loader -->
<div id="background_check_loader" class="text-center" style="display: none; position:fixed; top: 0; bottom: 0; left: 0; right: 0; width: 100%;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait, while we are processing your request. <br>This may take a few seconds.
        </div>
    </div>
</div>

<script>
    function func_apply_product() {
        var product_id = $('#productId:checked').val();

        var is_already_applied = $('#already_applied_' + product_id).val();

        if (parseInt(product_id) > 0) {
            if (is_already_applied === 'true') {
                alertify.confirm(
                    'Please confirm?',
                    'Please note that Background check is already performed in the past. Do you want to re-run this Product?',
                    function() {
                        $('#background_check_loader').show(); 
                        $('#run_product_form').submit();
                    },
                    function() {
                        // alertify.alert('Canceled!').set('title', 'NOTICE!');
                    }).set('label', {
                        ok: "YES",
                        cancel: "NO"
                    });
            } else {
                $('#background_check_loader').show(); 
                $('#run_product_form').submit();
            }
        } else {
            alertify.alert('Please Select a Product!');
        }
    }
</script>

<?php $this->load->view('iframeLoader'); ?>

<script>
    $('.js_view_report').click(function(e) {
        //
        e.preventDefault();
        //
        var url = $(this).data('url');
        //
        var rows = '';
        rows += `
        <div class="modal fade" id="js-report-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header header-success">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Background Report</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h4>Use the below details to login.</h4>
                                <p><strong>Username:</strong> <?php echo ASSUREHIR_USR ?></p>    
                                <p><strong>Password:</strong> <?php echo ASSUREHIR_PWD ?></p>    
                            </div>
                        </div>
                        
                        
                        <iframe id="js-report-iframe" style="width: 100%; height: 500px;" frameBorder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        //
        $('#js-report-modal').remove();
        $('body').append(rows);
        $('#js-report-modal').modal(true);
        //
        loadIframe(url, '#js-report-iframe', true);
        console.log(url);
    });
</script>