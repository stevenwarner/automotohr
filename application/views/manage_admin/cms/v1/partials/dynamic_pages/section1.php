<style>
    .ck-editor__editable_inline {
        min-height: 250px;
    }
</style>
<?php
$ariaExpanded = $this->input->get("page") == 'pageDetails' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'pageDetails' ? "in" : "";
?>
<!-- Page details  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection1" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Page
            </strong>
        </h4>
    </div>
    <div id="jsSection1" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection1Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>Heading</label>
                    <input type="text" class="form-control" name="heading" value="<?= $pageContent["pageDetails"]["heading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>Banner <strong class="text-danger">*</strong></label>
                    <input type="file" class="hidden" accept="image/*" id="jsSection1Banner" />
                </div>
                <div class="form-group">
                    <label>Details</label>
                    <textarea name="details" id="jsSection1Details" class="form-control" rows="5"><?= $pageContent["pageDetails"]["details"]; ?></textarea>

                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsSection1Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update page
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section1 = {
        sourceType: "<?= $pageContent["pageDetails"]["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $pageContent["pageDetails"]["sourceFile"] ?? "" ?>"
    };
</script>