<br>
<br>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="csF16 m0" style="padding-top: 10px;">
                        <strong>
                            Edit benefit category
                        </strong>
                    </h1>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <form action="">

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control jsName" value="<?= $category['name']; ?>" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Description</label>
                    <textarea class="form-control jsDescription" rows="10"><?= $category['description']; ?></textarea>
                </div>

            </form>
        </div>
        <div class="panel-footer text-right">
            <input type="hidden" class="jsKey" value="<?= $category['sid']; ?>" />
            <button class="btn csW csBG4 csF16 jsModalCancel">
                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn csW csBG3 csF16 jsBenefitCategoryUpdate">
                <i class="fa fa-save csF16" aria-hidden="true"></i>
                &nbsp;Save
            </button>
        </div>
    </div>
</div>