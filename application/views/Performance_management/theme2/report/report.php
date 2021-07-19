
<!--  -->
<div class="col-md-9 col-sm-12 col-xs-12">
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
        <div class="panel-heading">
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
                        <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Based on started review(s).
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
            <table class="table table-striped table-condensed">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csF16 csB7">Reviewer</th>
                        <th scope="col" class="csF16 csB7">Review / Reviewee</th>
                        <th scope="col" class="csF16 csB7">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!empty($graph1['Records'])){
                            foreach($graph1['Records'] as $record){
                                //
                                if($record['is_completed'] == 1){
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td>
                                        <p class="csF16">
                                            <?=ucwords($record['first_name'].' '.$record['last_name']);?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            <?=ucwords($record['review_title']);?>
                                        </p>
                                        <p class="csF16">
                                            <?=ucwords($record['reviewee_first_name'].' '.$record['reviewee_last_name']);?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16 csB7 text-danger">
                                            PENDING
                                        </p>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var graph1 = <?=json_encode($graph1);?>;
var graph2 = <?=json_encode($graph2);?>;
</script>