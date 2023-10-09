<?php if ($payrollEmployees) { ?>
    <?php foreach ($payrollEmployees as $value) { ?>
        <?php if($regularPayroll['employees'][$value['id']]['excluded']) { continue;} ?>
        <tr class="jsRegularPayrollEmployeeRowTimeOff" data-id="<?= $value['id']; ?>">
            <td class="vam">
                <p class="csF16">
                    <strong>
                        <?= $value['name']; ?>
                    </strong>
                </p>
            </td>
            <td class="vam">
                <?php if ($regularPayroll['employees'][$value['id']]['fixed_compensations']) { ?>
                    <?php foreach ($regularPayroll['employees'][$value['id']]['fixed_compensations'] as $key => $v1) { ?>
                        <?php if (!preg_match('/time/i', $key)) {
                            continue;
                        } ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="number" name="<?=$key;?>" class="form-control jsTimeOffField" value="<?=$v1['amount'];?>" placeholder="0.0" />
                                </div>
                                
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td class="vam" colspan="2">
            <?php $this->load->view('v1/no_data', ['message' => 'No employees found.']); ?>
        </td>
    </tr>
<?php } ?>