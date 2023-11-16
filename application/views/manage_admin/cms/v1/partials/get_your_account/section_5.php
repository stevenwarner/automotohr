<?php
$ariaExpanded = $this->input->get("page") == 'section_5' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_5' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_5"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection5" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Section 5
            </strong>
        </h4>
    </div>
    <div id="jsSection5" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection5Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" id="jsSection5Details" name="details"><?= $contentToShow["details"]; ?></textarea>
                </div>
                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection5File" class="hidden" accept="image/*" />
                </div>
                <div class=" form-group">
                    <label>
                        Logo&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection5FileLogo" class="hidden" accept="image/*" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection5Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section5 = {
        file: {
            sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
        },
        logo: {
            sourceType: "<?= $contentToShow["logoType"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["logoFile"] ?? "" ?>"
        }
    };
</script>