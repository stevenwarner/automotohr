<?php

    if(!empty($AssignedReviews)){
        //
        $newArray = [];
        //
        $now = date('Y-m-d', strtotime('now'));
        //
        foreach($AssignedReviews as $review){
            //
            if(!isset($newArray[$review['review_title']])){
                $newArray[$review['review_title']] = [];
            }
            //
            $tr = '<tr>';
            $tr .= '    <td>';
            $tr .= '        <p class="csF16 csB7"><b>';
            $tr .=             $company_employees_index[$review['reviewee_sid']]['Name'];
            $tr .= '           </br>';
            $tr .=             $company_employees_index[$review['reviewee_sid']]['Role'];
            $tr .= '        </b></p>';
            $tr .= '    </td>';
            $tr .= '    <td>';
            $tr .= '        <p class="csF16 csB7">';
            $tr .=              formatDateToDB($review['start_date'], DB_DATE, DATE).' - '.formatDateToDB($review['end_date'], DB_DATE, DATE);
            $tr .= '            </br> ';
            $tr .= '            Due In:'.(dateDifferenceInDays($now, $review['start_date'], '%a')).' Day(s)';
            $tr .= '        </p>';
            $tr .= '    </td>';
            $tr .= '    <td>';
            $tr .= '        <p class="csF16 csB7">';
            $tr .= '            <a href="'.( purl("feedback/{$review['sid']}/{$review['reviewee_sid']}/{$review['reviewer_sid']}")).'" class="btn btn-orange csF14">Provide Feedback</a>';
            $tr .= '        </p>';
            $tr .= '</tr>';
            //
            $newArray[$review['review_title']][] = $tr;
        }
    }


?>

<?php
   if ($load_view) {

    $panelHeading = 'background-color: #3554DC';
} else {
    $panelHeading = 'background-color: #81b431';
}
 
 ?>


<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">

    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Reviews Assigned To Me - Reporting Manager
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12 text-right">
                    <h5 class="csF16 csB7 csW">
                        Reviews Found: <?=count($AssignedReviews);?>
                    </h5>
                </div>
            </div>
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csInfo csB7"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Assigned reviews on which your feedback is required. The submitted feedback will be shared with the employee.</p>
                </div>
            </div>
            <br>
            <?php
                //
                if(!empty($AssignedReviews)){
                    ?>
                    
                        <?php foreach($newArray as $review_title => $row){ ?>
                        <div class="panel panel-theme">
                            <div class="panel-heading" style="<?=$panelHeading?>">
                                <h5 class="csF16 csB7 csW mb0 mt0">
                                    <?=$review_title;?>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Cycle Period</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?=implode('',$row);?>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                        <?php } ?>
                    <?php
                } else{
                    ?>
                    <div class="panel-body">
                        <div class="row">
                            <p class="csF16 csB7 text-center">
                                No reviews are assigned to you for feedback.
                            </p>
                        </div>
                    </div>
                    <?php
                }
            ?>
           
        </div>
    </div>
</div>