<div class="container">
    <!-- View -->
    <div class="jsSection" data-key="view">
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn btn-success jsAddSignatory">Add Signatory</button>
            </div>
        </div>
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
                                <th scope="col">Created At</th>
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
                                        <td><?= $signatory['ssn']; ?></td>
                                        <td><?= $signatory['email_address']; ?></td>
                                        <td><?= formatDateToDB($signatory['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
                    <!--  -->
                    <div class="form-group">
                        <label>First Name<strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="jsSignatoriesFirstName" name="jsSignatoriesFirstName" required />
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <label>Last Name<strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="jsSignatoriesLastName" name="jsSignatoriesLastName" required />
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <label>Email<strong class="text-danger">*</strong></label>
                        <input type="email" class="form-control" id="jsSignatoriesEmailAddress" name="jsSignatoriesEmailAddress" required />
                    </div>
                    <hr>
                    <!--  -->
                    <div class="form-group">
                        <button class="btn btn-success jsAddSignatorySaveBtn" type="submit">Save signatories</button>
                        <button class="btn btn-cancel csW jsSignatoriesView" type="button">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>