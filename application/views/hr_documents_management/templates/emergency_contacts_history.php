<?php if(count($data)):?>
<?php foreach($data as $k => $v): 
    $d = $v; 
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
                        <label>First Name</label>
                        <p><?=!empty($d['first_name']) ? $d['first_name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Last Name</label>
                        <p><?=!empty($d['last_name']) ? $d['last_name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Relationship</label>
                        <p><?=!empty($d['relationship']) ? $d['relationship'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Priority</label>
                        <p><?=!empty($d['priority']) ? $d['priority'] : 'N/A';?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>Country</label>
                        <p><?=!empty($d['Location_Country']) ? $cs[$d['Location_Country']]['Name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>State</label>
                        <p><?=!empty($d['Location_State']) ? $cs[$d['Location_Country']]['States'][$d['Location_State']]['Name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3  col-xs-3">
                        <label>City</label>
                        <p><?=!empty($d['Location_City']) ? $d['Location_City'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Postal Code</label>
                        <p><?=!empty($d['postal_code']) ? $d['postal_code'] : 'N/A';?></p>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>Phone</label>
                        <p><?=!empty($d['PhoneNumber']) ? $d['PhoneNumber'] : 'N/A';?></p>
                    </div>
                    
                    <div class="col-sm-3 col-xs-3">
                        <label>Email</label>
                        <p><?=!empty($d['email']) ? $d['email'] : 'N/A';?></p>
                    </div>
                </div>

                <br />
                
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <label>Location_Address</label>
                        <p><?=!empty($d['address']) ? $d['address'] : 'N/A';?></p>
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