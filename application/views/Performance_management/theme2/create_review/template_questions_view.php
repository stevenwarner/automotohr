<div class="container">
    <?php
    if (!empty($questions)) {
        $i = 1;
        foreach ($questions as $question) {
    ?>
            <!-- Question 1  -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-theme">
                        <div class="panel-heading" style="background-color: #81b431">
                            <div class="row">
                                <div class="col-sm-11 col-xs-12">
                                    <div class="csF16 csB7 csW">Q<?= $i; ?>: <?= $question->title; ?></div>
                                </div>
                                <div class="col-sm-1 col-xs-12">
                                    <span class="pull-right">
                                        <i class="fa fa-minus-circle csF18 csB7 jsPageBTN csCP" data-target="question_<?= $i; ?>"
                                            aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel-body jsPageBody" style="background-color: #fff;" data-page="question_<?= $i; ?>">
                            <?php
                            if (!empty($question->description)) {
                            ?>
                                <!-- Description -->
                                <div class="csF16 mb30"><?= nl2br(strip_tags($question->description)); ?></div>
                            <?php
                            }
                            ?>
                            <?php
                            if (!empty($question->video_help) && $question->video_help != 'none') {
                            ?>
                                <!-- Video Help -->
                                <video controls preload="metadata" width="50%">
                                    <source src="<?= site_url('assets/performance_management/videos/templates/' . ($id) . '/' . $question->video); ?>" type="video/webm">
                                    <track label="English" kind="captions" srclang="en" src="resources/myvideo-en.vtt" default>
                                </video>
                            <?php
                            }
                            ?>
                            <?php
                            if (in_array($question->question_type, ['text-n-rating', 'rating', 'text_rating'])) {
                            ?>
                                <!-- Rating Scale -->
                                <div class="csRatingScale mb30">
                                    <ul>
                                        <li>
                                            <p class="csF16 csB7">1</p>
                                            <p class="csF16">Strongly Agree</p>
                                        </li>
                                        <li>
                                            <p class="csF16 csB7">2</p>
                                            <p class="csF16">Agree</p>
                                        </li>
                                        <li>
                                            <p class="csF16 csB7">3</p>
                                            <p class="csF16">Neutral</p>
                                        </li>
                                        <li>
                                            <p class="csF16 csB7">4</p>
                                            <p class="csF16">Disagree</p>
                                        </li>
                                        <li>
                                            <p class="csF16 csB7">5</p>
                                            <p class="csF16">Strongly Disagree</p>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if (in_array($question->question_type, ['multiple-choice', 'multiple-choice-with-text'])) {
                            ?>
                                <!-- Multiple Choice -->
                                <div class="csMultipleChoice mb30">
                                    <label class="control control--radio">
                                        Yes
                                        <input type="radio" name="1" />
                                        <div class="control__indicator"></div>
                                    </label>&nbsp;&nbsp;
                                    <label class="control control--radio">
                                        No
                                        <input type="radio" name="2" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if (preg_match('/text/i', $question->question_type)) {
                            ?>
                                <!-- Text Box -->
                                <div class="csTextBox ma10">
                                    <p class="csF16 csB7">Feedback (Ellaborate)</p>
                                    <textarea rows="5" class="form-control"></textarea>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            $i++;
        }
    } else {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <p class="csF18 text-center">
                    No questions found.
                </p>
            </div>
        </div>
    <?php
    }
    ?>
</div>