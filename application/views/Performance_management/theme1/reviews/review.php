<?php

    $reviewerOBJ = [];
    //
    if(!empty($review['Reviewers'])){
        foreach($review['Reviewers'] as $reviewer){
            //
            if(!isset($reviewerOBJ[$reviewer['reviewee_sid']])) $reviewerOBJ[$reviewer['reviewee_sid']] = ['Reviewers' => [], 'Managers' => []];
            //
            $reviewerOBJ[$reviewer['reviewee_sid']][$reviewer['is_manager'] == 0 ? 'Reviewers' : 'Managers'][] = $reviewer;
        }
    }
    //
    $progress = [];
    $progress['reviewers']['total']
     = array_values(array_filter($review['Reviewers'], function($reviewer){
        return $reviewer['is_manager'] == 0;
    }));
    //
    $progress['reviewers']['completed'] = array_filter($review['Reviewers'], function($reviewer){
        return $reviewer['is_manager'] == 0 && $reviewer['is_completed'] == 1;
    });
    // 
    $progress['reviewers']['total_per'] = 100;
    $progress['reviewers']['completed_per'] = ceil((count($progress['reviewers']['completed']) * 100 )/ count($progress['reviewers']['total']));
    $progress['reviewers']['pending_per'] = 100  - $progress['reviewers']['completed_per'];
    //
    $progress['managers']['total']
     = array_values(array_filter($review['Reviewers'], function($reviewer){
        return $reviewer['is_manager'] == 1;
    }));
    //
    $progress['managers']['completed'] = array_filter($review['Reviewers'], function($reviewer){
        return $reviewer['is_manager'] == 1 && $reviewer['is_completed'] == 1;
    });
    // 
    $progress['managers']['total_per'] = 100;
    $progress['managers']['completed_per'] = ceil((count($progress['managers']['completed']) * 100 )/ count($progress['managers']['total']));
    $progress['managers']['pending_per'] = 100  - $progress['managers']['completed_per'];
?>

<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <div class="row">
            <div class="col-sm-12">
                <a href="<?=purl('reviews');?>" class="btn btn-black csF16"><i class="fa fa-long-arrow-left csF16"></i> All
                    Reviews</a>
                </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>
                    <span class="csBTNBox">
                        <div class="dropdown">
                            <button class="btn btn-orange btn-lg dropdown-toggle csF16" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Actions <span class="caret csF16"></span>
                            </button>
                            <ul class="dropdown-menu csUL" aria-labelledby="dropdownMenu1" style="left: -80%">
                                <li><a href="#" class="jsAddReviewers csF16" data-id="<?=$review['sid'];?>"><i class="fa fa-plus-circle csF16"></i> Add a Reviewee</a></li>
                            </ul>
                        </div>
                    </span>
                    <h1>
                        <span class="csF18 csB7"><?=$review['review_title'];?></span>
                    </h1>
                </h1>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Loader -->
                <div class="csIPLoader jsIPLoader dn" data-page="review_listing"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Data -->
                    <div class="csPageBodyProgress pt10">
                        <div class="row">
                            <div class="col-sm-6">
                                <h1 class="csF18 csB8">Reviewers Progress</h1>
                                <div class="progress csRadius100">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                        aria-valuemax="100" style="width: <?=$progress['reviewers']['completed_per'];?>%;"></div>
                                </div>
                                <ul class="csSpan">
                                    <li class="jsPopoverLiReviewer csF16" title="Reviewers Progress">
                                        <span class="csRadius50 active"></span>
                                        <?=$progress['reviewers']['completed_per'];?>% Completed
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <h1 class="csF18 csB8">Managers Progress</h1>
                                <div class="progress csRadius100">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                        aria-valuemax="100" style="width: <?=$progress['managers']['completed_per'];?>%;"></div>
                                </div>
                                <ul class="csSpan">
                                    <li class="jsPopoverLiManager csF16"  title="Manager Feedback Progress">
                                        <span class="csRadius50 active"></span>
                                        <?=$progress['managers']['completed_per'];?>% Completed
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="csPageBodyData">
                        <!-- Reviewees -->
                        <div class="csPajeSection jsPageSection" data-id="reviewees">
                            <div class="table-reponsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="column" class="csF18 csB7 ">Reviewee</th>
                                            <th scope="column" class="csF18 csB7 ">Review Period</th>
                                            <th scope="column" class="csF18 csB7 ">Reviewer Progress</th>
                                            <th scope="column" class="csF18 csB7 " colspan="2">Manager Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($review['Reviewees'])):
                                        foreach($review['Reviewees'] as $reviewee):?>
                                        <tr 
                                            data-id="<?=$reviewee['reviewee_sid'];?>" 
                                            data-name="<?=$employees[$reviewee['reviewee_sid']]['name'];?>"
                                            data-sd ="<?=formatDate($reviewee['start_date'], 'Y-m-d', 'm/d/Y');?>"
                                            data-ed ="<?=formatDate($reviewee['end_date'], 'Y-m-d', 'm/d/Y');?>"
                                        >
                                            <td>
                                                <div class="csEBox">
                                                    <figure>
                                                        <img src="<?=$employees[$reviewee['reviewee_sid']]['img'];?>"
                                                            class="csRadius50" alt=""/>
                                                    </figure>
                                                    <div class="csEBoxText">
                                                        <h4 class="mb0 csF16 csB7"><?=$employees[$reviewee['reviewee_sid']]['name'];?></h4>
                                                        <p class="csF16"><?=$employees[$reviewee['reviewee_sid']]['role'];?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="csF16"><?=formatDate($reviewee['start_date'], 'Y-m-d', 'M d, Y');?> - <?=formatDate($reviewee['end_date'], 'Y-m-d', 'M d, Y');?></p>
                                            </td>
                                            <td>
                                                <?php if(empty($reviewerOBJ[$reviewee['reviewee_sid']]['Reviewers'])): ?>
                                                <p class="csF16">No Reviewers</p>
                                                <?php else:?>
                                                    <div class="csPBox">
                                                        <ul class="mb0">
                                                <?php 
                                                    $sharedCount = 0;
                                                    $i = 3;
                                                    foreach($reviewerOBJ[$reviewee['reviewee_sid']]['Reviewers'] as $k => $reviewer): 
                                                        if($k <= $i):
                                                            echo '<li><img src="'.($employees[$reviewer['reviewer_sid']]['img']).'" class="csRadius50"></li>';
                                                        endif;
                                                        if($reviewer['is_completed'] == 1 ) {$sharedCount ++;}
                                                ?>
                                                <?php endforeach; ?>
                                                        </ul>
                                                        <span class="csF16"><?=ceil(($sharedCount * 100) / count($reviewerOBJ[$reviewee['reviewee_sid']]['Reviewers']))?>% Completed</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(empty($reviewerOBJ[$reviewee['reviewee_sid']]['Managers'])): ?>
                                                <p class="csF16">No Managers</p>
                                                <?php else:?>
                                                    <div class="csPBox">
                                                        <ul class="mb0">
                                                <?php 
                                                    $sharedCount = 0;
                                                    $i = 3;
                                                    foreach($reviewerOBJ[$reviewee['reviewee_sid']]['Managers'] as $k => $reviewer): 
                                                        if($k <= $i):
                                                            echo '<li><img src="'.($employees[$reviewer['reviewer_sid']]['img']).'" class="csRadius50"></li>';
                                                        endif;
                                                        if($reviewer['is_completed'] == 1 ) $sharedCount ++;
                                                ?>
                                                <?php endforeach; ?>
                                                        </ul>
                                                        <span class="csF16"><?=ceil(($sharedCount * 100) / count($reviewerOBJ[$reviewee['reviewee_sid']]['Managers']))?>% Completed</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="csBTNBox">
                                                <?php   
                                                    if(
                                                        in_array($employerId, array_column($reviewerOBJ[$reviewee['reviewee_sid']]['Managers'], 'reviewer_sid')) ||
                                                        in_array($employerId, array_column($reviewerOBJ[$reviewee['reviewee_sid']]['Reviewers'], 'reviewer_sid'))
                                                    ){
                                                ?>
                                                    <?php if($review['status'] != 'pending'):?>
                                                    <?php  if(in_array($employerId, array_column($reviewerOBJ[$reviewee['reviewee_sid']]['Managers'], 'reviewer_sid'))): ?>
                                                    <a href="<?=purl('feedback/'.($pid).'/'.($reviewee['reviewee_sid']).'');?>" class="btn btn-black"><i class="fa fa-eye csF16"></i> View</a>
                                                    <?php else: ?>
                                                    <a href="<?=purl('reviewer_feedback/'.($pid).'/'.($reviewee['reviewee_sid']).'');?>" class="btn btn-black"><i class="fa fa-eye csF16"></i> View</a>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php }?>
                                                    <div class="dropdown dn">
                                                        <button class="btn dropdown-toggle csF16" type="button" id="dropdownMenu1"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="true">
                                                            <i class="fa fa-ellipsis-v csF16" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="left: auto; right: 100%;">
                                                            <li><a href="javascript:void(0)" class="jsRemoveReviewee csF16"><em class="fa fa-times-circle csF16"></em> Remove Reviewee</a></li>
                                                            <li><a href="javascript:void(0)" class="jsReviewPeriodReviewee csF16"><em class="fa fa-edit csF16"></em> Change Review Period</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Reviewers -->
                        <div class="csPajeSection jsPageSection" data-id="reviewers"></div>
                       
                    </div>

                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $reviewListing = [];
    foreach($progress['reviewers']['total'] as $e){
        $reviewListing[] = [
            'name' => $employees[$e['reviewer_sid']]['name'],
            'completed' => $e['is_completed']
        ];
    }
    $managerListing = [];
    foreach($progress['managers']['total'] as $e){
        $managerListing[] = [
            'name' => $employees[$e['reviewer_sid']]['name'],
            'completed' => $e['is_completed']
        ];
    }
?>

<script>

let reviewerListing = <?=json_encode($reviewListing);?>;
let managerListing = <?=json_encode($managerListing);?>;


$('.jsPopoverLiReviewer').popover({
    placement: "bottom auto",
    trigger: 'click',
    html: true,
}).on('inserted.bs.popover', function(){
    let html = `<ul class="csPopoverLi">`;
    reviewerListing.map(function(l){
        html += `<li><strong>${l.name}</strong><i class="fa fa-circle ${l.completed == 1 ? "active" : ""}"></i></li>`;
    });
    html += `</ul>`;
    $(this).parent().find('.popover-content').html(html)
});

$('.jsPopoverLiManager').popover({
    placement: "bottom auto",
    trigger: 'click',
    html: true,
}).on('inserted.bs.popover', function(){
    let html = `<ul class="csPopoverLi">`;
    managerListing.map(function(l){
        html += `<li><strong>${l.name}</strong><i class="fa fa-circle ${l.completed == 1 ? "active" : ""}"></i></li>`;
    });
    html += `</ul>`;
    $(this).parent().find('.popover-content').html(html)
});

setTimeout(() => {
    $('.jsPopoverLiReviewer').tooltip('destroy')
    $('.jsPopoverLiManager').tooltip('destroy')
}, 1000);
</script>

