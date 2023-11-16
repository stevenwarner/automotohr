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
                Main
            </strong>
        </h4>
    </div>
    <div id="jsSection0" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection0Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="subHeading" value="<?= $contentToShow["subHeading"]; ?>" />
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

                <div class="form-group">
                    <label>
                        <strong>
                            Executive Admin
                        </strong>
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeadingExecutiveAdmin" value="<?= $contentToShow["mainHeadingExecutiveAdmin"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonTextExecutiveAdmin" value="<?= $contentToShow["buttonTextExecutiveAdmin"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLinkExecutiveAdmin" value="<?= $contentToShow["buttonLinkExecutiveAdmin"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        <strong>
                            Having Trouble Logging In?
                        </strong>
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeadingContact" value="<?= $contentToShow["mainHeadingContact"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonTextContact" value="<?= $contentToShow["buttonTextContact"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLinkContact" value="<?= $contentToShow["buttonLinkContact"]; ?>" />
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