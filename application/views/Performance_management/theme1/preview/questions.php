<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Main Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Body -->
                <div class="csPageBoxBody">
                    <?php 
                    $endKey = end($Questions);
                    foreach($Questions as $k => $question): ?>
                    <div class="csFeedbackViewBox <?=$endKey == $k ? 'bbn' : '';?> csQuestionRow p10">
                        <h4 class="pa10 pb10"><strong>Question <?=$k + 1;?></strong></h4>
                        <h4 class="mb0"><strong><?=$question['title'];?></strong></h4>
                        <p><?=$question['description'];?></p>
                        <?php if($question['not_applicable'] == 1): ?>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="csNotApplicable<?=$k;?>" /> Not Applicable
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?= getQuestionBody($question); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>