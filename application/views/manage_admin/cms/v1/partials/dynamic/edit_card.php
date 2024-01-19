<br />
<div class="container">
    <!-- Section  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Edit a card section
                </strong>
            </h4>
        </div>
        <form action="javascript:void(0)" id="jsEditCardForm">
            <div class="panel-body">
                <div class=" form-group">
                    <label>
                        Title
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" data-target="buttonLink" name="title" value="<?= $data["title"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Description
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details"><?= $data["details"]; ?></textarea>
                </div>

                <div class=" form-group">
                    <label>
                        Button text
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="<?= $data["buttonText"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Button link
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLink" value="<?= $data["buttonLink"]; ?>" />
                </div>

                <div id="jsSortOrderSection" class="form-group hidden">
                    <label>
                        Sort Order
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="sortOrder" value="<?= $data["sortOrder"] ? $data["sortOrder"] : 0; ?>" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsEditCardBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>