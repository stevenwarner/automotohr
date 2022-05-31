<?php if(count($data)): ?>
<?php 
    foreach($data as $k => $v): 
        $d = @unserialize($v['license_details']);
        if($k != 0 && $k % 3 == 0) echo '<div class="js-break"></div>';
?>
<!-- Row 1 -->
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading">
            <span class="glyphicon glyphicon-plus" style="color: #fff;"> </span> <a data-toggle="collapse" href="#collapse<?=$k;?>" style="color: #fff;"><?=reset_datetime(array('datetime' => $v['logged_at'],'format' => 'M d Y, D H:i:s', '_this' => $this));?></a>    
         </div>
            <div class="panel-body" style="padding-top: 15px;padding-right: 15px;padding-bottom: 0px;padding-left: 15px;">
            <div id="collapse<?=$k;?>" class="panel-collapse collapse">
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>License Type</label>
                        <p><?=!empty($d['license_type']) ? $d['license_type'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>License Authority</label>
                        <p><?=!empty($d['license_authority']) ? $d['license_authority'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>License Class</label>
                        <p><?=!empty($d['license_class']) ? $d['license_class'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>License Number</label>
                        <p><?=!empty($d['license_number']) ? $d['license_number'] : 'N/A';?></p>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>License Issue Date</label>
                        <p><?=!empty($d['license_issue_date']) ? $d['license_issue_date'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>License Expiration Date</label>
                        <p><?=!empty($d['license_expiration_date']) ? $d['license_expiration_date'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Is infinite?</label>
                        <p><?=!empty($d['license_indefinite']) ? ($d['license_indefinite'] == 'on' ? 'Yes' : 'No') : 'No';?></p>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>Notes</label>
                        <p><?=!empty($d['license_notes']) ? $d['license_notes'] : 'N/A';?></p>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>License Image</label> <br />
                        <?=!empty($v['license_file']) ? '<img src="'.('data:image/png;base64, '.base64_encode(getFileData(AWS_S3_BUCKET_URL.$v['license_file']))).'" style="display: block; max-with: 100%; margin: auto;" />' : 'N/A'; ?>
                    </div>
                </div>
           

                <?$k=$k+1;?>
    </div>

            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>