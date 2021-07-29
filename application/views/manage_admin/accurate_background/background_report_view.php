<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php

if (isset($background_order['order_response']['orderStatus']['percentageCompleted'])) $background_order['order_response']['orderStatus']['percentageComplete'] = $background_order['order_response']['orderStatus']['percentageCompleted'];
// $background_order['package_response']['orderStatus']['status'] = 'completed';
//
if (isset($background_order['package_response']['orderStatus']['status']) && strtolower($background_order['package_response']['orderStatus']['status']) == 'completed') {
    $background_order['order_response']['orderStatus']['percentageComplete'] = 100;
}
// Needs to be removed
$background_order['report_url'] = isset($background_order['package_response']['orderStatus']['report_url']) ? $background_order['package_response']['orderStatus']['report_url'] : 'javascript:;' ;
// $background_order['product_brand'] = 'assurehire';
 
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <!--                                --><?php //$this->load->view('templates/_parts/admin_flash_message'); 
                                                                        ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/accurate_background') ?>"><i class="fa fa-long-arrow-left"></i> Background Check Orders</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered pull-left">Order Information</h1>
                                                    <div class="pull-right">
                                                        <button class="btn btn-danger jsRemoveBGC" data-id="<?=$background_order['sid'];?>">Delete Background Check</button>
                                                        <?php if ($background_order['package_id'] != null || $background_order['package_id'] != '') { ?>
                                                            <?php if ($background_order['product_brand'] == 'assurehire') { ?>
                                                                <a href="javascript:void(0)" class="btn btn-success js_view_report" data-url="<?= $background_order['report_url']; ?>">View Report </a>
                                                            <?php } else { ?>
                                                                <?php if (!empty($background_order['order_response'])) { ?>
                                                                    <?php $percent_complete = isset($background_order['order_response']['orderStatus']['percentageComplete']) ? $background_order['order_response']['orderStatus']['percentageComplete'] : ''; ?>
                                                                <?php } else { ?>
                                                                    <?php $percent_complete = 0; ?>
                                                                <?php } ?>
                                                                <?php if ($percent_complete <= 100) { ?>
                                                                    <span style="display: inline-block;">
                                                                        <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                                                                            <input type="hidden" name="perform_action" id="perform_action" value="get_order_status" />
                                                                            <input type="hidden" name="ab_order_sid" id="ab_order_sid" value="<?php echo $background_order['sid'] ?>" />
                                                                            <input type="hidden" name="users_type" id="users_type" value="<?php echo $background_order['users_type'] ?>" />
                                                                            <input type="hidden" name="users_sid" id="users_sid" value="<?php echo $background_order['users_sid'] ?>" />
                                                                            <input type="hidden" name="package_id" id="package_id" value="<?php echo $background_order['package_id'] ?>" />
                                                                            <?php if ($background_order['product_brand'] != 'assurehire') { ?>
                                                                                <button type="submit" class="btn btn-success">Refresh Status</button>
                                                                            <?php } ?>
                                                                        </form>
                                                                    </span>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-4">Company Name</th>
                                                                <td><?php echo ucwords(strtolower($background_order['company_info']['CompanyName'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Employer Name</th>
                                                                <td><?php echo $background_order['employer_info']['first_name'] . ' ' . $background_order['employer_info']['last_name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Candidate Name</th>
                                                                <td><?php echo $background_order['candidate_info']['first_name'] . ' ' . $background_order['candidate_info']['last_name']; ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th class="col-xs-4">Candidate Type</th>
                                                                <td><?php echo ucwords($background_order['users_type']); ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th class="col-xs-4">Order Date</th>
                                                                <td><?php echo convert_date_to_frontend_format($background_order['date_applied'], true); ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th class="col-xs-4">Product Name</th>
                                                                <td><?php echo $background_order['product_name']; ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th class="col-xs-4">Product Type</th>
                                                                <td><?php echo $background_order['product_type']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Product Brand</th>
                                                                <td><?php echo $background_order['product_brand'] != 'assurehire' ? 'Accurate' : 'AssureHire'; ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th class="col-xs-4">Product Price</th>
                                                                <td><?php echo $background_order['product_price']; ?></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered pull-left">Order Status</h1>
                                                    <div class="pull-right">
                                                        <?php if (!empty($background_order['order_response'])) { ?>
                                                            <?php $order_status = isset($background_order['order_response']['orderStatus']) ? $background_order['order_response']['orderStatus'] : ''; ?>
                                                            <?php if (isset($order_status['percentageComplete']) && $order_status['percentageComplete'] == 100) { // Get Report for Accurate Background 
                                                            ?>
                                                                <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                                                                    <input type="hidden" name="perform_action" id="perform_action" value="get_report" />
                                                                    <input type="hidden" name="ab_order_sid" id="ab_order_sid" value="<?php echo $background_order['sid']; ?>" />
                                                                    <input type="hidden" name="users_type" id="users_type" value="<?php echo $background_order['users_type']; ?>" />
                                                                    <input type="hidden" name="users_sid" id="users_sid" value="<?php echo $background_order['users_sid']; ?>" />
                                                                    <input type="hidden" name="package_id" id="package_id" value="<?php echo $background_order['package_id']; ?>" />
                                                                    <input type="hidden" name="search_id" id="search_id" value="<?php echo isset($background_order['order_response']['orderInfo']['searchId']) ?  $background_order['order_response']['orderInfo']['searchId'] :  $background_order['external_id']; ?>" />
                                                                    <?php if ($background_order['product_brand'] != 'assurehire') { ?>
                                                                        <button class="btn btn-success btn-sm pull-left" type="submit">Download Report</button>
                                                                    <?php } else { ?>
                                                                        <a href="javascript:void(0)" class="btn btn-success js_view_report" data-url="<?= $background_order['report_url']; ?>">View Report </a>
                                                                    <?php } ?>
                                                                </form>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="table-responsive hr-innerpadding">
                                                    <?php if (isset($background_order['package_response']['errors'])) { ?>
                                                        <h4>Order Errors:</h4>
                                                        <ul class="list-group">
                                                            <?php foreach ($background_order['package_response']['errors'] as $error) { ?>
                                                                <li class="list-group-item list-group-item-danger">
                                                                    <span><?php echo $error['message']; ?> ( <?php echo $error['code']; ?> )</span>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } else { ?>
                                                        <?php if (!empty($background_order['order_response'])) { ?>
                                                            <?php 
                                                                $package_id = '';
                                                                $search_id = '';
                                                                $order_status = isset($background_order['order_response']['orderStatus']) ? $background_order['order_response']['orderStatus'] : '';
                                                                // 
                                                                $order_response_order_info = isset($background_order['order_response']['orderInfo']) ? $background_order['order_response']['orderInfo'] : '';
                                                                //
                                                                $package_response_order_info = isset($background_order['package_response']['orderInfo']) ? $background_order['package_response']['orderInfo'] : '';
                                                                //
                                                                if ($order_response_order_info != '') {
                                                                    $package_id = $order_response_order_info['packageId']; 
                                                                    $search_id = isset($order_response_order_info['searchId']) ? $order_response_order_info['searchId'] : $background_order['external_id']; 
                                                                } else if ($package_response_order_info != '') { 
                                                                    $package_id = $package_response_order_info['packageId']; 
                                                                    $search_id = isset($package_response_order_info['searchId']) ? $package_response_order_info['searchId'] : $background_order['external_id'];
                                                                } else {
                                                                    $package_id = isset($background_order['package_id']) ? $background_order['package_id'] : '';
                                                                }
                                                            ?>
                                                            <table class="table table-bordered table-striped table-hover">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="col-xs-4">Accu. Background Search Id</th>
                                                                        <!-- <td><?php //echo isset($order_info['order_status']) ? $order_info['searchId'] : $background_order['external_id']; ?></td> -->
                                                                        <td><?php echo $search_id; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="col-xs-4">Accu. Background Package Id</th>
                                                                        <!-- <td><?php //echo $order_info['packageId'] ?></td> -->
                                                                        <td><?php echo $package_id; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="col-xs-4">Status</th>
                                                                        <td>
                                                                            <?php $order_current_status = isset($order_status['status']) ? $order_status['status'] : ''; ?>
                                                                            <?php echo (strtolower($order_current_status) == 'draft' ? 'Awaiting Candidate Input' : ($order_current_status == '' || $order_current_status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $order_current_status))); ?>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="col-xs-4">Result</th>
                                                                        <td><?php echo isset($order_status['result']) ? $order_status['result'] : ''; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="col-xs-4">Percent Complete</th>
                                                                        <td>
                                                                            <div class="progress">
                                                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo isset($order_status['percentageComplete']) ? $order_status['percentageComplete'] : 0; ?>%">
                                                                                    <?php echo isset($order_status['percentageComplete']) ? $order_status['percentageComplete'] : 0; ?>% Complete
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="col-xs-4">Date Completed</th>
                                                                        <td><?php echo isset($order_status['completedDate']) ? $order_status['completedDate'] : ''; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="col-xs-4">Notes</th>
                                                                        <td><?php echo isset($order_status['notes']) ? $order_status['notes'] : ''; ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        <?php } else { ?>
                                                            <div class="no-data">No Status Found</div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($background_order['product_brand'] != 'assurehire') { ?>
                                        <?php if (!empty($background_order['order_response'])) { ?>
                                            <?php if (!empty($background_order['order_response']['orderStatus'])) { ?>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <h1 class="hr-registered pull-left">Order Summary</h1>
                                                            </div>

                                                            <div class="table-responsive hr-innerpadding">
                                                                <table class="table table-bordered table-striped table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-4">Search</th>
                                                                            <th class="col-xs-3">Status</th>
                                                                            <th class="col-xs-3">Result</th>
                                                                            <th class="col-xs-2">Flagged</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (!empty($background_order['order_response']['orderStatus']['summary'])) { ?>
                                                                            <?php $summary_items = $background_order['order_response']['orderStatus']['summary']; ?>
                                                                            <?php foreach ($summary_items as $summary_item) { ?>
                                                                                <tr>
                                                                                    <td><?php echo ucwords($summary_item['search']); ?></td>
                                                                                    <td>
                                                                                        <?php $status = trim(strtolower($summary_item['status']));
                                                                                        $pos = strpos($status, 'draft');

                                                                                        if ($pos === false) {
                                                                                            echo ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status));
                                                                                        } else {
                                                                                            echo 'Awaiting Candidate Input';
                                                                                        }
                                                                                        //                                                                                echo $status == 'draft' ? 'Awaiting Candidate Input' : ($status == '' || $status == NULL) ? 'Pending' : ucwords(str_replace('_', ' ', $status)); 
                                                                                        ?>
                                                                                    </td>
                                                                                    <td><?php echo ucwords($summary_item['result']); ?></td>
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

    //
    $(document).on('click', '.jsRemoveBGC', function(event){
            //
            event.preventDefault();
            //
            var Id = $(this).data('id');
            //
            alertify.confirm(
                "Do you want to delete this background check?<br>This action is not revertable.", 
                function(){
                    //
                    $('.js-loader .cs-loader-text').text('Please wait, while we are deleting the selected background check.');
                    $('.js-loader').show();
                    //
                    $.post(
                        "<?=base_url("manage_admin/accurate_background/remove_background_check");?>",
                        {
                            id: Id
                        }
                    ).done(function(resp){
                        //
                        $('.js-loader').hide();
                        //
                        $('.js-loader .cs-loader-text').text('Please wait, while we are fetching more results.');
                        //
                        if(resp.MSG == 'Success'){
                            alertify.alert("You have successfully deleted the background check.", function(){
                                window.location = 'manage_admin/accurate_background';
                            });
                        }else {
                            alertify.alert("Something went wrong while deleting the background check.");
                        }
                    }).error(function(err){
                        //
                        $('.js-loader').hide();
                        //
                        $('.js-loader .cs-loader-text').text('Please wait, while we are fetching more results.');
                        //
                        alertify.alert("Error!", "Something went wrong while deleting the backgrond check.<br/> Status Code: "+(err.status)+"<br> Error: "+(err.statusText)+"");
                    });
                }
            );
        });
</script>