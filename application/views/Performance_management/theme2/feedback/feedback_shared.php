
        <div class="csPageWrap" style="margin: 20px auto;">
            <div class="col-md-6 col-xs-12">
           
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
            <?php $this->load->view("{$pp}feedback/review_content_blue_shared"); ?>
        </div>
     