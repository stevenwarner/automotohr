
<?php 
    //
    $newArray = [];
    //
    if(!empty($graph1['Records'])){
        //
        foreach($graph1['Records'] as $record){
            //
            if($record['is_completed'] == 1){
                continue;
            }
            //
            $url = ($record['is_manager'] ? 'feedback' : 'review'). '/';
            $url .= $record['review_sid']. '/';
            $url .= $record['reviewee_sid']. '/';
            $url .= $record['reviewer_sid'];
            //
            if(!isset($newArray[$record['review_sid']])){
                $newArray[$record['review_sid']] = [
                    'Name' => $record['review_title'],
                    'Count' => 0,
                    'Rows' => ''
                ];
            }
            //
            $newArray[$record['review_sid']]['Count']++;
            //
            $newArray[$record['review_sid']]['Rows'] .= '
            <tr>
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        '.(ucwords($record['first_name'].' '.$record['last_name'])).'
                    </p>
                </td>
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        '.(ucwords($record['reviewee_first_name'].' '.$record['reviewee_last_name'])).'
                    </p>
                </td>
                <td style="vertical-align: middle;">
                    <p class="csF16">
                        '.($record['is_manager'] ? 'Reporting Manager' : 'Reviewer').'
                    </p>
                </td>
                <td style="vertical-align: middle;">
                    <p class="csF14">
                        ('.(formatDateToDB($record['review_start_date'], DB_DATE, DATE)).' - 
                        '.(formatDateToDB($record['review_end_date'], DB_DATE, DATE)).')
                    </p>
                </td>
                <td style="vertical-align: middle;">
                    <a href="'.(purl($url)).'" class="btn btn-orange csF16" target="_blank">
                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp; View Review
                    </a>
                </td>
            </tr>';
        }
        
    }

?>
<!--  -->
<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
    <!--  -->
    <div class="row dn">
        <div class="col-sm-4 col-xs-12 col-sm-offset-8">
            <input type="text" readonly placeholder="MM/DD/YYYY - MM/DD/YYY" id="jsRangeDate" class="form-control csCP" />
            <div class="pa10">
                <i class="fa fa-info-circle csF16 csB7 csCP" aria-hidden="true"></i>&nbsp; Report is generated according to this date range.
            </div>
        </div>
    </div>
    <br />
    <!-- Overall -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #81b431;" >
            <div class="row">
                <div class="col-md-11 col-xs-11">
                    <!--  -->
                    <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                        Report
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <!-- Graph 1 -->
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <canvas id="jsTimeoffPieGraph" height="300px"></canvas>
                    </div>
                    <h3 class="csF16 text-center">
                        <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Based on the started review(s).
                    </h3>
                </div>
                <!-- Graph 3 -->
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div>
                        <canvas id="jsTimeoffPieGraph2" height="300px"></canvas>
                    </div>
                    <h3 class="csF16 text-center">
                        <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Review(s) By Status.
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <p class="csF16 csB7">
                Reviewer(s) that haven't completed the reviews.
            </p>
            <?php
                if(!empty($newArray)){
                    foreach($newArray as $row){
                        ?>
                        <div class="pane panel-theme">
                            <div class="panel-heading">
                                <h5 class="csF16 csB7 mt0 mb0 csW">
                                    <?=$row['Name'];?>
                                    <span class="pull-right">
                                        Records Found: <?=$row['Count'];?>
                                    </span>
                                </h5>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Reviewer</th>
                                            <th scope="col">Reviewee</th>
                                            <th scope="col">Cycle Period</th>
                                            <th scope="col">Review Type</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $row['Rows']; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
</div>

<script>
var graph1 = <?=json_encode($graph1);?>;
var graph2 = <?=json_encode($graph2);?>;
</script>