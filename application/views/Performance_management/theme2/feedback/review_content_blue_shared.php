<?php 
    //
    $completedQuestionsOBJ = [];
    //
    if($selectedPage == 'feedback' ){
        $selectedPage = 0;
    }
    //
    $completedQuestionsCount = 0;
    $totalQuestions = count($review['QA']);
    $totalPages = $totalQuestions;
    //
    if($selectedPage == 0){
        $totalPages ++; 
        $totalQuestions ++;
        $question = $review['Feedback'];
    } else{
        //
        $question = $review['QA'][$selectedPage -1];
    }
    //
    foreach($review['QA'] as $index => $q){
        if(!empty($q['answer']['text']) || !empty($q['answer']['multiple_choice']) ||!empty($q['answer']['rating'])){
            $completedQuestionsOBJ[++$index] = true;
            $completedQuestionsCount++;
        }
    }
?>

<script>
var page = <?=$selectedPage;?>;
var totalPages = <?=$totalPages;?>;
var completedPages = <?=$completedQuestionsCount;?>;
var isManager = <?=$review['is_manager'];?>;
var question = {
    questionId: <?=$selectedPage == 'feedback' ? 0 : $review['QA'][$selectedPage -1]['question_id'];?>,
    reviewId: <?=$reviewId;?>,
    revieweeId: <?=$revieweeId;?>,
    reviewerId: <?=$reviewerId;?>,
    attachments: <?=empty($question['attachments']) ? '[]' : $question['attachments'];?>,
    multiple_choice: undefined,
    rating: undefined,
    text: undefined
};

<?php
        if(!empty($question['answer']['text'])){
            ?>
question.text = "<?=$question['answer']['text'];?>";
<?php
        }
        if(!empty($question['answer']['rating'])){
            ?>
question.rating = "<?=$question['answer']['rating'];?>";
<?php
        }
        if(!empty($question['answer']['multiple_choice'])){
            ?>
question.multiple_choice = "<?=$question['answer']['multiple_choice'];?>";
<?php
        }
    ?>
</script>

<div class="col-md-12 col-sm-12">
    <!--  -->
    <div class="csIPLoader jsIPLoader" data-page="save_question">
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
    </div>
    
    <!-- Questions -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!-- Basic -->
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div>
                        <p class="csF16 csB7">
                            <?=$review['review_title'];?>
                        </p>
                        <p class="csF14">
                            <?= formatDateToDB($review['start_date'], 'Y-m-d', 'M d Y, D').' - '.formatDateToDB($review['end_date'], 'Y-m-d', 'M d Y, D');?>
                            <br /><?=getDueText($review['start_date'], true);?>
                        </p>
                        <p class="csF14 csB7">
                            <?=ucwords($review['reviewer_first_name'].' '.$review['reviewer_last_name']);?>
                            Reviewing
                            <?=ucwords($review['first_name'].' '.$review['last_name']);?>
                        </p>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-6 col-xs-12">
                    <span class="pull-right csF16 csB7">
                        <?php if($review['is_started'] == 1){?>
                        <strong class="text-success">STARTED</strong>
                        <?php } else if($review['is_started'] == 2) { ?>
                        <strong class="text-danger">ENDED</strong>
                        <?php } else { ?>
                        <strong class="text-warning">PENDING</strong>
                        <?php }?>
                    </span>
                </div>
            </div>
            <hr>
            <!-- Basic -->
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div>
                        <p class="csF16 csB7">
                            Completed <?=$completedQuestionsCount;?> out of <?=$totalQuestions;?> Question(s)
                        </p>
                    </div>
                </div>
            </div>
            <!-- Basic -->
            <div class="row">
                <br>
                <div class="col-md-12 col-xs-12">
                    <div>
                        <ul class="csPaginationMenu text-left">
                            <?php for($i = 1; $i <= $totalQuestions; $i++): ?>
                            <li class="<?=$i == $selectedPage ? 'active' : '';?> <?=isset($completedQuestionsOBJ[$i]) ? ' active' : '';?>">
                                <a href="<?=current_url();?>?page=<?=$i;?>" title="<?=isset($completedQuestionsOBJ[$i]) ? 'Completed' : 'Pending';?>" placement="top"><?=$i;?></a>
                            </li>
                            <?php endfor; ?>
                            <?php 
                                if($review['is_manager']){
                                    ?>
                            <li <?='feedback' == $selectedPage ? 'class="active"' : '';?>>
                                <a href="<?=current_url();?>?page=feedback">Feedback</a>
                            </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($selectedPage != 'feedback' && !empty($question['other_answers'])) {?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <p class="csF16 csB7 csW mb0">
                        Reviewer(s) Feedback
                        </p>
                    </div>
                    <!--  -->
                    <div class="panel-body">
                        <?php
                            foreach($question['other_answers'] as $answer){
                                //
                                $cls = 'success';
                                $txt = 'ANSWERED';
                                //
                                if(
                                    empty($answer['multiple_choice']) &&
                                    empty($answer['rating']) &&
                                    empty($answer['text_answer'])
                                ){
                                    $cls = 'warning';
                                    $txt = 'PENDING';
                                }
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <p class="csF16 csW mb0">
                                            <?=$company_employees_index[$answer['reviewer_sid']]['Name'];?>'s Feedback
                                            <span class="pull-right"><button class="btn btn-<?=$cls;?> btn-xs csF16 csRadius5"><?=$txt;?></button></span>
                                        </p>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Multiple Choice</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                    <?=empty($answer['multipe_choice']) ? 'N/A' : $answer['multipe_choice'];?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Rating</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                <?=empty($answer['rating']) ? 'N/A' : $answer['rating'];?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Feedback</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                <?=empty($answer['text_answer']) ? 'N/A' : $answer['text_answer'];?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Attachment(s)</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                    <?php
                                                        if(!empty($answer['attachments'])){
                                                            ?>
                                                            <table class="table table-striped table-condensed">
                                                            <?php
                                                            foreach(json_decode($answer['attachments'], true) as $attachment){
                                                                ?>
                                                                <tr data-id="<?=$attachment;?>">
                                                                    <td style="vertical-align: middle">
                                                                        <p class="csF16"><?=$attachment;?></p>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <button class="btn btn-orange csF14 jsPreviewAttachment">
                                                                            <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            </table>
                                                            <?php
                                                        } 
                                                    ?>
                                                </p>
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
        </div>
    <?php }?>  
   
</div>
