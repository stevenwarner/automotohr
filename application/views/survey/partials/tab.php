<!-- Surveys -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#<?= $tabData["key"]; ?>Surveys"
        aria-expanded="true">
        <h1 class="panel-heading-text text-medium">
            <strong>
                <i class="fa fa-list text-orange"></i>
                <span><?= $tabData["heading"]; ?></span>
            </strong>
        </h1>
        <p class="text-small text-red">
            <strong>
                <em>
                    <?= $tabData["subHeading"]; ?>
                </em>
            </strong>
        </p>
    </div>
    <div class="panel-body panel-collapse collapse in" id="<?= $tabData["key"]; ?>Surveys">
        <div class="alert alert-info">
            <p class="text-center text-muted">
                No surveys found.
            </p>
        </div>
    </div>
    <?php if ($tabData["pagination"]): ?>
        <div class="panel-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination" style="float: none; margin: 0;">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>