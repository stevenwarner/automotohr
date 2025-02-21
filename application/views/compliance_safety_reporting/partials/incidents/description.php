<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Description</strong>
        </h1>
    </div>
    <div class="panel-body jsDescriptionBody">
        <?= $report["description"]; ?>
    </div>
</div>


<script>
    descriptionFieldsObj = <?= $report["fields_json"] ? $report["fields_json"] : "{}"; ?>;
</script>