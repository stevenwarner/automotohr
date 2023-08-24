<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Documents</strong>
        </h3>
    </div>
    <div class="panel-body">
        <?php if (!$documents) : ?>
            <div class="alert alert-info text-center">
                <p class="csF16">
                    <strong>
                        No documents found.
                    </strong>
                </p>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Name</th>
                            <th scope="col">Requires <br />Signing</th>
                            <th scope="col">Draft?</th>
                            <th scope="col">Actions</th>
                        </tr>
                    <tbody>
                        <?php foreach ($documents as $document) : ?>
                            <tr data-id="<?= $document['sid']; ?>">
                                <td class="vam">
                                    <p class="csF16">
                                        <?= $document['title']; ?>
                                    </p>
                                </td>
                                <td class="vam">
                                    <p class="csF16">
                                        <?= $document['name']; ?>
                                    </p>
                                </td>
                                <td class="vam">
                                    <p class="csF16">
                                        <?= $document['requires_signing'] ? "Yes" : "No"; ?>
                                    </p>
                                </td>
                                <td class="vam">
                                    <p class="csF16">
                                        <?= $document['draft'] ? "Yes" : "No"; ?>
                                    </p>
                                </td>
                                <td class="vam">
                                    <button class="btn csF16 csW csBG3 jsS jsContractorSingleForm">
                                        <i class="fa fa-arrow-right csF16"></i>
                                        &nbsp;Sign
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>