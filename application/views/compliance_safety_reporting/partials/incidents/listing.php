<?php if (isMainAllowedForCSP()) : ?>
    <!-- Documents -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Incidents</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <select name="jsReportIncidentType" id="jsReportIncidentType">
                            <option value="0">[Select Incident]</option>
                            <?php if ($incidentTypes): ?>
                                <?php foreach ($incidentTypes as $item): ?>
                                    <option value="<?= $item["id"]; ?>"><?= $item["compliance_incident_type_name"]; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-orange jsAddIncident" type="button">
                <i class="fa fa-plus"></i>
                Add Incident
            </button>
        </div>
    </div>
<?php endif; ?>

<?php
$firstSegment = $this->uri->segment(1);
?>


<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Manage Incidents</strong>
        </h1>
    </div>
    <div class="panel-body">
        <?php if ($report["incidents"]) : ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="bg-black" scope="col">Incident</th>
                            <th class="bg-black" scope="col">Last Modified</th>
                            <th class="bg-black" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report["incidents"] as $item) : ?>
                            <tr data-id="<?= $item["sid"]; ?>">
                                <td style="vertical-align: middle;">
                                    <?= $item["compliance_incident_type_name"]; ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?= remakeEmployeeName($item); ?>
                                    <br>
                                    <?= formatDateToDB($item['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <a target="_blank" href="<?= base_url("{$firstSegment}/report/{$report["sid"]}/incident/edit/" . $item["sid"]); ?>" class="btn btn-orange">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php if (isMainAllowedForCSP()) : ?>
                                        <button type="button" class="btn btn-red jsDeleteReportIncident">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    <?php endif; ?>

                                    <a class="btn btn-black" target="_blank" href="<?= base_url("compliance_safety_reporting/download_incident/" . $report["sid"]. '/' .$item["sid"]); ?>">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class=" alert alert-info text-center">
                No incidents found.
            </div>
        <?php endif; ?>
    </div>
</div>