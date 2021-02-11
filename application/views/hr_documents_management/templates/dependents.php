<?php if(count($data)): ?>
<?php 
    foreach($data as $k => $v): 
        $d = @unserialize($v['dependant_details']);

        if($k != 0 && $k % 3 == 0) echo '<div class="js-break"></div>';
?>
<!-- Row 1 -->
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading">Dependent <?=$k+1;?></div>
            <div class="panel-body">
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
                        <label>Gender</label>
                        <p><?=!empty($d['gender']) ? $d['gender'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Relationship</label>
                        <p><?=!empty($d['relationship']) ? $d['relationship'] : 'N/A';?></p>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        <label>Country</label>
                        <p><?=!empty($d['Location_Country']) ? $cs[$d['Location_Country']]['Name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>State</label>
                        <p><?=!empty($d['Location_State']) ? $cs[$d['Location_Country']]['States'][$d['Location_State']]['Name'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <label>Postal Code</label>
                        <p><?=!empty($d['postal_code']) ? $d['postal_code'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-3  col-xs-3">
                        <label>SSN</label>
                        <p><?=!empty($d['ssn']) ? $d['ssn'] : 'N/A';?></p>
                    </div>
                </div>

                <br />

                <div class="row">
                    
                    <div class="col-sm-3 col-xs-3">
                        <label>Birth Date</label>
                        <p><?=!empty($d['birth_date']) ? $d['birth_date'] : 'N/A';?></p>
                    </div>

                    <div class="col-sm-3 col-xs-3">
                        <label>Phone</label>
                        <p><?=!empty($d['phone']) ? $d['phone'] : 'N/A';?></p>
                    </div>
                    
                    <div class="col-sm-6 col-xs-6">
                        <label>Is family member?</label>
                        <p><?=!empty($d['family_member']) ? ( $d['family_member'] == 'on' ? 'Yes' : 'No' ) : 'N/A';?></p>
                    </div>
                </div>

                <br />
                
                <div class="row">
                <div class="col-sm-6 col-xs-6">
                        <label>Address</label>
                        <p><?=!empty($d['address']) ? $d['address'] : 'N/A';?></p>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label>Address Line 2</label>
                        <p><?=!empty($d['address_line']) ? $d['address_line'] : 'N/A';?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>