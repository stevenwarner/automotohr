<?php if ($userCompletedStateForms) { ?>
    <div class="row">
        <div class="col-xs-12">
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#user_not_completed_state_forms">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                            State Forms
                            <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($userCompletedStateForms); ?></b></div>
                        </a>

                    </h4>
                </div>

                <div id="user_not_completed_state_forms" class="panel-collapse collapse in">
                    <div class="table-responsive full-width">
                        <table class="table table-plane">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th class="col-lg-8">Document Name</th>
                                    <th class="col-lg-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userCompletedStateForms as $v0) { ?>
                                    <tr data-id="<?= $v0["sid"]; ?>">
                                        <td class="vam">
                                            <p><?= $v0["title"]; ?></p>
                                            <p>Assigned on: <?= formatDateToDB(
                                                                $v0["assigned_at"],
                                                                DB_DATE_WITH_TIME,
                                                                DATE_WITH_TIME
                                                            ); ?></p>
                                        </td>
                                        <td class="vam text-right">
                                            <button class="btn btn-success">
                                                View Form
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>