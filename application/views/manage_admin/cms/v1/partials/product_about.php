<?php
$ariaExpanded = $this->input->get("page") == 'about' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'about' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["about"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsProductAbout" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                About section
            </strong>
        </h4>
    </div>
    <div id="jsProductAbout" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsProductAboutForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="mainHeading" id="mainHeading" class="form-control" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="subHeading" id="subHeading" class="form-control" value="<?= $contentToShow["subHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="heading" id="heading" class="form-control" value="<?= $contentToShow["heading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" name="details" id="details" class="form-control"><?= $contentToShow["details"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsProductAboutFile" class="hidden" accept="image/*, video/mp4, video/mov" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsProductAboutBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const aboutObj = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? ""; ?>"
    };
</script>