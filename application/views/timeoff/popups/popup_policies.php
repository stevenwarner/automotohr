<div class="row">
    <div class="col-sm-6">
        <label>From Company</label>
    </div>
    <div class="col-sm-6">
        <label>To Company</label>
    </div>

</div>

<div id="policyParent">
    <?php

    $toCompanyPoliciesDropdown = '<select name="tocompanypolicy" class="tocompanyselect invoice-fields" style="width: 100%;">
                            <option value="0">Add a new policy</option>';

    if (!empty($toCompanyPolicies)) {
        foreach ($toCompanyPolicies as $rowPolicyToCompany) {
            $toCompanyPoliciesDropdown .= '<option value="' . $rowPolicyToCompany['sid'] . '">' . $rowPolicyToCompany['title'] . '</option>';
        }
    }

    $toCompanyPoliciesDropdown .= '</select>';

    ?>

    <?php if (!empty($formCompanyPolicies)) {
        foreach ($formCompanyPolicies as $rowPolicy) {
    ?>
            <div class="row rowdata" data-from_policy='<?php echo $rowPolicy['sid'] ?>'>
                <div class="col-sm-6">
                    <label><?php echo $rowPolicy['title'] ?></label>
                </div>

                <div class="col-sm-6">
                    <?php echo $toCompanyPoliciesDropdown; ?>
                </div>
            </div><br>
    <?php }
    } ?>

</div>