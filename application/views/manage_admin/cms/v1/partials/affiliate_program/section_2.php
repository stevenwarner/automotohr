<?php
$ariaExpanded = $this->input->get("page") == 'section_2' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_2' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_2"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection2" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Form
            </strong>
        </h4>
    </div>
    <div id="jsSection2" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection2Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="subHeading" value="<?= $contentToShow["subHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Form heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="formHeading" value="<?= $contentToShow["formHeading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="terms"><?= $contentToShow["terms"]; ?></textarea>
                </div>
                <div class="form-group">
                    <label>
                        Button Text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="<?= $contentToShow["buttonText"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Button Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLink" value="<?= $contentToShow["buttonLink"]; ?>" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection2Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>