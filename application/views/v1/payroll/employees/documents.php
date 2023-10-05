<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Forms</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin: 0;">
            <strong>Missing Requirements</strong>
        </h4>
        <p class="csF16">
            Please complete the following steps in order to continue.
        </p>
        <br>
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Form<br>Title</th>
                        <th scope="col">Form<br>Name</th>
                        <th scope="col">Require<br>Signing</th>
                        <th scope="col">Draft</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($documents) {
                        foreach ($documents as $form) { ?>
                            <tr data-id="<?= $form['sid']; ?>" data-did="<?= $form['document_sid']; ?>" data-type="<?= $form['form_name'] === 'employee_direct_deposit' ? 'direct_deposit' : 'w4' ?>">
                                <td class="vam">
                                    <strong>
                                        <?= $form['form_title']; ?>
                                    </strong>
                                </td>
                                <td class="vam">
                                    <?= $form['form_name']; ?>
                                </td>
                                <td class="vam">
                                    <?= $form['requires_signing'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam">
                                    <?= $form['draft'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam">
                                    <?php if ($form['status'] == 'pending') { ?>
                                        <button class="btn csBG3 csW csF16 jsAssignDocument">
                                            Assign
                                        </button>
                                    <?php } elseif ($form['status'] == 'assign') { ?>
                                        <button class="btn btn-danger csF16 jsRevokeDocument">
                                            Revoke
                                        </button>
                                    <?php } elseif ($form['status'] == 'revoke') { ?>
                                        <button class="btn btn-warning csF16 jsAssignDocument">
                                            Re-Assign
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6">
                                <div class="alert alert-info text-center">
                                    <strong>
                                        No forms found!
                                    </strong>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>