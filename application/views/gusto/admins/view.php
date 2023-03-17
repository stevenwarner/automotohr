<div class="container">
    <!-- View -->
    <div class="jsSection" data-key="view">
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn btn-success jsAddAdmin">Add Admin</button>
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
                                <th scope="col">Gusto UUID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$admins) : ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="alert alert-info text-center">
                                            <strong>
                                                No admins found yet!
                                            </strong>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <tr>
                                        <td><?= $admin['gusto_uuid']; ?></td>
                                        <td><?= $admin['first_name']; ?></td>
                                        <td><?= $admin['last_name']; ?></td>
                                        <td><?= $admin['email_address']; ?></td>
                                        <td><?= formatDateToDB($admin['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
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
                <button class="btn btn-success jsAdminView" type="button">View Admins</button>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <form action="javascript:void(0)">
                    <!--  -->
                    <div class="form-group">
                        <label>First Name<strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="jsAdminFirstName" name="jsAdminFirstName" required />
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <label>Last Name<strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="jsAdminLastName" name="jsAdminLastName" required />
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <label>Email<strong class="text-danger">*</strong></label>
                        <input type="email" class="form-control" id="jsAdminEmailAddress" name="jsAdminEmailAddress" required />
                    </div>
                    <hr>
                    <!--  -->
                    <div class="form-group">
                        <button class="btn btn-success jsAddAdminSaveBtn" type="submit">Save Admin</button>
                        <button class="btn btn-cancel csW jsAdminView" type="button">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>