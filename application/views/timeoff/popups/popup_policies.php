<div class="row">
    <div class="col-sm-6">
        
        <b><span id="fromCompanyName"></span></b><br><br>
    </div>
    <div class="col-sm-6">
        <b><span id="toCompanyName"></span></b><br><br>
       
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
            <div class="row rowdata" data-from_policy='<?php echo $rowPolicy['sid'] ?>' style=" background-color: #eee;  margin-left: 3px; margin-right: 3px;">
                <div class="col-sm-6">
                    <label style="margin-top: 10px"><?php echo $rowPolicy['title'] ?></label>
                </div>

                <div class="col-sm-6" style="padding-right:0px">
                    <?php echo $toCompanyPoliciesDropdown; ?>
                </div>
            </div><br>
    <?php }
    } ?>

</div>