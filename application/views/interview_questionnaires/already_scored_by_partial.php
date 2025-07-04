<?php if(!empty($candidate_scores)) { ?>
<div class="hr-box">
    <div class="hr-box-header">
        <strong>Applicant Score Card</strong>
    </div>    

    <div class="table-responsive hr-innerpadding">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="col-xs-3">Scored By</th>
                    <th class="text-center">Candidate Score</th>
<!--                    <th class="text-center">Job Relevancy Score</th>-->
                    <th class="text-center">Overall Assessment</th>
<!--                    <th class="text-center">Rating</th>-->
                </tr>
            </thead>
            <tbody>
                <?php foreach($candidate_scores as $score) { ?>
                    <tr>
                        <td>
                            <p style="font-style: normal;"><?php echo ucwords($score['scored_by']['first_name'] . ' ' . $score['scored_by']['last_name'])?></p>
                            <p style="font-style: normal;"><small><?php echo $score['scored_by']['job_title']; ?></small></p>
                            <span>
                                <?php if($is_already_scored == 1) { ?>
                                    <?php if($logged_in_employer_sid == $score['employer_sid'] && $logged_in_employer_sid == $employer_sid) {?>
                                        ( <a href="javascript:enable_editing();">Edit</a> )
                                    <?php } else { ?>
                                        <!--( <a href="<?php /*echo base_url('interview_questionnaires/' . $score['candidate_type'] . '/' . $score['candidate_sid'] . '/' . $score['job_sid'] . '/' . $score['employer_sid'])*/?>">View</a> )-->
                                        ( <a href="<?php echo base_url('interview_questionnaire/launch_interview/' . $score['candidate_sid'] . '/' . $score['questionnaire_sid'] . '/' . $score['employer_sid']); ?>">View</a> )
                                    <?php } ?>
                                <?php } else { ?>
                                    ( <a href="<?php echo base_url('interview_questionnaire/launch_interview/' . $score['candidate_sid'] . '/' . $score['questionnaire_sid'] . '/' . $score['employer_sid']); ?>">View</a> )
                                <?php } ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <p style="font-style: normal;"><?php echo $score['candidate_score']; ?></p>
                            <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>
                        </td>
<!--                        <td class="text-center">-->
<!--                            <p style="font-style: normal;">--><?php //echo $score['job_relevancy_score']; ?><!--</p>-->
<!--                            <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>-->
<!--                        </td>-->
                        <td class="text-center">
                            <p style="font-style: normal;">
                                <?php echo $score['candidate_overall_score'] * 10; ?>
<!--                                --><?php //echo (($score['candidate_overall_score'] + $score['job_relevancy_overall_score']) * 10) / 2; ?>
                            </p>
                            <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>
                            <div class="start-rating center-align" style="font-style: normal;">
                                <input name="star_rating" id="star_rating" value="<?php echo $score['star_rating']; ?>" type="number" class="rating" min="0" max="5" step="0.5" data-size="xs" readonly="readonly"/>
                            </div>
                        </td>
<!--                        <td class="text-center">-->
<!--                            <div class="start-rating center-align" style="font-style: normal;">-->
<!--                                <input name="star_rating" id="star_rating" value="--><?php //echo $score['star_rating']; ?><!--" type="number" class="rating" min="0" max="5" step="0.5" data-size="xs" readonly="readonly"/>-->
<!--                            </div>-->
<!--                        </td>-->
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th>
                    <p style="font-style: normal;">Average Score</p>
                </th>
                <th class="text-center">
                    <p style="font-style: normal;"><?php echo $candidate_average_score; ?></p>
                    <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>
                </th>
<!--                <th class="text-center">-->
<!--                    <p style="font-style: normal;">--><?php //echo $job_relevancy_average_score; ?><!--</p>-->
<!--                    <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>-->
<!--                </th>-->
                <th class="text-center">
                    <p style="font-style: normal;">
                        <?php echo $overall_average_score; ?>
                    </p>
                    <p style="font-style: normal;" class="text-success"><small>( Out of 100 )</small></p>
                    <div class="start-rating center-align" style="font-style: normal;">
                        <input name="star_rating" id="star_rating" value="<?php echo $average_star_rating; ?>" type="number" class="rating" min="0" max="5" step="0.5" data-size="xs" readonly="readonly"/>
                    </div>
                </th>
<!--                <th class="text-center">-->
<!--                    <div class="start-rating center-align" style="font-style: normal;">-->
<!--                        <input name="star_rating" id="star_rating" value="--><?php //echo $average_star_rating; ?><!--" type="number" class="rating" min="0" max="5" step="0.5" data-size="xs" readonly="readonly"/>-->
<!--                    </div>-->
<!--                </th>-->
            </tfoot>
        </table>
    </div>

</div>
<?php } ?>


