<?php 
    if($load_view){
        ?>
        <!-- Main Page Box -->
        <div class="csPageWrap" style="margin: 20px auto;">
            <div class="col-md-6 col-xs-12">
                <p class="csF16 csB7 jsToggleHelpArea ma10" data-help="assigned_reviews"><i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;<em style="color: #cc0000;">The provided feedback will be shared with the reporting manager(s).</em></p>
            </div>
            <div class="col-md-6 col-xs-12">
                <span class="pull-right">
                    <a href="" class="btn btn-black dn" title="Send a reminder email to the reviewer." placement="top">
                        <i class="fa fa-bell-o" aria-hidden="true"></i>&nbsp; Send A Reminder Email
                    </a>
                    <a href="<?=purl('pd/print/'.$reviewId.'/'.$revieweeId.'/'.$reviewerId);?>" target="blank" class="btn btn-orange"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Print</a>
                    <a href="<?=purl('pd/download/'.$reviewId.'/'.$revieweeId.'/'.$reviewerId);?>" target="blank" class="btn btn-orange"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download</a>
                </span>
            </div>
            <div class="clearfix"></div>
            <br />
            <!-- Left Sidebar -->
            <?php $this->load->view("{$pp}left_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}feedback/review_content_blue"); ?>
        </div>
        <?php
    } else{
        // Load green view
    }
?>
