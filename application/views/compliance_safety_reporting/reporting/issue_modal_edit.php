<div class="panel panel-default jsAddIssuePanelRef" id="jsAddIssuePanelRef">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>
                <i class="fa fa-warning text-orange"></i>
                <span id="jsAddIssuePanelRefText"><?= $records["title"]; ?></span>
            </strong>
        </h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <label></label>
                <!-- content -->
                <?= convertCSPTags($records["description"], json_decode($records["answers_json"], true)); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="label-wrapper-outer">
                    <div class="row">
                        <div class="col-xs-10 jsSelectedPill">
                            <div data-id="<?= $severity_status[$records["severity_level_sid"]]["sid"]; ?>"
                                class="csLabelPill jsSelectedLabelPill text-center"
                                style="background-color: <?= $severity_status[$records["severity_level_sid"]]["bg_color"]; ?>; color: <?= $severity_status[$records["severity_level_sid"]]["txt_color"]; ?>;">
                                Severity Level <?= $severity_status[$records["severity_level_sid"]]["level"]; ?></div>
                        </div>
                        <div class="col-xs-2 text-left">
                            <div class="btn btn-orange show-status-box">
                                <i class="fa fa-pencil"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Selected one -->
                    <div class="lable-wrapper">
                        <div style="height:20px;">
                            <i class="fa fa-times cross"></i>
                        </div>

                        <?php if ($severity_status): ?>
                            <?php foreach ($severity_status as $v1): ?>
                                <div class="row">
                                    <div data-id="<?= $v1["sid"]; ?>" class="col-sm-12 label applicant csLabelPill"
                                        style="background-color:<?= $v1["bg_color"]; ?>; color:<?= $v1["txt_color"]; ?>;">
                                        <div class="jsSeverityLevelText">Severity Level <?= $v1["level"]; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<input type="hidden" id="jsNewItemSeverityLevel" name="severity_level" value="<?= $records["severity_level_sid"]; ?>" />
<input type="hidden" id="jsNewItemId" name="sid" value="<?= $records["sid"]; ?>" />

<style>
    .candidate-status {
        width: 100%;
        height: 50px;
    }

    .label-wrapper-outer {
        width: 100%;
        position: relative;
    }

    .candidate-status .lable-wrapper {
        top: 40px;
        border-radius: 5px;
        padding: 20px;
        width: 100%;
    }

    .lable-wrapper {
        width: 100%;
        display: none;
        background-color: white;
        padding: 20px;
        padding-top: 0;
        box-shadow: 0px 0px 6px #888888;
        right: 0;
        position: absolute;
        top: 30px;
        z-index: 999;
    }

    .label.csLabelPill {
        display: block !important;
    }

    .csLabelPill {
        font-family: arial;
        font-weight: bold;
        padding: 6px;
        font-size: 13px;
        margin-bottom: 3px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .candidate-status .label {
        height: 30px;
        line-height: 24px;
        font-style: italic;
        font-size: 13px;
        font-weight: 600;
    }

    .candidate-status .fa.fa-times.cross {
        background-color: #000;
        border-radius: 100%;
        color: #fff;
        font-size: 9px;
        height: 20px;
        line-height: 19px;
        padding: 0;
        position: absolute;
        right: 20px;
        text-align: center;
        top: 10px;
        width: 20px;
    }
</style>