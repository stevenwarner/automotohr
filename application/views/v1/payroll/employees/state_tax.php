<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong> State Tax</strong>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (!$record) { ?>
            <p class="csF16">
                Please complete the first steps.
            </p>
        <?php } else { ?>
            <br>
            <form action="javascript:void(0)">

                <?php $questionsArray = json_decode($record['questions_json'], true);
                foreach ($questionsArray as $question) { ?>

                    <!--  -->
                    <div class="form-group">
                        <label class="csF16"><?= $question['label']; ?><strong class="text-danger">*</strong></label>
                        <p class="text-danger">
                            <strong>
                                <em>
                                    <?= $question['description']; ?>
                                </em>
                            </strong>
                        </p>
                        <?php if ($question['input_question_format']['type'] === 'Select') { ?>
                            <select class="form-control input-lg jsEmployeeFlowStateTax" name="<?= $question['key']; ?>">
                                <?php foreach ($question['input_question_format']['options'] as $option) {
                                    $option['value'] = $option['value'] === true ? 'yes' : $option['value'];
                                    $option['value'] = $option['value'] === false ? 'no' : $option['value'];
                                    $question['answers'][0]['value'] = $question['answers'][0]['value'] === true ? 'yes' : $question['answers'][0]['value'];
                                    $question['answers'][0]['value'] = $question['answers'][0]['value'] === false ? 'no' : $question['answers'][0]['value'];
                                ?>
                                    <option <?= $question['answers'][0]['value'] && $question['answers'][0]['value'] == $option['value'] ? 'selected' : ''; ?> value="<?= $option['value']; ?>"><?= $option['label']; ?></option>
                                <?php } ?>
                            </select>
                        <?php } elseif ($question['input_question_format']['type'] === 'Number') { ?>
                            <input type="number" name="<?= $question['key']; ?>" class="form-control input-lg jsEmployeeFlowStateTax" value="<?= $question['answers'][0]['value'] ?? 0; ?>" />
                        <?php } elseif ($question['input_question_format']['type'] === 'Currency') { ?>
                            <input type="number" name="<?= $question['key']; ?>" class="form-control input-lg jsEmployeeFlowStateTax" value="<?= $question['answers'][0]['value'] ?? ''; ?>" />
                        <?php } ?>
                    </div>
                <?php } ?>

            </form>
        <?php
        }
        ?>
    </div>
    <?php if ($record) { ?>
        <div class="panel-footer text-right">
            <button class="btn csBG3 csF16 jsEmployeeFlowSaveStateTaxBtn">
                <i class="fa fa-save csF16"></i>
                <span>Save & continue</span>
            </button>
        </div>
    <?php } ?>
</div>