<!-- Add Notes -->
<div class="tab-pane <?= $this->input->get("tab", true) == "notes" ? "active" : ""; ?>" id="tab-notes" role="tabpanel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <i class="fa fa-plus-circle text-orange"></i>
                <strong>Add New Note</strong>
            </h1>
        </div>
        <div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="report_note_type">Type <strong class="text-danger">*</strong></label>
                            <select name="report_note_type" id="report_note_type">
                                <option value="personal">Personal Note</option>
                                <option value="employee">Employee Note</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="report_note">Note <strong class="text-danger">*</strong></label>
                            <textarea class="form-control" id="report_note" name="report_note" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsAddNote">
                    <i class="fa fa-plus-circle"></i>
                    Add Note
                </button>
            </div>
        </div>
    </div>
    <!-- Notes -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Notes</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="respond">
                <?php if (!empty($report['notes'])): ?>
                    <?php foreach ($report['notes'] as $note): ?>
                        <article>
                            <figure>
                                <img class="img-responsive" src="<?= getImageURL($note["profile_picture"]) ?>">
                            </figure>
                            <div class="text">
                                <div class="message-header">
                                    <div class="message-title">
                                        <h2>
                                            <?php
                                            if ($note['manual_email']) {
                                                echo getManualUserNameByEmailId($reportId, $incidentId, $note['manual_email']);
                                            } else {
                                                echo remakeEmployeeName($note);
                                            }
                                            ?>
                                        </h2>
                                        <p class="text-danger"><?= ucfirst($note['note_type']); ?></p>
                                    </div>
                                    <ul class="message-option">
                                        <li>
                                            <time><?= formatDateToDB($note['updated_at'], DB_DATE_WITH_TIME, DATE); ?></time>
                                        </li>
                                    </ul>
                                </div>
                                <p><?= $note["notes"]; ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        No notes found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>