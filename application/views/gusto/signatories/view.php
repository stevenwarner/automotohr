<?php
$fields = [
    [
        'title' => 'SSN',
        'id' => 'ssn',
        'required' => 'required',
        'placeholder' => 'XX-XXX-XXX'
    ], [
        'title' => 'First Name',
        'id' => 'firstName',
        'required' => 'required',
        'placeholder' => 'e.g. John'
    ],
    [
        'title' => 'Middle Initial',
        'id' => 'middleInitial',
        'placeholder' => 'e.g. H'
    ],
    [
        'title' => 'Last Name',
        'id' => 'lastName',
        'required' => 'required',
        'placeholder' => 'e.g. Doe'
    ],
    [
        'title' => 'Email',
        'type' => 'email',
        'id' => 'email',
        'required' => 'required',
        'placeholder' => 'e.g. John.Doe@example.com'
    ],
    [
        'title' => 'Title',
        'id' => 'title',
        'required' => 'required',
        'placeholder' => 'e.g. Business Man'
    ],
    [
        'title' => 'Birthday',
        'id' => 'birthDay',
        'required' => 'required',
        'placeholder' => 'e.g. XXXX-XX-XX'
    ],
    [
        'title' => 'Phone',
        'id' => 'phone',
        'placeholder' => 'e.g. XXX-XXX-XXXX'
    ],
    [
        'title' => 'Street 1',
        'id' => 'street1',
        'required' => 'required',
        'placeholder' => 'e.g. PO BOX 123'
    ],
    [
        'title' => 'Street 2',
        'id' => 'street2',
    ], [
        'title' => 'State',
        'id' => 'state',
        'required' => 'required',
        'placeholder' => 'e.g. CA'
    ], [
        'title' => 'City',
        'id' => 'city',
        'required' => 'required',
        'placeholder' => 'e.g. San francisco'
    ], [
        'title' => 'Zip',
        'id' => 'zip',
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
        <?php } ?>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Gusto<br>UUID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Social<br>Security<br>Number</th>
                                <th scope="col">Email<br>Address</th>
                                <th scope="col">Created<br>At</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
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
                                    <?php
                                    $name = $signatory['title'];
                                    $name .= ' ' . $signatory['first_name'];
                                    if ($signatory['middle_name']) {
                                        $name .= ' ' . $signatory['middle_name'];
                                    }
                                    $name .= ' ' . $signatory['last_name'];
                                    ?>
                                    <tr>
                                        <td><?= $signatory['gusto_uuid']; ?></td>
                                        <td><?= $name; ?></td>
                                        <td><?= $signatory['ssn'] ? _secret($signatory['ssn']) : '-'; ?></td>
                                        <td><?= $signatory['email']; ?></td>
                                        <td><?= formatDateToDB($signatory['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                        <td>
                                            <button class="btn btn-success jsExpandRow" data-id="<?= $signatory['sid']; ?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-warning jsUpdateSignatory" data-id="<?= $signatory['sid']; ?>">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-danger jsDeleteSignatory" data-id="<?= $signatory['sid']; ?>">
                                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="jsExpandRowArea dn" data-id="<?= $signatory['sid']; ?>">
                                        <td colspan="6">
                                            <table class="table table-striped table-condensed table-bordered">
                                                <caption></caption>
                                                <tr>
                                                    <th scope="col" class="col-md-3">Phone</th>
                                                    <td><?= $signatory['phone']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="col" class="col-md-3">Date Of Birth</th>
                                                    <td><?= formatDateToDB($signatory['birthday'], DB_DATE, DATE); ?></td>
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
                                                    <th scope="col" class="col-md-3">Identity Verification Status</th>
                                                    <td><?= $signatory['identity_verification_status']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="col" class="col-md-3">Version</th>
                                                    <td><?= $signatory['version']; ?></td>
                                                </tr>

                                            </table>
                                        </td>
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
</div>