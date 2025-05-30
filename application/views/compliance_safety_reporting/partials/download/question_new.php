<!-- Question section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="2">
                <strong>Question Answer</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $decodedJSON = json_decode(
            $report["question_answer_json"],
            true
        );
        //
        $report_to_dashboard = empty($decodedJSON['report_to_dashboard']) ? 'no' : $decodedJSON['report_to_dashboard'];
        $ongoing_issue = empty($decodedJSON['ongoing_issue']) ? 'no' : $decodedJSON['ongoing_issue'];
        $reported_by = empty($decodedJSON['reported_by']) ? 'no' : $decodedJSON['reported_by'];
        $category_of_issue = empty($decodedJSON['category_of_issue']) ? '' : $decodedJSON['category_of_issue'];
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label>Report to Dashboard : <span class="required"
                            aria-required="true"></span></label>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                Yes<input type="radio" class="jsReportToDashboard"
                                    name="report_to_dashboard" value="yes" <?= $isMainAllowedForCSP ? "" : "disabled"; ?> aria-autocomplete="" style="position: relative;"
                                    <?php echo $report_to_dashboard == 'yes' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                No<input type="radio" class="jsReportToDashboard"
                                    name="report_to_dashboard" value="no" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  style="position: relative;"
                                    <?php echo $report_to_dashboard == 'no' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label>Is this a Repeat or Ongoing Issue? <span class="required"
                            aria-required="true"></span></label>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                Yes<input type="radio" class="jsOngoingIssue" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  name="ongoing_issue"
                                    value="yes" style="position: relative;" <?php echo $ongoing_issue == 'yes' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                No<input type="radio" class="jsOngoingIssue" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  name="ongoing_issue"
                                    value="no" style="position: relative;" <?php echo $ongoing_issue == 'no' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label>Was this reported by an employee?: <span class="required"
                            aria-required="true"></span></label>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                Yes<input type="radio" class="jsReportedBy" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  name="reported_by"
                                    value="yes" style="position: relative;" <?php echo $reported_by == 'yes' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                No<input type="radio" class="jsReportedBy" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  name="reported_by" value="no"
                                    style="position: relative;" <?php echo $reported_by == 'no' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label>Category of issue: <span class="required"
                            aria-required="true"></span></label>
                    <input id="jsCategoryOfIssue" type="text" <?= $isMainAllowedForCSP ? "" : "disabled"; ?>  name="category_of_issue"
                        value="<?= $category_of_issue ?>" class="form-control">
                </div>
            </div>
        </div> 
        <?php if ($questions) { ?>
            <?php foreach ($questions as $question) { ?>
                <?php if (!empty($question['question'])) { ?>
                    <tr>
                        <td colspan="3">
                            <label>
                                <strong><?php echo 'Question: '.$question['question']; ?></strong>
                            </label>
                            <span class="value-box bg-gray">
                                <?php
                                    $ans = @unserialize($question['answer']);
                                    if ($ans !== false) {
                                        echo implode(', ', $ans);
                                    } else {
                                        echo ucfirst($question['answer']);
                                    }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } else { ?> 
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Questions Found</h2>
                    </div>
                </td>
            </tr> 
        <?php } ?>  
    </tbody>
</table>
<!-- Question section End -->