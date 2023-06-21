<?php
$fields = [
    [
        'title' => 'SSN',
        'id' => 'ssn',
        'db_slug' => 'ssn',
        'secret' => false,
        'required' => 'required',
        'placeholder' => 'XX-XXX-XXX'
    ], [
        'title' => 'First Name',
        'id' => 'firstName',
        'db_slug' => 'first_name',
        'required' => 'required',
        'placeholder' => 'e.g. John'
    ],
    [
        'title' => 'Middle Initial',
        'id' => 'middleInitial',
        'db_slug' => 'middle_initial',
        'placeholder' => 'e.g. H'
    ],
    [
        'title' => 'Last Name',
        'id' => 'lastName',
        'db_slug' => 'last_name',
        'required' => 'required',
        'placeholder' => 'e.g. Doe'
    ],
    [
        'title' => 'Email',
        'type' => 'email',
        'id' => 'email',
        'db_slug' => 'email',
        'required' => 'required',
        'placeholder' => 'e.g. John.Doe@example.com'
    ],
    [
        'title' => 'Title',
        'id' => 'title',
        'db_slug' => 'title',
        'required' => 'required',
        'placeholder' => 'e.g. Business Man'
    ],
    [
        'title' => 'Birthday',
        'id' => 'birthDay',
        'db_slug' => 'birthday',
        'secret' => true,
        'required' => 'required',
        'placeholder' => 'e.g. XXXX-XX-XX'
    ],
    [
        'title' => 'Phone',
        'id' => 'phone',
        'db_slug' => 'phone',
        'required' => 'required',
        'placeholder' => 'e.g. XXX-XXX-XXXX'
    ],
    [
        'title' => 'Street 1',
        'id' => 'street1',
        'db_slug' => 'street_1',
        'required' => 'required',
        'placeholder' => 'e.g. PO BOX 123'
    ],
    [
        'title' => 'Street 2',
        'id' => 'street2',
        'db_slug' => 'street_2',
    ], [
        'title' => 'State',
        'id' => 'state',
        'db_slug' => 'state',
        'required' => 'required',
        'placeholder' => 'e.g. CA'
    ], [
        'title' => 'City',
        'id' => 'city',
        'db_slug' => 'city',
        'required' => 'required',
        'placeholder' => 'e.g. San francisco'
    ], [
        'title' => 'Zip',
        'id' => 'zip',
        'db_slug' => 'zip',
        'required' => 'required',
        'placeholder' => 'e.g. XXXXX'
    ]
];
?>
<div class="container">
    <!-- View -->
    <div class="jsSection" data-key="view">
        <?php if (!$signatories) { ?>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-success jsAddSignatory">Add Signatory</button>
                </div>
            </div>
        <?php }else{ ?>

            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-success jsEditSignatory">Edit Signatory</button>
                </div>
            </div>

            <?php } ?>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <caption></caption>
                        <tbody>
                            <?php if (!$signatories) : ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="alert alert-info text-center">
                                            <strong>
                                                No signatories found yet!
                                            </strong>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($signatories as $signatory) : ?>
                                    <tr>
                                        <th scope="col" class="col-md-3">Gusto UUID</th>
                                        <td><?= $signatory['gusto_uuid']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Version</th>
                                        <td><?= $signatory['version']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Identity Verification Status</th>
                                        <td><?= $signatory['identity_verification_status']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">First Name</th>
                                        <td><?= $signatory['first_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Middle Initial</th>
                                        <td><?= $signatory['middle_initial']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Last Name</th>
                                        <td><?= $signatory['last_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Title</th>
                                        <td><?= $signatory['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Email</th>
                                        <td><?= $signatory['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Phone</th>
                                        <td><?= $signatory['phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Date Of Birth</th>
                                        <td><?= _secret(formatDateToDB($signatory['birthday'], DB_DATE, DATE), true); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Social Security Number (SSN)</th>
                                        <td><?= $signatory['ssn'] ? _secret($signatory['ssn']) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Street 1</th>
                                        <td><?= $signatory['street_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Street 2</th>
                                        <td><?= $signatory['street_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">State</th>
                                        <td><?= $signatory['state']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">City</th>
                                        <td><?= $signatory['city']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Zip</th>
                                        <td><?= $signatory['zip']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Created At</th>
                                        <td><?= formatDateToDB($signatory['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="col-md-3">Updated At</th>
                                        <td><?= formatDateToDB($signatory['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$signatories) { ?>
        <!-- Add -->
        <div class="jsSection dn" data-key="add">
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-success jsSignatoriesView" type="button">View Signatories</button>
                </div>
            </div>
            <!--  -->

            <?php $companyUsersData = getActiveEmployees($companySid); ?>
            <?php if (!empty($companyUsersData)) { ?>
                <div class="row">
                    <div class="form-group col-sm-6 text-left;">
                        <label><strong style="font-size: 18px;">Employees</strong> <span class="text-danger">(Please select an employee to fill the form data)</span></label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <select name='activeemployees' id="adminesignatories">
                            <option value="">Please select an employee to fill the form</option>
                            <?php
                            foreach ($companyUsersData as $rowData) {
                                $stateCode = '';
                                $dob = '';
                                if ($rowData['Location_State'] != '' && $rowData['Location_State'] != null) {
                                    $stateCode = db_get_state_code_only($rowData['Location_State']);
                                }

                                if ($rowData['dob'] != '' && $rowData['dob'] != null) {
                                    $dob = date('m/d/Y', strtotime($rowData['dob']));
                                }
                            ?>
                                <option value="<?php echo $rowData['first_name']; ?>#<?php echo $rowData['last_name']; ?>#<?php echo $rowData['email']; ?>#<?php echo $rowData['middle_name']; ?>#<?php echo $rowData['ssn']; ?>#<?php echo $rowData['job_title']; ?>#<?php echo $dob; ?>#<?php echo $rowData['PhoneNumber']; ?>#<?php echo $rowData['Location_Address']; ?>#<?php echo $rowData['Location_Address_2']; ?>#<?php echo $rowData['Location_ZipCode']; ?>#<?php echo $rowData['Location_City']; ?>#<?php echo $stateCode; ?>"><?php echo remakeEmployeeName($rowData); ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
                <hr>
            <?php } ?>


            <div class="row">
                <div class="col-sm-12">
                    <form action="javascript:void(0)">
                        <?php foreach ($fields as $field) {
                            $slug = 'jsSignatoryAdd' . (ucfirst($field['id']));
                        ?>
                            <!--  -->
                            <div class="form-group">
                                <label>
                                    <?= $field['title']; ?>
                                    <?= $field['required'] ? '<strong class="text-danger">*</strong>' : ''; ?>
                                </label>
                                <input type="<?= $field['type'] ?? 'text'; ?>" class="form-control" id="<?= $slug; ?>" name="<?= $slug; ?>" <?= $field['required'] ?? '' ?> placeholder="<?= $field['placeholder'] ?? '' ?>" />
                            </div>
                        <?php
                        }
                        ?>
                        <hr>
                        <!--  -->
                        <div class="form-group">
                            <button class="btn btn-success jsAddSignatorySaveBtn" type="submit">Save Signatory</button>
                            <button class="btn btn-cancel csW jsSignatoriesView" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($signatories) { ?>
        <!-- Edit -->
        <div class="jsSection dn" data-key="edit">
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-success jsSignatoriesView" type="button">View Signatories</button>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="javascript:void(0)">
                        <?php foreach ($fields as $field) {
                            $slug = 'jsSignatoryEdit' . (ucfirst($field['id']));
                            //
                            $value = isset($field['secret'])
                                ? _secret($signatories[0][$field['db_slug']], $field['secret'])
                                : $signatories[0][$field['db_slug']];
                            //
                            if ($field['db_slug'] == 'birthday') {
                                $value = _secret(
                                    formatDateToDB($signatories[0][$field['db_slug']], DB_DATE, SITE_DATE),
                                    $field['secret']
                                );
                            }
                        ?>
                            <!--  -->
                            <div class="form-group">
                                <label>
                                    <?= $field['title']; ?>
                                    <?= $field['required'] ? '<strong class="text-danger">*</strong>' : ''; ?>
                                </label>
                                <input <?= $field['id'] == 'email' ? 'disabled' : ''; ?> type="<?= $field['type'] ?? 'text'; ?>" class="form-control" id="<?= $slug; ?>" name="<?= $slug; ?>" <?= $field['required'] ?? '' ?> placeholder="<?= $field['placeholder'] ?? '' ?>" value="<?= $value; ?>" />
                            </div>
                        <?php
                        }
                        ?>
                        <hr>
                        <!--  -->
                        <div class="form-group">
                            <input type="hidden" id="jsSignatoryEditId" name="jsSignatoryEditId" value="<?= $signatories[0]['sid']; ?>" />
                            <button class="btn btn-success jsEditSignatorySaveBtn" type="submit">Update Signatory</button>
                            <button class="btn btn-cancel csW jsSignatoriesView" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    $('#adminesignatories').select2();
</script>