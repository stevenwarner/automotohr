<?php
$ariaExpanded = $this->input->get("page") == 'section0' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section0' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section0"];
?>
<!-- section  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection0" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Sections
            </strong>
        </h4>
    </div>
    <div id="jsSection0" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="" id="jsSection0Form">
            <div class="panel-body">
                <!--  -->
                <div class="form-group">
                    <label>
                        Title
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="title" value="<?= $contentToShow["title"]; ?>" />
                </div>
                <!--  -->
                <div class="form-group">
                    <label>
                        Banner
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" id="jsSection0File" accept="image/*" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsSection0Btn">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
                <button class="btn btn-success jsAddMainSection" type="button">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    &nbsp;Add a Section
                </button>
            </div>
        </form>
    </div>
</div>


<div class="jsDraggableSection">
    <?php
    if ($contentToShow["tags"]) {
        foreach ($contentToShow["tags"] as $index => $tag) {
    ?>

        <?php
            $this->load->view(
                "manage_admin/cms/v1/partials/dynamic/dynamic_tag",
                [
                    "tagData" => $tag,
                    "tagIndex" => $index
                ]
            );
        } ?>

    <?php
    }
    ?>

</div>


<script>
    const section0 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload" ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>",
    }
</script>