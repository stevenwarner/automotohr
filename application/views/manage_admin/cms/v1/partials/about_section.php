<?php
$ariaExpanded = $this->input->get("page") == 'about_section' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'about_section' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["about"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsAboutSection" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                INNOVATING HR
            </strong>
        </h4>
    </div>
    <div id="jsAboutSection" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsAboutSectionForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="mainHeading" id="mainHeading" class="form-control" placeholder="INNOVATING HR" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="subHeading" id="subHeading" class="form-control" placeholder="HR Practices Redefined & Elevated by Innovation" value="<?= $contentToShow["subHeading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Our Mission&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" name="ourMission" id="ourMission" class="form-control"><?= $contentToShow["ourMission"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Our Vision&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" name="ourVision" id="ourVision" class="form-control"><?= $contentToShow["ourVision"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Notable Benefits&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="notableBenefitHeading1" id="notableBenefitHeading1" class="form-control" placeholder="heading" value="<?= $contentToShow["notableBenefitHeading1"]; ?>" />
                    <br>
                    <textarea rows="5" name="notableBenefitDetail1" id="notableBenefitDetail1" placeholder="details" class="form-control"><?= $contentToShow["notableBenefitDetail1"]; ?></textarea>
                    <hr />
                    <input type="text" name="notableBenefitHeading2" id="notableBenefitHeading2" class="form-control" placeholder="heading" value="<?= $contentToShow["notableBenefitHeading2"]; ?>" />
                    <br>
                    <textarea rows="5" name="notableBenefitDetail2" id="notableBenefitDetail2" placeholder="details" class="form-control"><?= $contentToShow["notableBenefitDetail2"]; ?></textarea>
                    <hr />
                    <input type="text" name="notableBenefitHeading3" id="notableBenefitHeading3" class="form-control" placeholder="heading" value="<?= $contentToShow["notableBenefitHeading3"]; ?>" />
                    <br>
                    <textarea rows="5" name="notableBenefitDetail3" id="notableBenefitDetail3" placeholder="details" class="form-control"><?= $contentToShow["notableBenefitDetail3"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsAboutSectionFile" class="hidden" accept="image/*, video/mp4, video/mov" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsAboutSectionBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>



<script>
    const aboutObj = {
        sourceType: "<?= $contentToShow["sourceType"]; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"]; ?>",
    };
</script>