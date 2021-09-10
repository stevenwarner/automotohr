<!--  -->
<div class="container">
    <?php 
        if(!empty($Review)){
            foreach($Review['Reviewees'] as $Reviewee){
                ?>
                <!--  -->
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <h5 class="csF16 csB7 csW mt0 mb0">
                            <?=$company_employees_index[$Reviewee['reviewee_sid']]['Name'];?> <?=$company_employees_index[$Reviewee['reviewee_sid']]['Role'];?>
                            <span class="pull-right">
                                Reviewers Found: <?=count($Reviewee['reviewers']);?>
                            </span>
                        </h5>
                        <div class="clearfix"></div>
                    </div>
                    <!--  -->
                    <div class="panel-body">
                        <?php 
                            if(!empty($Reviewee['reviewers'])){
                                ?>
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Reviewer</th>
                                                <th scope="col">Reviewer Type</th>
                                                <th scope="col">Review Status</th>
                                                <th scope="col">Cycle Period</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                <?php
                                foreach($Reviewee['reviewers'] as $Reviewer){
                                    //
                                    $url = $Reviewer['is_manager'] ? 'feedback/' : 'review/';
                                    $url .= $Review['sid'].'/';
                                    $url .= $Reviewee['reviewee_sid'].'/';
                                    $url .= $Reviewer['reviewer_sid'].'/';
                                    //
                                    $url = purl($url);
                                    ?>
                                    <tr>
                                        <td>
                                            <p class="csF16 csB7">
                                                <b>
                                                    <?=$company_employees_index[$Reviewer['reviewer_sid']]['Name'];?> <?=$company_employees_index[$Reviewer['reviewer_sid']]['Role'];?>
                                                </b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="csF16 csB7">
                                                <?=$Reviewer['is_manager'] ? 'Reporting Manager' : 'Reviewer';?>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="csF16 csB7 <?=$Reviewer['is_completed'] ? 'csFC1' : 'csFC3';?>">
                                                <b>
                                                    <?=$Reviewer['is_completed'] ? 'COMPLETED' : 'PENDING';?>
                                                </b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="csF16 csB7">
                                                <?=formatDateToDB($Reviewee['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($Reviewee['end_date'], DB_DATE, DATE);?>
                                            </p>
                                        </td>
                                        <td>
                                            <a href="<?=$url;?>" class="csF16 csB7 btn btn-orange">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;
                                                View Review
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    </tbody>
                                </table>
                                <?php
                            } else{
                                ?>
                                <p class="csF16 csB7 text-center">
                                    No reviewers found
                                </p>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <?php
            }
        } else{
            ?>
            <p class="csF16 csB7 text-center">
                No reviewees found
            </p>
            <?php
        }
    ?>
</div>