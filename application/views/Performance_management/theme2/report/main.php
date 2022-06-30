<div class="container-fluid">
    
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Body  -->
                <div class="csPageBoxBody pl10 pr10 pb10">
                    <!-- Data -->
                    <div class="csPageBodyHeader bbb mb10">
                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <h3 class="csF18 csB7"><strong>Filter</strong>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <span class="pull-right">
                                    <h3 data-trigger="jsAccordian" data-target="filter">
                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Data -->
                    <div class="csPageBody" data-target="jsfilter">
                        <!--  -->
                        <div class="row mb10">
                            <!-- Filter - Department -->
                            <div class="col-sm-3 col-xs-12">
                                <label class="csF16 csB7">Filter by Reviews</label>
                                <select id="jsReportReview">
                                    <option value="all">All</option>
                                    <?php 
                                        if(!empty($reviews)){
                                            foreach($reviews as $review){
                                                echo '<option value="'.( $review['sid'] ).'" '.( $reviewId == $review['sid'] ? 'selected' : '' ).'>'.( $review['review_title'] ).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <!-- Filter - Employee -->
                            <div class="col-sm-3 col-xs-12">
                                <label class="csF16 csB7">Filter by Employee</label>
                                <select id="jsReportEmployee">
                                    <option value="all">All</option>
                                    <?php 
                                    foreach($allEmployees as $k => $emp){
                                        echo '<option value="'.( $emp['Id'] ).'"'.( $employeeIds == $emp['Id'] ? 'selected' : '' ).'>'.( $emp['FirstName'].' '.$emp['LastName'].' '.$emp['FullRole'] ).'</option>';
                                    } ?>
                                </select>
                            </div>
                            <!-- Filter - Department -->
                            <div class="col-sm-3 col-xs-12">
                                <label class="csF16 csB7">Start Date</label>
                                <input type="text" class="form-control" readonly id="jsReportStartDate" value="<?=$startDate;?>" />
                            </div>
                            <!-- Filter - Manager -->
                            <div class="col-sm-3 col-xs-12">
                                <label class="csF16 csB7">End Date</label>
                                <input type="text" class="form-control" readonly id="jsReportEndDate" value="<?=$endDate;?>"  />
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <!-- Filter - Employee -->
                            <div class="col-sm-12 col-xs-12">
                                <span class="pull-right">
                                    <br />
                                    <button class="btn btn-orange jsReportSearch">Search</button>
                                    <button class="btn btn-black jsReportClear">Clear Filter</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="csPageBox csRadius5">
                <!--  -->
                <div class="csPageBody  pl10 pr10 pb10">
                    <div class="csPageBoxHeader bbb mb10">
                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <h3 class="csF18 csB7"><strong> Reviews</strong></h3>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <span class="pull-right">
                                    <h3 data-trigger="jsAccordian" data-target="reviewbox">
                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="csPageBody" data-target="jsreviewbox">
                        <div class="row">
                            <?php 
                                if(!empty($reviews)){
                                    foreach($reviews as $review){
                                        ?>
                                        <div class="col-xs-3 col-xs-12">
                                            <div class="csPageBox">
                                                <div class="csPageHeader pl10 pr10">
                                                    <h3 class="csF16 csB7"><strong><?=ucwords($review['review_title']);?></strong></h3>
                                                </div>
                                                <div class="csPageHeader pl10 pr10">
                                                    <div id="jsReviewReport<?=$review['sid'];?>" style="width: 100%; height: 130px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="csPageBox csRadius5">
                <!--  -->
                <div class="csPageBody pl10 pr10 pb10">
                    <div class="csPageBoxHeader bbb mb10">
                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <h3 class="csF18 csB7"><strong> Employees</strong></h3>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <span class="pull-right">
                                    <h3 data-trigger="jsAccordian" data-target="employeebox">
                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="csPageBody" data-target="jsemployeebox">
                        <div class="row">
                        <?php 
                            //
                            $employeeData = [];
                            foreach($allEmployees as $k => $emp){
                                //
                                if(!isset($allReviews['employees'][$emp['Id']])){
                                    continue;
                                }
                                //
                                $employeeData[$emp['Id']] = [
                                    'agree' => 0,
                                    'neutral' => 0,
                                    'disagree' => 0,
                                    'name' => ucwords($emp['FirstName'].' '.$emp['LastName']),
                                    'role' => $emp['FullRole']
                                ];
                                ?>
                                <div class="col-xs-3 col-xs-12">
                                    <div class="csPageBox">
                                        <div class="csPageHeader pl10 pr10">
                                            <h3 class="csF16 csB7"><strong><?=ucwords($emp['FirstName'].' '.$emp['LastName']);?></strong></h3>
                                            <p class="csF14"><?=$emp['FullRole'];?></p>
                                        </div>
                                        <div class="csPageHeader pl10 pr10">
                                            <div id="jsEmployeeReport<?=$emp['Id'];?>" style="width: 100%; height: 130px;">
                                                <p class="alert alert-info text-center">No Data found</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
                
                <!--  -->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<?php 
    $reviewCategory = [];
    //
    if(!empty($allReviews['reviews'])){
        foreach($allReviews['reviews'] as $key => $review){
            //
            $reviewCategory[] = [
                'id' => 'jsReviewReport'.$key,
                'data' => $review
            ];
        }
    }
    //
    $employeeCategory = [];
    //
    if(!empty($allReviews['employees'])){
        foreach($allReviews['employees'] as $key => $review){
            //
            $empDetail = $employeeData[$key];
            //
            $employeeCategory[] = [
                'id' => 'jsEmployeeReport'.$key,
                'data' => $review
            ];
        }
    }

?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
    .highcharts-credits{ display: none;}
</style>

<script>
$(function(){
    //
    var densen = <?=json_encode($employeeCategory);?>;
    var densenReview = <?=json_encode($reviewCategory);?>;
    //
    $('#jsReportReview').select2();
    $('#jsReportEmployee').select2();

    //
    if(densen.length){
        densen.map(function(den){
            makeContainer(
                den.id,
                '',
                [
                    {
                        data: [{
                            name: 'Disagree',
                            y: den.data.disagree,
                            color: '#cc1100'
                        }]
                    },
                    {
                        data: [{
                            name: 'Neutral',
                            y: den.data.neutral,
                            color: '#0000ff'
                        }]
                    },
                    {
                        data: [{
                            name: 'Agree',
                            y: den.data.agree,
                            color: '#81b431'
                        }]
                    },
                ],{
                    xAxis: {
                        labels:{
                            enabled: false
                        }
                    }
                }
            );
        });
    }

    //
    if(densenReview.length){
        densenReview.map(function(den){
            makeContainer(
                den.id,
                '',
                [
                    {
                        data: [{
                            name: 'Disagree',
                            y: den.data.disagree,
                            color: '#cc1100'
                        }]
                    },
                    {
                        data: [{
                            name: 'Neutral',
                            y: den.data.neutral,
                            color: '#0000ff'
                        }]
                    },
                    {
                        data: [{
                            name: 'Agree',
                            y: den.data.agree,
                            color: '#81b431'
                        }]
                    },
                ],{
                    xAxis: {
                        labels:{
                            enabled: false
                        }
                    }
                }
            );
        });
    }

    //
    function makeContainer(
        target,
        categories,
        data,
        additionalOptions
    ){
        //
        var options = {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            tooltip: {
            formatter: function() {
                return this.key+' '+this.y+'%';
            }
        },
            xAxis: {
                categories: categories
            },
            yAxis: {
                min: 0,
                tickInterval: 25,
                max: 100
            },
            legend: false,
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: data
        };
        //
        if(additionalOptions !== undefined){
            options = Object.assign(options, additionalOptions);
        }
        Highcharts.chart(target, options);
    }

    //
    $(document).on('click', '[data-trigger="jsAccordian"]', function(event){
        //
        event.preventDefault();
        //
        if($('[data-target="js'+( $(this).data('target') )+'"]').is(":visible")){
            $('[data-target="js'+( $(this).data('target') )+'"]').fadeOut(0);
        } else{
            $('[data-target="js'+( $(this).data('target') )+'"]').fadeIn(0);
        }
        $(this).find('i').toggleClass('fa-minus-circle');
        $(this).find('i').toggleClass('fa-plus-circle');
        //
        $('[data-target="js'+( $(this).data('target') )+'"]').closest('.csPageBox').css('min-height', 0);
    });

    //
    $('#jsReportStartDate').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function (e){
            $('#jsReportEndDate').datepicker('option', 'minDate', e);
            $('#jsReportEndDate').val(e);
        }
    });
    //
    $('#jsReportEndDate').datepicker({
        changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
    });

    //
    $('.jsReportSearch').click(function(event){
        //
        event.preventDefault();
        //
        var url = '';
        //
        url += $('#jsReportReview').val()+'/';
        url += $('#jsReportEmployee').val()+'/';
        url += ($('#jsReportStartDate').val() == '' ?  'all' : $('#jsReportStartDate').val()).replace(/\//g, '-')+'/';
        url += ($('#jsReportEndDate').val() == '' ? 'all' : $('#jsReportEndDate').val()).replace(/\//g, '-');
        //
        window.location.href = "<?=base_url('performance-management/report');?>/"+ url;
    });
    
    //
    $('.jsReportClear').click(function(event){
        //
        event.preventDefault();
        //
        window.location.href = "<?=base_url('performance-management/report');?>";
    });
})
</script>