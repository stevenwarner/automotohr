<div class="form-group">
    <label class="text-medium">
        Select Issue
        <strong class="text-danger">*</strong>
    </label>
    <select id="jsNewItemSelect" class="form-control">
        <?php if (isset($records) && !empty($records)): ?>
            <?php foreach ($records as $k0 => $record): ?>
                <optgroup label="<?= $record["title"]; ?>">
                    <?php foreach ($record["issues"] as $k1 => $issue): ?>
                        <option value="<?= $issue["id"]; ?>" data-incidentId="<?= $issue["incident_id"]; ?>"
                            data-issueId="<?= $issue["id"]; ?>" data-level="<?= $issue["level"]; ?>"
                            data-bg="<?= $issue["bg_color"]; ?>" data-txt="<?= $issue["txt_color"]; ?>">
                            <?= $issue["name"]; ?>
                            (Severity Level <?= $issue["level"]; ?>)
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div id="jsAddIssueBox"></div>