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
                            <option value="">Please Select Policy</option><option value="0">Add New Policy</option>';

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



<script>
    $(document).on("change", ".tocompanyselect", function() {
        mapPolicies();

    });


    var policylist = mapPolicies();

    function mapPolicies() {
        var employeesList = [];

        $(".tocompanyselect").each(function(index, select) {
            var toPolicyId = $(this).val();
            var fromPolicyId = $(this).closest(".rowdata").data("from_policy");

            employeesList.push({
                fromPolicyId: fromPolicyId,
                toPolicyId: toPolicyId,
            });

        });


        //
        if (employeesList.length > 0) {

            for (i = 0; i < employeesList.length; i++) {
                if (employeesList[i]['toPolicyId'] == '0') {
                    var toCompanySid='<?php echo $toCompanySid?>';
                    var fromCompanySid='<?php echo $fromCompanySid?>';

                    console.log('add');

                }

            }

        }



        //return employeesList;



        //  console.log(employeesList);

    }
</script>