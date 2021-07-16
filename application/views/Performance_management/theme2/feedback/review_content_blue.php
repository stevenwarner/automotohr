<?php 
    $totalQuestions = count($review['QA']);
    $question = $review['QA'][$selectedPage -1];
    //
    $completedQuestionsCount = 0;
    //
    foreach($review['QA'] as $q){
        if(!empty($q['answer']['text']) || !empty($q['answer']['multiple_choice']) ||!empty($q['answer']['rating'])){
            $completedQuestionsCount++;
        }
    }
?>

<script>
    var question = {
        questionId: <?=$review['QA'][$selectedPage -1]['question_id'];?>,
        reviewId: <?=$reviewId;?>,
        revieweeId: <?=$revieweeId;?>,
        reviewerId: <?=$reviewerId;?>,
        multiple_choice: undefined,
        rating: undefined,
        text: undefined
    };
</script>

<div class="col-md-9 col-sm-12">
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
                        <p class="csF14"> 
                            <?=ucwords($review['first_name'].' '.$review['last_name']);?>
                            <i class="fa fa-long-arrow-right csB7" aria-hidden="true"></i>
                            <?=ucwords($review['reviewer_first_name'].' '.$review['reviewer_last_name']);?>
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
                            <li <?=$i == $selectedPage ? 'class="active"' : '';?>>
                                <a href="<?=current_url();?>?page=<?=$i;?>"><?=$i;?></a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Question Screen -->
    <div class="row">
        <!--  -->
        <br />
        <div class="col-xs-12">
            <!--  -->
            <div class="panel panel-theme">
                <div class="panel-heading">
                    <h5 class="csF14 csB7 csW">
                        Q<?=$selectedPage;?>: <?=$question['question']['title'];?>
                    </h5>
                </div>
                <div class="panel-body">
                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <p class="csF14">
                                <?=$question['question']['description'];?>
                            </p>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <?php
                                if(!empty($question['question']['video'])){
                                    ?>
                                    <video controls style="width: 100%;" preload="metadata">
                                        <source src="<?=getVideoURL($question['question']['video']);?>" type="image/mp4"></source>
                                        <track label="English" kind="captions" srclang="en" default />
                                    </video>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <?php  if(preg_match('/multiple/i', $question['question']['question_type'])){ ?>
                            <!-- Multiple Choice -->
                            <div class="row">
                                <br />
                                <div class="col-xs-12">
                                    <label class="control control--radio csF14">
                                        <input type="radio" class="jsReviewChoice" name="jsReviewChoice" value="1" <?=!empty($question['answer']['multiple_choice']) && $question['answer']['multiple_choice'] == 1 ? 'checked' : '';?> /> Yes
                                        <span class="control__indicator"></span>
                                    </label> <br />
                                    <label class="control control--radio csF14">
                                        <input type="radio" class="jsReviewChoice" name="jsReviewChoice" value="0"  <?=!empty($question['answer']['multiple_choice']) && $question['answer']['multiple_choice'] == 0 ? 'checked' : '';?> /> No
                                        <span class="control__indicator"></span>
                                    </label>
                                </div>
                            </div>
                    <?php } ?>

                    <?php  if(preg_match('/rating/i', $question['question']['question_type'])){ ?>
                    <!-- Rating -->
                    <div class="row">
                        <br />
                        <ul class="csRatingBar pl10 pr10">
                            <li data-id="1" class="jsReviewRating <?=$question['answer']['rating'] == 1 ? 'active' : '';?>">
                                <p class="csF20 csB9">1</p>
                                <p class="csF14 csB6">Strongly Agree</p>
                            </li>
                            <li data-id="2" class="jsReviewRating <?=$question['answer']['rating'] == 2 ? 'active' : '';?>">
                                <p class="csF20 csB9">2</p>
                                <p class="csF14 csB6">Agree</p>
                            </li>
                            <li data-id="3" class="jsReviewRating <?=$question['answer']['rating'] == 3 ? 'active' : '';?>">
                                <p class="csF20 csB9">3</p>
                                <p class="csF14 csB6">Neutral</p>
                            </li>
                            <li data-id="4" class="jsReviewRating <?=$question['answer']['rating'] == 4 ? 'active' : '';?>">
                                <p class="csF20 csB9">4</p>
                                <p class="csF14 csB6">Disagree</p>
                            </li>
                            <li data-id="5" class="jsReviewRating <?=$question['answer']['rating'] == 5 ? 'active' : '';?>">
                                <p class="csF20 csB9">5</p>
                                <p class="csF14 csB6">Strongly Disagree</p>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>

                    <?php  if(preg_match('/text/i', $question['question']['question_type'])){ ?>
                    <!-- Text -->
                    <div class="row">
                        <br />
                        <div class="col-xs-12">
                            <p class="csF14 csB7">Feedback (Elaborate)</p>
                            <textarea rows="5" class="form-control jsReviewText"><?=$question['answer']['text'];?></textarea>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!--  -->
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-black jsReviewFinishLater">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp; Finish Later
                            </button>
                            <span class="pull-right">
                                <button class="btn btn-orange jsReviewSave">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp; Save & Next
                                </button>
                            </span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Attachments -->
    <div class="row">
        <br />
        <div class="col-xs-12">
            <div class="panel panel-theme">
                <div class="panel-heading">
                    <h5 class="csF16 csB7 csW">
                        Attachment(s)
                        <span class="pull-right">
                            <i class="fa fa-plus-circle csF18 csB7 csCP" aria-hidden="true"></i>
                        </span>
                    </h5>
                </div>
                <div class="panel-body">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" name="attachment" id="jsQuestionAttachmentUpload" class="hidden" />
                        </div>
                    </div>
                    <!--  -->
                    <div class="row dn" id="jsQuestionAttachmentUploadRow">
                        <br />
                        <div class="col-sm-12">
                            <button class="btn btn-orange csF16 csB7 pull-right">
                                <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload File
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-condensed">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th class="csF16" scope="col">Attached By</th>
                                        <th class="csF16" scope="col">Filename</th>
                                        <th class="csF16" scope="col">Attached On</th>
                                        <th class="csF16" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">
                                                Mubashir Ahmed <br>
                                                (QA) [Admin Plus]
                                            </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">Attachment</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">July 15 2021, Thu</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <button class="btn btn-orange csF14">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">
                                                Mubashir Ahmed <br>
                                                (QA) [Admin Plus]
                                            </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">Attachment</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">July 15 2021, Thu</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <button class="btn btn-orange csF14">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">
                                                Mubashir Ahmed <br>
                                                (QA) [Admin Plus]
                                            </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">Attachment</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="csF14 csB7">July 15 2021, Thu</p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <button class="btn btn-orange csF14">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>