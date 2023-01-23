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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="top-search-area">
                                <div class="tagline-area item-title">
                                    <h4>
                                        <em>Product Name: </em>:&nbsp;<span style="color:#00a700;"><?php echo $backgorund_order['product_name'] ?></span>
                                        <br/>
                                        <em>User Name: </em>:&nbsp;<span style="color:#00a700;"><?php echo $employer['first_name'] ?> <?php echo $employer['last_name'] ?></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="multistep-progress-form">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <fieldset id = "advertise_div">
                                    <div class = "produt-block">
                                        <div class="tagline-area item-title">
                                            <div class="background-reports">
                                                <div class="div-table">
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Convicted</div>
                                                        <div class="div-table-col"><?php echo $backgorund_order['order_response']->orderInfo->convicted; ?></div>
                                                    </div>
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Conviction Details</div>
                                                        <div class="div-table-col"><?php if ($backgorund_order['order_response']->orderInfo->convictionDetails == "") { ?>
                                                                N/A
                                                                <?php
                                                            } else {
                                                                echo $backgorund_order['order_response']->orderInfo->convictionDetails;
                                                            }
                                                            ?></div>
                                                    </div>
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Order Status</div>
                                                        <div class="div-table-col"><?php echo $backgorund_order['order_response_status']; ?></div>
                                                    </div>
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Percentage Complete</div>
                                                        <div class="div-table-col">
                                                            <?php if ($backgorund_order['order_response']->orderStatus->percentageComplete == "") { ?>
                                                                N/A
                                                                <?php
                                                            } else {
                                                                echo $backgorund_order['order_response']->orderStatus->percentageComplete;
                                                            }
                                                            ?>%</div>
                                                    </div>
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Completed Date</div>
                                                        <div class="div-table-col">
                                                            <?php if ($backgorund_order['order_response']->orderStatus->completedDate == "") { ?>
                                                                N/A
                                                                <?php
                                                            } else {
                                                                echo $backgorund_order['order_response']->orderStatus->completedDate;
                                                            }
                                                            ?></div>
                                                    </div>
                                                    <div class="div-table-row">
                                                        <div class="div-table-col">Summary</div>
                                                        <div class="div-table-col">
                                                            <?php if (empty($backgorund_order['order_response']->orderStatus->summary)) { ?>
                                                                N/A
                                                                <?php
                                                            } else {
                                                                print_r($backgorund_order['order_response']->orderStatus->summary);
                                                            }
                                                            ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <h4>
                                                <span style="color:#00a700;">Convicted:&nbsp;</span><em><?php echo $backgorund_order['order_response']->orderInfo->convicted; ?></em>
                                                <br/> <span style="color:#00a700;">Conviction Details:&nbsp;</span><em><?php echo $backgorund_order['order_response']->orderInfo->convictionDetails; ?></em>
                                                <br/>                                                <br/>

                                                <span style="color:#00a700;">Order Status:&nbsp;</span><em><?php echo $backgorund_order['order_response_status']; ?></em>
                                                <br/>
                                                <span style="color:#00a700;">Percentage Complete:&nbsp;</span><em><?php echo $backgorund_order['order_response']->orderStatus->percentageComplete; ?> </em>
                                                <br/>
                                                <span style="color:#00a700;">Completed Date:&nbsp;</span><em><?php echo $backgorund_order['order_response']->orderStatus->completedDate; ?> </em>
                                                <br/>
                                                <span style="color:#00a700;">Summary:&nbsp;</span><em><?php print_r($backgorund_order['order_response']->orderStatus->summary); ?> </em>
                                                <br/>

                                            </h4> -->
                                        </div>
                                        <?php
//                                        echo "<pre>";
//                                        print_r($backgorund_order['order_response']);
//                                        echo "</pre>";
                                        ?>
                                    </div>
                                </fieldset>
                            </div>   
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>
