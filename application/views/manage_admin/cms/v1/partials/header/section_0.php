<?php
$ariaExpanded = $this->input->get("page") == 'section_0' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_0' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_0"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection0" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Logo
            </strong>
        </h4>
    </div>
    <div id="jsSection0" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection0Form">
            <div class="panel-body">
                <div class=" form-group">
                    <label>
                        Logo&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection0File" class="hidden" accept="image/*" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection0Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section0 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>