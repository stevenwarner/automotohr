<?php 
if(!empty($policyHistory)){
 foreach($policyHistory as $record) {?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?php echo $record['title']; ?></strong>
    </div>
    <div class="panel-body">
        <p><strong><?php echo remakeEmployeeName($record);?></strong></p>
        <p><strong><?php echo formatDateToDB($record['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></strong></p>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                
                foreach(json_decode($record['change_json'], true) as $key => $val) {?>
                    <tr>
                      <th><?php 
                        echo ucwords(str_replace("_"," ",$key));
                        ?></th>
                        <td><p class="text-danger">
                        <?php
                        
                        if($key=='assigned_employees' || $key=='allowed_approvers'){
                         if(!empty(getUserNameBySIDString($val['old_value']))){
                          foreach (getUserNameBySIDString($val['old_value']) as $employeeName){
                            echo $employeeName."<br>";
                           }
                        }

                        }else{
                        if($val['old_value']==1){
                            echo "Yes";

                        }elseif($val['old_value']==0){
                            echo "No";
                        }else{
                            echo $val['old_value'];
                        }
                        }
                         ?>
                         </p></td>
                        <td>
                            <p class="text-success">
                        <?php
                            if($key=='assigned_employees' || $key=='allowed_approvers'){
                                if(!empty(getUserNameBySIDString($val['new_value']))){
                                    foreach (getUserNameBySIDString($val['new_value']) as $employeeName){
                                      echo $employeeName."<br>";
                                     }
                                  }

                            }else{
                                if($val['new_value']==1){
                                    echo "Yes";
                                }elseif($val['new_value']==0){
                                    echo "No";
                                }else{
                                echo $val['new_value'];
                                }
                            }
                             ?>
                             </p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php 
} 
    }else{
    ?>

    <div class="panel panel-default">
    <div class="panel-heading text-danger">
        <strong>Record Not Found</strong>
    </div>
   
</div>
   <?php } ?>