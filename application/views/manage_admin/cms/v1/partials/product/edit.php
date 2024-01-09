<div class="container">
    <br />
    <!-- Banner  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Edit a product section
                </strong>
            </h4>
        </div>
        <form id="jsEditHomeProduct" action="javascript:void(0)">
            <div class="panel-body">
                <p class="text-danger">
                    <strong>
                        <em>
                            To make word prominent use "##" before and after the word.
                        </em>
                    </strong>
                    <hr />
                </p>
                <div class="form-group">
                    <label>
                        Main Heading
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="mainHeading" name="mainHeading" value="<?= $data["mainHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Details
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <textarea id="details" name="details" rows="5" class="form-control"><?= $data["details"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Direction
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <select name="direction" id="direction" class="form-control">
                        <option value="left_to_right" <?= $data["direction"] == "left_to_right" ? "selected" : ""; ?>>Left To Right</option>
                        <option value="right_to_left" <?= $data["direction"] == "right_to_left" ? "selected" : ""; ?>>Right To Left</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Source
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" id="sourceFileEdit" accept="image/*, video/mp4, video/mov" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsUpdateProductBtn" type="submit">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save
                </button>
            </div>
        </form>
    </div>
</div>