<!-- Questions -->
<div class="row tab-pane <?= $this->input->get("tab", true) == "questions" ? "active" : ""; ?>" id="tab-questions"
    role="tabpanel">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="panel-heading-text text-medium">
                    <strong>
                        <i class="fa fa-question-circle text-orange"></i>
                        Questions
                    </strong>
                </h1>
            </div>
        </div>
        <div class="panel-body">
            <?php if ($report["question_answers"]) { ?>
                <?php foreach ($report["question_answers"] as $item) { ?>
                    <?php if (strpos($item['question'], 'Department Safety Condition exists') !== false || strpos($item['question'], "Original Date of Non-Compliance?") !== false) { ?>
                        <div class="table-responsive">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b><?php echo $item['question']; ?></b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                $ans = @unserialize($item['answer']);
                                                if ($ans !== false) {
                                                    echo implode(', ', $ans);
                                                } else {
                                                    echo ucfirst($item['answer']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } else if ($report['answers_json']) { ?>

                    <?php
                    $decodedJSON = json_decode(
                        $report["answers_json"],
                        true
                    );

                    $allowedOnes = empty($decodedJSON['departments']) ? [] : $decodedJSON['departments'];
                    $compliance_date = empty($decodedJSON['compliance_date']) ? '' : $decodedJSON['compliance_date'];
                    ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="auto-height">Department Safety Condition exists &nbsp; : <span class="required"
                                        aria-required="true"></span></label>
                                <div class="hr-select-dropdown">
                                    <select id="jsDepartments" name="departments[]" class="form-control" multiple>
                                        <option value="">-- Please Select --</option>
                                    <?php foreach ($departments as $v) { ?>
                                            <option value="<?= $v['sid']; ?>" <?= in_array($v['sid'], $allowedOnes) ? 'selected' : ''; ?>><?= $v['name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="auto-height">Original Date of Non-Compliance : <span class="required required_0"
                                        aria-required="true"></span></label>
                                <input id="jsComplianceDate" type="text" name="compliance_date" value="<?= $compliance_date; ?>"
                                    data-require="0" data-attr="0" class="form-control start_date related_0"
                                    aria-invalid="false" autocomplete="off" readonly="">
                            </div>
                        </div>
                    </div>
            <?php } ?>

            <?php if ($report['answers_json']): ?>
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsReportProgressQuestionBtn">
                        <i class="fa fa-save"></i>
                        Save Questions
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>