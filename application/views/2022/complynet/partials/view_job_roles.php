<div class="row">
    <div class="col-sm-12">
        <label>ComplyNet Job Role</label>
        <input type="text" disabled class="form-control disabled" value="<?= $complyJobRoleName; ?>">
    </div>
</div>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <caption></caption>
        <thead>
            <th scope="col">Job Title</th>
            <th scope="col">Created At</th>
            <th scope="col">Actions</th>
        </thead>
        <tbody>
            <?php if ($job_roles) {
                foreach ($job_roles as $role) {
            ?>
                    <tr data-id="<?= $role['sid']; ?>">
                        <td><strong><?= $role['job_title']; ?></strong></td>
                        <td><?= formatDateToDB($role['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                        <td>
                            <button class="btn btn-danger jsDeleteJobRole">Delete</button>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>