<?php
$ariaExpanded = $this->input->get("page") == 'section_1' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_1' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_1"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection1" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Social
            </strong>
        </h4>
    </div>
    <div id="jsSection1" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection1Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>

                <div class=" form-group hidden">
                    <label>
                        YouTube Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="url" class="form-control" name="youtubeLink" value="<?= $contentToShow["youtubeLink"]; ?>" />
                </div>

                <div class=" form-group hidden">
                    <label>
                        Twitter Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="url" class="form-control" name="twitterLink" value="<?= $contentToShow["twitterLink"]; ?>" />
                </div>

                <div class=" form-group hidden">
                    <label>
                        Facebook Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="url" class="form-control" name="facebookLink" value="<?= $contentToShow["facebookLink"]; ?>" />
                </div>
                
                <div class=" form-group hidden">
                    <label>
                        Linkedin Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="url" class="form-control" name="linkedinLink" value="<?= $contentToShow["linkedinLink"]; ?>" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection1Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>