<?php
$ariaExpanded = $this->input->get("page") == 'section_6' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_6' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_6"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection6" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Section 6
            </strong>
        </h4>
    </div>
    <div id="jsSection6" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection6Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="<?= $contentToShow["buttonText"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLink" value="<?= $contentToShow["buttonLink"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Phone number&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="phoneNumber" value="<?= $contentToShow["phoneNumber"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Email&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="email" class="form-control" name="emailAddress" value="<?= $contentToShow["emailAddress"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection6File" class="hidden" accept="image/*" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection6Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section6 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>