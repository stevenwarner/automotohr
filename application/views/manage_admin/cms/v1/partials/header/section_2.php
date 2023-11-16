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
                Buttons
            </strong>
        </h4>
    </div>
    <div id="jsSection2" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection2Form">
            <div class="panel-body">
                <div class=" form-group">
                    <label>
                        Schedule
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonTextSchedule" value="<?= $contentToShow["buttonTextSchedule"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLinkSchedule" value="<?= $contentToShow["buttonLinkSchedule"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Login
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonTextLogin" value="<?= $contentToShow["buttonTextLogin"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLinkLogin" value="<?= $contentToShow["buttonLinkLogin"]; ?>" />
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