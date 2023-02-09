<div class="row">
    <div class="col-sm-12">
        <p class="text-danger">
            <strong>
                <em>
                    Upon link, the system will automatically update the job titles against employees.
                </em>
            </strong>
        </p>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <label>ComplyNet Job Role</label>
        <select class="jsSelect2" disabled style="width: 100%">
            <option value=""><?= $complyJobRoleName; ?></option>
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <label>System Job Role</label>
        <p class="text-danger">You can link multiple job roles</p>
        <select class="jsSelect2Tags" multiple style="width: 100%">
            <?php
            if (!empty($systemJobRoles)) {
                foreach ($systemJobRoles as $role) {
                    $role['job_title'] = preg_replace('/[^a-z\s]/i', '', trim($role['job_title']));
            ?>
                    <option value="<?= $role['job_title']; ?>" <?= in_array($role['job_title'], $alreadyLinked) ? 'disabled' : ''; ?>><?= $role['job_title']; ?></option>
            <?php   }
            }
            ?>
        </select>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-sm-12 text-right">
        <button class="btn btn-success jsLinkJobRoleBtn">Link Job Roles</button>
    </div>
</div>