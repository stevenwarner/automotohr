<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <p class="text-left">
                    <strong><em>To successfully onboard a company, complete the following pending steps.</em></strong>
                </p>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Step</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php foreach ($steps as $step) : ?>
                <tr>
                    <td>
                        <strong><?= $step['title']; ?></strong>
                    </td>
                    <td class="text-<?= $step['completed'] == 0 ? 'danger' : 'success'; ?>"><?= $step['completed'] == 0 ? 'PENDING' : 'COMPLETED'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>